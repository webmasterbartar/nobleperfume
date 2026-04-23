<?php
/**
 * Contact page template.
 *
 * @package noble-theme
 */

defined( 'ABSPATH' ) || exit;

get_header();

$hero_title     = (string) get_theme_mod( 'noble_contact_hero_title', 'تماس با نوبل' );
$hero_desc      = (string) get_theme_mod( 'noble_contact_hero_desc', 'برای مشاوره خرید، پیگیری سفارش یا دریافت راهنمایی، با ما در ارتباط باشید.' );
$address        = (string) get_theme_mod( 'noble_contact_address', 'تهران، خیابان مثال، پلاک ۱۲، واحد ۵' );
$phone_primary  = (string) get_theme_mod( 'noble_contact_phone_primary', '02100000000' );
$phone_second   = (string) get_theme_mod( 'noble_contact_phone_secondary', '09120000000' );
$email          = (string) get_theme_mod( 'noble_contact_email', 'info@noble.test' );
$hours          = (string) get_theme_mod( 'noble_contact_working_hours', 'شنبه تا پنجشنبه: ۹:۰۰ تا ۲۱:۰۰' );
$map_url        = (string) get_theme_mod( 'noble_contact_map_embed_url', 'https://maps.google.com/maps?q=Tehran&t=&z=13&ie=UTF8&iwloc=&output=embed' );
$form_shortcode = (string) get_theme_mod( 'noble_contact_form_shortcode', '' );

