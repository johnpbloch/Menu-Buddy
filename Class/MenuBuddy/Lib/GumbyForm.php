<?php

namespace MenuBuddy\Lib;

class GumbyForm extends \Core\Form
{

	protected function render_field()
	{
		if( !$this->attributes )
		{
			$this->attributes = array( );
		}

		if( $this->value === NULL )
		{
			$this->value = $this->validation->value( $this->field );
		}

		if( !$this->tag )
		{
			$this->tag = 'div';
		}

		switch( $this->type )
		{
			case 'textarea':
				return $this->render_textarea();
			case 'select':
				return $this->render_select();
			case 'checkbox':
			case 'radio':
				return $this->render_checkbox_radio();
			case 'submit':
				return $this->render_submit_button();
			case 'hidden':
				$isHidden = true;
				break;
			default:
				$isHidden = false;
		}

		$attributes = $this->attributes + array(
			'type' => $this->type,
			'value' => $this->value,
		);
		$label = '';
		if( $this->label && !$isHidden )
		{
			$attributes['placeholder'] = (string)$this->label;
			$label = \Core\HTML::tag( 'label', $this->label, array( 'for' => $this->field, 'class' => 'placeholderAlt' ) );
		}

		$input = \Core\HTML::tag( 'input', 0, $attributes );

		if( !$isHidden )
		{
			$input = \Core\HTML::tag( 'div', $input, array( 'class' => 'text' ) );
		}

		$error = '';
		if( !$isHidden && $errorMessage = $this->validation->error( $this->field ) )
		{
			if( isset( $attributes['class'] ) )
			{
				$attributes['class'] .= ' error';
			}
			else
			{
				$attributes['class'] = $this->field . ' ' . $this->type . ' error';
			}

			$error = "\n<div class=\"error_message\">$errorMessage</div>";
		}

		return \Core\HTML::tag( $this->tag, $label . $input . $error, array( 'class' => 'field row' ) );
	}

	protected function render_checkbox_radio()
	{
		
	}

	protected function render_select()
	{
		
	}

	protected function render_textarea()
	{
		
	}

	protected function render_submit_button()
	{
		
	}

}
