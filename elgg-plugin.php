<?php
elgg_set_config('rr_debug', false);
require_once __DIR__ . "/lib/functions.php";
return [
	'plugin' => [
		'name' => 'Registration Randomizer',
		'version' => '7.0',
		'dependencies' => [],
	],
	'routes' => [
		'account:register' => [
		  'path' => 'register/{ts}/{token}',
		  'resource' => 'registration_randomizer/register',
			'walled' => false
		],
	],
	'settings' => [
		'reg_token_valid' => 5,
	],
	'events' => [
		'action:validate' => [
			'register' => [
				'\RegistrationRandomizer::validate_token' => [],
			],
		],
		'register' => [
			'menu:login' => [
				'\RegistrationRandomizer::generateURL' => [],
			],
		],
	],
];
