<?php
/**
 * Plugin Name: Ajax Load More: WooCommerce
 * Plugin URI: https://connekthq.com/plugins/ajax-load-more/add-ons/woocommerce/
 * Description: Ajax Load More addons for integrating WooCommerce.
 * Author: Darren Cooney
 * Twitter: @KaptonKaos
 * Author URI: http://connekthq.com
 * Copyright: Darren Cooney & Connekt Media
 * Version: 1.2.0
 * WC requires at least: 3.0
 * WC tested up to: 5.4.1
 *
 * @package ALMWooCommerce
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'ALM_WOO_VERSION', '1.2.0' );
define( 'ALM_WOO_RELEASE', 'July 8, 2021' );

/**
 * Plugin activation hook.
 *
 * @since 1.0
 */
function alm_woo_install() {
	if ( ! is_plugin_active( 'ajax-load-more/ajax-load-more.php' ) ) {
		set_transient( 'alm_woocommerce_admin_notice', true, 5 );
	}
	if ( ! alm_is_woo_activated() ) {
		wp_die( __( 'WooCommerce must be installed and activated in order to use Ajax Load More WooCommerce Add-on', 'alm-woocommerce' ) );
	}
}
register_activation_hook( __FILE__, 'alm_woo_install' );

/**
 * Is WooCommerce activated.
 *
 * @since 1.0
 */
function alm_is_woo_activated() {
	if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
		return true;
	} else {
		return false;
	}
}

/**
 * Display admin notice if plugin does not meet the requirements.
 *
 * @since 1.2
 */
function alm_woocommerce_admin_notice() {
	$slug   = 'ajax-load-more';
	$plugin = $slug . '-woocommerce';
	// Ajax Load More Notice.
	if ( get_transient( 'alm_woocommerce_admin_notice' ) ) {
		$install_url = get_admin_url() . '/update.php?action=install-plugin&plugin=' . $slug . '&_wpnonce=' . wp_create_nonce( 'install-plugin_' . $slug );
		$message     = '<div class="error">';
		$message    .= '<p>' . __( 'You must install and activate the core Ajax Load More plugin before using the Ajax Load More WooCommerce Add-on.', 'alm-woocommerce' ) . '</p>';
		$message    .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $install_url, __( 'Install Ajax Load More Now', 'alm-woocommerce' ) ) . '</p>';
		$message    .= '</div>';
		echo $message;
		// deactivate_plugins( '/' . $plugin . '/' . $plugin . '.php' );
		delete_transient( 'alm_woocommerce_admin_notice' );
	}
}
add_action( 'admin_notices', 'alm_woocommerce_admin_notice' );

