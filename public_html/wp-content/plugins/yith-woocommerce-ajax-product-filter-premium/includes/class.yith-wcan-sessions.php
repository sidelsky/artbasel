<?php
/**
 * Manage sessions
 *
 * @author  YITH
 * @package YITH WooCommerce Ajax Product FIlter
 * @version 4.0.0
 */

if ( ! defined( 'YITH_WCAN' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'YITH_WCAN_Sessions' ) ) {
	/**
	 * Filter Presets Handling
	 *
	 * @since 1.0.0
	 */
	class YITH_WCAN_Sessions {

		/**
		 * Single instance of this class
		 *
		 * @var YITH_WCAN_Sessions
		 */
		protected static $_instance;

		/**
		 * Constructor method for this class
		 *
		 * @return void
		 * @author Antonio La Rocca <antonio.larocca@yithemes.com>
		 */
		public function __construct() {
			// register data store.
			add_filter( 'woocommerce_data_stores', array( $this, 'register_data_stores' ) );
		}

		/**
		 * Register preset Data Store in the list of available data stores
		 *
		 * @param array $data_stores Array of available data stores.
		 *
		 * @return array Filtered array of data stores.
		 * @author Antonio La Rocca <antonio.larocca@yithemes.com>
		 */
		public function register_data_stores( $data_stores ) {
			$data_stores['filter_session'] = 'YITH_WCAN_Session_Data_Store';

			return $data_stores;
		}

		/* === UTILS METHODS === */

		/**
		 * Deletes all defined sessions
		 *
		 * @return void
		 */
		public function delete_all() {
			try {
				WC_Data_Store::load( 'filter_session' )->delete_all();
			} catch ( Exception $e ) {
				return;
			}
		}

		/**
		 * Return single instance for this class
		 *
		 * @return YITH_WCAN_Sessions
		 * @author Antonio La Rocca <antonio.larocca@yithemes.com>
		 */
		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}

			return self::$_instance;
		}
	}
}

if ( ! function_exists( 'YITH_WCAN_Sessions' ) ) {
	/**
	 * Return single instance for YITH_WCAN_Sessions class
	 *
	 * @return YITH_WCAN_Sessions
	 * @since 4.0.0
	 * @author Antonio La Rocca <antonio.larocca@yithemes.com>
	 */
	function YITH_WCAN_Sessions() {
		return YITH_WCAN_Sessions::instance();
	}
}
