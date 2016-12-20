<?php
/**
 * WP Mailman Form Manager Class
 * v1.0.0
 *
 */
class WP_Mailman_Form_Manager
{
	protected $pluginname;
	protected $globals;
	protected $settings_hook;
	protected $file;
	protected $dir;
	protected $cpts, $forms, $metaboxes, $pages, $settings, $shortcodes, $enqueueables;
	protected $form_builder;
	protected $form_errors;
	protected $current_shortcode_form;

	/**
	 * Initialize variables
	 *
	 * @param const $file
	 */
	public function __construct( $file = __FILE__ )
	{
		$this->file = $file;
		$this->dir = __DIR__;
		$this->cpts = require __DIR__ . '/includes/config/custom-post-types.php';
		$this->forms = require __DIR__ . '/includes/config/forms.php';
		$this->globals = require __DIR__ . '/includes/config/global.php';
		$this->metaboxes = require __DIR__ . '/includes/config/metaboxes.php';
		$this->pages = require __DIR__ . '/includes/config/pages.php';
		$this->pluginname = plugin_basename( $file );
		$this->settings = require __DIR__ . '/includes/config/settings.php';
		$this->settings_hook = "edit.php?post_type={$this->cpts['form']['name']}&page={$this->pluginname}";
		$this->shortcodes = require __DIR__ . '/includes/config/shortcodes.php';
		$this->enqueueables = require __DIR__ . '/includes/config/enqueueables.php';
	}

	/**
	 * Hook Actions
	 * Wordpress actions here.
	 *
	 * @return void
	 */
	public function hook_actions()
	{
		add_action( 'init', array( $this, 'register_post_types' ) );

		add_action( 'admin_menu', array( $this, 'register_menus' ) );
		add_action( 'admin_menu', array( $this, 'register_submenus' ) );

		add_action( 'admin_init', array( $this, 'register_settings' ) );
		add_action( 'load-'.$this->settings_hook, array( $this, 'save_settings' ) );

		add_action( 'edit_form_after_title', array( $this, 'register_edit_form_after_title' ) );

		add_action( 'add_meta_boxes', array( $this, 'register_metaboxes' ) );
		add_action( 'save_post', array( $this, 'save_metaboxes' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ), 11 );

		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_public_styles' ) );

		$this->add_shortcodes();
		$this->filter_content();

		$this->add_action_links( $this->settings );

		$this->filter_gettext();
		// add_filter("gettext", "translate_publish_post_status", 10, 2);
		// add_filter("ngettext", "translate_publish_post_status_number", 10, 5);
		// add_filter("ngettext_with_context", "translate_publish_post_status_number", 10, 6)

		add_action( "admin_post_nopriv_{$this->pluginname}", array( $this, 'send' ) );
		add_action( "admin_post_{$this->pluginname}", array( $this, 'send' ) );
	}

