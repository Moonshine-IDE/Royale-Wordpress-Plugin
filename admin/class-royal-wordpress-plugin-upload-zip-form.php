<?php

require_once ROYAL_WORDPRESS_PLUGIN_PATH . "includes/class-royal-wordpress-plugin-database.php";
require_once __DIR__ . '/class-royal-wordpress-plugin-zip-uploader.php';

/**
 * Prepare data for upload-zip-form view.
 * 
 * Validate user input on server side.
 * Trigger Royal_Wordpress_Plugin_ZIP_Uploader to add shortcodes to the database and to the filesystem
 *
 * @link       https://clearmedia.pl/
 * @since      1.0.0
 *
 * @package    Royal_Wordpress_Plugin
 * @subpackage Royal_Wordpress_Plugin/admin
 */

class Royal_Wordpress_Plugin_Upload_ZIP_Form {
	protected $rwp_wp_nonce_field = '';
	protected $page_title = null;
	protected $page_subtitle = null;
	protected $shortcode_name = '';
	protected $shortcode_description = '';
	protected $rwp_wp_nonce_action = -1;
	protected $is_file_upload_required = true;
	protected $submit_button_text = null;
	protected $errors = null;
	protected $notices = [];
	protected $rwp_db;

	public function __construct() {
		$this->errors = new WP_Error();
		$this->rwp_db = new Royal_Wordpress_Plugin_Database();
	}

	/**
	 * Display admin submenu for shortcode's upload.
	 *
	 * @return void
	 */
	public function display_page() {
		$page_title = $this->page_title;
		$page_subtitle = $this->page_subtitle;
		$shortcode_name = $this->shortcode_name;
		$shortcode_description = $this->shortcode_description;
		$is_file_upload_required = $this->is_file_upload_required;
		$submit_button_text = $this->submit_button_text;
		$rwp_wp_nonce_action = $this->rwp_wp_nonce_action;
		$notices = $this->notices;
		$errors_messages = $this->errors->get_error_messages();
		require __DIR__ . '/partials/upload-zip-form.php';
	}

	/**
	 * Check HTTP request and trigger needed action.
	 *
	 * @return void
	 */
	public function load() {
		$is_edit_action =
			isset( $_GET['action'] ) &&
			$_GET['action'] === 'edit' &&
			isset( $_GET['shortcode_id'] );
		if ( !$is_edit_action ) {
			$this->upload();
			$this->rwp_wp_nonce_action = 'rwp_upload_shortcode';
		} else {
			$shortcode_id = (int) $_GET['shortcode_id'];
			$this->edit( $shortcode_id );
			$this->rwp_wp_nonce_action = 'rwp_edit_shortcode_' . $shortcode_id;
			$shortcode_in_db = $this->rwp_db->get_row_by_id( $shortcode_id );
			$this->shortcode_name = $shortcode_in_db->name;
			$this->shortcode_description = $shortcode_in_db->description;
			$this->is_file_upload_required = false;
			$this->submit_button_text = __( 'Update', 'royal-wordpress-plugin' );
			$shortcode = "[royal_wp_plugin id=\"$shortcode_id\" name=\"{$this->shortcode_name}\"]";
			$this->page_title = __( 'Edit script', 'royal-wordpress-plugin' );
			$this->page_subtitle = $shortcode;
		}
	}

