<?php declare (strict_types=1);

namespace DodoIt\DibiEntity;


use Dibi\NotImplementedException;

class Entity implements \ArrayAccess, \IteratorAggregate, \Countable
{

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


	public function count()
	{
		return count((array) $this->data);
	}

	public function toArray()
	{
		return $this->data;
	}

	final public function getIterator()
	{
		return new \ArrayIterator($this->data);
	}


	final public function offsetSet($nm, $val)
	{
		$this->data[$nm] = $val;
		$this->modifications[$nm] = $val;
		$this->$nm = $val;
	}


	final public function offsetGet($nm)
	{
		throw new NotImplementedException('You should never access entity as array');
	}


	final public function offsetExists($nm)
	{
		throw new NotImplementedException('You should never access entity as array');
	}


	final public function offsetUnset($nm)
	{
		throw new NotImplementedException('You should never access entity as array');
	}
}