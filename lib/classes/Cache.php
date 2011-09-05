<?php

namespace MenuBuddy;

abstract class Cache {

	protected static $cache = array( );

	public static function set( $key, $object ){
		self::$cache[ $key ] = $object;
	}

	public static function get( $key ){
		return empty( self::$cache[ $key ] ) ? false : self::$cache[ $key ];
	}

	public static function delete( $key ){
		if( isset( self::$cache[ $key ] ) )
			unset( self::$cache[ $key ] );
	}

}