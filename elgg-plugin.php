<?php
require_once __DIR__ . "/lib/functions.php";
return [
	'plugin' => [
		'name' => 'Registration Randomizer',
		'version' => '5.0',
		'dependencies' => [],
	],
	'bootstrap' => RegistrationRandomizer::class,
	'routes' => [
		'account:register' => [
		  'path' => 'register/{ts}/{token}',
		  'resource' => 'registration_randomizer/register',
		],
	],
];
