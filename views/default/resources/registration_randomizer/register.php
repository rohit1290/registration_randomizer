<?php 

/**
 * Serves registration URLs as created by the elgg_register_plugin_hook_handler 'register', 'menu:login' callback
 *
 * /register/:ts/:token Where :token is the token and :ts is the current timestamp.
 *
 * @param array $page
 */

	// tarpit if the wrong token + ts combo
	$ts = elgg_extract('ts', $vars, 0);
	$token = elgg_extract('token', $vars, 0);

	if (!registration_randomizer_is_valid_token($token, $ts)) {
		registration_randomizer_log("Invalid token for registration page");
		forward('/', 404);
	} else {
		echo elgg_view_resource('account/register');
		return true;
	}
	registration_randomizer_log("No token for registration page");

	forward('/', '404');

 ?>