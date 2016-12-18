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
);