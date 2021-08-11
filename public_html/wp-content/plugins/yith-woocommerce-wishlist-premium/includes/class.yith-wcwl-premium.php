<?php
/**
 * Init premium features of the plugin
 *
 * @author Your Inspiration Themes
 * @package YITH WooCommerce Wishlist
 * @version 3.0.0
 */

if ( ! defined( 'YITH_WCWL' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'YITH_WCWL_Premium' ) ) {
	/**
	 * WooCommerce Wishlist Premium
	 *
	 * @since 1.0.0
	 */
	class YITH_WCWL_Premium extends YITH_WCWL {

		/**
		 * Single instance of the class
		 *
		 * @var \YITH_WCWL_Premium
		 * @since 2.0.0
		 */
		protected static $instance;

		/**
		 * Returns single instance of the class
		 *
		 * @return \YITH_WCWL_Premium
		 * @since 2.0.0
		 */
		public static function get_instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Constructor.
		 *
		 * @since 1.0.0
		 */
		public function __construct() {
			parent::__construct();

			// register plugin to licence/update system.
			add_action( 'wp_loaded', array( $this, 'register_plugin_for_activation' ), 99 );
			add_action( 'admin_init', array( $this, 'register_plugin_for_updates' ) );

			// emails handling.
			add_filter( 'woocommerce_email_classes', array( $this, 'add_woocommerce_emails' ) );
			add_action( 'woocommerce_init', array( $this, 'load_wc_mailer' ) );
			add_filter( 'woocommerce_locate_core_template', array( $this, 'filter_woocommerce_template' ), 10, 3 );
			add_filter( 'woocommerce_locate_template', array( $this, 'filter_woocommerce_template' ), 10, 3 );

			// back in stock handling.
			add_action( 'woocommerce_product_set_stock_status', array( $this, 'schedule_back_in_stock_emails' ), 10, 3 );
			add_action( 'woocommerce_variation_set_stock_status', array( $this, 'schedule_back_in_stock_emails' ), 10, 3 );

			// on sale item handling.
			add_action( 'yith_wcwl_item_is_on_sale', array( $this, 'schedule_on_sale_item_emails' ), 10, 1 );
		}

		/* === ITEM METHODS === */

		/**
		 * Retrieve first list of current user where a specific product occurs; if no wishlist is found, returns false
		 *
		 * @param int $product_id Product id.
		 * @return \YITH_WCWL_Wishlist|bool First wishlist found where the product occurs (system will privilege default lists)
		 */
		public function get_wishlist_for_product( $product_id ) {
			$items = YITH_WCWL_Wishlist_Factory::get_wishlist_items(
				array(
					'product_id' => $product_id,
					'wishlist_id' => 'all',
					'limit' => 1,
					'orderby' => 'dateadded',
					'order' => 'DESC',
				)
			);

			if ( ! $items ) {
				return false;
			}

			$item = array_shift( $items );

			return apply_filters( 'yith_wcwl_wishlist_for_product', $item->get_wishlist(), $product_id );
		}

		/* === WISHLIST METHODS === */

		/**
		 * Add a new wishlist for the user.
		 *
		 * @param array $atts Add to Wishlist info:
		 * [
		 *   'wishlist_name'
		 *   'wishlist_visibility'
		 *   'user_id'
		 *   'session_id'
		 * ].
		 *
		 * @return YITH_WCWL_Wishlist
		 * @throws YITH_WCWL_Exception When something goes wrong with creation.
		 * @since 3.0.0
		 */
		public function add_wishlist( $atts = array() ) {
			$defaults = array(
				'wishlist_name' => false,
				'wishlist_visibility' => 0,
				'user_id' => false,
				'session_id' => false,
			);

			$atts = empty( $atts ) && ! empty( $this->details ) ? $this->details : $atts;
			$atts = ! empty( $atts ) ? $atts : $_REQUEST;
			$atts = wp_parse_args( $atts, $defaults );

			// filtering params.
			$wishlist_name = apply_filters( 'yith_wcwl_adding_to_wishlist_wishlist_name', $atts['wishlist_name'] );
			$wishlist_visibility = apply_filters( 'yith_wcwl_adding_to_wishlist_wishlist_visibility', in_array( $atts['wishlist_visibility'], array( 0, 1, 2 ) ) ? $atts['wishlist_visibility'] : 0 );
			$user_id = apply_filters( 'yith_wcwl_adding_to_wishlist_user_id', intval( $atts['user_id'] ) );
			$session_id = apply_filters( 'yith_wcwl_adding_to_wishlist_user_id', $atts['session_id'] );

			if ( ! $wishlist_name ) {
				throw new YITH_WCWL_Exception( __( 'Wishlist name is required', 'yith-woocommerce-wishlist' ), 0 );
			} elseif ( strlen( $wishlist_name ) >= 65535 ) {
				throw new YITH_WCWL_Exception( __( 'Wishlist name exceeds the maximum number of characters allowed', 'yith-woocommerce-wishlist' ), 0 );
			}

			$wishlist_name = sanitize_text_field( $wishlist_name );
			$wishlist_slug = sanitize_title_with_dashes( $wishlist_name );
			$session_id = sanitize_title_with_dashes( $session_id );

			$new_wishlist = new YITH_WCWL_Wishlist();

			// set properties before saving.
			$new_wishlist->set_slug( $wishlist_slug );
			$new_wishlist->set_name( $wishlist_name );
			$new_wishlist->set_privacy( $wishlist_visibility );
			$new_wishlist->set_user_id( $user_id );
			$new_wishlist->set_session_id( $session_id );

			$new_wishlist->save();

			do_action( 'yith_wcwl_after_add_wishlist', $new_wishlist );

			return $new_wishlist;
		}

		/**
		 * Update wishlist with arguments passed as second parameter
		 *
		 * @param int   $wishlist_id Wishlist id.
		 * @param array $args Array of parameters to use for update query.
		 * @return void
		 * @throws YITH_WCWL_Exception When something goes wrong with update.
		 * @since 2.0.0
		 */
		public function update_wishlist( $wishlist_id, $args = array() ) {
			$wishlist = YITH_WCWL_Wishlist_Factory::get_wishlist( $wishlist_id );

			if ( ! $wishlist ) {
				throw new YITH_WCWL_Exception( __( 'Couldn\'t find any wishlist with the provided ID', 'yith-woocommerce-wishlist' ), 0 );
			}

			if ( ! $wishlist->current_user_can( 'update_wishlist' ) ) {
				throw new YITH_WCWL_Exception( __( 'There was an error while processing your request; please, try later', 'yith-woocommerce-wishlist' ), 0 ); // @since 3.0.7
			}

			if ( isset( $args['wishlist_name'] ) ) {
				$wishlist_name = $args['wishlist_name'];

				if ( ! $wishlist_name ) {
					throw new YITH_WCWL_Exception( __( 'Wishlist name is required', 'yith-woocommerce-wishlist' ), 0 );
				} elseif ( strlen( $wishlist_name ) >= 65535 ) {
					throw new YITH_WCWL_Exception( __( 'Wishlist name exceeds the maximum number of characters allowed', 'yith-woocommerce-wishlist' ), 0 );
				}

				$wishlist->set_name( $args['wishlist_name'] );
			}

			if ( isset( $args['wishlist_visibility'] ) || isset( $args['wishlist_privacy'] ) ) {
				$wishlist_visibility = isset( $args['wishlist_visibility'] ) ? $args['wishlist_visibility'] : $args['wishlist_privacy'];
				$wishlist_visibility = in_array( $wishlist_visibility, array( 0, 1, 2 ) ) ? $wishlist_visibility : 0;
				$wishlist->set_privacy( $wishlist_visibility );
			}

			$wishlist->save();
		}

		/**
		 * Delete indicated wishlist
		 *
		 * @param int $wishlist_id Wishlist id.
		 * @throws YITH_WCWL_Exception When something goes wrong with deletion.
		 * @return void
		 * @since 3.0.0
		 */
		public function remove_wishlist( $wishlist_id ) {
			$wishlist = YITH_WCWL_Wishlist_Factory::get_wishlist( $wishlist_id );

			if ( ! $wishlist ) {
				throw new YITH_WCWL_Exception( __( 'Couldn\'t find any wishlist with the provided ID', 'yith-woocommerce-wishlist' ), 0 );
			}

			if ( ! $wishlist->current_user_can( 'remove_wishlist' ) ) {
				throw new YITH_WCWL_Exception( __( 'There was an error while processing your request; please, try later', 'yith-woocommerce-wishlist' ), 0 ); // @since 3.0.7
			}

			$wishlist->delete();
		}

		/* === WOOCOMMERCE EMAIL METHODS === */

		/**
		 * Locate default templates of woocommerce in plugin, if exists
		 *
		 * @param string $core_file     Location of core files.
		 * @param string $template      Template to search.
		 * @param string $template_base Template base path.
		 *
		 * @return string
		 * @since  2.0.0
		 */
		public function filter_woocommerce_template( $core_file, $template, $template_base ) {
			$located = yith_wcwl_locate_template( $template );

			if ( $located ) {
				return $located;
			} else {
				return $core_file;
			}
		}

		/**
		 * Filters woocommerce available mails, to add wishlist related ones
		 *
		 * @param array $emails Array of available emails.
		 * @return array
		 * @since 2.0.0
		 */
		public function add_woocommerce_emails( $emails ) {
			$emails['YITH_WCWL_Estimate_Email'] = include( YITH_WCWL_INC . 'emails/class.yith-wcwl-estimate-email.php' );
			$emails['YITH_WCWL_Promotion_Email'] = include( YITH_WCWL_INC . 'emails/class.yith-wcwl-promotion-email.php' );
			$emails['YITH_WCWL_Back_In_Stock_Email'] = include( YITH_WCWL_INC . 'emails/class.yith-wcwl-back-in-stock-email.php' );
			$emails['YITH_WCWL_On_Sale_Item_Email'] = include( YITH_WCWL_INC . 'emails/class.yith-wcwl-on-sale-item-email.php' );

			return $emails;
		}

		/**
		 * Loads WC Mailer when needed
		 *
		 * @return void
		 * @since 1.0
		 * @author Antonio La Rocca <antonio.larocca@yithemes.it>
		 */
		public function load_wc_mailer() {
			add_action( 'send_estimate_mail', array( 'WC_Emails', 'send_transactional_email' ), 10, 4 );
			add_action( 'send_promotion_mail', array( 'WC_Emails', 'send_transactional_email' ), 10, 2 );
			add_action( 'send_back_in_stock_mail', array( 'WC_Emails', 'send_transactional_email' ), 10, 2 );
			add_action( 'send_on_sale_item_mail', array( 'WC_Emails', 'send_transactional_email' ), 10, 2 );
		}

		/**
		 * Return url to unsubscribe from wishlist mailing lists
		 *
		 * @param int $user_id User id.
		 * @return string Unsubscribe url
		 * @see \YITH_WCWL_Form_Handler_Premium::unsubscribe
		 */
		public function get_unsubscribe_link( $user_id ) {
			// retrieve unique unsubscribe token.
			$unsubscribe_token = get_user_meta( $user_id, 'yith_wcwl_unsubscribe_token', true );
			$unsubscribe_token_expiration = get_user_meta( $user_id, 'yith_wcwl_unsubscribe_token_expiration', true );

			// if user has no token, or previous token has expired, generate new unsubscribe token.
			if ( ! $unsubscribe_token || $unsubscribe_token_expiration < time() ) {
				$unsubscribe_token = wp_generate_password( 24, false, false );
				$unsubscribe_token_expiration = apply_filters( 'yith_wcwl_unsubscribe_token_expiration', time() + 30 * DAY_IN_SECONDS, $unsubscribe_token );

				update_user_meta( $user_id, 'yith_wcwl_unsubscribe_token', $unsubscribe_token );
				update_user_meta( $user_id, 'yith_wcwl_unsubscribe_token_expiration', $unsubscribe_token_expiration );
			}

			return apply_filters( 'yith_wcwl_unsubscribe_url', add_query_arg( 'yith_wcwl_unsubscribe', $unsubscribe_token, get_home_url() ), $user_id, $unsubscribe_token, $unsubscribe_token_expiration );
		}

		/* === BACK IN STOCK HANDLING === */

		/**
		 * Schedule email sending, when an item is back in stock
		 *
		 * @param int         $product_id Product or variation id.
		 * @param string      $stock_status Product stock status.
		 * @param \WC_Product $product Current product.
		 *
		 * @return void
		 */
		public function schedule_back_in_stock_emails( $product_id, $stock_status, $product ) {
			if ( 'instock' != $stock_status ) {
				return;
			}

			// skip if email ain't active.
			$email_options = get_option( 'woocommerce_yith_wcwl_back_in_stock_settings', array() );

			if ( ! isset( $email_options['enabled'] ) || 'yes' != $email_options['enabled'] ) {
				return;
			}

			// skip if product is on exclusion list.
			if ( ! empty( $email_options['product_exclusions'] ) && in_array( $product_id, $email_options['product_exclusions'] ) ) {
				return;
			}

			// skip if product category is on exclusion list.
			$product_categories = $product->get_category_ids();

			if ( ! empty( $email_options['category_exclusions'] ) && array_intersect( $product_categories, $email_options['category_exclusions'] ) ) {
				return;
			}

			// retrieve items.
			$items = $this->get_products(
				array(
					'user_id' => false,
					'session_id' => false,
					'wishlist_id' => 'all',
					'product_id' => $product_id,
				)
			);

			if ( empty( $items ) ) {
				return;
			}

			// queue handling.
			$queue = get_option( 'yith_wcwl_back_in_stock_queue', array() );
			$unsubscribed = get_option( 'yith_wcwl_unsubscribed_users', array() );

			foreach ( $items as $item ) {
				$user = $item->get_user();
				$user_id = $item->get_user_id();

				if ( ! $user ) {
					continue;
				}

				// skip if user unsubscribed.
				if ( in_array( $user->user_email, $unsubscribed ) ) {
					continue;
				}

				if ( ! isset( $queue[ $user_id ] ) ) {
					$queue[ $user_id ] = array(
						$item->get_product_id() => $item->get_id(),
					);
				} else {
					$queue[ $user_id ][ $item->get_product_id() ] = $item->get_id();
				}
			}

			update_option( 'yith_wcwl_back_in_stock_queue', $queue );
		}

		/* === ITEM ON SALE HANDLING === */

		/**
		 * Schedule on sale item email notification when an item switches to on sale
		 *
		 * @param \YITH_WCWL_Wishlist_Item $item Item on sale.
		 * @return void
		 */
		public function schedule_on_sale_item_emails( $item ) {
			$product_id = $item->get_product_id();
			$product = $item->get_product();
			$user_id = $item->get_user_id();
			$user = $item->get_user();

			if ( ! $user ) {
				return;
			}

			// skip if email ain't active.
			$email_options = get_option( 'woocommerce_yith_wcwl_on_sale_item_settings', array() );

			if ( ! isset( $email_options['enabled'] ) || 'yes' != $email_options['enabled'] ) {
				return;
			}

			// skip if product is on exclusion list.
			if ( ! empty( $email_options['product_exclusions'] ) && in_array( $product_id, $email_options['product_exclusions'] ) ) {
				return;
			}

			// skip if product category is on exclusion list.
			$product_categories = $product->get_category_ids();

			if ( ! empty( $email_options['category_exclusions'] ) && array_intersect( $product_categories, $email_options['category_exclusions'] ) ) {
				return;
			}

			// queue handling.
			$queue = get_option( 'yith_wcwl_on_sale_item_queue', array() );
			$unsubscribed = get_option( 'yith_wcwl_unsubscribed_users', array() );

			// skip if user unsubscribed.
			if ( in_array( $user->user_email, $unsubscribed ) ) {
				return;
			}

			if ( ! isset( $queue[ $user_id ] ) ) {
				$queue[ $user_id ] = array(
					$item->get_product_id() => $item->get_id(),
				);
			} else {
				$queue[ $user_id ][ $item->get_product_id() ] = $item->get_id();
			}

			update_option( 'yith_wcwl_on_sale_item_queue', $queue );
		}

		/* === GENERAL METHODS === */

		/**
		 * Checks whether multi-wishlist feature is enabled for current user
		 *
		 * @return bool Whether feature is enabled or not
		 */
		public function is_multi_wishlist_enabled() {
			$multi_wishlist_enabled = 'yes' == get_option( 'yith_wcwl_multi_wishlist_enable', 'no' );

			if ( $multi_wishlist_enabled && ! is_user_logged_in() ) {
				$multi_wishlist_enabled = $multi_wishlist_enabled && ( 'yes' == get_option( 'yith_wcwl_enable_multi_wishlist_for_unauthenticated_users', 'no' ) );
			}

			return $multi_wishlist_enabled;
		}

		/**
		 * Get current endpoint, if any
		 *
		 * @return string Current endpoint, empty string if no endpoint is being visited
		 */
		public function get_current_endpoint() {
			$action_params = get_query_var( YITH_WCWL()->wishlist_param, false );

			$action_params = explode( '/', apply_filters( 'yith_wcwl_current_wishlist_view_params', $action_params ) );
			$current_endpoint = ( isset( $action_params[0] ) ) ? $action_params[0] : '';

			return apply_filters( 'yith_wcwl_current_endpoint', $current_endpoint, $action_params );
		}

		/**
		 * Check if we're on a specific endpoint
		 *
		 * @param string $endpoint Endpoint to test.
		 * @return bool Whether we're on test endpoint or not
		 */
		public function is_endpoint( $endpoint ) {
			$current_endpoint = $this->get_current_endpoint();

			if ( 'view' == $endpoint && '' == $current_endpoint && yith_wcwl_is_wishlist_page() ) {
				$is_endpoint = true;
			} else {
				$is_endpoint = $current_endpoint == $endpoint;
			}

			return apply_filters( 'yith_wcwl_is_endpoint', $is_endpoint, $endpoint, $current_endpoint );
		}

		/* === WISHLIST LICENCE HANDLING === */

		/**
		 * Register plugins for activation tab
		 *
		 * @return void
		 * @since 2.0.0
		 */
		public function register_plugin_for_activation() {
			if ( ! class_exists( 'YIT_Plugin_Licence' ) ) {
				require_once( YITH_WCWL_DIR . 'plugin-fw/licence/lib/yit-licence.php' );
				require_once( YITH_WCWL_DIR . 'plugin-fw/licence/lib/yit-plugin-licence.php' );
			}

			YIT_Plugin_Licence()->register( YITH_WCWL_INIT, YITH_WCWL_SECRET_KEY, YITH_WCWL_SLUG );
		}

		/**
		 * Register plugins for update tab
		 *
		 * @return void
		 * @since 2.0.0
		 */
		public function register_plugin_for_updates() {
			if ( ! class_exists( 'YIT_Upgrade' ) ) {
				require_once( YITH_WCWL_DIR . 'plugin-fw/lib/yit-upgrade.php' );
			}

			YIT_Upgrade()->register( YITH_WCWL_SLUG, YITH_WCWL_INIT );
		}
	}
}

/**
 * Unique access to instance of YITH_WCWL_Premium class
 *
 * @return \YITH_WCWL_Premium
 * @since 2.0.0
 */
function YITH_WCWL_Premium() {
	return YITH_WCWL_Premium::get_instance();
}
