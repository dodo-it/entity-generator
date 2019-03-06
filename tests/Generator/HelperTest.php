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

}
