<!doctype html>
<html>
	<head>
		<meta charset="utf-8"/>
		<title><?php echo empty( $title ) ? 'MenuBuddy' : $title; ?></title>

		<?php
		//Print all CSS files
		if( !empty( $css ) ) foreach( $css as $file ) print '<link rel="stylesheet" media="all" href="' . $file . '" />';

		//Print all JS files
		if( !empty( $javascript ) ) foreach( $javascript as $file ) print '<script type="text/javascript" src="' . $file . '"></script>';

		//Print any other header data
		if( !empty( $head_data ) ) print $head_data;
		?>

		<meta name="viewport" content="width=device-width, initial-scale=1"/>

	</head>
	<body lang="en">

		<?php echo $header; ?>
		<?php echo $content; ?>
		<?php echo $footer; ?>

		<?php if( !empty( $footer_scripts ) ) foreach( $footer_scripts as $file ) print '<script type="text/javascript" src="' . $file . '"></script>';  ?>
		<?php if( isset( $debug ) && config()->debug ) print '<div id="debug">' . $debug . '</div>'; ?>

	</body>
</html>