<?php

namespace MenuBuddy\Lib;

class GumbyForm extends \Core\Form
{

	public function checkbox( array $options )
	{
		$this->type = 'checkbox';
		$this->options = $options;
		return $this;
	}

	public function radio( array $options )
	{
		$this->type = 'radio';
		$this->options = $options;
		return $this;
	}

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
		if( empty( $this->options ) )
		{
			$this->options = array( 0 => array( $this->label, $this->value ) );
		}
		$inputs = '';
		foreach( $this->options as $key => $option )
		{
			$input_attributes = array(
				'name' => $this->field,
				'id' => $this->field . $key,
				'type' => $this->type,
				'style' => 'display:none',
				'value' => $option[1],
			);
			$label_attributes = array(
				'for' => $input_attributes['id'],
				'class' => $this->type,
			);
			if( $this->type == 'checkbox' )
			{
				$submitted_values = $this->validation->value( $this->field );
				if( ( is_array( $submitted_values ) && in_array( $option[1], $submitted_values ) ) || !empty( $this->attributes['checked'] ) )
				{
					$label_attributes['class'] .= ' checked';
					$input_attributes['checked'] = 'checked';
				}
			}
			else
			{
				if( $this->validation->value( $this->field ) == $option[1] )
				{
					$label_attributes['class'] .= ' checked';
					$input_attributes['checked'] = 'checked';
				}
			}
			$input = \Core\HTML::tag( 'input', false, $input_attributes );
			$input .= '<span></span> ' . $option[0];
			$input = \Core\HTML::tag( 'label', $input, $label_attributes );
			$inputs .= \Core\HTML::tag( 'li', $input );
		}
		$inputs = \Core\HTML::tag( 'ul', $inputs, array( 'class' => 'field row' ) );
		return $inputs;
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
