<?php

/**
 * Create form in Wordpress admin menu for uploading zip files
 *
 * @link       https://clearmedia.pl/
 * @since      1.0.0
 *
 * @package    Royal_Wordpress_Plugin
 * @subpackage Royal_Wordpress_Plugin/admin
 */

class Upload_ZIP_Form {
	protected $errors = null;

	protected $notices = [];

	public function __construct() {
		add_action( 'admin_menu', array($this, 'register') );
		$this->errors = new WP_Error();
	}

	public function register() {
		add_submenu_page(
			'royal_wordpress_plugin_menu',
			'Upload a new shortcode',
			'Upload a new shortcode',
			'activate_plugins',
			'zip_upload_form',
			array($this, 'form')
		);
	}

	public function form( $atts ) {
		$this->upload();
		$notices = $this->notices;
		$errors_messages = $this->errors->get_error_messages();
		require __DIR__ . '/partials/upload-zip-form.php';
	}

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

		require_once __DIR__ . '/class-zip-uploader.php';
		$upload_folder = ROYAL_WORDPRESS_PLUGIN_PATH . "shortcodes-files";
		$uploader = new ZIP_Uploader($upload_folder);
		$result = $uploader->upload( $data );

		if ( is_wp_error( $result ) ) {
			$this->errors->add( $result->get_error_code(), $result->get_error_message() );
		} else {
			$this->notices[] = 'Uploaded! Path: ' . $result;
		}
	}
}
