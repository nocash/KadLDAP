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
 * LDAP Group Model
 */
class LDAP_Group_Model extends LDAP_Model {

/* ----------------------------------------------------------------------------
  Static Methods and Properties
---------------------------------------------------------------------------- */

	public static function factory($groupname = NULL)
	{
		$group = new LDAP_Group_Model;

		if ( NULL !== $groupname )
		{
			$group->get($groupname);
		}

		return $group;
	}

/* ----------------------------------------------------------------------------
  Non-Static Methods and Properties
---------------------------------------------------------------------------- */

	protected $groupinfo = array();

	public function __get($name)
	{
		if ( array_key_exists($name, $this->groupinfo) )
		{
			$value = $this->groupinfo[$name];

			if ( is_array($value) )
			{
				if ( array_key_exists('count', $value) )
				{
					unset($value['count']);
				}

				$value = ( count($value) == 1 ) ? reset($value) : $value;
			}

			return $value;
		}
	}

	public function get($group)
	{
		$groupinfo = $this->ldap->group_info($group);

		if ( ! is_array($groupinfo) || $groupinfo['count'] == 0 )
		{
			return FALSE;
		}

		// Let's tidy up this array real quick...

		$groupinfo = $groupinfo[0]; // Don't need that anymore...

		foreach ( $groupinfo as $key => $value )
		{
			if ( $key == 'count' || ( is_numeric($key) && array_key_exists($value, $groupinfo) ) )
			{
				unset($groupinfo[$key]);
			}
		}

		$this->groupinfo = $groupinfo;
		$this->loaded = TRUE;

		return $this; // method chaining
	}

	public function has_member($user)
	{
		// user model
		if ( $user instanceof LDAP_User_Model )
		{
			return in_array($user->dn, $this->groupinfo['member']);
		}

		// dn
		if ( in_array($user, $this->groupinfo['member']) )
		{
			return TRUE;
		}

		// display name
		foreach ( $this->groupinfo['member'] as $value )
		{
			if ( preg_match("/^CN={$user}/", $value) > 0 )
			{
				return TRUE;
			}
		}

		// samaccountname
		$ldap_user = new LDAP_User_Model;
		$ldap_user->get($user);

		if ( $this->has_member($ldap_user) )
		{
			return TRUE;
		}

		return FALSE;
	}

	public function is_member_of($group)
	{
		// group model
		if ( $group instanceof LDAP_Group_Model )
		{
			return in_array($group->dn, $this->groupinfo['memberof']);
		}

		// dn
		if ( in_array($group, $this->groupinfo['memberof']) )
		{
			return TRUE;
		}

		// simple name
		foreach ( $this->groupinfo['memberof'] as $value )
		{
			if ( preg_match("/^CN={$group}/", $value) > 0 )
			{
				return TRUE;
			}
		}

		return FALSE;
	}

}
