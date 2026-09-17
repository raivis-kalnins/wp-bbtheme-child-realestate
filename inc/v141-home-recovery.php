<?php
/**
 * WP BBTheme Child Real Estate 3.8.11.41
 *
 * Deterministic homepage recovery after the v140 regression.
 *
 * v139 already proved that Real Estate must have one visual owner. v140
 * accidentally retired that owner and left older suite layers (v98-v118)
 * active together. This release returns to the isolated v139 foundation and
 * removes the remaining fragile dynamic homepage blocks: the hero, service
 * cards, journey cards, proof cards, CTA and process cards are rendered as
 * ordinary semantic HTML. Property search stays dynamic and uses the existing
 * Real Estate shortcode/data model.
 */
defined( 'ABSPATH' ) || exit;

/* Never let the generic demo rebuilders overwrite the Real Estate homepage on
 * a routine wp-admin visit. Explicit demo import/reset hooks are handled by the
 * v141 persistence pass below. */
remove_action( 'admin_init', 'wpbb_child_v62_rebuild_demo_pages', 80 );
remove_action( 'admin_init', 'wpbb_child_v118_repair_once', PHP_INT_MAX );
remove_action( 'wp_theme_after_demo_import', 'wpbb_child_v118_after_demo_import', PHP_INT_MAX );
remove_action( 'wp_theme_after_demo_reset', 'wpbb_child_v118_after_demo_import', PHP_INT_MAX );
remove_action( 'wp_theme_demo_reset_complete', 'wpbb_child_v118_after_demo_import', PHP_INT_MAX );
remove_action( 'wp_theme_starter_setup_complete', 'wpbb_child_v118_after_demo_import', PHP_INT_MAX );
remove_filter( 'wp_insert_post_data', 'wpbb_child_v118_filter_post_data', PHP_INT_MAX );

/* v141 owns the front-page serialization, so do not persist the superseded
 * v138/v139 home builders. Their property-media/post repair helpers remain
 * available and are reused below. */
remove_action( 'admin_init', 'wpbb_realestate_v138_repair_demo_once', PHP_INT_MAX );
remove_action( 'admin_init', 'wpbb_realestate_v139_persist_repair', PHP_INT_MAX );
remove_action( 'wp_theme_after_demo_import', 'wpbb_realestate_v139_persist_repair', PHP_INT_MAX );
remove_filter( 'the_content', 'wpbb_realestate_v138_render_guard', 1 );
remove_filter( 'the_content', 'wpbb_realestate_v139_render_guard', 0 );

if ( ! function_exists( 'wpbb_realestate_v141_asset_url' ) ) {
    function wpbb_realestate_v141_asset_url( $relative ) {
        $relative = ltrim( str_replace( '\\', '/', (string) $relative ), '/' );
        if ( '' === $relative || false !== strpos( $relative, '../' ) ) return '';
        $path = get_stylesheet_directory() . '/' . $relative;
        if ( ! is_readable( $path ) ) return '';
        return add_query_arg(
            'v',
            (string) filemtime( $path ),
            trailingslashit( get_stylesheet_directory_uri() ) . $relative
        );
    }
}

if ( ! function_exists( 'wpbb_realestate_v141_profile' ) ) {
    function wpbb_realestate_v141_profile() {
        if ( function_exists( 'wpbb_realestate_v139_profile' ) ) {
            return (array) wpbb_realestate_v139_profile( array() );
        }
        if ( function_exists( 'wpbb_realestate_demo_profile' ) ) {
            return (array) wpbb_realestate_demo_profile( array() );
        }
        return array();
    }
}

if ( ! function_exists( 'wpbb_realestate_v141_icon' ) ) {
    function wpbb_realestate_v141_icon( $title, $text = '' ) {
        if ( function_exists( 'wpbb_child_v62_svg_icon' ) ) {
            return (string) wpbb_child_v62_svg_icon( (string) $title . ' ' . (string) $text );
        }
        return '<svg viewBox="0 0 24 24" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 11.5 12 5l8 6.5"/><path d="M6.5 10.5V20h11v-9.5"/></svg>';
    }
}

