<?php
/**
 * WP BBTheme Child Real Estate 3.8.11.39
 *
 * Final Real Estate ownership layer:
 * - retires historical visual patch assets that were competing for geometry;
 * - gives managed demo Home one predictable, property-specific block structure;
 * - refreshes demo property media from the bundled high-quality sources;
 * - keeps the front page repaired immediately, before the persistent admin pass.
 */
defined( 'ABSPATH' ) || exit;

/* Retire old render-time homepage injections as well as their assets. */
remove_filter( 'render_block', 'wpbb_child_v83_render_metrics', 90 );
remove_filter( 'render_block', 'wpbb_child_v97_render_hero_finder', 180 );

if ( ! function_exists( 'wpbb_realestate_v139_legacy_handles' ) ) {
    function wpbb_realestate_v139_legacy_handles() {
        return array(
            'wpbb-child-sector-v82',
            'wpbb-child-sector-v83',
            'wpbb-child-sector-v97',
            'wpbb-suite-v98',
            'wpbb-suite-v99',
            'wpbb-suite-v100',
            'wpbb-suite-v101',
            'wpbb-suite-v104',
            'wpbb-suite-v105',
            'wpbb-suite-v107',
            'wpbb-suite-v108',
            'wpbb-suite-v109',
            'wpbb-suite-v110',
            'wpbb-suite-v111',
            'wpbb-suite-v112',
            'wpbb-suite-v113',
            'wpbb-suite-v114',
            'wpbb-suite-v115',
            'wpbb-suite-v116',
            'wpbb-suite-v117',
            'wpbb-suite-v118',
            'wpbb-suite-v119',
            'wpbb-suite-v120',
            'wpbb-suite-v121',
            'wpbb-suite-v122',
            'wpbb-suite-v123',
            'wpbb-suite-v124',
            'wpbb-suite-v125',
            'wpbb-suite-v126',
            'wpbb-suite-v127',
            'wpbb-suite-v128',
            'wpbb-suite-v134',
            'wpbb-suite-v135',
            'wpbb-suite-v136',
            'wpbb-suite-v137',
            'wpbb-suite-v138',
        );
    }
}

if ( ! function_exists( 'wpbb_realestate_v139_enqueue' ) ) {
    function wpbb_realestate_v139_enqueue() {
        foreach ( wpbb_realestate_v139_legacy_handles() as $handle ) {
            wp_dequeue_style( $handle );
            wp_deregister_style( $handle );
            wp_dequeue_script( $handle );
            wp_deregister_script( $handle );
        }

        $dir = get_stylesheet_directory();
        $uri = get_stylesheet_directory_uri();
        $css = '/assets/suite-v139.css';
        $js  = '/assets/suite-v139.js';

        wp_enqueue_style(
            'wpbb-suite-v139',
            $uri . $css,
            array(),
            is_file( $dir . $css ) ? (string) filemtime( $dir . $css ) : '3.8.11.39'
        );
        wp_enqueue_script(
            'wpbb-suite-v139',
            $uri . $js,
            array(),
            is_file( $dir . $js ) ? (string) filemtime( $dir . $js ) : '3.8.11.39',
            true
        );
    }
}
add_action( 'wp_enqueue_scripts', 'wpbb_realestate_v139_enqueue', PHP_INT_MAX );

if ( ! function_exists( 'wpbb_realestate_v139_body_class' ) ) {
    function wpbb_realestate_v139_body_class( $classes ) {
        $clean = array();
        foreach ( (array) $classes as $class ) {
            if ( preg_match( '/^wpbb-v(?:9[89]|1(?:0[0-9]|1[0-9]|2[0-9]|3[0-8]))(?:$|-)/', (string) $class ) ) continue;
            $clean[] = $class;
        }
        $clean[] = 'wpbb-v139';
        $clean[] = 'wpbb-v139-theme-realestate';
        return array_values( array_unique( $clean ) );
    }
}
add_filter( 'body_class', 'wpbb_realestate_v139_body_class', PHP_INT_MAX );

