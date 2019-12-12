<?php

/**
 * Fired during plugin activation
 *
 * @link       https://waplugin.com/
 * @since      1.0.0
 *
 * @package    Waplugin
 * @subpackage Waplugin/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Waplugin
 * @subpackage Waplugin/includes
 * @author     WAPLUGIN <waplugin@gmail.com>
 */
class Waplugin_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		if (!class_exists( 'WooCommerce' )) {
			wp_die('Sorry, but this plugin requires the WooCommerce Plugin to be installed and active. <br><a href="' . admin_url( 'plugins.php' ) . '">&laquo; Return to Plugins</a>');
		}
	}

}
