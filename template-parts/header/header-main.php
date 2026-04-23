<?php
/**
 * Main site header.
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

$about_url   = $resolve_page_url( 'about-us', home_url( '/about-us/' ) );
$guide_url   = $resolve_page_url( 'buying-guide', home_url( '/buying-guide/' ) );
$contact_url = $resolve_page_url( 'contact', home_url( '/contact/' ) );
$blog_url    = get_post_type_archive_link( 'post' ) ? get_post_type_archive_link( 'post' ) : home_url( '/blog/' );

$cart_count = 0;
if ( function_exists( 'WC' ) && WC() && WC()->cart ) {
	$cart_count = (int) WC()->cart->get_cart_contents_count();
}

$promo_text      = 'ارسال رایگان برای خرید بالای ۵۰۰ هزار تومان';
$utility_links   = array(
	array(
		'label' => 'درباره ما',
		'url'   => $about_url,
	),
	array(
		'label' => 'راهنمای خرید',
		'url'   => $guide_url,
	),
	array(
		'label' => 'تماس',
		'url'   => $contact_url,
	),
);
$brand_logo_url  = get_template_directory_uri() . '/assets/images/%D9%84%D9%88%DA%AF%D9%88-%D9%86%D9%88%D8%A8%D9%84.png';
$cart_url        = function_exists( 'wc_get_cart_url' ) ? wc_get_cart_url() : ( function_exists( 'wc_get_checkout_url' ) ? add_query_arg( 'noble_step', 1, wc_get_checkout_url() ) : home_url( '/' ) );
$header_menu_location = has_nav_menu( 'noble_primary' ) ? 'noble_primary' : ( has_nav_menu( 'primary' ) ? 'primary' : '' );
?>
<header class="noble-header" dir="rtl">
	<div class="noble-header__topbar" aria-label="<?php echo esc_attr( 'اطلاع‌رسانی فروشگاه' ); ?>">
		<div class="noble-header__container noble-header__topbar-inner">
			<p class="noble-header__promo"><?php echo esc_html( $promo_text ); ?></p>
			<ul class="noble-header__utility-list" aria-label="<?php echo esc_attr( 'لینک‌های سریع' ); ?>">
				<?php foreach ( $utility_links as $item ) : ?>
					<li><a href="<?php echo esc_url( $item['url'] ); ?>"><?php echo esc_html( $item['label'] ); ?></a></li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>

	<div class="noble-header__bar">
		<div class="noble-header__container noble-header__main-row">
			<button class="noble-header__icon-btn noble-header__drawer-toggle" type="button" aria-label="<?php echo esc_attr( 'باز کردن منو' ); ?>" aria-controls="noble-mobile-drawer" aria-expanded="false">
				<span class="material-symbols-outlined" aria-hidden="true">menu</span>
			</button>

			<a class="noble-header__brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php echo esc_attr( 'صفحه اصلی نوبل' ); ?>">
				<img
					class="noble-header__brand-logo"
					src="<?php echo esc_url( $brand_logo_url ); ?>"
					alt="<?php echo esc_attr( 'نوبل پرفیوم' ); ?>"
					width="260"
					height="40"
					decoding="async"
					loading="eager"
				>
			</a>

			<nav class="noble-header__nav" aria-label="<?php echo esc_attr( 'منوی اصلی' ); ?>">
				<?php if ( $header_menu_location ) : ?>
					<?php
					wp_nav_menu(
						array(
							'theme_location' => $header_menu_location,
							'container'      => false,
							'menu_class'     => 'noble-nav',
							'menu_id'        => 'noble-primary-menu',
						)
					);
					?>
				<?php else : ?>
					<ul class="noble-nav" id="noble-primary-menu">
						<li class="noble-nav__item"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo esc_html( 'خانه' ); ?></a></li>
						<li class="noble-nav__item"><a href="<?php echo esc_url( home_url( '/shop/' ) ); ?>"><?php echo esc_html( 'محصولات' ); ?></a></li>
						<li class="noble-nav__item"><a href="<?php echo esc_url( home_url( '/collections/' ) ); ?>"><?php echo esc_html( 'مجموعه‌ها' ); ?></a></li>
						<li class="noble-nav__item"><a href="<?php echo esc_url( $blog_url ); ?>"><?php echo esc_html( 'بلاگ' ); ?></a></li>
						<li class="noble-nav__item"><a href="<?php echo esc_url( $contact_url ); ?>"><?php echo esc_html( 'تماس' ); ?></a></li>
					</ul>
				<?php endif; ?>
			</nav>

			<div class="noble-header__actions">
				<button type="button" class="noble-header__icon-btn" data-search-toggle aria-label="<?php echo esc_attr( 'جستجو' ); ?>" aria-controls="noble-header-search-panel" aria-expanded="false">
					<span class="material-symbols-outlined" aria-hidden="true">search</span>
				</button>
				<a class="noble-header__icon-btn noble-desktop-only" href="<?php echo esc_url( home_url( '/my-account/' ) ); ?>" aria-label="<?php echo esc_attr( 'حساب کاربری' ); ?>">
					<span class="material-symbols-outlined" aria-hidden="true">person</span>
				</a>
				<a class="noble-header__icon-btn noble-desktop-only" href="<?php echo esc_url( home_url( '/wishlist/' ) ); ?>" aria-label="<?php echo esc_attr( 'علاقه‌مندی‌ها' ); ?>">
					<span class="material-symbols-outlined" aria-hidden="true">favorite</span>
				</a>
				<a class="noble-header__cart" href="<?php echo esc_url( $cart_url ); ?>" aria-label="<?php echo esc_attr( 'سبد خرید' ); ?>">
					<span><?php echo esc_html( 'سبد خرید' ); ?></span>
					<span class="material-symbols-outlined noble-header__cart-icon" aria-hidden="true">shopping_bag</span>
					<span class="noble-header__cart-count"><?php echo esc_html( (string) $cart_count ); ?></span>
				</a>
			</div>
		</div>
	</div>

	<div class="noble-header__accent" aria-hidden="true"></div>

	<div id="noble-header-search-panel" class="noble-header__search" hidden>
		<div class="noble-header__container noble-header__search-inner">
			<form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
				<label class="screen-reader-text" for="noble-header-search"><?php echo esc_html( 'جستجو' ); ?></label>
				<input id="noble-header-search" type="search" name="s" placeholder="<?php echo esc_attr( 'جستجوی عطر، برند یا نت بویایی...' ); ?>">
				<button type="submit"><?php echo esc_html( 'جستجو' ); ?></button>
			</form>
		</div>
	</div>

	<div class="noble-drawer-backdrop" hidden></div>
	<aside class="noble-drawer" id="noble-mobile-drawer" role="dialog" aria-modal="true" aria-label="<?php echo esc_attr( 'منوی موبایل' ); ?>" hidden>
		<div class="noble-drawer__head">
			<a class="noble-header__brand" href="<?php echo esc_url( home_url( '/' ) ); ?>">
				<img
					class="noble-header__brand-logo noble-header__brand-logo--drawer"
					src="<?php echo esc_url( $brand_logo_url ); ?>"
					alt="<?php echo esc_attr( 'نوبل پرفیوم' ); ?>"
					width="180"
					height="30"
					decoding="async"
					loading="eager"
				>
			</a>
			<button type="button" class="noble-header__icon-btn" data-drawer-close aria-label="<?php echo esc_attr( 'بستن منو' ); ?>">
				<span class="material-symbols-outlined" aria-hidden="true">close</span>
			</button>
		</div>
		<div class="noble-drawer__search">
			<form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
				<input type="search" name="s" placeholder="<?php echo esc_attr( 'جستجو در فروشگاه' ); ?>">
			</form>
		</div>
		<nav class="noble-drawer__nav" aria-label="<?php echo esc_attr( 'ناوبری موبایل' ); ?>">
			<?php
			if ( $header_menu_location ) {
				wp_nav_menu(
					array(
						'theme_location' => $header_menu_location,
						'container'      => false,
						'menu_class'     => 'noble-mobile-nav',
					)
				);
			} else {
				?>
				<ul class="noble-mobile-nav">
					<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo esc_html( 'خانه' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/shop/' ) ); ?>"><?php echo esc_html( 'محصولات' ); ?></a></li>
					<li><a href="<?php echo esc_url( $blog_url ); ?>"><?php echo esc_html( 'بلاگ' ); ?></a></li>
					<li><a href="<?php echo esc_url( $contact_url ); ?>"><?php echo esc_html( 'تماس' ); ?></a></li>
				</ul>
				<?php
			}
			?>
		</nav>
		<div class="noble-drawer__cta">
			<a href="<?php echo esc_url( home_url( '/my-account/' ) ); ?>"><?php echo esc_html( 'ورود به حساب' ); ?></a>
			<a class="is-filled" href="<?php echo esc_url( home_url( '/wishlist/' ) ); ?>"><?php echo esc_html( 'علاقه‌مندی‌ها' ); ?></a>
		</div>
	</aside>
</header>
