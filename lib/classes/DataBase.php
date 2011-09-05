<?php
/**
 * Database class used to interact with the database for all parts of the program.
 * 
 * @package MenuBuddy
 * @author John P. Bloch
 * @license GPLv2
 */
namespace MenuBuddy;

/**
 * Database class. It extends MySQLi.
 * 
 * @since 1.0
 */
class DataBase extends \mysqli {

	/**
	 * Hostname for our database server (probably localhost).
	 *
	 * @var string $dbhost The mysql server we're connecting to
	 * @since 1.0
	 */
	private $dbhost;
	/**
	 * Username for our database user.
	 *
	 * @var string $dbuser The mysql user we're connecting with
	 * @since 1.0
	 */
	private $dbuser;
	/**
	 * Password for our database user.
	 *
	 * @var string $dbpass Our mysql user's password
	 * @since 1.0
	 */
	private $dbpass;
	/**
	 * Database to use once we're connected.
	 *
	 * @var string $dbname The mysql database we're connecting to
	 * @since 1.0
	 */
	private $dbname;
	/**
	 * Prefix for our database table names
	 *
	 * @var string $prefix Will prefix all our table names.
	 * @since 1.0
	 */
	protected $prefix;

	/**
	 * Constructor. Sets up the connection to the database and initializes the table pointers
	 * for core tables.
	 *
	 * @since 1.0
	 * @param string $name Database name
	 * @param string $user Database user
	 * @param string $pass Database user's password
	 * @param string $host Database server's hostname/ip address
	 * @param string $prefix Table name prefix
	 */
	public function __construct( $name='', $user='', $pass='', $host='', $prefix='' ){
		
		// If we're missing any arguments, get them from constants defined in our config file.
		if( empty( $name ) )
			$name = MB_DB_NAME;
		if( empty( $user ) )
			$user = MB_DB_USER;
		if( empty( $pass ) )
			$pass = MB_DB_PASS;
		if( empty( $host ) )
			$host = MB_DB_HOST;
		if( empty( $prefix ) )
			$prefix = MB_DB_PREFIX;

		// Make sure we're setting up a persistent connection.
		$host = ('p:' == substr( $host, 0, 2 )) ? $host : "p:$host";

		// Store values in our properties.
		$this->dbhost = $host;
		$this->dbuser = $user;
		$this->dbpass = $pass;
		$this->dbname = $name;
		$this->prefix = $prefix;

		// Connect to the database
		parent::__construct( $host, $user, $pass, $name );

		// We need a database connection, so get out if there's an error.
		if( $this->connect_error ){
			die( '<h1>Database Connection Error (' . $this->connect_errno . ')</h1><p>' . $this->connect_error . '</p>' );
		}

		$this->set_charset( 'utf8' );

		// Initialize tables
		$this->init_tables();
	}

	/**
	 * Prepare a query for execution.
	 * 
	 * This overwrites the MySQLi method of the same name {@link http://us2.php.net/manual/en/mysqli.prepare.php}
	 * If this method is called with only one argument, it falls back to that behavior and will return
	 * an object of the MySQLi_STMT class.
	 * 
	 * If more than one argument is present, the method will allow sprintf-like formatting for queries. The
	 * majority of this method was copied from WordPress.
	 *
	 * @since 1.0
	 * @uses vsprintf()|\MenuBuddy\Database::_real_escape_by_ref()
	 * @param string $query The query to prepare for execution
	 * @return string|mysqli_stmt A MySQLi_STMT object of a string or the formatted SQL statement.
	 */
	public function prepare( $query ){
		
		// Grab all arguments used and pop the first one off
		$args = func_get_args();
		array_shift( $args );
		
		// If we don't have more than one argument, fall back to \MySQLi::prepare()
		if( empty( $args ) )
			return parent::prepare( $query );
		
		// If our first argument is an array, treat that as our arguments.
		if( !empty( $args[ 0 ] ) && is_array( $args[ 0 ] ) )
			$args = $args[ 0 ];
		
		// Remove quotes around the string identifiers so that we can add our own.
		$query = str_replace( '"%s"', '%s', $query );
		$query = str_replace( "'%s'", '%s', $query );
		$query = preg_replace( '@(?<!%)%s@', "'%s'", $query );
		
		// Sanitize the values and prepare the query.
		array_walk( $args, array( $this, '_real_escape_by_ref' ) );
		return @vsprintf( $query, $args );
	}

