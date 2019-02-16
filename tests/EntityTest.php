<?php declare (strict_types=1);

namespace DodoIt\EntityGenerator\Tests;

use DodoIt\EntityGenerator\Tests\TestEntities\UserEntity;
use PHPUnit\Framework\TestCase;


class EntityTest extends TestCase
{

	public function testGetters()
	{
		$entity = new UserEntity(
			[
			'id' => 1,
			'active' => 1,
			'last_login' => new \DateTime('2019-01-24')
			]
		);
		$this->assertEquals(1, $entity->getId());
		$this->assertTrue($entity->isActive());
		$this->assertInstanceOf(\DateTime::class, $entity->getLastLogin());
	}

	public function test_getModifications()
	{
		$entity = new UserEntity();
		$entity->setActive(TRUE);
		$entity->setLastLogin(new \DateTime('2019-01-24'));
		$entity->setUsername('user');

		$this->assertTrue($entity->isActive());
		$this->assertInstanceOf(\DateTime::class, $entity->getLastLogin());
		$this->assertEquals('user', $entity->getUsername());

		$modifications = $entity->_getModifications();
		$this->assertEquals([
			'username' => 'user',
			'active' => 1,
			'last_login' => new \DateTime('2019-01-24')
		], $entity->toArray());
	}
}