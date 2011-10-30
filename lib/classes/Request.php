<?php

namespace MenuBuddy;

class Request {

	static private $matches = array( );
	static private $core_fragments = array( '_invalid_structure' );

	static function register( $url_fragment, $controller_class ){
		self::clean_fragment( $url_fragment );

		if( in_array( $url_fragment, self::$core_fragments ) )
			return;

		self::$matches[$url_fragment] = $controller_class;
	}

	static private function clean_fragment( &$fragment ){
		if( !is_scalar( $fragment ) )
			return $fragment = '_invalid_structure';

		$fragment = preg_replace( '@[^-_a-z0-9]@i', '', (string)$fragment );

		if( empty( $fragment ) )
			$fragment = '_invalid_structure';
	}

	static function match( $request ){
		$request_parts = explode( '/', $request );
		$root_fragment = array_shift( $request_parts );
		self::clean_fragment( $root_fragment );
		if( empty( self::$matches[$root_fragment] ) )
			return false;
		if( !class_exists( self::$matches[$root_fragment] ) )
			return false;
		return self::$matches[$root_fragment];
	}

}