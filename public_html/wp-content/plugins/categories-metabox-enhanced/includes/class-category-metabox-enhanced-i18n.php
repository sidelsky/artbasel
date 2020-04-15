<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that its ready for translation.
 *
 * @link       http://1fix.io
 * @since      0.1.0
 *
 * @package    Category_Metabox_Enhanced
 * @subpackage Category_Metabox_Enhanced/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that its ready for translation.
 *
 * @since      0.1.0
 * @package    Category_Metabox_Enhanced
 * @subpackage Category_Metabox_Enhanced/includes
 * @author     1Fix.io <1fixdotio@gmail.com>
 */
class Category_Metabox_Enhanced_i18n {

	/**
	 * The domain specified for this plugin.
	 *
	 * @since    0.1.0
	 * @access   private
	 * @var      string    $domain    The domain identifier for this plugin.
	 */
	private $domain;

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    0.1.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			$this->domain,
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}

	/**
	 * Set the domain equal to that of the specified domain.
	 *
	 * @since    0.1.0
	 * @param    string    $domain    The domain that represents the locale of this plugin.
	 */
	public function set_domain( $domain ) {
		$this->domain = $domain;
	}

}
