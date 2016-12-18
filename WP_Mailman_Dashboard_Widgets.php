<?php

class WP_Mailman_Dashboard_Widgets extends WP_Mailman_Form_Manager
{
	protected $widgets;
	protected $removable_widgets = array(
        'dashboard_incoming_links' => array(
            'page'      => 'dashboard',
            'context'   => 'normal'
        ),
        'dashboard_right_now'      => array(
            'page'      => 'dashboard',
            'context'   => 'normal'
        ),
        'dashboard_recent_drafts'      => array(
            'page'      => 'dashboard',
            'context'   => 'side'
        ),
        'dashboard_quick_press'        => array(
            'page'      => 'dashboard',
            'context'   => 'side'
        ),
        'dashboard_plugins'            => array(
            'page'      => 'dashboard',
            'context'   => 'side'
        ),
        'dashboard_primary'            => array(
            'page'      => 'dashboard',
            'context'   => 'side'
        ),
        'dashboard_secondary'          => array(
            'page'      => 'dashboard',
            'context'   => 'side'
        ),
        'dashboard_recent_comments'    => array(
            'page'      => 'dashboard',
            'context'   => 'normal'
        )
    );

	public function __construct( $file )
	{
		parent::__construct( $file );

		$widgets = require __DIR__ . '/includes/config/widgets.php';
		$this->setWidgets( $widgets );

        // add_action( 'wp_dashboard_setup', array( $this, 'remove_dashboard_widgets' ) );
        add_action( 'wp_dashboard_setup', array( $this, 'add_dashboard_widgets' ) );
	}

	public function setWidgets( $widgets )
	{
		$this->widgets = $widgets;
	}

	public function getWidgets()
	{
		return $this->widgets;
	}

	/**
	 * Register Dashboard Widget
	 *
	 */
	public function add_dashboard_widgets() {
        $widgets = $this->getWidgets();

        foreach ( $widgets as $widget => $widget_options) {
	        wp_add_dashboard_widget(
	            $widget,
	            $widget_options['title'],
	            function () use ( $widget_options ) {
	                $user = wp_get_current_user();
	                $args = array(
	                    'orderby'          => 'date',
	                    'order'            => 'DESC',
	                    'post_type'        => 'message',
	                    'post_status'      => 'unread',
	                    'suppress_filters' => true,
	                );
	                $posts_array = get_posts( $args );

	                require __DIR__ . $widget_options['view'];

	            }
	        );
        }
    }

    public function remove_dashboard_widgets()
    {
    	$widgets = $this->removable_widgets;

        foreach ($widgets as $widget_id => $options) {
            remove_meta_box($widget_id, $options['page'], $options['context']);
        }
    }
}