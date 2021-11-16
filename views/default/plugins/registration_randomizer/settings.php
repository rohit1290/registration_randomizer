<?php

$reg_token_valid = elgg_view_field([
	'#type' => 'number',
	'#label' => 'How long should registration tokens be valid? (In Minutes)',
	'#help' => "Randomizer registration token will expire after the defined time. Default is 5 minutes",
	'name' => 'params[reg_token_valid]',
	'value' => $vars['entity']->reg_token_valid,
]);

$settings = <<<__HTML
<div>$reg_token_valid</div>
__HTML;

echo $settings;

 ?>