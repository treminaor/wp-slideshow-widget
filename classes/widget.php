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
		);

		return $defaults;

	}

	/**
	 * Grabs the array of images currently set for the slideshow.
	 *
	 * @since 0.1
	 */
	private function get_slideshow_array() {

		return $slideshow_array;

	}

	/**
	 * Adds a blank index to the slideshow array
	 *
	 * @since 0.1
	 */
	private function add_slide_index() {

		array_push($slideshow_array, array("", ""));

	}

	/**
	 * Removes the given index from the slideshow array
	 *
	 * @since 0.1
	 */
	private function remove_slide_index($index) {

		if($index == null)
			return;

		array_splice($slideshow_array, $index, 1);
	}


	/**
	 * Outputs the widget based on the arguments input through the widget controls.
	 *
	 * @since 0.1
	 */
	public function widget( $args, $instance ) {
		
		extract( $args );

		$debugMode = true; //Note: If enabled, fallback is disabled for bad categories (i.e. the widget will not render if there is no matching press category found on the forum)

		$html = "";

		echo $html;

	}

	/**
	 * Updates the widget control options for the particular instance of the widget.
	 *
	 * @since 0.1
	 */
	public function update( $new_instance, $old_instance ) {

		$instance                     = $old_instance;
		$instance['title']            = sanitize_text_field( $new_instance['title'] );

		return $instance;

	}

	/**
	 * Displays the widget control options in the Widgets admin screen.
	 *
	 * @since 0.1
	 */
	public function form( $instance ) {

		// Merge the user-selected arguments with the defaults.
		$instance = wp_parse_args( (array) $instance, $this->get_default_args() );

		// Extract the array to allow easy use of variables.
		extract( $instance );

		// Loads the widget form.
		include( WPSW_INCLUDES . 'form.php' );

	}

}
