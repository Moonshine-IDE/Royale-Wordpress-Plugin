<?php
if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

require_once ROYALE_WORDPRESS_PLUGIN_PATH . "includes/class-royale-wordpress-plugin-database.php";

/**

 * This class defines data model for shorcodes-list view.
 *
 * @since      1.0.0
 * @package    Royale_Wordpress_Plugin
 * @subpackage Royale_Wordpress_Plugin/admin
 * @author     Clearmedia <contact@clearmedia.pl>
 */
class Royale_Wordpress_Plugin_Shortcode_List extends WP_List_Table {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */

	protected $rwp_db;

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */

	protected $shortcodes_path;

	public function __construct() {
		parent::__construct( [
			'singular' => __( 'Shortcode', 'royale-wordpress-plugin' ),
			'plural'   => __( 'Shortcodes', 'royale-wordpress-plugin' ),
			'ajax'     => false
		] );

		$this->rwp_db = new Royale_Wordpress_Plugin_Database();
		$this->shortcodes_path = ROYALE_WORDPRESS_PLUGIN_PATH . "shortcodes-files/";
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
		_e( 'No shortcodes avaliable.', 'royale-wordpress-plugin' );
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
		$date_time = $item['upload_time'];
		$date_time = explode ( ' ' , $date_time);
		$date = $date_time[0];
		$date = date_create_from_format( 'Y-m-d' , $date );
		$date_new_format = $date->format('Y/m/d');
		$time = $date_time[1];
		$time = date_create_from_format( 'H:i:s' , $time );
		$time_new_format = $time->format('g:i a');
		$date_time = "$date_new_format at $time_new_format";
		return $date_time;
	}

	/**
	 * Method for name column
	 *
	 * @param array $item an array of DB data
	 *
	 * @return string
	 */
	function column_name( $item ) {
		$page = wp_unslash( $_REQUEST['page'] );

		$edit_query_args = array(
			'page'   => 'upload_a_new_script',
			'action' => 'edit',
			'shortcode_id'  => $item['id'],
		);

		// $edit_url = esc_url( wp_nonce_url( add_query_arg( $edit_query_args, 'admin.php' ), 'rwp_edit_shortcode_' . $item['id'] ) );
		$edit_url = esc_url( add_query_arg( $edit_query_args, 'admin.php' ) );
		$edit_text = _x( 'Edit', 'List table row action', 'royale-wordpress-plugin' );

		$delete_query_args = array(
			'page'   => $page,
			'action' => 'delete',
			'shortcode_id'  => $item['id'],
		);

		// $delete_url = esc_url( wp_nonce_url( add_query_arg( $delete_query_args, 'admin.php' ), 'rwp_delete_shortcode_' . $item['id'] ) );
		$delete_url = esc_url( wp_nonce_url( add_query_arg( $delete_query_args ), 'rwp_delete_shortcode_' . $item['id'] ) );
		$delete_text = _x( 'Delete', 'List table row action', 'royale-wordpress-plugin' );

		$actions = [
			'edit' => sprintf( '<a href="%1$s">%2$s</a>', $edit_url, $edit_text ),
			'delete' => sprintf( '<a href="%1$s" class="btn-rwp-delete">%2$s</a>', $delete_url, $delete_text )
		];

		$title = '<strong>' . $item['name'] . '</strong>';
		return $title . $this->row_actions( $actions );
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
		[royale_wp_plugin id="<?= $item['id'] ?>" name="<?= $item['name'] ?>"]
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
			'name' => __( 'Name', 'royale-wordpress-plugin' ),
			'shortcode' => __( 'Shortcode', 'royale-wordpress-plugin' ),
			'description' => __( 'Description', 'royale-wordpress-plugin' ),
			'date' => __( 'Date', 'royale-wordpress-plugin' ),
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

		/** Process actions */
		$this->process_actions();

		$per_page = $this->get_items_per_page( 'rwp_scripts_per_page', 5 );
		$current_page = $this->get_pagenum();
		$total_items  = $this->rwp_db->rows_count();

		$this->set_pagination_args( [
			'total_items' => $total_items,
			'per_page'    => $per_page
		] );

		$this->items = $this->rwp_db->get_rows( $per_page, $current_page );
	}

