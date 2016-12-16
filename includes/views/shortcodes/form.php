<?php

$form = $the_post;
$form_options = $the_post_options;

if ( ! empty( $the_post_options['display_template'] ) ) {
	$template = get_post( $the_post_options['display_template'] );
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
	$is_valid = WP_Mailman_Emailer::valid( $this->globals['nonce'], $this->forms['field']['name'], $post_fields );

	// if ( $is_valid != false ) {

		/**
		 * Send To User
		 *
		 */
		$message_template = WP_Mailman_Emailer::get_message_template();
		if ( isset( $form_options['message']['template'] ) && ! empty( $form_options['message']['template'] ) ) {
			$message_template = get_post( $form_options['message']['template'] );
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
		$email_array = WP_Mailman_Emailer::compose( $post_fields, $options );

		WP_Mailman_Emailer::send( $email_array, $settings_options );

	// } else {

	// 	$this->form_errors = $is_valid;

	// }
}

/**
 * The content from the template
 * @var [type]
 */
$template_content = do_shortcode( $template->post_content );
$template_content = html_entity_decode( $template_content );
$template_content = FormBuilder::remove_empty_paragraphs( $template_content );

/**
 * To do:
 * ajax support
 */
// $is_ajax = ...
?>

<form id="wp-mailman-form-manager-<?php echo $form->ID; ?>" action="<?php #echo esc_url( admin_url('admin-post.php') ); ?>" class="wp-mailman-form-manager wp-mailman-form-manager-<?php echo $form->ID; ?>" method="<?php echo $the_post_options['method']; ?>" novalidate>

	<?php # action hidden input ?>
	<input type="hidden" name="action" value="<?php echo $pluginname; ?>">
	<?php $this->get_nonce(); ?>

	<?php

	$the_main_content = "";

	foreach ( $the_post_options['fields'] as $field ) :

		$the_loop = FormBuilder::get_loop( $template_content );

		/**
		 * Field Row
		 *
		 */
		$start_loop = "<div class='row'>";
		$the_loop = str_replace( FormBuilder::$pattern_start_loop, $start_loop, $the_loop );
		$end_loop = "</div>";
		$the_loop = str_replace( FormBuilder::$pattern_end_loop, $end_loop, $the_loop );

		$field = get_post( $field['name'] );
		$forms = $this->forms;
		$field_options = get_post_meta( $field->ID, $forms['field']['name'], true );

		$field_options['type'] = $field_options['type'] !== "" ? $field_options['type'] : 'text';

		/**
		 * Field Label
		 *
		 */
		if ( isset( $field_options['show_label'] ) && $field_options['show_label'] ) :
			ob_start(); ?>
			<label for="<?php echo $field_options['name']; ?>"><?php echo $field_options['label']; ?></label>
			<?php
			$label = ob_get_clean();
			$the_loop = str_replace( FormBuilder::$pattern_label, $label, $the_loop );
		endif;

		/**
		 * Field Input
		 *
		 */

		// Input Attributes
		$defaults = array();
		foreach ( $field_options['attributes'] as $attribute ) {
			$defaults[ $attribute['name'] ] = $attribute['value'];
		}

		$attr = array_merge( array(
			'id' => $field_options['name'],
			'name' => $field_options['name']."[value]",
			'required' => isset( $field_options['required'] ) ? "required" : false
		), $defaults );

		// Input Field
		ob_start();
		echo FormBuilder::make_field( $field_options['type'], $attr, $field_options['value'], isset( $_POST[ $field_options['name'] ]['value'] ) ? $_POST[ $field_options['name'] ]['value'] : null ); ?>
		<input type="hidden" name="<?php echo $field_options['name'].'[ID]'; ?>" value="<?php echo $field->ID; ?>">
		<?php
		$input = ob_get_clean();

		$the_loop = str_replace( FormBuilder::$pattern_field, $input, $the_loop );

		/**
		 * Field Error
		 *
		 */
		$error = $this->get_error( $field_options['name'] );
		$the_loop = str_replace( FormBuilder::$pattern_error, $error, $the_loop );

		/**
		 * Append to the Main Content
		 */
		$the_main_content .= $the_loop;

	endforeach;

	/**
	 * Field Submit
	 * replace $template_content's FormBuilder::$pattern_submit with the $submit_button
	 *
	 */
	ob_start();
	?>
	<input type="hidden" name="submitted" value="1">
	<button type="submit" class="btn btn-success button button-primary">Send</button>
	<?php
	$submit_button = ob_get_clean();
	$template_content = str_replace( FormBuilder::$pattern_submit, $submit_button, $template_content );

	/**
	 * Display the Field
	 * replace $template_content's <loop> with the $the_main_content.
	 *
	 */
	echo FormBuilder::make_content( $the_main_content, $template_content );

	?>

</form>