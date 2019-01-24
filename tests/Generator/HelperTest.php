<?php declare (strict_types=1);

namespace DodoIt\DibiEntity\Tests;

use PHPUnit\Framework\TestCase;

class HelperTest extends TestCase
{

	public function testMultiArrayFlip()
	{
		$arr = [
			'int' => ['int', 'bigint'],
			'\DateInterval' => ['time']
		];
		$result = \DodoIt\DibiEntity\Generator\Helper::multiArrayFlip($arr);
		$this->assertEquals($result, [
			'int' => 'int',
			'bigint' => 'int',
			'time' => '\DateInterval'
		]);
	}
}