<?php

/**
 * Fired during plugin activation
 *
 * @link       https://clearmedia.pl/
 * @since      1.0.0
 *
 * @package    Royal_Wordpress_Plugin
 * @subpackage Royal_Wordpress_Plugin/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Royal_Wordpress_Plugin
 * @subpackage Royal_Wordpress_Plugin/includes
 * @author     Clearmedia <contact@clearmedia.pl>
 */
class Royal_Wordpress_Plugin_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		require_once __DIR__ . '/class-royal-wordpress-plugin-database.php';
		$rwp_database = new Royal_Wordpress_Plugin_Database();
		$rwp_database->create_db_table();
	}

}
