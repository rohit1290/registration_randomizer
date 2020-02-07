<?php
require_once __DIR__ . "/lib/functions.php";
return [
	'bootstrap' => RegistrationRandomizer::class,
	'routes' => [
		'account:register' => [
		  'path' => 'register/{ts}/{token}',
		  'resource' => 'registration_randomizer/register',
		],
	],
];
