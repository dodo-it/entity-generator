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


	public function __construct(Repository $repository, string $path, string $namespace)
	{
		$this->repository = $repository;
		$this->path = $path;
		$this->namespace = $namespace;
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
		$file->addNamespace($this->namespace);

		$shortclassName = $this->getClassName($table);
		$fqnClassName = $this->namespace . '\\' . $shortclassName;

		if(class_exists($fqnClassName)){
			$entity = ClassType::from($shortclassName);
		} else {
			$entity = $file->addClass($shortclassName);
		}
		$columns = $this->repository->getTableColumns($table);
		foreach($columns as $column) {
			$this->validateColumnName($table, $column);
			if (isset($this->properties[$column->getField()])) {
				continue;
			}
			$entity->addProperty($column->getField())->setVisibility('protected');

			$getter = $entity->addMethod('get' . Inflector::ucwords($column->getField()));
			$getter->setVisibility('public');
			$getter->addBody('return $this->' . $column->getField() . ';');

			$setter = $entity->addMethod('set' . Inflector::ucwords($column->getField()));
			$setter->setVisibility('public');
			$setter->addParameter('value');
			$setter->addBody('$this->' . $column->getField() . ' = $value;');
		}
		file_put_contents($this->path . '/' . $shortclassName . '.php', $file->__toString());
		echo $this->path . '/' . $shortclassName . '.php' . "\n";
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
}