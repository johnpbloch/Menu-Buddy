<?php

namespace MenuBuddy\Controller;

use MenuBuddy\Model as M;
use MenuBuddy\Lib as L;

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
			$user->activation_key = L\Util::random_string( $key_length, false );
			$user->account_created = L\Util::sqltime();
			$user->save();
			$message = 'Almost done! Check your email for an activation link and click it to complete the activation of your account!';
			$url = site_url( '/user/activate/' . $user->key() . "/$user->activation_key" );
			$message .= '<a href="' . $url . '">Activate</a>';
			$this->content = $message;
			$message = <<<DOC
<p>Welcome to MenuBuddy!</p>

<p><a href="$url">Click here to activate your account,</a> or go to $url in your browser.</p>
DOC;
			L\Util::mail( $user->email, 'Your MenuBuddy account has been created', $message );
			return;
		}
		$this->create_form( $validation );
	}

	protected function activate_user( M\User $user, $code )
	{
		if( $user->key() === null )
		{
			throw new \Exception( 'That user does not exist' );
		}
		elseif( !$code )
		{
			throw new \Exception( 'No activation code provided' );
		}
		elseif( $code != $user->activation_key )
		{
			throw new \Exception( 'Invalid activation code' );
		}
		else
		{
			$user->activation_key = '';
			$user->active = true;
			$user->last_login = L\Util::sqltime();
			$user->save();
			$this->content = 'Congratulations! You activated your account!';
		}
	}

	protected function delete_form( M\User $user )
	{
		$token = \Core\Session::token();
		$this->content = new \Core\View( 'MenuBuddy/User/Delete' );
		$this->content->token = $token;
		$this->content->user = $user;
	}

	protected function delete_user( M\User $user )
	{
		$token = post( '_mbtoken' );
		if( \Core\Session::token( $token ) )
		{
			$user->delete();
		}
		redirect( site_url( '/user/list' ) );
		exit;
	}

	protected function edit_form( M\User $user )
	{
		
	}

	protected function edit_user( M\User $user )
	{
		
	}

	protected function list_users()
	{
		$users = M\User::fetch();
		$userTable = new \Core\Table( $users );
		$userTable->column( 'Username', 'username' )
				->column( 'Email', 'email' )
				->column( 'Active', 'active', function( M\User $data )
						{
							return $data->active ? 'yes' : 'no';
						} )
				->column( 'Actions', null, function( M\User $data )
						{
							$base = '/user';
							$id = $data->key();
							$edit_link = "$base/edit/$id";
							$delete_link = "$base/delete/$id";
							$edit_link = \Core\HTML::tag( 'a', 'Edit user', array( 'href' => $edit_link ) );
							$delete_link = \Core\HTML::tag( 'a', 'Delete user', array( 'href' => $delete_link ) );
							return $edit_link . ' | ' . $delete_link;
						} );
		$this->content = new \Core\View( 'MenuBuddy/User/List' );
		$this->content->table = $userTable;
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
				$this->delete_user( new M\User( $user ) );
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
			case 'activate':
				$user = new M\User( $user );
				$key = func_num_args() > 2 ? func_get_arg( 2 ) : false;
				$this->activate_user( $user, $key );
				break;
			case 'delete':
				$user = new M\User( $user );
				$this->delete_form( $user );
				break;
			case 'edit':
				break;
			case 'list':
				$this->list_users();
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