if ( ! function_exists( 'wpbb_realestate_v139_profile' ) ) {
    function wpbb_realestate_v139_profile( $profile ) {
        $profile = function_exists( 'wpbb_realestate_demo_profile' ) ? wpbb_realestate_demo_profile( (array) $profile ) : (array) $profile;
        $profile['id'] = 'realestate';
        $profile['commerce'] = false;
        $profile['hero_title'] = __( 'Find a home with the local context to choose well.', 'wp-bbtheme-child-realestate' );
        $profile['hero_text'] = __( 'Search current homes, compare the details that matter and move from shortlist to viewing with a clear local route.', 'wp-bbtheme-child-realestate' );
        $profile['palette'] = array_merge(
            (array) ( $profile['palette'] ?? array() ),
            array(
                'theme_brand_color'       => '#0b8f67',
                'theme_accent_color'      => '#0b8f67',
                'theme_surface_alt_color' => '#f5faf7',
                'theme_link_color'        => '#0b8f67',
                'theme_radius'            => '14px',
            )
        );
        return $profile;
    }
}
add_filter( 'wp_theme_demo_profile', 'wpbb_realestate_v139_profile', PHP_INT_MAX );

if ( ! function_exists( 'wpbb_realestate_v139_section' ) ) {
    function wpbb_realestate_v139_section( $classes, $inner ) {
        if ( function_exists( 'wpbb_child_v67_section' ) ) {
            return wpbb_child_v67_section( trim( 'wp-theme-section-shell ' . $classes ), $inner, 'wpbb-v139-section' );
        }
        return $inner;
    }
}

if ( ! function_exists( 'wpbb_realestate_v139_heading' ) ) {
    function wpbb_realestate_v139_heading( $eyebrow, $heading ) {
        return function_exists( 'wpbb_child_v62_section_heading' )
            ? wpbb_child_v62_section_heading( $eyebrow, $heading, 'wpbb-v139-section-heading' )
            : '';
    }
}

if ( ! function_exists( 'wpbb_realestate_v139_fun_facts' ) ) {
    function wpbb_realestate_v139_fun_facts( $stats, $class = '' ) {
        if ( ! function_exists( 'wpbb_child_v62_block' ) ) return '';
        $stats = array_values( (array) $stats );
        if ( ! $stats ) return '';
        $cols = '';
        $count = min( 4, count( $stats ) );
        $lg = 3 === $count ? 4 : ( 2 === $count ? 6 : 3 );
        foreach ( array_slice( $stats, 0, 4 ) as $stat ) {
            $cols .= wpbb_child_v62_block(
                'wpbb/column',
                array( 'xs' => 12, 'md' => 6, 'lg' => $lg, 'customClasses' => 'd-flex' ),
                wpbb_child_v62_block(
                    'wpbb/fun-fact',
                    array(
                        'number'       => (string) ( $stat[0] ?? '' ),
                        'label'        => (string) ( $stat[1] ?? '' ),
                        'styleVariant' => 'sector-proof',
                        'className'    => 'wp-theme-sector-proof__item wpbb-v139-stat-card',
                    ),
                    '',
                    true
                )
            );
        }
        return wpbb_child_v62_block(
            'wpbb/row',
            array( 'customClasses' => trim( 'wpbb-v139-stat-grid ' . $class ), 'gutterX' => 'gx-4', 'gutterY' => 'gy-4' ),
            $cols
        );
    }
}

