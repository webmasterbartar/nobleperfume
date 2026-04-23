<?php
/**
 * Front page based on Home/code.html.
 *
 * @package noble-theme
 */

$noble_hero_main_img  = get_template_directory_uri() . '/assets/images/FullSizeRender.webp';
$noble_hero_side_img  = get_template_directory_uri() . '/assets/images/IMG_8360.webp';
$noble_gift_main_img  = get_template_directory_uri() . '/assets/images/IMG_9208.webp';
$noble_gift_accent_img = get_template_directory_uri() . '/assets/images/FullSizeRender%20%281%29.webp';

get_header();

if ( ! function_exists( 'noble_render_home_product_card' ) ) {
	/**
	 * Render homepage product card.
	 *
	 * @param WC_Product $product Product object.
	 * @param string     $badge_text Badge text.
	 * @param string     $badge_class Badge modifier class.
	 * @return void
	 */
	function noble_render_home_product_card( $product, $badge_text = '', $badge_class = '' ) {
		if ( ! $product instanceof WC_Product ) {
			return;
		}

		$rating_value = $product->get_average_rating() ? (float) $product->get_average_rating() : 5;
		$rating_count = $product->get_rating_count() ? (int) $product->get_rating_count() : 12;
		$brand_label  = $product->get_attribute( 'pa_brand' ) ? $product->get_attribute( 'pa_brand' ) : $product->get_attribute( 'برند' );
		$brand_label  = $brand_label ? $brand_label : 'برند منتخب';
		$product_url  = get_permalink( $product->get_id() );
		$product_name = $product->get_name();

		$atc_product_id = (int) $product->get_id();
		$atc_in_cart    = false;
		if ( function_exists( 'WC' ) && WC() && WC()->cart ) {
			foreach ( WC()->cart->get_cart() as $cart_item ) {
				if ( isset( $cart_item['product_id'] ) && (int) $cart_item['product_id'] === $atc_product_id ) {
					$atc_in_cart = true;
					break;
				}
			}
		}

		$atc_is_ajax = $product->supports( 'ajax_add_to_cart' ) && $product->is_purchasable() && $product->is_in_stock();
		$atc_href    = $atc_is_ajax ? $product->add_to_cart_url() : $product_url;
		$atc_classes = array(
			'noble-home-card__icon-btn',
			'product-cart-btn',
			'noble-card-atc',
			'relative',
			'z-30',
			'product_type_' . $product->get_type(),
		);
		if ( $atc_is_ajax ) {
			$atc_classes[] = 'add_to_cart_button';
			$atc_classes[] = 'ajax_add_to_cart';
		}
		if ( $atc_in_cart ) {
			$atc_classes[] = 'is-added';
		}
		?>
		<article class="new-arrival-card noble-home-card products-mobile-slide">
			<?php if ( '' !== trim( (string) $badge_text ) ) : ?>
				<div class="noble-home-card__badge <?php echo esc_attr( $badge_class ); ?>"><?php echo esc_html( $badge_text ); ?></div>
			<?php endif; ?>
			<div class="noble-home-card__media">
				<a class="noble-home-card__media-link" href="<?php echo esc_url( $product_url ); ?>" aria-label="<?php echo esc_attr( $product_name ); ?>">
					<?php echo $product->get_image( 'medium', array( 'class' => 'noble-home-card__img' ) ); ?>
				</a>
				<div class="noble-home-card__media-glow" aria-hidden="true"></div>
			</div>
			<div class="noble-home-card__body">
				<div class="noble-home-card__brand"><?php echo esc_html( $brand_label ); ?></div>
				<h3 class="noble-home-card__title"><a href="<?php echo esc_url( $product_url ); ?>"><?php echo esc_html( $product_name ); ?></a></h3>
				<div class="noble-home-card__rating" aria-label="<?php echo esc_attr( sprintf( 'امتیاز %s', number_format_i18n( $rating_value, 1 ) ) ); ?>">
					<span class="material-symbols-outlined" data-icon="star" aria-hidden="true">star</span>
					<span><?php echo esc_html( number_format_i18n( $rating_value, 1 ) . ' (' . $rating_count . ' نظر)' ); ?></span>
				</div>
				<div class="noble-home-card__footer">
					<div class="noble-home-card__price product-price-chip"><?php echo wp_kses_post( $product->get_price_html() ); ?></div>
					<div class="noble-home-card__actions product-action-row">
						<a class="noble-home-card__cta" href="<?php echo esc_url( $product_url ); ?>">
							<?php esc_html_e( 'مشاهده سریع', 'noble-theme' ); ?>
							<span class="material-symbols-outlined noble-home-card__cta-icon" data-icon="arrow_back" aria-hidden="true">arrow_back</span>
						</a>
						<a
							class="<?php echo esc_attr( implode( ' ', array_filter( $atc_classes ) ) ); ?>"
							href="<?php echo esc_url( $atc_href ); ?>"
							<?php if ( $atc_is_ajax ) : ?>
								data-quantity="1"
								data-product_id="<?php echo esc_attr( (string) $atc_product_id ); ?>"
								data-product_sku="<?php echo esc_attr( (string) $product->get_sku() ); ?>"
								aria-label="<?php echo esc_attr( $product->add_to_cart_description() ); ?>"
								rel="nofollow"
							<?php endif; ?>
						>
							<span class="material-symbols-outlined noble-card-atc__icon noble-card-atc__icon--add" data-icon="add_shopping_cart" aria-hidden="true">add_shopping_cart</span>
							<span class="material-symbols-outlined noble-card-atc__icon noble-card-atc__icon--check" data-icon="check" aria-hidden="true">check</span>
						</a>
					</div>
				</div>
			</div>
		</article>
		<?php
	}
}
?>
<div class="home-vazir">
<section class="hero-navy relative min-h-[70vh] md:min-h-[78vh] flex items-center pt-6 md:pt-8 overflow-hidden">
	<div class="container mx-auto px-5 sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-12 gap-8 md:gap-10 items-center relative z-10 py-8 md:py-10">
		<div class="hero-content-simple lg:col-span-6 xl:col-span-5 order-2 lg:order-1 text-right lg:max-w-[430px] xl:max-w-[470px] relative z-30">
			<div class="inline-flex items-center px-3 py-1.5 mb-4 md:mb-6 bg-white/10 border border-white/20 rounded-full text-accent-gold eyebrow">کالکشن ویژه نوبل</div>
			<h1 class="title-xl hero-title-mobile-single font-serif text-white mb-4 md:mb-6">رایحه‌ای <span class="text-accent-gold">فراتر</span> از زمان</h1>
			<p class="text-body text-white/85 mb-6 md:mb-8 max-w-xl">دکانت، آنباکس و نسخه‌های اورجینال از معتبرترین برندهای جهان با ضمانت اصالت مادام‌العمر در نوبل پرفیوم.</p>
			<div class="flex flex-col sm:flex-row gap-3 md:gap-4">
				<a class="hero-btn-simple-primary inline-flex w-full sm:w-auto items-center justify-center px-7 md:px-8 py-3 text-sm md:text-base font-bold rounded-lg" href="<?php echo esc_url( home_url( '/shop/' ) ); ?>">خرید کالکشن</a>
				<a class="hero-btn-simple-secondary inline-flex w-full sm:w-auto items-center justify-center px-7 md:px-8 py-3 text-sm md:text-base font-bold rounded-lg" href="tel:09121551396">مشاوره تخصصی</a>
			</div>
		</div>
		<div class="lg:col-span-6 xl:col-span-7 order-1 lg:order-2 relative h-[290px] sm:h-[360px] lg:h-[620px]">
			<div class="absolute -top-10 -left-10 w-80 h-80 bg-accent-gold/5 rounded-full blur-[100px]"></div>
			<div class="absolute -bottom-10 -right-10 w-96 h-96 bg-primary/5 rounded-full blur-[120px]"></div>
			<div class="relative w-full h-full flex items-center justify-center">
				<div class="w-11/12 h-[90%] bg-white shadow-2xl overflow-hidden relative group">
					<img alt="نمای اصلی عطر" class="w-full h-full object-cover md:grayscale md:hover:grayscale-0 transition-all duration-[1.5s] scale-[1.02] group-hover:scale-100" src="<?php echo esc_url( $noble_hero_main_img ); ?>"/>
					<div class="absolute inset-0 border-[24px] border-white/10 pointer-events-none"></div>
				</div>
				<div class="absolute top-1/2 -right-12 -translate-y-1/2 w-2/5 h-3/5 border-[15px] border-background shadow-2xl overflow-hidden hidden lg:block z-20">
					<img alt="نمای نزدیک عطر" class="w-full h-full object-cover" src="<?php echo esc_url( $noble_hero_side_img ); ?>"/>
				</div>
				<div class="absolute bottom-8 -left-6 glass-panel p-6 max-w-[240px] hidden xl:block shadow-2xl animate-bounce-slow">
					<span class="material-symbols-outlined text-accent-gold mb-4 text-3xl" data-icon="auto_awesome">auto_awesome</span>
					<p class="text-primary font-medium text-base leading-relaxed">کالکشن منتخب نوبل؛ رایحه‌هایی خاص برای استایل امضادار شما.</p>
				</div>
			</div>
		</div>
	</div>
