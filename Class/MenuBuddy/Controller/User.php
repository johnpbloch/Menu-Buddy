<?php

namespace MenuBuddy\Controller;

use MenuBuddy\Model as M;

class User extends \MenuBuddy\Base
{

	protected function create_form( \Core\Validation $validation = null )
	{
		if( !$validation )
		{
			$validation = new \Core\Validation( array( ) );
		}
		$form = new \Core\Form( $validation );
		$form->username->wrap( 'div' )->input( 'text' )->label( 'Name' );
		$form->email->wrap( 'div' )->input( 'text' )->label( 'Email' );
		$form->pass->wrap( 'div' )->input( 'password' )->label( 'Password' );
		$form->submit->wrap( 'div' )->input( 'submit' )->value( 'Create User' );
		$this->content = new \Core\View( 'MenuBuddy/User/Create' );
		$this->content->fields = $form;
	}

	protected function create_user()
	{
		$username = post( 'username' );
		$email = post( 'email' );
		$pass = post( 'pass' );
		$data = compact( 'username', 'email', 'pass' );
		$validation = new \Core\Validation( $data );
		$validation->field( 'username' )
				->required( 'Username is required!' )
				->min( 'Usernames must be at least 3 characters long!)', 3 )
				->max( 'Usernames may not be more than 50 characters long!', 60 )
				->word( 'Only alphanumeric characters and underscores are allowed!' );
		$validation->field( 'email' )
				->required( 'You must provide an email address!' )
				->email( 'You must provide a valid email address' );
		$validation->field( 'pass' )
				->required( 'You must provide a password!' )
				->min( 'Passwords must be at least 7 characters long!', 7 )
				->max( 'Passwords may not be more than 50 characters long!', 50 );
		if( $validation->validates() )
		{
			$user = new M\User();
			$user->username = $username;
			$user->email = $email;
			$hasher = new \PasswordHash( 12, false );
			$passwordHash = $hasher->HashPassword( $pass );
			$user->pass = $passwordHash;
			$user->active = false;
			$key_length = rand( 40, 50 );
			$user->activation_key = \MenuBuddy\Lib\Util::random_string( $key_length, false );
			$user->account_created = gmdate( 'Y:m:d H:i:s' );
			$user->save();
			$message = 'Almost done! Check your email for an activation link and click it to complete the activation of your account!';
			$this->content = $message;
			$url = site_url( '/user/activate/' . $user->key() . "/$user->activation_key" );
			$message = <<<DOC
<p>Welcome to MenuBuddy!</p>

<p><a href="$url">Click here to activate your account,</a> or go to $url in your browser.</p>
DOC;
			\MenuBuddy\Lib\Util::mail( $user->email, 'Your MenuBuddy account has been created', $message );
			return;
		}
		$this->create_form( $validation );
	}

	protected function delete_form( M\User $user )
	{
		
	}

	protected function delete_user( M\User $user )
	{
		
	}

	protected function edit_form( M\User $user )
	{
		
	}

	protected function edit_user( M\User $user )
	{
		
	}

	public function post( $action = '', $user = false )
	{
		$this->load_database();
		switch( $action )
		{
			case 'create':
				$this->create_user();
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
				$this->create_form();
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
