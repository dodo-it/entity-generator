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

	/**
	 * @return string[]
	 */
	public static function getPhpDocComments(string $phpDoc): array
	{
		preg_match_all(
			'~^  [ \t*]*  @property(|-read|-write)  [ \t]+  [^\s$]+  [ \t]+  \$  (\w+)  ()~mx',
			$phpDoc,
			$matches,
			PREG_SET_ORDER
		);
		$result = [];
		foreach ($matches as $match) {
			$result[] = $match[2];
		}
		return $result;
	}

}
