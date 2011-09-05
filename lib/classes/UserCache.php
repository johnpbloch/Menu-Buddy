<?php

namespace MenuBuddy\Users;

class UserCache extends \MenuBuddy\Cache {

	private static $searchable_fields = array(
		'Login',
		'Email',
	);

	public static function set( $object ){
		if( !is_a( $object, '\\MenuBuddy\\Users\\User' ) )
			return;
		self::$cache[ $object->ID ] = $object;
	}

	public static function find_by( $field, $value ){
		if( empty( self::$cache ) )
			return false;
		if( !in_array( $field, self::$searchable_fields ) )
			return $this->get( $value );
		foreach( self::$cache as $object ){
			if( $object->$field == $value )
				return $object;
		}
		return false;
	}

}
