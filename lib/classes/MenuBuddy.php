<?php

namespace MenuBuddy;

class MenuBuddy extends Controller {

	function __construct(){
		$this->parse_request();
	}

	public function run(){
		echo '<pre>';
		var_dump( $_SERVER );
		echo '</pre>';
	}

}
