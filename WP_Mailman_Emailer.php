<?php

/**
 * WP Mailman Emailer
 *
 * This is a a wrapper for
 * the Wordpress WP_Mail and Post saving functions.
 *
 * @since  v1.0.0
 */
class WP_Mailman_Emailer
{
	protected static $errors;
	protected static $validations;
	protected static $mail;
	protected static $validation_regex_alphanumeric = '/^[a-zA-Z]+[a-zA-Z0-9._]+$/';
	protected static $regex_embedded_image = '#<img([^>]*) src="([^"/]*/?[^".]*\.[^"]*)"([^>]*)>((?!</a>))#';

	/**
	 * Get the appropriate CPT Message Template
	 *
	 * @param  boolean/string $template the template file to use
	 * @return string            The message
	 */
	public static function get_message_template()
	{
		return __DIR__ . "/includes/views/shortcodes/message-template-default.php";
	}

	public static function get_response( $response, $options = array() )
	{
		if ( "" == $response ) return;

		ob_start();

		$type = "danger";
		$message = $response;

		if ( $response ) {
			$type = "success";
			$message = "Form successfully submitted";
		}

		require __DIR__ . '/includes/views/messages/success.php';

		return ob_get_clean();
	}

	public static function make_message_from_template( $post_fields, $message_template )
	{
		// echo "<pre>";
		// echo "MAKE MESSAFE";
		//     var_dump( $post_fields );
		// echo "</pre>";
	}

	public static function valid( $post_metaname = "", $post_fields = null, $nonce = null )
	{
		/**
	     * At this point, $_GET/$_POST variable are available
	     *
	     * We can do our normal processing here
	     */
	    $post_fields = is_null( $post_fields ) ? $_POST : $post_fields;

	    self::$errors = array();

	    /**
	     * Check Nonce
	     *
	     */
	    // if ( ! isset( $_POST[ $nonce ] ) ) self::$errors['nonce'] = "Critical Error! No nonce sensed.";

	    // if ( ( isset( $_POST[ $nonce ] ) && ! wp_verify_nonce( $_POST[ $nonce ], $nonce ) ) ) self::$errors['nonce'] = "No nonce sensed.";

	    foreach ( $post_fields as $name => $post_field ) {

	    	if ( isset( $post_field['ID'] ) && isset( $post_field['value'] ) ) {
		    	$value = $post_field['value'];
		    	$field = get_post( $post_field['ID'] );
		    	$field_options = get_post_meta( $field->ID, $post_metaname, true ); // $forms['field']['name']

		    	/**
		    	 * Validate by Rules
		    	 * as defined in the fields_options['rules'].
		    	 *
		    	 */
		    	$rules = $field_options['rules'];
		    	$required_message = "";
		    	foreach ( $rules as $rule ) {
		    		/**
		    		 * The validation
		    		 *
		    		 */
		    		if ( ! empty( $rule['name'] ) && ! self::validate( $rule['name'], $rule['value'], $value ) ) {
	    				self::$errors[ $name ][] = $rule['message'];
		    		}

		    		/**
		    		 * The Rule `Required` is special,
		    		 * if it is specified in the rules,
		    		 * then we need to store it to another
		    		 * variable ( $required_message )
		    		 *
		    		 */
		    		if ( in_array('required', $rule ) ) {
		    			$required_message = $rule['message'];
		    		}
		    	}

		    	/**
		    	 * If `required` is set in the rules and the user input is empty,
		    	 * then let's clear all previous error for that field, and put
		    	 * only the `required` error message.
		    	 * The effect is, when the user submitted with an empty field, then
		    	 * only a `required` error message will be sent back for that particular field,
		    	 * otherwise all other error messages will kick in.
		    	 *
		    	 */
		    	if ( ! empty( $required_message ) && ( empty( $value ) || $value == "" ) && ! empty( $rules ) ) {
		    		self::$errors[ $name ] = null;
		    		self::$errors[ $name ][] = str_replace( '%field%', $field->post_title, $required_message );
		    	}
	    	}
	    }

	    /**
	     * If we have errors, return the errors,
	     * else the form is valid.
	     *
	     */
	    return ( ! empty( self::$errors ) ) ? self::$errors : true;
	}

