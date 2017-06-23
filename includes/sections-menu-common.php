<?php
/**
 * Handles various default layouts
 **/
if ( ! class_exists( 'Section_Menus_Common' ) ) {
	class Section_Menus_Common {

		/**
		 * Primary function for displaying section menu
		 * @author Jim Barnes
		 * @since 1.0.0
		 * @param $selector string | The css selector to find menu items
		 * @param layout string | The layout of the section menu to use
		 * @return string | The section menu markup
		 **/
		public static function display_section_menu( $selector, $layout ) {
			$output = section_menus_display_default( $selector );

			if ( has_filter( 'section_menus_display_' . $layout ) ) {
				$output = apply_filters( 'section_menus_display_' . $layout, $selector );
			}

			return $output;
		}

		/**
		 * Enqueues the frontend assets
		 * @author Jim Barnes
		 * @since 1.0.0
		 **/
		public static function enqueue_assets() {
			wp_enqueue_script( 'section-menu-js', SECTION_MENUS__SCRIPT_URL . 'section-menu.min.js', array( 'jquery', 'script' ), null, true );
		}

		/**
		 * Replaces paragraph tags around the section shortcode
		 * @author Jim Barnes
		 * @since 1.0.0
		 * @param $content string | The content being filtered
		 * @return string | The formatted content
		 **/
		public static function format_shortcode_output( $content ) {
			$block = 'section-menu';

			$rep = preg_replace( "/(<p>)?\[($block)(\s[^\]]+)?\](<\/p>|<br \/>)?/", "[$2$3]", $content );
			$rep = preg_replace( "/(<p>)?\[\/($block)](<\/p>|<br \/>)?/", "[/$2]", $rep );
			return $rep;
		}
	}
}

if ( ! function_exists( 'section_menus_display_default' ) ) {
	function section_menus_display_default( $selector ) {
		ob_start();
	?>
		<nav class="navbar navbar-toggleable-md navbar-light bg-primary sections-menu">
			<div class="container">
				<div class="navbar-header">
					<button type="button" class="navbar-toggler navbar-toggler-right" data-toggle="collapse" data-target="#sections-menu" aria-controls="#sections-menu" aria-expanded="false">
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