if ( ! function_exists( 'wpbb_realestate_v141_heading' ) ) {
    function wpbb_realestate_v141_heading( $eyebrow, $heading ) {
        $html = '<div class="wpbb-re141-heading">';
        if ( '' !== trim( (string) $eyebrow ) ) {
            $html .= '<p class="wp-theme-sector-eyebrow">' . esc_html( $eyebrow ) . '</p>';
        }
        $html .= '<h2>' . esc_html( $heading ) . '</h2></div>';
        return $html;
    }
}

if ( ! function_exists( 'wpbb_realestate_v141_cards' ) ) {
    function wpbb_realestate_v141_cards( $items, $columns = 3, $extra_class = '' ) {
        $items = array_values( array_filter( (array) $items, 'is_array' ) );
        if ( ! $items ) return '';
        $columns = max( 1, min( 4, absint( $columns ) ) );
        $html = '<div class="wpbb-re141-grid wpbb-re141-grid--' . esc_attr( $columns ) . ' ' . esc_attr( $extra_class ) . '">';
        foreach ( $items as $item ) {
            $title = (string) ( $item[0] ?? '' );
            $text  = (string) ( $item[1] ?? '' );
            $html .= '<article class="wpbb-re141-card">';
            $html .= '<span class="wpbb-re141-card__icon" aria-hidden="true">' . wpbb_realestate_v141_icon( $title, $text ) . '</span>';
            $html .= '<h3>' . esc_html( $title ) . '</h3>';
            if ( '' !== trim( $text ) ) $html .= '<p>' . esc_html( $text ) . '</p>';
            $html .= '</article>';
        }
        return $html . '</div>';
    }
}

if ( ! function_exists( 'wpbb_realestate_v141_stats' ) ) {
    function wpbb_realestate_v141_stats( $items, $columns = 4, $extra_class = '' ) {
        $items = array_values( array_filter( (array) $items, 'is_array' ) );
        if ( ! $items ) return '';
        $columns = max( 1, min( 4, absint( $columns ) ) );
        $html = '<div class="wpbb-re141-stats wpbb-re141-stats--' . esc_attr( $columns ) . ' ' . esc_attr( $extra_class ) . '">';
        foreach ( $items as $item ) {
            $html .= '<div class="wpbb-re141-stat"><strong>' . esc_html( (string) ( $item[0] ?? '' ) ) . '</strong><span>' . esc_html( (string) ( $item[1] ?? '' ) ) . '</span></div>';
        }
        return $html . '</div>';
    }
}

