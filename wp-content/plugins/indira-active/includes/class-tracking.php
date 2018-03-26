<?php

// exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Tracking scripts
 *
 * @since 1.0.0
 * @package indira
 */
class IA_Tracking {

	/**
	 * Get things going
	 *
	 * @since 1.0.0
	 */
	function __construct() {
        add_action( 'wp_head'   , array( __CLASS__ , 'add_head' ) );
        add_action( 'wp_footer' , array( __CLASS__ , 'add_foot' ) );
    }


	/**
	 * Add <head> scripts
	 *
	 * @since 1.0.0
	 */
	public static function add_head() { ?>

        <!-- Google Tag Manager -->
        <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
        new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
        j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
        'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-WHKQ7X8');</script>
        <!-- End Google Tag Manager -->

    <?php }


	/**
	 * Add pre-</body> scripts
	 *
	 * @since 1.0.0
	 */
	public static function add_foot() { ?>

        <!-- Google Tag Manager (noscript) -->
        <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-WHKQ7X8"
        height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
        <!-- End Google Tag Manager (noscript) -->

	<?php }
}

new IA_Tracking;
