<?php

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
		add_action( 'admin_menu', array( $this, 'add_shortcodes_submenu' ) );
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
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
		 * This function is provided for demonstration purposes only.
		 *
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
	 * Get folders containing shortcodes' code.
	 *
	 * @since    1.0.0
	 */
	private function get_shortcode_names() {
		$shortcodes_folder_path = ROYAL_WORDPRESS_PLUGIN_PATH . "shortcodes-files";
		$shortcodes_folder_content =glob("{$shortcodes_folder_path}/*", GLOB_ONLYDIR);
		$shortcodes = [];
		foreach ($shortcodes_folder_content as $shortcode_dir_path) {
			if ( file_exists("{$shortcode_dir_path}/index.html") ) {
				$shortcodes[] = basename($shortcode_dir_path);
			}
		}
		return $shortcodes;
	}


	/**
	 * Display html with list of shortcodes.
	 *
	 * @since    1.0.0
	 */
	function shortcodes_submenu_html() {
		$shortcode_names = $this->get_shortcode_names();
		require __DIR__ . '/partials/shortcodes-list.php';
	}

	/**
	 * Add submenu to the WordPress'es wp-admin tools menu.
	 *
	 * @since    1.0.0
	 */
	function add_shortcodes_submenu() {
		add_submenu_page(
			'tools.php',
			'RWP list of shortcodes',
			'RWP list of shortcodes',
			'edit_posts',
			'rwp_shortcode_list',
			array($this, 'shortcodes_submenu_html')
		);
	}
}
