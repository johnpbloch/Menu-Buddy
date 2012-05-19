<?php

namespace MenuBuddy\Controller;

class Index extends \MenuBuddyBase
{

	function run()
	{
		$this->content = new \Core\View( 'MenuBuddy/Index' );
	}

}
