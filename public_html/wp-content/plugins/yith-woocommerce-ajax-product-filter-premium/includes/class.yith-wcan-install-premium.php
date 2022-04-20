<?php
/**
 * Manage install, and performs all post update operations
 *
 * @author  YITH
 * @package YITH WooCommerce Ajax Product FIlter
 * @version 4.0.0
 */

if ( ! defined( 'YITH_WCAN' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'YITH_WCAN_Install_Premium' ) ) {
	/**
	 * Filter Presets Handling
	 *
	 * @since 4.0.0
	 */
	class YITH_WCAN_Install_Premium extends YITH_WCAN_Install {

		/**
		 * Name of Filters Lookup table
		 *
		 * @var string
		 */
		public static $filter_sessions;

		/**
		 * Stored DB version
		 *
		 * @var string
		 */
		protected static $_stored_db_version;

		/**
		 * Hooks methods required to install/update plugin
		 *
		 * @return void
		 */
		public static function init() {
			global $wpdb;

			// initialize db tables.
			self::$filter_sessions = "{$wpdb->prefix}yith_wcan_filter_sessions";
			$wpdb->filter_sessions = self::$filter_sessions;

			parent::init();

			add_action( 'init', array( __CLASS__, 'check_db_version' ), 5 );
			add_action( 'init', array( __CLASS__, 'install_endpoints' ), 15 );

			// premium specific upgrades.
			add_action( 'yith_wcan_did_410_upgrade', array( __CLASS__, 'maybe_update_filters' ) );
		}

		/**
		 * Check current version, and trigger db update procedures when needed
		 *
		 * @return void
		 */
		public static function check_db_version() {
			self::$_stored_db_version = get_option( 'yith_wcan_db_version' );

			if ( version_compare( self::$_stored_db_version, YITH_WCAN_DB_VERSION, '<' ) ) {
				self::update_db();
				do_action( 'yith_wcan_db_updated' );
			}
		}

		/**
		 * Install required endpoints
		 *
		 * @return void
		 */
		public static function install_endpoints() {
			$session_param = YITH_WCAN_Session_Factory::get_session_query_param();

			add_rewrite_endpoint( $session_param, apply_filters( 'yith_wcan_session_endpoint_places_bitmap', EP_PERMALINK | EP_PAGES | EP_CATEGORIES ) );

			/**
			 * Hot fix for static front pages.
			 * EP_ROOT bitmask for endpoints seems not to work well with static homepages; even if request is correctly
			 * parser, page_id isn't added to query_vars, with the result of defaulting to post archives.
			 *
			 * For this very reason we use a specific rewrite rule for static homepages, containing filter_session param
			 */
			$front_page = get_option( 'page_on_front' );

			if ( $front_page ) {
				add_rewrite_rule( "^$session_param(/(.*))?/?$", "index.php?page_id=$front_page&$session_param=\$matches[2]", 'top' );
			}

			/**
			 * Hot fix for shop page, working as product archive page.
			 * Endpoint would work for the shop page, but if you add it to the url, ^shop/?$ rewrite rule, automagically
			 * added to manage products archive, won't match any longer
			 *
			 * For this very reason we use a specific rewrite rule for shop products archive.
			 */
			$shop_page_id = wc_get_page_id( 'shop' );

			if ( current_theme_supports( 'woocommerce' ) ) {
				$has_archive = $shop_page_id && get_post( $shop_page_id ) ? urldecode( get_page_uri( $shop_page_id ) ) : 'shop';

				add_rewrite_rule( "^$has_archive/$session_param(/(.*))?/?$", "index.php?post_type=product&$session_param=\$matches[2]", 'top' );
			}

			/**
			 * Hot fix for product taxonomies.
			 * Even if we use EP_ALL when adding endpoint, system won't register it for custom taxonomies that were created
			 * using EP_NONE as ep_mask; unfortunately this is default value, and our endpoint wouldn't be registered
			 * for any of them
			 *
			 * Refer to https://core.trac.wordpress.org/ticket/33728 for further information on the problem.
			 */
			$taxonomies = YITH_WCAN_Query()->get_supported_taxonomies();

			foreach ( $taxonomies as $tax_id => $tax ) {
				if ( ! $tax->public ) {
					continue;
				}

				$rewrite_slug = $tax->rewrite['slug'];

				add_rewrite_rule( "$rewrite_slug/(.+?)/$session_param(/(.*))?/?$", "index.php?$tax_id=\$matches[1]&$session_param=\$matches[3]", 'top' );

			}
		}

		/**
		 * DB update/install procedure
		 *
		 * @return void
		 */
		public static function update_db() {
			self::maybe_update_tables();
			self::update_db_version();
		}

		/**
		 * Create or update tables for the plugin
		 *
		 * The dbDelta function will require correct operation depending on current DB structure.
		 *
		 * @return void
		 */
		public static function maybe_update_tables() {
			global $wpdb;

			$table   = self::$filter_sessions;
			$collate = '';

			if ( $wpdb->has_cap( 'collation' ) ) {
				$collate = $wpdb->get_charset_collate();
			}

			$sql = "CREATE TABLE {$table} (
							ID BIGINT( 20 ) NOT NULL AUTO_INCREMENT,
							hash CHAR( 32 ) NOT NULL,
							token CHAR( 10 ) NOT NULL,
							origin_url TEXT NOT NULL,
							query_vars TEXT NOT NULL,
							expiration timestamp NULL DEFAULT NULL,
							PRIMARY KEY  ( ID ),
							UNIQUE KEY filter_hash ( hash ),
							UNIQUE KEY filter_token ( token ),
							KEY filter_expiration ( expiration )
						) $collate;";

			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );
		}

		/**
		 * Updated version option to latest db version, to avoid executing upgrade multiple times
		 *
		 * @return void
		 */
		public static function update_db_version() {
			update_option( 'yith_wcan_db_version', YITH_WCAN_DB_VERSION );
		}

		/**
		 * Update review filters, to set a default design
		 *
		 * @return void.
		 */
		public static function maybe_update_filters() {
			$presets = YITH_WCAN_Preset_Factory::get_presets();

			foreach ( $presets as $preset ) {
				if ( ! $preset->has_filters() ) {
					continue;
				}

				$fuzzy = false;
				$filters_to_save = array();

				foreach ( $preset->get_filters() as $filter_id => $filter ) {
					$type = $filter->get_type();

					if ( 'review' === $type ) {
						$filter->set_filter_design( 'select' );
						$fuzzy = true;
					} elseif ( 'price_range' === $type ) {
						$filter->set_filter_design( 'text' );
						$fuzzy = true;
					} elseif ( 'tax' === $type && 'term_order' === $filter->get_order_by() ) {
						$filter->set_order_by( 'include' );
						$fuzzy = true;
					}

					$filters_to_save[] = $filter->get_data();
				}

				if ( ! $fuzzy ) {
					continue;
				}

				$preset->set_filters( $filters_to_save );
				$preset->save();
			}
		}

		/**
		 * Generates default filters for the preset created on first installation of the plugin
		 *
		 * @return array Array of filters.
		 */
		protected static function _get_default_filters() {
			$filters = parent::_get_default_filters();

			// set additional filters.
			$filters[] = self::_get_price_filter();
			$filters[] = self::_get_review_filter();
			$filters[] = self::_get_sale_stock_filter();
			$filters[] = self::_get_orederby_filter();

			return apply_filters( 'yith_wcan_default_filters', $filters );
		}

		/**
		 * Generates default Price filter for the preset created on first installation of the plugin
		 *
		 * @return array Filter options.
		 */
		protected static function _get_price_filter() {
			global $wpdb;

			$filter = new YITH_WCAN_Filter_Price_Slider();

			// lookup for max product price.
			$max_price = $wpdb->get_var( "SELECT MAX(max_price) FROM {$wpdb->prefix}wc_product_meta_lookup" );
			$step      = max( (int) $max_price / 10, 1 );

			$filter->set_title( _x( 'Filter by price', '[ADMIN] Name of default price filter created by plugin', 'yith-woocommerce-ajax-navigation' ) );
			$filter->set_show_toggle( 'no' );
			$filter->set_price_slider_min( 0 );
			$filter->set_price_slider_max( ceil( $max_price ) );
			$filter->set_price_slider_step( floor( $step ) );

			return $filter->get_data();
		}

		/**
		 * Generates default Review filter for the preset created on first installation of the plugin
		 *
		 * @return array Filter options.
		 */
		protected static function _get_review_filter() {
			$filter = new YITH_WCAN_Filter_Review();

			$filter->set_title( _x( 'Filter by review', '[ADMIN] Name of default review filter created by plugin', 'yith-woocommerce-ajax-navigation' ) );
			$filter->set_show_toggle( 'no' );
			$filter->set_show_count( 'no' );
			$filter->set_adoptive( 'hide' );

			return $filter->get_data();
		}

		/**
		 * Generates default Stock/Sale filter for the preset created on first installation of the plugin
		 *
		 * @return array Filter options.
		 */
		protected static function _get_sale_stock_filter() {
			$filter = new YITH_WCAN_Filter_Stock_Sale();

			$filter->set_title( _x( 'Additional filters', '[ADMIN] Name of default stock/sale filter created by plugin', 'yith-woocommerce-ajax-navigation' ) );
			$filter->set_show_toggle( 'no' );
			$filter->set_show_count( 'no' );
			$filter->set_adoptive( 'hide' );

			return $filter->get_data();
		}

		/**
		 * Generates default Orderby filter for the preset created on first installation of the plugin
		 *
		 * @return array Filter options.
		 */
		protected static function _get_orederby_filter() {
			$filter = new YITH_WCAN_Filter_Orderby();

			$filter->set_title( _x( 'Order by', '[ADMIN] Name of default order by filter created by plugin', 'yith-woocommerce-ajax-navigation' ) );
			$filter->set_show_toggle( 'no' );
			$filter->set_order_options( array_keys( YITH_WCAN_Filter_Factory::get_supported_orders() ) );

			return $filter->get_data();
		}

	}
}
