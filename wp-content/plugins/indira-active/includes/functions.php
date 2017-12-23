<?php
/**
 * Misc. helper functions
 *
 * @since 1.0.0
 * @package indira
 */


/**
 * The main function that returns Indira_Active
 *
 * @return object
 * @since 1.0.0
 */
function IA() {
	return Indira_Active::instance();
}


/**
 * Get the current post type
 *
 * @return  string  post type or queried post arg fallback
 * @since   1.0.0
 */
function indira_get_current_post_type() {
	return get_post_type() ?: get_query_var( 'post_type' );
}


/**
 * Get a template part from the plugin
 *
 * @param string  $slug  the slug name for the generic template
 * @param string  $name  the name of the specialized template
 * @param array   $args  arguments passed to indira_load_template
 *
 * @since 1.0.0
 */
function indira_template_part( $slug, $name = null, $args = array() ) {

	// allow edge cases to get in here
	do_action( "get_template_part_{$slug}", $slug, $name );

	// build the path
	$path = IA_PLUGIN_DIR . '/templates/parts/' . $slug;

	// add specialized name
	$path .= $name ? "-$name" : '';

	$path .= '.php';

	// check for the file
	if ( file_exists( $path ) ) {
		indira_load_template( $path, false, $args );
	}
}


/**
 * Load template
 *
 * Modified version of WordPress' load_template function
 * with an added argument to pass variables to the required
 * template part to avoid needing memory-hogging global vars.
 *
 * This implementation is similar to what WooCommerce does.
 *
 * @param string  $_template_file  path to the template file
 * @param bool    $require_once    whether to require_once or require
 * @param array   $args            variables extracted to template part
 *
 * @since 1.0.0
 */
function indira_load_template( $_template_file, $require_once = true, $args = array() ) {

	global $posts, $post, $wp_did_header, $wp_query, $wp_rewrite, $wpdb, $wp_version, $wp, $id, $comment, $user_ID;

	if ( is_array( $wp_query->query_vars ) ) {
		extract( $wp_query->query_vars, EXTR_SKIP );
	}

	// modification
	if ( is_array( $args ) ) {
		extract( $args, EXTR_SKIP );
	}

	if ( isset( $s ) ) {
		$s = esc_attr( $s );
	}

	if ( $require_once ) {
		require_once( $_template_file );
	} else {
		require( $_template_file );
	}
}
