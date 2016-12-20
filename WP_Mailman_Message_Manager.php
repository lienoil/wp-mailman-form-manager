<?php

class WP_Mailman_Message_Manager
{
	protected $post_type = 'message';

	/**
     * Creates a Post entry
     * This is used to copy the email sent by user
     * to the admin backend.
     *
     * @return $post_id
     */
    public static function save( $options ) {
        // Setup the author, slug, and title for the post
        $author_ID = 1; // Admin
        $title     = $options->subject;
        $slug      = $options->slug . '-' . date('Y-dd-i-s');
        $content   = $options->message;

        // Create post object
        $new_entry = array(
            'post_title'    => wp_strip_all_tags($title),
            'post_name'     => $slug,
            'post_content'  => $content,
            'post_status'   => 'unread',
            'post_author'   => $author_ID,
            'post_type'     => self::$post_type,
        );
        // Insert the post into the database
        // Set the post ID so that we know the post was created successfully
        $post_id = wp_insert_post( $new_entry );

        return $post_id;
    }

    public static function write( $post_fields, $options = array() )
    {
    	if ( isset( $options['nonce'] ) && ! isset( $post_fields[ $options['nonce'] ] ) && ! wp_verify_nonce( $post_fields[ $options['nonce'] ], $options['nonce'] ) ) return false;

    	foreach( $post_fields as $name => $post_field ) {
    		if ( isset( $post_field['ID'] ) && isset( $post_field['value'] ) ) {
    			$field = get_post( $post_field['ID'] );
    			$field_options = get_post_meta( $field->ID, $options['post_metaname'] );
    			echo "<pre>";
    			    var_dump( $field_options );
    			echo "</pre>";
    		}
    	}
    }
}