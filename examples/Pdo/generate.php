<?php

include __DIR__ . '/../../vendor/autoload.php';

$config = new \DodoIt\EntityGenerator\Generator\Config([
	'path' =>  __DIR__ . '/Entities',
	'extends' => \Examples\Pdo\Entities\Entity::class,
	'namespace' => 'Examples\Pdo\Entities'
]);

$pdo = new \PDO('mysql:dbname=example;host=127.0.0.1', 'root', '');

$generatorFactory = new \DodoIt\EntityGenerator\Factory\GeneratorPdoFactory($pdo);
$generator = $generatorFactory->create($config);
$generator->generate();