</section>

<section class="bg-white py-10 md:py-14 border-b border-primary/5">
	<div class="container mx-auto px-5 sm:px-6 lg:px-8">
		<div class="trust-mobile-carousel grid grid-cols-2 md:grid-cols-5 gap-4 md:gap-6 items-stretch">
			<div class="trust-mobile-slide group rounded-2xl border border-border-light/70 bg-background/40 p-4 md:p-5 text-center transition-all duration-300 hover:-translate-y-1 hover:bg-white hover:shadow-lg h-full flex flex-col">
				<div class="mx-auto mb-3 flex h-11 w-11 md:h-14 md:w-14 items-center justify-center rounded-xl bg-white shadow-sm ring-1 ring-primary/10 group-hover:bg-primary transition-colors">
					<span class="material-symbols-outlined text-primary group-hover:text-white text-xl md:text-2xl" data-icon="verified">verified</span>
				</div>
				<h3 class="font-bold text-primary text-xs md:text-sm mb-1">تضمین اصالت</h3>
				<p class="text-[10px] md:text-xs text-on-surface-variant mt-auto">تایید اصالت تمامی محصولات</p>
			</div>
			<div class="trust-mobile-slide group rounded-2xl border border-border-light/70 bg-background/40 p-4 md:p-5 text-center transition-all duration-300 hover:-translate-y-1 hover:bg-white hover:shadow-lg h-full flex flex-col">
				<div class="mx-auto mb-3 flex h-11 w-11 md:h-14 md:w-14 items-center justify-center rounded-xl bg-white shadow-sm ring-1 ring-primary/10 group-hover:bg-primary transition-colors">
					<span class="material-symbols-outlined text-primary group-hover:text-white text-xl md:text-2xl" data-icon="local_shipping">local_shipping</span>
				</div>
				<h3 class="font-bold text-primary text-xs md:text-sm mb-1">ارسال فوری</h3>
				<p class="text-[10px] md:text-xs text-on-surface-variant mt-auto">تحویل سریع و ایمن</p>
			</div>
			<div class="trust-mobile-slide group rounded-2xl border border-border-light/70 bg-background/40 p-4 md:p-5 text-center transition-all duration-300 hover:-translate-y-1 hover:bg-white hover:shadow-lg h-full flex flex-col">
				<div class="mx-auto mb-3 flex h-11 w-11 md:h-14 md:w-14 items-center justify-center rounded-xl bg-white shadow-sm ring-1 ring-primary/10 group-hover:bg-primary transition-colors">
					<span class="material-symbols-outlined text-primary group-hover:text-white text-xl md:text-2xl" data-icon="support_agent">support_agent</span>
				</div>
				<h3 class="font-bold text-primary text-xs md:text-sm mb-1">مشاوره رایگان</h3>
				<p class="text-[10px] md:text-xs text-on-surface-variant mt-auto">راهنمای تخصصی انتخاب رایحه</p>
			</div>
			<div class="trust-mobile-slide group rounded-2xl border border-border-light/70 bg-background/40 p-4 md:p-5 text-center transition-all duration-300 hover:-translate-y-1 hover:bg-white hover:shadow-lg h-full flex flex-col">
				<div class="mx-auto mb-3 flex h-11 w-11 md:h-14 md:w-14 items-center justify-center rounded-xl bg-white shadow-sm ring-1 ring-primary/10 group-hover:bg-primary transition-colors">
					<span class="material-symbols-outlined text-primary group-hover:text-white text-xl md:text-2xl" data-icon="assignment_return">assignment_return</span>
				</div>
				<h3 class="font-bold text-primary text-xs md:text-sm mb-1">ضمانت بازگشت</h3>
				<p class="text-[10px] md:text-xs text-on-surface-variant mt-auto">امکان بازگشت طبق شرایط</p>
			</div>
			<div class="trust-mobile-slide hidden md:flex group rounded-2xl border border-border-light/70 bg-background/40 p-4 md:p-5 text-center transition-all duration-300 hover:-translate-y-1 hover:bg-white hover:shadow-lg h-full flex-col">
				<div class="mx-auto mb-3 flex h-11 w-11 md:h-14 md:w-14 items-center justify-center rounded-xl bg-white shadow-sm ring-1 ring-primary/10 group-hover:bg-primary transition-colors">
					<span class="material-symbols-outlined text-primary group-hover:text-white text-xl md:text-2xl" data-icon="workspace_premium">workspace_premium</span>
				</div>
				<h3 class="font-bold text-primary text-xs md:text-sm mb-1">کیفیت ممتاز</h3>
				<p class="text-[10px] md:text-xs text-on-surface-variant mt-auto">گزینش بهترین رایحه‌های بازار</p>
			</div>
		</div>
	</div>
