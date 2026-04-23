<?php
/**
 * Static route templates (works even if WP pages are missing).
 *
 * @package noble-theme
 */

defined( 'ABSPATH' ) || exit;

/**
 * Route map for static templates.
 *
 * @return array<string,string>
 */
function noble_static_page_route_map() {
	return array(
		'about-us'       => 'page-about-us.php',
		'contact'        => 'page-contact.php',
		'buying-guide'   => 'page-buying-guide.php',
		'terms'          => 'page-terms.php',
		'privacy-policy' => 'page-privacy-policy.php',
	);
}

/**
 * Register static page routes.
 *
 * @return void
 */
function noble_register_static_page_routes() {
	foreach ( array_keys( noble_static_page_route_map() ) as $slug ) {
		add_rewrite_rule( '^' . preg_quote( $slug, '/' ) . '/?$', 'index.php?noble_static_page=' . $slug, 'top' );
	}
}
add_action( 'init', 'noble_register_static_page_routes' );

/**
 * Add custom query var for static pages.
 *
 * @param array<int,string> $vars Query vars.
 * @return array<int,string>
 */
function noble_add_static_page_query_var( $vars ) {
	$vars[] = 'noble_static_page';
	return $vars;
}
add_filter( 'query_vars', 'noble_add_static_page_query_var' );

/**
 * Load mapped template for static routes.
 *
 * @param string $template Template path.
 * @return string
 */
function noble_static_page_template_include( $template ) {
	$route = (string) get_query_var( 'noble_static_page' );
	if ( '' === $route ) {
		return $template;
	}

	$map = noble_static_page_route_map();
	if ( empty( $map[ $route ] ) ) {
		return $template;
	}

	$target = get_template_directory() . '/' . $map[ $route ];
	if ( file_exists( $target ) ) {
		return $target;
	}

	return $template;
}
add_filter( 'template_include', 'noble_static_page_template_include', 99 );

/**
 * Flush rewrite rules once for static routes.
 *
 * @return void
 */
function noble_static_pages_maybe_flush_rewrite() {
	if ( get_option( 'noble_static_pages_routes_flushed' ) ) {
		return;
	}
	flush_rewrite_rules( false );
	update_option( 'noble_static_pages_routes_flushed', 1 );
}
add_action( 'admin_init', 'noble_static_pages_maybe_flush_rewrite' );

