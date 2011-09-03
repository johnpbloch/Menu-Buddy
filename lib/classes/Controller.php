<?php

namespace MenuBuddy;

abstract class Controller {
	
	protected $view;
	
	protected $model;
	
	protected $request = '';


	abstract public function run();
	
	protected function parse_request(){
		$this->get_request();
	}
	
	protected function get_request(){
		$request_string = explode( '?', $_SERVER['REQUEST_URI'] );
		$request_string = $request_string[0];
		$this->request = trim($request_string, '/');
	}
	
}
