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
	public $errors = null;
	public $notices = [];
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
		$this->upload();
		$notices = $this->notices;
		$errors_messages = $this->errors->get_error_messages();
		require __DIR__ . '/partials/upload-zip-form.php';
	}

	/**
	 * Upload shortcode.
	 *
	 * @return void
	 */
	public function upload() {
		if ( ! isset( $_POST['zip_upload_nonce'] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['zip_upload_nonce'], 'zip_upload_nonce' ) ) {
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
				$this->notices[] = __( 'The shortcode name already exist. Please choose other shortcode name', 'royal-wordpress-plugin' );
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
			$this->notices[] = __( 'You have just registered the following shortcode:', 'royal-wordpress-plugin' ). ' ' . $result;
		}
	}
}
