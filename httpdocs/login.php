<?php

namespace MenuBuddy;

define( 'PATH', dirname( dirname( __FILE__ ) ) . '/' );

require( PATH . 'load.php' );

require( FUNC . 'authentication.php' );

if( !empty ($_POST['account']) && !empty ($_POST['pass']) && Auth\check_password( $_POST['account'], $_POST['pass'])){
	$user = Users\get_user($_POST['account']);
	Auth\cookie($user);
	// @todo: need to redirect to previous page / home page.
}

?><!DOCTYPE html>
<html>
	<head>
		<title>Log In</title>
		<style>
			#wrap {
				margin: 100px 0 0 50%;
			}
			#main {
				width: 300px;
				margin-left: -181px;
				background: #bbb;
				padding: 30px;
				border: 1px solid #666;
				-moz-border-radius: 20px;
				-webkit-border-radius: 20px;
				border-radius: 20px;
				-moz-box-shadow: 10px 10px 25px #333;
				-webkit-box-shadow: 10px 10px 25px #333;
				box-shadow: 10px 10px 25px #333;
			}
			label {
				display: block;
				font-size: 24px;
				color: #444;
				text-shadow: 0px 1px 0px #fff;
				margin: 0;
				padding: 0;
			}
			.textbox {
				border: 1px solid #666;
				-moz-border-radius: 2px;
				-webkit-border-radius: 2px;
				border-radius: 2px;
				background: #EEE;
				-moz-box-shadow: 0px 0px 1px 1px #ddd;
				-webkit-box-shadow: 0px 0px 1px 1px #ddd;
				box-shadow: 0px 0px 1px 1px #ddd;
				height: 21px;
				margin: 0;
				padding: 5px;
				width: 290px;
			}
			#submit {
				border: 1px solid #666;
				border-radius: 2px;
				box-shadow: 0px 0px 1px 1px #ddd;
				line-height: 20px;
				margin: 20px 0;
				height: 32px;
				padding: 0 25px;
				font-weight: bold;
				font-size: 20px;
				color: #333;
				text-shadow: 0px 1px 1px white;
				background: #AAA;
			}
		</style>
	</head>
	<body>
		<div id="wrap">
			<div id="main">
				<form action="" method="post">
					<label>Username or Email:<br />
						<input class="textbox" name="account" type="text" value="<?php echo (empty( $_POST[ 'account' ] ) ? '' : $_POST[ 'account' ]); ?>" /></label>
					<label>Password:<br />
						<input class="textbox" name="pass" type="password" value="" /></label>
					<input id="submit" type="submit" value="Log In" />
				</form>
			</div>
		</div>
	</body>
</html>