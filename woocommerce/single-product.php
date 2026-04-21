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

<main class="pt-16 sm:pt-18 pb-20 container mx-auto px-5 sm:px-6 lg:px-8">
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
		$average_rating = (float) $product->get_average_rating();
		$has_reviews    = $review_count > 0 && $average_rating > 0;
		$rounded_rating = $has_reviews ? (int) round( $average_rating ) : 0;
		$short_desc     = $product->get_short_description();
		$sku            = $product->get_sku();

		$normalize_attr_key = static function ( $value ) {
			$value = is_string( $value ) ? wp_strip_all_tags( $value ) : '';
			$value = function_exists( 'mb_strtolower' ) ? mb_strtolower( $value, 'UTF-8' ) : strtolower( $value );
			$value = str_replace( 'pa_', '', $value );
			return preg_replace( '/[\s\-_]+/u', '', $value );
		};

		$note_aliases = array(
			'top'    => array( 'topnotes', 'topnote', 'openingnotes', 'openingnote', 'notesoftop', 'نتآغازین', 'نتابتدایی', 'نتاولیه', 'نخستیننت' ),
			'middle' => array( 'middlenotes', 'middlenote', 'heartnotes', 'heartnote', 'notesofmiddle', 'نتمیانی', 'نتمیانی', 'قلبرایحه', 'نتقلب' ),
			'base'   => array( 'basenotes', 'basenote', 'drydownnotes', 'drydownnote', 'notesofbase', 'نتپایه', 'نتپایانی', 'رایحهپایه', 'نتآخر' ),
		);

		$product_notes = array(
			'top'    => '',
			'middle' => '',
			'base'   => '',
		);

		foreach ( $product->get_attributes() as $attribute ) {
			if ( ! $attribute instanceof WC_Product_Attribute ) {
				continue;
			}

			$attr_keys = array();
			if ( $attribute->is_taxonomy() ) {
				$taxonomy = $attribute->get_name();
				$tax_obj  = get_taxonomy( $taxonomy );
				$label    = $tax_obj && ! empty( $tax_obj->labels->singular_name ) ? $tax_obj->labels->singular_name : wc_attribute_label( $taxonomy );
				$attr_keys[] = $normalize_attr_key( $taxonomy );
				$attr_keys[] = $normalize_attr_key( wc_attribute_taxonomy_slug( $taxonomy ) );
				$attr_keys[] = $normalize_attr_key( $label );
				$values      = wc_get_product_terms( $product->get_id(), $taxonomy, array( 'fields' => 'names' ) );
			} else {
				$name      = $attribute->get_name();
				$attr_keys[] = $normalize_attr_key( $name );
				$values      = $attribute->get_options();
			}

			$values = array_filter( array_map( 'wc_clean', (array) $values ) );
			if ( empty( $values ) ) {
				continue;
			}

			foreach ( $note_aliases as $note_key => $aliases ) {
				if ( $product_notes[ $note_key ] ) {
					continue;
				}
				foreach ( $attr_keys as $attr_key ) {
					if ( in_array( $attr_key, $aliases, true ) ) {
						$product_notes[ $note_key ] = implode( '، ', $values );
						break;
					}
				}
			}
		}
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

			<div class="lg:col-span-7 space-y-8 noble-product-summary-column">
				<article class="noble-product-summary space-y-6">
					<header class="space-y-3">
						<p class="text-tertiary font-serif italic text-lg mb-0"><?php echo esc_html( $brand_label ); ?></p>
						<h1 class="text-3xl sm:text-4xl lg:text-5xl font-serif font-bold text-primary tracking-tight leading-[1.12]"><?php echo esc_html( $product->get_name() ); ?></h1>
						<?php if ( $short_desc ) : ?>
							<div class="noble-product-summary-lead text-on-surface-variant text-sm sm:text-[15px] leading-relaxed max-w-2xl">
								<?php echo wp_kses_post( wpautop( do_shortcode( $short_desc ) ) ); ?>
							</div>
						<?php endif; ?>

						<div class="noble-product-summary-meta flex flex-wrap items-center gap-x-4 gap-y-2 pt-1" role="group" aria-label="<?php echo esc_attr__( 'وضعیت محصول', 'noble-theme' ); ?>">
							<?php if ( $has_reviews ) : ?>
								<div class="flex items-center gap-1 text-accent-gold" aria-hidden="true">
									<?php for ( $i = 1; $i <= 5; $i++ ) : ?>
										<span class="material-symbols-outlined noble-rating-star text-[1.35rem] <?php echo $i <= $rounded_rating ? 'fill-icon' : 'noble-rating-star--empty'; ?>">star</span>
									<?php endfor; ?>
								</div>
								<span class="sr-only">
									<?php
									echo esc_html(
										sprintf(
											/* translators: 1: rating out of 5, 2: review count */
											__( 'میانگین امتیاز %1$s از ۵ بر پایهٔ %2$s دیدگاه', 'noble-theme' ),
											wc_format_decimal( $average_rating, 1 ),
											number_format_i18n( $review_count )
										)
									);
									?>
								</span>
							<?php else : ?>
								<span class="text-xs sm:text-sm text-on-surface-variant font-medium"><?php esc_html_e( 'هنوز امتیازی ثبت نشده است.', 'noble-theme' ); ?></span>
							<?php endif; ?>

							<a class="noble-summary-reviews-link noble-open-reviews-tab text-xs sm:text-sm font-bold text-primary border-e border-primary/15 pe-4 hover:text-accent-gold transition-colors" href="#noble-single-details" data-tab-target="reviews"><?php echo $review_count > 0 ? esc_html( sprintf( _n( '%s دیدگاه', '%s دیدگاه', $review_count, 'noble-theme' ), number_format_i18n( $review_count ) ) ) : esc_html__( 'ثبت نظر', 'noble-theme' ); ?></a>

							<?php if ( $product->is_in_stock() ) : ?>
								<span class="noble-stock-badge noble-stock-badge--in"><?php esc_html_e( 'موجود در انبار', 'noble-theme' ); ?></span>
							<?php else : ?>
								<span class="noble-stock-badge noble-stock-badge--out"><?php esc_html_e( 'ناموجود', 'noble-theme' ); ?></span>
							<?php endif; ?>

							<?php if ( $sku ) : ?>
								<span class="text-[11px] sm:text-xs text-on-surface-variant font-semibold tracking-wide"><?php echo esc_html( sprintf( __( 'کد: %s', 'noble-theme' ), $sku ) ); ?></span>
							<?php endif; ?>
						</div>
					</header>

				<div class="noble-product-summary-price flex flex-col gap-1 py-5 border-y border-primary/10">
					<span class="text-[11px] font-bold uppercase tracking-[0.14em] text-primary/50"><?php esc_html_e( 'قیمت', 'noble-theme' ); ?></span>
					<div class="text-2xl lg:text-4xl font-serif font-bold text-primary [&_.woocommerce-Price-amount]:font-serif"><?php echo wp_kses_post( $product->get_price_html() ); ?></div>
					<?php if ( $product_notes['top'] || $product_notes['middle'] || $product_notes['base'] ) : ?>
						<div class="noble-product-scent-notes mt-4">
							<div class="noble-product-scent-notes__title">
								<span class="material-symbols-outlined noble-product-scent-notes__title-icon" aria-hidden="true">psychiatry</span>
								<span><?php esc_html_e( 'پروفایل رایحه', 'noble-theme' ); ?></span>
							</div>
							<div class="noble-product-scent-notes__grid grid grid-cols-1 sm:grid-cols-3 gap-2">
							<?php if ( $product_notes['top'] ) : ?>
								<div class="noble-scent-note-card noble-scent-note-card--top">
									<span class="noble-scent-note-card__label"><?php esc_html_e( 'نت آغازین', 'noble-theme' ); ?></span>
									<span class="noble-scent-note-card__value"><?php echo esc_html( $product_notes['top'] ); ?></span>
								</div>
							<?php endif; ?>
							<?php if ( $product_notes['middle'] ) : ?>
								<div class="noble-scent-note-card noble-scent-note-card--middle">
									<span class="noble-scent-note-card__label"><?php esc_html_e( 'نت میانی', 'noble-theme' ); ?></span>
									<span class="noble-scent-note-card__value"><?php echo esc_html( $product_notes['middle'] ); ?></span>
								</div>
							<?php endif; ?>
							<?php if ( $product_notes['base'] ) : ?>
								<div class="noble-scent-note-card noble-scent-note-card--base">
									<span class="noble-scent-note-card__label"><?php esc_html_e( 'نت پایه', 'noble-theme' ); ?></span>
									<span class="noble-scent-note-card__value"><?php echo esc_html( $product_notes['base'] ); ?></span>
								</div>
							<?php endif; ?>
							</div>
						</div>
					<?php endif; ?>
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
						<div class="pt-1 noble-variable-wrap space-y-5">
							<div class="noble-variable-intro flex gap-3 items-start rounded-2xl border border-primary/10 bg-gradient-to-bl from-white via-white to-background/90 px-4 py-3.5 shadow-sm">
								<span class="material-symbols-outlined text-2xl text-accent-gold shrink-0 mt-0.5" aria-hidden="true">tune</span>
								<div class="min-w-0">
									<p class="text-sm font-extrabold text-primary mb-1"><?php esc_html_e( 'انتخاب واریانت', 'noble-theme' ); ?></p>
									<p class="text-xs text-on-surface-variant leading-relaxed m-0"><?php esc_html_e( 'گزینه‌ها را انتخاب کنید؛ قیمت و موجودی پس از تکمیل، نمایش داده می‌شود.', 'noble-theme' ); ?></p>
								</div>
							</div>
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
				</article>
			</div>
		</div>

		<section id="noble-single-details" class="mt-24 noble-single-details scroll-mt-28">
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

			<div id="noble-product-reviews" class="noble-tab-panel hidden" data-tab-panel="reviews">
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

