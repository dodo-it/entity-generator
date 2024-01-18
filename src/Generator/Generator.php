<?php declare (strict_types = 1);

namespace DodoIt\EntityGenerator\Generator;

use Doctrine\Inflector\Inflector;
use Doctrine\Inflector\InflectorFactory;
use DodoIt\EntityGenerator\Entity\Column;
use DodoIt\EntityGenerator\Repository\IRepository;
use Exception;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\PhpFile;
use Nette\SmartObject;
use Nette\Utils\Strings;
use ReflectionMethod;

class Generator
{

	use SmartObject;

	private IRepository $repository;

	private Config $config;

	private Inflector $inflector;

	public function __construct(IRepository $repository, Config $config)
	{
		$this->repository = $repository;
		$this->config = $config;
		$this->inflector = InflectorFactory::create()->build();
	}

	public function generate(?string $table = null, ?string $query = null): void
	{
		if ($query !== null) {
			if ($table === null) {
				throw new Exception('When using query table argument has to be provided!');
			}

			$this->repository->createViewFromQuery($table, $query);
			$this->generateEntity($table);
			$this->repository->dropView($table);

			return;
		}

		if ($table !== null) {
			$this->generateEntity($table);

			return;
		}

		$tables = $this->repository->getTables();

		foreach ($tables as $oneTable) {
			$this->generateEntity($oneTable);
		}
	}

	public function generateEntity(string $table): void
	{
		$file = new PhpFile();

		if ($this->config->addDeclareStrictTypes) {
			$file->setStrictTypes($this->config->addDeclareStrictTypes);
		}

		$namespace = $file->addNamespace($this->config->namespace);

		$shortClassName = $this->getClassName($table);
		$fqnClassName = '\\' . $this->config->namespace . '\\' . $shortClassName;
		$entity = $namespace->addClass($shortClassName);

		if ($this->config->addTrait !== null) {
			$entity->addTrait($this->config->addTrait);
		}

		$phpDocProperties = [];

		if (!$this->config->rewrite && class_exists($fqnClassName)) {
			$this->cloneEntityFromExistingEntity($entity, ClassType::from($fqnClassName)); //@phpstan-ignore-line
			$phpDocProperties = Helper::getPhpDocComments($entity->getComment() ?? '');
		}

		if ($this->config->tableConstant !== null) {
			$entity->addConstant($this->config->tableConstant, $table)->setVisibility('public');
		}

		if ($this->config->extends !== null) {
			$entity->setExtends($this->config->extends);
		}

		$columns = $this->repository->getTableColumns($table);
		$mapping = [];

		foreach ($columns as $column) {
			$this->validateColumnName($table, $column);
			$this->generateColumnConstant($entity, $column);

			if ($entity->hasProperty($column->getField()) || in_array($column->getField(), $phpDocProperties, true)) {
				continue;
			}

			$mapping[$column->getField()] = $this->inflector->classify($column->getField());
			$this->generateColumn($entity, $column);
		}

		if ($this->config->generateMapping) {
			if ($entity->hasProperty('mapping')) {
				$mapping += $entity->getProperty('mapping')->getValue();
			}

			$entity->addProperty('mapping', $mapping)->setVisibility('protected')
				->addComment('')->addComment('@var string[]')->addComment('');
		}

		file_put_contents($this->config->path . '/' . $shortClassName . '.php', $file->__toString());
	}

	protected function getClassName(string $table): string
	{
		return $this->config->prefix . Helper::camelize($table, $this->config->replacements) . $this->config->suffix;
	}

	/**
	 * @throws Exception
	 */
	protected function validateColumnName(string $table, Column $column): void
	{
		if (Strings::contains($column->getField(), '(')) {
			throw new Exception('Bad naming for ' . $column->getField() . ' in table ' . $table .
				', please change name in database or use AS in views');
		}
	}

	protected function generateColumn(ClassType $entity, Column $column): void
	{
		$type = $this->getColumnType($column);

		if ($this->config->generateProperties) {
			$property = $entity->addProperty($column->getField())
				->setVisibility($this->config->propertyVisibility);

			if ($this->config->addPropertyVarComment) {
				$property->addComment('@var ' . $type);
			}

			if ($this->config->strictlyTypedProperties) {
					$property->setType($type);
					$property->setNullable($column->isNullable());
			}
		}

		if ($this->config->generatePhpDocProperties) {
			$entity->addComment($this->config->phpDocProperty . ' ' . $type . ' $' . $column->getField());
		}

		if ($this->config->generateGetters) {
			$getter = $entity->addMethod('get' . $this->inflector->classify($column->getField()));
			$getter->setVisibility($this->config->getterVisibility)
				->addBody(str_replace('__FIELD__', $column->getField(), $this->config->getterBody))
				->setReturnType($type)
				->setReturnNullable($column->isNullable());
		}

		if ($this->config->generateSetters) {
			$setter = $entity->addMethod('set' . $this->inflector->classify($column->getField()));
			$setter->setVisibility($this->config->setterVisibility);
			$setter->addParameter('value')->setType($type)->setNullable($column->isNullable());
			$setter->addBody(str_replace('__FIELD__', $column->getField(), $this->config->setterBody));
			$setter->setReturnType('self');
		}
	}

	protected function getColumnType(Column $column): string
	{
		$dbColumnType = $column->getType();

		if (Strings::contains($dbColumnType, '(')) {
			$dbColumnType = Strings::lower(Strings::before($dbColumnType, '(') ?? 'string');
		}

		/** @var array<string, string> $typeMapping */
		$typeMapping = Helper::multiArrayFlip($this->config->typeMapping);

		if (isset($typeMapping[$dbColumnType])) {
			return $typeMapping[$dbColumnType];
		}

		return 'string';
	}

	protected function generateColumnConstant(ClassType $entity, Column $column): void
	{
		if ($this->config->primaryKeyConstant !== null && $column->isPrimary()) {
			$entity->addConstant($this->config->primaryKeyConstant, $column->getField())
				->setVisibility('public');
		}

		if ($this->config->generateColumnConstant) {
			$columnConstant = $this->config->prefix . Strings::upper($this->inflector->tableize($column->getField()));

			if ($columnConstant === 'CLASS') {
				$columnConstant = '_CLASS';
			}

			$constants = $entity->getConstants();

			if (!isset($constants[$column->getField()])) {
				$entity->addConstant($columnConstant, $column->getField())->setVisibility('public');
			}
		}
	}

	private function cloneEntityFromExistingEntity(ClassType $entity, ClassType $from): void
	{
		$entity->setProperties($from->getProperties());
		$entity->setComment($from->getComment());
		$entity->setMethods($from->getMethods());
		$methods = $entity->getMethods();

		foreach ($methods as $method) {
			$fqnClassName = '\\' . $this->config->namespace . '\\' . $entity->getName();
			$body = $this->getMethodBody($fqnClassName, $method->getName());
			$method->setBody($body);
		}
	}

	private function getMethodBody(string $class, string $name): string
	{
		$func = new ReflectionMethod($class, $name);
		$startLine = $func->getStartLine() + 1; //@phpstan-ignore-line
		$length = $func->getEndLine() - $startLine - 1; //@phpstan-ignore-line

		if ($func->getFileName() === false) {
			throw new Exception('Cannot get generated entity filename!');
		}

		$source = file($func->getFileName());

		if ($source === false) {
			throw new Exception('Cannot open generated entity file!');
		}

		$bodyLines = array_slice($source, $startLine, $length);
		$body = '';

		foreach ($bodyLines as $bodyLine) {
			$body .= Strings::after($bodyLine, "\t\t");
		}

		return $body;
	}

}
