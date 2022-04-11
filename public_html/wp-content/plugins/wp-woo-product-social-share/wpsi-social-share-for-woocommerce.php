<?php
/*
Plugin Name: Social Share For WooCommerce
Plugin URI : https://wordpress.org/plugins/wpsi-social-share-for-woocommerce/
Description: Add your valualble products on social sharing platform using Social Share For WooCommerce plugin with responsive design.
Version: 1.0.3
Author: kuldip_raghu
Author URI: https://ignizeo.com
Text Domain: wpsisocialshare
Tested up to: 5.7.2
WC tested up to: 5.5.1

License: GPL2
This WordPress Plugin is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

This free software is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this software. If not, see http://www.gnu.org/licenses/gpl-2.0.html.
*/

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * Basic Plugin Definitions
 * 
 * @package Social Share For WooCommerce
 * @since 1.0.0
 */
if (!defined('WPSI_PLUGIN_PATH')) {
    define("WPSI_PLUGIN_PATH", plugin_dir_path(__FILE__));
}

if (!defined('WPSI_PLUGIN_URL')) {
    define("WPSI_PLUGIN_URL", __FILE__);
}

if (!defined('WPSI_PLUGIN_INC')) {
    define("WPSI_PLUGIN_INC", WPSI_PLUGIN_PATH . "includes/");
}

if (!defined('WPSI_PLUGIN_TMP_PATH')) {
    define("WPSI_PLUGIN_TMP_PATH", WPSI_PLUGIN_INC . "template/");
}

if (!defined('WPSI_BASENAME')) {
    define('WPSI_BASENAME', basename(WPSI_PLUGIN_PATH)); // base name
}


/**
 * Load Text Domain
 * 
 * This gets the plugin ready for translation.
 * 
 * @package Social Share For WooCommerce
 * @since 1.0.0
 */
function wpsi_plugins_loaded() {
    // Set filter for plugin's languages directory
    $wpsi_languages_directory = dirname(plugin_basename(__FILE__)) . '/languages/';
    $wpsi_languages_directory = apply_filters('wpsi_languages_directory', $wpsi_languages_directory);
    // Traditional WordPress plugin locale filter
    $locale = apply_filters('plugin_locale', get_locale(), 'wpsisocialshare');
    $mofile = sprintf('%1$s-%2$s.mo', 'wpsisocialshare', $locale);
    // Setup paths to current locale file
    $mofile_local = $wpsi_languages_directory . $mofile;
    $mofile_global = WP_LANG_DIR . '/' . WPSI_BASENAME . '/' . $mofile;
    if (file_exists($mofile_global)) { // Look in global /wp-content/languages/woo-product-social-share folder
        load_textdomain('wpsisocialshare', $mofile_global);
    } elseif (file_exists($mofile_local)) { // Look in local /wp-content/plugins/woo-product-social-share/languages/ folder
        load_textdomain('wpsisocialshare', $mofile_local);
    } else { // Load the default language files
        load_plugin_textdomain('wpsisocialshare', false, $wpsi_languages_directory);
    }
}

add_action('plugins_loaded', 'wpsi_plugins_loaded');

/**
 * Checking if Woocommerce is either installed or active
 * 
 * @package Social Share For WooCommerce
 * @since 1.0.0
 */
function wpsi_admin_notices() {

    if (!class_exists('Woocommerce')) {

        echo '<div class="error">';
        echo "<p><strong>" . esc_html__('Social Share For WooCommerce requires WooCommerce plugin to be active!', 'woocommerce') . "</strong></p>";
        echo '</div>';
    }
}

register_activation_hook(__FILE__, 'wpsi_install');

/**
 * Installation of plugin
 * 
 * @package Social Share For WooCommerce
 * @since 1.0.0
 */
function wpsi_install() {
    global $wpdb;
    $wpsi_option = get_option('wpsi_active_plugins');
}

add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'wpsi_add_settings_link');

/**
 * Add Links in plugin list page
 * 
 * @package Social Share For WooCommerce
 * @since 1.0.0
 */
function wpsi_add_settings_link($links) {
    $plugin_links = array(
        '<a href="' . add_query_arg(array('page' => 'wpsi-social-share'), admin_url('admin.php')) . '">' . esc_html__('Settings', 'wpsisocialshare') . '</a>',
        '<a href="' . esc_url('https://ignizeo.com/support') . '" target="_blank">' . esc_html__('Support', 'wpsisocialshare') . '</a>'
    );
    return array_merge($plugin_links, $links);
}

add_action('admin_init', 'wpsi_check_woocommerce_activation_status');

/**
 * Activation status
 * 
 * @package Social Share For WooCommerce
 * @since 1.0.0
 */
function wpsi_check_woocommerce_activation_status() {

    if (!class_exists('Woocommerce')) {
        // is this plugin active? 
        // deactivate the plugin
        deactivate_plugins(plugin_basename(__FILE__));
        // unset activation notice
        unset($_GET['activate']);
        // display notice
        add_action('admin_notices', 'wpsi_admin_notices');
    }
}

add_action('admin_menu', 'wpsi_dashboard_page');

/**
 * Add Plugin Settings page to wp dashboard
 * 
 * @package Social Share For WooCommerce
 * @since 1.0.0
 */
function wpsi_dashboard_page() {

    add_menu_page(esc_html__("Social Share For WooCommerce", 'wpsisocialshare'), esc_html__("Social Share For WooCommerce", 'wpsisocialshare'), "manage_options", "wpsi-social-share", "wpsi_render_social_sharing", "dashicons-share");
}

// ---------------------------------------------------------
// Call Required Plugin Files
// ---------------------------------------------------------
require_once WPSI_PLUGIN_INC . "wpsi_render_settings_panel.php";

add_action("admin_enqueue_scripts", "wpsi_enqueue_assets");

require_once WPSI_PLUGIN_INC . "wpsi_enqueue.php";

add_action('admin_init', 'wpsi_register_settings');

require_once WPSI_PLUGIN_INC . "wpsi_register_settings.php";


/**
 * Validate User Submitted Settings Input (using trim() see @http://php.net/trim)
 * 
 * @package Social Share For WooCommerce
 * @since 1.0.0
 */
function wpsi_main_settings_validate($arr_input) {

    $options = get_option('wpsi_register_settings_fields');

    $options['wpsi_show_hide_field'] = trim($arr_input['wpsi_show_hide_field']);

    $options['wpsi_buttons_style_field'] = trim($arr_input['wpsi_buttons_style_field']);

    $options['wpsi_buttons_position_field'] = trim($arr_input['wpsi_buttons_position_field']);

    $options['wpsi_buttons_list_field'] = trim($arr_input['wpsi_buttons_list_field']);

    $options['wpsi_buttons_icontext_field'] = trim($arr_input['wpsi_buttons_icontext_field']);

    return $options;
}

require_once WPSI_PLUGIN_INC . "/wpsi_render_front_icons.php";

require_once WPSI_PLUGIN_TMP_PATH . "wpsi_buttons_show_hide.php";
require_once WPSI_PLUGIN_TMP_PATH . "wpsi_buttons_position.php";
require_once WPSI_PLUGIN_TMP_PATH . "wpsi_buttons_style.php";
require_once WPSI_PLUGIN_TMP_PATH . "wpsi_buttons_list.php";
require_once WPSI_PLUGIN_TMP_PATH . "wpsi_buttons_icon_text.php";