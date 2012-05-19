<?php

$config = array();

$config['routes'] = array(
	''					=> '\Controller\Index',
	'404'				=> '\Controller\Page404',
	'login'				=> '\MenuBuddy\Controller\Auth',
	'auth'				=> '\MenuBuddy\Controller\Auth',
);