	/**
	 * Escapes strings for use in queries, but does so by reference
	 * 
	 * Inspiration for this method came from WordPress.
	 *
	 * @since 1.0
	 * @param string $string The string to escape by reference
	 */
	public function _real_escape_by_ref( &$string ){
		$string = $this->real_escape_string( $string );
	}

	/**
	 * This method provides a layer of abstraction for executing queries against the
	 * database. It is useful for basic queries where the results object doesn't need
	 * to be accessed directly, merely the results.
	 * 
	 * If the query() method returns a boolean, this function will return that boolean
	 * (false means the query failed, true means the query executed successfully, but
	 * did not return anything). Otherwise, the function will put the results into an
	 * array of objects and return that array. This could be an empty array if no results
	 * were returned on a returning query.
	 * 
	 * This function also handles cleaning up after the query by running the close method
	 * of the results object returned (if there is one).
	 *
	 * @since 1.0
	 * @param string $query The query to execute
	 * @return bool|array true or false if no records fetched, an array of objects otherwise
	 */
	public function get_results( $query ){
		$results = $this->query( $query );
		if( false === $results ){
			$result = false;
		} elseif( true === $results ){
			$result = true;
		} else {
			$result = array( );
			while( $obj = $results->fetch_object() ){
				$result[ ] = $obj;
			}
		}
		$results->close();
		return $result;
	}

	/**
	 * An abstraction layer for inserting into the database. Takes care of escaping
	 * the query too.
	 * 
	 * Inspired by WordPress' similar method.
	 *
	 * @since 1.0
	 * @param string $table The table into which to insert data
	 * @param array $data An associative array keyed by column names
	 * @return bool Returns true if successful, false otherwise
	 */
	public function insert( $table, $data ){
		if( isset( $this->$table ) )
			$table = $this->$table;
		$columns = implode( '`,`', array_keys( $data ) );
		$formats = '';
		foreach( $data as $value ){
			if( is_numeric( $value ) )
				$format = '%d';
			else
				$format = '%s';
			$formats .= ",$format";
		}
		$formats = ltrim( $formats, ',' );
		$query = "INSERT INTO `$table` (`$columns`) VALUES ($formats)";
		return $this->query( $this->prepare( $query, array_values( $data ) ) );
	}

	/**
	 * Initialize core tables. Sets up properties corresponding to the tables
	 * with their prefixed values. Also checks for a schema installation script
	 * if the table is not in the database and the constant is defined to allow
	 * for it.
	 * 
	 * @since 1.0
	 */
	private function init_tables(){
		$tables = array(
			'Users',
		);
		$check_tables = (defined( 'CHECK_TABLES' ) && CHECK_TABLES);
		$table_list = $check_tables ? $this->fetch_table_list() : false;
		foreach( $tables as $table ){
			$this->$table = $this->prefix . $table;
			if( $check_tables && !in_array( $this->$table, $table_list ) ){
				$this->install_table( $table );
			}
		}
	}

	/**
	 * Fetches a list of all tables in the database. Should only be used
	 * when installing tables
	 *
	 * @since 1.0
	 * @return array The list of all tables in the database
	 */
	private function fetch_table_list(){
		$tables = $this->query( 'SHOW TABLES' );
		$table_list = array( );
		if( $tables === false || $tables->num_rows == 0 )
			return $table_list;
		while( $table = $tables->fetch_row() ){
			$table_list = array_merge( $table_list, $table );
		}
		$tables->close();
		return $table_list;
	}

	/**
	 * Install the table schema if the script with the schema definition
	 * exists.
	 *
	 * @since 1.0
	 * @param string $table The table to install
	 */
	private function install_table( $table ){
		if( file_exists( PATH . "schema/$table.php" ) ){
			require( PATH . "schema/$table.php" );
		}
	}

}
