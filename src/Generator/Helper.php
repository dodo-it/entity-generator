<?php declare (strict_types=1);

namespace DodoIt\EntityGenerator\Generator;

class Helper
{

	public static function multiArrayFlip($array): array
	{
		$result = [];
		foreach($array as $key => $insideArray) {
			foreach($insideArray as $value) {
				$result[$value] = $key;
			}
		}
		return $result;
	}
}