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
		 * @param string $selector | The css selector to find menu items
		 * @param string $layout | The layout of the section menu to use
		 * @param string $content | The shortcode's inner content
		 * @return string | The section menu markup
		 **/
		public static function display_section_menu( $selector, $layout, $content='' ) {
			$output = section_menus_display_default( $selector, $content );

			if ( has_filter( 'section_menus_display_' . $layout ) ) {
				$output = apply_filters( 'section_menus_display_' . $layout, $selector, $content );
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

		/**
		 * Given a [section-menu] shortcode's enclosing contents, returns
		 * inner [section-menu-item]'s, ignoring other extraneous content.
		 *
		 * @author Jo Dickson
		 * @since 1.0.5
		 * @param string $content | Inner [section-menu] shortcode contents
		 * @return string | HTML markup for the section menu <ul>
		 */
		public static function get_custom_menu_items( $content ) {
			$retval = '';
			if ( preg_match_all( '/\[section-menu-item(.*?)\](.*?)\[\/section-menu-item\]/', $content, $items ) ) {
				$items  = array_key_exists( 0, $items ) ? $items[0] : array();
				$items  = implode( '', $items );
				$retval = do_shortcode( $items );
			}
			return $retval;
		}

		/**
		 * Returns HTML markup for a section menu's custom inner list item.
		 *
		 * @author Jo Dickson
		 * @since 1.0.5
		 * @param array $atts | Shortcode attributes
		 * @param string $content | Inner [section-menu-item] shortcode contents
		 * @return string | HTML markup for the list item
		 */
		public static function display_section_menu_item( $atts, $content='' ) {
			$output = section_menu_item_display_default( $atts, $content );

			if ( has_filter( 'section_menu_item_display_' . $atts['layout'] ) ) {
				$output = apply_filters( 'section_menu_item_display_' . $atts['layout'], $atts, $content );
			}

			return $output;
		}
	}
}

if ( ! function_exists( 'section_menus_display_default' ) ) {
	function section_menus_display_default( $selector, $content='' ) {
		$content     = trim( $content );
		$auto_select = $content ? 'false' : 'true';
		$list_items  = $content ? trim( Section_Menus_Common::get_custom_menu_items( $content ) ) : '';

		ob_start();
	?>
		<nav class="sections-menu-wrapper">
			<div class="navbar navbar-toggleable-md navbar-light bg-primary sections-menu">
				<div class="container">
					<button class="navbar-toggler collapsed ml-auto" type="button" data-toggle="collapse" data-target="#sections-menu" aria-controls="#sections-menu" aria-expanded="false" aria-label="Toggle navigation">
						<span class="navbar-toggler-text">Skip to Section</span>
						<span class="navbar-toggler-icon" aria-hidden="true"></span>
					</button>
					<div class="navbar-collapse collapse" id="sections-menu" data-autoselect="<?php echo $auto_select; ?>" data-selector="<?php echo $selector; ?>">
						<ul class="nav navbar-nav">
							<?php if ( $list_items ) { echo $list_items; } ?>
						</ul>
					</div>
				</div>
			</div>
		</nav>
	<?php
		return ob_get_clean();
	}
}

if ( ! function_exists( 'section_menu_item_display_default' ) ) {
	function section_menu_item_display_default( $atts, $content='' ) {
		ob_start();
		if ( $content && $atts['href'] ):
		?>
			<li class="<?php echo $atts['li_class']; ?>">
				<a class="<?php echo $atts['a_class']; ?>" rel="<?php echo $atts['rel']; ?>" href="<?php echo $atts['href']; ?>">
					<?php echo wptexturize( do_shortcode( $content ) ); ?>
				</a>
			</li>
		<?php
		endif;
		return ob_get_clean();
	}
}
