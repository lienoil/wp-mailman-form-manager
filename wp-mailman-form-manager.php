<?php
/**
 * Plugin Name: WP Mailman Form Manager
 * Plugin Description: Forms and Emails Management Wordpress Plugin
 * Version: 1.0.0
 * Plugin URI: https://github.com/lioneil/wp-mailman-form-manager
 * Description: A simple form manager for your site.
 * Author: John Lioneil Dionisio
 */

if ( ! function_exists( 'add_action' ) ) {
    echo "Hi there!  I'm just a plugin, not much I can do when called directly.";
    exit;
}

$pluginname = 'mailmanformmanager';
$global = require("includes/config/global.php");
$forms = require("includes/config/edit-form-after-title.php");
$metaboxes = require("includes/config/metaboxes.php");
$validations = require("includes/config/fields-validation.php");

/**
 * Register Custom Post Types
 * in /includes/config/custom-post-types.php
 *
 * @return void
 */
add_action( 'init', 'mailmanformmanager_cpt_init' );

function mailmanformmanager_cpt_init() {
	$cpts = require __DIR__ . "/includes/config/custom-post-types.php";

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
 * Add Custom Post Type `field`'s metaboxes
 *
 * @return void
 */
add_action( 'edit_form_after_title', 'mailmanformmanager_edit_form_after_title' );

function mailmanformmanager_edit_form_after_title() {
	global $post, $pluginname, $typenow, $forms, $global, $validations;

	foreach ( $forms as $name => $form ) {
		if ( in_array( $typenow, array($form['for']) ) ) {
			$old = get_post_meta( $post->ID, $name, true );
			require __DIR__ . "/includes/views/partials/nonce.php";
			if ( file_exists( __DIR__ . $form['view'] ) ) {
				require __DIR__ . $form['view'];
			}
		}
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

function mailmanformmanager_cpt_save_metaboxes( $post_id, $post ) {
	global $forms, $global, $metaboxes;

    if ( ! isset( $_POST[ $global['nonce'] ] ) && ! wp_verify_nonce( $_POST[ $global['nonce'] ], $global['nonce'] ) ) {
        return $post_id;
    }

    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) {
        return $post_id;
    }

    if ( ! current_user_can( 'edit_post', $post_id ) || ! current_user_can( 'edit_page', $post_id ) ) {
        return $post_id;
    }

    if ( $post->post_type == 'revision' ) {
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
    wp_enqueue_style( 'skeleton', plugins_url('/admin/vendor/skeleton/css/skeleton.css', __FILE__), false, '2.0.4' );
    wp_enqueue_style( 'admin', plugins_url('/admin/css/admin.css', __FILE__), false, '2.0.4' );
}

add_action( 'admin_enqueue_scripts', 'mailmanformmanager_enqueue_admin_scripts', 11 );

function mailmanformmanager_enqueue_admin_scripts() {
	global $pluginname, $post_type, $global;

	if ( $post_type == 'field' || $post_type == 'form' || $post_type == 'message' ) {
	    wp_enqueue_script( 'cloner', plugins_url('/admin/vendor/jquery-cloner/dist/jquery.cloner.min.js', __FILE__), array('jquery'), '1.2.3', true );
	    wp_enqueue_script( 'slugger', plugins_url('/admin/vendor/jquery-slugger/dist/jquery.slugger.min.js', __FILE__), array('jquery'), '1.0.2', true );
	    wp_enqueue_script( 'admin', plugins_url('/admin/js/admin.js', __FILE__), array('jquery'), $global['text-domain'], true );
	}
}








/**
 * Private Helper Functions
 *
 */
function make_select($name, $value = null, $class = "", $options = array()) {
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

function convert_post_to_array($a_post) {
	$arr = array();
	foreach ($a_post as $p) {
		$arr[$p->ID] = $p->post_title;
	}
	return $arr;
}