	/**
	 * Register Custom Post Types
	 * in /includes/config/custom-post-types.php
	 *
	 * @return void
	 */
	public function register_post_types()
	{
		$cpts = $this->cpts;

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
		            'public'            => false,
		            'hierarchical'      => true,
		            'show_ui'           => true,
		            'show_in_menu'      => true,
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

	        /**
	         * Register the Post Type
	         *
	         */
	        register_post_type( $name, $args );
	    }
	}

	/**
	 * Register Menu
	 *
	 * @return void
	 */
	public function register_menus()
	{
		$global = $this->globals;
		$pages = $this->pages;

		foreach ($pages as $name => $page) {
			/**
			 * Add Menu to Specified position
			 *
			 * @var
			 */
			add_menu_page(
				$page['page-title'],
		        $page['menu-title'],
		        $page['capability'],
		        $page['menu-slug'],
		        function () use ( $global, $page ) {
		            global $post;

		            # Render Settings Page view
		            $name = $page['menu-slug'];
		            $old = get_option( $name );

		        	require_once __DIR__ . $page['view'];
		        },
		        $page['icon-url'],
		        $page['position']
			);
		}
	}

	/**
	 * Register Submenu
	 *
	 * @return void
	 */
	public function register_submenus()
	{
		$globals = $this->globals;
		$settings = $this->settings;
		$plugin_basename = $this->pluginname;

		foreach ( $settings as $name => $setting ) {
			/**
			 * Add Settings Page to a specified page
			 *
			 * @var
			 */
			add_submenu_page(
		        $setting['for'],
		        $setting['page-title'],
		        $setting['menu-title'],
		        $setting['capability'],
		        $plugin_basename,
		        function () use ( $globals, $name, $plugin_basename, $setting ) {
		            global $post;

		            # Render Settings Page view
		            $old = get_option( $name );

		        	require_once __DIR__ . $setting['view'];
		        }
		    );
		}
	}

	/**
	 * Register the Settings Page
	 *
	 * @return void
	 */
	public function register_settings()
	{
		$plugin_settings = $this->pluginname;
		foreach ( $this->settings as $options_name => $setting ) {
		    register_setting( $plugin_settings, $options_name );
		}
	}

	/**
	 * Save the Settings Page
	 *
	 * @return void
	 */
	public function save_settings()
	{
		if( isset($_GET['settings-updated']) && $_GET['settings-updated'] ) {
	    	//plugin settings have been saved. Here goes your code
	  		echo "win32_get_last_control_message(oid)";
	   	}
	}

	/**
	 * Add Custom Post Type `field`'s metaboxes
	 *
	 * @return void
	 */
	public function register_edit_form_after_title()
	{
		global $post, $typenow;

		$forms = $this->forms;
		$globals = $this->globals;

		foreach ( $forms as $name => $form ) {
			if ( in_array( $typenow, array( $form['for'] ) ) ) {
				$old = get_post_meta( $post->ID, $name, true );
				if ( file_exists( __DIR__ . $form['view'] ) ) {

					$this->get_nonce();

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
	public function register_metaboxes()
	{
		$metaboxes = $this->metaboxes;
		$globals = $this->globals;

		if ( is_null( $metaboxes ) ) return false;

	    foreach ( $metaboxes as $name => $metabox ) {
	        add_meta_box(
	            $metabox['id'], // $id
	            $metabox['title'], // $title
	            function() use ( $metabox, $globals, $name ) {
	                global $post, $post_id;

	                $this->get_nonce();

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
	 * @return void
	 */
	public function save_metaboxes( $post_id )
	{
		global $post;

		$forms = $this->forms;
		$globals = $this->globals;
		$metaboxes = $this->metaboxes;

	    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) {
	        return $post_id;
	    }

	    if ( ! current_user_can( 'edit_post', $post_id ) || ! current_user_can( 'edit_page', $post_id ) ) {
	        return $post_id;
	    }

	    if ( wp_is_post_revision( $post_id ) ) {
			return;
	    }

	    if ( isset( $_POST[ $globals['nonce'] ] ) && ! isset( $_POST[ $globals['nonce'] ] ) && ! wp_verify_nonce( $_POST[ $globals['nonce'] ], $globals['nonce'] ) ) {
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

	    foreach ( $metaboxes as $name => $metabox ) {
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
	}

	/**
	 * Register the Shortcode.
	 *
	 * @return html
	 */
	public function add_shortcodes()
	{
		$shortcodes = $this->shortcodes;

		foreach ( $shortcodes as $shortcode => $shortcode_options) {

			add_shortcode( $shortcode, function ( $atts, $content = "" ) use ( $shortcode_options ) {
		    	global $post_id;

		    	$globals = $this->globals;
		    	$pluginname = $this->pluginname;
		    	$post_metaname = $shortcode_options['post-metaname'];

		    	$atts = shortcode_atts(
		    		$shortcode_options['atts'](),
		    		$atts
		    	);

		    	ob_start();

		    	if ( file_exists( get_template_directory_uri() . "/wp-mailman-form-manager/{$shortcode_options['shortcode']}.php" ) ) {
			    	require get_template_directory_uri() . "/wp-mailman-form-manager/{$shortcode_options['shortcode']}.php";
		    	} else {
		    		require __DIR__ . $shortcode_options['view'];
		    	}

		    	wp_reset_postdata();

		    	return ob_get_clean();
		    } );

		}
	}

	public function set_current_shortcode_form( $current_shortcode_form )
	{
		$this->current_shortcode_form = new StdClass();
		$this->current_shortcode_form->ID = $current_shortcode_form['ID'];
		$this->current_shortcode_form->fields = $current_shortcode_form['fields'];
		$this->current_shortcode_form->template = $current_shortcode_form['template'];
		$this->current_shortcode_form->errors = $current_shortcode_form['errors'];
	}

	public function get_current_shortcode_form()
	{
		return $this->current_shortcode_form;
	}

	/**
	 * Add Screen Options to specific page.
	 *
	 */
	public function add_screen_options()
	{
		$option = 'per_page';

		$args = array(
		    'label' => 'Movies',
		    'default' => 10,
		    'option' => 'mailman_formtemplate_helper_per_page',
		);

		add_screen_option( $option, $args );
	}

	/**
	 * Hooks a function to the set-screen-option filter.
	 *
	 * @param [type] $status [description]
	 * @param [type] $option [description]
	 * @param [type] $value  [description]
	 */
	public function set_screen_option( $status, $option, $value )
	{
    	return $value;
	}

	/**
	 * Filter content for every
	 * applicable Custom Post Type
	 *
	 * @return void
	 */
	public function filter_content()
	{
		$cpts = $this->cpts;

		foreach ( $cpts as $name => $cpt ) {
			if ( isset( $cpt['shortcodable'] ) && true === $cpt['shortcodable'] ) {

				add_filter( 'the_content', function ( $the_content ) use ( $name ) {
					global $post;

				    $query = get_posts("post_type=$name"); // Use $name

				    if ( $query ) {
				        foreach ( $query as $q ) {

				            $options = get_post_meta($q->ID, $name, true);

				            if ( ( $options !== "" && isset( $options ) ) && array_key_exists( 'display_to_page', $options ) && array_key_exists( 'shortcode', $options ) && get_the_ID() == $options['display_to_page'] ) {
				                $the_content .= do_shortcode( $options['shortcode'] );
				            }

				        }
				    }

				    return $the_content;
				} );

			}
		}
	}

	/**
	 * Filter the Publish Button
	 *
	 * @return string
	 */
	public function filter_gettext()
	{
		add_filter( 'gettext', function ( $translated, $original, $domain ) {
			// exit early
			$params = array(
				'domain' => 'default',
				'context' => 'backend',
		        'replacements' => array(
		        	'Publish' => 'Save',
		           	// 'Preview' => 'Lurk',
		           	'Saveed' => 'Saved',
		        ),
		    	'post_type' => array( 'message-template', 'form-template', 'form', 'field' ),
		    );

	        if ( 'backend' == $params['context'] ) {
	            global $post_type;

	            if ( ! empty ( $post_type ) && ! in_array( $post_type, $params['post_type'] ) ) {
	                return $translated;
	            }
	        }

	        if ( $params['domain'] !== $domain ) {
	            return $translated;
	        }

	        // Finally replace
	        return strtr( $original, $params['replacements'] );

		}, 10, 3 );

	}

	/**
	 * Add action links to plugin page.
	 *
	 * @param array $action_links
	 */
	public function add_action_links( $action_links = array() )
	{
		$plugin_basename = $this->pluginname;

		foreach ($action_links as $name => $link) {
			$prefix = is_network_admin() ? 'network_admin_' : '';

			/**
			 * Add a Action links
			 */
		    add_filter( "{$prefix}plugin_action_links_{$plugin_basename}", function ( $links ) use ( $link, $plugin_basename ) {
		    	$settings_link = '<a href="' . admin_url("{$link['for']}&page=$plugin_basename") . '">Settings</a>';
			    array_unshift( $links, $settings_link );
			    return $links;
		    }, 10, 4 );
		}
	}

	/**
	 * Enqueue Admin Stylesheets
	 *
	 * @return void
	 */
	public function enqueue_admin_styles()
	{
		wp_enqueue_style( 'selectize', plugins_url( '/vendor/selectize.js/dist/css/selectize.css', $this->file ), false, '2.0.4' );
	    wp_enqueue_style( 'skeleton', plugins_url( '/vendor/skeleton/css/skeleton.css', $this->file ), false, '2.0.4' );
	    wp_enqueue_style( 'mailman-admin', plugins_url( '/admin/css/admin.css', $this->file ), false, '2.0.4' );
	}

	/**
	 * Enqueue Admin Scripts
	 *
	 * @return void
	 */
	public function enqueue_admin_scripts()
	{
		global $post_type;

		$pluginname = $this->pluginname;
		$globals = $this->globals;
		$enqueueables = $this->enqueueables;

		// $post_type == 'field' || $post_type == 'form' || $post_type == 'message' || $post_type == 'form-template'
		if ( in_array( $post_type, $enqueueables ) || ( isset( $_GET['page'] ) && $_GET['page'] == $pluginname ) ) {
			wp_enqueue_script( 'jquery-ui-core' );
			wp_enqueue_script( 'jquery-ui-accordion' );
			wp_enqueue_script( 'jquery-ui-sortable' );

			wp_enqueue_script( 'selectize', plugins_url( '/vendor/selectize.js/dist/js/standalone/selectize.min.js', $this->file ), array('jquery'), '1.2.3', true );
		    wp_enqueue_script( 'cloner', plugins_url( '/vendor/jquery-cloner/dist/jquery.cloner.js', $this->file ), array('jquery'), '1.2.3', true );
		    wp_enqueue_script( 'slugger', plugins_url( '/vendor/jquery-slugger/dist/jquery.slugger.min.js', $this->file ), array('jquery'), '1.0.4', true );
		    wp_enqueue_script( 'admin', plugins_url( '/admin/js/admin.js', $this->file ), array('jquery'), $globals['text-domain'], true );
		}
	}

	/**
	 * Enqueue Public Stylesheets
	 *
	 * @return void
	 */
	public function enqueue_public_styles()
	{
		wp_enqueue_style( 'mailman-front', plugins_url( '/public/css/mailman-form-manager.css', $this->file ) );
	}

	public function get_error( $name, $return_type = 'html' )
	{
		$errors = $this->form_errors;

		if ( ! isset( $errors[ $name ] ) ) return false;

		if ( ! is_array( $errors[ $name ] ) ) return $errors[ $name ];

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
	 * --------------------------------------------------------
	 * PRIVATE METHODS
	 * --------------------------------------------------------
	 * Here be private methods.
	 *
	 */

	/**
	 * Get the nonce field
	 *
	 * @return html
	 */
	private function get_nonce()
	{
		$globals = $this->globals;

		require __DIR__ . "/includes/views/partials/nonce.php";
	}
}

