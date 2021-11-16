<?php
use Elgg\DefaultPluginBootstrap;

class RegistrationRandomizer extends DefaultPluginBootstrap {

  public function init() {
  	// remove elgg's default register page
  	// elgg_unregister_route('account:register');

  	// check referrers
  		elgg_register_plugin_hook_handler('action:validate', 'register', function (\Elgg\Hook $hook) {
  			$action = $hook->getType();
  			$return = $hook->getValue();

  			$ref = filter_input(INPUT_SERVER, 'HTTP_REFERER');
  			$url = elgg_get_site_url();
  			list($register, $ts, $token) = explode('/', str_replace($url, '', $ref));

  			if ($register !== 'register') {
  				return $return;
  			}

  			if (!registration_randomizer_is_valid_token($token, $ts)) {
  				registration_randomizer_log("Invalid referrer for registration action");
  				register_error("Cannot complete registration at this time.");
          throw new \Elgg\Exceptions\HttpException(elgg_echo('invalid_request_signature'), ELGG_HTTP_FORBIDDEN);
  			}

  			return $return;
  		});

  	// replace view vars
  	elgg_register_plugin_hook_handler('register', 'menu:login', function (\Elgg\Hook $hook) {
  		$menu = $hook->getValue();
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
}