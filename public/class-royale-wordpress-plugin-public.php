<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://clearmedia.pl/
 * @since      1.0.0
 *
 * @package    Royale_Wordpress_Plugin
 * @subpackage Royale_Wordpress_Plugin/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Royale_Wordpress_Plugin
 * @subpackage Royale_Wordpress_Plugin/public
 * @author     Clearmedia <contact@clearmedia.pl>
 */
class Royale_Wordpress_Plugin_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->register_shortcodes();

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * An instance of this class should be passed to the run() function
		 * defined in Royale_Wordpress_Plugin_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Royale_Wordpress_Plugin_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/royale-wordpress-plugin-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * An instance of this class should be passed to the run() function
		 * defined in Royale_Wordpress_Plugin_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Royale_Wordpress_Plugin_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/royale-wordpress-plugin-public.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * The shortcode handler for plugin shortcode registered with WordPress.
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      array    $atts    Attributes used with the shortcode.
	 * 
	 * @return string
	 */
	public function shortcode_handler( $atts ) {
		if ( !isset( $atts['id']) ) {
			return;
		}
		$shortcode_path = ROYALE_WORDPRESS_PLUGIN_PATH . "shortcodes-files/id-{$atts['id']}";
		$shortcode_folder_subfolders = glob("{$shortcode_path}/*", GLOB_ONLYDIR);
		if ( !empty( $shortcode_folder_subfolders[0] ) ) {
			$shortcode_folder_subfolder = basename($shortcode_folder_subfolders[0]);
		} else {
			$shortcode_folder_subfolder = false;
		}
		if ( file_exists ( "{$shortcode_path}/index.html" ) ) {
			$shortcode_url = ROYALE_WORDPRESS_PLUGIN_URL . "shortcodes-files/id-{$atts['id']}/index.html";
		} else if ( $shortcode_folder_subfolder ) {
			$shortcode_url = ROYALE_WORDPRESS_PLUGIN_URL . "shortcodes-files/id-{$atts['id']}/$shortcode_folder_subfolder/index.html";
		} else {
			$shortcode_name_attr = '';
			if ( !empty( $atts['name'] ) ) {
				$shortcode_name_attr = " name=\"{$atts['name']}\"";
			}
			return "[royale_wp_plugin id=\"{$atts['id']}\"{$shortcode_name_attr}]";
		}
		ob_start();
		require __DIR__ . '/partials/shortcode-iframe.php';
		return ob_get_clean();
	}

	/**
	 * This function registers the shortcode with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 * 
	 * @return void
	 */
	private function register_shortcodes() {
		$callback = array($this, 'shortcode_handler');
		add_shortcode( 'royale_wp_plugin', $callback );
	}
}
