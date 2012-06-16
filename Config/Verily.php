<?php

$config = array(
	'hasher' => '\Verily\Lib\PasswordHash',
	'hasher_iterations' => 8,
	'use_portable_hashes' => false,
	'form_view' => 'Form',
	'form_class' => '\MenuBuddy\Lib\GumbyForm',
	'validation_class' => '\Core\Validation',
	'user_model' => '\MenuBuddy\Model\User',
	'username_property' => 'username',
	'password_property' => 'pass',
	'auth_salt' => 'Ct7GX+=buWP-A&hf2Hp8Qq4?X0]uCYM4(g:C/~DrL(/#Uf*jM,*>jI}6.!<wvz<|F]BC$5Uk',
);
