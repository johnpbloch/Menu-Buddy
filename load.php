<?php

namespace MenuBuddy;

require( PATH . 'config.php' );
require( FUNC . 'general.php' );
require( FUNC . 'users.php' );
require( FUNC . 'template.php' );

initialize_database();

Extensions::initialize();

Template\startup_check();
