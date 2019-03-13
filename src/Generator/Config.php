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
		'\DateTimeInterface' => ['date', 'datetime', 'timestamp'],
		'\DateInterval' => ['time'],
	];

	/**
	 * @var string[]
	 */
	public $replacements = [];

	/**
	 * set to true to generate new entities and completelly ignore old ones
	 *
	 * @var bool
	 */
	public $rewrite = false;

	/**
	 * set to null to skip table constant
	 *
	 * @var string|null
	 */
	public $tableConstant = 'TABLE_NAME';

	/**
	 * generate mapping array where key is entity property name and value is table column name
	 *
	 * @var bool
	 */
	public $generateMapping = false;

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
	 * @var string
	 */
	public $getterVisibility = 'public';

	/**
	 * @var string
	 */
	public $getterBody = 'return $this->__FIELD__;';

	/**
	 * @var string
	 */
	public $setterVisibility = 'public';

	/**
	 * @var string
	 */
	public $setterBody = '$this[\'__FIELD__\'] = $value;' . "\n" . 'return $this;';

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
	 * @var bool
	 */
	public $generatePhpDocProperties = false;

	/**
	 * @var string
	 */
	public $phpDocProperty = '@property';

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
