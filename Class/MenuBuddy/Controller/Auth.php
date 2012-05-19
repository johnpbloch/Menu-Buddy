<?php

namespace MenuBuddy\Controller;

class Auth extends \MenuBuddy\Base
{

	protected function canonicalRedirect()
	{
		/* If we're already using the canonical route or if the PATH
		 * doesn't begin with the route being used (i.e. this controller
		 * is being called manually, not automatically with the URL),
		 * get out of here before any other checks.
		 */
		if( $this->route == 'auth' ||
				!preg_match( '@^/' . preg_quote( $this->route, '@' ) . '(/|$)@', PATH ) )
		{
			return;
		}
		$this->route = trim( $this->route, '/' );
		$url = site_url( "/auth/$this->route" );
		redirect( $url, 301 );
		exit;
	}

	function run()
	{
		$this->canonicalRedirect();
		$this->content = 'This is the content';
	}

}