document.querySelectorAll('.noble-open-reviews-tab').forEach(function(link) {
	link.addEventListener('click', function(e) {
		e.preventDefault();
		var target = link.getAttribute('data-tab-target');
		if (!target) return;
		var tabBtn = document.querySelector('.noble-tab-btn[data-tab-target="' + target + '"]');
		if (tabBtn) tabBtn.click();
		var section = document.getElementById('noble-single-details');
		if (section) section.scrollIntoView({ behavior: 'smooth', block: 'start' });
	});
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

(function() {
	var successText = <?php echo wp_json_encode( __( 'به سبد خرید اضافه شد', 'noble-theme' ) ); ?>;
	var origKey = 'data-noble-atc-html';

	function nobleAtcButton() {
		return document.querySelector('.single-product form.cart .single_add_to_cart_button');
	}

	function nobleClearAtcSuccess(btn) {
		if (!btn || !btn.classList.contains('noble-atc-success')) {
			return;
		}
		btn.classList.remove('noble-atc-success');
		var prev = btn.getAttribute(origKey);
		if (prev) {
			btn.innerHTML = prev;
			btn.removeAttribute(origKey);
		}
		btn.removeAttribute('aria-label');
	}

	function nobleSetAtcSuccess(btn) {
		if (!btn || btn.classList.contains('noble-atc-success')) {
			return;
		}
		if (!btn.getAttribute(origKey)) {
			btn.setAttribute(origKey, btn.innerHTML);
		}
		btn.classList.add('noble-atc-success');
		btn.innerHTML = '<span class="material-symbols-outlined" aria-hidden="true">check_circle</span> ' + successText;
		btn.setAttribute('aria-label', successText);
	}

	function nobleNoticeLooksLikeAdded() {
		if (new URLSearchParams(window.location.search).get('added-to-cart')) {
			return true;
		}
		var nodes = document.querySelectorAll('.woocommerce-notices-wrapper .woocommerce-message, .woocommerce-message');
		for (var i = 0; i < nodes.length; i++) {
			var t = (nodes[i].textContent || '').toLowerCase();
			if (nodes[i].classList.contains('woocommerce-message--success')) {
				return true;
			}
			if (t.indexOf('has been added') !== -1 || t.indexOf('added to your cart') !== -1) {
				return true;
			}
			if (t.indexOf('سبد') !== -1 && (t.indexOf('اضاف') !== -1 || t.indexOf('افزود') !== -1)) {
				return true;
			}
		}
		return false;
	}

	if (typeof jQuery !== 'undefined') {
		jQuery(document.body).on('added_to_cart', function(event, fragments, cartHash, button) {
			var el = button && button[0] ? button[0] : nobleAtcButton();
			if (el && el.closest && el.closest('.single-product')) {
				nobleSetAtcSuccess(el);
			}
		});
		jQuery('.single-product form.cart').on('change', 'select', function() {
			nobleClearAtcSuccess(nobleAtcButton());
		});
		jQuery('.single-product form.cart').on('click', '.reset_variations', function() {
			nobleClearAtcSuccess(nobleAtcButton());
		});
	}

	document.addEventListener('DOMContentLoaded', function() {
		if (!nobleNoticeLooksLikeAdded()) {
			return;
		}
		var btn = nobleAtcButton();
		if (btn) {
			nobleSetAtcSuccess(btn);
		}
	});
})();
</script>

<?php
get_footer();
?>
