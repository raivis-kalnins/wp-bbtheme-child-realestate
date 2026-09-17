<?php
/**
 * WP BBTheme Child Real Estate 3.8.11.37 — uniform section alignment, grids and media quality.
 */
defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'wpbb_realestate_v137_enqueue' ) ) {
    function wpbb_realestate_v137_enqueue() {
        $dir = get_stylesheet_directory();
        $uri = get_stylesheet_directory_uri();
        $css = '/assets/suite-v137.css';
        $js  = '/assets/suite-v137.js';

        wp_enqueue_style(
            'wpbb-suite-v137',
            $uri . $css,
            array( 'wpbb-suite-v136' ),
            is_file( $dir . $css ) ? (string) filemtime( $dir . $css ) : '3.8.11.37'
        );
        wp_enqueue_script(
            'wpbb-suite-v137',
            $uri . $js,
            array( 'wpbb-suite-v136' ),
            is_file( $dir . $js ) ? (string) filemtime( $dir . $js ) : '3.8.11.37',
            true
        );
    }
}
add_action( 'wp_enqueue_scripts', 'wpbb_realestate_v137_enqueue', PHP_INT_MAX );

if ( ! function_exists( 'wpbb_realestate_v137_body_class' ) ) {
    function wpbb_realestate_v137_body_class( $classes ) {
        $classes[] = 'wpbb-v137';
        $classes[] = 'wpbb-v137-theme-realestate';
        return array_values( array_unique( $classes ) );
    }
}
add_filter( 'body_class', 'wpbb_realestate_v137_body_class', PHP_INT_MAX );

/**
 * Generate a consistent high-quality 4:3 card crop for newly uploaded property media.
 * Existing smaller demo sources continue to fall back to their original image safely.
 */
if ( ! function_exists( 'wpbb_realestate_v137_image_support' ) ) {
    function wpbb_realestate_v137_image_support() {
        add_image_size( 'wpbb-realestate-card-xl', 1200, 900, true );
    }
}
add_action( 'after_setup_theme', 'wpbb_realestate_v137_image_support', 30 );

if ( ! function_exists( 'wpbb_realestate_v137_image_quality' ) ) {
    function wpbb_realestate_v137_image_quality( $quality, $mime_type = '' ) {
        if ( in_array( $mime_type, array( 'image/jpeg', 'image/webp', 'image/avif' ), true ) ) {
            return 90;
        }
        return $quality;
    }
}
add_filter( 'wp_editor_set_quality', 'wpbb_realestate_v137_image_quality', 30, 2 );
