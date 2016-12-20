<?php

return array(
	'form' => array(
		'for' => 'form',
		'post-metaname' => 'form',
		'shortcode' => 'form',
		'atts' => function () {
			global $post_id;

			// the $atts to return to
			return array(
	    		'id' => $post_id,
	    	);
		},
		'view' => '/includes/views/shortcodes/form.php'
	),

	'loop' => array(
		'for' => 'loop',
		'post-metaname' => 'form',
		'shortcode' => 'loop',
		'atts' => function () {
			global $post_id;

			// the $atts to return to
			return array(
	    		'class' => 'row',
	    		'data-toggle' => '',
	    	);
		},
		'view' => '/includes/views/shortcodes/loop.php'
	),

	'div' => array(
		'for' => 'form',
		'post-metaname' => 'form',
		'shortcode' => 'div',
		'atts' => function () {
			global $post_id;

			// the $atts to return to
			return array(
	    		'class' => '',
	    		'data-toggle' => '',
	    	);
		},
		'view' => '/includes/views/shortcodes/div.php'
	),

	'row' => array(
		'for' => 'form',
		'post-metaname' => 'form',
		'shortcode' => 'row',
		'atts' => function () {
			global $post_id;

			// the $atts to return to
			return array(
	    		'class' => 'row',
	    		'data-toggle' => '',
	    	);
		},
		'view' => '/includes/views/shortcodes/div.php'
	),

	'column' => array(
		'for' => 'form',
		'post-metaname' => 'form',
		'shortcode' => 'column',
		'atts' => function () {
			global $post_id;

			// the $atts to return to
			return array(
	    		'class' => 'twelve columns col-sm-12',
	    		'data-toggle' => '',
	    	);
		},
		'view' => '/includes/views/shortcodes/div.php'
	),

	'form-group' => array(
		'for' => 'form',
		'post-metaname' => 'form',
		'shortcode' => 'form-group',
		'atts' => function () {
			global $post_id;

			// the $atts to return to
			return array(
	    		'class' => 'form-group',
	    		'data-toggle' => '',
	    	);
		},
		'view' => '/includes/views/shortcodes/div.php'
	),
);