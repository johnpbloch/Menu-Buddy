<?php

namespace MenuBuddy\Model;

class Recipe extends \Core\ORM
{

	public static $table = 'recipes';
	public static $foreign_key = 'recipe_id';
	public static $has = array(
		'ingredients' => '\MenuBuddy\Model\Ingredient',
	);
	public static $has_many_through = array(
		'meals' => array(
			'recipe_id' => '\MenuBuddy\Model\Dish',
			'meal_id' => '\MenuBuddy\Model\Meal',
		),
	);

}
