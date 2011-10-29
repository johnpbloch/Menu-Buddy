<?php

namespace MenuBuddy\Users;

class UserCache extends \MenuBuddy\Cache {

	private static $searchable_fields = array(
		'ID',
		'Login',
		'Email',
	);

	public static function set( $object ){
		if( !is_a( $object, '\\MenuBuddy\\Users\\User' ) )
			return;
		self::$cache[ $object->ID ] = $object;
	}

	public static function find_by( $find, $relation = 'and' ){
		if( empty( self::$cache ) || empty( self::$searchable_fields ) )
			return false;
		if( !is_array( $find ) )
			return false;
		if( !array_intersect( self::$searchable_fields, array_keys( $find ) ) )
			return false;
		$relation = $relation == 'and' ? 'and' : 'or';
		foreach( self::$cache as $object ){
			if( 'or' == $relation ){
				if( array_intersect_assoc( $find, (array)$object ) )
					return $object;
			} else {
				$pass = true;
				foreach( $find as $field => $value ){
					if( $value != $object->$field ){
						$pass = false;
						break;
					}
				}
				if( $pass ){
					return $object;
				}
			}
		}
		return false;
	}

}
