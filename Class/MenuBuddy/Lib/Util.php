<?php

namespace MenuBuddy\Lib;

class Util
{

	/**
	 * A global Service object
	 * @var \Core\Service
	 */
	public static $service;

	public static function set_up_services()
	{
		self::$service = new \Core\Service();
		self::$service->db = function( $service, $config )
				{
					return new \Core\Database( $config );
				};
		self::$service->db( config()->database );
		self::$service->mail = function( )
				{
					return new \PHPMailer();
				};
	}

	public static function random_string( $length = 12, $special_chars = true, $extra_special_chars = false )
	{
		$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
		if( $special_chars ) $chars .= '!@#$%^&*()';
		if( $extra_special_chars ) $chars .= '-_ []{}<>~`+=,.;:/?|';
		$string = '';
		for( $i = 0; $i < $length; $i++ )
		{
			$string .= substr( $chars, rand( 0, strlen( $chars ) - 1 ), 1 );
		}
		return $string;
	}

	public static function sqltime( $time = false )
	{
		if( !$time )
		{
			$time = time();
		}
		return gmdate( 'Y-m-d H:i:s', $time );
	}

	public static function unsqltime( $time )
	{
		$time = str_replace( array( '-', ' ' ), ':', $time );
		$time = array_filter( explode( ':', $time ) );
		foreach($time as &$t) $t = (int)$t;
		if( count( $time ) != 6 )
		{
			throw new \Exception( 'Invalid date format' );
		}
		else
		{
			//                      hour      minute    second    month     day       year
			$timestamp = gmmktime( $time[3], $time[4], $time[5], $time[1], $time[2], $time[0] );
			return $timestamp;
		}
	}

