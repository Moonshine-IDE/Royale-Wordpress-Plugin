<?php
require_once ROYAL_WORDPRESS_PLUGIN_PATH . "includes/class-royal-wordpress-plugin-database.php";
require_once __DIR__ . '/class-royal-wordpress-plugin-shortcode-list.php';
require_once __DIR__ . '/class-royal-wordpress-plugin-upload-zip-form.php';

/**
 * Defines the admin pages of the plugin.
 *
 * @link       https://clearmedia.pl/
 * @since      1.0.0
 *
 * @package    Royal_Wordpress_Plugin
 * @subpackage Royal_Wordpress_Plugin/admin
 */

/**
 * Defines the admin pages of the plugin.
 *
 * Defines the menu and submenu pages for shortcodes' upload and shortcodes' list
 *
 * @package    Royal_Wordpress_Plugin
 * @subpackage Royal_Wordpress_Plugin/admin
 * @author     Clearmedia <contact@clearmedia.pl>
 */

class Royal_Wordpress_Plugin_Admin_Pages {
	/**
	 * Display html with list of shortcodes.
	 *
	 * @since    1.0.0
	 */
	function shortcodes_list_submenu_html() {
		$shortcodes_list = new Royal_Wordpress_Plugin_Shortcode_List();
		$shortcodes_list->display_page();
	}

	/**
	 * Add shortcodes' list submenu to the plugin's menu.
	 *
	 * @since    1.0.0
	 */
	function add_shortcodes_list_submenu() {
		add_submenu_page(
			'royal_wordpress_plugin_menu',
			'List of shortcodes',
			'List of shortcodes',
			'edit_posts',
			'royal_wordpress_plugin_menu',
			array($this, 'shortcodes_list_submenu_html')
		);
	}

	/**
	 * Add plugin's menu to the WordPress'es wp-admin.
	 *
	 * @since    1.0.0
	 */
	function add_plugin_menu() {
		add_menu_page(
			'Royal Wordpress Plugin',
			'Royal Wordpress Plugin',
			'edit_posts',
			'royal_wordpress_plugin_menu',
		);
	}

	/**
	 * Display html with upload zip form.
	 *
	 * @since    1.0.0
	 */
	public function form() {
		$upload_zip_form = new Royal_Wordpress_Plugin_Upload_ZIP_Form();
		$upload_zip_form->display_page();
	}

	/**
	 * Add 'upload a new shortcode' submenu to the plugin's menu.
	 *
	 * @since    1.0.0
	 */
	public function add_shortcodes_upload_submenu() {
		add_submenu_page(
			'royal_wordpress_plugin_menu',
			'Upload a new shortcode',
			'Upload a new shortcode',
			'activate_plugins',
			'zip_upload_form',
			array($this, 'form')
		);
	}
}
