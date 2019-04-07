<?php

return [
	'routes' => [
		'default:object:registration_randomizer' => [
  		'path' => 'register/{ts}/{token}',
  		'resource' => 'registration_randomizer/register',
		],
	],
];
