<?php

namespace MenuBuddy {

	function initialize_database(){
		db();
	}

	function db(){
		static $database = null;

		if( empty( $database ) )
			$database = new DataBase();

		return $database;
	}

	function is_email( $maybe_email ){
		return preg_match( '|^([-_a-z0-9]+(\.[-_a-z0-9]+)*)@([-a-z0-9]+(\.[-a-z0-9]+)*(\.[a-z])+)$|i', $maybe_email );
	}

}

namespace {

	function __autoload( $classname ){
		$classname = end( explode( '\\', $classname ) );
		if( file_exists( CLASSES . $classname . '.php' ) )
			require( CLASSES . $classname . '.php' );
	}

}
