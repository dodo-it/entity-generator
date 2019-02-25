<?php declare (strict_types = 1);

namespace DodoIt\EntityGenerator\Generator;

use DodoIt\EntityGenerator\Entity\Entity;

class Config
{

	public function __construct(?array $config = null)
	{
		if ($config === null) {
			return;
		}
		foreach ($config as $key => $value) {
			$this->$key = $value;
		}
	}

	/**
	 * @var string
	 */
	public $namespace = 'App\\Models\Entities';

	/**
	 * @var string
	 */
	public $path = './';

	/**
	 * @var string[]
	 */
	public $typeMapping = [
		'int' => ['int', 'bigint', 'mediumint', 'smallint' ],
		'float' => ['decimal', 'float'],
		'bool' => ['bit', 'tinyint'],
		'\DateTime' => ['date', 'datetime', 'timestamp'],
		'\DateInterval' => ['time'],
	];

	/**
	 * @var string[]
	 */
	public $replacements = [];

	/**
	 * set to null to skip table constant
	 *
	 * @var string|null
	 */
	public $tableConstant = 'TABLE_NAME';

	/**
	 * @var string
	 */
	public $prefix = '';

	/**
	 * @var string
	 */
	public $suffix = 'Entity';

	/**
	 * @var string
	 */
	public $extends = Entity::class;

	/**
	 * @var bool
	 */
	public $generateGetters = true;

	/**
	 * @var bool
	 */
	public $generateSetters = true;

	/**
	 * @var bool
	 */
	public $generateColumnConstant = true;

	/**
	 * @var string
	 */
	public $columnConstantPrefix = 'COL_';

	/**
	 * @var bool
	 */
	public $generateProperties = true;

	/**
	 * @var string
	 */
	public $propertyVisibility = 'protected';

	/**
	 * @param string $name
	 * @return void
	 * @throws \Exception
	 */
	public function __get($name)
	{
		throw new \Exception('Configuration "' . $name . '" does not exist!');
	}

	/**
	 * @param string $name
	 * @param mixed  $value
	 * @return void
	 * @throws \Exception
	 */
	public function __set($name, $value)
	{
		throw new \Exception('Configuration "' . $name . '" does not exist!');
	}

	/**
	 * @param string $name
	 * @return void
	 * @throws \Exception
	 */
	public function __isset($name)
	{
		throw new \Exception('Configuration "' . $name . '" does not exist!');
	}

}