</section>

<section class="container mx-auto px-5 sm:px-6 lg:px-8 py-14 md:py-20">
	<div class="mb-8 md:mb-10 flex flex-col items-start justify-between gap-4 md:flex-row md:items-end">
		<div>
			<span class="text-accent-gold eyebrow mb-2 block">دسته‌بندی محصولات</span>
			<h2 class="title-lg font-serif text-primary">مسیر انتخاب رایحه شما</h2>
		</div>
		<a class="self-end md:self-auto text-primary text-sm font-bold border-b border-primary/20 hover:border-primary transition-all whitespace-nowrap" href="<?php echo esc_url( home_url( '/shop/' ) ); ?>">مشاهده همه دسته‌بندی‌ها</a>
	</div>
	<div class="categories-mobile-carousel grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 md:gap-6">
		<?php
		$manual_ids_raw = class_exists( 'WooCommerce' ) ? (string) get_theme_mod( 'noble_home_category_ids', '' ) : '';
		$cats_cache_key = function_exists( 'noble_cache_key' ) ? noble_cache_key( 'home', 'cats', array( 'ids' => $manual_ids_raw ) ) : '';
		$cats_html      = function_exists( 'noble_cache_remember' ) ? noble_cache_remember(
			$cats_cache_key,
			(int) apply_filters( 'noble_cache_ttl_home_cats', 15 * MINUTE_IN_SECONDS ),
			function () use ( $manual_ids_raw ) {
				ob_start();
				if ( class_exists( 'WooCommerce' ) ) {
					$manual_ids = array_filter( array_map( 'absint', array_map( 'trim', explode( ',', (string) $manual_ids_raw ) ) ) );

					$featured_categories = get_terms(
						array(
							'taxonomy'   => 'product_cat',
							'hide_empty' => false,
							'parent'     => empty( $manual_ids ) ? 0 : '',
							'number'     => 4,
							'include'    => $manual_ids,
						)
					);

					if ( ! is_wp_error( $featured_categories ) && ! empty( $featured_categories ) ) :
						if ( ! empty( $manual_ids ) ) {
							usort(
								$featured_categories,
								function ( $a, $b ) use ( $manual_ids ) {
									return array_search( $a->term_id, $manual_ids, true ) <=> array_search( $b->term_id, $manual_ids, true );
								}
							);
						}
						$rendered_categories = 0;
						foreach ( $featured_categories as $category ) :
							if ( 0 === (int) $category->count ) {
								continue;
							}
							$thumbnail_id = get_term_meta( $category->term_id, 'thumbnail_id', true );
							$image_url    = $thumbnail_id ? wp_get_attachment_image_url( $thumbnail_id, 'large' ) : '';
							$rendered_categories++;
							?>
							<a class="category-card noble-category-card category-mobile-slide group" href="<?php echo esc_url( get_term_link( $category ) ); ?>">
								<div class="noble-category-card__media">
									<?php if ( $image_url ) : ?>
										<img class="noble-category-card__img" src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $category->name ); ?>" loading="lazy" width="640" height="640">
									<?php else : ?>
										<div class="noble-category-card__placeholder" aria-hidden="true"></div>
									<?php endif; ?>
									<div class="noble-category-card__overlay"></div>
									<div class="noble-category-card__title-wrap">
										<h3 class="noble-category-card__title"><?php echo esc_html( $category->name ); ?></h3>
										<p class="noble-category-card__count"><?php echo esc_html( $category->count . ' محصول' ); ?></p>
									</div>
								</div>
								<div class="noble-category-card__footer">
									<span class="noble-category-card__cta-text">ورود به دسته</span>
									<span class="material-symbols-outlined noble-category-card__cta-icon" data-icon="arrow_back" aria-hidden="true">arrow_back</span>
								</div>
							</a>
							<?php
						endforeach;
						if ( 0 === $rendered_categories ) :
							?>
							<div class="col-span-full rounded-2xl border border-border-light bg-white p-6 text-center text-primary/70">
								هنوز دسته‌بندی محصولی با آیتم فعال ثبت نشده است.
							</div>
							<?php
						endif;
					else :
						?>
						<div class="col-span-full rounded-2xl border border-border-light bg-white p-6 text-center text-primary/70">
							ووکامرس فعال نیست.
						</div>
						<?php
					endif;
				} else {
					?>
					<div class="col-span-full rounded-2xl border border-border-light bg-white p-6 text-center text-primary/70">
						ووکامرس فعال نیست.
					</div>
					<?php
				}
				return (string) ob_get_clean();
			},
			'home'
		) : '';

		echo $cats_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		?>
	</div>
