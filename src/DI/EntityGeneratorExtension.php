<?php declare(strict_types = 1);

namespace DodoIt\EntityGenerator\DI;

use DodoIt\EntityGenerator\Command\GenerateCommand;
use DodoIt\EntityGenerator\Entity;
use DodoIt\EntityGenerator\Generator\Generator;
use DodoIt\EntityGenerator\Generator\Repository;
use Nette\DI\CompilerExtension;
use Nette\DI\Helpers;


class EntityGeneratorExtension extends CompilerExtension
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
		],
		'replacements' => [],
		'prefix' => '',
		'suffix' => 'Entity',
		'extends' => Entity::class,
		'gettersAndSetters' => false,
		'propertyVisibility' => 'protected'
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
			->setFactory(Generator::class, ['config' => $config]);

		$builder->addDefinition($this->prefix('GenerateCommand'))
			->setFactory(GenerateCommand::class);
	}
}