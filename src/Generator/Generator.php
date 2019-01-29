<?php declare (strict_types=1);

namespace DodoIt\DibiEntity\Generator;

use Doctrine\Common\Inflector\Inflector;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\PhpFile;
use Nette\SmartObject;
use Nette\Utils\Strings;

class Generator
{
	use SmartObject;

	/**
	 * @var Repository
	 */
	private $repository;

	/**
	 * @var string
	 */
	private $path;

	/**
	 * @var string
	 */
	private $namespace;

	/**
	 * @var string[]
	 */
	private $typeMapping;

	/**
	 * @var array
	 */
	private $replacements;

	/**
	 * @var string
	 */
	private $prefix;

	/**
	 * @var string
	 */
	private $suffix;

	/**
	 * @var string
	 */
	private $extends;

	/**
	 * @var bool
	 */
	private $gettersAndSetters;

	/**
	 * @var string
	 */
	private $propertyVisibility;

	public function __construct(Repository $repository, array $config)
	{
		$this->repository = $repository;
		$this->path = $config['path'];
		$this->namespace = $config['namespace'];
		$this->typeMapping = $config['typeMapping'];
		$this->replacements = $config['replacements'];
		$this->prefix = $config['prefix'];
		$this->suffix = $config['suffix'];
		$this->extends = $config['extends'];
		$this->gettersAndSetters = $config['gettersAndSetters'];
		$this->propertyVisibility = $config['propertyVisibility'];
	}


	public function generate(?string $table = NULL, ?string $query)
	{
		if(!empty($query)) {
			if(empty($table)) {
				throw new \Exception('When using query table argument has to be provided!');
			}
			$this->repository->createViewFromQuery($table, $query);
			$this->generateEntity($table);
			$this->repository->dropView($table, $query);
			return;
		}
		if($table !== NULL) {
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
		$namespace = $file->addNamespace($this->namespace);

		$shortclassName = $this->getClassName($table);
		$fqnClassName = '\\' . $this->namespace . '\\' . $shortclassName;
		$entity = $namespace->addClass($shortclassName);

		$entity->addConstant('TABLE', $table)->setVisibility('public');

		if(class_exists( $fqnClassName)){
			$this->cloneEntityFromExistingEntity($entity, ClassType::from($fqnClassName));
		}
		$entity->setExtends($this->extends);
		$columns = $this->repository->getTableColumns($table);
		foreach($columns as $column) {
			$this->validateColumnName($table, $column);
			if (isset($entity->properties[$column->getField()])) {
				continue;
			}
			$this->generateColumn($entity, $column);
		}
		file_put_contents($this->path . '/' . $shortclassName . '.php', $file->__toString());
	}


	protected function getClassName(string $table): string
	{
		if(isset($this->replacements[$table])) {
			return $this->replacements[$table];
		}
		return $this->prefix . Inflector::singularize(Inflector::classify($table)) . $this->suffix;
	}

	/**
	 * @throws \Exception
	 */
	protected function validateColumnName(string $table, Column $column): void
	{
		if (Strings::contains($column->getField(), '(')) {
			throw new \Exception('Bad naming for ' . $column->getField() . ' in table ' . $table .
				', please change name in database or use AS in views');
		}
	}


	protected function generateColumn(ClassType $entity, Column $column): void
	{
		$type = $this->getColumnType($column);
		$entity->addProperty($column->getField())
			->setVisibility($this->propertyVisibility)
			->addComment('')
			->addComment('@var ' . $type)
			->addComment('');
		if(!$this->gettersAndSetters) {
			return;
		}
		$getter = $entity->addMethod('get' . Inflector::classify($column->getField(), '_'));
		$getter->setVisibility('public')
			->addBody('return $this->' . $column->getField() . ';')
			->setReturnType($type)
			->setReturnNullable($column->isNullable());

		$setter = $entity->addMethod('set' . Inflector::classify($column->getField()));
		$setter->setVisibility('public');
		$setter->addParameter('value')->setTypeHint($type)->setNullable($column->isNullable());
		$setter->addBody('$this[\'' . $column->getField() . '\'] = $value;');
		$setter->addBody('return $this;');
		$setter->setReturnType('self');
	}


	protected function getColumnType(Column $column): string
	{
		$dbColumnType = $column->getType();
		if(Strings::contains($dbColumnType, '(')) {
			$dbColumnType = Strings::lower(Strings::before($dbColumnType, '('));
		}
		$typeMapping = Helper::multiArrayFlip($this->typeMapping);
		if(isset($typeMapping[$dbColumnType])) {
			return $typeMapping[$dbColumnType];
		}
		return 'string';
	}

	private function cloneEntityFromExistingEntity(ClassType $entity, ClassType $from): void
	{
		$entity->setProperties($from->getProperties());

		$entity->setMethods( $from->getMethods());

		foreach($entity->methods as $method) {
			$fqnClassName = '\\' . $this->namespace . '\\' . $entity->getName();
			$body = $this->getMethodBody($fqnClassName, $method->getName());
			$method->setBody($body);
		}
	}


	private function getMethodBody(string $class, string $name): string
	{
		$func = new \ReflectionMethod($class, $name);
		$startLine = $func->getStartLine() + 1;
		$length = $func->getEndLine() - $startLine - 1;

		$source = file($func->getFileName());
		$bodyLines = array_slice($source, $startLine, $length);
		$body = '';
		foreach ($bodyLines as $bodyLine) {
			$body .= Strings::after($bodyLine, "\t\t");
		}
		return $body;
	}
}