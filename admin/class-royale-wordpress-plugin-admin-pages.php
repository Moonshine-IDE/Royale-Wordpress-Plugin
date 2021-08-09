<?php
require_once ROYALE_WORDPRESS_PLUGIN_PATH . "includes/class-royale-wordpress-plugin-database.php";
require_once __DIR__ . '/class-royale-wordpress-plugin-shortcode-list.php';
require_once __DIR__ . '/class-royale-wordpress-plugin-upload-zip-form.php';

/**
 * Defines the admin pages of the plugin.
 *
 * @link       https://clearmedia.pl/
 * @since      1.0.0
 *
 * @package    Royale_Wordpress_Plugin
 * @subpackage Royale_Wordpress_Plugin/admin
 */

/**
 * Defines the admin pages of the plugin.
 *
 * Defines the menu and submenu pages for shortcodes' upload and shortcodes' list
 *
 * @package    Royale_Wordpress_Plugin
 * @subpackage Royale_Wordpress_Plugin/admin
 * @author     Clearmedia <contact@clearmedia.pl>
 */

class Royale_Wordpress_Plugin_Admin_Pages {
	protected $shortcodes_list;

	protected $upload_zip_form;

	/**
	 * Display html with list of shortcodes.
	 *
	 * @since    1.0.0
	 */
	function shortcodes_list_submenu_html() {
		$this->shortcodes_list->display_page();
	}

	/**
	 * Add shortcodes' list submenu to the plugin's menu.
	 *
	 * @since    1.0.0
	 */
	function add_shortcodes_list_submenu() {
		$hook_name = add_submenu_page(
			'royale_wordpress_plugin_menu',
			'List of scripts',
			'List of scripts',
			'edit_posts',
			'royale_wordpress_plugin_menu',
			array($this, 'shortcodes_list_submenu_html')
		);

		add_action( 'load-' . $hook_name, array($this, 'shortcodes_list_submenu_load') );
	}

	/**
	 * Prepare data before shortcodes' list submenu renders.
	 *
	 * @since    1.0.0
	 */
	function shortcodes_list_submenu_load() {
		$this->screen_option();
		$this->shortcodes_list = new Royale_Wordpress_Plugin_Shortcode_List();
		$this->shortcodes_list->prepare_items();
	}

	/**
	 * Add items per page to screen options.
	 *
	 * @since    1.0.0
	 */
	public function screen_option() {
		$option = 'per_page';
		$args   = [
			'label'   => 'Scripts',
			'default' => 5,
			'option'  => 'rwp_scripts_per_page'
		];

		add_screen_option( $option, $args );
	}

	/**
	 * Add plugin's menu to the WordPress'es wp-admin.
	 *
	 * @since    1.0.0
	 */
	function add_plugin_menu() {
		add_menu_page(
			'Apache Royale® Apps',
			'Apache Royale® Apps',
			'edit_posts',
			'royale_wordpress_plugin_menu',
		);
	}

	/**
	 * Display html with upload zip form.
	 *
	 * @since    1.0.0
	 */
	public function shortcodes_upload_submenu_html() {
		$this->upload_zip_form->display_page();
	}

	/**
	 * Add 'upload a new shortcode' submenu to the plugin's menu.
	 *
	 * @since    1.0.0
	 */
	public function add_shortcodes_upload_submenu() {
		$hook_name = add_submenu_page(
			'royale_wordpress_plugin_menu',
			'Upload a new script',
			'Upload a new script',
			'activate_plugins',
			'upload_a_new_script',
			array($this, 'shortcodes_upload_submenu_html')
		);

		add_action( 'load-' . $hook_name, array($this, 'shortcodes_upload_submenu_load') );
	}

	/**
	 * Prepare data before 'upload a new shortcode' submenu renders.
	 *
	 * @since    1.0.0
	 */
	function shortcodes_upload_submenu_load() {
		$this->upload_zip_form = new Royale_Wordpress_Plugin_Upload_ZIP_Form();
		$this->upload_zip_form->load();
	}
}