if ( ! function_exists( 'wpbb_realestate_v139_home_content' ) ) {
    function wpbb_realestate_v139_home_content() {
        if ( ! function_exists( 'wpbb_child_v62_block' ) || ! function_exists( 'wpbb_child_v62_cards_row' ) ) {
            $profile = wpbb_realestate_v139_profile( array() );
            return function_exists( 'wpbb_child_v62_front_content' ) ? (string) wpbb_child_v62_front_content( $profile ) : '';
        }

        $profile = wpbb_realestate_v139_profile( array() );
        $uri = trailingslashit( get_stylesheet_directory_uri() );
        $hero = $uri . 'assets/img/hero-v136/slide-1.jpg';
        $property = $uri . 'assets/img/properties/';
        $content = '';

        /* One strong hero slide avoids duplicate imagery, duplicate pagination and old runtime sizing races. */
        $slides = array(
            array(
                'type'       => 'hero',
                'eyebrow'    => __( 'Local property specialists', 'wp-bbtheme-child-realestate' ),
                'title'      => (string) $profile['hero_title'],
                'text'       => (string) $profile['hero_text'],
                'buttonText' => __( 'Browse properties', 'wp-bbtheme-child-realestate' ),
                'buttonUrl'  => '#properties',
                'image'      => $hero,
            ),
        );
        $hero_block = wpbb_child_v62_block(
            'wpbb/swiper',
            array(
                'slides'         => $slides,
                'slidesJson'     => wp_json_encode( $slides, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ),
                'slidesPerView'  => 1,
                'slidesTablet'   => 1,
                'slidesMobile'   => 1,
                'spaceBetween'   => 0,
                'speed'          => 500,
                'loop'           => false,
                'rewind'         => false,
                'autoplay'       => false,
                'demoStyle'      => 'hero',
                'showPagination' => false,
                'showNavigation' => false,
                'className'      => 'wpbb-v139-home-hero',
            ),
            '',
            true
        );
        $content .= wpbb_child_v62_block(
            'wpbb/row',
            array( 'containerClass' => 'container-fluid', 'customClasses' => 'wp-theme-sector-hero wpbb-v139-hero-shell', 'gutterX' => 'gx-0', 'gutterY' => 'gy-0' ),
            wpbb_child_v62_block( 'wpbb/column', array( 'xs' => 12 ), $hero_block )
        );

        /* Finder: direct 12-column placement, no nested narrow editorial column. */
        $finder = wpbb_child_v62_block( 'shortcode', array(), '[wp_theme_property_search limit="6"]' );
        $content .= wpbb_realestate_v139_section(
            'wp-theme-property-search-section wpbb-v139-property-search',
            wpbb_child_v62_block( 'wpbb/row', array( 'customClasses' => 'wpbb-v139-full-row', 'gutterX' => 'gx-0', 'gutterY' => 'gy-0' ), wpbb_child_v62_block( 'wpbb/column', array( 'xs' => 12 ), $finder ) )
        );

        /* Core services. */
        $services = (array) ( $profile['services'] ?? array() );
        $inner = wpbb_realestate_v139_heading( (string) ( $profile['services_eyebrow'] ?? __( 'Agency services', 'wp-bbtheme-child-realestate' ) ), (string) ( $profile['services_heading'] ?? '' ) );
        $inner .= wpbb_child_v62_cards_row( $services, 'wp-theme-sector-card wpbb-v139-card', 4 );
        $content .= wpbb_realestate_v139_section( 'wp-theme-services-section wpbb-v139-soft-section', $inner );

        /* Media/text split. */
        $about_left = function_exists( 'wpbb_child_v62_image' ) ? wpbb_child_v62_image( $property . 'park-view.jpg', 'wp-theme-sector-media-text__media wpbb-v139-feature-image' ) : '';
        $about_right = wpbb_child_v62_paragraph( (string) ( $profile['about_eyebrow'] ?? __( 'Local knowledge', 'wp-bbtheme-child-realestate' ) ), 'wp-theme-sector-eyebrow' );
        $about_right .= wpbb_child_v62_heading( (string) ( $profile['about_title'] ?? '' ), 2 );
        $about_right .= wpbb_child_v62_paragraph( (string) ( $profile['about_text'] ?? '' ) );
        $about_right .= wpbb_child_v62_block( 'wpbb/button', array( 'text' => __( 'Book a valuation', 'wp-bbtheme-child-realestate' ), 'url' => '/contact/', 'btnClass' => 'btn btn-primary' ), '', true );
        $about_row = wpbb_child_v62_block(
            'wpbb/row',
            array( 'customClasses' => 'align-items-center wp-theme-sector-media-text wpbb-v139-media-row', 'gutterX' => 'gx-5', 'gutterY' => 'gy-5' ),
            wpbb_child_v62_block( 'wpbb/column', array( 'xs' => 12, 'lg' => 6 ), $about_left ) .
            wpbb_child_v62_block( 'wpbb/column', array( 'xs' => 12, 'lg' => 6 ), $about_right )
        );
        $content .= wpbb_realestate_v139_section( 'wp-theme-about-section', $about_row );

        /* Property journeys. */
        $journeys = (array) ( $profile['industries'] ?? array() );
        $inner = wpbb_realestate_v139_heading( (string) ( $profile['industries_eyebrow'] ?? __( 'Property journeys', 'wp-bbtheme-child-realestate' ) ), (string) ( $profile['industries_heading'] ?? '' ) );
        $inner .= wpbb_child_v62_cards_row( $journeys, 'wp-theme-sector-card wpbb-v139-card', 4 );
        $content .= wpbb_realestate_v139_section( 'wp-theme-industries-section wpbb-v139-soft-section', $inner );

        /* Four equal proof facts. */
        $content .= wpbb_realestate_v139_section( 'wp-theme-home-stats wpbb-v139-stats-section', wpbb_realestate_v139_fun_facts( (array) ( $profile['stats'] ?? array() ) ) );

        /* Area guides and local proof share one aligned section instead of two detached narrow strips. */
        $areas = array(
            array( __( 'North Quarter', 'wp-bbtheme-child-realestate' ), __( 'Period homes, independent shops and straightforward transport links.', 'wp-bbtheme-child-realestate' ) ),
            array( __( 'Riverside', 'wp-bbtheme-child-realestate' ), __( 'Apartments, waterside walks and quick access to the city.', 'wp-bbtheme-child-realestate' ) ),
            array( __( 'Old Village', 'wp-bbtheme-child-realestate' ), __( 'Character homes, schools and established neighbourhood streets.', 'wp-bbtheme-child-realestate' ) ),
        );
        $inner = wpbb_realestate_v139_heading( __( 'Area guides', 'wp-bbtheme-child-realestate' ), __( 'Know the neighbourhood before the viewing.', 'wp-bbtheme-child-realestate' ) );
        $inner .= wpbb_child_v62_cards_row( $areas, 'wp-theme-sector-card wpbb-v139-area-card', 3 );
        $inner .= wpbb_realestate_v139_fun_facts(
            array(
                array( '12', __( 'local areas covered', 'wp-bbtheme-child-realestate' ) ),
                array( '96%', __( 'asking price achieved*', 'wp-bbtheme-child-realestate' ) ),
                array( '4.9/5', __( 'demo client rating', 'wp-bbtheme-child-realestate' ) ),
            ),
            'wpbb-v139-proof-grid'
        );
        $content .= wpbb_realestate_v139_section( 'estate-area-guides wpbb-v139-area-section', $inner );

        /* Valuation call-to-action. */
        $content .= wpbb_child_v62_block(
            'wpbb/cta-section',
            array(
                'title'      => __( 'Start with a straightforward valuation.', 'wp-bbtheme-child-realestate' ),
                'text'       => __( 'Get useful local context, realistic next steps and a clear route to market.', 'wp-bbtheme-child-realestate' ),
                'buttonText' => __( 'Book a valuation', 'wp-bbtheme-child-realestate' ),
                'buttonUrl'  => '/contact/',
                'className'  => 'wp-theme-home-cta wpbb-v139-home-cta',
            ),
            '',
            true
        );

        /* Measurable outcomes. */
        $cases = array(
            array( __( 'Accurate local pricing', 'wp-bbtheme-child-realestate' ), __( 'Comparable evidence and neighbourhood context kept together from valuation to launch.', 'wp-bbtheme-child-realestate' ) ),
            array( __( 'Joined-up viewing route', 'wp-bbtheme-child-realestate' ), __( 'Shortlists move into structured viewing requests without losing the property details.', 'wp-bbtheme-child-realestate' ) ),
            array( __( 'Clear vendor updates', 'wp-bbtheme-child-realestate' ), __( 'Status, interest and next actions stay easy to scan throughout the marketing period.', 'wp-bbtheme-child-realestate' ) ),
        );
        $inner = wpbb_realestate_v139_heading( __( 'Recent work', 'wp-bbtheme-child-realestate' ), __( 'Recent work and measurable outcomes.', 'wp-bbtheme-child-realestate' ) );
        $inner .= wpbb_child_v62_cards_row( $cases, 'wp-theme-sector-card wp-theme-case-card wpbb-v139-card', 3 );
        $content .= wpbb_realestate_v139_section( 'wp-theme-case-studies-section', $inner );

        /* Static 3-up gallery uses three genuinely different 1200x900 property images. */
        $gallery = array(
            array( 'willow-house.jpg', __( 'Willow House', 'wp-bbtheme-child-realestate' ) ),
            array( 'harbour-house.jpg', __( 'Riverside homes', 'wp-bbtheme-child-realestate' ) ),
            array( 'the-glassworks.jpg', __( 'The Glassworks', 'wp-bbtheme-child-realestate' ) ),
        );
        $gallery_cols = '';
        foreach ( $gallery as $item ) {
            $card = wpbb_child_v62_block(
                'wpbb/bootstrap-div',
                array( 'containerClass' => '', 'utilityClasses' => 'wpbb-v139-gallery-card' ),
                wpbb_child_v62_image( $property . $item[0], 'wpbb-v139-gallery-image' ) . wpbb_child_v62_heading( $item[1], 3 )
            );
            $gallery_cols .= wpbb_child_v62_block( 'wpbb/column', array( 'xs' => 12, 'md' => 4, 'customClasses' => 'd-flex' ), $card );
        }
        $inner = wpbb_realestate_v139_heading( __( 'Property gallery', 'wp-bbtheme-child-realestate' ), __( 'Homes, neighbourhoods and details shown at a useful scale.', 'wp-bbtheme-child-realestate' ) );
        $inner .= wpbb_child_v62_block( 'wpbb/row', array( 'customClasses' => 'wpbb-v139-gallery-grid', 'gutterX' => 'gx-4', 'gutterY' => 'gy-4' ), $gallery_cols );
        $content .= wpbb_realestate_v139_section( 'wp-theme-gallery-section wpbb-v139-gallery-section', $inner );

        /* Search-to-move process. */
        $process_cards = array();
        foreach ( (array) ( $profile['process'] ?? array() ) as $step ) {
            $process_cards[] = array( trim( (string) ( $step[0] ?? '' ) . ' ' . (string) ( $step[1] ?? '' ) ), (string) ( $step[2] ?? '' ) );
        }
        $inner = wpbb_realestate_v139_heading( (string) ( $profile['process_eyebrow'] ?? __( 'From search to keys', 'wp-bbtheme-child-realestate' ) ), (string) ( $profile['process_heading'] ?? '' ) );
        $inner .= wpbb_child_v62_cards_row( $process_cards, 'wp-theme-sector-card wp-theme-process-card wpbb-v139-card', 3 );
        $content .= wpbb_realestate_v139_section( 'wp-theme-process-section wpbb-v139-soft-section', $inner );

        /* FAQ. */
        $faq = '';
        foreach ( (array) ( $profile['faq'] ?? array() ) as $item ) {
            $faq .= wpbb_child_v62_block(
                'details',
                array(),
                '<details class="wp-block-details"><summary>' . esc_html( (string) ( $item[0] ?? '' ) ) . '</summary>' . wpbb_child_v62_paragraph( (string) ( $item[1] ?? '' ) ) . '</details>'
            );
        }
        $inner = wpbb_realestate_v139_heading( __( 'FAQ', 'wp-bbtheme-child-realestate' ), (string) ( $profile['faq_heading'] ?? '' ) );
        $inner .= wpbb_child_v62_block( 'wpbb/row', array( 'customClasses' => 'wpbb-v139-faq-row' ), wpbb_child_v62_block( 'wpbb/column', array( 'xs' => 12 ), $faq ) );
        $content .= wpbb_realestate_v139_section( 'wp-theme-faq-section', $inner );

        /* Latest guidance stays dynamic but is rendered in the same three-column system. */
        $inner = wpbb_realestate_v139_heading( __( 'Latest thinking', 'wp-bbtheme-child-realestate' ), __( 'Latest guides, news and practical advice.', 'wp-bbtheme-child-realestate' ) );
        $inner .= wpbb_child_v62_block( 'shortcode', array(), '[wpbb_realestate_latest_guides]' );
        $content .= wpbb_realestate_v139_section( 'wp-theme-blog-preview wpbb-v139-blog-section', $inner );

        return $content;
    }
}

