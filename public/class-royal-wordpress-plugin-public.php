<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://clearmedia.pl/
 * @since      1.0.0
 *
 * @package    Royal_Wordpress_Plugin
 * @subpackage Royal_Wordpress_Plugin/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Royal_Wordpress_Plugin
 * @subpackage Royal_Wordpress_Plugin/public
 * @author     Clearmedia <contact@clearmedia.pl>
 */
class Royal_Wordpress_Plugin_Public {

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
		$this->add_shortcodes();

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/royal-wordpress-plugin-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/royal-wordpress-plugin-public.js', array( 'jquery' ), $this->version, false );

	}

	public function shortcode_handler( $atts ) {
		/**
		 * The shortcode handler for plugin shortcode registered with WordPress.
		 *
		 * @since    1.0.0
		 * @access   protected
		 * @var      array    $atts    Attributes used with the shortcode.
		 */

		if ( !isset( $atts['name']) ) {
			return;
		}
		$rwp_plugin_shortcode_url = ROYAL_WORDPRESS_PLUGIN_URL . "shortcodes-files/{$atts['name']}/index.html";
		ob_start();
		require __DIR__ . '/partials/shortcode-iframe.php';
		return ob_get_clean();
	}

	private function add_shortcodes() {
		/**
		 * This function registers the shortcode with WordPress.
		 *
		 * 
		 */

		$callback = array($this, 'shortcode_handler');
		add_shortcode( 'royal_wp_plugin', $callback );
	}
}
