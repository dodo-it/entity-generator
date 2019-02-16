<?php declare (strict_types=1);

namespace DodoIt\EntityGenerator;


class Entity implements \ArrayAccess, \IteratorAggregate, \Countable
{

	public const TABLE = NULL;

	/**
	 * @var $data
	 */
	protected $data;

	/**
	 * @var array
	 */
	private $modifications = [];


	public function __construct(array $arr = [])
	{
		$this->data = $arr;
		foreach ($arr as $k => $v) {
			$this->$k = $v;
		}
	}


	public function _getModifications(): array
	{
		return $this->modifications;
	}


	public function tableName(): ?string
	{
		return static::TABLE;
	}


	public function count()
	{
		return count((array) $this->data);
	}


	public function toArray()
	{
		return $this->data;
	}


	public function getIterator()
	{
		return new \ArrayIterator($this->data);
	}


	public function offsetSet($nm, $val)
	{
		$this->data[$nm] = $val;
		$this->modifications[$nm] = $val;
		$this->$nm = $val;
	}


	public function offsetGet($nm)
	{
		throw new \Exception('You should never access entity as array');
	}


	public function offsetExists($nm)
	{
		throw new \Exception('You should never access entity as array');
	}


	public function offsetUnset($nm)
	{
		throw new \Exception('You should never access entity as array');
	}
}