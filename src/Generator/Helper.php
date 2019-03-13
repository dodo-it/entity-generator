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

	public static function camelize(string $input, array $replacements = [], string $separator = '_'): string
	{
		$words = explode($separator, $input);
		$result = '';
		foreach ($words as $word) {
			$result .= $replacements[$word] ?? Inflector::singularize(ucfirst($word));
		}
		return $result;
	}

}
