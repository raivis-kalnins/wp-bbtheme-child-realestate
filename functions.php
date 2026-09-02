<?php
defined('ABSPATH') || exit;
function wpbb_realestate_project_mode($mode){ return 'realestate'; }
add_filter('wp_theme_project_mode','wpbb_realestate_project_mode');

function wpbb_realestate_needs_search_assets() {
	if ( is_post_type_archive( 'property' ) ) {
		return true;
	}
	if ( is_singular() ) {
		$post_id = get_queried_object_id();
		$content = $post_id ? (string) get_post_field( 'post_content', $post_id ) : '';
		return has_shortcode( $content, 'wp_theme_property_search' ) || has_shortcode( $content, 'wp_theme_properties' ) || has_block( 'wpbb/sector-finder', $content );
	}
	return false;
}

function wpbb_realestate_assets() {
    $theme = wp_get_theme();
    $manifest = get_stylesheet_directory() . '/dist/.vite/manifest.json';
    if (!is_readable($manifest)) return;
    $data = json_decode((string) file_get_contents($manifest), true);
    if (!is_array($data)) return;
    if (!empty($data['src/scss/public.scss']['file'])) {
        wp_enqueue_style('wpbb_realestate-app', get_stylesheet_directory_uri() . '/dist/' . ltrim($data['src/scss/public.scss']['file'], '/'), array(), $theme->get('Version'));
        if (function_exists('wp_theme_sector_customizer_css')) wp_add_inline_style('wpbb_realestate-app', wp_theme_sector_customizer_css('#214e3b', '10px', '--sector-primary', '--sector-radius'));
    }
    if (!empty($data['src/js/main.js']['file'])) wp_enqueue_script('wpbb_realestate-app', get_stylesheet_directory_uri() . '/dist/' . ltrim($data['src/js/main.js']['file'], '/'), array(), $theme->get('Version'), true);
}
add_action('wp_enqueue_scripts', 'wpbb_realestate_assets', 30);

function wpbb_realestate_dark_mode_bootstrap() { echo '<script>(function(){try{var m=localStorage.getItem("wpThemeMode");if(m==="dark"){document.documentElement.classList.add("is-dark-theme");document.documentElement.setAttribute("data-theme","dark");}}catch(e){}})();</script>'; }
add_action('wp_head', 'wpbb_realestate_dark_mode_bootstrap', 1);

function wpbb_realestate_search_assets(){ if(!wpbb_realestate_needs_search_assets()) return; $f=get_stylesheet_directory().'/assets/js/property-search.js'; if(is_readable($f)) wp_enqueue_script('wpbb-realestate-search',get_stylesheet_directory_uri().'/assets/js/property-search.js',array(),filemtime($f),true); }
add_action('wp_enqueue_scripts','wpbb_realestate_search_assets',35);
function wpbb_realestate_demo_profile( $profile ) {
	$assets = trailingslashit( get_stylesheet_directory_uri() ) . 'assets/img/properties/';
	return array_merge( $profile, array(
		'id' => 'realestate', 'name' => __( 'Real Estate Agency', 'wp-bbtheme-child-realestate' ), 'commerce' => false,
        'services_eyebrow' => __( 'Agency services', 'wp-bbtheme-child-realestate' ),
        'services_heading' => __( 'Local advice for selling, buying, letting and valuation.', 'wp-bbtheme-child-realestate' ),
        'about_eyebrow' => __( 'Local knowledge', 'wp-bbtheme-child-realestate' ),
        'industries_eyebrow' => __( 'Property journeys', 'wp-bbtheme-child-realestate' ),
        'industries_heading' => __( 'Property search and advice organised around the decision you are making.', 'wp-bbtheme-child-realestate' ),
        'process_eyebrow' => __( 'From search to keys', 'wp-bbtheme-child-realestate' ),
        'process_heading' => __( 'A clearer route from first enquiry to viewing, offer and move.', 'wp-bbtheme-child-realestate' ),
        'faq_heading' => __( 'The questions people ask before they book a viewing.', 'wp-bbtheme-child-realestate' ),
		'eyebrow' => __( 'London and Surrey property experts', 'wp-bbtheme-child-realestate' ),
		'hero_title' => __( 'Find a home that fits the way you want to live.', 'wp-bbtheme-child-realestate' ),
		'hero_text' => __( 'Search homes for sale and to rent, compare the details that matter and speak to a genuinely local team.', 'wp-bbtheme-child-realestate' ),
		'hero_image' => $assets . 'willow-house.jpg', 'about_image' => $assets . 'cedar-cottage.jpg',
		'primary_label' => __( 'Search properties', 'wp-bbtheme-child-realestate' ), 'primary_url' => '#properties',
		'secondary_label' => __( 'Book a valuation', 'wp-bbtheme-child-realestate' ), 'secondary_url' => '#contact',
		'industries' => array( __( 'Homes for sale', 'wp-bbtheme-child-realestate' ), __( 'New developments', 'wp-bbtheme-child-realestate' ), __( 'Lettings', 'wp-bbtheme-child-realestate' ), __( 'Valuations', 'wp-bbtheme-child-realestate' ) ),
		'services' => array(
			array( __( 'Buying', 'wp-bbtheme-child-realestate' ), __( 'Local insight and straightforward support from viewing to completion.', 'wp-bbtheme-child-realestate' ) ),
			array( __( 'Selling', 'wp-bbtheme-child-realestate' ), __( 'Thoughtful presentation, realistic advice and a clear sales plan.', 'wp-bbtheme-child-realestate' ) ),
			array( __( 'Lettings', 'wp-bbtheme-child-realestate' ), __( 'Practical management for landlords and responsive help for tenants.', 'wp-bbtheme-child-realestate' ) ),
		),
	) );
}
add_filter( 'wp_theme_demo_profile', 'wpbb_realestate_demo_profile' );

function wpbb_realestate_register_content() {
	register_post_type( 'property', array(
		'labels' => array( 'name' => __( 'Properties', 'wp-bbtheme-child-realestate' ), 'singular_name' => __( 'Property', 'wp-bbtheme-child-realestate' ), 'add_new_item' => __( 'Add New Property', 'wp-bbtheme-child-realestate' ), 'edit_item' => __( 'Edit Property', 'wp-bbtheme-child-realestate' ) ),
		'public' => true, 'show_in_rest' => true, 'has_archive' => true, 'rewrite' => array( 'slug' => 'properties' ), 'menu_icon' => 'dashicons-building', 'menu_position' => 22,
		'supports' => array( 'title', 'editor', 'excerpt', 'thumbnail', 'custom-fields', 'page-attributes' ),
	) );
	register_taxonomy( 'property_type', 'property', array(
		'labels' => array( 'name' => __( 'Property Types', 'wp-bbtheme-child-realestate' ), 'singular_name' => __( 'Property Type', 'wp-bbtheme-child-realestate' ) ),
		'public' => true, 'hierarchical' => true, 'show_in_rest' => true, 'rewrite' => array( 'slug' => 'property-type' ),
	) );
	$meta = array( 'property_price' => 'integer', 'property_bedrooms' => 'integer', 'property_bathrooms' => 'integer', 'property_size' => 'integer', 'property_location' => 'string', 'property_postcode' => 'string', 'property_listing_type' => 'string', 'property_marketing_status' => 'string', 'property_tenure' => 'string' );
	foreach ( $meta as $key => $type ) {
		register_post_meta( 'property', $key, array( 'type' => $type, 'single' => true, 'show_in_rest' => true, 'sanitize_callback' => 'string' === $type ? 'sanitize_text_field' : 'absint', 'auth_callback' => function() { return current_user_can( 'edit_posts' ); } ) );
	}
}
add_action( 'init', 'wpbb_realestate_register_content' );

