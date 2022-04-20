<?php
/**
 * Plugin Name: WooCommerce Email Customizer Plus
 * Plugin URI: https://www.flycart.org
 * Description: Create awesome transactional emails with a drag and drop email builder.
 * Version: 1.2.7
 * Author: Flycart
 * Author URI: https://www.flycart.org
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 * Contributers: Sathyaseelan
 * WC requires at least: 3.6
 * WC tested up to: 5.1.0
 */
defined('ABSPATH') OR die;
//Define the plugin version
defined('WECP_PLUGIN_VERSION') OR define('WECP_PLUGIN_VERSION', '1.2.7');
// Define the plugin text domain
defined('WECP_TEXT_DOMAIN') OR define('WECP_TEXT_DOMAIN', 'woocommerce-email-customizer-plus');
// Define the slug
defined('WECP_PLUGIN_SLUG') OR define('WECP_PLUGIN_SLUG', 'woocommerce-email-customizer-plus');
// Define plugin path
defined('WECP_PLUGIN_PATH') OR define('WECP_PLUGIN_PATH', __DIR__ . '/');
// Define plugin URL
defined('WECP_PLUGIN_URL') OR define('WECP_PLUGIN_URL', plugin_dir_url(__FILE__));
// Define plugin file
defined('WECP_PLUGIN_FILE') OR define('WECP_PLUGIN_FILE', __FILE__);
// Define plugin prefix
defined('WECP_PLUGIN_PREFIX') OR define('WECP_PLUGIN_PREFIX', 'wecp');
defined('WECP_PLUGIN_NAME') OR define('WECP_PLUGIN_NAME', 'Woocommerce Email Customizer Plus');
defined('WECP_BASE_FILE') OR define('WECP_BASE_FILE', plugin_basename(__FILE__));
//Define the plugin language
defined('WECP_PLUGIN_LANGUAGE') OR define('WECP_PLUGIN_LANGUAGE', get_locale());
// Autoload the vendor
if (!file_exists(__DIR__ . '/vendor/autoload.php')) {
    return false;
} else {
    require __DIR__ . '/vendor/autoload.php';
}
//Init the router
$router = new \Wecp\App\Router();
$router->init();