</section>

<section class="container mx-auto px-5 sm:px-6 lg:px-8 py-14 md:py-20">
	<div class="best-sellers-head flex flex-col sm:flex-row sm:items-end sm:justify-between mb-8 md:mb-12 gap-3 sm:gap-6">
		<div class="min-w-0">
			<span class="text-accent-gold eyebrow mb-2 block">منتخب ویژه</span>
			<h2 class="title-lg font-serif text-primary leading-tight">پرفروش‌ترین‌ها</h2>
		</div>
		<a class="inline-flex items-center text-accent-gold font-bold border-b-2 border-accent-gold/20 pb-1 hover:border-accent-gold hover:text-primary transition-all text-sm whitespace-nowrap" href="<?php echo esc_url( home_url( '/shop/' ) ); ?>">مشاهده گالری محصولات</a>
	</div>
	<div class="products-mobile-carousel grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 md:gap-10">
		<?php
		$best_key  = function_exists( 'noble_cache_key' ) ? noble_cache_key( 'home', 'best_sellers' ) : '';
		$best_html = function_exists( 'noble_cache_remember' ) ? noble_cache_remember(
			$best_key,
			(int) apply_filters( 'noble_cache_ttl_home_best_sellers', 10 * MINUTE_IN_SECONDS ),
			function () {
				ob_start();
				if ( class_exists( 'WooCommerce' ) ) {
					$products = wc_get_products( array( 'limit' => 4, 'orderby' => 'popularity', 'status' => 'publish' ) );
					if ( ! empty( $products ) ) :
						foreach ( $products as $product ) :
							noble_render_home_product_card( $product, 'پرفروش', '' );
						endforeach;
					else :
						?>
						<div class="col-span-full rounded-2xl border border-border-light bg-white p-6 text-center text-primary/70">
							برای نمایش این بخش، ابتدا محصول منتشر کنید.
						</div>
						<?php
					endif;
				}
				return (string) ob_get_clean();
			},
			'home'
		) : '';

		echo $best_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		?>
	</div>
