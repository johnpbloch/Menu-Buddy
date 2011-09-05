<?php

/**
 * The abstract Cache class lays the groundwork for implementations extending
 * it. It's fairly basic and simple, only setting the bare necessities for
 * in-memory object caching.
 * 
 * @package MenuBuddy
 * @author John P. Bloch
 * @license GPLv2
 */

namespace MenuBuddy;

/**
 * Cache class. Used as a template for more specific caching classes
 * 
 * @since 1.0
 * @abstract
 */
abstract class Cache {

	/**
	 * Holds cached objects in an associative array
	 * 
	 * @var array Associative array holding an extension's object cache
	 * @since 1.0
	 */
	protected static $cache = array( );

	/**
	 * Set or update new elements in the object cache
	 *
	 * @since 1.0
	 * @param string|int $key
	 * @param mixed $object Whatever data of whatever type needs to be stored
	 */
	public static function set( $key, $object ){
		self::$cache[ $key ] = $object;
	}

	/**
	 * Retrieve stored values
	 *
	 * @since 1.0
	 * @param string|int $key
	 * @return mixed false if the key doesn't exist, the stored value otherwise
	 */
	public static function get( $key ){
		return empty( self::$cache[ $key ] ) ? false : self::$cache[ $key ];
	}

	/**
	 * Delete objects from the cache
	 *
	 * @since 1.0
	 * @param string|int $key
	 */
	public static function delete( $key ){
		if( isset( self::$cache[ $key ] ) )
			unset( self::$cache[ $key ] );
	}

}