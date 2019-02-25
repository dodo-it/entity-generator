<?php declare (strict_types = 1);

namespace Examples\Pdo\Entities;

// you can create your own entity class, no need to extend \DodoIt\EntityGenerator\Entity
// this is not the code I would ever use in production but its just a showoff code for library
class Entity extends \DodoIt\EntityGenerator\Entity\Entity
{
	public function __construct(array $arr = [])
	{
		// most DBAL automatically return \DateTime so this is not needed and this is just
		// a sample code to show you for what you can use this library
		$reflection = new \ReflectionClass($this);
		//automatically convert datetime string to datetime object
		foreach ($arr as $key => $value) {
			$property = $reflection->getProperty($key);
			if (preg_match('/@var\s+([^\s]+)/', $property->getDocComment(), $matches)) {
				list(, $type) = $matches;
				if($type === \DateTime::class) {
					$arr[$key] = new \DateTime($value);
				}
			}
		}
		parent::__construct($arr);
	}

	public function _getModifications(): array
	{
		$arr = parent::_getModifications();
		foreach ($arr as $key => $value) {
			if($value instanceof \DateTime) {
				$arr[$key] = $value->format('Y-m-d H:i:s');
			}
		}
		return $arr;
	}
}