</section>

<section class="special-offers-section py-14 md:py-20">
	<div class="container mx-auto px-5 sm:px-6 lg:px-8">
		<div class="best-sellers-head flex flex-col sm:flex-row sm:items-end sm:justify-between mb-8 md:mb-12 gap-3 sm:gap-6">
			<div class="min-w-0">
				<span class="text-accent-gold eyebrow mb-2 block">فرصت محدود</span>
				<h2 class="title-lg font-serif text-primary leading-tight">محصولات با تخفیف ویژه</h2>
			</div>
			<a class="inline-flex items-center text-accent-gold font-bold border-b-2 border-accent-gold/20 pb-1 hover:border-accent-gold hover:text-primary transition-all text-sm whitespace-nowrap" href="<?php echo esc_url( home_url( '/shop/' ) ); ?>">مشاهده همه تخفیف‌ها</a>
		</div>
		<div class="products-mobile-carousel grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 md:gap-10">
			<?php
			$sale_key  = function_exists( 'noble_cache_key' ) ? noble_cache_key( 'home', 'sale_products' ) : '';
			$sale_html = function_exists( 'noble_cache_remember' ) ? noble_cache_remember(
				$sale_key,
				(int) apply_filters( 'noble_cache_ttl_home_sale_products', 10 * MINUTE_IN_SECONDS ),
				function () {
					ob_start();
					if ( class_exists( 'WooCommerce' ) ) {
						$sale_products = wc_get_products(
							array(
								'limit'   => 4,
								'status'  => 'publish',
								'orderby' => 'date',
								'order'   => 'DESC',
								'on_sale' => true,
							)
						);
						if ( ! empty( $sale_products ) ) :
							foreach ( $sale_products as $product ) :
								noble_render_home_product_card( $product, 'تخفیف ویژه', 'noble-home-card__badge--sale' );
							endforeach;
						else :
							?>
							<div class="col-span-full rounded-2xl border border-border-light bg-white p-6 text-center text-primary/70">
								محصول دارای تخفیف فعال پیدا نشد.
							</div>
							<?php
						endif;
					}
					return (string) ob_get_clean();
				},
				'home'
			) : '';

			echo $sale_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			?>
		</div>
	</div>
