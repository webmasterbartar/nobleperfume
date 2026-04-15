<?php
/**
 * Theme setup hooks.
 *
 * @package noble-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function noble_theme_setup() {
	load_theme_textdomain( 'noble-theme', get_template_directory() . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support( 'custom-logo' );
	add_theme_support( 'customize-selective-refresh-widgets' );

	register_nav_menus(
		array(
			'primary' => esc_html__( 'منوی اصلی', 'noble-theme' ),
			'footer'  => esc_html__( 'منوی فوتر', 'noble-theme' ),
		)
	);
}
add_action( 'after_setup_theme', 'noble_theme_setup' );

function noble_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'noble_content_width', 960 );
}
add_action( 'after_setup_theme', 'noble_content_width', 0 );
