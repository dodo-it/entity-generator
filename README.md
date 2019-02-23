

## Entity generator
Typed entity generator from database. It can generate entities for whole database, table/view and from query

-----
[![Latest Stable Version](https://poser.pugx.org/dodo-it/entity-generator/v/stable)](https://packagist.org/packages/dodo-it/entity-generator)
[![Build Status](https://travis-ci.org/dodo-it/entity-generator.svg?branch=master)](https://travis-ci.org/dodo-it/entity-generator)
[![codecov](https://codecov.io/gh/dodo-it/entity-generator/branch/master/graph/badge.svg)](https://codecov.io/gh/dodo-it/entity-generator)
[![PHPStan](https://img.shields.io/badge/PHPStan-enabled-brightgreen.svg?style=flat)](https://github.com/phpstan/phpstan)
[![Total Downloads](https://poser.pugx.org/dodo-it/entity-generator/downloads)](https://packagist.org/packages/dodo-it/entity-generator)
[![License](https://poser.pugx.org/dodo-it/entity-generator/license)](https://packagist.org/packages/dodo-it/entity-generator)

## Installation

    $ composer require dodo-it/entity-generator

## USAGE:
        $config = [
            'path' => __DIR__ . '/Entities',
            'extends' => \Examples\Pdo\Entities\Entity::class,
            'namespace' => 'Examples\Pdo\Entities'
        ];


        $pdo = new \PDO('mysql:dbname=example;host=127.0.0.1', 'root', '');

        $generatorFactory = new \DodoIt\EntityGenerator\GeneratorFactory($pdo);
        $generator = $generatorFactory->create($config);
        $generator->generate();



see example folder


You can add your own methods to entities and change getter/setter functions, they won't be overriden when regenerated


## Configuration
this are defaults

    $config = [
            'path' => NULL
            'namespace' => 'App\\Models\Entities',
            'typeMapping' => [
                'int' => ['int', 'bigint', 'mediumint', 'smallint' ],
                'float' => ['decimal', 'float'],
                'bool' => ['bit', 'tinyint'],
                '\DateTime' => ['date', 'datetime', 'timestamp'],
                '\DateInterval' => ['time']
            ],
            'replacements' => [],
            'prefix' => '',
            'suffix' => 'Entity',
            'extends' => \Examples\Pdo\Entity::class,
            'gettersAndSetters' => true,
            'propertyVisibility' => 'protected'
        ];
        
        
# Example of generated entity:

    <?php
    namespace Examples\Pdo\Entities;

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
