<?php

namespace MenuBuddy;

define( 'PATH', dirname( dirname( __FILE__ ) ) . '/' );

require( PATH . 'load.php' );

$MenuBuddy = new MenuBuddy();
$MenuBuddy->run();
