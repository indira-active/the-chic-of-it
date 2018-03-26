<?php
/**
 * Indira Active
 *
 * @package     indira
 * @author      Jordan Pakrosnis
 * @copyright   2017 Indira Active
 *
 * @wordpress-plugin
 * Plugin Name: Indira Active
 * Plugin URI:  https://www.indiraactive.com/
 * Description: Child theme customizations and other non-theme functionality for Indira Active.
 * Version:     1.0.0
 * Author:      Jordan Pakrosnis
 * Author URI:  https://jordanpak.com
 * Text Domain: indira
 */

// exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Main Indira Active class
 *
 * @since 1.0.0
 */
final class Indira_Active {

	/**
	 * The one/only instance of Indira_Active
	 *
	 * @var object
	 * @since 1.0.0
	 */
	private static $instance;


	/**
	 * Plugin version for enqueueing, etc.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	public $version = '1.0.0';


	/**
	 * Main Indira Active instance
	 *
	 * @return Indira_Active
	 * @since 1.0.0
	 */
	public static function instance() {

		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Indira_Active ) ) {

			self::$instance = new Indira_Active;
			self::$instance->constants();
			self::$instance->includes();

			add_action( 'wp_enqueue_scripts', array( self::$instance, 'enqueue_scripts'         ), 20     );
			add_filter( 'single_template'   , array( self::$instance, 'maybe_override_template' ), 100, 1 );

			register_activation_hook(   IA_PLUGIN_FILE, array( self::$instance, 'plugin_activate'   ) );
			register_deactivation_hook( IA_PLUGIN_FILE, array( self::$instance, 'plugin_deactivate' ) );
		}

		return self::$instance;
	}


	/**
	 * Setup plugin constants
	 *
	 * @since 1.0.0
	 */
	private function constants() {

		define( 'IA_VERSION'        , $this->version                                    );
		define( 'IA_PLUGIN_DIR'     , plugin_dir_path( __FILE__ )                       );
		define( 'IA_PLUGIN_URL'     , plugin_dir_url( __FILE__ )                        );
		define( 'IA_PLUGIN_FILE'    , __FILE__                                          );
		define( 'IA_T_PREFIX'       , 'indira_'                                        );
		define( 'IA_DOING_AUTOSAVE' , ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) );
		define( 'IA_DOING_AJAX'     , ( defined( 'DOING_AJAX' ) && DOING_AJAX )         );
	}


	/**
	 * Include required files
	 *
	 * @since 1.0.0
	 */
	private function includes() {

		// load every time
		$includes = array(
			'class-tracking',
			'class-header',
			'class-footer',
			'functions',
		);

		// include stuff
		foreach ( $includes as $include ) {
			require_once IA_PLUGIN_DIR . 'includes/' . $include . '.php';
		}
	}


	/**
	 * Do plugin activation stuff
	 *
	 * @since 1.0.0
	 */
	public function plugin_activate() {
		do_action( 'indira_activate' );
		flush_rewrite_rules();
	}


	/**
	 * Do plugin deactivation stuff
	 *
	 * @since 1.0.0
	 */
	public function plugin_deactivate() {
		do_action( 'indira_deactivate' );
		flush_rewrite_rules();
	}


	/**
	 * Maybe override template
	 *
	 * Check if a template file exists in the plugin and override the theme if
	 * it does.
	 *
	 * @param  string  $template  the queried template path
	 * @return string  $template  modified template path (if found)
	 *
	 * @since 1.0.0
	 */
	public function maybe_override_template( $template ) {

		$path = IA_PLUGIN_DIR . 'templates/';

		$post_type = get_post_type() ?: get_query_var( 'post_type' );

		$path .= ( is_archive() ? 'archive' : 'single' ) . '-' . $post_type . '.php';

		return file_exists( $path ) ? $path : $template;
	}


	/**
	 * Enqueue assets
	 *
	 * @since 1.0.0
	 */
	public function enqueue_scripts() {

		// kill Refined theme stuff
		wp_dequeue_style( 'google-font' );


		$assets    = IA_PLUGIN_URL . 'assets/';
		$post_type = indira_get_current_post_type();

		// Google Fonts
		wp_enqueue_style( 'indira-google-fonts', 'https://fonts.googleapis.com/css?family=Open+Sans:300,300i', array(), '' );

		// main custom styles
		wp_enqueue_style( 'indira-styles', $assets . 'css/style.min.css', array(), '', 'all' );

		// scripts
		// wp_enqueue_script( 'indira-scripts', $assets . 'js/foot.min.js', array( 'jquery' ), false, true );
	}
}


/**
 * The main function that returns Indira_Active
 *
 * @return object
 * @since 1.0.0
 */
function IA() {
	return Indira_Active::instance();
}

// SPINSIES
IA();
