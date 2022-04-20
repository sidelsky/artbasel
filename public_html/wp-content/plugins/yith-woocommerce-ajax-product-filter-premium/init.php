<?php
/**
 * Plugin Name: YITH WooCommerce Ajax Product Filter Premium
 * Plugin URI: https://yithemes.com/themes/plugins/yith-woocommerce-ajax-product-filter/
 * Description:<code><strong>YITH WooCommerce AJAX Product Filter</strong></code> allows your users to find the product they are looking for as quickly as possible. Thanks to the plugin you will be able to set up one or more search filters for your WooCommerce products and improve the user experience of your shop. <a href="https://yithemes.com/" target="_blank">Get more plugins for your e-commerce shop on <strong>YITH</strong></a>
 * Version: 4.1.0
 * Author: YITH
 * Author URI: https://yithemes.com/
 * Text Domain: yith-woocommerce-ajax-navigation
 * Domain Path: /languages/
 *
 * WC requires at least: 4.3
 * WC tested up to: 5.4
 *
 * @author  YITH
 * @package YITH WooCommerce Ajax Product Filter
 * @version 1.3.2
 */

/**
 * Copyright 2020  YITH  (email : plugins@yithemes.com)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2, as
 * published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

// define required constants.

! defined( 'YITH_WCAN' ) && define( 'YITH_WCAN', true );
! defined( 'YITH_WCAN_URL' ) && define( 'YITH_WCAN_URL', plugin_dir_url( __FILE__ ) );
! defined( 'YITH_WCAN_DIR' ) && define( 'YITH_WCAN_DIR', plugin_dir_path( __FILE__ ) );
! defined( 'YITH_WCAN_INC' ) && define( 'YITH_WCAN_INC', YITH_WCAN_DIR . 'includes/' );
! defined( 'YITH_WCAN_VERSION' ) && define( 'YITH_WCAN_VERSION', '4.1.0' );
! defined( 'YITH_WCAN_DB_VERSION' ) && define( 'YITH_WCAN_DB_VERSION', '4.0.0' );
! defined( 'YITH_WCAN_PREMIUM' ) && define( 'YITH_WCAN_PREMIUM', true );
! defined( 'YITH_WCAN_FILE' ) && define( 'YITH_WCAN_FILE', __FILE__ );
! defined( 'YITH_WCAN_SLUG' ) && define( 'YITH_WCAN_SLUG', 'yith-woocommerce-ajax-navigation' );
! defined( 'YITH_WCAN_SECRET_KEY' ) && define( 'YITH_WCAN_SECRET_KEY', 'VsQ4mRdupNhzcONEx1mj' );
! defined( 'YITH_WCAN_INIT' ) && define( 'YITH_WCAN_INIT', plugin_basename( __FILE__ ) );

// define required functions.

if ( ! function_exists( 'yith_wcan_install' ) ) {
	/**
	 * Installs plugin and start the processing
	 *
	 * @return void
	 *
	 * @since 4.0
	 * @author Antonio La Rocca <antonio.larocca@yithemes.com>
	 */
	function yith_wcan_install() {

		if ( ! function_exists( 'WC' ) ) {
			add_action( 'admin_notices', 'yith_wcan_install_woocommerce_admin_notice' );
		} else {
			/**
			 * Instance main plugin class
			 */
			global $yith_wcan;

			// deactivate free version.
			yith_wcan_deactivate_free_version();

			// load plugin text domain.
			load_plugin_textdomain( 'yith-woocommerce-ajax-navigation', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

			$yith_wcan = yith_wcan_initialize();
		}
	}
}

if ( ! function_exists( 'yith_wcan_initialize' ) ) {
	/**
	 * Unique access to instance of YITH_Vendors class.
	 *
	 * @return YITH_WCAN|YITH_WCAN_Premium
	 * @since 1.0.0
	 */
	function yith_wcan_initialize() {
		// Load required classes and functions.
		require_once( YITH_WCAN_INC . 'class.yith-wcan.php' );

		if ( defined( 'YITH_WCAN_PREMIUM' ) && file_exists( YITH_WCAN_DIR . 'includes/class.yith-wcan-premium.php' ) ) {
			require_once( YITH_WCAN_INC . 'class.yith-wcan-premium.php' );
			return YITH_WCAN_Premium();
		}

		return YITH_WCAN();
	}
}

if ( ! function_exists( 'yith_wcan_install_plugin_framework' ) ) {
	/**
	 * Performs check over plugin framework, and maybe loads it
	 *
	 * @return void
	 *
	 * @since 4.0
	 * @author Antonio La Rocca <antonio.larocca@yithemes.com>
	 */
	function yith_wcan_install_plugin_framework() {
		// plugin framework version check.
		if ( ! function_exists( 'yit_maybe_plugin_fw_loader' ) && file_exists( YITH_WCAN_DIR . 'plugin-fw/init.php' ) ) {
			require_once( YITH_WCAN_DIR . 'plugin-fw/init.php' );
		}
		yit_maybe_plugin_fw_loader( YITH_WCAN_DIR );
	}
}

if ( ! function_exists( 'yith_wcan_deactivate_free_version' ) ) {
	/**
	 * Deactivate free version, when premium version is installed
	 *
	 * @return void
	 *
	 * @since 4.0
	 * @author Antonio La Rocca <antonio.larocca@yithemes.com>
	 */
	function yith_wcan_deactivate_free_version() {
		if ( ! function_exists( 'yit_deactive_free_version' ) ) {
			require_once( 'plugin-fw/yit-deactive-plugin.php' );
		}

		yit_deactive_free_version( 'YITH_WCAN_FREE_INIT', plugin_basename( __FILE__ ) );
	}
}

if ( ! function_exists( 'yith_wcan_install_woocommerce_admin_notice' ) ) {
	/**
	 * Print an admin notice if woocommerce is deactivated
	 *
	 * @return void
	 *
	 * @author Andrea Grillo <andrea.grillo@yithemes.com>
	 * @since 1.0
	 * @use admin_notices hooks
	 */
	function yith_wcan_install_woocommerce_admin_notice() { ?>
		<div class="error">
			<p><?php echo esc_html_x( 'YITH WooCommerce Ajax Product Filter is enabled but not effective. It requires WooCommerce in order to work.', '[Plugin Name]', 'yith-woocommerce-ajax-navigation' ); ?></p>
		</div>
		<?php
	}
}

// load plugin framework.
yith_wcan_install_plugin_framework();

// install plugin.
add_action( 'plugins_loaded', 'yith_wcan_install', 11 );
