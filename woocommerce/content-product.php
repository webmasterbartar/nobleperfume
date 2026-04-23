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
		<h3 class="text-xs md:text-sm font-bold leading-tight mb-2 line-clamp-1 group-hover:text-primary transition-colors m-0 break-words" style="line-height:1.6; <?php echo ! $product->is_in_stock() ? 'color:#9ca3af;' : 'color:#1f2937;'; ?>">
			<a href="<?php echo esc_url( $product_url ); ?>" class="text-inherit no-underline">
				<?php echo esc_html( $product->get_name() ); ?>
			</a>
		</h3>

		<!-- Price & Call to Action -->
		<?php if ( ! defined( 'NOBLE_LOOP_STYLE_ADDED' ) ) : define( 'NOBLE_LOOP_STYLE_ADDED', true ); ?>
		<style>
		/* ── Noble Card: Price & CTA ── */

		/* price span: block, transparent bg */
		.n-card-price .price {
			display: block;
			background: transparent !important;
		}

		/* All woocommerce price amounts: clear background */
		.n-card-price .woocommerce-Price-amount {
			background: transparent !important;
		}

		/* ── Strikethrough: قیمت اصلی ── */
		.n-card-price del {
			display: block;
			font-size: 11px;
			font-weight: 400;
			color: #b0b0b0 !important;
			text-decoration: line-through;
			background: transparent !important;
			line-height: 1.5;
		}
		.n-card-price del bdi { color: #b0b0b0 !important; font-size: 11px !important; font-weight: 400 !important; }

		/* ── Sale price ── */
		.n-card-price ins {
			display: block;
			text-decoration: none !important;
			background: transparent !important;
			line-height: 1.5;
		}
		.n-card-price ins bdi { color: #051061 !important; font-size: 14px !important; font-weight: 700 !important; }

		/* ── Regular price (no sale) ── */
		.n-card-price .price > .woocommerce-Price-amount bdi {
			color: #051061 !important;
			font-size: 14px !important;
			font-weight: 700 !important;
		}


		/* ── Cart button baseline ── */
		a.n-cart-btn.button {
			display: flex !important;
			align-items: center !important;
			justify-content: center !important;
			gap: 6px !important;
			text-decoration: none !important;
			border: none !important;
			cursor: pointer !important;
			box-shadow: none !important;
			flex-wrap: nowrap !important;
			transition: background 0.22s ease, color 0.22s ease, transform 0.15s ease !important;
			outline: none !important;
			position: relative !important;
			overflow: hidden !important;
		}

		/* WooCommerce injects "View cart" link after AJAX add-to-cart.
		   In this compact card UI we keep the layout stable and hide that extra link. */
		li.product a.added_to_cart {
			display: none !important;
		}

		/* Keep icon button visible while ajax request runs */
		li.product a.n-cart-btn.loading {
			opacity: 1 !important;
		}
		li.product a.n-cart-btn.button.is-loading {
			pointer-events: none !important;
			color: transparent !important;
		}
		li.product a.n-cart-btn.button.is-loading .material-symbols-outlined {
			opacity: 0 !important;
		}
		li.product a.n-cart-btn.button.is-loading::after {
			content: "";
			position: absolute;
			width: 16px;
			height: 16px;
			border-radius: 50%;
			border: 2px solid rgba(5, 16, 97, .2);
			border-top-color: #051061;
			animation: nCartSpin .65s linear infinite;
		}
		li.product a.n-cart-btn.button.n-added-success,
		li.product a.n-cart-btn.button.n-added-success:hover {
			background: linear-gradient(135deg, #15803d 0%, #22c55e 100%) !important;
			color: #fff !important;
			transform: none !important;
			box-shadow: 0 10px 20px -12px rgba(21, 128, 61, .55) !important;
		}
		li.product a.n-cart-btn.button.n-added-success .material-symbols-outlined {
			color: #fff !important;
		}
		@keyframes nCartSpin {
			to { transform: rotate(360deg); }
		}

		/* ── Mobile: icon-only button ── */
		@media (max-width: 767px) {
			a.n-cart-btn.button {
				width: 36px !important;
				height: 36px !important;
				border-radius: 50% !important;
				padding: 0 !important;
				background: #eef0f8 !important;
				color: #051061 !important;
			}
			a.n-cart-btn.button:hover {
				background: #051061 !important;
				color: #fff !important;
				transform: scale(1.08) !important;
			}
			a.n-cart-btn.button .material-symbols-outlined { font-size: 18px !important; line-height: 1 !important; margin: 0 !important; padding: 0 !important; }
			.n-cart-text { display: none !important; }
		}

		/* ── Desktop: compact icon-only button ── */
		@media (min-width: 768px) {
			a.n-cart-btn.button {
				width: 36px !important;
				height: 36px !important;
				padding: 0 !important;
				border-radius: 50% !important;
				background: #eef0f8 !important;
				color: #051061 !important;
				line-height: 1 !important;
			}
			a.n-cart-btn.button:hover {
				background: #051061 !important;
				color: #fff !important;
				transform: scale(1.08) !important;
				box-shadow: none !important;
			}
			a.n-cart-btn.button .material-symbols-outlined { font-size: 16px !important; line-height: 1 !important; margin: 0 !important; padding: 0 !important; }
			.n-cart-text { display: none !important; }
		}
		</style>
		<?php endif; ?>
		<?php if ( ! defined( 'NOBLE_LOOP_ATC_SCRIPT_ADDED' ) ) : define( 'NOBLE_LOOP_ATC_SCRIPT_ADDED', true ); ?>
		<script>
		(function() {
			if (typeof jQuery === 'undefined') {
				return;
			}
			var originKey = 'data-n-cart-origin';
			var successIcon = '<span class="material-symbols-outlined">check</span>';

			jQuery(document.body).on('adding_to_cart', function(event, $button) {
				if (!$button || !$button.length || !$button.hasClass('n-cart-btn')) {
					return;
				}
				if (!$button.attr(originKey)) {
					$button.attr(originKey, $button.html());
				}
				$button.removeClass('n-added-success');
				$button.addClass('is-loading');
			});

			jQuery(document.body).on('added_to_cart', function(event, fragments, cartHash, $button) {
				if (!$button || !$button.length || !$button.hasClass('n-cart-btn')) {
					return;
				}
				$button.removeClass('is-loading').addClass('n-added-success');
				$button.html(successIcon);
			});
		})();
		</script>
		<?php endif; ?>

		<div class="mt-auto" style="padding-top:12px; display:flex; align-items:center; justify-content:space-between; border-top:1px solid #f0f0f0; gap:8px;">

			<!-- Price -->
			<div style="flex: 1 1 auto; overflow: hidden; text-align: right;">
				<?php if ( ! $product->is_in_stock() ) : ?>
					<span style="font-size:12px; font-weight:600; color:#b0b0b0;">ناموجود</span>
				<?php elseif ( $price_html = $product->get_price_html() ) : ?>
					<div class="n-card-price">
						<?php echo wp_kses_post( $price_html ); ?>
					</div>
				<?php endif; ?>
			</div>

			<!-- Add to Cart -->
			<?php if ( $product->is_purchasable() && $product->is_in_stock() ) : ?>
				<div style="flex-shrink:0;">
					<?php
					$cart_text = esc_html( $product->add_to_cart_text() );
					$cart_url  = esc_url( $product->add_to_cart_url() );
					$is_in_cart = false;
					if ( function_exists( 'WC' ) && WC() && WC()->cart ) {
						foreach ( WC()->cart->get_cart() as $cart_item ) {
							if ( isset( $cart_item['product_id'] ) && (int) $cart_item['product_id'] === (int) $product->get_id() ) {
								$is_in_cart = true;
								break;
							}
						}
					}
					$btn_class = implode( ' ', array_filter( array(
						'n-cart-btn',
						'button product_type_' . $product->get_type(),
						$product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
						$product->supports( 'ajax_add_to_cart' ) && $product->is_purchasable() && $product->is_in_stock() ? 'ajax_add_to_cart' : '',
						$is_in_cart ? 'n-added-success' : '',
					) ) );

					printf(
						'<a href="%s" data-quantity="1" class="%s" data-product_id="%d" data-product_sku="%s" aria-label="%s" rel="nofollow">',
						$cart_url,
						esc_attr( $btn_class ),
						$product->get_id(),
						esc_attr( $product->get_sku() ),
						esc_attr( $product->add_to_cart_description() )
					);
					?>
					<span class="material-symbols-outlined"><?php echo $is_in_cart ? 'check' : 'shopping_cart'; ?></span>
					</a>
				</div>
			<?php endif; ?>

		</div>
	</div>
</li>