$phone_primary_link = preg_replace( '/[^\d\+]/', '', $phone_primary );
$phone_second_link  = preg_replace( '/[^\d\+]/', '', $phone_second );
?>
<section class="noble-contact-page bg-background min-h-screen pb-16">
	<div class="container mx-auto px-5 sm:px-6 lg:px-8 pt-8">
		<nav class="noble-contact-page__breadcrumb text-xs sm:text-sm mb-5" aria-label="<?php echo esc_attr__( 'مسیر صفحه', 'noble-theme' ); ?>">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'خانه', 'noble-theme' ); ?></a>
			<span aria-hidden="true">/</span>
			<span><?php esc_html_e( 'تماس با ما', 'noble-theme' ); ?></span>
		</nav>

		<header class="noble-contact-hero rounded-3xl border border-primary/10 p-6 sm:p-8 lg:p-10 mb-8">
			<div class="noble-contact-hero__eyebrow"><?php esc_html_e( 'Contact Noble', 'noble-theme' ); ?></div>
			<h1 class="noble-contact-hero__title"><?php echo esc_html( $hero_title ); ?></h1>
			<p class="noble-contact-hero__desc"><?php echo esc_html( $hero_desc ); ?></p>
		</header>

		<div class="noble-contact-layout">
			<section class="noble-contact-info-card" aria-labelledby="noble-contact-info-title">
				<h2 id="noble-contact-info-title"><?php esc_html_e( 'اطلاعات تماس', 'noble-theme' ); ?></h2>
				<ul class="noble-contact-info-list">
					<li>
						<span class="material-symbols-outlined" aria-hidden="true">call</span>
						<div>
							<div class="noble-contact-info-list__label"><?php esc_html_e( 'تلفن ثابت', 'noble-theme' ); ?></div>
							<a href="<?php echo esc_url( 'tel:' . $phone_primary_link ); ?>"><?php echo esc_html( $phone_primary ); ?></a>
						</div>
					</li>
					<li>
						<span class="material-symbols-outlined" aria-hidden="true">smartphone</span>
						<div>
							<div class="noble-contact-info-list__label"><?php esc_html_e( 'موبایل پشتیبانی', 'noble-theme' ); ?></div>
							<a href="<?php echo esc_url( 'tel:' . $phone_second_link ); ?>"><?php echo esc_html( $phone_second ); ?></a>
						</div>
					</li>
					<li>
						<span class="material-symbols-outlined" aria-hidden="true">mail</span>
						<div>
							<div class="noble-contact-info-list__label"><?php esc_html_e( 'ایمیل', 'noble-theme' ); ?></div>
							<a href="<?php echo esc_url( 'mailto:' . sanitize_email( $email ) ); ?>"><?php echo esc_html( $email ); ?></a>
						</div>
					</li>
					<li>
						<span class="material-symbols-outlined" aria-hidden="true">location_on</span>
						<div>
							<div class="noble-contact-info-list__label"><?php esc_html_e( 'آدرس', 'noble-theme' ); ?></div>
							<p><?php echo esc_html( $address ); ?></p>
						</div>
					</li>
					<li>
						<span class="material-symbols-outlined" aria-hidden="true">schedule</span>
						<div>
							<div class="noble-contact-info-list__label"><?php esc_html_e( 'ساعات پاسخگویی', 'noble-theme' ); ?></div>
							<p><?php echo esc_html( $hours ); ?></p>
						</div>
					</li>
				</ul>
			</section>

			<section class="noble-contact-map-card" aria-label="<?php echo esc_attr__( 'موقعیت فروشگاه', 'noble-theme' ); ?>">
				<div class="noble-contact-map-frame">
					<iframe src="<?php echo esc_url( $map_url ); ?>" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen title="<?php echo esc_attr__( 'موقعیت نوبل روی نقشه', 'noble-theme' ); ?>"></iframe>
				</div>
			</section>
		</div>

		<section class="noble-contact-form-card mt-8" aria-labelledby="noble-contact-form-title">
			<h2 id="noble-contact-form-title"><?php esc_html_e( 'ارسال پیام', 'noble-theme' ); ?></h2>
			<?php if ( '' !== trim( $form_shortcode ) ) : ?>
				<div class="noble-contact-form-shortcode">
					<?php echo do_shortcode( wp_kses_post( $form_shortcode ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>
			<?php else : ?>
				<p class="noble-contact-form-help"><?php esc_html_e( 'برای نمایش فرم تماس، از مسیر نمایش > سفارشی سازی > تنظیمات صفحه تماس، شورتکد فرم را وارد کنید.', 'noble-theme' ); ?></p>
			<?php endif; ?>
		</section>
	</div>
</section>
<?php
get_footer();
<?php
/**
 * Contact page template.
 *
 * @package noble-theme
 */

defined( 'ABSPATH' ) || exit;

get_header();

$hero_title    = (string) get_theme_mod( 'noble_contact_hero_title', 'تماس با نوبل' );
$hero_desc     = (string) get_theme_mod( 'noble_contact_hero_desc', 'برای مشاوره خرید، پیگیری سفارش یا دریافت راهنمایی، با ما در ارتباط باشید.' );
$address       = (string) get_theme_mod( 'noble_contact_address', 'تهران، خیابان مثال، پلاک ۱۲، واحد ۵' );
$phone_primary = (string) get_theme_mod( 'noble_contact_phone_primary', '02100000000' );
$phone_second  = (string) get_theme_mod( 'noble_contact_phone_secondary', '09120000000' );
$email         = (string) get_theme_mod( 'noble_contact_email', 'info@noble.test' );
$hours         = (string) get_theme_mod( 'noble_contact_working_hours', 'شنبه تا پنجشنبه: ۹:۰۰ تا ۲۱:۰۰' );
$map_url       = (string) get_theme_mod( 'noble_contact_map_embed_url', 'https://maps.google.com/maps?q=Tehran&t=&z=13&ie=UTF8&iwloc=&output=embed' );
$form_shortcode = (string) get_theme_mod( 'noble_contact_form_shortcode', '' );

$phone_primary_link = preg_replace( '/[^\d\+]/', '', $phone_primary );
$phone_second_link  = preg_replace( '/[^\d\+]/', '', $phone_second );
?>
<section class="noble-contact-page bg-background min-h-screen pb-16">
	<div class="container mx-auto px-5 sm:px-6 lg:px-8 pt-8">
		<nav class="noble-contact-page__breadcrumb text-xs sm:text-sm mb-5" aria-label="<?php echo esc_attr__( 'مسیر صفحه', 'noble-theme' ); ?>">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'خانه', 'noble-theme' ); ?></a>
			<span aria-hidden="true">/</span>
			<span><?php esc_html_e( 'تماس با ما', 'noble-theme' ); ?></span>
		</nav>

		<header class="noble-contact-hero rounded-3xl border border-primary/10 p-6 sm:p-8 lg:p-10 mb-8">
			<div class="noble-contact-hero__eyebrow"><?php esc_html_e( 'Contact Noble', 'noble-theme' ); ?></div>
			<h1 class="noble-contact-hero__title"><?php echo esc_html( $hero_title ); ?></h1>
			<p class="noble-contact-hero__desc"><?php echo esc_html( $hero_desc ); ?></p>
		</header>

		<div class="noble-contact-layout">
			<section class="noble-contact-info-card" aria-labelledby="noble-contact-info-title">
				<h2 id="noble-contact-info-title"><?php esc_html_e( 'اطلاعات تماس', 'noble-theme' ); ?></h2>
				<ul class="noble-contact-info-list">
					<li>
						<span class="material-symbols-outlined" aria-hidden="true">call</span>
						<div>
							<div class="noble-contact-info-list__label"><?php esc_html_e( 'تلفن ثابت', 'noble-theme' ); ?></div>
							<a href="<?php echo esc_url( 'tel:' . $phone_primary_link ); ?>"><?php echo esc_html( $phone_primary ); ?></a>
						</div>
					</li>
					<li>
						<span class="material-symbols-outlined" aria-hidden="true">smartphone</span>
						<div>
							<div class="noble-contact-info-list__label"><?php esc_html_e( 'موبایل پشتیبانی', 'noble-theme' ); ?></div>
							<a href="<?php echo esc_url( 'tel:' . $phone_second_link ); ?>"><?php echo esc_html( $phone_second ); ?></a>
						</div>
					</li>
					<li>
						<span class="material-symbols-outlined" aria-hidden="true">mail</span>
						<div>
							<div class="noble-contact-info-list__label"><?php esc_html_e( 'ایمیل', 'noble-theme' ); ?></div>
							<a href="<?php echo esc_url( 'mailto:' . sanitize_email( $email ) ); ?>"><?php echo esc_html( $email ); ?></a>
						</div>
					</li>
					<li>
						<span class="material-symbols-outlined" aria-hidden="true">location_on</span>
						<div>
							<div class="noble-contact-info-list__label"><?php esc_html_e( 'آدرس', 'noble-theme' ); ?></div>
							<p><?php echo esc_html( $address ); ?></p>
						</div>
					</li>
					<li>
						<span class="material-symbols-outlined" aria-hidden="true">schedule</span>
						<div>
							<div class="noble-contact-info-list__label"><?php esc_html_e( 'ساعات پاسخگویی', 'noble-theme' ); ?></div>
							<p><?php echo esc_html( $hours ); ?></p>
						</div>
					</li>
				</ul>
			</section>

			<section class="noble-contact-map-card" aria-label="<?php echo esc_attr__( 'موقعیت فروشگاه', 'noble-theme' ); ?>">
				<div class="noble-contact-map-frame">
					<iframe
						src="<?php echo esc_url( $map_url ); ?>"
						loading="lazy"
						referrerpolicy="no-referrer-when-downgrade"
						allowfullscreen
						title="<?php echo esc_attr__( 'موقعیت نوبل روی نقشه', 'noble-theme' ); ?>"
					></iframe>
				</div>
			</section>
		</div>

		<section class="noble-contact-form-card mt-8" aria-labelledby="noble-contact-form-title">
			<h2 id="noble-contact-form-title"><?php esc_html_e( 'ارسال پیام', 'noble-theme' ); ?></h2>
			<?php if ( '' !== trim( $form_shortcode ) ) : ?>
				<div class="noble-contact-form-shortcode">
					<?php echo do_shortcode( wp_kses_post( $form_shortcode ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>
			<?php else : ?>
				<p class="noble-contact-form-help">
					<?php esc_html_e( 'برای نمایش فرم تماس، از مسیر نمایش > سفارشی سازی > تنظیمات صفحه تماس، شورتکد فرم را وارد کنید.', 'noble-theme' ); ?>
				</p>
			<?php endif; ?>
		</section>
	</div>
</section>
<?php
get_footer();
