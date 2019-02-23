## About
Typed entity generator from database. It can generate entities for whole database, table/view and from query

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
