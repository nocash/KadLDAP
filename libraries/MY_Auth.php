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
 * Auth
 *
 * Overrides some functionality when using the LDAP driver.
 */
class Auth extends Auth_Core {

	/**
	 * Login method override for Auth module.
	 *
	 * The Auth module salts all passwords before passing them around. This is
	 * no good if we're working with LDAP.
	 *
	 * @see Auth_Core::login()
	 */
	public function login($username, $password, $remember = FALSE)
	{
		if (empty($password))
			return FALSE;

		if ( $this->config['driver'] == 'LDAP' )
		{
			return $this->driver->login($username, $password, $remember);
		}
		else
		{
			return parent::login($username, $password, $remember);
		}
	}

}
