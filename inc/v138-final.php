<?php
/**
 * WP BBTheme Child Real Estate 3.8.11.38 — sector-safe content + stable layout owner.
 */
defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'wpbb_realestate_v138_hero_urls' ) ) {
    function wpbb_realestate_v138_hero_urls() {
        $dir = get_stylesheet_directory();
        $uri = get_stylesheet_directory_uri();
        $urls = array();
        foreach ( array( 1, 2, 3 ) as $i ) {
            foreach ( array( 'assets/img/hero-v136/slide-' . $i . '.jpg', 'assets/img/hero-v118/slide-' . $i . '.jpg' ) as $rel ) {
                $path = $dir . '/' . $rel;
                if ( is_file( $path ) ) {
                    $urls[] = $uri . '/' . $rel . '?v=' . filemtime( $path );
                    break;
                }
            }
        }
        return $urls;
    }
}

if ( ! function_exists( 'wpbb_realestate_v138_enqueue' ) ) {
    function wpbb_realestate_v138_enqueue() {
        $dir = get_stylesheet_directory();
        $uri = get_stylesheet_directory_uri();

        /*
         * v136/v137 both mutate section geometry at runtime. The broken shop
         * screenshot is a classic nested-grid collision: a section wrapper is
         * promoted to a card grid and its real grid is then squeezed inside a
         * single grid cell. v138 is the only late DOM owner for Real Estate.
         */
        foreach ( array( 'wpbb-suite-v136', 'wpbb-suite-v137' ) as $handle ) {
            wp_dequeue_script( $handle );
            wp_deregister_script( $handle );
            wp_dequeue_style( $handle );
            wp_deregister_style( $handle );
        }

        $css = '/assets/suite-v138.css';
        $js  = '/assets/suite-v138.js';
        wp_enqueue_style(
            'wpbb-suite-v138',
            $uri . $css,
            array(),
            is_file( $dir . $css ) ? (string) filemtime( $dir . $css ) : '3.8.11.38'
        );
        wp_enqueue_script(
            'wpbb-suite-v138',
            $uri . $js,
            array(),
            is_file( $dir . $js ) ? (string) filemtime( $dir . $js ) : '3.8.11.38',
            true
        );
        wp_add_inline_script(
            'wpbb-suite-v138',
            'window.wpbbRealEstateV138=' . wp_json_encode(
                array(
                    'version'  => '3.8.11.38',
                    'heroUrls' => wpbb_realestate_v138_hero_urls(),
                ),
                JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
            ) . ';',
            'before'
        );
    }
}
add_action( 'wp_enqueue_scripts', 'wpbb_realestate_v138_enqueue', PHP_INT_MAX );

if ( ! function_exists( 'wpbb_realestate_v138_body_class' ) ) {
    function wpbb_realestate_v138_body_class( $classes ) {
        $classes = array_values( array_diff( (array) $classes, array(
            'wpbb-v136', 'wpbb-v137',
            'wpbb-v136-theme-realestate', 'wpbb-v137-theme-realestate',
        ) ) );
        $classes[] = 'wpbb-v138';
        $classes[] = 'wpbb-v138-theme-realestate';
        return array_values( array_unique( $classes ) );
    }
}
add_filter( 'body_class', 'wpbb_realestate_v138_body_class', PHP_INT_MAX );


/*
 * Register one final profile owner after every historical finish layer. Several
 * older releases also use PHP_INT_MAX and were registered later than the base
 * Real Estate profile, so they could otherwise replace its media/content again.
 */
if ( ! function_exists( 'wpbb_realestate_v138_final_profile' ) ) {
    function wpbb_realestate_v138_final_profile( $profile ) {
        $profile = is_array( $profile ) ? $profile : array();
        if ( function_exists( 'wpbb_realestate_demo_profile' ) ) {
            $profile = wpbb_realestate_demo_profile( $profile );
        }
        $profile['id'] = 'realestate';
        $profile['commerce'] = false;
        return $profile;
    }
}
add_filter( 'wp_theme_demo_profile', 'wpbb_realestate_v138_final_profile', PHP_INT_MAX );

