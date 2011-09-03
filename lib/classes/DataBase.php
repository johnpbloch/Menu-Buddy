<?php

namespace MenuBuddy;

class DataBase extends \mysqli {

	private $dbhost;
	
	private $dbuser;
	
	private $dbpass;
	
	private $dbname;
	
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

		$host = ('p:' == substr($host, 0, 2)) ? $host : "p:$host";
		
		$this->dbhost = $host;
		$this->dbuser = $user;
		$this->dbpass = $pass;
		$this->dbname = $name;
		$this->prefix = $prefix;

		parent::__construct( $host, $user, $pass, $name );

		if($this->connect_error){
			die( '<h1>Database Connection Error (' . $this->connect_errno . ')</h1><p>' . $this->connect_error . '</p>' );
		}

		$this->set_charset('utf8');
	}

	public function prepare($query) {
		$args = func_get_args();
		array_shift($args);
		if(empty ($args))
			return parent::prepare ($query);
		if(!empty($args[0]) && is_array($args[0]))
			$args = $args[0];
		$query = str_replace('"%s"', '%s', $query);
		$query = str_replace("'%s'", '%s', $query);
		$query = preg_replace('@(?<!%)%s@', "'%s'", $query);
		array_walk($args, array($this, '_real_escape_by_ref'));
		return @vsprintf($query, $args);
	}

	public function _real_escape_by_ref(&$string){
		$string = $this->real_escape_string($string);
	}

	public function get_results($query) {
		$results = $this->query($query);
		if(false === $results){
			$result = false;
		} elseif(true === $results){
			$result = true;
		} else {
			$result = array();
			while( $obj = $results->fetch_object() ){
				$result[] = $obj;
			}
		}
		$results->close();
		return $result;
	}

}
