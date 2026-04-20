<?php
/**
 * Main site header.
 *
 * @package noble-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$cart_count = 0;
if ( function_exists( 'WC' ) && WC() && WC()->cart ) {
	$cart_count = (int) WC()->cart->get_cart_contents_count();
}

$promo_text      = 'ارسال رایگان برای خرید بالای ۵۰۰ هزار تومان';
$utility_links   = array(
	array(
		'label' => 'درباره ما',
		'url'   => home_url( '/about-us/' ),
	),
	array(
		'label' => 'راهنمای خرید',
		'url'   => home_url( '/buying-guide/' ),
	),
	array(
		'label' => 'تماس',
		'url'   => home_url( '/contact/' ),
	),
);
$category_links  = array( 'عطر مردانه', 'زنانه', 'یونیسکس', 'اسپرت', 'شرقی' );
$brand_links     = array( 'Dior', 'Chanel', 'Tom Ford', 'Creed', 'مشاهده همه' );
$featured_name   = 'عطر نوبل امپریال';
$featured_price  = '۳,۹۸۰,۰۰۰ تومان';
$featured_badge  = 'پیشنهاد هفته';
$featured_img    = function_exists( 'wc_placeholder_img_src' ) ? wc_placeholder_img_src( 'woocommerce_single' ) : 'data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22400%22 height=%22320%22 viewBox=%220 0 400 320%22%3E%3Crect width=%22400%22 height=%22320%22 fill=%22%23ebf1ff%22/%3E%3Ctext x=%22200%22 y=%22170%22 font-size=%2224%22 text-anchor=%22middle%22 fill=%22%23051061%22%3ENoble%20Parfum%3C/text%3E%3C/svg%3E';
$cart_url        = function_exists( 'wc_get_cart_url' ) ? wc_get_cart_url() : ( function_exists( 'wc_get_checkout_url' ) ? add_query_arg( 'noble_step', 1, wc_get_checkout_url() ) : home_url( '/' ) );
$mega_products   = array(
	array(
		'key'          => 'products',
		'title'        => 'محصولات منتخب نوبل',
		'description'  => 'از عطرهای روزمره تا امضاهای لوکس، انتخابی دقیق برای هر سلیقه.',
		'column_one'   => 'دسته‌بندی‌ها',
		'column_two'   => 'برندهای محبوب',
		'quick_links'  => array( 'ارسال سریع', 'اورجینال', 'پرفروش', 'هدیه' ),
		'feature_note' => 'نت‌های گرم و شرقی برای استایل امضادار',
	),
	array(
		'key'          => 'collections',
		'title'        => 'کالکشن‌های تجربه‌محور',
		'description'  => 'کالکشن‌هایی که بر اساس موقعیت، فصل و حس انتخاب شده‌اند.',
		'column_one'   => 'کالکشن‌ها',
		'column_two'   => 'برندهای محبوب',
		'quick_links'  => array( 'کلاسیک', 'شبانه', 'روزانه', 'VIP' ),
		'feature_note' => 'انتخابی مناسب برای هدیه و موقعیت‌های رسمی',
	),
);
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
				<span class="noble-header__brand-mark" aria-hidden="true">●</span>
				<span class="noble-header__brand-name"><?php echo esc_html( 'نوبل' ); ?></span>
				<span class="noble-header__brand-tag"><?php echo esc_html( 'NOBLE · PARFUM' ); ?></span>
			</a>

			<nav class="noble-header__nav" aria-label="<?php echo esc_attr( 'منوی اصلی' ); ?>">
				<?php if ( has_nav_menu( 'noble_primary' ) || has_nav_menu( 'primary' ) ) : ?>
					<?php
					wp_nav_menu(
						array(
							'theme_location' => has_nav_menu( 'noble_primary' ) ? 'noble_primary' : 'primary',
							'container'      => false,
							'menu_class'     => 'noble-nav',
							'menu_id'        => 'noble-primary-menu',
						)
					);
					?>
				<?php else : ?>
					<ul class="noble-nav" id="noble-primary-menu">
						<li class="noble-nav__item"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo esc_html( 'خانه' ); ?></a></li>
						<li class="noble-nav__item noble-nav__item--mega">
							<button type="button" class="noble-nav__mega-trigger" aria-haspopup="true" aria-expanded="false" data-mega-target="products"><?php echo esc_html( 'محصولات' ); ?><span aria-hidden="true">▾</span></button>
						</li>
						<li class="noble-nav__item noble-nav__item--mega">
							<button type="button" class="noble-nav__mega-trigger" aria-haspopup="true" aria-expanded="false" data-mega-target="collections"><?php echo esc_html( 'مجموعه‌ها' ); ?><span aria-hidden="true">▾</span></button>
						</li>
						<li class="noble-nav__item"><a href="<?php echo esc_url( home_url( '/blog/' ) ); ?>"><?php echo esc_html( 'بلاگ' ); ?></a></li>
						<li class="noble-nav__item"><a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php echo esc_html( 'تماس' ); ?></a></li>
					</ul>
				<?php endif; ?>

				<?php foreach ( $mega_products as $mega_item ) : ?>
					<div class="noble-mega" data-mega-menu="<?php echo esc_attr( $mega_item['key'] ); ?>" aria-hidden="true">
						<div class="noble-mega__intro">
							<div>
								<p class="noble-mega__eyebrow"><?php echo esc_html( strtoupper( $mega_item['key'] ) ); ?></p>
								<h3 class="noble-mega__title"><?php echo esc_html( $mega_item['title'] ); ?></h3>
								<p class="noble-mega__description"><?php echo esc_html( $mega_item['description'] ); ?></p>
							</div>
							<div class="noble-mega__quick-links">
								<?php foreach ( $mega_item['quick_links'] as $quick_link ) : ?>
									<a href="<?php echo esc_url( home_url( '/shop/' ) ); ?>"><?php echo esc_html( $quick_link ); ?></a>
								<?php endforeach; ?>
							</div>
						</div>
						<div class="noble-mega__grid">
							<div class="noble-mega__col">
								<p class="noble-mega__heading"><?php echo esc_html( $mega_item['column_one'] ); ?></p>
								<ul>
									<?php if ( 'products' === $mega_item['key'] ) : ?>
										<?php foreach ( $category_links as $cat ) : ?>
											<li><a href="<?php echo esc_url( home_url( '/shop/' ) ); ?>"><?php echo esc_html( $cat ); ?></a></li>
										<?php endforeach; ?>
									<?php else : ?>
										<li><a href="<?php echo esc_url( home_url( '/shop/' ) ); ?>"><?php echo esc_html( 'کالکشن کلاسیک' ); ?></a></li>
										<li><a href="<?php echo esc_url( home_url( '/shop/' ) ); ?>"><?php echo esc_html( 'کالکشن لوکس' ); ?></a></li>
										<li><a href="<?php echo esc_url( home_url( '/shop/' ) ); ?>"><?php echo esc_html( 'کالکشن روزانه' ); ?></a></li>
										<li><a href="<?php echo esc_url( home_url( '/shop/' ) ); ?>"><?php echo esc_html( 'امضای نوبل' ); ?></a></li>
										<li><a href="<?php echo esc_url( home_url( '/shop/' ) ); ?>"><?php echo esc_html( 'هدیه سازمانی' ); ?></a></li>
									<?php endif; ?>
								</ul>
							</div>
							<div class="noble-mega__col">
								<p class="noble-mega__heading"><?php echo esc_html( $mega_item['column_two'] ); ?></p>
								<ul>
									<?php foreach ( $brand_links as $brand ) : ?>
										<li><a href="<?php echo esc_url( home_url( '/shop/' ) ); ?>"><?php echo esc_html( $brand ); ?></a></li>
									<?php endforeach; ?>
								</ul>
							</div>
							<div class="noble-mega__feature">
								<span class="noble-mega__badge"><?php echo esc_html( $featured_badge ); ?></span>
								<img src="<?php echo esc_url( $featured_img ); ?>" alt="<?php echo esc_attr( $featured_name ); ?>">
								<div>
									<p><?php echo esc_html( $featured_name ); ?></p>
									<small><?php echo esc_html( $mega_item['feature_note'] ); ?></small>
									<strong><?php echo esc_html( $featured_price ); ?></strong>
									<a class="noble-mega__feature-link" href="<?php echo esc_url( home_url( '/shop/' ) ); ?>"><?php echo esc_html( 'مشاهده محصول' ); ?></a>
								</div>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
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
				<span class="noble-header__brand-mark" aria-hidden="true">●</span>
				<span class="noble-header__brand-name"><?php echo esc_html( 'نوبل' ); ?></span>
				<span class="noble-header__brand-tag"><?php echo esc_html( 'NOBLE · PARFUM' ); ?></span>
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
			if ( has_nav_menu( 'noble_primary' ) || has_nav_menu( 'primary' ) ) {
				wp_nav_menu(
					array(
						'theme_location' => has_nav_menu( 'noble_primary' ) ? 'noble_primary' : 'primary',
						'container'      => false,
						'menu_class'     => 'noble-mobile-nav',
					)
				);
			} else {
				?>
				<ul class="noble-mobile-nav">
					<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo esc_html( 'خانه' ); ?></a></li>
					<li class="menu-item-has-children">
						<a href="<?php echo esc_url( home_url( '/shop/' ) ); ?>"><?php echo esc_html( 'محصولات' ); ?></a>
						<ul class="sub-menu">
							<?php foreach ( $category_links as $cat ) : ?>
								<li><a href="<?php echo esc_url( home_url( '/shop/' ) ); ?>"><?php echo esc_html( $cat ); ?></a></li>
							<?php endforeach; ?>
						</ul>
					</li>
					<li><a href="<?php echo esc_url( home_url( '/blog/' ) ); ?>"><?php echo esc_html( 'بلاگ' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php echo esc_html( 'تماس' ); ?></a></li>
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
