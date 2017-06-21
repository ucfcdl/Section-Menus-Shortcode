<?php
/*
Plugin Name: Automatic Section Menu Shortcode
Description: Provides a shortcode that auto generates a menu for each section of a page based on a common css class selector
Author: UCF Web Communications
Version: 1.0.0
License: GPL3
*/
if ( ! defined( 'WPINC' ) ) {
    die;
}

define( 'SECTION_MENUS__FILE', __FILE__ );
define( 'SECTION_MENUS__STATIC_URL', plugins_url( 'static' ), SECTION_MENUS__FILE );
define( 'SECTION_MENUS__SCRIPT_URL', SECTION_MENUS__STATIC_URL . 'js' );
define( 'SECTION_MENUS__STYLES_URL', SECTION_MENUS__STATIC_URL . 'css' );

include_once 'includes/sections-menu-common.php';
include_once 'includes/sections-menu-shortcode.php';

if ( ! function_exists( 'section_menus_activation' ) ) {
    function section_menus_activation() {

    }

    register_activation_hook( 'section_menus_activation', SECTION_MENUS__FILE );
}

if ( ! function_exists( 'section_menus_deactivation' ) ) {
    function section_menus_deactivation() {

    }

    register_deactivation_hook( 'section_menus_deactivation', SECTION_MENUS__FILE );
}

add_action( 'init', array( 'Section_Menus_Shortcode', 'register_shortcode' ), 10, 0 );
