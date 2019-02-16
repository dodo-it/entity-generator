<?php
namespace Examples\Pdo\Entities;

class CategoryEntity extends Entity
{
	public const TABLE = 'categories';

	/**
	 * @var int
	 */
	protected $id;

	/**
	 * @var string
	 */
	protected $name;


	public function getId(): int
	{
		return $this->id;
	}


	public function setId(int $value): self
	{
		$this['id'] = $value;
		return $this;
	}


	public function getName(): ?string
	{
		return $this->name;
	}


	public function setName(?string $value): self
	{
		$this['name'] = $value;
		return $this;
	}
}