	/**
	 * Checks if a given condition is true.
	 *
	 * @param  Validation $name  The type of Validation.
	 * @param  mixed $value The string/boolean/integer/ value for the given Validation type.
	 * @param  mixed $input The value to check against $value.
	 *
	 * @return Boolean
	 */
	public static function validate( $name,  $value, $input = "" )
	{
		$input = trim( $input );

		switch ( $name ) {
			case 'required':
				if ( ! empty( $input ) || "" !== $input ) return $value;
				break;

			case 'email':
				if ( is_email( $input ) !== false ) return $value;
				break;

			case 'alphanumeric':
				if ( preg_match( self::$validation_regex_alphanumeric, $input ) ) return $value;
				break;

			case 'digits':
				if ( is_numeric( $input ) == $value ) return true;
				break;

			case 'maxlength':
				if ( strlen( $input ) <= $value ) return true;
				break;

			case 'minlength':
				if ( "" !== $input && strlen( $input ) >= $value ) return true;
				break;

			case 'maxwordlength':
				if ( str_word_count( $input ) <= $value ) return true;
				break;

			case 'minwordlength':
				if ( "" !== $input && str_word_count( $input ) >= $value ) return true;
				break;

			case 'url':
				if ( filter_var( $input, FILTER_VALIDATE_URL ) != ! $value ) return true;
				break;

			case 'date':
				$date = date_parse( $input );
				if ( $date['error_count'] == 0 && checkdate( $date["month"], $date["day"], $date["year"] ) ) return true;
				break;

			case 'time':
				$date = date( 'H:i:s', strtotime( $input ) );
				$date = explode(":", $date);
				if ( self::checktime( $date[0], $date[1], $date[2] ) ) return true;
				break;


			default:
				return true;
				break;
		}

		return false;
	}

	private static function checktime( $hour, $min, $sec )
	{
	    if ( $hour < 0 || $hour > 23 || ! is_numeric( $hour ) ) {
	        return false;
	    }
	    if ( $min < 0 || $min > 59 || ! is_numeric( $min ) ) {
	        return false;
	    }
	    if ( $sec < 0 || $sec > 59 || ! is_numeric( $sec ) ) {
	        return false;
	    }

	    return true;
	}

	public static function get_wp_mail_headers( $emails )
	{
		$headers = array();

		# From
		$headers[] = "From: " . trim( sanitize_email( $emails['from']['email'] ) );

		# CC
		foreach ( $emails['cc'] as $cc ) {
			if ( "" !== $cc ) $headers[] = "CC: " . trim( sanitize_email( $cc ) );
		}

		# BCC
		foreach ( $emails['bcc'] as $bcc ) {
			if ( "" !== $bcc ) $headers[] = "BCC: " . trim( sanitize_email( $bcc ) );
		}

		return $headers;
	}

	public static function get_error( $name, $return_type = 'html' )
	{
		$errors = self::$errors;

		if ( ! isset( $errors[ $name ] ) ) return false;

		switch ( $return_type ) {
			case 'array':
				return $errors[ $name ];
				break;

			case 'html':
			default:
				ob_start();
				echo "<p class='error'>";
				echo implode( '</p><p class="error">', $errors[ $name ] );
				echo "</p>";
				return ob_get_clean();
				break;
		}

		return $errors[ $name ];
	}

	/**
	 * Sanitize the $post_fields
	 * @param  array $post_fields 		The array to sanitize
	 * @return array $sanitized_fields 	The sanitized $post_fields
	 */
	public static function sanitize( $post_fields = null )
	{
		$post_fields = is_null( $post_fields ) ? $_POST : $post_fields;

		$sanitized_fields = array();

		foreach ( $post_fields as $name => $post_field ) {
			if ( isset( $post_field['ID'] ) && isset( $post_field['value'] ) ) {
				$sanitized_fields[ $name ]['ID'] = $post_field['ID'];
				$sanitized_fields[ $name ]['value'] = sanitize_text_field( $post_field['value'] );
			} else {
				$sanitized_fields[ $name ] = $post_field;
			}
		}

		return $sanitized_fields;
	}

	/**
	 * Sends the Email based on options provided.
	 *
	 * @param  array $email_array The array containing email subject, message, embedded media (if any), and attachments
	 * @param  array $options     Settings to decide how to send the email.
	 * @return mixed
	 */
	public static function send( $email_array, $options )
	{
		$protocol = isset( $options['protocol'] ) ? $options['protocol'] : 'smtp_fallback';

		$subject = $email_array['subject'];
		$message = $email_array['message'];
		$emails = $options['mail'];
		$embeds = $email_array['embeds'];
		$attachments = $email_array['attachments'];
		// $headers will be created based on protocol

		switch ( $protocol ) {

			/**
			 * SMTP Fallback
			 *
			 * 1. PHP mail - wp_mail()
			 * 2. SMTP     - PHPMailer()
			 *
			 * Will try to send email with wp_mail().
			 * If it failed, then will try for SMTP Protocol.
			 *
			 */
			case 'smtp_fallback':
				$mail = self::smtp_init( $subject, $message, $emails, $embeds, $attachments, $options );

				if ( ! $mail->Send() ) {
					return $mail->ErrorInfo();
				}
				break;

			/**
			 * SMTP Only
			 *
			 * 1. SMTP
			 *
			 * Will send using SMTP
			 *
			 */
			case 'smtp_only':
				//
				break;

			/**
			 * WP_Mail only
			 *
			 * 1. PHP mail
			 *
			 * will try to send using Worpress' `wp_mail()` function.
			 */
			case 'mail_only':
			default:
				//
				break;
		}

		return true;
	}

