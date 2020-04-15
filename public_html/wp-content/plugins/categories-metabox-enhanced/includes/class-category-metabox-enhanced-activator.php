<?php

/**
 * Fired during plugin activation
 *
 * @link       http://1fix.io
 * @since      0.1.0
 *
 * @package    Category_Metabox_Enhanced
 * @subpackage Category_Metabox_Enhanced/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      0.1.0
 * @package    Category_Metabox_Enhanced
 * @subpackage Category_Metabox_Enhanced/includes
 * @author     1Fix.io <1fixdotio@gmail.com>
 */
class Category_Metabox_Enhanced_Activator {

	/**
	 * Fired when the plugin is activated.
	 *
	 * @since    0.2.0
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses
	 *                                       "Network Activate" action, false if
	 *                                       WPMU is disabled or plugin is
	 *                                       activated on an individual blog.
	 */
	public static function activate( $network_wide ) {

		if ( function_exists( 'is_multisite' ) && is_multisite() ) {

			if ( $network_wide  ) {

				// Get all blog ids
				$blogs = wp_get_sites();

				foreach ( (array) $blogs as $blog ) {

					switch_to_blog( $blog['blog_id'] );
					self::single_activate();
				}

				restore_current_blog();
			} else {
				self::single_activate();
			}
		} else {
			self::single_activate();
		}

	}

	/**
	 * Fired for each blog when the plugin is activated.
	 *
	 * @since    0.2.0
	 */
	private static function single_activate() {

		if ( false == get_option( 'cme-display-activation-message' ) ) {
			add_option( 'cme-display-activation-message', true );
		}
	}

}
