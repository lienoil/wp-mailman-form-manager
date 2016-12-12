<?php
/**
 * Plugin Name: WP Mailman Form Manager
 * Description: A humble Form builder and simple Email management Wordpress plugin
 * Version: 1.0.0
 * Plugin URI: https://github.com/lioneil/wp-mailman-form-manager
 * Author: John Lioneil Dionisio
 */

if ( ! function_exists( 'add_action' ) ) {
    echo "Hi there!  I'm just a plugin, not much I can do when called directly.";
    exit;
}

$pluginname = plugin_basename(__FILE__);//'mailmanformmanager';
$settings_pluginname = "mailman-form-settings";
$mailmanformmanager_global = require("includes/config/global.php");
$forms = require("includes/config/edit-form-after-title.php");
$metaboxes = require("includes/config/metaboxes.php");
$validations = require("includes/config/fields-validation.php");
$cpts = require_once __DIR__ . "/includes/config/custom-post-types.php";

/**
 * Register Custom Post Types
 * in /includes/config/custom-post-types.php
 *
 * @return void
 */
add_action( 'init', 'mailmanformmanager_cpt_init' );

function mailmanformmanager_cpt_init() {
	global $cpts;

	if ( is_null( $cpts ) ) return false;

	foreach ( $cpts as $name => $cpt ) {
        $labels = array(
            'name'              => ucwords( $name ),
            'singular_name'     => ucwords( $cpt['single'] ),
            'all_items'         => "All " . ucwords( $cpt['plural'] ),
            'add_new'           => "Add New " . ucwords( $cpt['single'] ),
            'add_new_item'      => "Add New " . ucwords( $cpt['single'] ),
            'edit_item'         => "Edit " . ucwords( $cpt['single'] ),
            'new_item'          => "New " . ucwords( $cpt['single'] ),
            'view_item'         => "View " . ucwords( $cpt['single'] ),
            'search_items'      => "Search " . ucwords( $cpt['plural'] ),
            'not_found'         => "No " . ucwords( $cpt['single'] ) . " found",
            'not_found_in_trash'=> "No " . ucwords( $cpt['single'] ) . " found in trash",
            'parent_item_colon' => "Parent " . ucwords( $cpt['single'] ) . ":",
            'menu_name'         => _x( ucwords( $cpt['plural'] ), 'wordpress' )
        );

        $args = array_merge(
        	array(
	            'labels'            => $labels,
	            'query_var'         => $name,
	            'rewrite'           => true,
	            // 'menu_icon'         => '',
	            'public'            => false,
	            'hierarchical'      => true,
	            // 'supports'          => array(),
	            'show_ui'           => true,
	            'show_in_menu'      => true,
	            // 'menu_position'     => 8,
	            'show_in_nav_menus' => false,
	            'publicly_queryable'=> false,
	            'exclude_from_search' => true,
	            'has_archive'       => true,
	            'can_export'        => true,
	            'capability_type'   => 'post',
	            'capabilities' => array(
	                'read' => true,
	                'create_posts' => true, // Toggles support for the "Add New" function
	            ),
	            'map_meta_cap' => true, // Set to false, if users are not allowed to edit/delete existing posts
	        ),
	        $cpt['args']
	    );

        register_post_type( $name, $args );
    }
}


/**
 * Add Submenu Options on Forms
 *
 * @return void
 */
add_action( 'admin_menu', 'mailmanformmanager_submenu_option_for_forms' );

function mailmanformmanager_submenu_option_for_forms() {
	global $pluginname, $cpts, $settings_pluginname, $post_type;

    add_submenu_page(
        'edit.php?post_type=' . $cpts['form']['name'],
        'Settings',
        'Settings',
        'manage_options',
        $settings_pluginname,
        function () use ( $settings_pluginname ) {
            global $post, $mailmanformmanager_global;

            # Render Settings Page view
            $name = $settings_pluginname;
            $old = get_option( $name );

            echo "<pre>";
                var_dump( $old );
            echo "</pre>";

        	require_once __DIR__ . '/includes/views/admin/options.php';
        }
    );
}


/**
 * Register the Settings Page
 *
 * @var
 */
add_filter( "plugin_action_links_$settings_pluginname", "mailmanformmanager_add_plugin_action_links" );

