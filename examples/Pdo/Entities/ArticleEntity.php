<?php

namespace examples\Pdo\Entities;

class ArticleEntity extends Entity
{
	public const TABLE = 'articles';

	/**
	 * @var int
	 */
	protected $id;

	/**
	 * @var int
	 */
	protected $category_id;

	/**
	 * @var string
	 */
	protected $title;

	/**
	 * @var string
	 */
	protected $content;

	/**
	 * @var bool
	 */
	protected $published;

	/**
	 * @var \DateTime
	 */
	protected $created_at;


	public function getId(): int
	{
		return $this->id;
	}


	public function setId(int $value): self
	{
		$this['id'] = $value;
		return $this;
	}


	public function getCategoryId(): int
	{
		return $this->category_id;
	}


	public function setCategoryId(int $value): self
	{
		$this['category_id'] = $value;
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


	public function getContent(): ?string
	{
		return $this->content;
	}


	public function setContent(?string $value): self
	{
		$this['content'] = $value;
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


	public function getCreatedAt(): ?\DateTime
	{
		return $this->created_at;
	}


	public function setCreatedAt(?\DateTime $value): self
	{
		$this['created_at'] = $value;
		return $this;
	}
}
