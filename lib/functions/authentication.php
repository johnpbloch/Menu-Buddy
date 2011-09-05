<?php

namespace MenuBuddy\Auth;

function check_password( $user, $password ){
	$user = \MenuBuddy\Users\get_user( $user );
	if( empty( $user ) )
		return false;
	$hasher = new \PasswordHash( 16, false );
	return $hasher->CheckPassword( $password, $user->Pass );
}

function cookie( $user, $remember = false ){
	if( !is_object( $user ) || !is_a( $user, '\\MenuBuddy\\Users\\User' ) ){
		$user = \MenuBuddy\Users\get_user( $user );
	}
	if( !$user || empty( $user->ID ) )
		return delete();
	$value = $user->Login;
	$value .= '|';
	$value .= md5( $user->Email . substr( $user->Pass, 8, 32 ) );
	\setcookie( 'MenuBuddyAuth', $value, ($remember ? time() + 3600 * 24 * 14 : 0 ), '/', '', false, true );
}

function delete(){
	\setcookie( 'MenuBuddyAuth', '', time() - 3600, '/', '', false, true );
	return false;
}
