<?php declare (strict_types = 1);

namespace DodoIt\EntityGenerator\Entity;

class Entity
{

	/**
	 * @param array<string, string|int|bool|float|\DateTimeInterface|null>|\Iterator<string, string|int|bool|float|\DateTimeInterface|null>|null $arr
	 */
	public function __construct(array|\Iterator|null $arr = [])
	{
		$this->loadFromArray($arr);
	}

	/**
	 * @return array<string, string|int|bool|float|\DateTimeInterface|null>
	 */
	public function toArray(): array
	{
		$ref = new \ReflectionClass($this);

		$result = [];

		foreach ($ref->getProperties() as $property) {
			if (isset($this->{$property->getName()})) {
				$result[$property->getName()] = $this->{$property->getName()};
			}
		}

		return $result;
	}

	/**
	 * @param array<string, string|int|bool|float|\DateTimeInterface|null>|\Iterator<string, string|int|bool|float|\DateTimeInterface|null>|null $arr
	 */
	public function loadFromArray(array|\Iterator|null $arr): void
	{
		if ($arr === null) {
			return;
		}

		foreach ($arr as $k => $v) {
			if (property_exists($this, $k)) {
				$this->$k = $v;
			}
		}
	}

}
