<?php

namespace DodoIt\EntityGenerator\Tests\TestEntities;

/**
 * @property int $id
 * @property string $title
 * @property int $published
 * @property \DateTimeInterface $created_at
 */
class PhpDocPropertyEntity extends \DodoIt\EntityGenerator\Entity\Entity
{
	public const TABLE_NAME = 'php_doc_properties';

	public function getId(): int
	{
		return $this->id;
	}


	public function setId(int $value): self
	{
		$this['id'] = $value;
		return $this;
	}


	public function getTitle(): ?string
	{
		return $this->title;
	}


	public function setTitle(?string $value): self
	{
		$this['title'] = $value;
		return $this;
	}


	public function getPublished(): bool
	{
		return $this->published;
	}


	public function setPublished(bool $value): self
	{
		$this['published'] = $value;
		return $this;
	}


	public function getCreatedAt(): ?\DateTimeInterface
	{
		return $this->created_at;
	}


	public function setCreatedAt(?\DateTimeInterface $value): self
	{
		$this['created_at'] = $value;
		return $this;
	}
}
