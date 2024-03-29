<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://moonshine-ide.com/
 * @since             1.0.0
 * @package           Royale_Wordpress_Plugin
 *
 * @wordpress-plugin
 * Plugin Name:       Royale Apps
 * Plugin URI:        https://github.com/Moonshine-IDE/Royale-Wordpress-Plugin/
 * Description:       Royale Apps plugin allows include your Apache Royale application to wordpress website.
 * Version:           1.0.0
 * Author:            Moonshine IDE Team
 * Author URI:        https://moonshine-ide.com/
 * License:           GNU General Public License v2.0
 * License URI:       https://github.com/Moonshine-IDE/Royale-Wordpress-Plugin/blob/main/LICENSE.txt
 * Text Domain:       royale-wordpress-plugin
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
define( 'ROYALE_WORDPRESS_PLUGIN_VERSION', '1.0.0' );

/**
 * Plugin directory url
 */
if ( !defined( 'ROYALE_WORDPRESS_PLUGIN_URL' ) ) {
    define( 'ROYALE_WORDPRESS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}

/**
 * Plugin directory path
 */
if ( !defined( 'ROYALE_WORDPRESS_PLUGIN_PATH' ) ) {
    define( 'ROYALE_WORDPRESS_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-royale-wordpress-plugin-activator.php
 */
function activate_royale_wordpress_plugin() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-royale-wordpress-plugin-activator.php';
	Royale_Wordpress_Plugin_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-royale-wordpress-plugin-deactivator.php
 */
function deactivate_royale_wordpress_plugin() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-royale-wordpress-plugin-deactivator.php';
	Royale_Wordpress_Plugin_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_royale_wordpress_plugin' );
register_deactivation_hook( __FILE__, 'deactivate_royale_wordpress_plugin' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-royale-wordpress-plugin.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_royale_wordpress_plugin() {

	$plugin = new Royale_Wordpress_Plugin();
	$plugin->run();

}
run_royale_wordpress_plugin();
