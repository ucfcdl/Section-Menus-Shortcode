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
        public static function callback( $atts, $content='' ) {
            $atts = shortcode_atts( array(
                'selector' => '.auto-section',
                'layout'   => 'default'
            ), $atts );

            return Section_Menus_Common::display_section_menu( $atts['selector'], $atts['layout'], $content );
        }
    }
}


/**
 * Shortcode for inner sections menu items
 **/
if ( ! class_exists( 'Section_Menu_Items_Shortcode' ) ) {
    class Section_Menu_Items_Shortcode {
        /**
         * Handles the registration of the shortcode
         * @author Jo Dickson
         * @since 1.0.5
         **/
        public static function register_shortcode() {
            add_shortcode( 'section-menu-item', array( 'Section_Menu_Items_Shortcode', 'callback' ) );
        }

        /**
         * The `section-menu-item` shortcode callback
         * @author Jo Dickson
         * @since 1.0.5
         * @param array $atts | The shortcode attributes
         * @return string | The shortcode output
         **/
        public static function callback( $atts, $content='' ) {
            $atts = shortcode_atts( array(
				'li_class' => 'nav-item',
				'a_class'  => 'section-link nav-link',
				'href'     => '',
				'rel'      => '',
				'layout'   => 'default'
			), $atts );

			$atts['li_class'] = esc_attr( $atts['li_class'] );
			$atts['a_class']  = esc_attr( $atts['a_class'] );
			$atts['href']     = esc_url( $atts['href'] );
			$atts['rel']      = esc_attr( $atts['rel'] );

            return Section_Menus_Common::display_section_menu_item( $atts, $content );
        }
    }
}