</section>

<section class="bg-primary py-6 md:py-8 relative overflow-hidden quiz-banner">
	<div class="absolute inset-0 opacity-100 quiz-banner-overlay"></div>
	<div class="container mx-auto px-5 sm:px-6 lg:px-8 relative z-10">
		<div class="quiz-banner-shell flex flex-col md:flex-row items-start md:items-center justify-between gap-3 md:gap-6">
			<div class="text-right">
				<h2 class="quiz-title text-white font-bold text-base md:text-lg">با چند سوال ساده، رایحه مناسب خودت رو پیدا کن</h2>
			</div>
			<a class="quiz-cta-visible inline-flex w-full sm:w-auto items-center justify-center rounded-lg px-6 py-2.5 font-bold text-sm md:text-base" href="<?php echo esc_url( home_url( '/quiz/' ) ); ?>">شروع کوییز</a>
		</div>
	</div>
</section>

<section class="bg-background py-14 md:py-24">
	<div class="container mx-auto px-5 sm:px-6 lg:px-8">
		<div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-10 md:mb-20 gap-4 md:gap-6">
			<div><span class="text-accent-gold eyebrow mb-3 block">جدید رسید</span><h2 class="title-lg font-serif text-primary">تازه‌ترین‌ها</h2></div>
		</div>
		<div class="products-mobile-carousel grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 md:gap-10">
			<?php
			$new_key  = function_exists( 'noble_cache_key' ) ? noble_cache_key( 'home', 'new_products' ) : '';
			$new_html = function_exists( 'noble_cache_remember' ) ? noble_cache_remember(
				$new_key,
				(int) apply_filters( 'noble_cache_ttl_home_new_products', 10 * MINUTE_IN_SECONDS ),
				function () {
					ob_start();
					if ( class_exists( 'WooCommerce' ) ) {
						$new_products = wc_get_products(
							array(
								'limit'   => 4,
								'orderby' => 'date',
								'order'   => 'DESC',
								'status'  => 'publish',
								'type'    => 'simple',
							)
						);
						if ( ! empty( $new_products ) ) :
							foreach ( $new_products as $product ) :
								noble_render_home_product_card( $product, 'جدید', '' );
							endforeach;
						else :
							?>
							<div class="col-span-full rounded-2xl border border-border-light bg-white p-6 text-center text-primary/70">
								محصولی برای نمایش در تازه‌ترین‌ها پیدا نشد.
							</div>
							<?php
						endif;
					}
					return (string) ob_get_clean();
				},
				'home'
			) : '';

			echo $new_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			?>
		</div>
	</div>
</section>

