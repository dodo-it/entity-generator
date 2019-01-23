<?php declare (strict_types=1);

namespace DodoIt\DibiEntity\Generator;

use DodoIt\DibiEntity\Entity;


class Column extends Entity
{

	/**
	 * @var string
	 */
	protected $Field;

	/**
	 * @var string
	 */
	protected $Type;

	/**
	 * @var string
	 */
	protected $Null;

	/**
	 * @var string
	 */
	protected $Key;

	/**
	 * @var string
	 */
	protected $Default;

	/**
	 * @var $Extra
	 */
	protected $Extra;


	public function getField(): string
	{
		return $this->Field;
	}


	public function getType(): string
	{
		return $this->Type;
	}


	public function isNullable(): bool
	{
		return $this->Null === 'YES';
	}


	public function getKey(): ?string
	{
		return $this->Key;
	}


	public function getDefault(): ?string
	{
		return $this->Default;
	}


	public function getExtra(): ?string
	{
		return $this->Extra;
	}
}