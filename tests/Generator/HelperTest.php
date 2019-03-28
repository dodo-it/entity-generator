<?php declare (strict_types = 1);

namespace DodoIt\EntityGenerator\Tests;

use DodoIt\EntityGenerator\Generator\Helper;
use PHPUnit\Framework\TestCase;

class HelperTest extends TestCase
{

	public function testMultiArrayFlip(): void
	{
		$arr = [
			'int' => ['int', 'bigint'],
			'\DateInterval' => ['time'],
		];
		$result = Helper::multiArrayFlip($arr);
		$this->assertEquals($result, [
			'int' => 'int',
			'bigint' => 'int',
			'time' => '\DateInterval',
		]);
	}

	public function testCamelize()
	{
		$this->assertEquals('User', Helper::camelize('users'));
		$this->assertEquals('UserLogin', Helper::camelize('users_logins'));
	}

	public function testGetPhpDocProperties()
	{
		$comment = '/**
		 * @property int $id
		 * @property string $title
		 * @property int $published
		 * @property \DateTimeInterface $created_at
		 */';
		$result = Helper::getPhpDocComments($comment);
		$this->assertCount(4, $result);
		$this->assertEquals('id', $result[0]);
		$this->assertEquals('created_at', $result[3]);
	}

}
