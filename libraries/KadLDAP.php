<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * AD/LDAP Module for Kohana
 *
 * @package    KadLDAP
 * @author     Beau Dacious <dacious.beau@gmail.com>
 * @copyright  (c) 2009 Beau Dacious
 * @license    http://www.opensource.org/licenses/mit-license.php
 */

/**
 * Include third-party adLDAP library from vendor directory.
 *
 * @see adLDAP
 */
require_once Kohana::find_file('vendor', 'adLDAP/adLDAP', TRUE);

/**
 * Kohana adLDAP Adapter
 *
 * @todo allow config override in constructor
 */
class KadLDAP_Core {

	/**
	 * Return a static instance of KadLDAP.
	 *
	 * @return object
	 */
	public static function instance($config = array())
	{
		static $instance;

		// Load the KadLDAP instance
		empty($instance) and $instance = new KadLDAP($config);

		return $instance;
	}

// ----------------------------------------------------------------------------

	// Instance of third-party adLDAP library
	protected $adldap;

	/**
	 * Loads third-party adLDAP library.
	 */
	public function __construct($config = array())
	{
		// Append default adldap configuration
		$config += Kohana::config('adldap');

		// Store instantiation of adLDAP library
		$this->adldap = new adLDAP($config);

		Kohana::log('debug', 'KadLDAP Library loaded');
	}


	public function __call($name, $arguments)
	{
		if ( method_exists($this->adldap, $name) )
		{
			return call_user_func_array(array($this->adldap, $name), $arguments);
		}
		else
		{
			throw new Exception('Method ' . $name . ' does not exist.');
		}
	}

// ----------------------------------------------------------------------------

	/**
	 * Override for adLDAP::user_info() method. Prevents the display of errors
	 * if the user does not exist.
	 *
	 * @see adLDAP::user_info()
	 */
	public function user_info()
	{
		$args = func_get_args();
		return @call_user_func_array(array($this->adldap, __FUNCTION__), $args);
	}

}
