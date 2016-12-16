<?php

return array(
	'message' => array(
		'name' => 'message',
		'single' => 'Message',
		'plural' => 'Messages',
		'args' => array(
			'menu_icon' => 'dashicons-email-alt',
			'rewrite' => true,
			'supports' => array( 'title', 'editor' ),
			'capabilities' => array(
                'read' => true,
                'create_posts' => false, // Toggles support for the "Add New" function
            ),
            'map_meta_cap' => true, // Set to false, if users are not allowed to edit/delete existing posts
		),
	),

	'form' => array(
		'name' => 'form',
		'single' => 'Form',
		'plural' => 'Forms',
		'args' => array(
			'menu_icon' => 'dashicons-feedback',
			'rewrite' => true,
			'supports' => array( 'title' ),
		),
		'shortcodable' => true,
	),

	'field' => array(
		'name' => 'field',
		'single' => 'Form Field',
		'plural' => 'Form Fields',
		'hierarchical' => false,
		'args' => array(
			'supports' => array( 'title' ),
			'show_in_menu' => 'edit.php?post_type=form',
		),
	),

	'form-template' => array(
		'name' => 'form-template',
		'single' => 'Form Template',
		'plural' => 'Form Templates',
		'hierarchical' => false,
		'args' => array(
			'supports' => array( 'title', 'editor' ),
			'show_in_menu' => 'edit.php?post_type=form',
		),
	),

	'message-template' => array(
		'name' => 'message-template',
		'single' => 'Message Template',
		'plural' => 'Message Templates',
		'hierarchical' => false,
		'args' => array(
			'supports' => array( 'title', 'editor' ),
			'show_in_menu' => 'edit.php?post_type=message',
		),
	),
);