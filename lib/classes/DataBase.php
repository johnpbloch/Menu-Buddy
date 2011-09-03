<?php

namespace MenuBuddy;

class DataBase extends \mysqli {
	
	protected $prefix;

	public function __construct( $name='', $user='', $pass='', $host='', $prefix='' ) {
		if( empty ($name) )
			$name = MB_DB_NAME;
		if( empty ($user) )
			$user = MB_DB_USER;
		if( empty ($pass) )
			$pass = MB_DB_PASS;
		if( empty ($host) )
			$host = MB_DB_HOST;
		if( empty ($prefix) )
			$prefix = MB_DB_PREFIX;
		
		$host = preg_match('/^p:/', $host) ? $host : "p:$host";
		
		$this->prefix = $prefix;
		
		parent::__construct( $host, $user, $pass, $name );
		
		if($this->connect_error){
			die( '<h1>Database Connection Error (' . $this->connect_errno . ')</h1><p>' . $this->connect_error . '</p>' );
		}
	}
	
}
