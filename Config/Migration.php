<?php

$config = array(
	'users' => array(
		'id' => array( 'type' => 'primary' ),
		'username' => array( 'type' => 'string', 'length' => 60, 'unique' => true, 'null' => false ),
		'email' => array( 'type' => 'string', 'length' => 100, 'unique' => true, 'null' => false ),
		'pass' => array( 'type' => 'string', 'length' => 60, 'null' => false ),
		'active' => array( 'type' => 'boolean', 'default' => false ),
		'activation_key' => array( 'type' => 'string', 'length' => 60 ),
		'account_created' => array( 'type' => 'datetime' ),
		'last_login' => array( 'type' => 'datetime' ),
	),
);
