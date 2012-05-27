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
	'ingredients' => array(
		'id' => array( 'type' => 'primary' ),
		'name' => array( 'type' => 'string', 'length' => 120, 'null' => false, ),
		'quantity' => array( 'type' => 'decimal', 'length' => 8, 'null' => false, 'precision' => 8, 'scale' => 4 ),
		'unit' => array( 'type' => 'string', 'length' => 30, ),
		'substitution' => array( 'type' => 'string', 'length' => 255, ),
		'recipe_id' => array( 'type' => 'integer', 'length' => 60, 'null' => false, 'index' => true ),
	),
	'recipes' => array(
		'id' => array( 'type' => 'primary' ),
		'name' => array( 'type' => 'string', 'length' => 120, 'null' => false, ),
		'prep_time' => array( 'type' => 'integer', 'length' => 6, 'default' => 0, ),
		'cook_time' => array( 'type' => 'integer', 'length' => 6, 'default' => 0, ),
		'servings' => array( 'type' => 'integer', 'length' => 3 ),
		'instructions' => array( 'type' => 'string', 'null' => false, ),
		'user_id' => array( 'type' => 'integer', 'length' => 60, 'null' => false, 'index' => true ),
	),
	'dishes' => array(
		'id' => array( 'type' => 'primary' ),
		'recipe_id' => array( 'type' => 'integer', 'length' => 60, 'null' => false, 'index' => true ),
		'meal_id' => array( 'type' => 'integer', 'length' => 60, 'null' => false, 'index' => true ),
	),
	'meals' => array(
		'id' => array( 'type' => 'primary' ),
		'meal' => array( 'type' => 'string', 'length' => 20, 'null' => false, 'default' => 'Dinner', 'index' => true ),
		'date' => array( 'type' => 'datetime', 'null' => false ),
	),
);
