<?php
/**
 * Custom single product template based on refined design.
 *
 * @package noble-theme
 */

defined( 'ABSPATH' ) || exit;

get_header();

$noble_theme_uri = get_template_directory_uri();
?>

<style>
@font-face {
	font-family: "YekanBakhFaNum";
	font-style: normal;
	font-weight: 400;
	src: url("<?php echo esc_url( $noble_theme_uri . '/font/YekanBakhFaNum-Regular.woff2' ); ?>") format("woff2");
	font-display: swap;
}
@font-face {
	font-family: "Material Symbols Outlined";
	font-style: normal;
	font-weight: 400;
	src: url("<?php echo esc_url( $noble_theme_uri . '/font/material-symbols-v39-latin-regular.woff2' ); ?>") format("woff2");
	font-display: swap;
}
.single-product,
.single-product *:not(.material-symbols-outlined) {
	font-family: "YekanBakhFaNum", sans-serif;
}
.single-product .material-symbols-outlined {
	font-family: "Material Symbols Outlined" !important;
}
</style>

<main class="pt-24 pb-20 container mx-auto px-5 sm:px-6 lg:px-8">
	<?php
	while ( have_posts() ) :
		the_post();
		global $product;
		if ( ! $product instanceof WC_Product ) {
			$product = wc_get_product( get_the_ID() );
		}
		if ( ! $product instanceof WC_Product ) {
			continue;
		}

		$brand_label = $product->get_attribute( 'pa_brand' ) ? $product->get_attribute( 'pa_brand' ) : $product->get_attribute( 'برند' );
		$brand_label = $brand_label ? $brand_label : get_bloginfo( 'name' );

		$main_image_id  = $product->get_image_id();
		$gallery_ids    = $product->get_gallery_image_ids();
		$image_ids      = array_values( array_unique( array_filter( array_merge( array( $main_image_id ), $gallery_ids ) ) ) );
		$primary_image  = ! empty( $image_ids ) ? wp_get_attachment_image_url( $image_ids[0], 'large' ) : wc_placeholder_img_src( 'large' );
		$review_count   = (int) $product->get_review_count();
		$average_rating = $product->get_average_rating() ? (float) $product->get_average_rating() : 5;
		?>

		<div class="mb-10">
			<?php woocommerce_breadcrumb(); ?>
		</div>

		<div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
			<div class="lg:col-span-5 space-y-6">
				<div class="bg-transparent rounded-3xl p-0 noble-product-image-wrap overflow-hidden border-0 shadow-none">
					<img
						src="<?php echo esc_url( $primary_image ); ?>"
						alt="<?php echo esc_attr( $product->get_name() ); ?>"
						class="w-full aspect-[4/5] object-cover rounded-2xl"
						id="noble-main-product-image"
					/>
				</div>

				<?php if ( count( $image_ids ) > 1 ) : ?>
					<div class="grid grid-cols-4 gap-4">
						<?php foreach ( array_slice( $image_ids, 0, 4 ) as $idx => $image_id ) : ?>
							<?php $thumb_url = wp_get_attachment_image_url( $image_id, 'woocommerce_thumbnail' ); ?>
							<button
								class="noble-thumb-btn aspect-square bg-white rounded-xl overflow-hidden border p-2 <?php echo 0 === $idx ? 'border-2 border-primary' : 'border border-transparent'; ?>"
								type="button"
								data-image-url="<?php echo esc_url( wp_get_attachment_image_url( $image_id, 'large' ) ); ?>"
							>
								<img src="<?php echo esc_url( $thumb_url ? $thumb_url : wc_placeholder_img_src( 'woocommerce_thumbnail' ) ); ?>" class="w-full h-full object-contain" alt="<?php echo esc_attr( $product->get_name() ); ?>">
							</button>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>

			<div class="lg:col-span-7 space-y-8">
				<div class="space-y-2">
					<p class="text-tertiary font-serif italic text-lg"><?php echo esc_html( $brand_label ); ?></p>
					<h1 class="text-4xl lg:text-6xl font-serif font-bold text-primary tracking-tight"><?php echo esc_html( $product->get_name() ); ?></h1>
					<div class="flex items-center gap-4 mt-4">
						<div class="flex text-tertiary text-sm">
							<?php for ( $i = 1; $i <= 5; $i++ ) : ?>
								<span class="material-symbols-outlined <?php echo $i <= round( $average_rating ) ? 'fill-icon' : ''; ?>">star</span>
							<?php endfor; ?>
						</div>
						<span class="text-xs font-bold text-outline tracking-wide border-r border-outline-variant pr-4"><?php echo esc_html( $review_count . ' دیدگاه' ); ?></span>
						<span class="text-xs font-bold text-outline tracking-wide"><?php echo $product->is_in_stock() ? 'موجود در انبار' : 'ناموجود'; ?></span>
					</div>
				</div>

				<div class="flex items-baseline gap-3 py-4 border-y border-primary/5">
					<div class="text-2xl lg:text-4xl font-serif font-bold text-primary"><?php echo wp_kses_post( $product->get_price_html() ); ?></div>
				</div>

				<div class="space-y-6">
					<?php if ( $product->is_type( 'simple' ) ) : ?>
						<form class="cart" method="post" enctype='multipart/form-data'>
							<div class="noble-simple-atc flex flex-col sm:flex-row items-center gap-4 pt-2">
								<div class="noble-simple-qty flex items-center bg-white border border-primary/10 rounded-2xl p-1 w-full sm:w-auto">
									<button class="w-12 h-12 flex items-center justify-center text-primary hover:bg-primary/5 rounded-xl transition-colors noble-qty-btn" type="button" data-action="minus">
										<span class="material-symbols-outlined">remove</span>
									</button>
									<?php
									$min_qty = (int) apply_filters( 'woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product );
									$max_qty = (int) apply_filters( 'woocommerce_quantity_input_max', $product->get_max_purchase_quantity(), $product );
									$val_qty = isset( $_POST['quantity'] ) ? (int) wc_stock_amount( wp_unslash( $_POST['quantity'] ) ) : $min_qty;
									if ( $val_qty < $min_qty ) {
										$val_qty = $min_qty;
									}
									?>
									<input
										type="number"
										class="noble-qty-input qty"
										name="quantity"
										min="<?php echo esc_attr( $min_qty ); ?>"
										<?php echo $max_qty > 0 ? 'max="' . esc_attr( $max_qty ) . '"' : ''; ?>
										step="1"
										value="<?php echo esc_attr( $val_qty ); ?>"
										inputmode="numeric"
									/>
									<button class="w-12 h-12 flex items-center justify-center text-primary hover:bg-primary/5 rounded-xl transition-colors noble-qty-btn" type="button" data-action="plus">
										<span class="material-symbols-outlined">add</span>
									</button>
								</div>

								<button type="submit" name="add-to-cart" value="<?php echo esc_attr( $product->get_id() ); ?>" class="noble-simple-submit flex-1 w-full bg-primary text-white h-14 rounded-2xl font-bold text-md hover:bg-primary/90 transition-all flex items-center justify-center gap-3">
									<span class="material-symbols-outlined text-xl">shopping_bag</span>
									افزودن به سبد خرید
								</button>
							</div>
						</form>
					<?php else : ?>
						<div class="pt-2 noble-variable-wrap space-y-3">
							<div class="text-xs font-bold text-primary tracking-wide">انتخاب نوع و حجم محصول</div>
							<?php woocommerce_template_single_add_to_cart(); ?>
						</div>
					<?php endif; ?>
				</div>

				<div class="grid grid-cols-3 gap-3 pt-8 border-t border-primary/10 noble-trust-grid">
					<div class="noble-trust-item flex items-center sm:flex-col text-right sm:text-center gap-3 sm:gap-2 rounded-2xl bg-white/90 border border-primary/10 px-4 py-3">
						<span class="material-symbols-outlined text-primary text-2xl noble-trust-icon">verified</span>
						<span class="text-xs font-bold text-primary tracking-normal">تضمین اصالت</span>
					</div>
					<div class="noble-trust-item flex items-center sm:flex-col text-right sm:text-center gap-3 sm:gap-2 rounded-2xl bg-white/90 border border-primary/10 px-4 py-3">
						<span class="material-symbols-outlined text-primary text-2xl noble-trust-icon">local_shipping</span>
						<span class="text-xs font-bold text-primary tracking-normal">ارسال فوری</span>
					</div>
					<div class="noble-trust-item flex items-center sm:flex-col text-right sm:text-center gap-3 sm:gap-2 rounded-2xl bg-white/90 border border-primary/10 px-4 py-3">
						<span class="material-symbols-outlined text-primary text-2xl noble-trust-icon">workspace_premium</span>
						<span class="text-xs font-bold text-primary tracking-normal">گارانتی بازگشت</span>
					</div>
				</div>
			</div>
		</div>

		<section class="mt-24 noble-single-details">
			<div class="flex border-b border-primary/10 gap-10 mb-10 overflow-x-auto no-scrollbar">
				<button class="noble-tab-btn pb-4 text-primary border-b-2 border-primary font-bold whitespace-nowrap" type="button" data-tab-target="specs">مشخصات محصول</button>
				<button class="noble-tab-btn pb-4 text-outline hover:text-primary font-medium transition-colors whitespace-nowrap" type="button" data-tab-target="overview">بررسی تخصصی</button>
				<button class="noble-tab-btn pb-4 text-outline hover:text-primary font-medium transition-colors whitespace-nowrap" type="button" data-tab-target="reviews">نظرات کاربران</button>
			</div>

			<div class="noble-tab-panel hidden" data-tab-panel="overview">
				<div class="space-y-6">
					<h2 class="text-3xl lg:text-4xl font-serif font-bold text-primary leading-tight">عمیق، لوکس و تکرار نشدنی</h2>
					<div class="text-on-surface-variant text-justify noble-product-description">
						<?php echo wp_kses_post( wpautop( $product->get_description() ) ); ?>
					</div>
				</div>
			</div>

			<div class="noble-tab-panel" data-tab-panel="specs">
				<div class="bg-white rounded-2xl border border-primary/10 p-6 md:p-8">
					<?php
					ob_start();
					wc_display_product_attributes( $product );
					$attributes_html = trim( ob_get_clean() );
					if ( ! empty( $attributes_html ) ) {
						echo wp_kses_post( $attributes_html );
					} else {
						echo '<p class="text-on-surface-variant">برای این محصول مشخصات تکمیلی ثبت نشده است.</p>';
					}
					?>
				</div>
			</div>

			<div class="noble-tab-panel hidden" data-tab-panel="reviews">
				<div class="bg-white rounded-2xl border border-primary/10 p-6 md:p-8">
					<?php
					if ( 'no' !== get_option( 'woocommerce_enable_reviews', 'yes' ) ) {
						wc_get_template( 'single-product-reviews.php' );
					} else {
						echo '<p class="text-on-surface-variant">امکان ثبت نظر برای محصولات غیرفعال است. از تنظیمات ووکامرس آن را فعال کنید.</p>';
					}
					?>
				</div>
			</div>
		</section>

		<section class="mt-24">
			<div class="flex justify-between items-end mb-10">
				<div>
					<p class="text-tertiary font-serif italic text-lg mb-2">Curated for You</p>
					<h2 class="text-3xl lg:text-4xl font-serif font-bold text-primary">محصولات مشابه</h2>
				</div>
				<div class="hidden md:flex items-center gap-2">
					<button type="button" class="noble-related-prev w-10 h-10 rounded-xl border border-primary/15 text-primary hover:bg-primary hover:text-white transition-colors">
						<span class="material-symbols-outlined text-[20px]">chevron_right</span>
					</button>
					<button type="button" class="noble-related-next w-10 h-10 rounded-xl border border-primary/15 text-primary hover:bg-primary hover:text-white transition-colors">
						<span class="material-symbols-outlined text-[20px]">chevron_left</span>
					</button>
				</div>
			</div>
			<?php
			$current_cat_ids = wc_get_product_term_ids( $product->get_id(), 'product_cat' );
			if ( ! empty( $current_cat_ids ) ) :
				$related_query = new WP_Query(
					array(
						'post_type'      => 'product',
						'post_status'    => 'publish',
						'posts_per_page' => 12,
						'post__not_in'   => array( $product->get_id() ),
						'tax_query'      => array(
							array(
								'taxonomy' => 'product_cat',
								'field'    => 'term_id',
								'terms'    => $current_cat_ids,
								'operator' => 'IN',
							),
						),
					)
				);
				if ( $related_query->have_posts() ) :
					?>
					<div class="noble-related-carousel flex gap-6 overflow-x-auto no-scrollbar pb-2">
						<?php
						while ( $related_query->have_posts() ) :
							$related_query->the_post();
							$related_product = wc_get_product( get_the_ID() );
							if ( ! $related_product ) {
								continue;
							}
							?>
							<div class="group noble-related-slide shrink-0">
								<article class="noble-related-card h-full rounded-2xl border border-primary/10 bg-white p-4 md:p-5">
									<div class="relative mb-4 overflow-hidden rounded-xl bg-background/40 noble-related-media">
										<a href="<?php the_permalink(); ?>" class="block">
											<?php echo $related_product->get_image( 'medium', array( 'class' => 'w-full aspect-square object-contain p-4 noble-related-image' ) ); ?>
										</a>
									</div>
									<?php $related_brand = $related_product->get_attribute( 'pa_brand' ) ? $related_product->get_attribute( 'pa_brand' ) : $related_product->get_attribute( 'برند' ); ?>
									<p class="mb-1 text-[10px] font-bold uppercase tracking-[0.14em] text-accent-gold"><?php echo esc_html( $related_brand ? $related_brand : 'NOBLE' ); ?></p>
									<h3 class="mb-2 line-clamp-2 min-h-[3.1rem] text-sm md:text-base font-extrabold leading-6 text-primary">
										<a href="<?php the_permalink(); ?>"><?php echo esc_html( $related_product->get_name() ); ?></a>
									</h3>
									<div class="mt-auto border-t border-primary/10 pt-2">
										<div class="product-price-chip mx-auto mb-2 text-primary font-bold text-base text-center">
											<?php if ( $related_product->is_type( 'variable' ) ) : ?>
												<?php
												$min_price = (float) $related_product->get_variation_price( 'min', true );
												$max_price = (float) $related_product->get_variation_price( 'max', true );
												?>
												<?php if ( $min_price > 0 && $max_price > 0 ) : ?>
													<?php if ( $min_price === $max_price ) : ?>
														<span class="noble-related-price-single"><?php echo wp_kses_post( wc_price( $min_price ) ); ?></span>
													<?php else : ?>
														<span class="noble-related-price-range block">از <?php echo wp_kses_post( wc_price( $min_price ) ); ?></span>
														<span class="noble-related-price-range block">تا <?php echo wp_kses_post( wc_price( $max_price ) ); ?></span>
													<?php endif; ?>
												<?php else : ?>
													<span class="noble-related-price-single"><?php echo wp_kses_post( $related_product->get_price_html() ); ?></span>
												<?php endif; ?>
											<?php else : ?>
												<span class="noble-related-price-single"><?php echo wp_kses_post( $related_product->get_price_html() ); ?></span>
											<?php endif; ?>
										</div>
										<a href="<?php the_permalink(); ?>" class="noble-related-cta w-full inline-flex items-center justify-center gap-1 rounded-lg px-3 py-2 text-xs font-bold text-primary">
											مشاهده
											<span class="material-symbols-outlined text-[16px]">chevron_left</span>
										</a>
									</div>
								</article>
							</div>
							<?php
						endwhile;
						wp_reset_postdata();
						?>
					</div>
					<?php
				else :
					?>
					<p class="text-on-surface-variant">محصول مشابهی در این دسته یافت نشد.</p>
					<?php
				endif;
				wp_reset_postdata();
			else :
				?>
				<p class="text-on-surface-variant">این محصول دسته‌بندی مشخصی ندارد.</p>
				<?php
			endif;
			?>
		</section>
	<?php endwhile; ?>
</main>

<script>
document.querySelectorAll('.noble-thumb-btn').forEach(function(btn) {
	btn.addEventListener('click', function() {
		var main = document.getElementById('noble-main-product-image');
		if (!main) return;
		main.setAttribute('src', btn.getAttribute('data-image-url'));
		document.querySelectorAll('.noble-thumb-btn').forEach(function(other) {
			other.classList.remove('border-2', 'border-primary');
			other.classList.add('border', 'border-transparent');
		});
		btn.classList.remove('border', 'border-transparent');
		btn.classList.add('border-2', 'border-primary');
	});
});

document.querySelectorAll('.noble-simple-qty input.qty').forEach(function(input) {
	input.readOnly = true;
});

document.addEventListener('click', function(event) {
	var btn = event.target.closest('.noble-qty-btn');
	if (!btn) return;
	event.preventDefault();

	var qtyBox = btn.closest('.noble-simple-qty');
	var input = qtyBox ? qtyBox.querySelector('input.qty') : null;
	if (!input) {
		var form = btn.closest('form.cart');
		input = form ? form.querySelector('input.qty') : null;
	}
	if (!input) return;

	var step = parseFloat(input.step || '1') || 1;
	var current = parseFloat(input.value || input.getAttribute('value') || input.min || '1') || 1;
	var min = parseFloat(input.min || '1') || 1;
	var max = input.max ? parseFloat(input.max) : NaN;

	if (btn.dataset.action === 'plus') {
		current = current + step;
		if (!isNaN(max) && current > max) current = max;
	} else {
		current = current - step;
		if (current < min) current = min;
	}

	input.value = String(current);
	input.setAttribute('value', String(current));
	input.dispatchEvent(new Event('input', { bubbles: true }));
	input.dispatchEvent(new Event('change', { bubbles: true }));
});

document.querySelectorAll('.noble-tab-btn').forEach(function(btn) {
	btn.addEventListener('click', function() {
		var target = btn.getAttribute('data-tab-target');
		document.querySelectorAll('.noble-tab-btn').forEach(function(other) {
			other.classList.remove('text-primary', 'border-primary', 'font-bold', 'border-b-2');
			other.classList.add('text-outline', 'font-medium');
		});
		btn.classList.add('text-primary', 'border-primary', 'font-bold', 'border-b-2');
		btn.classList.remove('text-outline', 'font-medium');

		document.querySelectorAll('.noble-tab-panel').forEach(function(panel) {
			if (panel.getAttribute('data-tab-panel') === target) {
				panel.classList.remove('hidden');
			} else {
				panel.classList.add('hidden');
			}
		});
	});
});

(function() {
	var track = document.querySelector('.noble-related-carousel');
	if (!track) return;

	var prevBtn = document.querySelector('.noble-related-prev');
	var nextBtn = document.querySelector('.noble-related-next');
	var slide = track.querySelector('.noble-related-slide');
	if (!slide) return;

	var step = function() {
		return slide.getBoundingClientRect().width + 24;
	};

	if (prevBtn) {
		prevBtn.addEventListener('click', function() {
			track.scrollBy({ left: -step(), behavior: 'smooth' });
		});
	}
	if (nextBtn) {
		nextBtn.addEventListener('click', function() {
			track.scrollBy({ left: step(), behavior: 'smooth' });
		});
	}
})();
</script>

<?php
get_footer();
?>
