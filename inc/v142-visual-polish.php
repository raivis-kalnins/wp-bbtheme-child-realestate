<?php
/**
 * WP BBTheme Child Real Estate 3.8.11.42
 *
 * Final visual polish based on the working Woo Events recovery patterns:
 * - deterministic local hero carousel with an Events-style pager;
 * - sharper property photography (no over-processed v136 hero source);
 * - explicit header CTA/search-card/footer alignment and colour ownership.
 */
defined( 'ABSPATH' ) || exit;

/* v142 owns the managed homepage serialization. Prevent v141 from rewriting
 * the stored shortcode back on every admin request. */
remove_action( 'admin_init', 'wpbb_realestate_v141_persist_home', PHP_INT_MAX );
remove_action( 'wp_theme_after_demo_import', 'wpbb_realestate_v141_persist_home', PHP_INT_MAX );
remove_action( 'wp_theme_after_demo_reset', 'wpbb_realestate_v141_persist_home', PHP_INT_MAX );
remove_action( 'wp_theme_demo_reset_complete', 'wpbb_realestate_v141_persist_home', PHP_INT_MAX );
remove_action( 'wp_theme_starter_setup_complete', 'wpbb_realestate_v141_persist_home', PHP_INT_MAX );
remove_filter( 'the_content', 'wpbb_realestate_v141_render_guard', 0 );

if ( ! function_exists( 'wpbb_realestate_v142_hero_markup' ) ) {
    function wpbb_realestate_v142_hero_markup() {
        $profile = function_exists( 'wpbb_realestate_v141_profile' ) ? wpbb_realestate_v141_profile() : array();
        $contact_url = home_url( '/contact/' );

        /* These source files are the clean 1200x900 property masters. They look
         * visibly sharper than the over-processed v136 hero artwork at the
         * right-hand pane size used by this layout. */
        $slides = array(
            array(
                'image'   => 'assets/img/properties/oak-residence.jpg',
                'eyebrow' => __( 'Local property specialists', 'wp-bbtheme-child-realestate' ),
                'title'   => (string) ( $profile['hero_title'] ?? __( 'Find a home with the local context to choose well.', 'wp-bbtheme-child-realestate' ) ),
                'text'    => (string) ( $profile['hero_text'] ?? __( 'Search current homes, compare the details that matter and move from shortlist to viewing with a clear local route.', 'wp-bbtheme-child-realestate' ) ),
            ),
            array(
                'image'   => 'assets/img/properties/harbour-house.jpg',
                'eyebrow' => __( 'Neighbourhood context', 'wp-bbtheme-child-realestate' ),
                'title'   => __( 'See more than the listing before you book a viewing.', 'wp-bbtheme-child-realestate' ),
                'text'    => __( 'Property facts, local context and a direct route to the team stay together so each shortlist is easier to compare.', 'wp-bbtheme-child-realestate' ),
            ),
            array(
                'image'   => 'assets/img/properties/willow-house.jpg',
                'eyebrow' => __( 'Clear next steps', 'wp-bbtheme-child-realestate' ),
                'title'   => __( 'Move from shortlist to viewing with less friction.', 'wp-bbtheme-child-realestate' ),
                'text'    => __( 'Keep pricing, availability, property details and viewing enquiries connected from the first search onwards.', 'wp-bbtheme-child-realestate' ),
            ),
        );

        $html = '<section class="wpbb-re142-hero" data-wpbb-re142-hero aria-roledescription="carousel" aria-label="' . esc_attr__( 'Featured property guidance', 'wp-bbtheme-child-realestate' ) . '">';
        $html .= '<div class="wpbb-re142-hero__viewport">';
        foreach ( $slides as $index => $slide ) {
            $active = 0 === $index;
            $image  = function_exists( 'wpbb_realestate_v141_asset_url' ) ? wpbb_realestate_v141_asset_url( $slide['image'] ) : '';
            $html  .= '<article class="wpbb-re142-hero__slide' . ( $active ? ' is-active' : '' ) . '" data-wpbb-re142-slide="' . esc_attr( (string) $index ) . '" aria-hidden="' . ( $active ? 'false' : 'true' ) . '">';
            if ( $image ) {
                $html .= '<div class="wpbb-re142-hero__media"><img src="' . esc_url( $image ) . '" alt="" width="1200" height="900" loading="' . ( $active ? 'eager' : 'lazy' ) . '" decoding="async"' . ( $active ? ' fetchpriority="high"' : '' ) . '></div>';
            }
            $html .= '<div class="wpbb-re141-shell wpbb-re142-hero__inner"><div class="wpbb-re142-hero__copy">';
            $html .= '<p class="wp-theme-sector-eyebrow">' . esc_html( $slide['eyebrow'] ) . '</p>';
            $heading_tag = $active ? 'h1' : 'h2';
            $heading_id  = $active ? ' id="wpbb-re142-hero-title"' : '';
            $html .= '<' . $heading_tag . $heading_id . '>' . esc_html( $slide['title'] ) . '</' . $heading_tag . '>';
            $html .= '<p class="wpbb-re142-hero__text">' . esc_html( $slide['text'] ) . '</p>';
            $html .= '<div class="wpbb-re142-actions"><a class="btn btn-primary" href="#properties">' . esc_html__( 'Browse properties', 'wp-bbtheme-child-realestate' ) . '</a><a class="wpbb-re142-text-link" href="' . esc_url( $contact_url ) . '">' . esc_html__( 'Book a valuation', 'wp-bbtheme-child-realestate' ) . '</a></div>';
            $html .= '</div></div></article>';
        }
        $html .= '</div><div class="wpbb-re142-hero__pagination" role="group" aria-label="' . esc_attr__( 'Hero slides', 'wp-bbtheme-child-realestate' ) . '">';
        foreach ( $slides as $index => $slide ) {
            $html .= '<button type="button" class="wpbb-re142-hero__bullet' . ( 0 === $index ? ' is-active' : '' ) . '" data-wpbb-re142-go="' . esc_attr( (string) $index ) . '" aria-label="' . esc_attr( sprintf( __( 'Go to hero slide %1$d of %2$d', 'wp-bbtheme-child-realestate' ), $index + 1, count( $slides ) ) ) . '"' . ( 0 === $index ? ' aria-current="true"' : '' ) . '></button>';
        }
        $html .= '</div></section>';
        return $html;
    }
}