add_action( 'admin_init', function() {
	global $settings_pluginname;

	$plugin_settings = $settings_pluginname;
    $options_name = $settings_pluginname;
    # Page
    register_setting( $plugin_settings, $options_name );
});

$hook = 'edit.php?post_type=' . $cpts['form']['name'] . "&page=$settings_pluginname";
add_action( 'load-'.$hook, 'mailmanformmanager_do_on_plugin_settings_save' );

function mailmanformmanager_do_on_plugin_settings_save() {
  	if( isset($_GET['settings-updated']) && $_GET['settings-updated'] ) {
    	//plugin settings have been saved. Here goes your code
  		echo "win32_get_last_control_message(oid)";
   	}
}


function mailmanformmanager_add_plugin_action_links( $links ) {
	global $cpts, $settings_pluginname;

    $settings_link = '<a href="' . admin_url("edit.php?post_type={$cpts['form']['name']}&page=$settings_pluginname") . '">Settings</a>';
    array_unshift( $links, $settings_link );
    return $links;
}


/**
 * Add Custom Post Type `field`'s metaboxes
 *
 * @return void
 */
add_action( 'edit_form_after_title', 'mailmanformmanager_edit_form_after_title' );

function mailmanformmanager_edit_form_after_title() {
	global $post, $pluginname, $typenow, $forms, $mailmanformmanager_global, $validations;

	foreach ( $forms as $name => $form ) {
		if ( in_array( $typenow, array( $form['for'] ) ) ) {
			$old = get_post_meta( $post->ID, $name, true );
			if ( file_exists( __DIR__ . $form['view'] ) ) {
				require __DIR__ . "/includes/views/partials/nonce.php";
				require __DIR__ . $form['view'];
			}
		}
	}

}


/**
 * Register all metaboxes found
 * in /includes/config/metaboxes.php
 *
 * @see  /includes/config/metaboxes.php
 * @return void
 */
add_action( 'add_meta_boxes', 'mailmanformmanager_cpt_metaboxes' );

function mailmanformmanager_cpt_metaboxes() {
	global $cpts, $metaboxes, $mailmanformmanager_global;

    if ( is_null( $metaboxes ) ) return false;

    foreach ( $metaboxes as $name => $metabox ) {
        add_meta_box(
            $metabox['id'], // $id
            $metabox['title'], // $title
            function() use ( $metabox, $mailmanformmanager_global, $name ) {
                global $post, $post_id;

                get_mailman_nonce();

                $old = get_post_meta( $post->ID, $name, true );

                require __DIR__ . $metabox['view'];
            }, // $callback
            $metabox['for'], // $page
            $metabox['context'], // $context
            'default' // $callback args
        );
    }
}


/**
 * Save metaboxes
 *
 * @param  int $post_id The posts' ID
 * @param  Object $post    The post object
 * @return void
 */
add_action( 'save_post', 'mailmanformmanager_cpt_save_metaboxes' );

function mailmanformmanager_cpt_save_metaboxes( $post_id ) {
	global $forms, $mailmanformmanager_global, $metaboxes, $post;

    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) {
        return $post_id;
    }

    if ( ! current_user_can( 'edit_post', $post_id ) || ! current_user_can( 'edit_page', $post_id ) ) {
        return $post_id;
    }

    if ( wp_is_post_revision( $post_id ) ) {
		return;
    }

    if ( isset( $_POST[ $mailmanformmanager_global['nonce'] ] ) && ! isset( $_POST[ $mailmanformmanager_global['nonce'] ] ) && ! wp_verify_nonce( $_POST[ $mailmanformmanager_global['nonce'] ], $mailmanformmanager_global['nonce'] ) ) {
        return $post_id;
    }

    foreach ( $forms as $name => $form ) {
    	$metas = isset( $_POST[ $name ] ) ? $_POST[ $name ] : false;

        if ( get_post_meta( $post_id, $name, false ) ) {
            // If the custom field already has a value
            update_post_meta( $post_id, $name, $metas );
        } else {
            // If the custom field doesn't have a value
            add_post_meta( $post_id, $name, $metas );
        }

        if ( ! $metas ) delete_post_meta( $post_id, $name );
    }

    foreach ( $metaboxes as $key => $metabox ) {
        $metas = isset( $_POST[ $metabox['name'] ] ) ? $_POST[ $metabox['name'] ] : false;

        if ( get_post_meta( $post_id, $metabox['name'], false ) ) {
            // If the custom field already has a value
            update_post_meta( $post_id, $metabox['name'], $metas );
        } else {
            // If the custom field doesn't have a value
            add_post_meta( $post_id, $metabox['name'], $metas );
        }

        if ( ! $metas ) delete_post_meta( $post_id, $metabox['name'] );
    }
}