if ( ! function_exists( 'wpbb_realestate_v139_latest_guides_shortcode' ) ) {
    function wpbb_realestate_v139_latest_guides_shortcode() {
        $query = new WP_Query(
            array(
                'post_type'           => 'post',
                'post_status'         => 'publish',
                'posts_per_page'      => 3,
                'ignore_sticky_posts' => true,
            )
        );
        if ( ! $query->have_posts() ) return '';
        $html = '<div class="wpbb-v139-blog-grid">';
        $index = 1;
        while ( $query->have_posts() ) {
            $query->the_post();
            $id = get_the_ID();
            $image = get_the_post_thumbnail_url( $id, 'large' );
            if ( ! $image ) $image = trailingslashit( get_stylesheet_directory_uri() ) . 'assets/img/blog/blog-' . min( 6, $index ) . '.jpg';
            $excerpt = get_the_excerpt( $id );
            if ( ! $excerpt ) $excerpt = wp_trim_words( wp_strip_all_tags( (string) get_post_field( 'post_content', $id ) ), 22 );
            $html .= '<article class="wp-theme-blog-card wpbb-v139-blog-card">';
            $html .= '<a class="wpbb-v139-blog-card__media" href="' . esc_url( get_permalink( $id ) ) . '"><img src="' . esc_url( $image ) . '" alt="" loading="lazy" decoding="async"></a>';
            $html .= '<div class="wpbb-v139-blog-card__body"><p class="wpbb-v139-blog-card__date">' . esc_html( get_the_date( '', $id ) ) . '</p><h3><a href="' . esc_url( get_permalink( $id ) ) . '">' . esc_html( get_the_title( $id ) ) . '</a></h3><p>' . esc_html( $excerpt ) . '</p><a class="wp-theme-text-link" href="' . esc_url( get_permalink( $id ) ) . '">' . esc_html__( 'Read article', 'wp-bbtheme-child-realestate' ) . '</a></div>';
            $html .= '</article>';
            $index++;
        }
        wp_reset_postdata();
        return $html . '</div>';
    }
}
add_shortcode( 'wpbb_realestate_latest_guides', 'wpbb_realestate_v139_latest_guides_shortcode' );