if ( ! function_exists( 'wpbb_realestate_v138_content_is_cross_sector' ) ) {
    function wpbb_realestate_v138_content_is_cross_sector( $content ) {
        $content = (string) $content;
        if ( '' === trim( $content ) ) return false;
        $signals = array(
            'Find the car, part or service',
            'Sales, rentals, parts and workshop services',
            'New cars, used stock, rentals, parts and service',
            'Synthetic Engine Oil 5L',
            'wp-theme-home-product-catalogue',
            'postType&quot;:&quot;product',
            '"postType":"product"',
            'automotive',
            'dealership',
            'vehicle finder',
            'vehicle sales',
            'car rental',
            'workshop service',
            'test drive',
            'WooCommerce parts',
            'EV charger',
            'engine oil',
        );
        $hits = 0;
        foreach ( $signals as $signal ) {
            if ( false !== stripos( $content, $signal ) ) $hits++;
        }
        return $hits >= 2;
    }
}

if ( ! function_exists( 'wpbb_realestate_v138_canonical_home' ) ) {
    function wpbb_realestate_v138_canonical_home() {
        $profile = function_exists( 'wpbb_child_v62_profile' ) ? (array) wpbb_child_v62_profile() : apply_filters( 'wp_theme_demo_profile', array() );
        $content = '';
        if ( function_exists( 'wp_theme_build_sector_homepage_content' ) ) {
            $content = (string) wp_theme_build_sector_homepage_content();
        } elseif ( function_exists( 'wp_theme_demo_homepage_content' ) ) {
            $content = (string) wp_theme_demo_homepage_content();
        }
        if ( '' === trim( $content ) || wpbb_realestate_v138_content_is_cross_sector( $content ) ) {
            $content = function_exists( 'wpbb_child_v62_front_content' ) ? (string) wpbb_child_v62_front_content( $profile ) : $content;
        }
        if ( function_exists( 'wpbb_child_v72_repair_canonical_markup' ) ) {
            $content = (string) wpbb_child_v72_repair_canonical_markup( $content );
        }
        return $content;
    }
}

/*
 * Front-end guard: if a stale managed Home still contains another sector's
 * imported content, render the corrected Real Estate canonical page on this
 * request. The admin migration below persists the same repair.
 */
if ( ! function_exists( 'wpbb_realestate_v138_render_guard' ) ) {
    function wpbb_realestate_v138_render_guard( $content ) {
        if ( is_admin() || ! is_front_page() || ! in_the_loop() || ! is_main_query() ) return $content;
        if ( ! wpbb_realestate_v138_content_is_cross_sector( $content ) ) return $content;
        $clean = wpbb_realestate_v138_canonical_home();
        return '' !== trim( $clean ) ? $clean : $content;
    }
}
add_filter( 'the_content', 'wpbb_realestate_v138_render_guard', 1 );

if ( ! function_exists( 'wpbb_realestate_v138_refresh_property_media' ) ) {
    /** Refresh existing demo property attachments from the bundled 1200x900 sources. */
    function wpbb_realestate_v138_refresh_property_media() {
        $dir = trailingslashit( get_stylesheet_directory() ) . 'assets/img/properties/';
        foreach ( glob( $dir . '*.jpg' ) ?: array() as $source ) {
            $slug = sanitize_title( pathinfo( $source, PATHINFO_FILENAME ) );
            $ids = get_posts( array(
                'post_type'      => 'attachment',
                'post_status'    => 'inherit',
                'name'           => 'demo-property-image-' . $slug,
                'posts_per_page' => 1,
                'fields'         => 'ids',
            ) );
            if ( ! $ids ) continue;
            $attachment_id = absint( $ids[0] );
            $target = get_attached_file( $attachment_id );
            if ( ! $target ) continue;
            if ( strtolower( pathinfo( $target, PATHINFO_EXTENSION ) ) !== 'jpg' ) {
                $target = trailingslashit( dirname( $target ) ) . pathinfo( basename( $target ), PATHINFO_FILENAME ) . '.jpg';
                update_attached_file( $attachment_id, $target );
                wp_update_post( array( 'ID' => $attachment_id, 'post_mime_type' => 'image/jpeg' ) );
            }
            if ( ! @copy( $source, $target ) ) continue;
            if ( ! function_exists( 'wp_generate_attachment_metadata' ) ) require_once ABSPATH . 'wp-admin/includes/image.php';
            $meta = function_exists( 'wpbb_child_381048_generate_attachment_metadata' )
                ? wpbb_child_381048_generate_attachment_metadata( $attachment_id, $target )
                : wp_generate_attachment_metadata( $attachment_id, $target );
            if ( $meta ) wp_update_attachment_metadata( $attachment_id, $meta );
            clean_attachment_cache( $attachment_id );
        }
    }
}