/**
 * Enqueue Styles & Scripts
 *
 */
add_action( 'admin_enqueue_scripts', 'mailmanformmanager_enqueue_admin_styles' );

function mailmanformmanager_enqueue_admin_styles() {
	wp_enqueue_style( 'selectize', plugins_url('/vendor/selectize.js/dist/css/selectize.css', __FILE__), false, '2.0.4' );
    wp_enqueue_style( 'skeleton', plugins_url('/vendor/skeleton/css/skeleton.css', __FILE__), false, '2.0.4' );
    wp_enqueue_style( 'admin', plugins_url('/admin/css/admin.css', __FILE__), false, '2.0.4' );
}

add_action( 'admin_enqueue_scripts', 'mailmanformmanager_enqueue_admin_scripts', 11 );

function mailmanformmanager_enqueue_admin_scripts() {
	global $pluginname, $post_type, $mailmanformmanager_global, $cpts, $settings_pluginname;

	if ( $post_type == 'field' || $post_type == 'form' || $post_type == 'message' || ( isset( $_GET['page'] ) && $_GET['page'] == $settings_pluginname ) ) {
		wp_enqueue_script( 'jquery-ui-core' );
		wp_enqueue_script( 'jquery-ui-accordion' );

		wp_enqueue_script( 'selectize', plugins_url('/vendor/selectize.js/dist/js/standalone/selectize.min.js', __FILE__), array('jquery'), '1.2.3', true );
	    wp_enqueue_script( 'cloner', plugins_url('/vendor/jquery-cloner/dist/jquery.cloner.js', __FILE__), array('jquery'), '1.2.3', true );
	    wp_enqueue_script( 'slugger', plugins_url('/vendor/jquery-slugger/dist/jquery.slugger.min.js', __FILE__), array('jquery'), '1.0.4', true );
	    wp_enqueue_script( 'admin', plugins_url('/admin/js/admin.js', __FILE__), array('jquery'), $mailmanformmanager_global['text-domain'], true );
	}
}


/**
 * # Displaying & Shortcodes
 *
 */
add_filter( 'the_content', 'mailmanformmanager_display_the_content' );

function mailmanformmanager_display_the_content( $the_content ) {
    global $post, $forms, $mailmanformmanager_global, $pluginname;

    $cpts = require __DIR__ . "/includes/config/custom-post-types.php";

    $query = get_posts("post_type={$cpts['form']['name']}");

    if ( $query ) {
        foreach ( $query as $q ) {

            $options = get_post_meta($q->ID, $forms['form']['name'], true);

            if ( ( $options !== "" && isset($options) ) && array_key_exists('display_to_page', $options) && array_key_exists('shortcode', $options) && get_the_ID() == $options['display_to_page'] ) {
                $the_content .= do_shortcode( $options['shortcode'] );
            }

        }
    }

    return $the_content;
}

foreach ( $mailmanformmanager_global['shortcodes'] as $shortcode ) {
    add_shortcode( $shortcode, function ( $atts, $content = "" ) use ( $shortcode ) {
    	global $post_id, $forms, $mailmanformmanager_global, $pluginname;

    	$atts = shortcode_atts(
    		array(
    			'id' => $post_id,
    		),
    		$atts
    	);
    	ob_start();

    	$form = get_post($atts['id']);

    	$form_options = get_post_meta($form->ID, $forms['form']['name'], true);


    	if ( file_exists( get_template_directory_uri() . "/wp-mailman-form-manager/form.php" ) ) {
	    	require get_template_directory_uri() . "/wp-mailman-form-manager/form.php";
    	} else {
    		require __DIR__ . "/includes/views/shortcodes/form.php";
    	}

    	wp_reset_postdata();

    	return ob_get_clean();
    });
}