if ( ! function_exists( 'wpbb_realestate_v141_home_markup' ) ) {
    function wpbb_realestate_v141_home_markup( $atts = array(), $content = null, $tag = '' ) {
        $profile = wpbb_realestate_v141_profile();
        $hero = wpbb_realestate_v141_asset_url( 'assets/img/hero-v136/slide-1.jpg' );
        $about = wpbb_realestate_v141_asset_url( 'assets/img/properties/park-view.jpg' );
        $gallery = array(
            array( 'assets/img/properties/willow-house.jpg', __( 'Willow House', 'wp-bbtheme-child-realestate' ) ),
            array( 'assets/img/properties/harbour-house.jpg', __( 'Riverside homes', 'wp-bbtheme-child-realestate' ) ),
            array( 'assets/img/properties/the-glassworks.jpg', __( 'The Glassworks', 'wp-bbtheme-child-realestate' ) ),
        );
        $contact_url = home_url( '/contact/' );

        $services = (array) ( $profile['services'] ?? array() );
        $journeys = (array) ( $profile['industries'] ?? array() );
        $process  = array();
        foreach ( (array) ( $profile['process'] ?? array() ) as $step ) {
            $process[] = array(
                trim( (string) ( $step[0] ?? '' ) . ' ' . (string) ( $step[1] ?? '' ) ),
                (string) ( $step[2] ?? '' ),
            );
        }

        $areas = array(
            array( __( 'North Quarter', 'wp-bbtheme-child-realestate' ), __( 'Period homes, independent shops and straightforward transport links.', 'wp-bbtheme-child-realestate' ) ),
            array( __( 'Riverside', 'wp-bbtheme-child-realestate' ), __( 'Apartments, waterside walks and quick access to the city.', 'wp-bbtheme-child-realestate' ) ),
            array( __( 'Old Village', 'wp-bbtheme-child-realestate' ), __( 'Character homes, schools and established neighbourhood streets.', 'wp-bbtheme-child-realestate' ) ),
        );
        $local_proof = array(
            array( '12', __( 'local areas covered', 'wp-bbtheme-child-realestate' ) ),
            array( '96%', __( 'asking price achieved*', 'wp-bbtheme-child-realestate' ) ),
            array( '4.9/5', __( 'demo client rating', 'wp-bbtheme-child-realestate' ) ),
        );
        $cases = array(
            array( __( 'Accurate local pricing', 'wp-bbtheme-child-realestate' ), __( 'Comparable evidence and neighbourhood context kept together from valuation to launch.', 'wp-bbtheme-child-realestate' ) ),
            array( __( 'Joined-up viewing route', 'wp-bbtheme-child-realestate' ), __( 'Shortlists move into structured viewing requests without losing the property details.', 'wp-bbtheme-child-realestate' ) ),
            array( __( 'Clear vendor updates', 'wp-bbtheme-child-realestate' ), __( 'Status, interest and next actions stay easy to scan throughout the marketing period.', 'wp-bbtheme-child-realestate' ) ),
        );

        $finder = shortcode_exists( 'wp_theme_property_search' )
            ? do_shortcode( '[wp_theme_property_search limit="6"]' )
            : '<p>' . esc_html__( 'Property search is temporarily unavailable.', 'wp-bbtheme-child-realestate' ) . '</p>';

        $guides = function_exists( 'wpbb_realestate_v139_latest_guides_shortcode' )
            ? (string) wpbb_realestate_v139_latest_guides_shortcode()
            : ( shortcode_exists( 'wpbb_realestate_latest_guides' ) ? do_shortcode( '[wpbb_realestate_latest_guides]' ) : '' );

        $html = '<div class="wpbb-re141-home" data-wpbb-realestate-home="3.8.11.41">';

        /* Hero: direct semantic markup, no Swiper/default-slide dependency. */
        $html .= '<section class="wpbb-re141-hero" aria-labelledby="wpbb-re141-hero-title">';
        if ( $hero ) {
            $html .= '<div class="wpbb-re141-hero__media"><img src="' . esc_url( $hero ) . '" alt="" width="2560" height="1216" loading="eager" decoding="async" fetchpriority="high"></div>';
        }
        $html .= '<div class="wpbb-re141-shell wpbb-re141-hero__inner"><div class="wpbb-re141-hero__copy">';
        $html .= '<p class="wp-theme-sector-eyebrow">' . esc_html__( 'Local property specialists', 'wp-bbtheme-child-realestate' ) . '</p>';
        $html .= '<h1 id="wpbb-re141-hero-title">' . esc_html( (string) ( $profile['hero_title'] ?? __( 'Find a home with the local context to choose well.', 'wp-bbtheme-child-realestate' ) ) ) . '</h1>';
        $html .= '<p class="wpbb-re141-hero__text">' . esc_html( (string) ( $profile['hero_text'] ?? '' ) ) . '</p>';
        $html .= '<div class="wpbb-re141-actions"><a class="btn btn-primary" href="#properties">' . esc_html__( 'Browse properties', 'wp-bbtheme-child-realestate' ) . '</a><a class="wpbb-re141-text-link" href="' . esc_url( $contact_url ) . '">' . esc_html__( 'Book a valuation', 'wp-bbtheme-child-realestate' ) . '</a></div>';
        $html .= '</div></div></section>';

        /* Search */
        $html .= '<section id="properties" class="wpbb-re141-section wpbb-re141-section--search"><div class="wpbb-re141-shell">' . $finder . '</div></section>';

        /* Services */
        $html .= '<section class="wpbb-re141-section wpbb-re141-section--soft"><div class="wpbb-re141-shell">';
        $html .= wpbb_realestate_v141_heading( (string) ( $profile['services_eyebrow'] ?? __( 'Agency services', 'wp-bbtheme-child-realestate' ) ), (string) ( $profile['services_heading'] ?? '' ) );
        $html .= wpbb_realestate_v141_cards( $services, 4, 'wpbb-re141-services' );
        $html .= '</div></section>';

        /* About */
        $html .= '<section class="wpbb-re141-section"><div class="wpbb-re141-shell wpbb-re141-split">';
        $html .= '<figure class="wpbb-re141-split__media">';
        if ( $about ) $html .= '<img src="' . esc_url( $about ) . '" alt="" width="1200" height="900" loading="lazy" decoding="async">';
        $html .= '</figure><div class="wpbb-re141-split__copy">';
        $html .= '<p class="wp-theme-sector-eyebrow">' . esc_html( (string) ( $profile['about_eyebrow'] ?? __( 'Local knowledge', 'wp-bbtheme-child-realestate' ) ) ) . '</p>';
        $html .= '<h2>' . esc_html( (string) ( $profile['about_title'] ?? '' ) ) . '</h2>';
        $html .= '<p>' . esc_html( (string) ( $profile['about_text'] ?? '' ) ) . '</p>';
        $html .= '<a class="btn btn-primary" href="' . esc_url( $contact_url ) . '">' . esc_html__( 'Book a valuation', 'wp-bbtheme-child-realestate' ) . '</a>';
        $html .= '</div></div></section>';

        /* Property journeys */
        $html .= '<section class="wpbb-re141-section wpbb-re141-section--soft"><div class="wpbb-re141-shell">';
        $html .= wpbb_realestate_v141_heading( (string) ( $profile['industries_eyebrow'] ?? __( 'Property journeys', 'wp-bbtheme-child-realestate' ) ), (string) ( $profile['industries_heading'] ?? '' ) );
        $html .= wpbb_realestate_v141_cards( $journeys, 4, 'wpbb-re141-journeys' );
        $html .= '</div></section>';

        /* Main proof facts */
        $html .= '<section class="wpbb-re141-section wpbb-re141-section--compact"><div class="wpbb-re141-shell">';
        $html .= wpbb_realestate_v141_stats( (array) ( $profile['stats'] ?? array() ), 4 );
        $html .= '</div></section>';

        /* Areas + proof */
        $html .= '<section class="wpbb-re141-section"><div class="wpbb-re141-shell">';
        $html .= wpbb_realestate_v141_heading( __( 'Area guides', 'wp-bbtheme-child-realestate' ), __( 'Know the neighbourhood before the viewing.', 'wp-bbtheme-child-realestate' ) );
        $html .= wpbb_realestate_v141_cards( $areas, 3, 'wpbb-re141-areas' );
        $html .= wpbb_realestate_v141_stats( $local_proof, 3, 'wpbb-re141-local-proof' );
        $html .= '</div></section>';

        /* Valuation CTA */
        $html .= '<section class="wpbb-re141-cta"><div class="wpbb-re141-shell wpbb-re141-cta__inner"><div><p class="wp-theme-sector-eyebrow">' . esc_html__( 'Property valuation', 'wp-bbtheme-child-realestate' ) . '</p><h2>' . esc_html__( 'Start with a straightforward valuation.', 'wp-bbtheme-child-realestate' ) . '</h2><p>' . esc_html__( 'Get useful local context, realistic next steps and a clear route to market.', 'wp-bbtheme-child-realestate' ) . '</p></div><a class="btn btn-primary" href="' . esc_url( $contact_url ) . '">' . esc_html__( 'Book a valuation', 'wp-bbtheme-child-realestate' ) . '</a></div></section>';

        /* Outcomes */
        $html .= '<section class="wpbb-re141-section"><div class="wpbb-re141-shell">';
        $html .= wpbb_realestate_v141_heading( __( 'Recent work', 'wp-bbtheme-child-realestate' ), __( 'Recent work and measurable outcomes.', 'wp-bbtheme-child-realestate' ) );
        $html .= wpbb_realestate_v141_cards( $cases, 3, 'wpbb-re141-cases' );
        $html .= '</div></section>';

        /* Gallery */
        $html .= '<section class="wpbb-re141-section wpbb-re141-section--cream"><div class="wpbb-re141-shell">';
        $html .= wpbb_realestate_v141_heading( __( 'Property gallery', 'wp-bbtheme-child-realestate' ), __( 'Homes, neighbourhoods and details shown at a useful scale.', 'wp-bbtheme-child-realestate' ) );
        $html .= '<div class="wpbb-re141-gallery">';
        foreach ( $gallery as $item ) {
            $url = wpbb_realestate_v141_asset_url( $item[0] );
            $html .= '<figure class="wpbb-re141-gallery__card">';
            if ( $url ) $html .= '<img src="' . esc_url( $url ) . '" alt="" width="1200" height="900" loading="lazy" decoding="async">';
            $html .= '<figcaption>' . esc_html( $item[1] ) . '</figcaption></figure>';
        }
        $html .= '</div></div></section>';

        /* Process */
        $html .= '<section class="wpbb-re141-section wpbb-re141-section--soft"><div class="wpbb-re141-shell">';
        $html .= wpbb_realestate_v141_heading( (string) ( $profile['process_eyebrow'] ?? __( 'From search to keys', 'wp-bbtheme-child-realestate' ) ), (string) ( $profile['process_heading'] ?? '' ) );
        $html .= wpbb_realestate_v141_cards( $process, 3, 'wpbb-re141-process' );
        $html .= '</div></section>';

        /* FAQ */
        $html .= '<section class="wpbb-re141-section"><div class="wpbb-re141-shell">';
        $html .= wpbb_realestate_v141_heading( __( 'FAQ', 'wp-bbtheme-child-realestate' ), (string) ( $profile['faq_heading'] ?? '' ) );
        $html .= '<div class="wpbb-re141-faq">';
        foreach ( (array) ( $profile['faq'] ?? array() ) as $item ) {
            $html .= '<details><summary>' . esc_html( (string) ( $item[0] ?? '' ) ) . '</summary><p>' . esc_html( (string) ( $item[1] ?? '' ) ) . '</p></details>';
        }
        $html .= '</div></div></section>';

        /* Latest guidance */
        if ( '' !== trim( $guides ) ) {
            $html .= '<section class="wpbb-re141-section"><div class="wpbb-re141-shell">';
            $html .= wpbb_realestate_v141_heading( __( 'Latest thinking', 'wp-bbtheme-child-realestate' ), __( 'Latest guides, news and practical advice.', 'wp-bbtheme-child-realestate' ) );
            $html .= $guides . '</div></section>';
        }

        return $html . '</div>';
    }
}
add_shortcode( 'wpbb_realestate_v141_home', 'wpbb_realestate_v141_home_markup' );

