<?php

namespace MenuBuddy;

class MenuBuddy extends Controller {
	
	private $action = null;

	function __construct(){
		$this->parse_request();
	}

	public function run(){
		$action_controller = Request::match( $this->request );
		if($action_controller)
			$this->action = new $action_controller;
	}

}
