<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * AD/LDAP Module for Kohana
 *
 * @package    KadLDAP
 * @author     Beau Dacious <dacious.beau@gmail.com>
 * @copyright  (c) 2009 Beau Dacious
 * @license    http://www.opensource.org/licenses/mit-license.php
 */

$config['domain_controllers'] = array('dc01.mydomain.local');

$config['account_suffix'] = '@mydomain.local';
$config['base_dn'] = 'dc=mydomain,dc=local';

$config['ad_username'] = NULL;
$config['ad_password'] = NULL;