<section class="container mx-auto px-5 sm:px-6 lg:px-8 py-14 md:py-24">
	<div class="gift-section gift-section-simple grid grid-cols-1 lg:grid-cols-12 overflow-hidden rounded-3xl border border-border-light bg-white shadow-xl">
		<div class="lg:col-span-5 relative min-h-[170px] sm:min-h-[280px] md:min-h-[380px] lg:min-h-[480px] overflow-hidden">
			<img alt="باکس هدیه نوبل" class="h-full w-full object-cover transition-transform duration-700 hover:scale-105" src="<?php echo esc_url( $noble_gift_main_img ); ?>"/>
			<div class="absolute inset-0 bg-gradient-to-t from-primary/35 via-primary/10 to-transparent"></div>
			<div class="hidden md:block absolute -bottom-8 -left-8 w-44 h-44 rounded-2xl overflow-hidden border-4 border-white shadow-2xl">
				<img alt="جزئیات هدیه نوبل" class="h-full w-full object-cover" src="<?php echo esc_url( $noble_gift_accent_img ); ?>"/>
			</div>
		</div>
		<div class="lg:col-span-7 p-6 sm:p-8 lg:p-12 text-right flex flex-col justify-center">
			<span class="text-accent-gold eyebrow mb-3 block">هنر هدیه دادن</span>
			<h2 class="title-lg font-serif text-primary mb-4">هدیه‌ای به یاد ماندنی</h2>
			<p class="text-body text-primary/75 mb-6">
				باکس‌های هدیه نوبل با بسته‌بندی اختصاصی و رایحه‌های دست‌چین‌شده، تجربه‌ای شیک و شخصی‌سازی‌شده برای مناسبت‌های خاص شما می‌سازند.
			</p>
			<ul class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 mb-6 text-sm text-primary/75">
				<li class="gift-feature-simple rounded-lg bg-background px-4 py-2.5">بسته‌بندی لوکس و اختصاصی</li>
				<li class="gift-feature-simple rounded-lg bg-background px-4 py-2.5">امکان درج پیام هدیه</li>
				<li class="gift-feature-simple rounded-lg bg-background px-4 py-2.5">انتخاب رایحه بر اساس سلیقه</li>
				<li class="gift-feature-simple rounded-lg bg-background px-4 py-2.5">ارسال سریع و امن</li>
			</ul>
			<div class="flex flex-col sm:flex-row gap-3 sm:items-center">
				<a class="gift-cta-simple-primary inline-flex items-center justify-center w-full sm:w-auto px-8 py-3 text-sm md:text-base font-bold rounded-lg" href="<?php echo esc_url( home_url( '/gift-box-builder/' ) ); ?>">سفارش باکس هدیه</a>
				<a class="gift-cta-simple-secondary inline-flex items-center justify-center w-full sm:w-auto px-8 py-3 text-sm md:text-base font-bold rounded-lg" href="tel:09121551396">مشاوره انتخاب هدیه</a>
			</div>
		</div>
	</div>
</section>

<section class="container mx-auto px-5 sm:px-6 lg:px-8 py-14 md:py-20 bg-background/50">
	<div class="mb-10 md:mb-24 !text-center text-center">
		<span class="text-accent-gold eyebrow mb-4 block !text-center text-center">مجله نوبل</span>
		<h2 class="title-lg font-serif text-primary !text-center text-center">مجله دنیای رایحه</h2>
	</div>
	<div class="grid grid-cols-1 md:grid-cols-3 gap-5 md:gap-12">
		<?php
		$blog_key  = function_exists( 'noble_cache_key' ) ? noble_cache_key( 'home', 'blog_latest' ) : '';
		$blog_html = function_exists( 'noble_cache_remember' ) ? noble_cache_remember(
			$blog_key,
			(int) apply_filters( 'noble_cache_ttl_home_blog', 10 * MINUTE_IN_SECONDS ),
			function () {
				ob_start();
				$posts = new WP_Query( array( 'post_type' => 'post', 'posts_per_page' => 3, 'ignore_sticky_posts' => true ) );
				while ( $posts->have_posts() ) :
					$posts->the_post();
					?>
					<div class="bg-white overflow-hidden group hover:shadow-2xl transition-all duration-700 border border-border-light">
						<div class="p-6 md:p-12">
							<h3 class="title-md text-primary mb-4 md:mb-6 group-hover:text-accent-gold transition-colors"><?php the_title(); ?></h3>
							<p class="text-primary/50 text-body mb-6 md:mb-10"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 24 ) ); ?></p>
							<a class="text-accent-gold font-bold text-sm flex items-center gap-3 hover:gap-5 transition-all" href="<?php the_permalink(); ?>">ادامه مطلب <span class="material-symbols-outlined text-lg" data-icon="arrow_back">arrow_back</span></a>
						</div>
					</div>
					<?php
				endwhile;
				wp_reset_postdata();
				return (string) ob_get_clean();
			},
			'home'
		) : '';

		echo $blog_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		?>
	</div>
</section>