/**
 * Handling Sending
 *
 */
function mailmanformmanager_send_email() {
	global $forms;

    /**
     * At this point, $_GET/$_POST variable are available
     *
     * We can do our normal processing here
     */
    $post_fields = $_POST;

    // Sanitize the POST field
    $errors = array();

    foreach ( $post_fields as $name => $post_field ) {

    	if ( isset( $post_field['ID'] ) && isset( $post_field['value'] ) ) {
	    	$value = $post_field['value'];
	    	$field = get_post( $post_field['ID'] );
	    	$field_options = get_post_meta( $field->ID, $forms['field']['name'], true );

	    	/**
	    	 * Validate by Rules
	    	 * as defined in the fields_options['rules'].
	    	 *
	    	 */
	    	$rules = $field_options['rules'];

	    	foreach ( $rules as $rule ) {
	    		if ( ! empty( $rule['name'] ) && ! mailmanformmanager_validate( $rule['name'], $rule['value'], $value ) ) {
    				$errors[$name][] = $rule['message'];
	    		}
	    	}

    	}
    }

	if ( ! empty( $errors ) ) {
		echo "<pre>";
		    var_dump( $errors );
		echo "</pre>";
	}

    // Generate email content
    // Send to appropriate email
}

add_action( "admin_post_nopriv_$pluginname", 'mailmanformmanager_send_email' );

add_action( "admin_post_$pluginname", 'mailmanformmanager_send_email' );




/**
 * Private Helper Functions
 *
 */
function make_select( $name, $value = null, $class = "", $options = array() ) {
	?>
	<select name="<?php echo $name; ?>" class="regular-text-fluid <?php echo $class; ?>">
		<option value="">--Select Options--</option>
		<?php foreach ($options as $code => $name) {
			$selected = ($value == $code) ? 'selected="selected"' : ""; ?>
			<option value="<?php echo $code; ?>" <?php echo $selected; ?>><?php echo $name; ?></option>
		<?php } ?>
	</select>
	<?php
}

function convert_post_to_array( $a_post ) {
	$arr = array();
	foreach ($a_post as $p) {
		$arr[$p->ID] = $p->post_title;
	}
	return $arr;
}

function mailman_make_field( $type = "text", $attr = array(), $value = null ) {
	$attr_arr = array();
	foreach ($attr as $name => $val) {
		$attr_arr[] = "$name='$val'";
	}
	$attr_html = implode(" ", $attr_arr);

	$html = "";

	switch ($type) {
		case 'select':
			$value = explode("|", $value);
			$value_arr = array();

			foreach ($value as $v) {
				$selected = strpos($v, '*') !== false ? 'selected="selected"' : "";

				$v = str_replace("*", '', $v);
				$value_arr[] = "<option value='$v' $selected>$v</option>";
			}
			$value_html = implode("", $value_arr);
			$html = "<select $attr_html autocomplete='off'>$value_html</select>";
			break;

		case 'textarea':
			$html = "<textarea $attr_html>$value</textarea>";
			break;

		case 'text':
		case 'email':
		case 'color':
		default:
			$html = "<input type='$type' $attr_html value='$value'>";
			break;
	}

	return $html;
}


function check_if_checked( $new_value='', $old_value = '', $checked = "checked", $else_checked = "" ) {
	return $new_value == $old_value ? $checked : $else_checked;
}

function get_mailman_nonce() {
	global $mailmanformmanager_global, $pluginname;

	require __DIR__ . '/includes/views/partials/nonce.php';
}

function mailmanformmanager_validate( $name, $value, $input ) {
	switch ( $name ) {
		case 'email':
			if ( is_email( $input ) == $value ) return true;
			break;

		case 'digits':
			if ( is_numeric( $input ) == $value ) return true;
			break;

		case 'maxlength':
			if ( strlen( $input ) <= $value ) return true;
			break;

		case 'minlength':
			if ( strlen( $input ) >= $value ) return true;
			break;

		case 'required':
			if ( !empty( $name ) || $name !== "" ) return true;
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
			if ( checktime( $date[0], $date[1], $date[2] ) ) return true;
			break;


		default:
			return true;
			break;
	}

	return false;
}

function checktime( $hour, $min, $sec ) {
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