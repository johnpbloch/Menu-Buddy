<?php

namespace MenuBuddy\Users;

function get_user( $id_or_login_or_email ){
	if( preg_match( '|^\d+$|', $id_or_login_or_email ) )
		$where = '`ID` = %d';
	elseif( \MenuBuddy\is_email( $id_or_login_or_email ) )
		$where = '`Email` = %s';
	else
		$where = '`Login` = %s';

	$DB = \MenuBuddy\db();
	$query = "SELECT * FROM $DB->Users WHERE $where LIMIT 1";
	$result = $DB->query( $DB->prepare( $query, $id_or_login_or_email ) );
	if( $result->num_rows != 1 )
		return false;
	$user = $result->fetch_object( '\\MenuBuddy\\Users\\User' );
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
		return false;
	if( empty( $details[ 'DisplayName' ] ) )
		$details[ 'DisplayName' ] = $details[ 'Login' ];
	if( !preg_match( '|^\d{4}(-\d\d){2} \d\d(:\d\d){2}$|', (string)$details[ 'DateCreated' ] ) )
		$details[ 'DateCreated' ] = date( 'Y-m-d H:i:s' );
	$hasher = new \PasswordHash( 16, false );
	$details[ 'Pass' ] = $hasher->HashPassword( $details[ 'Pass' ] );
	$DB = \MenuBuddy\db();
	$existing_user_check = $DB->get_results( $DB->prepare( "SELECT `ID` FROM $DB->Users WHERE `Login` = %s OR `Email` = %s", $details[ 'Login' ], $details[ 'Email' ] ) );
	if( empty( $existing_user_check ) )
		return $DB->insert( 'Users', $details );
	return false;
}

function validate_auth_cookie(){
	if(!isset ($_COOKIE['MenuBuddyAuth']))
		return false;
	$cookie = $_COOKIE['MenuBuddyAuth'];
	$cookie_parts = explode('|', $cookie);
	if(2 != count($cookie_parts))
		return false;
	$user = get_user($cookie_parts[0]);
	if(!$user)
		return false;
	$value = md5( $user->Email . substr( $user->Pass, 8, 32 ) );
	return $value == $cookie_parts[1];
}
