<?php

class WP_Slideshow_Widget extends WP_Widget {

	/**
	 * Sets up the widgets.
	 *
	 * @since 0.1
	 */
	public function __construct() {

		/* Set up the widget options. */
		$widget_options = array(
			'classname'   => 'wp_slideshow_widget',
			'description' => __( 'Adds a widget for displaying a simple image slideshow.' ),
			'customize_selective_refresh' => true
		);

		$control_options = array(
			
		);

		$slideshow_array = array();

		/* Create the widget. */
		parent::__construct(
			'wp_slideshow_widget',                               // $this->id_base
			__( 'WP Slideshow Widget', 'wp_slideshow_widget' ), 	// $this->name
			$widget_options,                                            // $this->widget_options
			$control_options                                            // $this->control_options
		);

		$this->alt_option_name = 'wp_slideshow_widget';

	}

	private function get_default_args() {

		$defaults = array(
			'title'	=> '',
			'slideshow' => array(), 
			'slippryjs_speed' => 800, 
			'slippryjs_pause' => 3000, 
			'slippryjs_pager' => true,
			'slippryjs_controls'=> true,
			'slippryjs_adaptiveHeight'=> true,
		);

		return $defaults;

	}

	/**
	 * Outputs the widget based on the arguments input through the widget controls.
	 *
	 * @since 0.1
	 */
	public function widget( $args, $instance ) {

		include( WPSW_INCLUDES . 'widget.php' );
	}

	/**
	 * Updates the widget control options for the particular instance of the widget.
	 *
	 * @since 0.1
	 */
	public function update( $new_instance, $old_instance ) {
		
		return $new_instance;

	}

	/**
	 * Displays the widget control options in the Widgets admin screen.
	 *
	 * @since 0.1
	 */
	public function form( $instance ) {

		// Merge the user-selected arguments with the defaults.
		$instance = wp_parse_args( (array) $instance, $this->get_default_args() );

		// Loads the widget form.
		include( WPSW_INCLUDES . 'widget_form.php' );

	}

}