<section class="bg-white py-14 md:py-20">
	<div class="container mx-auto px-5 sm:px-6 lg:px-8">
		<div class="text-center mb-10 md:mb-24">
			<span class="text-accent-gold eyebrow mb-4 block">صدای مشتریان</span>
			<h2 class="title-lg font-serif text-primary">تجربیات نوبل</h2>
		</div>
		<div class="grid grid-cols-1 md:grid-cols-3 gap-5 md:gap-10 items-stretch">
			<div class="relative rounded-2xl border border-border-light bg-white p-6 md:p-8 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-xl h-full flex flex-col">
				<span class="absolute left-4 top-2 text-7xl text-accent-gold/15 font-serif leading-none">“</span>
				<div class="mb-5 flex items-center gap-1">
					<span class="material-symbols-outlined text-accent-gold text-sm" data-icon="star">star</span>
					<span class="material-symbols-outlined text-accent-gold text-sm" data-icon="star">star</span>
					<span class="material-symbols-outlined text-accent-gold text-sm" data-icon="star">star</span>
					<span class="material-symbols-outlined text-accent-gold text-sm" data-icon="star">star</span>
					<span class="material-symbols-outlined text-accent-gold text-sm" data-icon="star">star</span>
				</div>
				<p class="text-primary/75 leading-7 md:leading-8 text-[14px] md:text-[15px] mb-6 md:mb-8">کیفیت دکانت‌ها فراتر از انتظار من بود. بسته‌بندی بسیار تمیز و حرفه‌ای انجام شده بود.</p>
				<div class="mt-auto flex items-center gap-3 border-t border-border-light pt-4">
					<div class="w-11 h-11 rounded-full bg-primary/10 text-primary flex items-center justify-center font-bold">م</div>
					<div>
						<div class="font-bold text-primary text-sm">مریم علوی</div>
						<div class="text-xs text-on-surface-variant">خریدار وفادار</div>
					</div>
				</div>
			</div>
			<div class="relative rounded-2xl border border-border-light bg-white p-6 md:p-8 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-xl h-full flex flex-col">
				<span class="absolute left-4 top-2 text-7xl text-accent-gold/15 font-serif leading-none">“</span>
				<div class="mb-5 flex items-center gap-1">
					<span class="material-symbols-outlined text-accent-gold text-sm" data-icon="star">star</span>
					<span class="material-symbols-outlined text-accent-gold text-sm" data-icon="star">star</span>
					<span class="material-symbols-outlined text-accent-gold text-sm" data-icon="star">star</span>
					<span class="material-symbols-outlined text-accent-gold text-sm" data-icon="star">star</span>
					<span class="material-symbols-outlined text-accent-gold text-sm" data-icon="star">star</span>
				</div>
				<p class="text-primary/75 leading-7 md:leading-8 text-[14px] md:text-[15px] mb-6 md:mb-8">ارسال فوق‌العاده سریع و ضمانت اصالت کالا باعث شد با خیال راحت خرید کنم.</p>
				<div class="mt-auto flex items-center gap-3 border-t border-border-light pt-4">
					<div class="w-11 h-11 rounded-full bg-primary/10 text-primary flex items-center justify-center font-bold">ر</div>
					<div>
						<div class="font-bold text-primary text-sm">رضا محمدی</div>
						<div class="text-xs text-on-surface-variant">مشتری فروشگاه</div>
					</div>
				</div>
			</div>
			<div class="relative rounded-2xl border border-border-light bg-white p-6 md:p-8 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-xl h-full flex flex-col">
				<span class="absolute left-4 top-2 text-7xl text-accent-gold/15 font-serif leading-none">“</span>
				<div class="mb-5 flex items-center gap-1">
					<span class="material-symbols-outlined text-accent-gold text-sm" data-icon="star">star</span>
					<span class="material-symbols-outlined text-accent-gold text-sm" data-icon="star">star</span>
					<span class="material-symbols-outlined text-accent-gold text-sm" data-icon="star">star</span>
					<span class="material-symbols-outlined text-accent-gold text-sm" data-icon="star">star</span>
					<span class="material-symbols-outlined text-accent-gold text-sm" data-icon="star">star</span>
				</div>
				<p class="text-primary/75 leading-7 md:leading-8 text-[14px] md:text-[15px] mb-6 md:mb-8">مشاوره تخصصی قبل از خرید واقعا کمک کرد تا عطری متناسب با سلیقه‌ام انتخاب کنم.</p>
				<div class="mt-auto flex items-center gap-3 border-t border-border-light pt-4">
					<div class="w-11 h-11 rounded-full bg-primary/10 text-primary flex items-center justify-center font-bold">س</div>
					<div>
						<div class="font-bold text-primary text-sm">سارا راد</div>
						<div class="text-xs text-on-surface-variant">مشتری جدید</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
</div>
<?php get_footer(); ?>
