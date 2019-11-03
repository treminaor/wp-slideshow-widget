<?php
/*
Plugin Name: WP Simple Slideshow Widget
Plugin URI: https://github.com/andyking93
Description: Adds a widget to display a slideshow of images in a widget.
Version: 0.1
Author: Andy King
Author URI: https://github.com/andyking93

*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class wp_simple_slideshow {

    public function __construct() {

        // Set the constants needed by the plugin.
        add_action( 'plugins_loaded', array( &$this, 'constants' ), 1 );

        // Internationalize the text strings used.
        add_action( 'plugins_loaded', array( &$this, 'i18n' ), 2 );

        // Load the functions files.
        add_action( 'plugins_loaded', array( &$this, 'includes' ), 3 );

        // Load the admin style.
        add_action( 'admin_enqueue_scripts', array( &$this, 'admin_style' ) );

        // Register widget.
        add_action( 'widgets_init', array( &$this, 'register_widget' ) );

        // Load CSS
        add_action( 'wp_enqueue_scripts', array( &$this, 'enqueue_scripts' ) ); 

    }

    public function constants() {

        // Set constant path to the plugin directory.
        define( 'WPSS_DIR', trailingslashit( plugin_dir_path( __FILE__ ) ) );

        // Set the constant path to the plugin directory URI.
        define( 'WPSS_URI', trailingslashit( plugin_dir_url( __FILE__ ) ) );

        // Set the constant path to the includes directory.
        define( 'WPSS_INCLUDES', WPSS_DIR . trailingslashit( 'includes' ) );

        // Set the constant path to the includes directory.
        define( 'WPSS_CLASS', WPSS_DIR . trailingslashit( 'classes' ) );

        // Set the constant path to the assets directory.
        define( 'WPSS_CSS', WPSS_URI . trailingslashit( 'css' ) );
        
    }

    public function i18n() {

    }

    public function includes() {

    }

    public function admin_style() {

    }

    public function enqueue_scripts()
    {
        wp_register_style('wp-simple-slideshow', WPSS_CSS . 'widget.css', array(), '1.0');
        wp_enqueue_style('wp-simple-slideshow');
    }

    public function register_widget() {
        require_once( WPSS_CLASS . 'widget.php' );
        register_widget( 'WP_Simple_Slideshow_Widget' );
    }

}

new WP_Simple_Slideshow;