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
 * LDAP Model
 *
 * This is to be extended by actual models.
 */
class LDAP_Model {

	protected $ldap;

	protected $loaded = FALSE;

	public function __construct()
	{
		$this->ldap = KadLDAP::instance();
	}

	public function is_loaded()
	{
		return $this->loaded;
	}

}