if ( ! function_exists( 'wpbb_realestate_v138_repair_demo_posts' ) ) {
    /** Replace only clearly generated Automotive demo articles; authored posts are untouched. */
    function wpbb_realestate_v138_repair_demo_posts() {
        $articles = array(
            array(
                'title'   => __( 'What to check before booking a property viewing', 'wp-bbtheme-child-realestate' ),
                'excerpt' => __( 'A practical viewing checklist covering location, condition, running costs and the questions worth asking early.', 'wp-bbtheme-child-realestate' ),
            ),
            array(
                'title'   => __( 'How area guides help narrow a property shortlist', 'wp-bbtheme-child-realestate' ),
                'excerpt' => __( 'Use transport, schools, amenities and street-by-street context to compare homes beyond the listing headline.', 'wp-bbtheme-child-realestate' ),
            ),
            array(
                'title'   => __( 'Preparing a home for a realistic market valuation', 'wp-bbtheme-child-realestate' ),
                'excerpt' => __( 'The information and presentation that help an agent give clearer local pricing advice before a sale.', 'wp-bbtheme-child-realestate' ),
            ),
            array(
                'title'   => __( 'Buying or renting: which property details matter first?', 'wp-bbtheme-child-realestate' ),
                'excerpt' => __( 'A simple way to prioritise budget, location, space, tenure and move timing before comparing individual homes.', 'wp-bbtheme-child-realestate' ),
            ),
            array(
                'title'   => __( 'Understanding property status from available to under offer', 'wp-bbtheme-child-realestate' ),
                'excerpt' => __( 'What common listing statuses mean and when it is still useful to speak with the local agent.', 'wp-bbtheme-child-realestate' ),
            ),
            array(
                'title'   => __( 'Questions to ask before choosing a local estate agent', 'wp-bbtheme-child-realestate' ),
                'excerpt' => __( 'Compare local knowledge, communication, marketing and the practical route from valuation to completion.', 'wp-bbtheme-child-realestate' ),
            ),
        );

        $posts = get_posts( array(
            'post_type'      => 'post',
            'post_status'    => array( 'publish', 'draft', 'private' ),
            'posts_per_page' => 20,
            'orderby'        => 'date',
            'order'          => 'DESC',
        ) );
        $replace_index = 0;
        foreach ( $posts as $post ) {
            if ( $replace_index >= count( $articles ) ) break;
            $blob = (string) $post->post_title . "\n" . (string) $post->post_excerpt . "\n" . (string) $post->post_content;
            $generated = '1' === (string) get_post_meta( $post->ID, '_wp_theme_demo_generated', true );
            $demo_copy = false !== stripos( $blob, 'concise demo insight' ) || false !== stripos( $blob, 'demo insight' );
            $automotive_article = (bool) preg_match( '/\b(automotive|vehicle|vehicles|dealership|workshop|test drive|ev charger|engine oil|service checklist|car|cars)\b/i', $blob );
            if ( ! $automotive_article || ( ! $generated && ! $demo_copy ) ) continue;

            $article = $articles[ $replace_index++ ];
            $body = '<!-- wp:paragraph --><p>' . esc_html( $article['excerpt'] ) . '</p><!-- /wp:paragraph -->' .
                '<!-- wp:paragraph --><p>' . esc_html__( 'Use the listing facts as a starting point, then confirm the details that affect your own move with the local property team.', 'wp-bbtheme-child-realestate' ) . '</p><!-- /wp:paragraph -->';
            wp_update_post( array(
                'ID'           => $post->ID,
                'post_title'   => $article['title'],
                'post_name'    => sanitize_title( $article['title'] ),
                'post_excerpt' => $article['excerpt'],
                'post_content' => $body,
            ) );
            clean_post_cache( $post->ID );
        }
    }
}

