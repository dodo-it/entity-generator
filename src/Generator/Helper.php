<?php declare (strict_types = 1);

namespace DodoIt\EntityGenerator\Generator;

use Doctrine\Common\Inflector\Inflector;

class Helper
{

	/**
	 * @param mixed[] $array
	 * @return mixed[]
	 */
	public static function multiArrayFlip(array $array): array
	{
		$result = [];
		foreach ($array as $key => $insideArray) {
			foreach ($insideArray as $value) {
				$result[$value] = $key;
			}
		}
		return $result;
	}

	public static function camelize($input, $separator = '_')
	{
		$words = explode($separator, $input);
		$result = '';
		foreach ($words as $word) {
			$result .= Inflector::singularize(ucfirst($word));
		}
		return $result;
	}
}
