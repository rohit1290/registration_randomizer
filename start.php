<?php
/**
 * Rotates the address for the registration form
 */
elgg_register_event_handler('init', 'system', 'registration_randomizer_init');
/**
 * Init
 */
function registration_randomizer_init() {
	
	// remove elgg's default register page
	elgg_unregister_route('account:register');
	
	// check referrers
		elgg_register_plugin_hook_handler('action:validate', 'register', function ($hook, $action, $return) {
			$ref = filter_input(INPUT_SERVER, 'HTTP_REFERER');
			$url = elgg_get_site_url();
			list($register, $ts, $token) = explode('/', str_replace($url, '', $ref));

			if ($register !== 'register') {
				return $return;
			}

			if (!registration_randomizer_is_valid_token($token, $ts)) {
				registration_randomizer_log("Invalid referrer for registration action");
				register_error("Cannot complete registration at this time.");
				forward('/', '403');
			}

			return $return;
		});

	// replace view vars
	elgg_register_plugin_hook_handler('register', 'menu:login', function ($hook, $type, $menu, $params) {
		foreach ($menu as $key => $item) {
			if ($item->getName() == 'register') {
				$info = registration_randomizer_generate_token();
				$item->setHref('/register/' . $info['ts'] . '/' . $info['token']);
			}
		}
		return $menu;
	});

	elgg_set_config('rr_debug', false);
}

/**
 * Hashes the site secret, UA, and a ts.
 *
 * @return mixed A token if time or req is passed, and array of info if not
 */
function registration_randomizer_generate_token($passed_time = null, $passed_req = null) {

	if ($passed_time === null) {
		$ts = time();
	} else {
		$ts = $passed_time;
	}

	if ($passed_req === null) {
		$req = $_SERVER;
	} else {
		$req = $passed_req;
	}

	$str = md5('registration_randomizer');
	$str .= filter_input(INPUT_SERVER, 'HTTP_USER_AGENT');
	$str .= filter_input(INPUT_SERVER, 'REMOTE_ADDR', FILTER_VALIDATE_IP);
	$str .= $ts;

	$token = md5($str);

	if ($passed_time === null && $passed_req === null) {
		return array(
			'ts' => $ts,
			'token' => $token,
			'req' => $req
		);
	} else {
		return $token;
	}
}

/**
 * Checks if the token and ts are valid
 *
 * @param type $token
 * @param type $time
 * @param type $req
 * @return bool
 */
function registration_randomizer_is_valid_token($token, $time, $req = null) {
	return $token === registration_randomizer_generate_token($time, $req);
}

/**
 * Log to file
 *
 * @param type $msg
 * @return type
 */
function registration_randomizer_log($msg, $all = true) {
	if (elgg_get_config('rr_debug') !== true) {
		return;
	}

	if (!$all) {
		file_put_contents(elgg_get_data_path() . 'rr_log.log', $msg . "\n", FILE_APPEND);
		return;
	}

	$data = $_REQUEST;
	$data['referrer'] = filter_input(INPUT_SERVER, 'HTTP_REFERER');
	$data['remote_ip'] = filter_input(INPUT_SERVER, 'REMOTE_ADDR');
	$data['remote_ua'] = filter_input(INPUT_SERVER, 'HTTP_USER_AGENT');
	$data['time'] = date("r");
	$data['error'] = $msg;

	file_put_contents(elgg_get_data_path() . 'rr_log.log', print_r($data, true), FILE_APPEND);
}


