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
			wp_enqueue_style( 'section-menu', plugins_url( 'static/css/section-menu.min.css', SECTION_MENUS__FILE ), null, false, 'screen' );
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
		<nav class="sections-menu-wrapper">
			<div class="navbar navbar-toggleable-md navbar-light bg-primary sections-menu">
				<div class="container">
					<button class="navbar-toggler collapsed ml-auto" type="button" data-toggle="collapse" data-target="#sections-menu" aria-controls="#sections-menu" aria-expanded="false" aria-label="Toggle navigation">
						<span class="navbar-toggler-text">Skip to Section</span>
						<span class="navbar-toggler-icon" aria-hidden="true"></span>
					</button>
					<div class="navbar-collapse collapse" id="sections-menu" data-selector="<?php echo $selector; ?>">
						<ul class="nav navbar-nav">

						</ul>
					</div>
				</div>
			</div>
		</nav>
	<?php
		return ob_get_clean();
	}
}
