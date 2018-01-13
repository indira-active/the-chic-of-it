<?php

// exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Theme footer adjustments
 *
 * @since 1.0.0
 * @package indira
 */
class IA_Footer {

	/**
	 * Get things going
	 *
	 * @since 1.0.0
	 */
	function __construct() {
        add_action( 'init'                      , array( __CLASS__ , 'remove_filters'   ) );
        add_action( 'genesis_footer_creds_text' , array( __CLASS__ , 'add_footer_creds' ) );
	}


	/**
	 * Misc filter removals
	 *
	 * @since 1.0.0
	 */
	public static function remove_filters() {
        remove_filter( 'genesis_footer_creds_text', 'refined_footer_creds_text' );
    }
    

    /**
     * Add custom footer creds text
     * 
     * @since 1.0.0
     */
    public static function add_footer_creds( $creds ) {
        return '<div class="creds">[footer_copyright] ' . __( 'Indira Active', 'indira' ) . '</div>';
    }
}

new IA_Footer;
