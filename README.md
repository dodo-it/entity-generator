

## Entity generator
Highly customizable (typed) entity generator from database. It can generate entities for whole database, table/view and from raw SQL query

-----
[![Latest Stable Version](https://poser.pugx.org/dodo-it/entity-generator/v/stable)](https://packagist.org/packages/dodo-it/entity-generator)
[![build](https://github.com/dodo-it/entity-generator/workflows/build/badge.svg)](https://github.com/dodo-it/entity-generator/actions?query=workflow%3Abuild)
[![Coverage Status](https://coveralls.io/repos/github/dodo-it/entity-generator/badge.svg?branch=master)](https://coveralls.io/github/dodo-it/entity-generator?branch=master)
[![PHPStan](https://img.shields.io/badge/PHPStan-enabled-brightgreen.svg?style=flat)](https://github.com/phpstan/phpstan)
[![Total Downloads](https://poser.pugx.org/dodo-it/entity-generator/downloads)](https://packagist.org/packages/dodo-it/entity-generator)
[![License](https://poser.pugx.org/dodo-it/entity-generator/license)](https://packagist.org/packages/dodo-it/entity-generator)

## Installation

    $ composer require dodo-it/entity-generator

## How to run:
 ```php
    $config = new \DodoIt\EntityGenerator\Generator\Config([
        'path' =>  __DIR__ . '/Entities',
        'extends' => \Examples\Pdo\Entities\Entity::class,
        'namespace' => 'Examples\Pdo\Entities'
    ]);

    $pdo = new \PDO('mysql:dbname=example;host=127.0.0.1', 'root', '');

    $generatorFactory = new \DodoIt\EntityGenerator\Factory\GeneratorPdoFactory($pdo);
    $generator = $generatorFactory->create($config);
    $generator->generate();
 ```

## What kind of entities can I get?
Tool is highly customizable and can generate various different entity types of which most interesting are:

 - PHP 7.4 typed properties
 ```php
class ArticleEntity extends YourBaseEntity
{
	public int $id;

	public ?string $title;

	public bool $published;

	public ?\DateTimeInterface $created_at;
}
```
 - properties with phpdoc
```php
class ArticleEntity extends YourBaseEntity
{

	/** @var int */
	protected $id;

	/** @var string */
	protected $title;

	/** @var bool */
	protected $published;

	/** @var \DateTimeInterface */
	protected $created_at;
}
```

- properties with getters and setters (methods body is customizable)

```php
class ArticleEntity extends YourBaseEntity
{

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
```

-  phpdoc properties

```php
/**
 * @property int $id
 * @property string $title
 * @property int $published
 * @property \DateTimeInterface $created_at
 */
class ArticleEntity extends YourBaseEntity
{
}
```

- properties with phpdoc
```php
class ArticleEntity extends YourBaseEntity
{
	/** @var int */
	public $id;

	/** @var string */
	public $title;

	/** @var bool */
	public $published;

	/** @var \DateTimeInterface */
	public $created_at;

```

- it can generate column constants (use generateColumnConstants option)

```php
class ArticleEntity extends YourBaseEntity
{
    	public const TABLE_NAME = 'articles';
    	public const ID = 'id';
    	public const TITLE = 'title';
    	public const PUBLISHED = 'published';
    	public const CREATED_AT = 'created_at';

.
.
.

```
- almost any combination you can imagine, check src/Generator/Config.php for list of all options
see example folder


You can add your own methods to entities and change getter/setter functions, they won't be overriden when regenerated if rewrite flag is set to false


## Configuration

see src/Generator/Config.php

# Docker integration

## Entity Generator image

![Docker Pulls](https://img.shields.io/docker/pulls/pifou25/entity-generator)
 https://hub.docker.com/r/pifou25/entity-generator

## Generation from existing database
The database should be accessible from the docker network, the hostname is either
the name of the Mysql running container, or `localhost` if db is running on host.
The below command will generate entities into `./entities` directory.

```
docker run --rm -v $PWD/entities:/app/entities --network some-network \
   -e MYSQL_HOSTNAME=some-mariadb \
   -e MYSQL_DATABASE=exmple-database \
   -e MYSQL_USERNAME=example-user \
   -e MYSQL_PASSWORD=my_cool_secret \
    pifou25/entity-generator
```

## namespace and extends example
The base class must exist into /entities to be declared
```
docker run --rm -v $PWD/include/entities:/app/entities --network scripts_default \
   -e MYSQL_HOSTNAME=myhost \
   -e MYSQL_DATABASE=mydb \
   -e MYSQL_USERNAME=myuser \
   -e MYSQL_PASSWORD=mypwd \
   -e ENTITY_NAMESPACE=Example\\Pdo\\Entities \
   -e BASECLASS=Example\\Pdo\\Entities\Entity \
    entity-generator
```

## Generation from flat plain SQL file

The `docker compose` file create a new database and initialize it with SQL data,
you have to put SQL init file into `./examples` directory. When db is ready, 
 the entity-generator start on it to generate PHP entities.

It is as simple as running this command from the `docker` directory :
`docker compose up`

