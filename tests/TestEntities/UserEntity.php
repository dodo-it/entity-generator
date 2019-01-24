<?php
namespace DodoIt\DibiEntity\Tests\TestEntities;

use DodoIt\DibiEntity\Entity;

class UserEntity extends Entity
{
	/** @var int */
	protected $id;

	/** @var string */
	protected $username;

	/** @var \DateTimeInterface|null */
	protected $last_login;

	/** @var bool */
	protected $active;


	public function getId(): int
	{
		return $this->id;
	}


	public function setId(int $value): self
	{
		$this['id'] = $value;
		return $this;
	}


	public function getUsername(): string
	{
		return $this->username;
	}


	public function setUsername(string $value): self
	{
		$this['username'] = $value;
		return $this;
	}


	public function getLastLogin(): ?\Dibi\DateTime
	{
		return $this->last_login;
	}


	public function setLastLogin(\Dibi\DateTime $value): self
	{
		$this['last_login'] = $value;
		return $this;
	}


	public function isActive(): bool
	{
		return $this->active;
	}


	public function setActive(bool $value): self
	{
		$this['active'] = $value;
		return $this;
	}

}
