<?php

namespace MenuBuddy;

class Extension {

	public $ID;
	public $Name;
	public $DisplayName;
	public $Description;
	public $Type;
	public $Dependencies;
	public $ControllerFile;

	function __construct(){
		$this->Dependencies = trim( preg_replace( '|,,+|', ',', $this->Dependencies ), ' ,' );
		$this->Dependencies = explode( ',', $this->Dependencies );
	}

	function initialize(){
		if(!$this->can_initialize())
			return;
		if( file_exists( LIB . "$this->Type/$this->ControllerFile" ) )
			require( LIB . "$this->Type/$this->ControllerFile" );
		else
			require( $this->ControllerFile );
	}

	function can_initialize(){
		return ( ( file_exists( LIB . "$this->Type/$this->ControllerFile" ) ) || ( PATH == substr( $this->ControllerFile, 0, strlen( PATH ) ) && file_exists( $this->ControllerFile ) ) );
	}

}
