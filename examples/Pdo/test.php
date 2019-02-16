<?php

include __DIR__ . '/../../vendor/autoload.php';

$pdo = new \PDO('mysql:dbname=example;host=127.0.0.1', 'root', '');

//insert category
$category = new \Examples\Pdo\Entities\CategoryEntity();
$category->setName('Test category');
easyInsert($pdo, \Examples\Pdo\Entities\CategoryEntity::TABLE, $category);

//get category

$rows = $pdo->query('SELECT * FROM categories')
->fetchAll(\PDO::FETCH_ASSOC);
foreach ($rows as $row) {
	$entity = new \Examples\Pdo\Entities\CategoryEntity($row);
	//now you can do whatever you want with entity
	//$entity->getName();
}

//create article
$article = new \Examples\Pdo\Entities\ArticleEntity();
$article->setTitle('Test article')
->setContent('TestContent')
->setCreatedAt(new DateTime())
->setCategoryId(1)
->setPublished(TRUE);

easyInsert($pdo, \Examples\Pdo\Entities\ArticleEntity::TABLE, $article);



function easyInsert(\PDO $pdo, string $table, \Examples\Pdo\Entities\Entity $entity) {
	$row = $entity->_getModifications();
	$columns = implode(", ", array_keys($row));
	$valuesParams = ":" . implode(", :", array_keys($row));
	$sql = "INSERT INTO $table ($columns) VALUES ($valuesParams)";
	$pdo->prepare($sql)->execute($row);
}