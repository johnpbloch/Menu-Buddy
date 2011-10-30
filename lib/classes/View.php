<?php

namespace MenuBuddy;

abstract class View {

	protected function link( $maybe_link ){
		$protocol = is_ssl() ? 'https://' : 'http://';
		
		if( preg_match( '@^https?://@', $maybe_link, $matches ) )
			return $protocol . substr( $maybe_link, strlen( $matches[0] ) );
		
		$domain = MB_SITE_ADDRESS;
		if( '/' == substr( $maybe_link, 0, 1 ) )
			return ( is_ssl() ? $protocol . $domain : '' ) . $maybe_link;
		
		$current_page = explode( '?', $_SERVER['REQUEST_URI'] );
		$current_page = trim( $current_page[0], '/' );
		
		$path = '/' . $current_page . '/' . $maybe_link;
		return ( is_ssl() ? $protocol . $domain : '' ) . $path;
	}
	
	protected function asset( $asset_path ){
		$asset_path = trim($asset_path,'/');
		$path = "/template/assets/$asset_path";
		return $this->link($path);
	}
	
	protected function js( $script ){
		$script = trim( $script, '/' );
		$path = "js/$script";
		return $this->asset($script);
	}
	
	protected function css( $script ){
		$script = trim( $script, '/' );
		$path = "css/$script";
		return $this->asset($script);
	}
	
	protected function img( $script ){
		$script = trim( $script, '/' );
		$path = "img/$script";
		return $this->asset($script);
	}

}