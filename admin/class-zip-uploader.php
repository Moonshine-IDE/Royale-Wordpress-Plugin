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

class ZIP_Uploader {

	protected $folder = '';

	public function __construct($folder) {
		$this->folder = $folder;
	}

	/**
	 * Check if there is an error
	 *
	 * @param $error
	 *
	 * @return bool|WP_Error
	 */
	public function check_error($error) {
		$file_errors = array(
			0 => __( "There is no error, the file uploaded with success", 'your_textdomain' ),
			1 => __( "The uploaded file exceeds the upload_max_files in server settings", 'your_textdomain' ),
			2 => __( "The uploaded file exceeds the MAX_FILE_SIZE from html form", 'your_textdomain' ),
			3 => __( "The uploaded file uploaded only partially", 'your_textdomain' ),
			4 => __( "No file was uploaded", 'your_textdomain' ),
			6 => __( "Missing a temporary folder", 'your_textdomain' ),
			7 => __( "Failed to write file to disk", 'your_textdomain' ),
			8 => __( "A PHP extension stoped file to upload", 'your_textdomain' ),
		);

		if ( $error > 0 ) {
			return new \WP_Error( 'file-error', $file_errors[ $error ] );
		}

		return true;
	}

	/**
	 * Upload File
	 *
	 * @param $file
	 *
	 * @return bool|string|true|WP_Error
	 */
	public function upload( $file ) {
		/** @var $wp_filesystem \WP_Filesystem_Direct */
		global $wp_filesystem;
    
		if ( ! function_exists( 'WP_Filesystem' ) ) {
			include_once 'wp-admin/includes/file.php';
		}
    
		WP_Filesystem();

		$file_error = $file["file"]["error"];

		// Check for Errors
		if ( is_wp_error( $this->check_error( $file_error ) ) ) {
			return $this->check_error( $file_error );
		}
    
		$file_name       = $file["file"]["name"];
		$file_name_arr   = explode( '.', $file_name );
		$extension       = array_pop( $file_name_arr );
		$filename        = implode( '.', $file_name_arr ); // File Name
		$zip_file        = sanitize_title( $filename ) . '.' . $extension; //Our File

		if ( 'zip' !== $extension ) {
			return new WP_Error( 'no-zip', 'This does not seem to be a ZIP file' );
		}

		$temp_name  = $file["file"]["tmp_name"];

		// Get upload folder path
		$upload_path = $this->folder;
    
    // Create upload folder if doesn't exist
		if ( ! $wp_filesystem->exists( $upload_path ) ) {
			$wp_filesystem->mkdir( $upload_path );
		}

		$working_dir = $this->folder . "-temp";
    
    // Delete if such folder exists
		if ( $wp_filesystem->is_dir( $working_dir ) ) {
			$wp_filesystem->delete( $working_dir, true );
		}
    // Create the folder to hold our zip file
		$wp_filesystem->mkdir( $working_dir );
		
    // Uploading ZIP file
		if( move_uploaded_file( $temp_name, $working_dir . "/" . $zip_file ) ){

			// Unzip the file to the upload path
			$unzip_result = unzip_file( $working_dir . "/" . $zip_file, $upload_path );

			if ( is_wp_error( $unzip_result ) ) {
				return $unzip_result;
			}

			// Remove the uploaded zip
			@unlink( $working_dir . "/" . $zip_file );
			if ( $wp_filesystem->is_dir( $working_dir ) ) {
				$wp_filesystem->delete( $working_dir, true );
			}

			return  $upload_path;
		} else {
			return new \WP_Error( 'not-uploaded', __( 'Could not upload file', 'your_textdomain' ) );
		}
	}
}