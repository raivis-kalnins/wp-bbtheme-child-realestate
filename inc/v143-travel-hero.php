<?php
/**
 * WP BBTheme Child Real Estate 3.8.11.43
 * Travel-theme hero parity: full photographic pane, real horizontal slider,
 * compact pager and isolated runtime ownership.
 */
defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'wpbb_realestate_v143_enqueue' ) ) {
    function wpbb_realestate_v143_enqueue() {
        $dir = get_stylesheet_directory();
        $uri = get_stylesheet_directory_uri();

        /* v143 replaces only the v142 carousel runtime. Keep v142 CSS because
         * it also owns the finder, card and footer polish from the prior fix. */
        wp_dequeue_script( 'wpbb-suite-v142' );
        wp_deregister_script( 'wpbb-suite-v142' );

        $css = '/assets/suite-v143.css';
        $js  = '/assets/suite-v143.js';

        if ( is_readable( $dir . $css ) ) {
            wp_enqueue_style(
                'wpbb-suite-v143',
                $uri . $css,
                array( 'wpbb-suite-v142' ),
                (string) filemtime( $dir . $css )
            );
        }
        if ( is_readable( $dir . $js ) ) {
            wp_enqueue_script(
                'wpbb-suite-v143',
                $uri . $js,
                array(),
                (string) filemtime( $dir . $js ),
                true
            );
        }
    }
}
add_action( 'wp_enqueue_scripts', 'wpbb_realestate_v143_enqueue', PHP_INT_MAX );

if ( ! function_exists( 'wpbb_realestate_v143_body_class' ) ) {
    function wpbb_realestate_v143_body_class( $classes ) {
        $classes[] = 'wpbb-v143';
        $classes[] = 'wpbb-v143-theme-realestate';
        return array_values( array_unique( (array) $classes ) );
    }
}
add_filter( 'body_class', 'wpbb_realestate_v143_body_class', PHP_INT_MAX );