if ( ! function_exists( 'wpbb_realestate_v142_home_markup' ) ) {
    function wpbb_realestate_v142_home_markup( $atts = array(), $content = null, $tag = '' ) {
        $base = function_exists( 'wpbb_realestate_v141_home_markup' ) ? (string) wpbb_realestate_v141_home_markup( $atts, $content, $tag ) : '';
        if ( '' === trim( $base ) ) return $base;

        $hero = wpbb_realestate_v142_hero_markup();
        $base = preg_replace(
            '#<section class="wpbb-re141-hero"[^>]*>.*?</section>#s',
            $hero,
            $base,
            1
        );
        $base = str_replace( 'data-wpbb-realestate-home="3.8.11.41"', 'data-wpbb-realestate-home="3.8.11.42"', $base );
        return $base;
    }
}
add_shortcode( 'wpbb_realestate_v142_home', 'wpbb_realestate_v142_home_markup' );

if ( ! function_exists( 'wpbb_realestate_v142_front_content' ) ) {
    function wpbb_realestate_v142_front_content() {
        return '<!-- wp:shortcode -->[wpbb_realestate_v142_home]<!-- /wp:shortcode -->';
    }
}

if ( ! function_exists( 'wpbb_realestate_v142_should_own_front' ) ) {
    function wpbb_realestate_v142_should_own_front( $post_id, $content = '' ) {
        $post_id = absint( $post_id );
        if ( ! $post_id ) return false;
        if ( '1' === (string) get_post_meta( $post_id, '_wp_theme_demo_managed', true ) ) return true;
        if ( '' !== (string) get_post_meta( $post_id, '_wpbb_child_bbuilder_version', true ) ) return true;
        $content = (string) $content;
        return false !== strpos( $content, 'wpbb-v139-home-hero' )
            || false !== strpos( $content, 'wpbb-re141-home' )
            || false !== strpos( $content, '[wpbb_realestate_v141_home]' )
            || false !== strpos( $content, '[wpbb_realestate_v142_home]' );
    }
}