if ( ! function_exists( 'wpbb_realestate_v138_repair_demo_once' ) ) {
    function wpbb_realestate_v138_repair_demo_once() {
        if ( ! is_admin() || ! current_user_can( 'manage_options' ) || wp_doing_ajax() ) return;

        $key = 'wpbb_realestate_v138_demo_repair_' . sanitize_key( get_stylesheet() );
        $front = absint( get_option( 'page_on_front' ) );
        $content = $front ? (string) get_post_field( 'post_content', $front, 'raw' ) : '';
        $cross_sector = $front && wpbb_realestate_v138_content_is_cross_sector( $content );

        /* A marker is only final while the current front page is actually clean. */
        if ( '3.8.11.38' === (string) get_option( $key ) && ( ! $front || ! $cross_sector ) ) return;
        if ( ! $front ) return;

        $managed = '1' === (string) get_post_meta( $front, '_wp_theme_demo_managed', true );
        if ( $managed || $cross_sector ) {
            delete_option( 'wpbb_child_v72_demo_system_' . sanitize_key( get_stylesheet() ) );
            if ( function_exists( 'wpbb_child_v62_rebuild_demo_pages' ) ) {
                wpbb_child_v62_rebuild_demo_pages( true );
            }

            /* Re-read the front-page ID because an importer may replace it. */
            $front = absint( get_option( 'page_on_front' ) );
            if ( $front ) {
                $fresh = (string) get_post_field( 'post_content', $front, 'raw' );
                if ( '' === trim( $fresh ) || wpbb_realestate_v138_content_is_cross_sector( $fresh ) ) {
                    $clean = wpbb_realestate_v138_canonical_home();
                    if ( '' !== trim( $clean ) && ! wpbb_realestate_v138_content_is_cross_sector( $clean ) ) {
                        wp_update_post( array( 'ID' => $front, 'post_content' => $clean ) );
                        update_post_meta( $front, '_wp_theme_demo_managed', '1' );
                        update_post_meta( $front, '_wpbb_child_bbuilder_version', '3.8.11.38' );
                        clean_post_cache( $front );
                    }
                }
            }
        }

        /* Parent demo pages are shared between child themes. Repair only managed pages that still carry Automotive copy. */
        $profile = function_exists( 'wpbb_child_v62_profile' ) ? (array) wpbb_child_v62_profile() : apply_filters( 'wp_theme_demo_profile', array() );
        foreach ( array( 'about', 'services', 'industries', 'contact' ) as $slug ) {
            $page = get_page_by_path( $slug );
            if ( ! $page instanceof WP_Post ) continue;
            if ( function_exists( 'wpbb_child_v71_is_source_language' ) && ! wpbb_child_v71_is_source_language( $page->ID ) ) continue;
            $is_managed = '1' === (string) get_post_meta( $page->ID, '_wp_theme_demo_managed', true ) || (bool) get_post_meta( $page->ID, '_wpbb_child_bbuilder_version', true );
            if ( ! $is_managed || ! wpbb_realestate_v138_content_is_cross_sector( (string) $page->post_content ) ) continue;
            if ( function_exists( 'wpbb_child_v62_simple_page_content' ) ) {
                $clean_page = (string) wpbb_child_v62_simple_page_content( $profile, $slug );
                if ( function_exists( 'wpbb_child_v72_repair_canonical_markup' ) ) $clean_page = (string) wpbb_child_v72_repair_canonical_markup( $clean_page );
                if ( '' !== trim( $clean_page ) && ! wpbb_realestate_v138_content_is_cross_sector( $clean_page ) ) {
                    wp_update_post( array( 'ID' => $page->ID, 'post_content' => $clean_page ) );
                    update_post_meta( $page->ID, '_wp_theme_demo_managed', '1' );
                    update_post_meta( $page->ID, '_wpbb_child_bbuilder_version', '3.8.11.38' );
                    clean_post_cache( $page->ID );
                }
            }
        }
        wpbb_realestate_v138_repair_demo_posts();
        wpbb_realestate_v138_refresh_property_media();

        $final = $front ? (string) get_post_field( 'post_content', $front, 'raw' ) : '';
        if ( $front && '' !== trim( $final ) && ! wpbb_realestate_v138_content_is_cross_sector( $final ) ) {
            update_option( $key, '3.8.11.38', false );
        }
    }
}
add_action( 'admin_init', 'wpbb_realestate_v138_repair_demo_once', PHP_INT_MAX );
add_action( 'wp_theme_after_demo_import', 'wpbb_realestate_v138_repair_demo_once', PHP_INT_MAX );
