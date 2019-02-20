<?php declare (strict_types = 1);

namespace DodoIt\EntityGenerator;

use DodoIt\EntityGenerator\Generator\Config;
use DodoIt\EntityGenerator\Generator\Generator;
use DodoIt\EntityGenerator\Generator\Repository;

class GeneratorFactory
{
	/**
	 * @var \PDO
	 */
	private $pdo;

	public function __construct(\PDO $pdo)
	{
		$this->pdo = $pdo;
	}

	public function create(Config $config): Generator
	{
		$repository = new Repository($this->pdo);
		$generator = new Generator($repository, $config);
		return $generator;
	}
}