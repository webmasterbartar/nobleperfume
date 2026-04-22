<?php
/**
 * Product loop item override.
 * Clean, Simple, Standard Mobile UI (2 cols)
 *
 * @package noble-theme
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}

$product_id  = $product->get_id();
$product_url = get_permalink( $product_id );
$terms       = get_the_terms( $product_id, 'product_cat' );
$brand_label = __( 'محصول', 'noble-theme' );

if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
	$deepest_term = $terms[0];
	$max_depth    = 0;
	foreach ( $terms as $term ) {
		$ancestors = get_ancestors( $term->term_id, 'product_cat' );
		$depth     = count( $ancestors );
		if ( $depth > $max_depth ) {
			$deepest_term = $term;
			$max_depth    = $depth;
		}
	}
	$brand_label = $deepest_term->name;
}

$is_new       = ( time() - strtotime( $product->get_date_created() ) ) < ( 30 * DAY_IN_SECONDS );
$sale_percent = null;
if ( $product->is_on_sale() ) {
	if ( $product->is_type( 'variable' ) ) {
		$max_pct = 0;
		foreach ( $product->get_children() as $vid ) {
			$v = wc_get_product( $vid );
			if ( ! $v || ! $v->is_on_sale() ) {
				continue;
			}
			$reg = (float) $v->get_regular_price();
			$sal = (float) $v->get_sale_price();
			if ( $reg > 0 && $sal > 0 && $sal < $reg ) {
				$max_pct = max( $max_pct, (int) round( 100 - ( $sal / $reg * 100 ) ) );
			}
		}
		$sale_percent = $max_pct > 0 ? $max_pct : null;
	} else {
		$reg = (float) $product->get_regular_price();
		$sal = (float) $product->get_sale_price();
		if ( $reg > 0 && $sal > 0 && $sal < $reg ) {
			$sale_percent = (int) round( 100 - ( $sal / $reg * 100 ) );
		}
	}
}
?>
<li <?php wc_product_class( 'group relative flex flex-col bg-white border border-gray-200 rounded-lg overflow-hidden transition-all hover:shadow-lg', $product ); ?> style="margin: 0; width: 100%;">
	
	<!-- Image Wrapper (Square on Mobile, 4/5 on Desktop) -->
	<div class="relative w-full aspect-square md:aspect-[4/5] bg-gray-50 flex items-center justify-center p-0">
		
		<!-- Badges -->
		<div class="absolute right-2 top-2 z-10 pointer-events-none" style="display:flex; flex-direction:column; gap:5px; align-items:flex-start;">
			<?php if ( $product->is_on_sale() ) : ?>
				<span style="background:#051061; color:white; font-size:10px; font-weight:bold; padding:3px 8px; border-radius:4px; box-shadow:0 2px 4px rgba(0,0,0,0.1);">
					<?php echo $sale_percent ? esc_html( sprintf( '٪%d تخفیف', $sale_percent ) ) : 'تخفیف'; ?>
				</span>
			<?php endif; ?>
			<?php if ( $is_new ) : ?>
				<span style="background:#fef3c7; color:#b45309; font-size:10px; font-weight:bold; padding:3px 8px; border-radius:4px; box-shadow:0 2px 4px rgba(0,0,0,0.1);">جدید</span>
			<?php endif; ?>
			<?php if ( ! $product->is_in_stock() ) : ?>
				<span style="background:#e5e7eb; color:#6b7280; font-size:10px; font-weight:bold; padding:3px 8px; border-radius:4px; box-shadow:0 2px 4px rgba(0,0,0,0.1);">ناموجود</span>
			<?php endif; ?>
		</div>

		<a href="<?php echo esc_url( $product_url ); ?>" class="block w-full h-full relative z-0 p-4 md:p-6 pb-0" <?php if ( ! $product->is_in_stock() ) echo 'style="opacity: 0.5; filter: grayscale(1);"'; ?>>
			<?php
			$image_html = $product->get_image( 'woocommerce_thumbnail', array(
				'class' => 'w-full h-full object-cover transition-transform duration-500 group-hover:scale-105 rounded-md',
				'alt'   => esc_attr( $product->get_name() ),
			) );
			
			if ( $image_html ) {
				echo $image_html;
			} else {
				echo sprintf( '<img src="%s" alt="%s" class="w-full h-full object-cover opacity-50" />', esc_url( wc_placeholder_img_src( 'woocommerce_thumbnail' ) ), esc_attr__( 'تصویر محصول', 'noble-theme' ) );
			}
			?>
		</a>

		<!-- Desktop Only: Hover View Details Layer -->
		<a href="<?php echo esc_url( $product_url ); ?>" class="hidden md:flex absolute inset-x-0 bottom-0 z-10 bg-white/90 backdrop-blur-md border-t border-gray-200 py-3 text-center text-sm font-bold text-[#051061] hover:bg-[#051061] hover:text-white items-center justify-center gap-2 translate-y-full opacity-0 transition-all duration-300 group-hover:translate-y-0 group-hover:opacity-100">
			<span class="material-symbols-outlined shrink-0 text-[18px]">search</span> مشاهده
		</a>
	</div>

	<!-- Info Wrapper -->
	<div class="flex flex-col flex-1 p-3 md:p-4 text-center">
		
		<!-- Category -->
		<p class="text-[10px] sm:text-[11px] text-[#b49140] uppercase tracking-wide font-bold mb-1 opacity-90 line-clamp-1">
			<?php echo esc_html( $brand_label ); ?>
		</p>
		
		<!-- Title -->
		<h3 class="text-xs md:text-sm font-bold leading-tight mb-2 line-clamp-1 group-hover:text-primary transition-colors m-0 overflow-hidden text-ellipsis whitespace-nowrap" style="line-height:1.6; <?php echo ! $product->is_in_stock() ? 'color:#9ca3af;' : 'color:#1f2937;'; ?>">
			<a href="<?php echo esc_url( $product_url ); ?>" class="text-inherit no-underline">
				<?php echo esc_html( $product->get_name() ); ?>
			</a>
		</h3>

		<!-- Price & Call to Action -->
		<div class="mt-auto n-card-footer">

			<!-- Price -->
			<div class="n-card-footer__price">
				<?php if ( ! $product->is_in_stock() ) : ?>
					<span class="n-card-price n-card-price--oos"><?php echo esc_html__( 'ناموجود', 'noble-theme' ); ?></span>
				<?php else : ?>
					<div class="n-card-price">
						<?php
						if ( $product->is_type( 'variable' ) ) {
							$min_regular = (float) $product->get_variation_regular_price( 'min', true );
							$min_sale    = (float) $product->get_variation_sale_price( 'min', true );
							$min_price   = (float) $product->get_variation_price( 'min', true );
							$max_price   = (float) $product->get_variation_price( 'max', true );

							$is_range   = ( $min_price !== $max_price );
							$is_on_sale = $product->is_on_sale() && $min_sale > 0 && $min_regular > 0 && $min_sale < $min_regular;
							?>
							<span class="price">
								<?php if ( $is_range ) : ?>
									<span class="n-card-price__prefix"><?php echo esc_html__( 'از', 'noble-theme' ); ?></span>
								<?php endif; ?>

								<?php if ( $is_on_sale ) : ?>
									<del class="n-card-price__old"><?php echo wp_kses_post( wc_price( $min_regular ) ); ?></del>
									<ins class="n-card-price__new"><?php echo wp_kses_post( wc_price( $min_sale ) ); ?></ins>
								<?php else : ?>
									<span class="n-card-price__new"><?php echo wp_kses_post( wc_price( $min_price ) ); ?></span>
								<?php endif; ?>
							</span>
							<?php
						} elseif ( $price_html = $product->get_price_html() ) {
							echo wp_kses_post( $price_html );
						}
						?>
					</div>
				<?php endif; ?>
			</div>

			<!-- Add to Cart -->
			<?php if ( $product->is_purchasable() && $product->is_in_stock() ) : ?>
				<?php
				$btn_class = implode(
					' ',
					array_filter(
						array(
							'n-card-atc',
							'button',
							'product_type_' . $product->get_type(),
							'add_to_cart_button',
							$product->supports( 'ajax_add_to_cart' ) ? 'ajax_add_to_cart' : '',
						)
					)
				);
				?>
				<a
					href="<?php echo esc_url( $product->add_to_cart_url() ); ?>"
					data-quantity="1"
					class="<?php echo esc_attr( $btn_class ); ?>"
					data-product_id="<?php echo esc_attr( (string) $product->get_id() ); ?>"
					data-product_sku="<?php echo esc_attr( $product->get_sku() ); ?>"
					aria-label="<?php echo esc_attr( $product->add_to_cart_description() ); ?>"
					rel="nofollow"
				>
					<span class="material-symbols-outlined" aria-hidden="true">shopping_cart</span>
				</a>
			<?php endif; ?>

		</div>
	</div>
</li>