if ( ! function_exists( 'wpbb_realestate_v141_front_content' ) ) {
    function wpbb_realestate_v141_front_content() {
        return '<!-- wp:shortcode -->[wpbb_realestate_v141_home]<!-- /wp:shortcode -->';
    }
}

if ( ! function_exists( 'wpbb_realestate_v141_should_own_front' ) ) {
    function wpbb_realestate_v141_should_own_front( $post_id, $content = '' ) {
        $post_id = absint( $post_id );
        if ( ! $post_id ) return false;
        if ( '1' === (string) get_post_meta( $post_id, '_wp_theme_demo_managed', true ) ) return true;
        if ( '' !== (string) get_post_meta( $post_id, '_wpbb_child_bbuilder_version', true ) ) return true;
        $content = (string) $content;
        return false !== strpos( $content, 'wpbb-v139-home-hero' )
            || false !== strpos( $content, 'wpbb-re141-home' )
            || false !== strpos( $content, '[wpbb_realestate_v141_home]' );
    }
}

if ( ! function_exists( 'wpbb_realestate_v141_render_guard' ) ) {
    function wpbb_realestate_v141_render_guard( $content ) {
        if ( is_admin() || ! is_front_page() || ! in_the_loop() || ! is_main_query() ) return $content;
        $post_id = get_queried_object_id();
        if ( ! wpbb_realestate_v141_should_own_front( $post_id, (string) $content ) ) return $content;
        $clean = wpbb_realestate_v141_home_markup();
        return '' !== trim( (string) $clean ) ? $clean : $content;
    }
}
add_filter( 'the_content', 'wpbb_realestate_v141_render_guard', 0 );