function wpbb_realestate_property_fields() {
	return array(
		'property_price' => __( 'Price / monthly rent (£)', 'wp-bbtheme-child-realestate' ), 'property_bedrooms' => __( 'Bedrooms', 'wp-bbtheme-child-realestate' ),
		'property_bathrooms' => __( 'Bathrooms', 'wp-bbtheme-child-realestate' ), 'property_size' => __( 'Floor area (sq ft)', 'wp-bbtheme-child-realestate' ),
		'property_location' => __( 'Area', 'wp-bbtheme-child-realestate' ), 'property_postcode' => __( 'Postcode', 'wp-bbtheme-child-realestate' ),
		'property_listing_type' => __( 'Listing type (sale or rent)', 'wp-bbtheme-child-realestate' ), 'property_marketing_status' => __( 'Marketing status', 'wp-bbtheme-child-realestate' ),
		'property_tenure' => __( 'Tenure / furnishing', 'wp-bbtheme-child-realestate' ),
	);
}

function wpbb_realestate_add_details_box() {
	add_meta_box( 'wpbb-property-details', __( 'Property details', 'wp-bbtheme-child-realestate' ), 'wpbb_realestate_details_box', 'property', 'normal', 'high' );
}
add_action( 'add_meta_boxes', 'wpbb_realestate_add_details_box' );

function wpbb_realestate_details_box( $post ) {
	wp_nonce_field( 'wpbb_realestate_save_details', 'wpbb_realestate_details_nonce' );
	echo '<div class="wpbb-property-admin-fields" style="display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:16px">';
	foreach ( wpbb_realestate_property_fields() as $field => $label ) {
		echo '<label><strong style="display:block;margin-bottom:5px">' . esc_html( $label ) . '</strong><input class="widefat" name="' . esc_attr( $field ) . '" value="' . esc_attr( get_post_meta( $post->ID, $field, true ) ) . '"></label>';
	}
	echo '</div>';
}

