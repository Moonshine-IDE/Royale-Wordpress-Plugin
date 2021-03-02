<?php
require_once __DIR__ . '/class-royal-wordpress-plugin-admin-pages.php';

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://clearmedia.pl/
 * @since      1.0.0
 *
 * @package    Royal_Wordpress_Plugin
 * @subpackage Royal_Wordpress_Plugin/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Royal_Wordpress_Plugin
 * @subpackage Royal_Wordpress_Plugin/admin
 * @author     Clearmedia <contact@clearmedia.pl>
 */

class Royal_Wordpress_Plugin_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * An instance of this class should be passed to the run() function
		 * defined in Royal_Wordpress_Plugin_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Royal_Wordpress_Plugin_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/royal-wordpress-plugin-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * An instance of this class should be passed to the run() function
		 * defined in Royal_Wordpress_Plugin_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Royal_Wordpress_Plugin_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/royal-wordpress-plugin-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Register the Admin menu and submenus pages.
	 *
	 * @since    1.0.0
	 */
	public function add_admin_pages() {
		$admin_pages = new Royal_Wordpress_Plugin_Admin_Pages();
		$admin_pages->add_plugin_menu();
		$admin_pages->add_shortcodes_list_submenu();
		$admin_pages->add_shortcodes_upload_submenu();
	}
}
