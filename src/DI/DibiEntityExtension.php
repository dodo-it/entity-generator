<?php declare(strict_types = 1);

namespace DodoIt\DibiEntity\DI;

use DodoIt\DibiEntity\Command\GenerateCommand;
use DodoIt\DibiEntity\Generator\Generator;
use DodoIt\DibiEntity\Generator\Repository;
use Nette\DI\CompilerExtension;
use Nette\DI\Helpers;


class DibiEntityExtension extends CompilerExtension
{
	/** @var mixed[] */
	private $defaults = [
		'path' => '%appDir%/Models/Entities',
		'namespace' => 'App\\Models\Entities',
		'typeMapping' => [
			'int' => ['int', 'bigint', 'mediumint', 'smallint' ],
			'float' => ['decimal', 'float'],
			'bool' => ['bit', 'tinyint'],
			'\Dibi\DateTime' => ['date', 'datetime', 'timestamp'],
			'\DateInterval' => ['time']
		]
	];

	/**
	 * Register services
	 */
	public function loadConfiguration(): void
	{
		$builder = $this->getContainerBuilder();
		$config = $this->validateConfig($this->defaults);
		$config = Helpers::expand($config, $builder->parameters);

		$builder->addDefinition($this->prefix('Repository'))
			->setFactory(Repository::class);

		$builder->addDefinition($this->prefix('Generator'))
			->setFactory(Generator::class, [
				'path' => $config['path'],
				'namespace' => $config['namespace'],
				'typeMapping' => $config['typeMapping']
			]);

		$builder->addDefinition($this->prefix('GenerateCommand'))
			->setFactory(GenerateCommand::class);
	}


}