if ( ! function_exists( 'wpbb_realestate_v141_persist_home' ) ) {
    function wpbb_realestate_v141_persist_home( $page_id = 0, $profile = array() ) {
        if ( ! is_admin() || ! current_user_can( 'manage_options' ) || wp_doing_ajax() ) return;
        $front = absint( get_option( 'page_on_front' ) );
        if ( ! $front ) return;
        $raw = (string) get_post_field( 'post_content', $front, 'raw' );
        if ( ! wpbb_realestate_v141_should_own_front( $front, $raw ) ) return;

        $target = wpbb_realestate_v141_front_content();
        $version = (string) get_post_meta( $front, '_wpbb_child_bbuilder_version', true );
        if ( '3.8.11.41' !== $version || trim( $raw ) !== trim( $target ) ) {
            wp_update_post( wp_slash( array( 'ID' => $front, 'post_content' => $target ) ) );
            update_post_meta( $front, '_wp_theme_demo_managed', '1' );
            update_post_meta( $front, '_wpbb_child_bbuilder_version', '3.8.11.41' );
            clean_post_cache( $front );
        }

        if ( function_exists( 'wpbb_realestate_v138_repair_demo_posts' ) ) wpbb_realestate_v138_repair_demo_posts();
        if ( function_exists( 'wpbb_realestate_v138_refresh_property_media' ) ) wpbb_realestate_v138_refresh_property_media();
        update_option( 'wpbb_realestate_v141_home_' . sanitize_key( get_stylesheet() ), '3.8.11.41', false );
    }
}
add_action( 'admin_init', 'wpbb_realestate_v141_persist_home', PHP_INT_MAX );
add_action( 'wp_theme_after_demo_import', 'wpbb_realestate_v141_persist_home', PHP_INT_MAX );
add_action( 'wp_theme_after_demo_reset', 'wpbb_realestate_v141_persist_home', PHP_INT_MAX );
add_action( 'wp_theme_demo_reset_complete', 'wpbb_realestate_v141_persist_home', PHP_INT_MAX );
add_action( 'wp_theme_starter_setup_complete', 'wpbb_realestate_v141_persist_home', PHP_INT_MAX );

