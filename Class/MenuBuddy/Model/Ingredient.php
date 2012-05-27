<?php

namespace MenuBuddy\Model;

class Ingredient extends \Core\ORM
{

	public static $table = 'ingredients';
	public static $belongs_to = array(
		'recipe' => '\MenuBuddy\Model\Recipe',
	);

}
