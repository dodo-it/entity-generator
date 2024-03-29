<?php declare (strict_types = 1);

namespace DodoIt\EntityGenerator\Factory;

use DodoIt\EntityGenerator\Generator\Config;
use DodoIt\EntityGenerator\Generator\Generator;
use DodoIt\EntityGenerator\Repository\PdoRepository;
use PDO;

class GeneratorPdoFactory
{

	private PDO $pdo;

	public function __construct(PDO $pdo)
	{
		$this->pdo = $pdo;
	}

	public function create(Config $config): Generator
	{
		$repository = new PdoRepository($this->pdo);

		return new Generator($repository, $config);
	}

}
