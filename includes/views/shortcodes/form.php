<?php

$the_form = get_post( $atts['id'] );
$the_form_options = get_post_meta( $the_form->ID, $post_metaname, true );

if ( ! empty( $the_form_options['display_template'] ) ) {
	$template = get_post( $the_form_options['display_template'] );
} else {
	$template = new StdClass();
	$template->post_content = htmlentities( file_get_contents( __DIR__ . '/form-default.php' ) );
}

if ( isset( $_POST['submitted'] ) && $_POST['submitted'] ) {
	/**
     * Sanitize and extract fields
     *
     */
	$post_fields = WP_Mailman_Emailer::sanitize( $_POST );

	/**
	 * Validate
	 *
	 */
	$is_valid = WP_Mailman_Emailer::valid( $this->forms['field']['name'], $post_fields, $this->globals['nonce'] );

	if ( $is_valid === true ) {

		/**
		 * Send To User
		 *
		 */
		$message_template = WP_Mailman_Emailer::get_message_template();
		if ( isset( $the_form_options['message']['template'] ) && ! empty( $the_form_options['message']['template'] ) ) {
			$message_template = get_post( $the_form_options['message']['template'] );
			$message_template = array(
				'subject' => $message_template->post_title,
				'message' => $message_template->post_content,
				'post_meta' => get_post_meta( $message_template->ID, $this->metaboxes['message-template-attachment']['name'] )
			);
		}

		$settings_options = get_option( $this->settings['form_settings']['name'] );

		$options = array(
			'name'=> $this->forms['field']['name'],
			'template' => $message_template,
			'type' => isset( $settings_options['type'] ) && ! empty( $settings_options['type'] ) ? $settings_options['type'] : 'plain-text',
		);
		$the_email = WP_Mailman_Emailer::compose( $post_fields, $options );

		/**
		 * Send
		 *
		 */
		// $response = WP_Mailman_Emailer::send( $the_email, $settings_options );

		// if ( $response ) WP_Mailman_Emailer::clear_posts();

		/**
		 * Save Post
		 *
		 */
		$message_options = array(
			'nonce' => $this->globals['nonce'],
			'post_metaname' => $this->forms['field']['name'],
		);
		WP_Mailman_Message_Manager::write( $post_fields, $message_options );

	} else {

		$this->form_errors = $is_valid;

	}
}

$this->set_current_shortcode_form(array(
	'ID' => $the_form->ID,
	'fields' => $the_form_options['fields'],
	'template' => $template->post_content,
	'errors' => $this->form_errors,
));

/**
 * The content from the template
 * @var [type]
 */
$template_content = $template->post_content;
$template_content = html_entity_decode( $template_content );
$template_content = FormBuilder::remove_empty_paragraphs( $template_content );

/**
 * To do:
 * ajax support
 */
// $is_ajax = ...
?>

<?php echo WP_Mailman_Emailer::get_response( isset( $response ) ? $response : '' ); ?>

<form id="wp-mailman-form-manager-<?php echo $the_form->ID; ?>" action="<?php #echo esc_url( admin_url('admin-post.php') ); ?>" class="wp-mailman-form-manager wp-mailman-form-manager-<?php echo $the_form->ID; ?>" method="<?php echo $the_form_options['method']; ?>" novalidate>

	<?php # action hidden input ?>
	<input type="hidden" name="action" value="<?php echo $pluginname; ?>">
	<?php $this->get_nonce(); ?>
	<?php # echo isset( $this->form_errors['nonce'] ) ? $this->get_error('nonce') : ''; ?>

	<?php
	/**
	 * Field Submit
	 * replace $template_content's FormBuilder::$pattern_submit with the $submit_button
	 *
	 */
	ob_start();
	$label = isset( $the_form_options['submit_button']['label'] ) && "" !== $the_form_options['submit_button']['label'] ? $the_form_options['submit_button']['label'] : 'Send';
	$attributes = $the_form_options['submit_button']['attributes'];
	echo FormBuilder::make_submit( $label, $attributes );
	$submit_button = ob_get_clean();
	$template_content = str_replace( FormBuilder::$pattern_submit, $submit_button, $template_content );

	echo do_shortcode( $template_content );
	?>

</form>