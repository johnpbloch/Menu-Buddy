<?php

namespace MenuBuddy\Users;

function get_user( $id_or_login_or_email ){
	if( preg_match( '|^\d+$|', $id_or_login_or_email ) ){
		$cache = UserCache::get( $id_or_login_or_email );
		$where = '`ID` = %d';
	} elseif( \MenuBuddy\is_email( $id_or_login_or_email ) ){
		$cache = UserCache::find_by( 'Email', $id_or_login_or_email );
		$where = '`Email` = %s';
	} else {
		$cache = UserCache::find_by( 'Login', $id_or_login_or_email );
		$where = '`Login` = %s';
	}

	if( $cache )
		return $cache;

	$DB = \MenuBuddy\db();
	$query = "SELECT * FROM $DB->Users WHERE $where LIMIT 1";
	$result = $DB->query( $DB->prepare( $query, $id_or_login_or_email ) );
	if( $result->num_rows != 1 )
		return false;
	$user = $result->fetch_object( '\\MenuBuddy\\Users\\User' );
	UserCache::set( $user );
	return $user;
}

function create_user( $details ){
	$defaults = array(
		'Login' => false,
		'Email' => false,
		'DisplayName' => false,
		'DateCreated' => false,
		'Pass' => false,
	);
	$details = array_merge( $defaults, array_intersect_key( (array)$details, $defaults ) );
	if( empty( $details[ 'Login' ] ) || empty( $details[ 'Email' ] ) || !\MenuBuddy\is_email( $details[ 'Email' ] ) || empty( $details[ 'Pass' ] ) )
		return new \Exception( 'Not enough information', 1 );
	if( empty( $details[ 'DisplayName' ] ) )
		$details[ 'DisplayName' ] = $details[ 'Login' ];
	if( !preg_match( '|^\d{4}(-\d\d){2} \d\d(:\d\d){2}$|', (string)$details[ 'DateCreated' ] ) )
		$details[ 'DateCreated' ] = date( 'Y-m-d H:i:s' );
	$hasher = new \PasswordHash( 16, false );
	$details[ 'Pass' ] = $hasher->HashPassword( $details[ 'Pass' ] );
	$DB = \MenuBuddy\db();
	$existing_user_check = $DB->get_results( $DB->prepare( "SELECT `ID` FROM $DB->Users WHERE `Login` = %s OR `Email` = %s", $details[ 'Login' ], $details[ 'Email' ] ) );
	if( !empty( $existing_user_check ) ){
		if( count( $existing_user_check ) == 2 )
			return new \Exception( 'Username and email already exist', 4 );
		elseif( $existing_user_check[ 0 ]->Email == $details[ 'Email' ] )
			return new \Exception( 'Email address already in use', 3 );
		else
			return new \Exception( 'Username already exists', 2 );
	}
	$result = $DB->insert( 'Users', $details );
	return $result ? get_user( $details[ 'Email' ] ) : false;
}

function validate_auth_cookie(){
	if( !isset( $_COOKIE[ 'MenuBuddyAuth' ] ) )
		return false;
	$cookie = $_COOKIE[ 'MenuBuddyAuth' ];
	$cookie_parts = explode( '|', $cookie );
	if( 2 != count( $cookie_parts ) ){
		\MenuBuddy\Auth\delete();
		return false;
	}
	$user = get_user( $cookie_parts[ 0 ] );
	if( !$user ){
		\MenuBuddy\Auth\delete();
		return false;
	}
	$value = md5( $user->Email . substr( $user->Pass, 8, 32 ) );
	if( $value == $cookie_parts[ 1 ] )
		return $user->ID;
	else
		\MenuBuddy\Auth\delete();
	return false;
}
