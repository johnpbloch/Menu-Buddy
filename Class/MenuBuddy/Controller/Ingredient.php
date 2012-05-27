<?php

namespace MenuBuddy\Controller;

class Ingredient extends \MenuBuddy\Base
{

	public $format = 'HTML';

	public function add( $name, $quantity, $unit, $recipe, $substitution = '' )
	{
		$data = compact( 'name', 'quantity', 'unit', 'recipe', 'substitution' );
		$validation = $this->validate( $data );
		if( !$validation->errors() )
		{
			$ingredient = new \MenuBuddy\Model\Ingredient();
			foreach( $data as $key => $value )
			{
				$ingredient->{$key} = $value;
			}
			$ingredient->save();
		}
	}

	public function edit( $ingredient_id, array $newData )
	{
		if( empty( $newData ) )
		{
			return;
		}
		$ingredient = new \MenuBuddy\Model\Ingredient( $ingredient_id );
		$ingredient->load();
		$data = array_merge( $ingredient->to_array(), $newData );
		$validation = $this->validate( $data );
		if( !$validation->errors() )
		{
			foreach( $newData as $key => $value )
			{
				$ingredient->{$key} = $value;
			}
			$ingredient->save();
		}
	}

	/**
	 * Validate data for Ingredients
	 * 
	 * @param array $data the data to validate
	 * @return \MicromvcExt\Lib\Validation the validation object
	 */
	protected function validate( array $data )
	{
		$validation = new \MicromvcExt\Lib\Validation( $data );
		$validation->field( 'name' )
				->required( 'Name is a required field' )
				->pattern( 'A-Z, 0-9, - _ ! ( ) " . and \' only', '/^[-_!()"\'.a-zA-Z0-9]+$/' );
		$validation->field( 'quantity' )
				->required( 'Quantity is required', false )
				->numeric( 'Quantity must be numeric' )
				->greater_than( 'Quantity must be greater than', 0 );
		$validation->field( 'unit' )
				->required( 'Unit is required' )
				->pattern( 'A-Z, 0-9, - _ ! ( ) " . and \' only', '/^[-_!()"\'.a-zA-Z0-9]+$/' );
		$validation->field( 'recipe' )
				->required( 'Recipe is required', false )
				->integer( 'Recipe must be an integer' )
				->min( 'Recipe must be greater than 0', 1 );
		return $validation;
	}

}
