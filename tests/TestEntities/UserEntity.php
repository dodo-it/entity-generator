<?php declare(strict_types = 1);

namespace DodoIt\EntityGenerator\Tests\TestEntities;

use DateTime;
use DateTimeInterface;
use DodoIt\EntityGenerator\Entity\Entity;

class UserEntity extends Entity
{

	protected int $id;

	protected string $username;

	protected ?DateTimeInterface $last_login = null;

	protected bool $active;

	public function getId(): int
	{
		return $this->id;
	}

	public function setId(int $value): self
	{
		$this->id = $value;

		return $this;
	}

	public function getUsername(): string
	{
		return $this->username;
	}

	public function setUsername(string $value): self
	{
		$this->username = $value;

		return $this;
	}

	public function getLastLogin(): ?DateTime
	{
		return $this->last_login;
	}

	public function setLastLogin(DateTime $value): self
	{
		$this->last_login = $value;

		return $this;
	}

	public function isActive(): bool
	{
		return $this->active;
	}

	public function setActive(bool $value): self
	{
		$this->active = $value;

		return $this;
	}

}
