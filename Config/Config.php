<?php

/**
 * Config
 *
 * Core system configuration file
 *
 * @package		MicroMVC
 * @author		David Pennington
 * @copyright	(c) 2010 MicroMVC Framework
 * @license		http://micromvc.com/license
 * ********************************* 80 Columns *********************************
 */
// Base site url - Not currently supported!
$config['site_url'] = '/';

// Enable debug mode?
$config['debug_mode'] = FALSE;

// Load boostrap file?
$config['bootstrap'] = FALSE;

// Available translations (Array of Locales)
$config['languages'] = array( 'en' );

$config['database'] = array(
	'dns' => "mysql:host=127.0.0.1;port=3306;dbname=micromvc",
	'username' => 'root',
	'password' => '',
	//'dns' => "pgsql:host=localhost;port=5432;dbname=micromvc",
	//'username' => 'postgres',
	//'password' => 'postgres',
	'params' => array()
);


/**
 * System Events
 */
$config['events'] = array(
		//'pre_controller'	=> 'Class::method',
		//'post_controller'	=> 'Class::method',
);

/**
 * Cookie Handling
 *
 * To insure your cookies are secure, please choose a long, random key!
 * @link http://php.net/setcookie
 */
$config['cookie'] = array(
	'key' => 'very-secret-key',
	'timeout' => time() + (60 * 60 * 4), // Ignore submitted cookies older than 4 hours
	'expires' => 0, // Expire on browser close
	'path' => '/',
	'domain' => '',
	'secure' => '',
	'httponly' => '',
);

/**
 * Allow local config files to overwrite configuration settings. 
 */
if( file_exists( __FILE__ . '.local' ) )
{
	require __FILE__ . '.local';
}
