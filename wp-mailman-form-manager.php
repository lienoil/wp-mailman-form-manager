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

require_once __DIR__ . '/WP_Mailman_Form_Manager.php';

require_once __DIR__ . '/WP_Mailman_Dashboard_Widgets.php';

require_once __DIR__ . '/WP_Mailman_Message_Manager.php';

require_once __DIR__ . '/WP_Form_Builder.php';

require_once __DIR__ . '/WP_Mailman_Emailer.php';

# Vendors
require_once __DIR__ . '/includes/vendor/PHPMailer/PHPMailerAutoload.php';

# Init!
$wp_mailman_form_manager = new WP_Mailman_Form_Manager( __FILE__ );
$wp_mailman_form_manager->hook_actions();

new WP_Mailman_Dashboard_Widgets( __FILE__ );