	/**
	 * Upload shortcode.
	 *
	 * @return void
	 */
	public function upload() {
		if ( !isset( $_POST['_wpnonce'] ) ) {
			return;
		}

		if ( !wp_verify_nonce( $_POST['_wpnonce'], 'rwp_upload_shortcode' ) ) {
			return;
		}

		$posted_data =  $_POST ?? array();
		$file_data   = $_FILES ?? array();
		$data        = array_merge( $posted_data, $file_data );

		$upload_folder = ROYAL_WORDPRESS_PLUGIN_PATH . "shortcodes-files";
		$uploader = new Royal_Wordpress_Plugin_ZIP_Uploader($upload_folder);

		$is_valid_shortcode_name = false;
		if ( !empty( $_POST['shortcode-name'] ) ) {
			$shortcode_in_db = $this->rwp_db->get_row_by_name( $_POST['shortcode-name'] );
			if ( !$shortcode_in_db ) {
				$is_valid_shortcode_name = true;
			} else {
				$this->notices[] = __( 'The shortcode name "', 'royal-wordpress-plugin' ) . $shortcode_in_db->name . __( '" already exist. Please choose other shortcode name', 'royal-wordpress-plugin' );
			};
		} else {
			$this->notices[] = __( 'Shortcode name is missing.', 'royal-wordpress-plugin' );
		}

		$is_file_attached = false;
		if ( !empty( $_FILES['file']['tmp_name'] ) ) {
			$is_file_attached = true;
		} else {
			$this->notices[] = __( 'File is missing.', 'royal-wordpress-plugin' );
		}

		if ( !$is_valid_shortcode_name ) {
			return;
		}

		if ( !$is_file_attached ) {
			return;
		}

		$result = $uploader->upload( $data );

		if ( is_wp_error( $result ) ) {
			$this->errors->add( $result->get_error_code(), $result->get_error_message() );
		} else {
			wp_redirect( admin_url('admin.php?page=royal_wordpress_plugin_menu&orderby=date&order=desc') );
			exit;
		}
	}

	/**
	 * Edit shortcode.
	 * 
	 * @param int $shortcode_id
	 *
	 * @return void
	 */
	public function edit( int $shortcode_id ) {
		if ( !isset( $_POST['_wpnonce'] ) ) {
			return;
		}

		if ( !wp_verify_nonce( $_POST['_wpnonce'], 'rwp_edit_shortcode_' . $shortcode_id ) ) {
			return;
		}

		$posted_data =  $_POST ?? array();
		$file_data   = $_FILES ?? array();
		$data        = array_merge( $posted_data, $file_data );

		$upload_folder = ROYAL_WORDPRESS_PLUGIN_PATH . "shortcodes-files";
		$uploader = new Royal_Wordpress_Plugin_ZIP_Uploader($upload_folder);

		$is_valid_shortcode_name = false;
		if ( !empty( $_POST['shortcode-name'] ) ) {
			$shortcode_in_db = $this->rwp_db->get_row_by_name( $_POST['shortcode-name'] );
			if ( !$shortcode_in_db ) {
				$is_valid_shortcode_name = true;
			} else if ( $shortcode_id === (int) $shortcode_in_db->id ) {
				$is_valid_shortcode_name = true;
			} else {
				$this->notices[] = __( 'The shortcode name "', 'royal-wordpress-plugin' ) . $shortcode_in_db->name . __( '" already exist. Please choose other shortcode name', 'royal-wordpress-plugin' );
			};
		} else {
			$this->notices[] = __( 'Shortcode name is missing.', 'royal-wordpress-plugin' );
		}

		$is_file_attached = false;
		if ( !empty( $_FILES['file']['tmp_name'] ) ) {
			$is_file_attached = true;
		}

		if ( $is_valid_shortcode_name ) {
			$updated_shortcode = $this->rwp_db->update_db_row( $_POST['shortcode-name'], $_POST['shortcode-description'], $shortcode_id );
			if ( $updated_shortcode ) {
				$this->notices[] = __( 'You have just modified the metadata for the following shortcode:', 'royal-wordpress-plugin' ). ' ' . "[royal_wp_plugin id=\"$shortcode_id\" name=\"{$_POST['shortcode-name']}\"]";
			}
		}

		if ( $is_file_attached ) {
			$result = $uploader->upload( $data, $shortcode_id );

			if ( is_wp_error( $result ) ) {
				$this->errors->add( $result->get_error_code(), $result->get_error_message() );
			} else {
				$this->notices[] = __( 'You have just modified the script for the following shortcode:', 'royal-wordpress-plugin' ). ' ' . $result;
			}
		}

	}
}