	public static function mail( $to, $subject, $message, $headers = '', $attachments = array( ) )
	{
		if( empty( $headers ) )
		{
			$headers = array( );
		}
		else
		{
			if( !is_array( $headers ) )
			{
				$tempheaders = explode( "\n", str_replace( "\r\n", "\n", $headers ) );
			}
			else
			{
				$tempheaders = $headers;
			}
			$headers = array( );
			$cc = array( );
			$bcc = array( );

			// If it's actually got contents
			if( !empty( $tempheaders ) )
			{
				// Iterate through the raw headers
				foreach( (array)$tempheaders as $header )
				{
					if( strpos( $header, ':' ) === false )
					{
						if( false !== stripos( $header, 'boundary=' ) )
						{
							$parts = preg_split( '/boundary=/i', trim( $header ) );
							$boundary = trim( str_replace( array( "'", '"' ), '', $parts[1] ) );
						}
						continue;
					}
					// Explode them out
					list( $name, $content ) = explode( ':', trim( $header ), 2 );

					// Cleanup crew
					$name = trim( $name );
					$content = trim( $content );

					switch( strtolower( $name ) )
					{
						// Mainly for legacy -- process a From: header if it's there
						case 'from':
							if( strpos( $content, '<' ) !== false )
							{
								// So... making my life hard again?
								$from_name = substr( $content, 0, strpos( $content, '<' ) - 1 );
								$from_name = str_replace( '"', '', $from_name );
								$from_name = trim( $from_name );

								$from_email = substr( $content, strpos( $content, '<' ) + 1 );
								$from_email = str_replace( '>', '', $from_email );
								$from_email = trim( $from_email );
							}
							else
							{
								$from_email = trim( $content );
							}
							break;
						case 'content-type':
							if( strpos( $content, ';' ) !== false )
							{
								list( $type, $charset ) = explode( ';', $content );
								$content_type = trim( $type );
								if( false !== stripos( $charset, 'charset=' ) )
								{
									$charset = trim( str_replace( array( 'charset=', '"' ), '', $charset ) );
								}
								elseif( false !== stripos( $charset, 'boundary=' ) )
								{
									$boundary = trim( str_replace( array( 'BOUNDARY=', 'boundary=', '"' ), '', $charset ) );
									$charset = '';
								}
							}
							else
							{
								$content_type = trim( $content );
							}
							break;
						case 'cc':
							$cc = array_merge( (array)$cc, explode( ',', $content ) );
							break;
						case 'bcc':
							$bcc = array_merge( (array)$bcc, explode( ',', $content ) );
							break;
						default:
							// Add it to our grand headers array
							$headers[trim( $name )] = trim( $content );
							break;
					}
				}
			}
		}

		self::$service->mail()->ClearAddresses();
		self::$service->mail()->ClearAllRecipients();
		self::$service->mail()->ClearAttachments();
		self::$service->mail()->ClearBCCs();
		self::$service->mail()->ClearCCs();
		self::$service->mail()->ClearCustomHeaders();
		self::$service->mail()->ClearReplyTos();

		if( empty( $from_name ) )
		{
			$from_name = 'MenuBuddy';
		}

		if( empty( $from_email ) )
		{
			$from_email = 'menubuddy@example.com';
		}

		self::$service->mail()->From = $from_email;
		self::$service->mail()->FromName = $from_name;

		if( !is_array( $to ) )
		{
			$to = explode( ',', $to );
		}
		foreach( (array)$to as $recipient )
		{
			try
			{
				$recipient_name = '';
				if( preg_match( '/(.*)<(.+)>/', $recipient, $matches ) )
				{
					if( count( $matches ) == 3 )
					{
						$recipient_name = $matches[1];
						$recipient = $matches[2];
					}
				}
				self::$service->mail()->AddAddress( $recipient, $recipient_name );
			}
			catch( \phpmailerException $e )
			{
				continue;
			}
		}

		self::$service->mail()->Subject = $subject;
		self::$service->mail()->Body = $message;

		if( !empty( $cc ) )
		{
			foreach( (array)$cc as $recipient )
			{
				try
				{
					$recipient_name = '';
					if( preg_match( '/(.*)<(.+)>/', $recipient, $matches ) )
					{
						if( count( $matches ) == 3 )
						{
							$recipient_name = $matches[1];
							$recipient = $matches[2];
						}
					}
					self::$service->mail()->AddCc( $recipient, $recipient_name );
				}
				catch( \phpmailerException $e )
				{
					continue;
				}
			}
		}

		if( !empty( $bcc ) )
		{
			foreach( (array)$bcc as $recipient )
			{
				try
				{
					// Break $recipient into name and address parts if in the format "Foo <bar@baz.com>"
					$recipient_name = '';
					if( preg_match( '/(.*)<(.+)>/', $recipient, $matches ) )
					{
						if( count( $matches ) == 3 )
						{
							$recipient_name = $matches[1];
							$recipient = $matches[2];
						}
					}
					self::$service->mail()->AddBcc( $recipient, $recipient_name );
				}
				catch( \phpmailerException $e )
				{
					continue;
				}
			}
		}

		self::$service->mail()->IsMail();

		if( !isset( $content_type ) )
		{
			$content_type = 'text/html';
		}

		self::$service->mail()->ContentType = $content_type;
		if( 'text/html' == $content_type )
		{
			self::$service->mail()->IsHTML( true );
		}

		self::$service->mail()->CharSet = 'utf-8';

		if( !empty( $headers ) )
		{
			foreach( (array)$headers as $name => $content )
			{
				self::$service->mail()->AddCustomHeader( sprintf( '%1$s: %2$s', $name, $content ) );
			}

			if( false !== stripos( $content_type, 'multipart' ) && !empty( $boundary ) )
			{
				self::$service->mail()->AddCustomHeader( sprintf( "Content-Type: %s;\n\t boundary=\"%s\"", $content_type, $boundary ) );
			}
		}

		if( !empty( $attachments ) )
		{
			foreach( $attachments as $attachment )
			{
				try
				{
					self::$service->mail()->AddAttachment( $attachment );
				}
				catch( \phpmailerException $e )
				{
					continue;
				}
			}
		}

		try
		{
			self::$service->mail()->Send();
		}
		catch( \phpmailerException $e )
		{
			return false;
		}

		return true;
	}

}
