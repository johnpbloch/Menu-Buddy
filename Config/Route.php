<?php

$config = array();

$config['routes'] = array(
	''					=> '\MenuBuddy\Controller\Index',
	'404'				=> '\MenuBuddy\Controller\Page404',
	'login'				=> '\MenuBuddy\Controller\Auth',
	'auth'				=> '\MenuBuddy\Controller\Auth',
	'user'				=> '\MenuBuddy\Controller\User',
);
