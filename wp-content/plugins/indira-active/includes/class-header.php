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
		add_action( 'init', array( __CLASS__ , 'remove_filters' ) );
	}


	/**
	 * Misc filter removals
	 *
	 * Stuff like deleting the topbar's search
	 *
	 * @since 1.0.0
	 */
	public static function remove_filters() {
		remove_filter( 'wp_nav_menu_items', 'refined_primary_nav_extras', 10 );
	}
}

new IA_Header;


