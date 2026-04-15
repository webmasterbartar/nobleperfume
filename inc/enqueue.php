<?php
/**
 * Enqueue scripts and styles.
 *
 * @package noble-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function noble_asset_version( $relative_path ) {
	$file = get_template_directory() . $relative_path;
	return file_exists( $file ) ? (string) filemtime( $file ) : wp_get_theme()->get( 'Version' );
}

function noble_enqueue_assets() {
	$css_rel = '/assets/dist/css/app.css';
	$js_rel  = '/assets/dist/js/app.js';

	wp_enqueue_style(
		'noble-theme-main',
		get_template_directory_uri() . $css_rel,
		array(),
		noble_asset_version( $css_rel )
	);

	wp_enqueue_script(
		'noble-theme-main',
		get_template_directory_uri() . $js_rel,
		array(),
		noble_asset_version( $js_rel ),
		true
	);
	wp_script_add_data( 'noble-theme-main', 'defer', true );
}
add_action( 'wp_enqueue_scripts', 'noble_enqueue_assets' );
