<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * Dashboard. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://1fix.io
 * @since             0.1.0
 * @package           Category_Metabox_Enhanced
 *
 * @wordpress-plugin
 * Plugin Name:       Categories Metabox Enhanced
 * Plugin URI:        http://1fix.io/category-metabox-enhanced/
 * Description:       Replace the checkboxes with radio buttons or a select drop-down in the built-in Categories metabox.
 * Version:           0.6.1
 * Author:            1Fix.io
 * Author URI:        http://1fix.io/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       of-cme
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/class-category-metabox-enhanced-activator.php';

/**
 * The code that runs during plugin deactivation.
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/class-category-metabox-enhanced-deactivator.php';

/** This action is documented in includes/class-category-metabox-enhanced-activator.php */
register_activation_hook( __FILE__, array( 'Category_Metabox_Enhanced_Activator', 'activate' ) );

/** This action is documented in includes/class-category-metabox-enhanced-deactivator.php */
register_deactivation_hook( __FILE__, array( 'Category_Metabox_Enhanced_Deactivator', 'deactivate' ) );

/**
 * The core plugin class that is used to define internationalization,
 * dashboard-specific hooks, and public-facing site hooks.
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/class-category-metabox-enhanced.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    0.1.0
 */
function run_category_metabox_enhanced() {

	$plugin = new Category_Metabox_Enhanced();
	$plugin->run();

}
run_category_metabox_enhanced();
