<?php

/**

 * This class defines all code necessary to create plugin's database table when activated and deletes it when uninstall.
 *
 * @since      1.0.0
 * @package    Royal_Wordpress_Plugin
 * @subpackage Royal_Wordpress_Plugin/includes
 * @author     Clearmedia <contact@clearmedia.pl>
 */
class Royal_Wordpress_Plugin_Database {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */

	private $wpdb;
	private $table_name;
	private $charset_collate;

	public function __construct() {
		global $wpdb;
		$this->wpdb = $wpdb;
		$this->table_name = $this->wpdb->prefix . "royal_wordpress_plugin_shortcodes";
		$this->charset_collate = $this->wpdb->get_charset_collate();
	}

	public function create_db_table() {
		$sql = "CREATE TABLE $this->table_name (
			id bigint UNSIGNED NOT NULL AUTO_INCREMENT,
			name char(255) NOT NULL UNIQUE,
			description text NOT NULL,
			upload_time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			PRIMARY KEY  (id)
		) $this->charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta( $sql );
	}

	public function insert_db_row( string $name, string $description ) {
		$this->wpdb->insert( 
			$this->table_name,
			array( 
				'name' => $name,
				'description' => $description,
				'upload_time' => current_time( 'mysql' ),
			)
		);
	}

	public function delete_db_row( $id ) {
		$this->wpdb->delete(
			$this->table_name,
			array(
				'id' => $id,
			)
		);
	}
}