if ( ! function_exists( 'wpbb_realestate_v142_render_guard' ) ) {
    function wpbb_realestate_v142_render_guard( $content ) {
        if ( is_admin() || ! is_front_page() || ! in_the_loop() || ! is_main_query() ) return $content;
        $post_id = get_queried_object_id();
        if ( ! wpbb_realestate_v142_should_own_front( $post_id, (string) $content ) ) return $content;
        $clean = wpbb_realestate_v142_home_markup();
        return '' !== trim( (string) $clean ) ? $clean : $content;
    }
}
add_filter( 'the_content', 'wpbb_realestate_v142_render_guard', 0 );

if ( ! function_exists( 'wpbb_realestate_v142_persist_home' ) ) {
    function wpbb_realestate_v142_persist_home( $page_id = 0, $profile = array() ) {
        if ( ! is_admin() || ! current_user_can( 'manage_options' ) || wp_doing_ajax() ) return;
        $front = absint( get_option( 'page_on_front' ) );
        if ( ! $front ) return;
        $raw = (string) get_post_field( 'post_content', $front, 'raw' );
        if ( ! wpbb_realestate_v142_should_own_front( $front, $raw ) ) return;

        $target  = wpbb_realestate_v142_front_content();
        $version = (string) get_post_meta( $front, '_wpbb_child_bbuilder_version', true );
        if ( '3.8.11.42' !== $version || trim( $raw ) !== trim( $target ) ) {
            wp_update_post( wp_slash( array( 'ID' => $front, 'post_content' => $target ) ) );
            update_post_meta( $front, '_wp_theme_demo_managed', '1' );
            update_post_meta( $front, '_wpbb_child_bbuilder_version', '3.8.11.42' );
            clean_post_cache( $front );
        }
        update_option( 'wpbb_realestate_v142_home_' . sanitize_key( get_stylesheet() ), '3.8.11.42', false );
    }
}
add_action( 'admin_init', 'wpbb_realestate_v142_persist_home', PHP_INT_MAX );
add_action( 'wp_theme_after_demo_import', 'wpbb_realestate_v142_persist_home', PHP_INT_MAX );
add_action( 'wp_theme_after_demo_reset', 'wpbb_realestate_v142_persist_home', PHP_INT_MAX );
add_action( 'wp_theme_demo_reset_complete', 'wpbb_realestate_v142_persist_home', PHP_INT_MAX );
add_action( 'wp_theme_starter_setup_complete', 'wpbb_realestate_v142_persist_home', PHP_INT_MAX );

if ( ! function_exists( 'wpbb_realestate_v142_enqueue' ) ) {
    function wpbb_realestate_v142_enqueue() {
        $dir = get_stylesheet_directory();
        $uri = get_stylesheet_directory_uri();
        $css = '/assets/suite-v142.css';
        $js  = '/assets/suite-v142.js';
        if ( is_readable( $dir . $css ) ) {
            wp_enqueue_style( 'wpbb-suite-v142', $uri . $css, array( 'wpbb-suite-v141' ), (string) filemtime( $dir . $css ) );
        }
        if ( is_readable( $dir . $js ) ) {
            wp_enqueue_script( 'wpbb-suite-v142', $uri . $js, array(), (string) filemtime( $dir . $js ), true );
        }
    }
}
add_action( 'wp_enqueue_scripts', 'wpbb_realestate_v142_enqueue', PHP_INT_MAX );

if ( ! function_exists( 'wpbb_realestate_v142_body_class' ) ) {
    function wpbb_realestate_v142_body_class( $classes ) {
        $classes[] = 'wpbb-v142';
        $classes[] = 'wpbb-v142-theme-realestate';
        return array_values( array_unique( (array) $classes ) );
    }
}
add_filter( 'body_class', 'wpbb_realestate_v142_body_class', PHP_INT_MAX );
