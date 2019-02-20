<?php

include __DIR__ . '/../../vendor/autoload.php';

$config = new \DodoIt\EntityGenerator\Generator\Config();
$config->path = __DIR__ . '/Entities';
$config->extends = \Examples\Pdo\Entities\Entity::class;
$config->namespace = 'Examples\Pdo\Entities';

$pdo = new \PDO('mysql:dbname=example;host=127.0.0.1', 'root', '');

$generatorFactory = new \DodoIt\EntityGenerator\GeneratorFactory($pdo);
$generator = $generatorFactory->create($config);
$generator->generate();