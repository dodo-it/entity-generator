<?php

const BASEPATH = '/entities';

// get variable from system environment
$params['hostname'] = getenv('MYSQL_HOSTNAME');
$params['database'] = getenv('MYSQL_DATABASE');
$params['username'] = getenv('MYSQL_USERNAME');
$params['password'] = getenv('MYSQL_PASSWORD');
$params['baseclass'] = getenv('BASECLASS');
$params['namespace'] = getenv('ENTITY_NAMESPACE');

// check all required mysql parameters
if(!isset($params['hostname']) || !isset($params['database']) || !isset($params['username']) || !isset($params['password'])) {
    var_dump($params);
    die('Missing or bad command line parameters.');
}
if($params['baseclass']) {
    // include file and transform string into ::class
    include __DIR__ . BASEPATH . '/' . $params['baseclass'] . '.php';
} else {
    // Default empty entity class
    class Entity {}
    $params['baseclass'] = Entity::class;
}
echo "baseclass is defined: {$params['baseclass']}\n";
if(!$params['namespace']) {
    $params['namespace'] = 'Examples\Pdo\Entities';
}

include('vendor/autoload.php');

$config = new \DodoIt\EntityGenerator\Generator\Config([
    'path' =>  __DIR__ . BASEPATH,
    'extends' => $params['baseclass'],  // \Examples\Pdo\Entities\Entity::class,
    'namespace' => $params['namespace'] // 'Examples\Pdo\Entities'
]);

echo "connect to {$params['hostname']} {$params['database']} ...\n";
$pdo = new \PDO("mysql:dbname={$params['database']};host={$params['hostname']}", $params['username'], $params['password']);

echo "ok! Generating entities...\n";
$generatorFactory = new \DodoIt\EntityGenerator\Factory\GeneratorPdoFactory($pdo);
$generator = $generatorFactory->create($config);
$generator->generate();
echo "job done ! nb entities generated : " . count(glob('entities/*')) . "\n";
