<?php

namespace MenuBuddy;

class Extensions {

	private static $ext = array( );

	public static function initialize(){
		$DB = db();
		$results = $DB->query( "SELECT * FROM $DB->Extensions WHERE `Active` = 1 ORDER BY `Type` ASC" );
		$extensions = array();
		while ($obj = $results->fetch_object('\\MenuBuddy\\Extension')){
			$extensions[] = $obj;
		}
		if( empty( $extensions ) )
			return;
		$extensions = self::setup_extensions_array( $extensions );
		self::$ext = self::check_dependencies($extensions);
		foreach(self::$ext as $extension){
			$extension->initialize();
		}
	}

	private static function setup_extensions_array( $extensions ){
		$new_array = array( );
		foreach( $extensions as $extension ){
			$new_array[ $extension->Name ] = $extension;
		}
		return $new_array;
	}

	private static function check_dependencies( $extensions ){
		$good_to_go = array( );
		$temp_extensions = array_values( $extensions );
		$all_extensions = array_keys( $extensions );
		while( $temp_extensions ){
			$current = array_shift( $temp_extensions );
			if( array_diff( $current->Dependencies, $all_extensions ) || !$current->can_initialize() )
				continue;
			if( !array_diff( $current->Dependencies, $good_to_go ) )
				$good_to_go[ ] = $current->Name;
			else
				$temp_extensions[ ] = $current;
		}
		$resolved_extensions = array( );
		foreach( $good_to_go as $ext ){
			$resolved_extensions[ $ext ] = $extensions[ $ext ];
		}
		return $resolved_extensions;
	}

}
