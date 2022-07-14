<?php declare (strict_types = 1);

namespace DodoIt\EntityGenerator\Generator;

use DodoIt\EntityGenerator\Entity\Entity;

class Config
{

	public string $namespace = 'App\\Models\Entities';

	public string $path = './';

	/**
	 * @var array<string, string[]>
	 */
	public array $typeMapping = [
		'int' => ['int', 'bigint', 'mediumint', 'smallint' ],
		'float' => ['decimal', 'float'],
		'bool' => ['bit', 'tinyint'],
		'\DateTimeInterface' => ['date', 'datetime', 'timestamp'],
		'\DateInterval' => ['time'],
	];

	/**
	 * @var string[]
	 */
	public array $replacements = [];

	/**
	 * set to true to generate new entities and completelly ignore old ones
	 */
	public bool $rewrite = false;

	/**
	 * set to null to skip table constant
	 */
	public ?string $tableConstant = 'TABLE_NAME';

	/**
	 * generate mapping array where key is entity property name and value is table column name
	 */
	public bool $generateMapping = false;

	/**
	 * Generate primary key constant, value of constant is name of field which is primary
	 */
	public ?string $primaryKeyConstant = null;

	public string $prefix = '';

	public string $suffix = 'Entity';

	public bool $addDeclareStrictTypes = false;

	public ?string $extends = Entity::class;

	public bool $generateGetters = true;

	public bool $generateSetters = true;

	public string $getterVisibility = 'public';

	/**
	 * Add trait to generated entity (use TraitName;)
	 */
	public ?string $addTrait = null;

	public string $getterBody = 'return $this->__FIELD__;';

	public string $setterVisibility = 'public';

	public string $setterBody = '$this[\'__FIELD__\'] = $value;' . "\n" . 'return $this;';

	public bool $generateColumnConstant = true;

	public string $columnConstantPrefix = 'COL_';

	public bool $generateProperties = true;

	public bool $strictlyTypedProperties = false;

	public bool $addPropertyVarComment = true;

	public string $propertyVisibility = 'protected';

	public bool $generatePhpDocProperties = false;

	public string $phpDocProperty = '@property';

	/**
	 * @param array<string, mixed> $config
	 */
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
	 * @throws \Exception
	 */
	public function __get(string $name): mixed
	{
		throw new \Exception('Configuration "' . $name . '" does not exist!');
	}

	/**
	 * @throws \Exception
	 */
	public function __set(string $name, mixed $value): void
	{
		throw new \Exception('Configuration "' . $name . '" does not exist!');
	}

	/**
	 * @throws \Exception
	 */
	public function __isset(string $name): bool
	{
		throw new \Exception('Configuration "' . $name . '" does not exist!');
	}

}
