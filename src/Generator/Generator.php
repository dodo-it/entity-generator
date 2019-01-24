<?php declare (strict_types=1);

namespace DodoIt\DibiEntity\Generator;

use Doctrine\Common\Inflector\Inflector;
use DodoIt\DibiEntity\Entity;
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


	public function __construct(Repository $repository, string $path, string $namespace, array $typeMapping)
	{
		$this->repository = $repository;
		$this->path = $path;
		$this->namespace = $namespace;
		$this->typeMapping = $typeMapping;
	}


	public function generate()
	{
		$tables = $this->repository->getTables();
		foreach ($tables as $table) {
			$this->generateEntity($table);
		}
	}

	public function generateEntity(string $table): void
	{
		$file = new PhpFile();
		$namespace = $file->addNamespace($this->namespace);

		$shortclassName = $this->getClassName($table);
		$fqnClassName = '\\' . $this->namespace . '\\' . $shortclassName;
		$entity = $namespace->addClass($shortclassName);
		if(class_exists( $fqnClassName)){
			$this->cloneEntityFromExistingEntity($entity, ClassType::from($fqnClassName));
		}
		$entity->setExtends(Entity::class);
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
		return Inflector::singularize(Inflector::classify($table));
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
			->setVisibility('protected')
			->addComment('@var ' . $type);

		$getter = $entity->addMethod('get' . Inflector::classify($column->getField(), '_'));
		$getter->setVisibility('public')
			->addBody('return $this->' . $column->getField() . ';')
			->setReturnType($type)
			->setReturnNullable($column->isNullable());

		$setter = $entity->addMethod('set' . Inflector::classify($column->getField()));
		$setter->setVisibility('public');
		$setter->addParameter('value')->setTypeHint($type)->isNullable($column->isNullable());
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