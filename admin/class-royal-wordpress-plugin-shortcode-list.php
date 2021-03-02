<?php
if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

require_once ROYAL_WORDPRESS_PLUGIN_PATH . "includes/class-royal-wordpress-plugin-database.php";

/**

 * This class defines data model for shorcodes-list view.
 *
 * @since      1.0.0
 * @package    Royal_Wordpress_Plugin
 * @subpackage Royal_Wordpress_Plugin/admin
 * @author     Clearmedia <contact@clearmedia.pl>
 */
class Royal_Wordpress_Plugin_Shortcode_List extends WP_List_Table {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */

	protected $rwp_db;

	public function __construct() {
		parent::__construct( [
			'singular' => __( 'Shortcode', 'royal-wordpress-plugin' ),
			'plural'   => __( 'Shortcodes', 'royal-wordpress-plugin' ),
			'ajax'     => false
		] );

		$this->rwp_db = new Royal_Wordpress_Plugin_Database();
	}

	/**
	 * Display admin submenu for shortcode's list.
	 *
	 * @return void
	 */
	public function display_page() {
		$shortcodes_list = $this;
		require __DIR__ . '/partials/shortcodes-list.php';
	}

	/** Text displayed when no customer data is available */
	public function no_items() {
		_e( 'No shortcodes avaliable.', 'royal-wordpress-plugin' );
	}

	/**
	 * Render a column when no column specific method exists.
	 *
	 * @param array $item
	 * @param string $column_name
	 *
	 * @return mixed
	 */
	public function column_default( $item, $column_name ) {
		switch ( $column_name ) {
			case 'description':
				return $item[ $column_name ];
			default:
				return print_r( $item, true ); //Show the whole array for troubleshooting purposes
		}
	}

	/**
	 * Render the bulk edit checkbox
	 *
	 * @param array $item
	 *
	 * @return string
	 */
	function column_cb( $item ) {
		return sprintf(
			'<input type="checkbox" name="bulk-delete[]" value="%s" />', $item['id']
		);
	}

	/**
	 * Method for date column
	 *
	 * @param array $item an array of DB data
	 *
	 * @return string
	 */
	function column_date( $item ) {
		$date_full = $item['upload_time'];
		$date = explode ( ' ' , $date_full);
		$date_short = $date[0];
		ob_start();
		?>
		<abbr title="<?= $date_full ?>"><?= $date_short ?></abbr>
		<?php
		return ob_get_clean();
	}

	/**
	 * Method for name column
	 *
	 * @param array $item an array of DB data
	 *
	 * @return string
	 */
	function column_name( $item ) {
		return '<strong>' . $item['name'] . '</strong>';
	}

	/**
	 * Method for shortcode column
	 *
	 * @param array $item an array of DB data
	 *
	 * @return string
	 */
	function column_shortcode( $item ) {
		ob_start();
		?>
		[royal_wp_plugin id="<?= $item['id'] ?>" name="<?= $item['name'] ?>"]
		<?php
		$shortcode = ob_get_clean();
		$shortcode = trim ( $shortcode );

		ob_start();
		?>
		<span class="shortcode">
			<input type="text" onfocus="this.select();" readonly="readonly" value="<?= esc_attr( $shortcode ) ?>" class="large-text code" />
		</span>
		<?php
		return ob_get_clean();
	}

	/**
	 *  Associative array of columns
	 *
	 * @return array
	 */
	function get_columns() {
		$columns = [
			'cb' => '<input type="checkbox" />',
			'name' => __( 'Name', 'royal-wordpress-plugin' ),
			'shortcode' => __( 'Shortcode', 'royal-wordpress-plugin' ),
			'description' => __( 'Description', 'royal-wordpress-plugin' ),
			'date' => __( 'Date', 'royal-wordpress-plugin' ),
		];
		return $columns;
	}

	function get_hidden_columns() {
		return array();
	}

	/**
	 * Columns to make sortable.
	 *
	 * @return array
	 */
	public function get_sortable_columns() {
		$sortable_columns = array(
			'name' => array( 'name', true ),
			'date' => array( 'date', false )
		);

		return $sortable_columns;
	}

	/**
	 * Returns an associative array containing the bulk action
	 *
	 * @return array
	 */
	public function get_bulk_actions() {
		$actions = [
			'bulk-delete' => 'Delete'
		];

		return $actions;
	}

	/**
	 * Handles data query and filter, sorting, and pagination.
	 * 
	 * @return void
	 */
	public function prepare_items() {

		$columns = $this->get_columns();
		$hidden = $this->get_hidden_columns();
		$sortable = $this->get_sortable_columns();

		$this->_column_headers = array($columns, $hidden, $sortable);

		/** Process bulk action */
		$this->process_bulk_action();

		// $per_page     = $this->get_items_per_page( 'customers_per_page', 5 );
		$per_page     = 5;
		$current_page = $this->get_pagenum();
		$total_items  = $this->rwp_db->rows_count();

		$this->set_pagination_args( [
			'total_items' => $total_items,
			'per_page'    => $per_page
		] );

		$this->items = $this->rwp_db->get_rows( $per_page, $current_page );
	}

	public function process_bulk_action() {
		global $wp_filesystem;
    
		if ( ! function_exists( 'WP_Filesystem' ) ) {
			include_once 'wp-admin/includes/file.php';
		}
    
		WP_Filesystem();

		$shortcodes_path = ROYAL_WORDPRESS_PLUGIN_PATH . "shortcodes-files/";
	
		// If the delete bulk action is triggered
		if ( ( isset( $_POST['action'] ) && $_POST['action'] == 'bulk-delete' )
				 || ( isset( $_POST['action2'] ) && $_POST['action2'] == 'bulk-delete' )
		) {
	
			$delete_ids = esc_sql( $_POST['bulk-delete'] );
	
			// loop over the array of record IDs and delete them
			foreach ( $delete_ids as $id ) {
				$this->rwp_db->delete_db_row( $id );
				$shortcode_path = $shortcodes_path . "id-$id";
				if ( $wp_filesystem->is_dir( $shortcode_path ) ) {
					$wp_filesystem->delete( $shortcode_path, true );
				}
			}
		}
	}
}