if ( ! function_exists( 'wpbb_realestate_v139_should_own_front' ) ) {
    function wpbb_realestate_v139_should_own_front( $post_id, $content = '' ) {
        $post_id = absint( $post_id );
        if ( ! $post_id ) return false;
        $managed = '1' === (string) get_post_meta( $post_id, '_wp_theme_demo_managed', true ) || '' !== (string) get_post_meta( $post_id, '_wpbb_child_bbuilder_version', true );
        $cross = function_exists( 'wpbb_realestate_v138_content_is_cross_sector' ) && wpbb_realestate_v138_content_is_cross_sector( $content );
        return $managed || $cross;
    }
}

if ( ! function_exists( 'wpbb_realestate_v139_render_guard' ) ) {
    function wpbb_realestate_v139_render_guard( $content ) {
        if ( is_admin() || ! is_front_page() || ! in_the_loop() || ! is_main_query() ) return $content;
        $post_id = get_queried_object_id();
        if ( ! wpbb_realestate_v139_should_own_front( $post_id, (string) $content ) ) return $content;
        $version = (string) get_post_meta( $post_id, '_wpbb_child_bbuilder_version', true );
        if ( '3.8.11.39' === $version && false !== strpos( (string) $content, 'wpbb-v139-home-hero' ) ) return $content;
        $clean = wpbb_realestate_v139_home_content();
        return '' !== trim( $clean ) ? $clean : $content;
    }
}
add_filter( 'the_content', 'wpbb_realestate_v139_render_guard', 0 );

