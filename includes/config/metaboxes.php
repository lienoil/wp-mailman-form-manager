<?php

return array(
	// 'container' => array(
	// 	'for' => 'form',
	// 	'id' => 'container',
	// 	'title' => 'Containers',
	// 	'view' => '/includes/views/admin/container.metabox.php',
	// 	'name' => 'container',
	// 	'context' => 'side',
	// ),

	'form-template-helper' => array(
		'for' => 'form-template',
		'id' => 'form-template-helper',
		'title' => 'Help',
		'view' => '/includes/views/admin/metaboxes/form-template-helper.php',
		'name' => 'form-template-helper',
		'context' => 'advanced',
	),

	'message-template-attachment' => array(
		'for' => 'message-template',
		'id' => 'message-template-attachment',
		'title' => 'Attachments',
		'view' => '/includes/views/admin/metaboxes/message-template-attachment.php',
		'name' => 'message-template-attachment',
		'context' => 'side',
	),
);