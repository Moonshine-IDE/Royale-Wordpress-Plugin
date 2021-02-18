<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://clearmedia.pl/
 * @since             1.0.0
 * @package           Royal_Wordpress_Plugin
 *
 * @wordpress-plugin
 * Plugin Name:       Royal Wordpress Plugin
 * Plugin URI:        https://github.com/prominic/Royale-Wordpress-Plugin
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Clearmedia
 * Author URI:        https://clearmedia.pl/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       royal-wordpress-plugin
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
define( 'ROYAL_WORDPRESS_PLUGIN_VERSION', '1.0.0' );

if ( !defined( 'ROYAL_WORDPRESS_PLUGIN_URL' ) ) {
    define( 'ROYAL_WORDPRESS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}

if ( !defined( 'ROYAL_WORDPRESS_PLUGIN_PATH' ) ) {
    define( 'ROYAL_WORDPRESS_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-royal-wordpress-plugin-activator.php
 */
function activate_royal_wordpress_plugin() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-royal-wordpress-plugin-activator.php';
	Royal_Wordpress_Plugin_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-royal-wordpress-plugin-deactivator.php
 */
function deactivate_royal_wordpress_plugin() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-royal-wordpress-plugin-deactivator.php';
	Royal_Wordpress_Plugin_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_royal_wordpress_plugin' );
register_deactivation_hook( __FILE__, 'deactivate_royal_wordpress_plugin' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-royal-wordpress-plugin.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_royal_wordpress_plugin() {

	$plugin = new Royal_Wordpress_Plugin();
	$plugin->run();

}
run_royal_wordpress_plugin();
