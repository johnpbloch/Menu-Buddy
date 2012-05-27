<?php

namespace MenuBuddy\Model;

class Meal extends \Core\ORM
{

	public static $table = 'meals';
	public static $foreign_key = 'meal_id';
	
	public static $has_many_through = array(
		'recipes' => array(
			'meal_id' => '\MenuBuddy\Model\Dish',
			'recipe_id' => '\MenuBuddy\Model\Recipe',
		),
	);

}
