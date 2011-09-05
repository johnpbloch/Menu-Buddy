<?php

namespace MenuBuddy;

function get_user( $id_or_login_or_email ){
	if(  preg_match( '|^\d+$|', $id_or_login_or_email))
		$where = '`ID` = %d';
	elseif( preg_match( '|([-_a-z0-9]+(\.[-_a-z0-9]+)*)@([-a-z0-9]+(\.[-a-z0-9]+)*(\.[a-z])+)|', $id_or_login_or_email) )
		$where = '`Email` = %s';
	else
		$where = '`Login` = %s';
	
	$DB = db();
	$query = "SELECT * FROM $DB->Users WHERE $where LIMIT 1";
	$result = $DB->query( $DB->prepare( $query, $id_or_login_or_email ) );
	if( $result->num_rows != 1 )
		return false;
	$user = $result->fetch_object( '\\'.__NAMESPACE__.'\\User' );
	return $user;
}
