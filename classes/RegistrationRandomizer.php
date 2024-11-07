<?php
use Elgg\DefaultPluginBootstrap;

class RegistrationRandomizer extends DefaultPluginBootstrap {

  public function init() {
  	// remove elgg's default register page
  	// elgg_unregister_route('account:register');

  	// check referrers
  		elgg_register_event_handler('action:validate', 'register', function (\Elgg\Event $event) {
  			$action = $event->getType();
  			$return = $event->getValue();

  			$ref = filter_input(INPUT_SERVER, 'HTTP_REFERER');
  			$url = elgg_get_site_url();
        $parts = explode('/', str_replace($url, '', $ref));

        // Assign with default empty strings if not set
        $register = $parts[0] ?? '';
        $ts = $parts[1] ?? 1;
        $token = $parts[2] ?? 1;

  			if ($register !== 'register') {
  				return $return;
  			}

  			if (!registration_randomizer_is_valid_token($token, $ts)) {
  				registration_randomizer_log("Invalid referrer for registration action");
  				elgg_register_error_message("Cannot complete registration at this time.");
          throw new \Elgg\Exceptions\HttpException(elgg_echo('invalid_request_signature'), ELGG_HTTP_FORBIDDEN);
  			}

  			return $return;
  		});

  	// replace view vars
  	elgg_register_event_handler('register', 'menu:login', function (\Elgg\Event $event) {
  		$menu = $event->getValue();
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