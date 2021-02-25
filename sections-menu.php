<?php
/*
Plugin Name: Automatic Section Menu Shortcodes
Description: Provides shortcodes for generating a sticky menu on a page, populated automatically based on sections on the page or manually with custom links.
Author: UCF Web Communications
Version: 1.1.3
License: GPL3
GitHub Plugin URI: UCF/Section-Menus-Shortcode
*/
if ( ! defined( 'WPINC' ) ) {
    die;
}

define( 'SECTION_MENUS__PLUGIN_FILE', __FILE__ );
define( 'SECTION_MENUS__PLUGIN_URL', plugins_url( basename( dirname( __FILE__ ) ) ) );
define( 'SECTION_MENUS__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'SECTION_MENUS__STATIC_URL', SECTION_MENUS__PLUGIN_URL . '/static' );
define( 'SECTION_MENUS__SCRIPT_URL', SECTION_MENUS__STATIC_URL . '/js' );
define( 'SECTION_MENUS__STYLES_URL', SECTION_MENUS__STATIC_URL . '/css' );

include_once SECTION_MENUS__PLUGIN_DIR . 'includes/sections-menu-common.php';
include_once SECTION_MENUS__PLUGIN_DIR . 'includes/sections-menu-shortcodes.php';

if ( ! function_exists( 'section_menus_activation' ) ) {
    function section_menus_activation() {

    }

    register_activation_hook( 'section_menus_activation', SECTION_MENUS__PLUGIN_FILE );
}

if ( ! function_exists( 'section_menus_deactivation' ) ) {
    function section_menus_deactivation() {

    }

    register_deactivation_hook( 'section_menus_deactivation', SECTION_MENUS__PLUGIN_FILE );
}

add_action( 'init', array( 'Section_Menus_Shortcode', 'register_shortcode' ), 10, 0 );
add_action( 'init', array( 'Section_Menu_Items_Shortcode', 'register_shortcode' ), 10, 0 );
add_action( 'wp_enqueue_scripts', array( 'Section_Menus_Common', 'register_assets' ), 10, 0 );
add_action( 'wp_enqueue_scripts', array( 'Section_Menus_Common', 'enqueue_styles' ), 11, 0 );

add_filter( 'the_content', array( 'Section_Menus_Common', 'format_shortcode_output' ), 10, 1 );

// Enable necessary data attributes on elements that would otherwise
// be filtered by WordPress's KSES filters:
add_filter( 'wp_kses_allowed_html', array( 'Section_Menus_Common', 'kses_valid_attributes' ), 10, 2 );
