<?php

/**
 * Create and interact with the plugin's database table with the shortcode's information.
 *
 * @since      1.0.0
 * @package    Royal_Wordpress_Plugin
 * @subpackage Royal_Wordpress_Plugin/includes
 * @author     Clearmedia <contact@clearmedia.pl>
 */
class Royal_Wordpress_Plugin_Database {

	/**
	 * Wordpress database instance.
	 *
	 * @since    1.0.0
	 */
	private $wpdb;

	/**
	 * Plugin's table name without the Wordpress'es prefix.
	 *
	 * @since    1.0.0
	 */
	private $short_table_name = "royal_wordpress_plugin_shortcodes";

	/**
	 * Plugin's table name with the Wordpress'es prefix.
	 *
	 * @since    1.0.0
	 */
	private $table_name;

	/**
	 * Wordpress database's charset collate.
	 *
	 * @since    1.0.0
	 */
	private $charset_collate;

	public function __construct() {
		global $wpdb;
		$this->wpdb = $wpdb;
		$this->table_name = $this->wpdb->prefix . $this->short_table_name;
		$this->charset_collate = $this->wpdb->get_charset_collate();
	}

	/**
	 * Create a Wordpress database's table for plugin's shortcodes
	 *
	 * @return void
	 */
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

	/**
	 * Delete the row from the plugin's database table.
	 *
	 * @param int|string $id
	 * @return void
	 */
	public function delete_db_row( $id ) {
		$this->wpdb->delete(
			$this->table_name,
			array(
				'id' => $id,
			)
		);
	}

	/**
	 * Insert row to the plugin's database table.
	 *
	 * @param string $name
	 * @param string $description
	 * @return integer|false
	 */
	public function insert_db_row( string $name, string $description ) {
		$this->wpdb->insert( 
			$this->table_name,
			array( 
				'name' => $name,
				'description' => $description,
				'upload_time' => current_time( 'mysql' ),
			)
		);
		return $this->wpdb->insert_id;
	}

	/**
	 * Update row in the plugin's database table.
	 *
	 * @param string $name
	 * @param string $description
	 * @param integer $id
	 * @return integer|false
	 */
	public function update_db_row( string $name, string $description, int $id ) {
		$updated_row = $this->wpdb->update( 
			$this->table_name,
			array( 
				'name' => $name,
				'description' => $description,
			),
			array(
				'id' => $id,
			)
		);
		return $updated_row;
	}

	/**
	 * Get a row by name from the plugin's database table.
	 *
	 * @param string $name
	 * @return object|null
	 */
	public function get_row_by_name( string $name ) {
		$row = $this->wpdb->get_row( "SELECT * FROM $this->table_name WHERE name = \"$name\"");
		return $row;
	}

	/**
	 * Get a row by id from the plugin's database table.
	 *
	 * @param integer $id
	 * @return object|null
	 */
	public function get_row_by_id( int $id ) {
		$row = $this->wpdb->get_row( "SELECT * FROM $this->table_name WHERE id = \"$id\"");
		return $row;
	}

	/**
	 * Get paginated rows from the plugin's database table
	 *
	 * @param integer $per_page Number of rows that make one paginated page.
	 * @param integer $page_number Paginated page number to be got from the database.
	 * @return array|null
	 */
	public function get_rows( $per_page = 5, $page_number = 1 ) {
		$sql = "SELECT * FROM {$this->table_name}";

		if ( ! empty( $_REQUEST['orderby'] ) ) {
			$orderby = $_REQUEST['orderby'];
			if ( $orderby === 'date' ) {
				$orderby = 'upload_time';
			}
			$sql .= ' ORDER BY ' . esc_sql( $orderby );
			$sql .= ! empty( $_REQUEST['order'] ) ? ' ' . esc_sql( $_REQUEST['order'] ) : ' ASC';
		}

		$sql .= " LIMIT $per_page";

		$offset = ( $page_number - 1 ) * $per_page;
		$sql .= " OFFSET $offset";
		$rows = $this->wpdb->get_results( $sql, 'ARRAY_A' );
		return $rows;
	}

	/**
	 * Returns the count of rows in the plugin's database table.
	 *
	 * @return string|null
	 */
	public function rows_count() {

		$sql = "SELECT COUNT(*) FROM {$this->table_name}";

		return $this->wpdb->get_var( $sql );
	}
}
