<?php

// exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Theme header adjustments
 *
 * @since 1.0.0
 * @package indira
 */
class IA_Header {

	/**
	 * Get things going
	 *
	 * @since 1.0.0
	 */
	function __construct() {
		add_action( 'init', array( __CLASS__ , 'init' ) );
	}


	/**
	 * Fire off misc filters/actions that need to happen on init
	 *
	 * Stuff like deleting the topbar's search
	 *
	 * @since 1.0.0
	 */
	public static function init() {
		add_action(    'genesis_site_description' , 'genesis_seo_site_description'     );
		remove_filter( 'wp_nav_menu_items'        , 'refined_primary_nav_extras'  , 10 );
	}
}

new IA_Header;


