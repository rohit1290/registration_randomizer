<?php
echo elgg_view_field([
	'#type' => 'number',
	'#label' => elgg_echo('rr:settings:title'),
	'#help' => elgg_echo('rr:settings:help'),
	'name' => 'params[reg_token_valid]',
	'value' => $vars['entity']->reg_token_valid,
]);
?>