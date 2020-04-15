<?php

/**
 * Fired during plugin deactivation
 *
 * @link       http://1fix.io
 * @since      0.1.0
 *
 * @package    Category_Metabox_Enhanced
 * @subpackage Category_Metabox_Enhanced/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      0.1.0
 * @package    Category_Metabox_Enhanced
 * @subpackage Category_Metabox_Enhanced/includes
 * @author     1Fix.io <1fixdotio@gmail.com>
 */
class Category_Metabox_Enhanced_Deactivator {

	/**
	 * Fired when the plugin is deactivated.
	 *
	 * @since    0.2.0
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses
	 *                                       "Network Deactivate" action, false if
	 *                                       WPMU is disabled or plugin is
	 *                                       deactivated on an individual blog.
	 */
	public static function deactivate( $network_wide ) {

		if ( function_exists( 'is_multisite' ) && is_multisite() ) {

			if ( $network_wide ) {

				// Get all blog ids
				$blogs = wp_get_sites();

				foreach ( (array) $blogs as $blog ) {

					switch_to_blog( $blog['blog_id'] );
					self::single_deactivate();
				}

				restore_current_blog();
			} else {
				self::single_deactivate();
			}
		} else {
			self::single_deactivate();
		}

	}

	/**
	 * Fired for each blog when the plugin is deactivated.
	 *
	 * @since    0.2.0
	 */
	private static function single_deactivate() {

		delete_option( 'cme-display-activation-message' );

	}

}