	/**
	 * Process delete actions
	 *
	 * @return void
	 */
	public function process_actions() {
		global $wp_filesystem;
    
		if ( ! function_exists( 'WP_Filesystem' ) ) {
			include_once 'wp-admin/includes/file.php';
		}
    
		WP_Filesystem();
	
		// If the delete bulk action is triggered
		if ( $this->current_action() === 'bulk-delete' ) {
			$delete_ids = esc_sql( $_POST['bulk-delete'] );
	
			// loop over the array of record IDs and delete them
			foreach ( $delete_ids as $id ) {
				$this->delete_shortcode($wp_filesystem, $id);
			}
		}

		if ( $this->current_action() === 'delete' ) {
			// In our file that handles the request, verify the nonce.
			if ( empty( $_GET['_wpnonce'] ) ) {
				return;
			}
			if ( empty( $_GET['shortcode_id'] ) ) {
				return;
			}
			$nonce = $_GET['_wpnonce'];
			$shortcode_id = $_GET['shortcode_id'];
			$action = 'rwp_delete_shortcode_' . $shortcode_id;

			if ( ! wp_verify_nonce( $nonce, $action ) ) {
				exit;
			} else {
				if ( empty( $_GET['page'] ) ) {
					return;
				}
				$page = wp_unslash( $_GET['page'] );
				$delete_query_args = [
					'page'   => $page,
				];
				$per_page = $this->get_items_per_page( 'rwp_scripts_per_page', 5 );
				$current_page = $this->get_pagenum();
				$total_items  = $this->rwp_db->rows_count();
				$total_pages = ceil( $total_items / $per_page );
				$total_pages = (int) $total_pages;
				if ( 1 < $current_page && $current_page < $total_pages  ) {
					// if current page is not the first one and not the last one add 'paged' arg
					$delete_query_args['paged'] = $current_page;
				} else if ( 1 < $current_page && $current_page === $total_pages ) {
					// if it is the last page, check how many items are on it
					$items_on_last_page = $total_items % $per_page;
					if ( 1 < $items_on_last_page ) {
						$delete_query_args['paged'] = $current_page;
					} else {
						// if the last item on the page is deleted, go back one page
						$delete_query_args['paged'] = $current_page - 1;
					}
				}

				$sortable_columns = $this->get_sortable_columns();
				$orderby_allowed_values = array_keys( $sortable_columns );
				$order_allowed_values = ['asc', 'desc'];

				if (
					!empty( $_GET['orderby'] ) &&
					in_array( $_GET['orderby'], $orderby_allowed_values )
				) {
					$delete_query_args['orderby'] = $_GET['orderby'];

					if (
						!empty( $_GET['order'] ) &&
						in_array( $_GET['order'], $order_allowed_values )
					) {
						$delete_query_args['order'] = $_GET['order'];
					}
				}

				$this->delete_shortcode($wp_filesystem, $shortcode_id);

		    	wp_redirect( esc_url_raw( add_query_arg($delete_query_args, 'admin.php') ) );
				exit;
			}

		}
	}

	/**
	 * Delete script files and corresponding database row
	 *
	 * @param object $wp_filesystem wordpress filesystem object
	 * @param int|string $id script id
	 * @return void
	 */
	protected function delete_shortcode($wp_filesystem, $id) {
		$id = (int) $id;
		$this->rwp_db->delete_db_row( $id );
		$shortcode_path = $this->shortcodes_path . "id-$id";
		if ( $wp_filesystem->is_dir( $shortcode_path ) ) {
			$wp_filesystem->delete( $shortcode_path, true );
		}
	}
}
