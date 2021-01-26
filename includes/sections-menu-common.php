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
			self::enqueue_scripts();
			$output = section_menus_display_default( $selector, $content );

			if ( has_filter( 'section_menus_display_' . $layout ) ) {
				$output = apply_filters( 'section_menus_display_' . $layout, $selector, $content );
			}

			return $output;
		}

		/**
		 * Registers frontend plugin assets
		 * @since 1.1.3
		 * @author Jo Dickson
		 * @return void
		 */
		public static function register_assets() {
			$plugin_data = get_plugin_data( SECTION_MENUS__PLUGIN_FILE, false, false );
			$version     = $plugin_data['Version'];

			wp_register_script( 'section-menu-js', SECTION_MENUS__SCRIPT_URL . '/section-menu.min.js', array( 'jquery', 'script' ), $version, true );
			wp_register_style( 'section-menu', SECTION_MENUS__STYLES_URL . '/section-menu.min.css', null, $version, 'screen' );
		}

		/**
		 * Enqueues frontend plugin styles
		 * @since 1.1.3
		 * @author Jo Dickson
		 * @return void
		 */
		public static function enqueue_styles() {
			wp_enqueue_style( 'section-menu' );
		}

		/**
		 * Enqueues frontend plugin scripts
		 * @author Jo Dickson
		 * @since 1.1.3
		 **/
		public static function enqueue_scripts() {
			wp_enqueue_script( 'section-menu-js' );
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
		 * @since 1.1.0
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
		 * @since 1.1.0
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

		/**
		 * Applies content filtering overrides to prevent expected
		 * data attributes from being stripped by WordPress KSES filtering
		 * prior to WP 5.0.
		 *
		 * @author Jo Dickson
		 * @since 1.1.1
		 * @param array $tags Global $allowedposttags array
		 * @param mixed $context Context for which to retrieve tags
		 * @return array Modified post tag array
		 */
		public static function kses_valid_attributes( $tags, $context ) {
			if ( $context === 'post' ) {
				// Tags on which our custom data attributes should be valid.
				$data_attr_tags = array(
					'div',
					'section',
					'aside',
					'article'
				);

				foreach ( $data_attr_tags as $t ) {
					if ( ! isset( $tags[$t]['data-*'] ) ) {
						$existing_rules = isset( $tags[$t] ) ? $tags[$t] : array();
						$tags[$t] = array_merge( $existing_rules, array(
							'data-section-link-title' => true
						) );
					}
				}
			}
			return $tags;
		}
	}
}

if ( ! function_exists( 'section_menus_display_default' ) ) {
	function section_menus_display_default( $selector, $content='' ) {
		$content     = trim( $content );
		$list_items  = $content ? trim( Section_Menus_Common::get_custom_menu_items( $content ) ) : '';
		$auto_select = $list_items ? 'false' : 'true';

		ob_start();
	?>
		<nav class="sections-menu-wrapper" aria-label="Page section navigation">
			<div class="navbar navbar-toggleable-md navbar-light bg-primary sections-menu">
				<div class="container">
					<button class="navbar-toggler collapsed ml-auto" type="button" data-toggle="collapse" data-target="#sections-menu" aria-controls="#sections-menu" aria-expanded="false">
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
			$target = $atts['new_window'] ? '_blank' : '';
		?>
			<li class="<?php echo $atts['li_class']; ?>">
				<a class="<?php echo $atts['a_class']; ?>" rel="<?php echo $atts['rel']; ?>" href="<?php echo $atts['href']; ?>" target="<?php echo $target; ?>">
					<?php echo wptexturize( do_shortcode( $content ) ); ?>
				</a>
			</li>
		<?php
		endif;
		return ob_get_clean();
	}
}
