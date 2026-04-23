<?php
/**
 * Main site footer.
 *
 * @package noble-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$resolve_page_url = static function( $path, $fallback ) {
	$page = get_page_by_path( trim( (string) $path, '/' ) );
	if ( $page instanceof WP_Post ) {
		$permalink = get_permalink( $page );
		if ( $permalink ) {
			return $permalink;
		}
	}
	return $fallback;
};

$year         = (int) gmdate( 'Y' );
$blog_name    = get_bloginfo( 'name' );
$shop_url     = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/shop/' );
$account_url  = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'myaccount' ) : home_url( '/my-account/' );
$contact_url  = $resolve_page_url( 'contact', home_url( '/contact/' ) );
$about_url    = $resolve_page_url( 'about-us', home_url( '/about-us/' ) );
$guide_url    = $resolve_page_url( 'buying-guide', home_url( '/buying-guide/' ) );
$blog_url     = get_post_type_archive_link( 'post' ) ? get_post_type_archive_link( 'post' ) : home_url( '/blog/' );
$privacy_url  = $resolve_page_url( 'privacy-policy', home_url( '/privacy-policy/' ) );
$terms_url    = $resolve_page_url( 'terms', home_url( '/terms/' ) );
$social_ig    = get_theme_mod( 'noble_social_instagram', '' );
$social_tg    = get_theme_mod( 'noble_social_telegram', '' );
$ig_href      = $social_ig ? $social_ig : $contact_url;
$tg_href      = $social_tg ? $social_tg : $contact_url;

$shop_links = array(
	array( 'label' => 'فروشگاه', 'url' => $shop_url ),
	array( 'label' => 'جدیدترین‌ها', 'url' => $shop_url ),
	array( 'label' => 'پرفروش‌ترین‌ها', 'url' => $shop_url ),
	array( 'label' => 'هدیه و بسته‌بندی', 'url' => $shop_url ),
);

$support_links = array(
	array( 'label' => 'راهنمای خرید', 'url' => $guide_url ),
	array( 'label' => 'ارسال و بازگشت', 'url' => $contact_url ),
	array( 'label' => 'سوالات متداول', 'url' => $contact_url ),
	array( 'label' => 'تماس با ما', 'url' => $contact_url ),
);

$phone_display = '۰۲۱-۹۱۰۰۰۰۰۰';
$phone_tel     = '+982191000000';
$email         = 'hello@nobleparfum.ir';
$brand_logo_url = get_template_directory_uri() . '/assets/images/%D9%84%D9%88%DA%AF%D9%88-%D9%86%D9%88%D8%A8%D9%84.png';
?>
<footer class="noble-footer" dir="rtl">
	<div class="noble-footer__accent" aria-hidden="true"></div>

	<div class="noble-footer__main">
		<div class="noble-footer__container">
			<div class="noble-footer__grid">
				<div class="noble-footer__brand">
					<a class="noble-footer__brand-link" href="<?php echo esc_url( home_url( '/' ) ); ?>">
						<img
							class="noble-footer__brand-logo"
							src="<?php echo esc_url( $brand_logo_url ); ?>"
							alt="<?php echo esc_attr( 'نوبل پرفیوم' ); ?>"
							width="220"
							height="34"
							decoding="async"
							loading="lazy"
						>
					</a>
					<p class="noble-footer__tagline"><?php echo esc_html( 'NOBLE · PARFUM' ); ?></p>
					<p class="noble-footer__lead"><?php echo esc_html( 'عطرهای منتخب، تجربه‌ای لوکس و اصالت تضمین‌شده.' ); ?></p>
					<ul class="noble-footer__social" aria-label="<?php echo esc_attr( 'شبکه‌های اجتماعی' ); ?>">
						<li><a href="<?php echo esc_url( $ig_href ); ?>" <?php echo $social_ig ? 'rel="noopener noreferrer" target="_blank"' : ''; ?> aria-label="<?php echo esc_attr( 'اینستاگرام نوبل' ); ?>"><span class="material-symbols-outlined" aria-hidden="true">photo_camera</span></a></li>
						<li><a href="<?php echo esc_url( $tg_href ); ?>" <?php echo $social_tg ? 'rel="noopener noreferrer" target="_blank"' : ''; ?> aria-label="<?php echo esc_attr( 'تلگرام نوبل' ); ?>"><span class="material-symbols-outlined" aria-hidden="true">send</span></a></li>
						<li><a href="<?php echo esc_url( $contact_url ); ?>" aria-label="<?php echo esc_attr( 'تماس' ); ?>"><span class="material-symbols-outlined" aria-hidden="true">mail</span></a></li>
					</ul>
				</div>

				<div class="noble-footer__block" data-footer-accordion data-desktop-title="<?php echo esc_attr( 'منوی فوتر' ); ?>">
					<button type="button" class="noble-footer__block-toggle" aria-expanded="false" aria-controls="noble-footer-nav-menu">
						<span><?php echo esc_html( 'منوی فوتر' ); ?></span>
						<span class="material-symbols-outlined noble-footer__chev" aria-hidden="true">expand_more</span>
					</button>
					<div id="noble-footer-nav-menu" class="noble-footer__block-panel">
						<nav class="noble-footer__nav" aria-label="<?php echo esc_attr( 'لینک‌های فوتر' ); ?>">
							<?php if ( has_nav_menu( 'footer' ) ) : ?>
								<?php
								wp_nav_menu(
									array(
										'theme_location' => 'footer',
										'container'      => false,
										'menu_class'     => 'noble-footer__list',
										'depth'          => 1,
										'fallback_cb'    => false,
									)
								);
								?>
							<?php else : ?>
								<ul class="noble-footer__list">
									<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo esc_html( 'خانه' ); ?></a></li>
									<li><a href="<?php echo esc_url( $shop_url ); ?>"><?php echo esc_html( 'فروشگاه' ); ?></a></li>
									<li><a href="<?php echo esc_url( $blog_url ); ?>"><?php echo esc_html( 'بلاگ' ); ?></a></li>
									<li><a href="<?php echo esc_url( $about_url ); ?>"><?php echo esc_html( 'درباره ما' ); ?></a></li>
									<li><a href="<?php echo esc_url( $contact_url ); ?>"><?php echo esc_html( 'تماس' ); ?></a></li>
								</ul>
							<?php endif; ?>
						</nav>
					</div>
				</div>

				<div class="noble-footer__block" data-footer-accordion data-desktop-title="<?php echo esc_attr( 'فروشگاه' ); ?>">
					<button type="button" class="noble-footer__block-toggle" aria-expanded="false" aria-controls="noble-footer-shop">
						<span><?php echo esc_html( 'فروشگاه' ); ?></span>
						<span class="material-symbols-outlined noble-footer__chev" aria-hidden="true">expand_more</span>
					</button>
					<div id="noble-footer-shop" class="noble-footer__block-panel">
						<ul class="noble-footer__list">
							<?php foreach ( $shop_links as $row ) : ?>
								<li><a href="<?php echo esc_url( $row['url'] ); ?>"><?php echo esc_html( $row['label'] ); ?></a></li>
							<?php endforeach; ?>
						</ul>
					</div>
				</div>

				<div class="noble-footer__block" data-footer-accordion data-desktop-title="<?php echo esc_attr( 'پشتیبانی' ); ?>">
					<button type="button" class="noble-footer__block-toggle" aria-expanded="false" aria-controls="noble-footer-support">
						<span><?php echo esc_html( 'پشتیبانی' ); ?></span>
						<span class="material-symbols-outlined noble-footer__chev" aria-hidden="true">expand_more</span>
					</button>
					<div id="noble-footer-support" class="noble-footer__block-panel">
						<ul class="noble-footer__list">
							<?php foreach ( $support_links as $row ) : ?>
								<li><a href="<?php echo esc_url( $row['url'] ); ?>"><?php echo esc_html( $row['label'] ); ?></a></li>
							<?php endforeach; ?>
						</ul>
					</div>
				</div>

				<div class="noble-footer__aside">
					<div class="noble-footer__contact" aria-label="<?php echo esc_attr( 'اطلاعات تماس' ); ?>">
						<p class="noble-footer__contact-title"><?php echo esc_html( 'ارتباط با نوبل' ); ?></p>
						<ul class="noble-footer__contact-list">
							<li>
								<span class="material-symbols-outlined" aria-hidden="true">call</span>
								<a href="<?php echo esc_url( 'tel:' . $phone_tel ); ?>"><?php echo esc_html( $phone_display ); ?></a>
							</li>
							<li>
								<span class="material-symbols-outlined" aria-hidden="true">mail</span>
								<a href="<?php echo esc_url( 'mailto:' . $email ); ?>"><?php echo esc_html( $email ); ?></a>
							</li>
							<li>
								<span class="material-symbols-outlined" aria-hidden="true">location_on</span>
								<span><?php echo esc_html( 'تهران، ایران' ); ?></span>
							</li>
						</ul>
					</div>

					<div class="noble-footer__news">
						<p class="noble-footer__news-title"><?php echo esc_html( 'خبرنامه' ); ?></p>
						<p class="noble-footer__news-text"><?php echo esc_html( 'از تخفیف‌ها و رسیدن کالکشن‌های جدید باخبر شوید.' ); ?></p>
						<form class="noble-footer__news-form" action="<?php echo esc_url( home_url( '/' ) ); ?>" method="get" aria-label="<?php echo esc_attr( 'عضویت در خبرنامه' ); ?>">
							<label class="screen-reader-text" for="noble-footer-news-email"><?php echo esc_html( 'ایمیل' ); ?></label>
							<input id="noble-footer-news-email" type="email" name="noble_footer_news_email" autocomplete="email" placeholder="<?php echo esc_attr( 'ایمیل شما' ); ?>">
							<button type="button" class="noble-footer__news-btn"><?php echo esc_html( 'عضویت' ); ?></button>
						</form>
						<p class="noble-footer__news-note"><?php echo esc_html( 'اتصال به سرویس خبرنامه به‌زودی فعال می‌شود.' ); ?></p>
					</div>
				</div>
			</div>

			<ul class="noble-footer__trust" aria-label="<?php echo esc_attr( 'مزایا' ); ?>">
				<li><?php echo esc_html( 'ارسال سریع' ); ?></li>
				<li><?php echo esc_html( 'ضمانت اصالت' ); ?></li>
				<li><?php echo esc_html( 'پشتیبانی اختصاصی' ); ?></li>
				<li><?php echo esc_html( 'بسته‌بندی لوکس' ); ?></li>
			</ul>
		</div>
	</div>

	<div class="noble-footer__bottom">
		<div class="noble-footer__container noble-footer__bottom-inner">
			<p class="noble-footer__copy">
				<?php
				echo esc_html(
					sprintf(
						/* translators: 1: site name, 2: year */
						__( '© %1$s %2$s. تمامی حقوق محفوظ است.', 'noble-theme' ),
						$blog_name,
						(string) $year
					)
				);
				?>
			</p>
			<nav class="noble-footer__legal" aria-label="<?php echo esc_attr( 'قوانین' ); ?>">
				<a href="<?php echo esc_url( $terms_url ); ?>"><?php echo esc_html( 'قوانین و مقررات' ); ?></a>
				<span class="noble-footer__dot" aria-hidden="true">·</span>
				<a href="<?php echo esc_url( $privacy_url ); ?>"><?php echo esc_html( 'حریم خصوصی' ); ?></a>
				<span class="noble-footer__dot" aria-hidden="true">·</span>
				<a href="<?php echo esc_url( $account_url ); ?>"><?php echo esc_html( 'حساب کاربری' ); ?></a>
			</nav>
		</div>
	</div>
</footer>