if ( ! function_exists( 'wpbb_realestate_v141_enqueue' ) ) {
    function wpbb_realestate_v141_enqueue() {
        /* v139 has already removed v82-v138 competing suite assets. Keep that
         * proven isolation and layer only the small v141 recovery on top. */
        $dir = get_stylesheet_directory();
        $uri = get_stylesheet_directory_uri();
        $css = '/assets/suite-v141.css';
        $js  = '/assets/suite-v141.js';
        if ( is_readable( $dir . $css ) ) {
            wp_enqueue_style(
                'wpbb-suite-v141',
                $uri . $css,
                array( 'wpbb-suite-v139' ),
                (string) filemtime( $dir . $css )
            );
        }
        if ( is_readable( $dir . $js ) ) {
            wp_enqueue_script(
                'wpbb-suite-v141',
                $uri . $js,
                array(),
                (string) filemtime( $dir . $js ),
                true
            );
        }
    }
}
add_action( 'wp_enqueue_scripts', 'wpbb_realestate_v141_enqueue', PHP_INT_MAX );

if ( ! function_exists( 'wpbb_realestate_v141_body_class' ) ) {
    function wpbb_realestate_v141_body_class( $classes ) {
        $classes = array_values( array_filter( (array) $classes, static function( $class ) {
            return ! preg_match( '/^wpbb-v140(?:$|-)/', (string) $class );
        } ) );
        $classes[] = 'wpbb-v141';
        $classes[] = 'wpbb-v141-theme-realestate';
        /* v139 owns header/footer/property-search primitives. */
        $classes[] = 'wpbb-v139';
        $classes[] = 'wpbb-v139-theme-realestate';
        return array_values( array_unique( $classes ) );
    }
}
add_filter( 'body_class', 'wpbb_realestate_v141_body_class', PHP_INT_MAX );
