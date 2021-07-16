<?php

require_once ROYAL_WORDPRESS_PLUGIN_PATH . "includes/class-royal-wordpress-plugin-database.php";

/**
 * Add shortcodes to database and filesystem.
 * 
 * Upload the Zip file to temporary folder.
 * Add the shortcode to the database.
 * Unpack Zip content to destination folder.
 *
 * @link       https://clearmedia.pl/
 * @since      1.0.0
 *
 * @package    Royal_Wordpress_Plugin
 * @subpackage Royal_Wordpress_Plugin/admin
 */

class Royal_Wordpress_Plugin_ZIP_Uploader {

	protected $folder = '';
	protected $rwp_db;

	public function __construct($folder) {
		$this->folder = $folder;
		$this->rwp_db = new Royal_Wordpress_Plugin_Database();
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
	 * @param $data
	 *
	 * @return bool|string|true|WP_Error
	 */
	public function upload( $data, $shortcode_id = null ) {
		/** @var $wp_filesystem \WP_Filesystem_Direct */
		global $wp_filesystem;
    
		if ( ! function_exists( 'WP_Filesystem' ) ) {
			include_once 'wp-admin/includes/file.php';
		}
    
		WP_Filesystem();

		$file_error = $data["file"]["error"];

		// Check for Errors
		if ( is_wp_error( $this->check_error( $file_error ) ) ) {
			return $this->check_error( $file_error );
		}
    
		$file_name       = $data["file"]["name"];
		$file_name_arr   = explode( '.', $file_name );
		$extension       = array_pop( $file_name_arr );
		$filename        = implode( '.', $file_name_arr ); // File Name
		$zip_file        = sanitize_title( $filename ) . '.' . $extension; //Our File

		if ( 'zip' !== $extension ) {
			return new WP_Error( 'no-zip', 'This does not seem to be a ZIP file' );
		}

		$temp_name  = $data["file"]["tmp_name"];

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
			$upload_path_temp = $upload_path . '/temp';
			if ( $wp_filesystem->is_dir( $upload_path_temp ) ) {
				$wp_filesystem->delete( $upload_path_temp, true );
			}
			$wp_filesystem->mkdir( $upload_path_temp );
			$unzip_result = unzip_file( $working_dir . "/" . $zip_file, $upload_path_temp );

			if ( is_wp_error( $unzip_result ) ) {
				if ( $wp_filesystem->is_dir( $upload_path_temp ) ) {
					$wp_filesystem->delete( $upload_path_temp, true );
				}
				return $unzip_result;
			} else {
				$is_royal_plugin = false;

				//check if it is Royal script
				foreach(glob($upload_path_temp . '{,*/,*/*/,*/*/*/}*.js', GLOB_BRACE) as $file) {
					if( (strpos(file_get_contents($file), 'mx.core.Application') !== false) ||
						(strpos(file_get_contents($file), 'org.apache.royale.mdl.Application') !== false) || 
						(strpos(file_get_contents($file), 'spark.components.Application') !== false) || 
						(strpos(file_get_contents($file), 'org.apache.royale.core.Application') !== false) || 
						(strpos(file_get_contents($file), 'org.apache.royale.jewel.Application') !== false)) {
						$is_royal_plugin = true;
						break;
					}
				}

				if($is_royal_plugin) {
					$shortcode_name = $data['shortcode-name'];
					$shortcode_description = $data['shortcode-description'];
					$shortcode_id = $shortcode_id ?? $this->rwp_db->insert_db_row( $shortcode_name, $shortcode_description );
					if ( $shortcode_id ) {
						$upload_path_final = $upload_path . "/id-{$shortcode_id}";
						if ( $wp_filesystem->is_dir( $upload_path_final ) ) {
							$wp_filesystem->delete( $upload_path_final, true );
						}
						$wp_filesystem->mkdir( $upload_path_final );
						unzip_file( $working_dir . "/" . $zip_file, $upload_path_final );
						$wp_filesystem->delete( $upload_path_temp, true );
						$result = "[royal_wp_plugin id=\"$shortcode_id\" name=\"$shortcode_name\"]";
					} else {
						if ( $wp_filesystem->is_dir( $upload_path_temp ) ) {
							$wp_filesystem->delete( $upload_path_temp, true );
						}
						$result = new \WP_Error( 'not-uploaded', __( 'Could not write the shortcode to the database', 'royal-wordpress-plugin' ) );
					}
				} else {
					$result = new \WP_Error( 'not-valid', __( 'It isn\'t Royal script', 'royal-wordpress-plugin' ) );
				}
			}

			// Remove the uploaded zip
			@unlink( $working_dir . "/" . $zip_file );
			if ( $wp_filesystem->is_dir( $working_dir ) ) {
				$wp_filesystem->delete( $working_dir, true );
			}

			return  $result;
		} else {
			return new \WP_Error( 'not-uploaded', __( 'Could not upload file', 'royal-wordpress-plugin' ) );
		}
	}
}