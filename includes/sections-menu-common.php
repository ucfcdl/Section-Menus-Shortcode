<?php
/**
 * Handles various default layouts
 **/
if ( ! class_exists( 'Section_Menus_Common' ) ) {
    class Section_Menus_Common {
        public static function display_section_menu( $selector, $layout ) {
            $output = section_menus_display_default( $selector );

            if ( has_filter( 'section_menus_display_' . $layout ) ) {
                $output = apply_filters( 'section_menus_display_' . $layout, $selector );
            }

            return $output;
        }
    }
}

if ( ! function_exists( 'section_menus_display_default' ) ) {
    function section_menus_display_default( $selector ) {
        ob_start();
    ?>
        <nav id="sections-navbar" class="navbar navbar-gold center">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#sections-menu">
                        <span class="sr-only">Toggle sections navigation</span>
                        Menu <span class="fa fa-bars" aria-hidden="true"></span>
                    </button>
                </div>
                <div class="collapse navbar-collapse" id="sections-menu" data-selector="<?php echo $selector; ?>">
                    <ul class="nav navbar-nav">

                    </ul>
                </div>
            </div>
        </nav>
        <div class="navbar-bumper"></div>
    <?php
        return ob_get_clean();
    }
}
