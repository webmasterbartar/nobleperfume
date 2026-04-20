<?php
/**
 * Theme settings without third-party plugins.
 *
 * @package noble-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function noble_customize_register( $wp_customize ) {
	$wp_customize->add_section(
		'noble_theme_options',
		array(
			'title'    => __( 'تنظیمات قالب نوبل', 'noble-theme' ),
			'priority' => 35,
		)
	);

	$wp_customize->add_setting(
		'noble_hero_title',
		array(
			'default'           => __( 'رایحه‌ای فراتر از زمان', 'noble-theme' ),
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'noble_hero_title',
		array(
			'label'   => __( 'عنوان هیرو', 'noble-theme' ),
			'section' => 'noble_theme_options',
			'type'    => 'text',
		)
	);

	$wp_customize->add_setting(
		'noble_home_category_ids',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'noble_home_category_ids',
		array(
			'label'       => __( 'شناسه دسته‌های صفحه اصلی', 'noble-theme' ),
			'description' => __( 'شناسه دسته‌بندی‌های ووکامرس را با کاما وارد کنید. مثال: 12,18,25,33', 'noble-theme' ),
			'section'     => 'noble_theme_options',
			'type'        => 'text',
		)
	);

	$wp_customize->add_setting(
		'noble_social_instagram',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		)
	);

	$wp_customize->add_control(
		'noble_social_instagram',
		array(
			'label'       => __( 'لینک اینستاگرام (فوتر)', 'noble-theme' ),
			'description' => __( 'خالی بگذارید تا آیکون به صفحه تماس برود.', 'noble-theme' ),
			'section'     => 'noble_theme_options',
			'type'        => 'url',
		)
	);

	$wp_customize->add_setting(
		'noble_social_telegram',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		)
	);

	$wp_customize->add_control(
		'noble_social_telegram',
		array(
			'label'       => __( 'لینک تلگرام (فوتر)', 'noble-theme' ),
			'description' => __( 'خالی بگذارید تا آیکون به صفحه تماس برود.', 'noble-theme' ),
			'section'     => 'noble_theme_options',
			'type'        => 'url',
		)
	);
}
add_action( 'customize_register', 'noble_customize_register' );
