<?php

class WP_Simple_Slideshow_Widget extends WP_Widget {

	/**
	 * Sets up the widgets.
	 *
	 * @since 0.1
	 */
	public function __construct() {

		/* Set up the widget options. */
		$widget_options = array(
			'classname'   => 'wp_simple_slideshow_widget recent-posts-extended',
			'description' => __( 'Adds a widget for displaying a simple image slideshow.' ),
			'customize_selective_refresh' => true
		);

		$control_options = array(
			
		);

		/* Create the widget. */
		parent::__construct(
			'wp_simple_slideshow_widget',                               // $this->id_base
			__( 'WP Simple Slideshow', 'wp_simple_slideshow_widget' ), 	// $this->name
			$widget_options,                                            // $this->widget_options
			$control_options                                            // $this->control_options
		);

		$this->alt_option_name = 'wp_simple_slideshow_widget';

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

}
