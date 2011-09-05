<?php

namespace MenuBuddy;

function get_user( $id_or_login_or_email ){
	if( preg_match( '|^\d+$|', $id_or_login_or_email ) )
		$where = '`ID` = %d';
	elseif( is_email( $id_or_login_or_email ) )
		$where = '`Email` = %s';
	else
		$where = '`Login` = %s';

	$DB = db();
	$query = "SELECT * FROM $DB->Users WHERE $where LIMIT 1";
	$result = $DB->query( $DB->prepare( $query, $id_or_login_or_email ) );
	if( $result->num_rows != 1 )
		return false;
	$user = $result->fetch_object( '\\' . __NAMESPACE__ . '\\User' );
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
	if( empty( $details[ 'Login' ] ) || empty( $details[ 'Email' ] ) || !is_email( $details[ 'Email' ] ) || empty( $details[ 'Pass' ] ) )
		return false;
	if( empty( $details[ 'DisplayName' ] ) )
		$details[ 'DisplayName' ] = $details[ 'Login' ];
	if( !preg_match( '|^\d{4}(-\d\d){2} \d\d(:\d\d){2}$|', (string)$details[ 'DateCreated' ] ) )
		$details[ 'DateCreated' ] = date( 'Y-m-d H:i:s' );
	$hasher = new \PasswordHash( 16, false );
	$details[ 'Pass' ] = $hasher->HashPassword( $details[ 'Pass' ] );
	$DB = db();
	$existing_user_check = $DB->get_results( $DB->prepare( "SELECT `ID` FROM $DB->Users WHERE `Login` = %s OR `Email` = %s", $details[ 'Login' ], $details[ 'Email' ] ) );
	if( empty( $existing_user_check ) )
		return $DB->insert( 'Users', $details );
	return false;
}
