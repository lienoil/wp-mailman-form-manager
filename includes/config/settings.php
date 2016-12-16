<?php

return array(
	'form_settings' => array(
		'type' => 'submenu',
		'for' => 'edit.php?post_type=form',
		'name' => 'form_settings',
		'page-title' => 'Settings',
		'menu-title' => 'Settings',
		'capability' => 'manage_options',
		'menu-slug' => 'form-settings',
		'view' => '/includes/views/admin/options.php',
	),
);