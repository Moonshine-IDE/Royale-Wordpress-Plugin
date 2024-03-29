<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://clearmedia.pl/
 * @since      1.0.0
 *
 * @package    Royale_Wordpress_Plugin
 * @subpackage Royale_Wordpress_Plugin/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Royale_Wordpress_Plugin
 * @subpackage Royale_Wordpress_Plugin/includes
 * @author     Clearmedia <contact@clearmedia.pl>
 */
class Royale_Wordpress_Plugin_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'royale-wordpress-plugin',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
