<?php
/**
 * WP BBTheme Child Real Estate 3.8.11.40 - Events-parity layout owner.
 *
 * Woo Events solved the same hero/grid regressions by keeping suite-v118 as
 * the proven frontend base and retiring the later emergency geometry layers.
 * Real Estate now uses that exact ownership model: v118 remains the stable
 * foundation and v140 is the only post-v118 layout/runtime owner.
 */
defined( 'ABSPATH' ) || exit;

/* Retire generic emergency enqueue/body owners before enqueue time. */
foreach ( range( 119, 136 ) as $wpbb_re_v140_retired_version ) {
    $wpbb_re_v140_enqueue_callback = 'wpbb_child_v' . $wpbb_re_v140_retired_version . '_enqueue';
    $wpbb_re_v140_body_callback    = 'wpbb_child_v' . $wpbb_re_v140_retired_version . '_body_class';
    remove_action( 'wp_enqueue_scripts', $wpbb_re_v140_enqueue_callback, PHP_INT_MAX );
    remove_action( 'wp_enqueue_scripts', $wpbb_re_v140_enqueue_callback, 999 );
    remove_filter( 'body_class', $wpbb_re_v140_body_callback, PHP_INT_MAX );
}
unset( $wpbb_re_v140_retired_version, $wpbb_re_v140_enqueue_callback, $wpbb_re_v140_body_callback );

/* Real Estate v137-v139 used theme-specific callback names. Keep their data,
 * content repair and image support, but retire their competing visual owners. */
foreach ( array( 137, 138, 139 ) as $wpbb_re_v140_custom_version ) {
    remove_action( 'wp_enqueue_scripts', 'wpbb_realestate_v' . $wpbb_re_v140_custom_version . '_enqueue', PHP_INT_MAX );
    remove_action( 'wp_enqueue_scripts', 'wpbb_realestate_v' . $wpbb_re_v140_custom_version . '_enqueue', 999 );
    remove_filter( 'body_class', 'wpbb_realestate_v' . $wpbb_re_v140_custom_version . '_body_class', PHP_INT_MAX );
}
unset( $wpbb_re_v140_custom_version );

if ( ! function_exists( 'wpbb_realestate_v140_enqueue' ) ) {
    function wpbb_realestate_v140_enqueue() {
        $dir = get_stylesheet_directory();
        $uri = get_stylesheet_directory_uri();

        /* Mirror the working Woo Events 3.8.11.40 recovery. The post-v118
         * files were incremental emergency layers and must not stack. */
        foreach ( range( 119, 139 ) as $n ) {
            $handle = 'wpbb-suite-v' . $n;
            wp_dequeue_style( $handle );
            wp_deregister_style( $handle );
            wp_dequeue_script( $handle );
            wp_deregister_script( $handle );
        }

        $base_css = $dir . '/assets/suite-v118.css';
        $base_js  = $dir . '/assets/suite-v118.js';
        if ( is_readable( $base_css ) ) {
            wp_enqueue_style( 'wpbb-suite-v118', $uri . '/assets/suite-v118.css', array(), (string) filemtime( $base_css ) );
        }
        if ( is_readable( $base_js ) ) {
            wp_enqueue_script( 'wpbb-suite-v118', $uri . '/assets/suite-v118.js', array(), (string) filemtime( $base_js ), false );
        }

        $css = $dir . '/assets/suite-v140.css';
        $js  = $dir . '/assets/suite-v140.js';
        if ( is_readable( $css ) ) {
            wp_enqueue_style(
                'wpbb-suite-v140',
                $uri . '/assets/suite-v140.css',
                is_readable( $base_css ) ? array( 'wpbb-suite-v118' ) : array(),
                (string) filemtime( $css )
            );
        }
        if ( is_readable( $js ) ) {
            wp_enqueue_script(
                'wpbb-suite-v140',
                $uri . '/assets/suite-v140.js',
                is_readable( $base_js ) ? array( 'wpbb-suite-v118' ) : array(),
                (string) filemtime( $js ),
                true
            );
            wp_add_inline_script(
                'wpbb-suite-v140',
                'window.wpbbRealestateV140=' . wp_json_encode(
                    array(
                        'version'   => '3.8.11.40',
                        'themeSlug' => basename( $dir ),
                    ),
                    JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
                ) . ';',
                'before'
            );
        }
    }
}
add_action( 'wp_enqueue_scripts', 'wpbb_realestate_v140_enqueue', PHP_INT_MAX );

if ( ! function_exists( 'wpbb_realestate_v140_body_class' ) ) {
    function wpbb_realestate_v140_body_class( $classes ) {
        $classes[] = 'wpbb-v140';
        $classes[] = 'wpbb-v140-theme-realestate';
        return array_values( array_unique( $classes ) );
    }
}
add_filter( 'body_class', 'wpbb_realestate_v140_body_class', PHP_INT_MAX );
