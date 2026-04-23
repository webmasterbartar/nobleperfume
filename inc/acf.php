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

	$wp_customize->add_section(
		'noble_contact_options',
		array(
			'title'    => __( 'تنظیمات صفحه تماس', 'noble-theme' ),
			'priority' => 36,
		)
	);

	$wp_customize->add_setting(
		'noble_contact_hero_title',
		array(
			'default'           => __( 'تماس با نوبل', 'noble-theme' ),
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'noble_contact_hero_title',
		array(
			'label'   => __( 'عنوان صفحه تماس', 'noble-theme' ),
			'section' => 'noble_contact_options',
			'type'    => 'text',
		)
	);

	$wp_customize->add_setting(
		'noble_contact_hero_desc',
		array(
			'default'           => __( 'برای مشاوره خرید، پیگیری سفارش یا دریافت راهنمایی، با ما در ارتباط باشید.', 'noble-theme' ),
			'sanitize_callback' => 'sanitize_textarea_field',
		)
	);
	$wp_customize->add_control(
		'noble_contact_hero_desc',
		array(
			'label'   => __( 'توضیح بالای صفحه', 'noble-theme' ),
			'section' => 'noble_contact_options',
			'type'    => 'textarea',
		)
	);

	$wp_customize->add_setting(
		'noble_contact_address',
		array(
			'default'           => __( 'تهران، خیابان مثال، پلاک ۱۲، واحد ۵', 'noble-theme' ),
			'sanitize_callback' => 'sanitize_textarea_field',
		)
	);
	$wp_customize->add_control(
		'noble_contact_address',
		array(
			'label'   => __( 'آدرس فروشگاه', 'noble-theme' ),
			'section' => 'noble_contact_options',
			'type'    => 'textarea',
		)
	);

	$wp_customize->add_setting(
		'noble_contact_phone_primary',
		array(
			'default'           => '02100000000',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'noble_contact_phone_primary',
		array(
			'label'   => __( 'تلفن اصلی', 'noble-theme' ),
			'section' => 'noble_contact_options',
			'type'    => 'text',
		)
	);

	$wp_customize->add_setting(
		'noble_contact_phone_secondary',
		array(
			'default'           => '09120000000',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'noble_contact_phone_secondary',
		array(
			'label'   => __( 'موبایل/پشتیبانی', 'noble-theme' ),
			'section' => 'noble_contact_options',
			'type'    => 'text',
		)
	);

	$wp_customize->add_setting(
		'noble_contact_email',
		array(
			'default'           => 'info@noble.test',
			'sanitize_callback' => 'sanitize_email',
		)
	);
	$wp_customize->add_control(
		'noble_contact_email',
		array(
			'label'   => __( 'ایمیل', 'noble-theme' ),
			'section' => 'noble_contact_options',
			'type'    => 'email',
		)
	);

	$wp_customize->add_setting(
		'noble_contact_working_hours',
		array(
			'default'           => __( 'شنبه تا پنجشنبه: ۹:۰۰ تا ۲۱:۰۰', 'noble-theme' ),
			'sanitize_callback' => 'sanitize_textarea_field',
		)
	);
	$wp_customize->add_control(
		'noble_contact_working_hours',
		array(
			'label'   => __( 'ساعات پاسخگویی', 'noble-theme' ),
			'section' => 'noble_contact_options',
			'type'    => 'textarea',
		)
	);

	$wp_customize->add_setting(
		'noble_contact_map_embed_url',
		array(
			'default'           => 'https://maps.google.com/maps?q=Tehran&t=&z=13&ie=UTF8&iwloc=&output=embed',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	$wp_customize->add_control(
		'noble_contact_map_embed_url',
		array(
			'label'       => __( 'لینک Embed نقشه', 'noble-theme' ),
			'description' => __( 'لینک iframe نقشه (Google Maps embed URL).', 'noble-theme' ),
			'section'     => 'noble_contact_options',
			'type'        => 'url',
		)
	);

	$wp_customize->add_setting(
		'noble_contact_form_shortcode',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'noble_contact_form_shortcode',
		array(
			'label'       => __( 'شورتکد فرم تماس', 'noble-theme' ),
			'description' => __( 'مثال: [contact-form-7 id="123" title="Contact form"]', 'noble-theme' ),
			'section'     => 'noble_contact_options',
			'type'        => 'text',
		)
	);
}
add_action( 'customize_register', 'noble_customize_register' );
