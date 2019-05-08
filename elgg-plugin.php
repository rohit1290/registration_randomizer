<?php

return [
	'routes' => [
		'account:register' => [
  		'path' => 'register/{ts}/{token}',
  		'resource' => 'registration_randomizer/register',
		],
	],
];
