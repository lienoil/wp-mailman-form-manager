<?php

/**
 * The current form this shortcode is at.
 *
 * @var
 */
$current_form = $this->get_current_shortcode_form();

/**
 * The content of this shortcode
 *
 * @var
 */
$loopable_content = do_shortcode( $content );

foreach ( $current_form->fields as $field_obj ) :
	$form_control = $loopable_content;

	$forms = $this->forms;
	$field = get_post( $field_obj['ID'] );
	$field_id = $field->ID;
	$field = get_post_meta( $field_id, $forms['field']['name'], true );
	$field['type'] = $field['type'] !== "" ? $field['type'] : 'text';

	/**
	 * Label field
	 * replace all instance of Keyword %label%
	 *
	 */
	if ( isset( $field['show_label'] ) && $field['show_label'] ) {
		$form_control = FormBuilder::make_label( $field['name'], $field['label'], $form_control );
	}

	/**
	 * Field/Input field
	 * replace all instance of Keyword %field%
	 *
	 */
	$name = $field['name'];
	$type = $field['type'];
	$defaults = FormBuilder::make_attributes( $field['attributes'] );
	$attributes = array_merge(
		array(
			'id' => $field['name'],
			'name' => $field['name'].'[value]',
			'class' => 'form-control',
		),
		$defaults
	);
	$value = isset( $field['value'] ) ? $field['value'] : '';
	$old_value = isset( $_POST[ $field['name'] ]['value'] ) ? $_POST[ $field['name'] ]['value'] : null;

	$form_control = FormBuilder::make_field( $name, $type, $attributes, $value, $old_value, $form_control, $field_id );

	/**
	 * Error field
	 * replace all instance of Keyword %error%
	 *
	 */
	$error = $this->get_error( $field['name'] );
	$form_control = FormBuilder::make_error( $error, $form_control );

	/**
	 * Display this row
	 *
	 */
	echo do_shortcode( $form_control );

endforeach;