if ( ! class_exists( 'ALMWooCommerce' ) ) :

	/**
	 * WooCommerce class.
	 */
	class ALMWooCommerce {

		function __construct() {

			define( 'ALM_WOO_PATH', plugin_dir_path( __FILE__ ) );
			define( 'ALM_WOO_URL', plugins_url( '', __FILE__ ) );
			define( 'ALM_WOO_PREFIX', 'alm_woo_' );

			add_action( 'alm_woocommerce_installed', array( &$this, 'alm_woocommerce_installed' ) );
			add_action( 'wp_enqueue_scripts', array( &$this, 'alm_woocommerce_enqueue_scripts' ) );
			add_filter( 'alm_woocommerce_shortcode', array( &$this, 'alm_woocommerce_shortcode' ), 10, 3 );
			add_action( 'woocommerce_before_shop_loop', array( &$this, 'alm_woocommerce_before_shop_loop' ) );
			add_action( 'woocommerce_after_shop_loop', array( &$this, 'alm_woocommerce_after_shop_loop' ) );
			add_action( 'alm_woocommerce_settings', array( &$this, 'alm_woocommerce_settings' ) );
			$this->includes();

			load_plugin_textdomain( 'alm-woocommerce', false, dirname( plugin_basename( __FILE__ ) ) . '/lang' ); // load text domain.

		}

		/**
		 * Load these files before the plugin loads.
		 *
		 * @since 1.0
		 */
		public function includes() {
			if ( alm_is_woo_activated() ) {
				require_once 'core/functions.php';
				require_once 'admin/customizer/customizer.php';
			}
		}

		/**
		 * Set up ALM shortcode and params
		 *
		 * @since 1.0
		 */
		public function alm_woocommerce_after_shop_loop() {

			if ( ! alm_is_woo_archive() ) { // WooCommerce Archive.
				return false;
			}

			if ( ! alm_woo_is_shop_enabled() ) { // Shop.
				return false;
			}

			if ( ! alm_woo_is_shop_archive_enabled() ) { // Shop Archives.
				return false;
			}

			if ( ! alm_woo_is_shop_search_enabled() ) { // Product Search.
				return false;
			}

			// Configuration.
			$woo_config = array(
				'post_type'         => 'product',
				'container_element' => 'div',
				'classes'           => 'stylefree',
				'columns'           => alm_woo_get_loop_prop( 'columns', '3' ),
				'per_page'          => alm_woo_get_loop_prop( 'per_page', 6 ),
			);

			/**
			 * WooCommerce hook to filter columns, per_page, classes etc
			 *
			 * @return $config;
			 */

			$woo_config = apply_filters( 'alm_woocommerce_config', $woo_config );
			$orderby    = apply_filters( 'alm_woocommerce_orderby', 'menu_order title' );

			// Defaults.
			$args = array(
				'id'             => 'alm_woocommerce',
				'woo'            => 'true',
				'post_type'      => $woo_config['post_type'],
				'posts_per_page' => $woo_config['per_page'],
				'pause'          => 'true',
				'order'          => 'ASC',
				'orderby'        => $orderby,
				'container_type' => $woo_config['container_element'],
				'css_classes'    => $woo_config['classes'],
			);

			// ALM Cache.
			if ( has_action( 'alm_cache_installed' ) && ! is_customize_preview() ) {
				// Main Shop.
				if ( is_shop() && ! is_product_category() && ! is_product_tag() ) {
					if ( alm_woo_is_shop_cache() ) {
						$args['cache']    = 'true';
						$args['cache_id'] = $this::alm_woocommerce_get_cache_id();
					}
				}
				// Archives.
				if ( is_product_category() || is_product_tag() ) {
					if ( alm_woo_is_shop_archive_cache() ) {
						$args['cache']    = 'true';
						$args['cache_id'] = $this::alm_woocommerce_get_cache_id();
					}
				}
			}

			// Loading Style.
			$loading_style         = ALMWooCustomizer::loading_style();
			$args['loading_style'] = ( get_option( ALM_WOO_PREFIX . 'button_style', $loading_style ) === 'default' ) ? 'default' : get_option( ALM_WOO_PREFIX . 'button_style', $loading_style );
			$is_infinite           = ( strpos( $args['loading_style'], 'infinite' ) !== false ) ? true : false;

			// Button Labels.
			$args['button_label'] = ( get_option( ALM_WOO_PREFIX . 'button_label' ) ) ? get_option( ALM_WOO_PREFIX . 'button_label' ) : ALMWooCustomizer::default_button_label();
			$loading_label        = ( get_option( ALM_WOO_PREFIX . 'button_loading_label' ) ) ? get_option( ALM_WOO_PREFIX . 'button_loading_label' ) : ALMWooCustomizer::default_button_loading_label();

			if ( $loading_label && ! $is_infinite ) {
				$args['button_loading_label'] = $loading_label;
			}

			// Scroll, Distance & Override.
			$scroll          = ( get_option( ALM_WOO_PREFIX . 'scroll', 'true' ) === 'false' ) ? 'false' : 'true';
			$scroll_override = ( get_option( ALM_WOO_PREFIX . 'scroll_override', 'true' ) === 'false' ) ? 'false' : 'true';
			$scroll_distance = ( get_option( ALM_WOO_PREFIX . 'scroll_distance' ) ) ? get_option( ALM_WOO_PREFIX . 'scroll_distance' ) : 100;

			if ( 'true' === $scroll || $is_infinite ) {
				// Scroll false OR loading infinite style.
				$args['scroll'] = 'true';

				// Pause Override.
				if ( 'true' === $scroll_override ) {
					$args['pause_override'] = 'true';
				} else {
					// If loading style is 'infinite'.
					if ( $is_infinite ) {
						$args['pause_override'] = 'true';
					}
				}

				// Scroll Distance.
				if ( $scroll_distance !== 100 ) {
					$args['scroll_distance'] = (int) $scroll_distance;
				}
			} else {
				$args['scroll'] = 'false';
			}

			// Core WooCommerce Hook.
			$args = apply_filters( 'alm_woocommerce_args', $args );

			// Render ALM.
			alm_render( $args );

		}

		/**
		 * Create a cache ID based on current page and querystrings.
		 *
		 * @since 1.1
		 */
		public static function alm_woocommerce_get_cache_id() {

			$cache_id = 'woo-shop'; // Default ID.

			if ( is_product_category() || is_product_tag() ) { // Shop Archives.
				$obj = get_queried_object();
				if ( isset( $obj->taxonomy ) && isset( $obj->slug ) ) {
					$taxonomy = $obj->taxonomy;
					$cache_id = 'woo-' . $obj->taxonomy . '-' . $obj->slug;
				}
			}

			// Get Querystring and parse into string.
			$qs = $_SERVER['QUERY_STRING'];
			if ( $qs ) {
				$qs       = str_replace( '=', '-', $qs );
				$qs       = str_replace( '&', '-', $qs );
				$cache_id = $cache_id . '--' . $qs;
			}

			return $cache_id;
		}

		/**
		 * Build WooCommerce shortcode
		 *
		 * @param string $id the ALM ID.
		 * @param array  $args Query args.
		 * @since 1.0.2
		 */
		public function alm_woocommerce_shortcode( $id, $args ) {

			if ( ! alm_is_woo_archive() ) { // Exit if not an archive page.
				return false;
			}

			$total_posts = wc_get_loop_prop( 'total' );
			ALM_LOCALIZE::add_localized_var( 'total_posts', $total_posts, $id );
			ALM_LOCALIZE::add_localized_var( 'post_count', 3, $id );

			// Create localized Paged URLs.
			$url_array      = array();
			$posts_per_page = $args['posts_per_page'];
			$pages          = ceil( $total_posts / $posts_per_page );

			// Create paged URLs.
			for ( $i = 1; $i <= $pages; $i++ ) {

				// Core WooCommerce Hook.
				$permalink_structure = apply_filters( 'alm_woocommerce_permalink_structure', get_option( ALM_WOO_PREFIX . 'permalink_structure' ) );

				if ( ! $permalink_structure ) {
					$url = htmlspecialchars_decode( get_pagenum_link( $i ) );
					$url = str_replace( '%5B', '[', $url );
					$url = str_replace( '%5D', ']', $url );

				} else {
					global $wp;
					$base_url = home_url( add_query_arg( array(), $wp->request ) );
					$url      = $base_url . str_replace( '{page}', $i, $permalink_structure );

				}
				array_push( $url_array, $url );
			}

			// Container class.
			$container_class = get_option( ALM_WOO_PREFIX . 'container' );

			// Product class.
			$products_class = get_option( ALM_WOO_PREFIX . 'products' );

			// Previous Products Button.
			$previous_products = get_option( ALM_WOO_PREFIX . 'previous_products' );
			$previous_products = apply_filters( 'alm_woocommerce_previous_products', $previous_products );

			$params = array(
				'container'  => isset( $container_class ) && ! empty( $container_class ) ? $container_class : apply_filters( 'alm_woocommerce_container', 'ul.products' ),
				'products'   => isset( $products_class ) && ! empty( $products_class ) ? $products_class : apply_filters( 'alm_woocommerce_products', '.product' ),
				'results'    => apply_filters( 'alm_woocommerce_results', '.woocommerce-result-count' ),
				'columns'    => alm_woo_get_loop_prop( 'columns', '3' ),
				'total'      => wc_get_loop_prop( 'total' ),
				'paged'      => wc_get_loop_prop( 'current_page' ),
				'pages'      => $pages,
				'paged_urls' => $url_array,
			);

			// Scrolltop and Controls.
			$scrolltop = get_option( ALM_WOO_PREFIX . 'scrolltop' ) ? get_option( ALM_WOO_PREFIX . 'scrolltop' ) : 50;
			$scrolltop = apply_filters( 'alm_woocommerce_scrolltop', $scrolltop );
			$controls  = get_option( ALM_WOO_PREFIX . 'controls' ) ? get_option( ALM_WOO_PREFIX . 'controls' ) : 'true';
			$controls  = apply_filters( 'alm_woocommerce_controls', $controls );

			$params['settings'] = array(
				'scrolltop'           => $scrolltop,
				'controls'            => $controls,
				'previous_products'   => $previous_products,
				'previous_page_link'  => wc_get_loop_prop( 'current_page' ) > 1 ? get_pagenum_link() : '',
				'previous_page_label' => apply_filters( 'alm_woocommerce_previous_link', __( 'Previous Products', 'alm-woocommerce' ) ),
				'previous_page_sep'   => apply_filters( 'alm_woocommerce_previous_link_sep', ' - ' ),
			);

			$data  = ' data-woo="true"';
			$data .= 'data-woo-settings="' . htmlspecialchars( wp_json_encode( $params ), ENT_QUOTES, 'UTF-8' ) . '"';

			return $data;
		}

		/**
		 * Fired before the shop loop.
		 *
		 * @since 1.0
		 */
		public function alm_woocommerce_before_shop_loop() {

			if ( ! alm_is_woo_archive() ) { // Exit if not WooCommerce archive page.
				return false;
			}

			if ( ! alm_woo_is_shop_enabled() ) { // Shop.
				return false;
			}

			if ( ! alm_woo_is_shop_archive_enabled() ) { // Shop Archives.
				return false;
			}

			$hide_pagination = alm_woo_hide_pagination();
			$hide_orderby    = alm_woo_hide_orderby();

			$return  = '<style>';
			$return .= $hide_pagination; // Hide Pagination.
			$return .= $hide_orderby; // Hide Orderby (If set).
			$return .= '</style>';

			echo $return;
		}

		/**
		 * An empty function to determine if add-on is activated.
		 *
		 * @since 1.0
		 */
		public function alm_woocommerce_installed() {
			// Empty.
		}

		/**
		 * Enqueue scripts.
		 *
		 * @since 1.0
		 */
		public function alm_woocommerce_enqueue_scripts() {

			// Use minified libraries if SCRIPT_DEBUG is turned off.
			$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

			// Enqueue JS.
			wp_register_script( 'ajax-load-more-woocommerce', plugins_url( '/core/js/alm-woocommerce' . $suffix . '.js', __FILE__ ), array( 'ajax-load-more' ), ALM_WOO_VERSION, true );

		}

		/**
		 * Create the WooCommerce settings panel.
		 *
		 * @since 1.0
		 */
		public function alm_woocommerce_settings() {
			register_setting(
				'alm_woocommerce_license',
				'alm_woocommerce_license_key',
				'alm_woocommerce_sanitize_license'
			);
		}
	}

	/**
	 *  Sanitize the license activation
	 *
	 *  @since 1.0.1
	 */
	function alm_woocommerce_sanitize_license( $new ) {
		$old = get_option( 'alm_woocommerce_license_key' );
		if ( $old && $old != $new ) {
			delete_option( 'alm_woocommerce_license_status' );
		}
		return $new;
	}

	/**
	 * The main function starter.
	 *
	 * @since 1.0
	 */
	function ALMWooCommerce() {
		global $ALMWooCommerce;
		if ( ! isset( $ALMWooCommerce ) ) {
			$ALMWooCommerce = new ALMWooCommerce();
		}
		return $ALMWooCommerce;
	}

	// initialize.
	ALMWooCommerce();

endif;

/**
 * Software Licensing.
 *
 * @since 1.0
 */
function alm_woocommerce_plugin_updater() {
	if ( ! has_action( 'alm_pro_installed' ) && class_exists( 'EDD_SL_Plugin_Updater' ) ) {
		$license_key = trim( get_option( 'alm_woocommerce_license_key' ) ); // license key from the DB.
		$edd_updater = new EDD_SL_Plugin_Updater(
			ALM_STORE_URL,
			__FILE__,
			array(
				'version' => ALM_WOO_VERSION,
				'license' => $license_key,
				'item_id' => ALM_WOO_ITEM_NAME,
				'author'  => 'Darren Cooney',
			)
		);
	}
}
add_action( 'admin_init', 'alm_woocommerce_plugin_updater', 0 );
