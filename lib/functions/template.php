<?php

namespace MenuBuddy\Template;

function is_template_installed(){
	$name = get_installed_template_name();
	return $name == MB_TEMPLATE;
}

function get_template_info( $reset = false ){
	static $info = false;
	if( !$reset && $info != false )
		return $info;
	if( !file_exists( PATH . 'httpdocs/template/info.json' ) )
		return $info = null;
	$info_file = file_get_contents( PATH . 'httpdocs/template/info.json' );
	if( empty( $info_file ) )
		$info = null;
	$info = json_decode( $info_file );
	return $info;
}

function get_installed_template_name(){
	$info = get_template_info();
	if( empty( $info ) || empty( $info->template ) )
		return false;
	return $info->template;
}

function startup_check(){
	if( is_template_installed() )
		return;
	$info = get_template_info();
	if( empty( $info ) ){
		$command = 'ln -s ' . PATH . 'templates/' . MB_TEMPLATE . ' ' . PATH . 'httpdocs/template';
		exec( $command );
		$info = get_template_info( true );
	}
	if( empty( $info ) || empty( $info->template ) || !file_exists( PATH . 'templates/' . $info->template . '/info.json' ) ){
		echo '<h1>Template Error</h1>';
		exit;
	}
}