function wpbb_realestate_save_details( $post_id ) {
	if ( empty( $_POST['wpbb_realestate_details_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['wpbb_realestate_details_nonce'] ) ), 'wpbb_realestate_save_details' ) || ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	foreach ( wpbb_realestate_property_fields() as $field => $label ) {
		if ( ! isset( $_POST[ $field ] ) ) { continue; }
		$value = wp_unslash( $_POST[ $field ] );
		$value = in_array( $field, array( 'property_price', 'property_bedrooms', 'property_bathrooms', 'property_size' ), true ) ? absint( $value ) : sanitize_text_field( $value );
		update_post_meta( $post_id, $field, $value );
	}
}
add_action( 'save_post_property', 'wpbb_realestate_save_details' );

function wpbb_realestate_demo_image_attachment( $slug, $title ) {
	$source = get_stylesheet_directory() . '/assets/img/properties/' . $slug . '.jpg';
	if ( ! is_readable( $source ) ) { return 0; }
	$attachment_slug = 'demo-property-image-' . $slug;
	$existing = get_posts( array( 'post_type' => 'attachment', 'name' => $attachment_slug, 'post_status' => 'inherit', 'posts_per_page' => 1, 'fields' => 'ids' ) );
	if ( $existing ) { return (int) $existing[0]; }
	$upload = wp_upload_dir();
	if ( ! empty( $upload['error'] ) ) { return 0; }
	$filename = wp_unique_filename( $upload['path'], $attachment_slug . '.jpg' );
	$target = trailingslashit( $upload['path'] ) . $filename;
	if ( ! copy( $source, $target ) ) { return 0; }
	$id = wp_insert_attachment( array( 'post_mime_type' => 'image/jpeg', 'post_title' => $title, 'post_name' => $attachment_slug, 'post_status' => 'inherit', 'guid' => trailingslashit( $upload['url'] ) . $filename ), $target );
	if ( is_wp_error( $id ) ) { return 0; }
	require_once ABSPATH . 'wp-admin/includes/image.php';
	wp_update_attachment_metadata( $id, wp_generate_attachment_metadata( $id, $target ) );
	return (int) $id;
}

function wpbb_realestate_seed_properties() {
	$properties = array(
		array( 'slug' => 'willow-house', 'title' => 'Willow House', 'type' => 'Detached', 'location' => 'Richmond', 'postcode' => 'TW10', 'price' => 1250000, 'beds' => 4, 'baths' => 3, 'size' => 2450, 'listing' => 'sale', 'status' => 'For sale', 'tenure' => 'Freehold' ),
		array( 'slug' => 'the-glassworks', 'title' => 'The Glassworks', 'type' => 'Apartment', 'location' => 'Shoreditch', 'postcode' => 'E2', 'price' => 3750, 'beds' => 2, 'baths' => 2, 'size' => 980, 'listing' => 'rent', 'status' => 'To rent', 'tenure' => 'Long let' ),
		array( 'slug' => 'garden-mews', 'title' => 'Garden Mews', 'type' => 'Townhouse', 'location' => 'Clapham', 'postcode' => 'SW4', 'price' => 875000, 'beds' => 3, 'baths' => 2, 'size' => 1610, 'listing' => 'sale', 'status' => 'Guide price', 'tenure' => 'Freehold' ),
		array( 'slug' => 'riverside-loft', 'title' => 'Riverside Loft', 'type' => 'Apartment', 'location' => 'Battersea', 'postcode' => 'SW11', 'price' => 3250, 'beds' => 2, 'baths' => 2, 'size' => 1040, 'listing' => 'rent', 'status' => 'Available now', 'tenure' => 'Furnished' ),
		array( 'slug' => 'cedar-cottage', 'title' => 'Cedar Cottage', 'type' => 'Cottage', 'location' => 'Surrey Hills', 'postcode' => 'GU5', 'price' => 980000, 'beds' => 3, 'baths' => 2, 'size' => 1840, 'listing' => 'sale', 'status' => 'Offers over', 'tenure' => 'Freehold' ),
		array( 'slug' => 'park-view', 'title' => 'Park View', 'type' => 'Apartment', 'location' => 'Hampstead', 'postcode' => 'NW3', 'price' => 840000, 'beds' => 2, 'baths' => 1, 'size' => 910, 'listing' => 'sale', 'status' => 'For sale', 'tenure' => 'Share of freehold' ),
		array( 'slug' => 'oak-residence', 'title' => 'Oak Residence', 'type' => 'Detached', 'location' => 'Wimbledon', 'postcode' => 'SW19', 'price' => 1495000, 'beds' => 5, 'baths' => 3, 'size' => 2780, 'listing' => 'sale', 'status' => 'New instruction', 'tenure' => 'Freehold' ),
		array( 'slug' => 'kensington-court', 'title' => 'Kensington Court', 'type' => 'Apartment', 'location' => 'Kensington', 'postcode' => 'W8', 'price' => 4650, 'beds' => 2, 'baths' => 2, 'size' => 1120, 'listing' => 'rent', 'status' => 'To rent', 'tenure' => 'Furnished' ),
		array( 'slug' => 'harbour-house', 'title' => 'Harbour House', 'type' => 'Townhouse', 'location' => 'Putney', 'postcode' => 'SW15', 'price' => 1095000, 'beds' => 4, 'baths' => 3, 'size' => 2180, 'listing' => 'sale', 'status' => 'Guide price', 'tenure' => 'Freehold' ),
		array( 'slug' => 'elm-gardens', 'title' => 'Elm Gardens', 'type' => 'Apartment', 'location' => 'Richmond', 'postcode' => 'TW9', 'price' => 2950, 'beds' => 2, 'baths' => 2, 'size' => 940, 'listing' => 'rent', 'status' => 'Available now', 'tenure' => 'Unfurnished' ),
	);
	foreach ( $properties as $index => $item ) {
		$post_slug = 'demo-property-' . ( $index + 1 );
		$post = get_page_by_path( $post_slug, OBJECT, 'property' );
		$args = array( 'post_title' => $item['title'], 'post_name' => $post_slug, 'post_status' => 'publish', 'post_type' => 'property', 'menu_order' => $index, 'post_excerpt' => sprintf( __( 'A beautifully presented %1$d bedroom home in %2$s.', 'wp-bbtheme-child-realestate' ), $item['beds'], $item['location'] ), 'post_content' => '<!-- wp:heading {"level":2} --><h2 class="wp-block-heading">' . esc_html__( 'About this property', 'wp-bbtheme-child-realestate' ) . '</h2><!-- /wp:heading --><!-- wp:paragraph --><p>' . esc_html__( 'A light, carefully maintained home with well-planned living space, useful storage and excellent local connections. Full particulars, floor-plan information and viewing arrangements are available from the team.', 'wp-bbtheme-child-realestate' ) . '</p><!-- /wp:paragraph -->' );
		if ( $post instanceof WP_Post ) { $args['ID'] = $post->ID; $id = wp_update_post( $args ); } else { $id = wp_insert_post( $args ); }
		if ( ! $id || is_wp_error( $id ) ) { continue; }
		wp_set_object_terms( $id, $item['type'], 'property_type' );
		$fields = array( 'price' => 'price', 'bedrooms' => 'beds', 'bathrooms' => 'baths', 'size' => 'size', 'location' => 'location', 'postcode' => 'postcode', 'listing_type' => 'listing', 'marketing_status' => 'status', 'tenure' => 'tenure' );
		foreach ( $fields as $meta => $data_key ) { update_post_meta( $id, 'property_' . $meta, $item[ $data_key ] ); }
		update_post_meta( $id, '_wp_theme_demo_property', 1 );
		$image_id = wpbb_realestate_demo_image_attachment( $item['slug'], $item['title'] );
		if ( $image_id ) { set_post_thumbnail( $id, $image_id ); update_post_meta( $image_id, '_wp_attachment_image_alt', sprintf( __( 'Exterior of %s', 'wp-bbtheme-child-realestate' ), $item['title'] ) ); }
	}
	flush_rewrite_rules( false );
}
add_action( 'wp_theme_before_demo_import', 'wpbb_realestate_seed_properties' );

function wpbb_realestate_search_request() {
	$request = array();
	foreach ( array( 'listing_type', 'location', 'property_type', 'min_price', 'max_price', 'bedrooms', 'bathrooms', 'tenure', 'keyword', 'sort' ) as $key ) {
		if ( isset( $_REQUEST[ $key ] ) ) { $request[ $key ] = sanitize_text_field( wp_unslash( $_REQUEST[ $key ] ) ); }
	}
	return $request;
}

function wpbb_realestate_query_args( $request, $limit = 6 ) {
	$args = array( 'post_type' => 'property', 'post_status' => 'publish', 'posts_per_page' => max( 1, min( 24, absint( $limit ) ) ) );
	$meta = array( 'relation' => 'AND' );
	if ( ! empty( $request['listing_type'] ) && in_array( $request['listing_type'], array( 'sale', 'rent' ), true ) ) { $meta[] = array( 'key' => 'property_listing_type', 'value' => $request['listing_type'] ); }
	if ( ! empty( $request['location'] ) ) {
		$meta[] = array( 'relation' => 'OR', array( 'key' => 'property_location', 'value' => $request['location'], 'compare' => 'LIKE' ), array( 'key' => 'property_postcode', 'value' => $request['location'], 'compare' => 'LIKE' ) );
	}
	foreach ( array( 'min_price' => '>=', 'max_price' => '<=', 'bedrooms' => '>=', 'bathrooms' => '>=' ) as $field => $compare ) {
		if ( isset( $request[ $field ] ) && '' !== $request[ $field ] ) {
			$key = false !== strpos( $field, 'price' ) ? 'property_price' : 'property_' . $field;
			$meta[] = array( 'key' => $key, 'value' => absint( $request[ $field ] ), 'compare' => $compare, 'type' => 'NUMERIC' );
		}
	}
	if ( ! empty( $request['tenure'] ) ) { $meta[] = array( 'key' => 'property_tenure', 'value' => $request['tenure'], 'compare' => 'LIKE' ); }
	if ( count( $meta ) > 1 ) { $args['meta_query'] = $meta; }
	if ( ! empty( $request['property_type'] ) ) { $args['tax_query'] = array( array( 'taxonomy' => 'property_type', 'field' => 'slug', 'terms' => sanitize_title( $request['property_type'] ) ) ); }
	if ( ! empty( $request['keyword'] ) ) { $args['s'] = $request['keyword']; }
	$sort = isset( $request['sort'] ) ? $request['sort'] : 'featured';
	if ( in_array( $sort, array( 'price-asc', 'price-desc' ), true ) ) { $args['meta_key'] = 'property_price'; $args['orderby'] = 'meta_value_num'; $args['order'] = 'price-asc' === $sort ? 'ASC' : 'DESC'; }
	elseif ( 'newest' === $sort ) { $args['orderby'] = 'date'; $args['order'] = 'DESC'; }
	else { $args['orderby'] = array( 'menu_order' => 'ASC', 'date' => 'DESC' ); }
	return $args;
}

function wpbb_realestate_card_markup( $post_id ) {
	$terms = get_the_terms( $post_id, 'property_type' );
	$type = $terms && ! is_wp_error( $terms ) ? $terms[0]->name : __( 'Property', 'wp-bbtheme-child-realestate' );
	$price = absint( get_post_meta( $post_id, 'property_price', true ) ); $beds = absint( get_post_meta( $post_id, 'property_bedrooms', true ) );
	$baths = absint( get_post_meta( $post_id, 'property_bathrooms', true ) ); $size = absint( get_post_meta( $post_id, 'property_size', true ) );
	$listing = get_post_meta( $post_id, 'property_listing_type', true ); $status = get_post_meta( $post_id, 'property_marketing_status', true );
	$place = trim( get_post_meta( $post_id, 'property_location', true ) . ' ' . get_post_meta( $post_id, 'property_postcode', true ) );
	$image = function_exists( 'wp_theme_item_gallery_card_inner' ) ? wp_theme_item_gallery_card_inner( $post_id, 'large', 4 ) : get_the_post_thumbnail( $post_id, 'large', array( 'loading' => 'lazy' ) );
	if ( ! $image ) { $image = '<span class="wp-theme-property-card__placeholder" aria-hidden="true"></span>'; }
	$price_label = 'rent' === $listing ? sprintf( __( '£%s pcm', 'wp-bbtheme-child-realestate' ), number_format_i18n( $price ) ) : '£' . number_format_i18n( $price );
	return '<article class="wp-theme-property-card" data-property-id="' . esc_attr( $post_id ) . '"><div class="wp-theme-property-card__media"><a class="wp-theme-property-card__image" href="' . esc_url( get_permalink( $post_id ) ) . '">' . $image . '</a><span class="wp-theme-property-card__status">' . esc_html( $status ) . '</span><button class="wp-theme-property-save" type="button" data-save-property="' . esc_attr( $post_id ) . '" aria-label="' . esc_attr__( 'Save property', 'wp-bbtheme-child-realestate' ) . '" aria-pressed="false">♡</button></div><div class="wp-theme-property-card__body"><p class="wp-theme-property-card__type">' . esc_html( $type ) . '</p><p class="wp-theme-property-card__price">' . esc_html( $price_label ) . '</p><h3><a href="' . esc_url( get_permalink( $post_id ) ) . '">' . esc_html( get_the_title( $post_id ) ) . '</a></h3><p class="wp-theme-property-card__location">' . esc_html( $place ) . '</p><div class="wp-theme-property-card__facts"><span>' . esc_html( sprintf( _n( '%d bed', '%d beds', $beds, 'wp-bbtheme-child-realestate' ), $beds ) ) . '</span><span>' . esc_html( sprintf( _n( '%d bath', '%d baths', $baths, 'wp-bbtheme-child-realestate' ), $baths ) ) . '</span><span>' . esc_html( number_format_i18n( $size ) . ' sq ft' ) . '</span></div></div></article>';
}

function wpbb_realestate_results_markup( $request, $limit = 6 ) {
	$query = new WP_Query( wpbb_realestate_query_args( $request, $limit ) );
	$view = isset( $request['view'] ) && 'list' === $request['view'] ? 'list' : 'grid';
	$html = '<div class="wp-theme-property-results-head"><p><strong>' . esc_html( sprintf( _n( '%d property', '%d properties', $query->found_posts, 'wp-bbtheme-child-realestate' ), $query->found_posts ) ) . '</strong> ' . esc_html__( 'matching your search', 'wp-bbtheme-child-realestate' ) . '</p><div class="wp-theme-property-view" aria-label="' . esc_attr__( 'Results view', 'wp-bbtheme-child-realestate' ) . '"><button type="button" data-property-view="grid" aria-pressed="' . ( 'grid' === $view ? 'true' : 'false' ) . '">' . esc_html__( 'Grid', 'wp-bbtheme-child-realestate' ) . '</button><button type="button" data-property-view="list" aria-pressed="' . ( 'list' === $view ? 'true' : 'false' ) . '">' . esc_html__( 'List', 'wp-bbtheme-child-realestate' ) . '</button></div></div>';
	if ( ! $query->have_posts() ) { return $html . '<div class="wp-theme-property-empty"><h3>' . esc_html__( 'No exact matches yet', 'wp-bbtheme-child-realestate' ) . '</h3><p>' . esc_html__( 'Try a wider location, fewer bedrooms or clearing the price range.', 'wp-bbtheme-child-realestate' ) . '</p></div>'; }
	$html .= '<div class="wp-theme-property-grid is-' . esc_attr( $view ) . '">';
	while ( $query->have_posts() ) { $query->the_post(); $html .= wpbb_realestate_card_markup( get_the_ID() ); }
	wp_reset_postdata();
	return $html . '</div>';
}

function wpbb_realestate_filter_options( $taxonomy ) {
	$options = '';
	foreach ( get_terms( array( 'taxonomy' => $taxonomy, 'hide_empty' => true ) ) as $term ) { $options .= '<option value="' . esc_attr( $term->slug ) . '">' . esc_html( $term->name ) . '</option>'; }
	return $options;
}

function wpbb_realestate_properties_shortcode( $atts ) {
	$atts = shortcode_atts( array( 'limit' => 6 ), $atts, 'wp_theme_property_search' );
	$request = wpbb_realestate_search_request(); $limit = max( 1, absint( $atts['limit'] ) );
	$listing = isset( $request['listing_type'] ) ? $request['listing_type'] : 'sale';
	$request['listing_type'] = $listing;
	$form = '<section id="properties" class="wp-theme-property-finder"><div class="wp-theme-property-finder__intro"><p class="wp-theme-sector-eyebrow">' . esc_html__( 'Property search', 'wp-bbtheme-child-realestate' ) . '</p><h2>' . esc_html__( 'Start with a place. Refine from there.', 'wp-bbtheme-child-realestate' ) . '</h2><p>' . esc_html__( 'Search by area or postcode, then narrow the results by the details that matter to you.', 'wp-bbtheme-child-realestate' ) . '</p></div><form class="wp-theme-property-search" method="get" action="' . esc_url( get_post_type_archive_link( 'property' ) ) . '" data-property-search><input type="hidden" name="action" value="wpbb_property_search"><input type="hidden" name="nonce" value="' . esc_attr( wp_create_nonce( 'wpbb_property_search' ) ) . '"><input type="hidden" name="limit" value="' . esc_attr( $limit ) . '"><input type="hidden" name="listing_type" value="' . esc_attr( $listing ) . '"><div class="wp-theme-property-tabs" role="group" aria-label="' . esc_attr__( 'Search intent', 'wp-bbtheme-child-realestate' ) . '"><button type="button" data-listing-type="sale" aria-pressed="' . ( 'rent' !== $listing ? 'true' : 'false' ) . '">' . esc_html__( 'Buy', 'wp-bbtheme-child-realestate' ) . '</button><button type="button" data-listing-type="rent" aria-pressed="' . ( 'rent' === $listing ? 'true' : 'false' ) . '">' . esc_html__( 'Rent', 'wp-bbtheme-child-realestate' ) . '</button></div><div class="wp-theme-property-search__main"><label class="wp-theme-property-location"><span>' . esc_html__( 'Location', 'wp-bbtheme-child-realestate' ) . '</span><input name="location" value="' . esc_attr( isset( $request['location'] ) ? $request['location'] : '' ) . '" placeholder="' . esc_attr__( 'Area or postcode', 'wp-bbtheme-child-realestate' ) . '"></label><label><span>' . esc_html__( 'Property type', 'wp-bbtheme-child-realestate' ) . '</span><select name="property_type"><option value="">' . esc_html__( 'Any type', 'wp-bbtheme-child-realestate' ) . '</option>' . wpbb_realestate_filter_options( 'property_type' ) . '</select></label><label><span>' . esc_html__( 'Min price', 'wp-bbtheme-child-realestate' ) . '</span><input type="number" name="min_price" min="0" step="100" value="' . esc_attr( isset( $request['min_price'] ) ? $request['min_price'] : '' ) . '" placeholder="' . esc_attr__( 'No min', 'wp-bbtheme-child-realestate' ) . '"></label><label><span>' . esc_html__( 'Max price', 'wp-bbtheme-child-realestate' ) . '</span><input type="number" name="max_price" min="0" step="100" value="' . esc_attr( isset( $request['max_price'] ) ? $request['max_price'] : '' ) . '" placeholder="' . esc_attr__( 'No max', 'wp-bbtheme-child-realestate' ) . '"></label><label><span>' . esc_html__( 'Bedrooms', 'wp-bbtheme-child-realestate' ) . '</span><select name="bedrooms"><option value="">' . esc_html__( 'Any beds', 'wp-bbtheme-child-realestate' ) . '</option><option value="1">1+</option><option value="2">2+</option><option value="3">3+</option><option value="4">4+</option></select></label><button class="wp-theme-property-search__submit" type="submit">' . esc_html__( 'Search', 'wp-bbtheme-child-realestate' ) . '</button></div><details class="wp-theme-property-more"><summary>' . esc_html__( 'More filters', 'wp-bbtheme-child-realestate' ) . '</summary><div><label><span>' . esc_html__( 'Bathrooms', 'wp-bbtheme-child-realestate' ) . '</span><select name="bathrooms"><option value="">' . esc_html__( 'Any bathrooms', 'wp-bbtheme-child-realestate' ) . '</option><option value="1">1+</option><option value="2">2+</option><option value="3">3+</option></select></label><label><span>' . esc_html__( 'Tenure / furnishing', 'wp-bbtheme-child-realestate' ) . '</span><input name="tenure" value="' . esc_attr( isset( $request['tenure'] ) ? $request['tenure'] : '' ) . '" placeholder="' . esc_attr__( 'e.g. Freehold', 'wp-bbtheme-child-realestate' ) . '"></label><label><span>' . esc_html__( 'Keyword', 'wp-bbtheme-child-realestate' ) . '</span><input name="keyword" value="' . esc_attr( isset( $request['keyword'] ) ? $request['keyword'] : '' ) . '" placeholder="' . esc_attr__( 'e.g. garden', 'wp-bbtheme-child-realestate' ) . '"></label><label><span>' . esc_html__( 'Sort by', 'wp-bbtheme-child-realestate' ) . '</span><select name="sort"><option value="featured">' . esc_html__( 'Featured', 'wp-bbtheme-child-realestate' ) . '</option><option value="newest">' . esc_html__( 'Newest', 'wp-bbtheme-child-realestate' ) . '</option><option value="price-asc">' . esc_html__( 'Price: low to high', 'wp-bbtheme-child-realestate' ) . '</option><option value="price-desc">' . esc_html__( 'Price: high to low', 'wp-bbtheme-child-realestate' ) . '</option></select></label><button type="reset" data-property-clear>' . esc_html__( 'Clear filters', 'wp-bbtheme-child-realestate' ) . '</button><button type="button" data-save-search>' . esc_html__( 'Save this search', 'wp-bbtheme-child-realestate' ) . '</button></div></details></form><div class="wp-theme-property-results" data-property-results aria-live="polite">' . wpbb_realestate_results_markup( $request, $limit ) . '</div></section>';
	return $form;
}
add_shortcode( 'wp_theme_property_search', 'wpbb_realestate_properties_shortcode' );
add_shortcode( 'wp_theme_properties', 'wpbb_realestate_properties_shortcode' );

function wpbb_realestate_ajax_search() {
	check_ajax_referer( 'wpbb_property_search', 'nonce' );
	$request = wpbb_realestate_search_request(); $request['view'] = isset( $_REQUEST['view'] ) ? sanitize_key( wp_unslash( $_REQUEST['view'] ) ) : 'grid';
	wp_send_json_success( array( 'html' => wpbb_realestate_results_markup( $request, isset( $_REQUEST['limit'] ) ? absint( $_REQUEST['limit'] ) : 6 ) ) );
}
add_action( 'wp_ajax_wpbb_property_search', 'wpbb_realestate_ajax_search' );
add_action( 'wp_ajax_nopriv_wpbb_property_search', 'wpbb_realestate_ajax_search' );

function wpbb_realestate_home_search( $content ) {
	return $content . '<!-- wp:group {"className":"wp-theme-property-search-section"} --><div class="wp-block-group wp-theme-property-search-section"><div class="container"><!-- wp:wpbb/sector-finder {"context":"realestate","limit":6} /--></div></div><!-- /wp:group -->';
}
add_filter( 'wp_theme_demo_after_hero_sections', 'wpbb_realestate_home_search' );

function wpbb_realestate_sector_finder_render_v37( $html, $context, $attributes ) {
    if ( 'realestate' !== $context ) return $html;
    return wpbb_realestate_properties_shortcode( array( 'limit' => absint( $attributes['limit'] ?? 6 ) ) );
}
add_filter( 'wp_theme_sector_finder_render', 'wpbb_realestate_sector_finder_render_v37', 20, 3 );


function wpbb_realestate_menu_item( $items ) {
	$item = array(
		'key'       => 'properties',
		'title'     => __( 'Properties', 'wp-bbtheme-child-realestate' ),
		'type'      => 'post_type_archive',
		'object'    => 'property',
		'locations' => array( 'header', 'footer' ),
	);
	array_splice( $items, 1, 0, array( $item ) );
	return $items;
}
add_filter( 'wp_theme_demo_navigation_items', 'wpbb_realestate_menu_item' );

function wpbb_realestate_single_details( $content ) {
	if ( ! is_singular( 'property' ) || ! in_the_loop() || ! is_main_query() ) { return $content; }
	$id      = get_the_ID();
	$listing = get_post_meta( $id, 'property_listing_type', true );
	$price   = absint( get_post_meta( $id, 'property_price', true ) );
	$price   = 'rent' === $listing ? '£' . number_format_i18n( $price ) . ' pcm' : '£' . number_format_i18n( $price );
	$image   = get_the_post_thumbnail_url( $id, 'full' );
	$gallery = function_exists( 'wp_theme_item_gallery_single_markup' ) ? wp_theme_item_gallery_single_markup( $id ) : '';
	$place   = trim( get_post_meta( $id, 'property_location', true ) . ' ' . get_post_meta( $id, 'property_postcode', true ) );
	$status  = get_post_meta( $id, 'property_marketing_status', true );

	$summary = '<aside class="wp-theme-property-summary motion-fade-up"><p class="wp-theme-sector-eyebrow">' . esc_html( $status ) . '</p><p class="wp-theme-property-summary__price">' . esc_html( $price ) . '</p><dl><div><dt>' . esc_html__( 'Bedrooms', 'wp-bbtheme-child-realestate' ) . '</dt><dd>' . esc_html( get_post_meta( $id, 'property_bedrooms', true ) ) . '</dd></div><div><dt>' . esc_html__( 'Bathrooms', 'wp-bbtheme-child-realestate' ) . '</dt><dd>' . esc_html( get_post_meta( $id, 'property_bathrooms', true ) ) . '</dd></div><div><dt>' . esc_html__( 'Floor area', 'wp-bbtheme-child-realestate' ) . '</dt><dd>' . esc_html( number_format_i18n( absint( get_post_meta( $id, 'property_size', true ) ) ) . ' sq ft' ) . '</dd></div><div><dt>' . esc_html__( 'Tenure', 'wp-bbtheme-child-realestate' ) . '</dt><dd>' . esc_html( get_post_meta( $id, 'property_tenure', true ) ) . '</dd></div></dl><a class="btn btn-primary w-100" href="' . esc_url( home_url( '/contact/?property=' . rawurlencode( get_the_title( $id ) ) ) ) . '">' . esc_html__( 'Arrange a viewing', 'wp-bbtheme-child-realestate' ) . '</a></aside>';

	$html  = '<article class="wp-theme-property-single"><div class="container">';
	$html .= '<div class="wp-theme-property-single__heading motion-fade-up"><p class="wp-theme-sector-eyebrow">' . esc_html( $place ) . '</p><h1>' . esc_html( get_the_title( $id ) ) . '</h1></div>';
	$html .= '<div class="row g-4 g-xl-5 align-items-start"><div class="col-12 col-xl-8">';
	if ( $gallery ) $html .= $gallery;
	elseif ( $image ) $html .= '<figure class="wp-theme-property-single__media motion-fade-up"><img src="' . esc_url( $image ) . '" alt="' . esc_attr( get_the_title( $id ) ) . '"></figure>';
	$html .= '<div class="wp-theme-property-single__content motion-fade-up"><h2>' . esc_html__( 'About this property', 'wp-bbtheme-child-realestate' ) . '</h2>' . $content . '</div></div>';
	$html .= '<div class="col-12 col-xl-4">' . $summary . '</div></div></div></article>';
	return $html;
}
add_filter( 'the_content', 'wpbb_realestate_single_details', 15 );

function wpbb_realestate_demo_profile_premium( $profile ) {
	if ( empty( $profile['id'] ) || 'realestate' !== $profile['id'] ) { return $profile; }
	$profile['about_title'] = __( 'Local property knowledge, presented with less noise.', 'wp-bbtheme-child-realestate' );
	$profile['about_text'] = __( 'Search-led property discovery with useful area context, clear listing information and a calm path from first browse to valuation or viewing.', 'wp-bbtheme-child-realestate' );
	$profile['stats'] = array(
		array( '10', __( 'Available listings', 'wp-bbtheme-child-realestate' ) ),
		array( '3', __( 'Area-guide examples', 'wp-bbtheme-child-realestate' ) ),
		array( '7', __( 'Useful finder controls', 'wp-bbtheme-child-realestate' ) ),
		array( '0', __( 'WooCommerce dependencies', 'wp-bbtheme-child-realestate' ) ),
	);
	$profile['process'] = array(
		array( '01', __( 'Search', 'wp-bbtheme-child-realestate' ), __( 'Filter by location, status, type, price, beds and baths without leaving the page.', 'wp-bbtheme-child-realestate' ) ),
		array( '02', __( 'Shortlist', 'wp-bbtheme-child-realestate' ), __( 'Use clear property cards and local context to focus the next conversation.', 'wp-bbtheme-child-realestate' ) ),
		array( '03', __( 'Move', 'wp-bbtheme-child-realestate' ), __( 'Connect viewing, valuation and contact journeys to a familiar local-agency workflow.', 'wp-bbtheme-child-realestate' ) ),
	);
	$profile['cta_title'] = __( 'Thinking about your next move?', 'wp-bbtheme-child-realestate' );
	$profile['cta_text'] = __( 'Start with a straightforward valuation or tell the team what you are looking for.', 'wp-bbtheme-child-realestate' );
	$profile['footer_text'] = __( 'Local property search, sales and lettings support for buyers, sellers, landlords and tenants.', 'wp-bbtheme-child-realestate' );
	$profile['page_labels'] = array( 'about' => __( 'About', 'wp-bbtheme-child-realestate' ), 'services' => __( 'Selling & letting', 'wp-bbtheme-child-realestate' ), 'industries' => __( 'Area guides', 'wp-bbtheme-child-realestate' ), 'contact' => __( 'Contact', 'wp-bbtheme-child-realestate' ), 'blog' => __( 'Property journal', 'wp-bbtheme-child-realestate' ) );
	return $profile;
}
add_filter( 'wp_theme_demo_profile', 'wpbb_realestate_demo_profile_premium', 20 );

function wpbb_realestate_pattern_markup( $name ) {
	$path = get_stylesheet_directory() . '/patterns/' . sanitize_file_name( $name ) . '.php';
	if ( ! is_readable( $path ) ) { return ''; }
	ob_start(); include $path; return trim( (string) ob_get_clean() );
}
function wpbb_realestate_extra_home_sections( $content, $profile ) {
	if ( empty( $profile['id'] ) || 'realestate' !== $profile['id'] ) { return $content; }
	return $content . wpbb_realestate_pattern_markup( 'property-areas' ) . wpbb_realestate_pattern_markup( 'property-proof' ) . wpbb_realestate_pattern_markup( 'property-valuation' );
}
add_filter( 'wp_theme_demo_extra_home_sections', 'wpbb_realestate_extra_home_sections', 20, 2 );

/**
 * Expose the property content model to Polylang Free when it is active.
 */
function wpbb_realestate_polylang_post_types( $post_types, $is_settings ) {
	$post_types['property'] = 'property';
	return $post_types;
}
add_filter( 'pll_get_post_types', 'wpbb_realestate_polylang_post_types', 10, 2 );

function wpbb_realestate_polylang_taxonomies( $taxonomies, $is_settings ) {
	$taxonomies['property_type'] = 'property_type';
	return $taxonomies;
}
add_filter( 'pll_get_taxonomies', 'wpbb_realestate_polylang_taxonomies', 10, 2 );

/**
 * Brandsafe-style editable mega menu: a classic nav item references a Gutenberg
 * mega-menu post. This definition is sector-specific; the parent owns storage.
 */
function wpbb_realestate_mega_menu_definitions( $definitions, $profile ) {
	if ( empty( $profile['id'] ) || 'realestate' !== $profile['id'] ) {
		return $definitions;
	}
	$archive = get_post_type_archive_link( 'property' ) ?: home_url( '/properties/' );
	$definitions['properties'] = array(
		'title'      => __( 'Property navigation', 'wp-bbtheme-child-realestate' ),
		'target_key' => 'properties',
		'eyebrow'    => __( 'Property', 'wp-bbtheme-child-realestate' ),
		'heading'    => __( 'Find your next place.', 'wp-bbtheme-child-realestate' ),
		'intro'      => __( 'Search, shortlist and move from browsing to a useful local conversation.', 'wp-bbtheme-child-realestate' ),
		'columns'    => array(
			array( 'title' => __( 'Buy', 'wp-bbtheme-child-realestate' ), 'links' => array(
				array( __( 'Homes for sale', 'wp-bbtheme-child-realestate' ), __( 'Browse the complete sale catalogue.', 'wp-bbtheme-child-realestate' ), add_query_arg( 'listing_type', 'sale', $archive ) ),
				array( __( 'New developments', 'wp-bbtheme-child-realestate' ), __( 'Explore recently launched homes.', 'wp-bbtheme-child-realestate' ), add_query_arg( 'status', 'new-development', $archive ) ),
				array( __( 'Area guides', 'wp-bbtheme-child-realestate' ), __( 'Understand neighbourhoods before booking a viewing.', 'wp-bbtheme-child-realestate' ), wp_theme_demo_page_url( 'industries' ) ),
			) ),
			array( 'title' => __( 'Rent', 'wp-bbtheme-child-realestate' ), 'links' => array(
				array( __( 'Properties to rent', 'wp-bbtheme-child-realestate' ), __( 'Find currently available rentals.', 'wp-bbtheme-child-realestate' ), add_query_arg( 'listing_type', 'rent', $archive ) ),
				array( __( 'Tenant guide', 'wp-bbtheme-child-realestate' ), __( 'What to expect from search to move-in.', 'wp-bbtheme-child-realestate' ), wp_theme_demo_page_url( 'services' ) ),
				array( __( 'Landlords', 'wp-bbtheme-child-realestate' ), __( 'Lettings and management support.', 'wp-bbtheme-child-realestate' ), wp_theme_demo_page_url( 'services' ) ),
			) ),
			array( 'title' => __( 'Services', 'wp-bbtheme-child-realestate' ), 'links' => array(
				array( __( 'Book a valuation', 'wp-bbtheme-child-realestate' ), __( 'Start with a clear local valuation.', 'wp-bbtheme-child-realestate' ), wp_theme_demo_page_url( 'contact' ) ),
				array( __( 'Selling', 'wp-bbtheme-child-realestate' ), __( 'Presentation, pricing and sales advice.', 'wp-bbtheme-child-realestate' ), wp_theme_demo_page_url( 'services' ) ),
				array( __( 'Contact the team', 'wp-bbtheme-child-realestate' ), __( 'Ask about a property or your next move.', 'wp-bbtheme-child-realestate' ), wp_theme_demo_page_url( 'contact' ) ),
			) ),
		),
	);
	return $definitions;
}
add_filter( 'wp_theme_demo_mega_menu_definitions', 'wpbb_realestate_mega_menu_definitions', 20, 2 );

/** v3.5 sector editorial labels. */
function wpbb_realestate_blog_profile_v35( $profile ) {
    if ( ( $profile['id'] ?? '' ) !== 'realestate' ) return $profile;
    $profile['blog_eyebrow'] = __( 'Property journal', 'wp-bbtheme-child-realestate' );
    $profile['blog_archive_title'] = __( 'Market insight, area guides and practical property advice.', 'wp-bbtheme-child-realestate' );
    $profile['blog_archive_intro'] = __( 'Useful guidance for buyers, sellers, landlords and anyone planning their next move.', 'wp-bbtheme-child-realestate' );
    return $profile;
}
add_filter( 'wp_theme_demo_profile', 'wpbb_realestate_blog_profile_v35', 90 );

/**
 * v3.8.10.20: keep editable Mega Menu content out of public discovery / SEO.
 * The parent already registers these objects as private; child filters make the
 * intent explicit for Core XML sitemaps and common SEO plugins too.
 */
function wpbb_child_private_megamenu_post_type_args( $args, $post_type ) {
    if ( 'megamenu' !== $post_type ) return $args;
    $args['public'] = false;
    $args['publicly_queryable'] = false;
    $args['exclude_from_search'] = true;
    $args['has_archive'] = false;
    $args['rewrite'] = false;
    $args['query_var'] = false;
    return $args;
}
add_filter( 'register_post_type_args', 'wpbb_child_private_megamenu_post_type_args', 20, 2 );

function wpbb_child_private_megamenu_taxonomy_args( $args, $taxonomy ) {
    if ( 'megamenu-cat' !== $taxonomy ) return $args;
    $args['public'] = false;
    $args['publicly_queryable'] = false;
    $args['rewrite'] = false;
    $args['query_var'] = false;
    return $args;
}
add_filter( 'register_taxonomy_args', 'wpbb_child_private_megamenu_taxonomy_args', 20, 2 );

function wpbb_child_core_sitemap_post_types( $post_types ) {
    unset( $post_types['megamenu'] );
    return $post_types;
}
add_filter( 'wp_sitemaps_post_types', 'wpbb_child_core_sitemap_post_types', 20 );

function wpbb_child_core_sitemap_taxonomies( $taxonomies ) {
    unset( $taxonomies['megamenu-cat'] );
    return $taxonomies;
}
add_filter( 'wp_sitemaps_taxonomies', 'wpbb_child_core_sitemap_taxonomies', 20 );

function wpbb_child_mega_robots( $robots ) {
    if ( is_singular( 'megamenu' ) || is_tax( 'megamenu-cat' ) ) {
        $robots['noindex'] = true;
        $robots['nofollow'] = true;
    }
    return $robots;
}
add_filter( 'wp_robots', 'wpbb_child_mega_robots', 20 );

function wpbb_child_yoast_exclude_megamenu_post_type( $excluded, $post_type ) {
    return 'megamenu' === $post_type ? true : $excluded;
}
add_filter( 'wpseo_sitemap_exclude_post_type', 'wpbb_child_yoast_exclude_megamenu_post_type', 20, 2 );

function wpbb_child_yoast_exclude_megamenu_taxonomy( $excluded, $taxonomy ) {
    return 'megamenu-cat' === $taxonomy ? true : $excluded;
}
add_filter( 'wpseo_sitemap_exclude_taxonomy', 'wpbb_child_yoast_exclude_megamenu_taxonomy', 20, 2 );

function wpbb_child_yoast_mega_robots( $robots ) {
    if ( is_singular( 'megamenu' ) || is_tax( 'megamenu-cat' ) ) return 'noindex, nofollow';
    return $robots;
}
add_filter( 'wpseo_robots', 'wpbb_child_yoast_mega_robots', 20 );


/**
 * v3.8.10.21: global request-a-quote UI is opt-in by child theme.
 * Sector themes with their own quote journeys can keep it; the rest do not
 * expose an unrelated floating "My Quote" control or public route.
 */
if ( ! function_exists( 'wpbb_child_request_quote_enabled' ) ) {
    function wpbb_child_request_quote_enabled() {
        $enabled_themes = array(
            'wp-bbtheme-child-automotive',
            'wp-bbtheme-child-building-services',
            'wp-bbtheme-child-insurance',
            'wp-bbtheme-child-logistics',
            'wp-bbtheme-child-medicine',
            'wp-bbtheme-child-woo-tech-shop',
        );
        $enabled = in_array( get_stylesheet(), $enabled_themes, true );
        return (bool) apply_filters( 'wpbb_child_request_quote_enabled', $enabled, get_stylesheet() );
    }
}

function wpbb_child_request_quote_body_class( $classes ) {
    $classes[] = wpbb_child_request_quote_enabled() ? 'wpbb-request-quote-enabled' : 'wpbb-request-quote-disabled';
    return $classes;
}
add_filter( 'body_class', 'wpbb_child_request_quote_body_class', 30 );

function wpbb_child_request_quote_menu_items( $items ) {
    if ( wpbb_child_request_quote_enabled() ) return $items;
    $target = trim( (string) wp_parse_url( home_url( '/request-a-quote/' ), PHP_URL_PATH ), '/' );
    foreach ( $items as $key => $item ) {
        $path = trim( (string) wp_parse_url( $item->url, PHP_URL_PATH ), '/' );
        if ( $target && $path === $target ) unset( $items[ $key ] );
    }
    return $items;
}
add_filter( 'wp_nav_menu_objects', 'wpbb_child_request_quote_menu_items', 30 );

function wpbb_child_request_quote_disable_route() {
    if ( wpbb_child_request_quote_enabled() ) return;
    $request = isset( $GLOBALS['wp']->request ) ? trim( (string) $GLOBALS['wp']->request, '/' ) : '';
    if ( ! is_page( 'request-a-quote' ) && 'request-a-quote' !== $request ) return;

    global $wp_query;
    if ( $wp_query ) $wp_query->set_404();
    status_header( 404 );
    nocache_headers();
    $template = get_404_template();
    if ( $template ) {
        include $template;
        exit;
    }
    wp_die( esc_html__( 'Page not found.', 'wp-bbtheme-child' ), esc_html__( 'Not found', 'wp-bbtheme-child' ), array( 'response' => 404 ) );
}
add_action( 'template_redirect', 'wpbb_child_request_quote_disable_route', 1 );

function wpbb_child_request_quote_sitemap_args( $args, $post_type ) {
    if ( wpbb_child_request_quote_enabled() || 'page' !== $post_type ) return $args;
    $page = get_page_by_path( 'request-a-quote' );
    if ( $page ) {
        $excluded = isset( $args['post__not_in'] ) ? (array) $args['post__not_in'] : array();
        $excluded[] = (int) $page->ID;
        $args['post__not_in'] = array_values( array_unique( $excluded ) );
    }
    return $args;
}
add_filter( 'wp_sitemaps_posts_query_args', 'wpbb_child_request_quote_sitemap_args', 30, 2 );

require_once get_stylesheet_directory() . '/inc/seo-guardrails.php';

/** v3.8.10.24: identify generated legal pages independently of translated slugs. */
function wpbb_child_legal_page_body_class_v381024( $classes ) {
    if ( ! is_page() ) return $classes;
    $post = get_queried_object();
    if ( ! $post instanceof WP_Post ) return $classes;

    $is_legal = function_exists( 'is_privacy_policy' ) && is_privacy_policy();
    if ( ! $is_legal && false !== strpos( (string) $post->post_content, 'wp-theme-legal-section' ) ) {
        $is_legal = true;
    }
    if ( $is_legal ) $classes[] = 'wpbb-legal-page';
    return array_values( array_unique( $classes ) );
}
add_filter( 'body_class', 'wpbb_child_legal_page_body_class_v381024', 40 );

/** v3.8.10.25: remove generated empty spacing without touching authored copy. */
if ( ! function_exists( 'wpbb_child_remove_empty_paragraphs_v381025' ) ) {
    function wpbb_child_remove_empty_paragraphs_v381025( $content ) {
        if ( is_admin() || ! is_string( $content ) || '' === $content ) return $content;
        return (string) preg_replace(
            '~<p(?:\\s[^>]*)?>(?:\\s|&nbsp;|&#160;|<br\\s*/?>)*</p>~i',
            '',
            $content
        );
    }
}
add_filter( 'the_content', 'wpbb_child_remove_empty_paragraphs_v381025', 120 );

/** v3.8.10.25: do not output a completely empty CTA block above the footer. */
if ( ! function_exists( 'wpbb_child_remove_empty_cta_v381025' ) ) {
    function wpbb_child_remove_empty_cta_v381025( $block_content, $block ) {
        if ( empty( $block['blockName'] ) || 'wpbb/cta-section' !== $block['blockName'] || ! is_string( $block_content ) ) return $block_content;
        if ( preg_match( '~<(?:img|picture|video|iframe|form|button|a)\\b~i', $block_content ) ) return $block_content;
        $plain = trim( html_entity_decode( wp_strip_all_tags( $block_content ), ENT_QUOTES | ENT_HTML5, get_bloginfo( 'charset' ) ) );
        return '' === $plain ? '' : $block_content;
    }
}
add_filter( 'render_block', 'wpbb_child_remove_empty_cta_v381025', 120, 2 );

