<?php declare (strict_types = 1);

namespace DodoIt\EntityGenerator\Entity;

use ArrayAccess;
use ArrayIterator;
use Countable;
use Exception;
use IteratorAggregate;

class Entity implements ArrayAccess, IteratorAggregate, Countable
{

	public const TABLE = null;

	/** @var mixed[] */
	protected $data;

	/** @var mixed[] */
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

	/**
	 * @return int
	 */
	public function count()
	{
		return count($this->data);
	}


	public function toArray(): array
	{
		return $this->data;
	}

	/**
	 * @return ArrayIterator
	 */
	public function getIterator()
	{
		return new ArrayIterator($this->data);
	}

	/**
	 * @param string $nm
	 * @param mixed $val
	 * @return void
	 */
	public function offsetSet($nm, $val)
	{
		$this->data[$nm] = $val;
		$this->modifications[$nm] = $val;
		$this->$nm = $val;
	}


	/**
	 * @param string $nm
	 * @return void
	 */
	public function offsetGet($nm)
	{
		throw new Exception('You should never access entity as array');
	}


	/**
	 * @param string $nm
	 * @return void
	 */
	public function offsetExists($nm)
	{
		throw new Exception('You should never access entity as array');
	}


	/**
	 * @param string $nm
	 * @return void
	 */
	public function offsetUnset($nm)
	{
		throw new Exception('You should never access entity as array');
	}

}
