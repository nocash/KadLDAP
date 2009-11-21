<?php
/**
 * AD/LDAP Module for Kohana
 *
 * @package    KadLDAP
 * @author     Beau Dacious <dacious.beau@gmail.com>
 * @copyright  (c) 2009 Beau Dacious
 * @license    http://www.opensource.org/licenses/mit-license.php
 */

/**
 * Simple LDAP Result Adapter
 *
 * This class is designed to simplify interacting with results returned by
 * LDAP-related functions.
 *
 * This class hasn't been tested much and my not work at all.
 */
class SimpleLDAPResult implements Iterator, ArrayAccess, Countable {

	/**
	 * Tracks the current position for iterator methods
	 */
	protected $position = 0;
	protected $positions;

	/**
	 * The adLDAP result after being processed by the constructor
	 * @var array
	 */
	protected $result;

	/**
	 * Constructor
	 *
	 * Refactors the adLDAP result array into a structure that is easier to use
	 *
	 * @param array $result the result array from adLDAP
	 */
	public function __construct($result)
	{
		if ( is_array($result) )
		{
			if ( $result['count'] == 1 && is_array($result[0]) )
			{
				$result = $result[0];
			}

			unset($result['count']);

			foreach ( $result as $key => $value )
			{
				if ( is_numeric($key) && is_string($value) )
				{
					if ( array_key_exists($value, $result) )
					{
						$this->positions[$key] = $value;
						unset($result[$key]);
					}
				}
			}

			$this->result = $result;
		}
	}

	public function __get($name)
	{
		return new SimpleADResult($this->result[$name]);
	}

	public function __toString()
	{
		if ( is_string($this->result[0]) )
		{
			return $this->result[0];
		}
	}

/* ----------------------------------------------------------------------------
	Iterator Methods
---------------------------------------------------------------------------- */

	public function current()
	{
		$position = empty($this->positions) ? $this->position : $this->positions[$this->position];
		return $this->result[$position];
	}

	public function key()
	{
		return empty($this->positions) ? $this->position : $this->positions[$this->position];;
	}

	public function next()
	{
		$this->position++;
	}

	public function rewind()
	{
		$this->position = 0;
	}

	public function valid()
	{
		if ( empty($this->positions) )
		{
			return array_key_exists($this->position, $this->result);
		}
		else
		{
			return array_key_exists($this->position, $this->positions);
		}
	}

/* ----------------------------------------------------------------------------
	ArrayAccess Methods
---------------------------------------------------------------------------- */

	public function offsetExists($offset)
	{
		return array_key_exists($offset, $this->result);
	}

	public function offsetGet($offset)
	{
		return $this->{$offset};
	}

	public function offsetSet($offset, $value)
	{
		throw new Exception('Setting of properties is unsupported.');
	}

	public function offsetUnset($offset)
	{
		throw new Exception('Unsetting of properties is unsupported.');
	}

/* ----------------------------------------------------------------------------
	Countable Methods
---------------------------------------------------------------------------- */

	public function count()
	{
		return count($this->result);
	}

}
