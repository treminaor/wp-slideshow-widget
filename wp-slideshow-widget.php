<?php
/*
Plugin Name: WP Slideshow Widget
Plugin URI: https://github.com/andyking93
Description: Adds a widget to display a slideshow of images in a widget.
Version: 0.1
Author: Andy King
Author URI: https://github.com/andyking93

*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class wp_slideshow_widget_plugin {

    public function __construct() {

        // Set the constants needed by the plugin.
        add_action( 'plugins_loaded', array( &$this, 'constants' ), 1 );

        // Internationalize the text strings used.
        add_action( 'plugins_loaded', array( &$this, 'i18n' ), 2 );

        // Load the functions files.
        add_action( 'plugins_loaded', array( &$this, 'includes' ), 3 );

        // Load the admin style.
        add_action( 'admin_enqueue_scripts', array( &$this, 'admin_enqueue_scripts' ) );

        // Register widget.
        add_action( 'widgets_init', array( &$this, 'register_widget' ) );

        // Load CSS
        add_action( 'wp_enqueue_scripts', array( &$this, 'enqueue_scripts' ) ); 

    }

    public function constants() {

        // Set constant path to the plugin directory.
        define( 'WPSW_DIR', trailingslashit( plugin_dir_path( __FILE__ ) ) );

        // Set the constant path to the plugin directory URI.
        define( 'WPSW_URI', trailingslashit( plugin_dir_url( __FILE__ ) ) );

        // Set the constant path to the includes directory.
        define( 'WPSW_INCLUDES', WPSW_DIR . trailingslashit( 'includes' ) );

        // Set the constant path to the includes directory.
        define( 'WPSW_CLASS', WPSW_DIR . trailingslashit( 'classes' ) );

        // Set the constant path to the assets directory.
        define( 'WPSW_CSS', WPSW_URI . trailingslashit( 'css' ) );

         // Set the constant path to the assets directory.
        define( 'WPSW_JS', WPSW_URI . trailingslashit( 'js' ) );
        
    }

    public function i18n() {

    }

    public function includes() {

    }

    public function admin_enqueue_scripts() {
        wp_enqueue_media();
    }

    public function enqueue_scripts()
    {
        wp_register_style('wp-slideshow-widget', WPSW_CSS . 'widget.css', array(), '1.0');
        wp_enqueue_style('wp-slideshow-widget');
    }

    public function register_widget() {
        require_once( WPSW_CLASS . 'widget.php' );
        register_widget( 'WP_Slideshow_Widget' );
    }

}

new wp_slideshow_widget_plugin;