<?php declare (strict_types = 1);

namespace DodoIt\EntityGenerator\Generator;

use DodoIt\EntityGenerator\Entity;

class Config
{

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
	public $generateProperties = true;

	/**
	 * @var string
	 */
	public $propertyVisibility = 'protected';

}