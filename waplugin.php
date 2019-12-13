<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://waplugin.com/
 * @since             1.0.0
 * @package           Waplugin
 *
 * @wordpress-plugin
 * Plugin Name:       WAPLUGIN
 * Plugin URI:        https://github.com/wapluginlab/woocommerce-plugin
 * Description:       Woocommerce order notification via WhatsApp by WAPLUGIN.COM
 * Version:           1.0.1
 * Author:            WAPLUGIN
 * Author URI:        https://waplugin.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       waplugin
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'WAPLUGIN_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-waplugin-activator.php
 */
function activate_waplugin() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-waplugin-activator.php';
	Waplugin_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-waplugin-deactivator.php
 */
function deactivate_waplugin() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-waplugin-deactivator.php';
	Waplugin_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_waplugin' );
register_deactivation_hook( __FILE__, 'deactivate_waplugin' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-waplugin.php';

/*Auto Update*/
require plugin_dir_path( __FILE__ ) . 'plugin-update-checker/plugin-update-checker.php';
$WapluginUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
	'https://github.com/wapluginlab/waplugin-woocommerce/',
	__FILE__,
	'waplugin'
);
$WapluginUpdateChecker->setBranch('waplugin');

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_waplugin() {

	$plugin = new Waplugin();
	$plugin->run();

}
run_waplugin();