	/**
	 * Compose message from $post_fields or ($_POST)
	 *
	 * @param  array  	$post_fields  	An associative array of 'ID' and 'value' to process into the message.
	 * @param  array  	$options      	Options for composing messages.
	 *
	 * @return array
	 */
	public static function compose( $post_fields = null, $options )
	{
		$post_fields = ! is_null( $post_fields ) ? $post_fields : $_POST;

		$subject = isset( $options['template']['subject'] ) ? $options['template']['subject'] : "";
		$message = isset( $options['template']['message'] ) ? $options['template']['message'] : "";
		$embedded = array();
		$attachments = array();

		foreach ( $post_fields as $field_name => $post_field ) {
			if ( isset( $post_field['value'] ) ) {
				$subject = preg_replace( '/%'.$field_name.'%/', $post_field['value'], $subject );
				$message = preg_replace( '/%'.$field_name.'%/', $post_field['value'], $message );
			}
		}

		switch ( $options['type'] ) {
			case 'html':
				preg_match_all( self::$regex_embedded_image, $message, $matches );

				// $message = preg_replace( self::$regex_embedded_image, '<img src="$2"$3>', $message );

				foreach ( $matches[2] as $i => $match ) {
					$parts = pathinfo( $match );

					$filename = parse_url( $match, PHP_URL_PATH );
					$embedded[ $i ]['filename'] = $_SERVER['DOCUMENT_ROOT'] . $filename;
					$embedded[ $i ]['cid'] =  $parts['filename'];
					$embedded[ $i ]['name'] = basename( $match );
// echo "<pre>";
//     var_dump( $embedded ); die();
// echo "</pre>";
					$message = str_replace( $match, "cid:".basename( $parts['filename'] ), $message );
				}

				// preg_match_all('#<\s*img [^\>]*src\s*=\s*(["\'])(.*?)\1#im', $message, $matches);
				$message = html_entity_decode( $message );
				// echo "<pre>";
				//     var_dump( htmlentities($message) ); die();
				// echo "</pre>";

				break;

			case 'plain-text':
			case 'plain_text':
			case 'plaintext':
			default:
				$message = htmlentities( $message );
				break;
		}


		return array(
			'message' => $message,
			'embeds' => $embedded,
			'subject' => $subject,
			'attachments' => $attachments, // TO DO.
		);
	}

	public static function wp_mail( $options )
	{
		$to = $options['to'];
		$subject = $options['subject'];
		$message = $options['message'];
		$headers = $options['headers'];
		$attachments = $options['attachments'];

		return wp_mail( $to, $subject, $message, $headers, $attachments );
	}

	private static function smtp_init( $subject, $message, $emails, $embeds, $attachments, $options ) {
		self::$mail = new PHPMailer();

		$mail = self::$mail;

		# Init
		// $mail->SMTPDebug = 3;
		$mail->isSMTP();
		$mail->Host = isset( $options['smtp_details']['host'] ) ? $options['smtp_details']['host'] : 'localhost';
		$mail->Port = isset( $options['smtp_details']['port'] ) ? $options['smtp_details']['port'] : '25';

		# Encryption / Security
		$mail->SMTPSecure = isset( $options['smtp_details']['encryption'] ) ? $options['smtp_details']['encryption'] : 'tls';

		# Auth
		$mail->SMTPAuth = isset( $options['smtp_details']['authenticate'] ) ? $options['smtp_details']['authenticate'] : false;
		$mail->Username = $options['smtp_details']['username'];
        $mail->Password = $options['smtp_details']['password'];

        # Add ReplyTo
		$mail->addReplyTo( $emails['from']['email'], $emails['from']['name'] );

		# From
		$mail->setFrom( $emails['from']['email'], $emails['from']['name'] );

		# To
		foreach ( $emails['to'] as $to ) {
			if ( "" !== $to && ! empty( $to ) ) $mail->addAddress( trim( sanitize_email( $to ) ) );
		}

		# CC
		foreach ( $emails['cc'] as $cc ) {
			if ( "" !== $cc && ! empty( $cc ) ) $mail->addAddress( trim( sanitize_email( $cc ) ) );
		}

		# BCC
		foreach ( $emails['bcc'] as $bcc ) {
			if ( "" !== $bcc && ! empty( $bcc ) ) $mail->addAddress( trim( sanitize_email( $bcc ) ) );
		}

		# Send as HTML?
		$mail->isHTML = isset( $options['type'] ) ? $options['type'] : 'html';

		# Embeds
		if ( ! empty( $embeds ) ) {
			foreach ( $embeds as $key => $embed ) {
				// filename, cid, name
				$mail->AddEmbeddedImage( $embed['filename'], $embed['cid'], $embed['name'] );
			}
		}


		# Subject & Message
		$mail->Subject = $subject;
		$mail->Body    = wpautop( $message );
		$mail->AltBody = strip_tags( $message );

		return self::$mail = $mail;
	}

	private static function smtp_only() {
		die('smtp_only asdasd');
	}

	private static function mail_only() {
		die('mail_only asdasd');
	}

	public static function clear_posts()
	{
		$_POST = null;
		unset( $_POST );
	}
}