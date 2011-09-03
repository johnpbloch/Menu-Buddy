<?php

namespace MenuBuddy{

	function initialize_database(){
		db();
	}
	
	function db(){
		static $database = null;
		
		if(empty ($database))
			$database = new DataBase();
		
		return $database;
	}
	
}

namespace {
	
	function __autoload( $classname ){
		$classname = end( explode( '\\', $classname ) );
		if( file_exists( CLASSES . $classname . '.php' ) )
			require( CLASSES . $classname . '.php' );
	}
	
}
