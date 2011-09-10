<?php

namespace MenuBuddy;

require( PATH . 'config.php' );
require( FUNC . 'general.php' );
require( FUNC . 'users.php' );

initialize_database();

Extensions::initialize();
