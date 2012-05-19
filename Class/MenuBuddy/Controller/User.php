<?php

namespace MenuBuddy\Controller;

class User extends \MenuBuddy\Base
{

	protected function create_form()
	{
		
	}

	public function post( $action = '', $user = false )
	{
		$this->load_database();
		switch( $action )
		{
			case 'create':
				break;
			case 'delete':
				break;
			case 'edit':
				break;
			default:
				$this->show_404();
				break;
		}
	}

	public function get( $action = '', $user = false )
	{
		$this->load_database();
		switch( $action )
		{
			case 'create':
				break;
			case 'delete':
				break;
			case 'edit':
				break;
			case 'list':
				break;
			case 'profile':
				break;
			default:
				$this->show_404();
				break;
		}
	}

	public function run()
	{
		$this->show_404();
	}

}
