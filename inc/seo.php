<?php
/**
 * SEO and performance helpers.
 *
 * @package noble-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function noble_schema_type() {
	if ( is_singular( 'post' ) ) {
		return 'Article';
	}
	if ( function_exists( 'is_product' ) && is_product() ) {
		return 'Product';
	}
	return 'WebPage';
}

function noble_print_schema() {
	$current_url = home_url( add_query_arg( array(), $GLOBALS['wp']->request ?? '' ) );
	echo '<script type="application/ld+json">' . wp_json_encode(
		array(
			'@context' => 'https://schema.org',
			'@type'    => noble_schema_type(),
			'name'     => wp_get_document_title(),
			'url'      => $current_url,
		)
	) . '</script>';
}
add_action( 'wp_head', 'noble_print_schema', 20 );