if ( ! function_exists( 'wpbb_realestate_v139_persist_repair' ) ) {
    function wpbb_realestate_v139_persist_repair() {
        if ( ! is_admin() || ! current_user_can( 'manage_options' ) || wp_doing_ajax() ) return;
        $key = 'wpbb_realestate_v139_demo_repair_' . sanitize_key( get_stylesheet() );
        $front = absint( get_option( 'page_on_front' ) );
        if ( ! $front ) return;
        $content = (string) get_post_field( 'post_content', $front, 'raw' );
        if ( ! wpbb_realestate_v139_should_own_front( $front, $content ) ) return;

        $already = '3.8.11.39' === (string) get_option( $key );
        $version = (string) get_post_meta( $front, '_wpbb_child_bbuilder_version', true );
        $has_v139 = false !== strpos( $content, 'wpbb-v139-home-hero' );
        if ( ! $already || '3.8.11.39' !== $version || ! $has_v139 ) {
            $clean = wpbb_realestate_v139_home_content();
            if ( '' !== trim( $clean ) ) {
                wp_update_post( array( 'ID' => $front, 'post_content' => $clean ) );
                update_post_meta( $front, '_wp_theme_demo_managed', '1' );
                update_post_meta( $front, '_wpbb_child_bbuilder_version', '3.8.11.39' );
                clean_post_cache( $front );
            }
        }

        if ( function_exists( 'wpbb_realestate_v138_repair_demo_posts' ) ) wpbb_realestate_v138_repair_demo_posts();
        if ( function_exists( 'wpbb_realestate_v138_refresh_property_media' ) ) wpbb_realestate_v138_refresh_property_media();
        update_option( $key, '3.8.11.39', false );
    }
}
add_action( 'admin_init', 'wpbb_realestate_v139_persist_repair', PHP_INT_MAX );
add_action( 'wp_theme_after_demo_import', 'wpbb_realestate_v139_persist_repair', PHP_INT_MAX );
