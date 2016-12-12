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
	),

	'field' => array(
		'name' => 'field',
		'single' => 'Field',
		'plural' => 'Fields',
		'hierarchical' => false,
		'args' => array(
			'supports' => array( 'title' ),
			'show_in_menu' => 'edit.php?post_type=form',
		),
	),

	'template' => array(
		'name' => 'template',
		'single' => 'Template',
		'plural' => 'Templates',
		'hierarchical' => false,
		'args' => array(
			'supports' => array( 'title', 'editor' ),
			'show_in_menu' => 'edit.php?post_type=form',
		),
	),
);