<?php
$ts = elgg_extract('ts', $vars, 0);
$token = elgg_extract('token', $vars, 0);

if (!registration_randomizer_is_valid_token($token, $ts)) {
	registration_randomizer_log(elgg_echo('rr:invalid:token'));
	throw new \Elgg\Exceptions\Http\PageNotFoundException();
} else {
	echo elgg_view_resource('account/register');
	return true;
}
	
registration_randomizer_log(elgg_echo('rr:no:token'));
throw new \Elgg\Exceptions\Http\PageNotFoundException();