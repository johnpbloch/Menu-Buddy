<?php

namespace MenuBuddy\Controller;

class Index extends \MenuBuddy\Base
{

	function run()
	{
		$this->content = new \Core\View( 'MenuBuddy/Index' );
	}

}
