<?php declare (strict_types = 1);

namespace DodoIt\EntityGenerator;

use DodoIt\EntityGenerator\Generator\Generator;
use DodoIt\EntityGenerator\Generator\Repository;

class GeneratorFactory
{

	protected $config = [
		'namespace' => 'App\\Models\Entities',
		'typeMapping' => [
			'int' => ['int', 'bigint', 'mediumint', 'smallint' ],
			'float' => ['decimal', 'float'],
			'bool' => ['bit', 'tinyint'],
			'\DateTime' => ['date', 'datetime', 'timestamp'],
			'\DateInterval' => ['time']
		],
		'replacements' => [],
		'prefix' => '',
		'suffix' => 'Entity',
		'extends' => \Examples\Pdo\Entity::class,
		'gettersAndSetters' => true,
		'propertyVisibility' => 'protected'
	];

	/**
	 * @var \PDO
	 */
	private $pdo;

	public function __construct(\PDO $pdo)
	{
		$this->pdo = $pdo;
	}

	public function create(array $config = []): Generator
	{
		$this->config = array_merge($this->config, $config);
		$repository = new Repository($this->pdo);
		$generator = new Generator($repository, $this->config);
		return $generator;
	}
}