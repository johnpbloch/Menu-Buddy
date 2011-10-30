<?php

namespace MenuBuddy;

define( 'LIB', PATH . 'lib/' );
define( 'CLASSES', LIB . 'classes/' );
define( 'FUNC', LIB . 'functions/' );

// To turn off error reporting the next line should be '/*'. To turn it on it should be '//*'
//*
error_reporting( E_ALL );
ini_set( 'display_errors', 1 );
// */

if( file_exists( PATH . 'local-config.php' ) )
	require( PATH . 'local-config.php' );

if( !defined( 'MB_DB_NAME' ) )
	define( 'MB_DB_NAME', '' );

if( !defined( 'MB_DB_USER' ) )
	define( 'MB_DB_USER', '' );

if( !defined( 'MB_DB_PASS' ) )
	define( 'MB_DB_PASS', '' );

if( !defined( 'MB_DB_HOST' ) )
	define( 'MB_DB_HOST', 'localhost' );

if( !defined( 'MB_DB_PREFIX' ) )
	define( 'MB_DB_PREFIX', 'mbd_' );

if( !defined( 'MB_SITE_ADDRESS' ) )
	define( 'MB_SITE_ADDRESS', '' );

if( !defined( 'MB_TEMPLATE' ) )
	define( 'MB_TEMPLATE', 'default' );
