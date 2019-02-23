

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
    $config = new \DodoIt\EntityGenerator\Generator\Config([
        'path' =>  __DIR__ . '/Entities',
        'extends' => \Examples\Pdo\Entities\Entity::class,
        'namespace' => 'Examples\Pdo\Entities'
    ]);

    $pdo = new \PDO('mysql:dbname=example;host=127.0.0.1', 'root', '');

    $generatorFactory = new \DodoIt\EntityGenerator\Factory\GeneratorPdoFactory($pdo);
    $generator = $generatorFactory->create($config);
    $generator->generate();



see example folder


You can add your own methods to entities and change getter/setter functions, they won't be overriden when regenerated


## Configuration

see src/Generator/Config.php
