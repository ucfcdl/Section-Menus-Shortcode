<?php
/**
 * The Sections Menu Shortcode
 **/
if ( ! class_exists( 'Section_Menus_Shortcode' ) ) {
    class Section_Menus_Shortcode {
        /**
         * Handles the registration of the shortcode
         * @author Jim Barnes
         * @since 1.0.0
         **/
        public static function register_shortcode() {
            add_shortcode( 'section-menu', array( 'Section_Menus_Shortcode', 'callback' ) );
        }

        /**
         * The `section-menu` shortcode callback
         * @author Jim Barnes
         * @since 1.0.0
         * @param $atts Array | The shortcode attributes
         * @return string | The shortcode output
         **/
        public static function callback( $atts ) {
            $atts = shortcode_atts( array(
                'selector' => '.auto-section',
                'layout'   => 'default'
            ), $atts );

            return Section_Menus_Common::display_section_menu( $atts['selector'], $atts['layout'] );
        }
    }
}
