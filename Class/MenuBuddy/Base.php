<?php

/**
 * MyController
 *
 * Basic DEMO outline for standard controllers
 *
 * @package		MicroMVC
 * @author		David Pennington
 * @copyright	(c) 2011 MicroMVC Framework
 * @license		http://micromvc.com/license
 * ********************************* 80 Columns *********************************
 */

namespace MenuBuddy;

abstract class Base extends \Core\Controller
{

	// Global view template
	public $template = 'MenuBuddy/Layout';
	public $header = '';
	public $footer = '';

	/**
	 * Called after the controller is loaded, before the method
	 *
	 * @param string $method name
	 */
	public function initialize( $method )
	{
		\Core\Session::start();

		$headerTemplate = !empty( config( 'Layout' )->header ) ? config( 'Layout' )->header : 'MenuBuddy/Header';
		$footerTemplate = !empty( config( 'Layout' )->footer ) ? config( 'Layout' )->footer : 'MenuBuddy/Footer';
		$this->header = new \Core\View( $headerTemplate );
		$this->footer = new \Core\View( $footerTemplate );
	}

	/**
	 * Load database connection
	 */
	public function load_database( $name = 'database' )
	{
		// Load database
		$db = new \Core\Database( config()->$name );

		// Set default ORM database connection
		if( empty( \Core\ORM::$db ) )
		{
			\Core\ORM::$db = $db;
		}

		return $db;
	}

	/**
	 * Show a 404 error page
	 */
	public function show_404()
	{
		headers_sent() OR header( 'HTTP/1.0 404 Page Not Found' );
		$this->content = new \Core\View( '404' );
	}

	/**
	 * Save user session and render the final layout template
	 */
	public function send()
	{
		\Core\Session::save();

		headers_sent() OR header( 'Content-Type: text/html; charset=utf-8' );

		$gumby = array(
			'css' => array(
				'/gumby/css/gumby.css',
				'/gumby/css/ui.css',
			),
			'javascript' => array(
				'/gumby/js/libs/modernizr-2.0.6.min.js',
			),
			'footer_scripts' => array(
				'/gumby/js/libs/jquery-1.7.2.min.js',
				'/gumby/js/libs/gumby.min.js',
				'/gumby/js/plugins.js'
			)
		);

		foreach( $gumby as $type => $files )
		{
			if( empty( $this->{$type} ) || !is_array( $this->{$type} ) )
			{
				$this->{$type} = $files;
			}
			else
			{
				$this->{$type} = $files + $this->{$type};
			}
		}
		$layout = new \Core\View( $this->template );
		$layout->set( (array)$this );
		print $layout;

		$layout = NULL;

		if( config()->debug_mode )
		{
			print new \Core\View( 'System/Debug' );
		}
	}

}

// End
