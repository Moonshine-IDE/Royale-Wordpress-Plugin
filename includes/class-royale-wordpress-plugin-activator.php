<?php

/**
 * Fired during plugin activation
 *
 * @link       https://clearmedia.pl/
 * @since      1.0.0
 *
 * @package    Royale_Wordpress_Plugin
 * @subpackage Royale_Wordpress_Plugin/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Royale_Wordpress_Plugin
 * @subpackage Royale_Wordpress_Plugin/includes
 * @author     Clearmedia <contact@clearmedia.pl>
 */
class Royale_Wordpress_Plugin_Activator {

	/**
	 * Code that is executed on plugin activation
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		require_once __DIR__ . '/class-royale-wordpress-plugin-database.php';
		$rwp_database = new Royale_Wordpress_Plugin_Database();
		$rwp_database->create_db_table();
	}

}
