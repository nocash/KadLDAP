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
 * LDAP Driver for Kohana's Auth module.
 */
class Auth_LDAP_Driver extends Auth_Driver {

	protected $ldap;

	public function __construct(array $config)
	{
		$this->ldap = KadLDAP::instance();
		parent::__construct($config);
	}

/* ----------------------------------------------------------------------------
	Defined Abstract Methods
---------------------------------------------------------------------------- */

	public function login($username, $password, $remember)
	{
		if ( $this->ldap->authenticate($username, $password, TRUE) )
		{
			return $this->complete_login($username);
		}

		return FALSE;
	}

	public function force_login($username)
	{
		if ( $this->user_exists($username) )
		{
			return $this->complete_login($username);
		}
		else
		{
			return FALSE;
		}
	}

	public function password($username)
	{
		return FALSE;
	}

/* ----------------------------------------------------------------------------
	Overridden Parent Methods
---------------------------------------------------------------------------- */

	public function auto_login()
	{
		$username = $this->session->get($this->config['session_key']);

		if ( $this->user_exists($username) )
		{
			return $this->complete_login($username);
		}
	}

/* ----------------------------------------------------------------------------
	Class Methods
---------------------------------------------------------------------------- */

	protected function user_exists($username)
	{
		$userinfo = $this->ldap->user_info($username);
		return ( is_array($userinfo) && array_key_exists('count', $userinfo) && $userinfo['count'] == 1);
	}

}
