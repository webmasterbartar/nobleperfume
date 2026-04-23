<?php
/**
 * Custom checkout flow: Step 1 (information) → Step 2 (shipping) → Step 3 (payment).
 *
 * @package noble-theme
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_checkout_form', $checkout );

if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
	echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) ) );
	return;
}

$cart_count    = function_exists( 'WC' ) && WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
$order_total   = function_exists( 'WC' ) && WC()->cart ? WC()->cart->get_total() : '';
$raw_step      = isset( $_GET['noble_step'] ) ? absint( wp_unslash( $_GET['noble_step'] ) ) : 1; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
$current_step  = ( $raw_step >= 1 && $raw_step <= 3 ) ? $raw_step : 1;
$is_step_one   = 1 === $current_step;
$is_step_two   = 2 === $current_step;
$is_step_three = 3 === $current_step;
$step_one_url  = add_query_arg( 'noble_step', 1, wc_get_checkout_url() );
$step_two_url  = add_query_arg( 'noble_step', 2, wc_get_checkout_url() );
$step_three_url = add_query_arg( 'noble_step', 3, wc_get_checkout_url() );
$fallback_back_url = function_exists( 'wc_get_page_permalink' ) ? (string) wc_get_page_permalink( 'shop' ) : home_url( '/' );
$header_back_url   = wp_get_referer( $fallback_back_url );
if ( $is_step_two ) {
	$header_back_url = $step_one_url;
} elseif ( $is_step_three ) {
	$header_back_url = $step_two_url;
}

// Handle coupon actions directly on step 1.
if (
	$is_step_one &&
	isset( $_SERVER['REQUEST_METHOD'] ) &&
	'POST' === strtoupper( (string) $_SERVER['REQUEST_METHOD'] ) &&
	isset( $_POST['noble_coupon_action'] ) &&
	function_exists( 'WC' ) &&
	WC()->cart
) {
	$nonce_ok = isset( $_POST['noble_step1_coupon_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( (string) $_POST['noble_step1_coupon_nonce'] ) ), 'noble_step1_coupon_action' );
	if ( $nonce_ok ) {
		$action = sanitize_key( wp_unslash( (string) $_POST['noble_coupon_action'] ) );
		if ( 'apply' === $action ) {
			$coupon_code = isset( $_POST['coupon_code'] ) ? wc_format_coupon_code( sanitize_text_field( wp_unslash( (string) $_POST['coupon_code'] ) ) ) : '';
			if ( '' !== $coupon_code ) {
				WC()->cart->apply_coupon( $coupon_code );
				WC()->cart->calculate_totals();
			}
		} elseif ( 'remove' === $action ) {
			$coupon_code = isset( $_POST['remove_coupon_code'] ) ? wc_format_coupon_code( sanitize_text_field( wp_unslash( (string) $_POST['remove_coupon_code'] ) ) ) : '';
			if ( '' !== $coupon_code ) {
				WC()->cart->remove_coupon( $coupon_code );
				WC()->cart->calculate_totals();
			}
		}
	}
	wp_safe_redirect( $step_one_url );
	exit;
}

$discount_total_raw  = function_exists( 'WC' ) && WC()->cart ? (float) WC()->cart->get_discount_total() + (float) WC()->cart->get_discount_tax() : 0.0;
$discount_total_html = $discount_total_raw > 0 ? wc_price( $discount_total_raw ) : '';
$applied_coupons     = function_exists( 'WC' ) && WC()->cart ? (array) WC()->cart->get_coupons() : array();
$iran_states         = function_exists( 'WC' ) && WC()->countries ? (array) WC()->countries->get_states( 'IR' ) : array();
?>
<section class="noble-checkout-wrap <?php echo $is_step_three ? 'noble-checkout-step-3' : ( $is_step_two ? 'noble-checkout-step-2' : 'noble-checkout-step-1' ); ?> bg-background min-h-screen pb-32">
	<header class="fixed top-0 w-full z-50 bg-surface flex justify-between items-center h-16 border-b border-primary/10">
		<div class="noble-checkout-header-inner w-full max-w-[1300px] mx-auto px-5 sm:px-6 lg:px-8 flex items-center justify-between">
			<div class="flex items-center gap-4">
				<a href="<?php echo esc_url( $header_back_url ); ?>" class="hover:bg-surface-container-high transition-colors p-2 rounded-full active:scale-95 duration-150">
					<span class="material-symbols-outlined text-primary">arrow_back</span>
				</a>
				<h1 class="text-[1.25rem] md:text-[1.5rem] font-bold text-on-surface">تسویه حساب</h1>
			</div>
			<div class="flex items-center gap-2">
				<?php if ( $is_step_one ) : ?>
					<span class="text-sm font-medium text-primary bg-surface-container-low px-3 py-1 rounded-full">مرحله ۱ از ۳</span>
				<?php elseif ( $is_step_two ) : ?>
					<span class="text-sm font-medium text-primary bg-surface-container-low px-3 py-1 rounded-full">مرحله ۲ از ۳</span>
				<?php elseif ( $is_step_three ) : ?>
					<span class="text-sm font-medium text-primary bg-surface-container-low px-3 py-1 rounded-full">مرحله ۳ از ۳</span>
				<?php endif; ?>
			</div>
		</div>
	</header>

	<?php if ( $is_step_one ) : ?>
		<main class="w-full max-w-[1300px] mx-auto px-5 sm:px-6 lg:px-8 pt-20">
			<div class="noble-step1-inner max-w-md mx-auto md:max-w-[1300px]">
			<div class="noble-step1-main">
			<nav class="noble-checkout-progress mb-8 px-1" aria-label="Checkout steps">
				<div class="noble-checkout-progress-item is-current">
					<div class="noble-checkout-progress-dot"><span class="material-symbols-outlined">person</span></div>
					<span class="noble-checkout-progress-label">اطلاعات</span>
				</div>
				<div class="noble-checkout-progress-line"></div>
				<div class="noble-checkout-progress-item">
					<div class="noble-checkout-progress-dot"><span class="material-symbols-outlined">local_shipping</span></div>
					<span class="noble-checkout-progress-label">ارسال</span>
				</div>
				<div class="noble-checkout-progress-line"></div>
				<div class="noble-checkout-progress-item">
					<div class="noble-checkout-progress-dot"><span class="material-symbols-outlined">payments</span></div>
					<span class="noble-checkout-progress-label">پرداخت</span>
				</div>
			</nav>

			<?php if ( wc_coupons_enabled() ) : ?>
				<form class="md:hidden noble-step1-coupon-mobile w-full flex flex-row items-center gap-2 mb-6" method="post" action="<?php echo esc_url( $step_one_url ); ?>">
					<div class="noble-step1-coupon-mobile-input-wrap min-w-0 flex-1">
						<span class="material-symbols-outlined noble-step1-coupon-mobile-icon">sell</span>
						<input class="noble-step1-coupon-mobile-input min-w-0 w-full bg-surface-container-high border-none rounded-xl px-4 py-3 pr-12 focus:ring-2 focus:ring-primary/20 text-sm" placeholder="کد تخفیف" type="text" name="coupon_code" />
					</div>
					<input type="hidden" name="noble_coupon_action" value="apply" />
					<?php wp_nonce_field( 'noble_step1_coupon_action', 'noble_step1_coupon_nonce' ); ?>
					<button class="noble-step1-coupon-mobile-btn shrink-0 px-5 py-3 font-bold rounded-xl transition-colors text-sm" type="submit">اعمال</button>
				</form>
			<?php endif; ?>

			<form id="noble-step1-checkout-form" method="post" class="noble-step1-form space-y-8" action="<?php echo esc_url( $step_two_url ); ?>" enctype="multipart/form-data" aria-label="<?php echo esc_attr__( 'Checkout', 'woocommerce' ); ?>">
				<input type="hidden" name="noble_step1_submit" value="1" />
				<div id="noble-step1-inline-error" class="noble-step1-inline-error hidden" role="alert" aria-live="assertive"></div>
				<section class="space-y-6">
					<header class="space-y-1">
						<h2 class="text-xl font-extrabold text-on-surface">اطلاعات کاربری</h2>
						<p class="text-sm text-on-surface-variant">لطفاً مشخصات خود را جهت ثبت سفارش وارد نمایید.</p>
					</header>
					<div class="space-y-4">
						<div class="grid grid-cols-2 gap-4">
							<div class="group">
								<label class="block text-sm font-bold mb-2 text-on-surface-variant mr-1">نام</label>
								<input class="noble-step1-input w-full bg-surface-container-high border-none rounded-xl py-4 px-4" placeholder="مثلاً رضا" type="text" name="billing_first_name" required autocomplete="given-name" value="<?php echo esc_attr( $checkout->get_value( 'billing_first_name' ) ); ?>" />
							</div>
							<div class="group">
								<label class="block text-sm font-bold mb-2 text-on-surface-variant mr-1">نام خانوادگی</label>
								<input class="noble-step1-input w-full bg-surface-container-high border-none rounded-xl py-4 px-4" placeholder="مثلاً محمدی" type="text" name="billing_last_name" required autocomplete="family-name" value="<?php echo esc_attr( $checkout->get_value( 'billing_last_name' ) ); ?>" />
							</div>
						</div>
						<div class="group">
							<label class="block text-sm font-bold mb-2 text-on-surface-variant mr-1">شماره موبایل</label>
							<div class="relative">
								<input class="noble-step1-input noble-step1-ltr w-full bg-surface-container-high border-none rounded-xl py-4 pr-4 pl-12 text-right" placeholder="09123456789" type="tel" name="billing_phone" required inputmode="numeric" pattern="09[0-9]{9}" maxlength="11" autocomplete="tel-national" value="<?php echo esc_attr( $checkout->get_value( 'billing_phone' ) ); ?>" />
								<span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline">smartphone</span>
							</div>
						</div>
					</div>
				</section>

				<section class="space-y-6 pt-2">
					<header class="space-y-1">
						<div class="space-y-1">
							<h2 class="text-xl font-extrabold text-on-surface">نشانی ارسال</h2>
							<p class="text-sm text-on-surface-variant">محل تحویل بسته را مشخص کنید.</p>
						</div>
					</header>
					<div class="space-y-4">
						<div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
							<div>
								<label class="block text-xs font-bold mb-1 text-on-surface-variant mr-1">استان</label>
								<select class="noble-step1-input w-full bg-surface-container-high border-none rounded-lg py-3 px-3" name="billing_state" required autocomplete="address-level1" data-noble-state-select="1">
									<option value="">انتخاب استان</option>
									<?php foreach ( $iran_states as $state_code => $state_label ) : ?>
										<option value="<?php echo esc_attr( (string) $state_code ); ?>" <?php selected( $checkout->get_value( 'billing_state' ), $state_code ); ?>><?php echo esc_html( (string) $state_label ); ?></option>
									<?php endforeach; ?>
								</select>
							</div>
							<div>
								<label class="block text-xs font-bold mb-1 text-on-surface-variant mr-1">شهر</label>
								<input class="noble-step1-input w-full bg-surface-container-high border-none rounded-lg py-3 px-3" placeholder="مثلاً تهران" type="text" name="billing_city" required autocomplete="address-level2" value="<?php echo esc_attr( $checkout->get_value( 'billing_city' ) ); ?>" />
							</div>
						</div>
						<div class="relative">
							<label class="block text-sm font-bold mb-2 text-on-surface-variant mr-1">جستجوی آدرس</label>
							<div class="relative">
								<input class="noble-step1-input noble-step1-address-search w-full bg-surface-container-high border-none rounded-xl py-4 pr-4 pl-4" placeholder="نام خیابان یا محله..." type="text" name="billing_address_1" required autocomplete="address-line1" value="<?php echo esc_attr( $checkout->get_value( 'billing_address_1' ) ); ?>" />
								<span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-outline">location_on</span>
							</div>
						</div>
						<div class="grid grid-cols-12 gap-3">
							<div class="col-span-4">
								<label class="block text-xs font-bold mb-1 text-on-surface-variant mr-1">پلاک</label>
								<input class="noble-step1-input w-full bg-surface-container-high border-none rounded-lg py-3 px-3 text-center" type="text" name="noble_plaque" autocomplete="off" value="<?php echo esc_attr( $checkout->get_value( 'noble_plaque' ) ); ?>" />
							</div>
							<div class="col-span-4">
								<label class="block text-xs font-bold mb-1 text-on-surface-variant mr-1">واحد</label>
								<input class="noble-step1-input w-full bg-surface-container-high border-none rounded-lg py-3 px-3 text-center" type="text" name="billing_address_2" value="<?php echo esc_attr( $checkout->get_value( 'billing_address_2' ) ); ?>" />
							</div>
							<div class="col-span-4">
								<label class="block text-xs font-bold mb-1 text-on-surface-variant mr-1">کد پستی</label>
								<input class="noble-step1-input noble-step1-ltr w-full bg-surface-container-high border-none rounded-lg py-3 px-3 text-center" type="text" name="billing_postcode" required inputmode="numeric" pattern="[0-9]{10}" maxlength="10" autocomplete="postal-code" value="<?php echo esc_attr( $checkout->get_value( 'billing_postcode' ) ); ?>" />
							</div>
						</div>
					</div>
				</section>

				<article class="noble-step1-product-card bg-surface-container-lowest p-5 rounded-2xl shadow-[0_12px_32px_rgba(26,28,28,0.06)] space-y-4 border border-primary/10">
					<div class="flex items-center justify-between border-b border-primary/10 pb-3">
						<h3 class="text-sm font-extrabold text-primary">خلاصه محصولات انتخابی</h3>
						<span class="text-[11px] font-bold text-on-surface-variant"><?php echo esc_html( number_format_i18n( $cart_count ) ); ?> آیتم</span>
					</div>
					<?php
					$step1_items_rendered = 0;
					foreach ( WC()->cart->get_cart() as $cart_item_key => $item ) :
						if ( empty( $item['data'] ) || ! is_object( $item['data'] ) ) {
							continue;
						}
						$preview_product = $item['data'];
						$remove_url      = wc_get_cart_remove_url( $cart_item_key );
						$qty_plus_url    = add_query_arg(
							array(
								'noble_step'      => 1,
								'noble_qty_action'=> 'plus',
								'cart_item'       => $cart_item_key,
							),
							wc_get_checkout_url()
						);
						$qty_minus_url   = add_query_arg(
							array(
								'noble_step'      => 1,
								'noble_qty_action'=> 'minus',
								'cart_item'       => $cart_item_key,
							),
							wc_get_checkout_url()
						);
						$step1_items_rendered++;
						?>
						<div class="noble-step1-product-row flex items-center gap-3.5 rounded-xl bg-white/80 py-3.5 px-4 border border-primary/10">
							<div class="w-16 h-16 rounded-lg overflow-hidden flex-shrink-0 bg-background/60">
								<?php echo wp_kses_post( $preview_product->get_image( 'woocommerce_thumbnail', array( 'class' => 'w-full h-full object-cover' ) ) ); ?>
							</div>
							<div class="flex-1 min-w-0">
								<h4 class="text-[13px] font-bold text-on-surface line-clamp-1 mb-1"><?php echo esc_html( $preview_product->get_name() ); ?></h4>
								<div class="flex justify-between items-center">
									<div class="noble-step1-qty-control inline-flex items-center rounded-lg border border-primary/15 bg-surface-container-lowest">
										<a class="noble-step1-qty-btn" href="<?php echo esc_url( $qty_minus_url ); ?>" aria-label="کاهش تعداد">
											<span class="material-symbols-outlined text-[16px]">remove</span>
										</a>
										<span class="noble-step1-qty-value"><?php echo esc_html( number_format_i18n( (int) $item['quantity'] ) ); ?></span>
										<a class="noble-step1-qty-btn" href="<?php echo esc_url( $qty_plus_url ); ?>" aria-label="افزایش تعداد">
											<span class="material-symbols-outlined text-[16px]">add</span>
										</a>
									</div>
									<span class="text-[13px] font-semibold text-primary"><?php echo wp_kses_post( WC()->cart->get_product_subtotal( $preview_product, (int) $item['quantity'] ) ); ?></span>
								</div>
							</div>
							<a class="noble-step1-remove-btn" href="<?php echo esc_url( $remove_url ); ?>" aria-label="حذف محصول">
								<span class="material-symbols-outlined text-[18px]">delete</span>
							</a>
						</div>
					<?php endforeach; ?>
					<?php if ( 0 === $step1_items_rendered ) : ?>
						<div class="text-sm text-on-surface-variant">محصولی برای نمایش ثبت نشده است.</div>
					<?php endif; ?>
				</article>

				<input type="hidden" name="billing_country" value="<?php echo esc_attr( $checkout->get_value( 'billing_country' ) ? $checkout->get_value( 'billing_country' ) : 'IR' ); ?>" />
				<input type="hidden" name="shipping_country" value="<?php echo esc_attr( $checkout->get_value( 'shipping_country' ) ? $checkout->get_value( 'shipping_country' ) : 'IR' ); ?>" />
				<input type="hidden" name="shipping_state" value="<?php echo esc_attr( $checkout->get_value( 'shipping_state' ) ); ?>" />

			</form>
			</div>
			<aside class="noble-step1-aside hidden md:block md:sticky md:top-24">
				<div class="noble-step1-aside-card rounded-2xl border border-primary/10 bg-white p-6 space-y-6 shadow-[0_12px_32px_rgba(26,28,28,0.06)]">
					<div class="pb-3 border-b border-primary/10">
						<h3 class="text-base font-extrabold text-primary">خلاصه سفارش</h3>
						<p class="text-sm text-on-surface-variant mt-1 leading-6">بررسی نهایی قبل از مرحله ارسال</p>
					</div>
					<?php if ( wc_coupons_enabled() ) : ?>
						<form class="w-full flex flex-row items-center gap-2" method="post" action="<?php echo esc_url( $step_one_url ); ?>">
							<input class="min-w-0 flex-1 bg-surface-container-high border-none rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary/20 text-sm" placeholder="کد تخفیف" type="text" name="coupon_code" />
							<input type="hidden" name="noble_coupon_action" value="apply" />
							<?php wp_nonce_field( 'noble_step1_coupon_action', 'noble_step1_coupon_nonce' ); ?>
							<button class="shrink-0 px-5 py-3 font-bold text-primary hover:bg-primary/5 rounded-xl transition-colors text-sm" type="submit">اعمال</button>
						</form>
					<?php endif; ?>
					<div class="space-y-4">
						<?php if ( ! empty( $applied_coupons ) ) : ?>
							<div class="noble-step1-coupons space-y-2">
								<?php foreach ( $applied_coupons as $coupon_code => $coupon_obj ) : ?>
									<div class="noble-step1-coupon-row flex items-center justify-between rounded-xl border border-emerald-200 bg-emerald-50/80 px-3 py-2">
										<div class="flex items-center gap-2 min-w-0">
											<span class="material-symbols-outlined text-[16px] text-emerald-600">sell</span>
											<span class="truncate text-[12px] font-bold text-emerald-700"><?php echo esc_html( wc_format_coupon_code( $coupon_code ) ); ?></span>
											<span class="text-[12px] font-semibold text-emerald-800"><?php wc_cart_totals_coupon_html( $coupon_obj ); ?></span>
										</div>
										<form method="post" action="<?php echo esc_url( $step_one_url ); ?>">
											<input type="hidden" name="noble_coupon_action" value="remove" />
											<input type="hidden" name="remove_coupon_code" value="<?php echo esc_attr( (string) $coupon_code ); ?>" />
											<?php wp_nonce_field( 'noble_step1_coupon_action', 'noble_step1_coupon_nonce' ); ?>
											<button type="submit" class="text-[11px] font-bold text-emerald-700 hover:text-emerald-900 px-2 py-1 rounded-lg hover:bg-emerald-100 transition-colors">حذف</button>
										</form>
									</div>
								<?php endforeach; ?>
							</div>
						<?php endif; ?>
						<div class="flex justify-between items-center text-base gap-6">
							<span class="text-on-surface-variant font-medium">تعداد کالا</span>
							<span class="font-extrabold text-on-surface whitespace-nowrap text-left [direction:ltr] [font-variant-numeric:tabular-nums]"><?php echo esc_html( number_format_i18n( $cart_count ) ); ?></span>
						</div>
						<?php if ( $discount_total_raw > 0 ) : ?>
							<div class="flex justify-between items-center text-base gap-6">
								<span class="text-emerald-700 font-bold">تخفیف</span>
								<span class="font-extrabold text-emerald-700 whitespace-nowrap text-left [direction:ltr] [font-variant-numeric:tabular-nums]">-<?php echo wp_kses_post( $discount_total_html ); ?></span>
							</div>
						<?php endif; ?>
						<div class="flex justify-between items-center pt-4 border-t border-primary/10 gap-6">
							<span class="text-on-surface-variant font-medium">جمع کل سفارش</span>
							<span class="text-xl font-extrabold text-on-surface whitespace-nowrap text-left [direction:ltr] [font-variant-numeric:tabular-nums]"><?php echo wp_kses_post( $order_total ); ?></span>
						</div>
					</div>
					<button type="submit" form="noble-step1-checkout-form" class="noble-step1-cta w-full text-white py-4 rounded-2xl font-extrabold text-base inline-flex items-center justify-center border-0">تکمیل خرید</button>
				</div>
			</aside>
			</div>
		</main>

		<footer class="noble-step1-mobile-footer fixed bottom-0 w-full z-50 bg-surface px-8 py-6 pb-8 shadow-[0_-12px_32px_rgba(26,28,28,0.06)] rounded-t-3xl md:hidden">
			<div class="max-w-md mx-auto flex flex-col gap-4">
				<div class="flex justify-between items-center px-2">
					<span class="text-on-surface-variant font-medium">جمع کل سفارش:</span>
					<span class="text-lg font-bold text-on-surface"><?php echo wp_kses_post( $order_total ); ?></span>
				</div>
				<?php if ( $discount_total_raw > 0 ) : ?>
					<div class="flex justify-between items-center px-2 rounded-xl bg-emerald-50 border border-emerald-200 py-2">
						<span class="text-emerald-700 text-sm font-bold">تخفیف اعمال‌شده</span>
						<span class="text-emerald-700 text-sm font-extrabold">-<?php echo wp_kses_post( $discount_total_html ); ?></span>
					</div>
				<?php endif; ?>
				<button type="submit" form="noble-step1-checkout-form" class="noble-step1-cta w-full text-white py-4 rounded-2xl font-bold text-lg inline-flex items-center justify-center border-0">تکمیل خرید</button>
			</div>
		</footer>
	<?php elseif ( $is_step_two ) : ?>
		<?php
		$packages = function_exists( 'WC' ) && WC()->shipping ? WC()->shipping->get_packages() : array();
		$chosen   = function_exists( 'WC' ) && WC()->session ? (array) WC()->session->get( 'chosen_shipping_methods', array() ) : array();
		$all_rates = array();
		foreach ( $packages as $pkg_idx => $pkg ) {
			if ( empty( $pkg['rates'] ) || ! is_array( $pkg['rates'] ) ) {
				continue;
			}
			foreach ( $pkg['rates'] as $rate_id => $rate ) {
				$all_rates[] = array(
					'package' => (int) $pkg_idx,
					'id'      => (string) $rate_id,
					'rate'    => $rate,
				);
			}
		}
		$selected_shipping_label = '';
		$selected_shipping_cost  = '';
		if ( ! empty( $all_rates ) ) {
			foreach ( $all_rates as $idx => $row ) {
				/** @var WC_Shipping_Rate $rate */
				$rate     = $row['rate'];
				$rate_id  = $row['id'];
				$pkg_idx  = (int) $row['package'];
				$is_match = isset( $chosen[ $pkg_idx ] ) ? ( (string) $chosen[ $pkg_idx ] === (string) $rate_id ) : ( 0 === $idx );
				if ( ! $is_match ) {
					continue;
				}
				$selected_shipping_label = $rate ? (string) $rate->get_label() : '';
				$selected_shipping_cost  = $rate ? (float) $rate->get_cost() : 0.0;
				break;
			}
		}
		$selected_shipping_cost_html = ( '' !== $selected_shipping_label && (float) $selected_shipping_cost > 0 ) ? wc_price( (float) $selected_shipping_cost ) : 'رایگان';
		$order_total_raw_for_step2   = function_exists( 'WC' ) && WC()->cart ? (float) WC()->cart->get_total( 'edit' ) : 0.0;
		$step2_base_total            = max( 0, $order_total_raw_for_step2 - (float) $selected_shipping_cost );
		?>
		<main class="w-full max-w-[1300px] mx-auto px-5 sm:px-6 lg:px-8 pt-20">
			<div class="noble-step2-inner mx-auto px-0 pt-6 md:pt-8">
				<nav class="noble-checkout-progress mb-10 px-1" aria-label="Checkout steps">
					<div class="noble-checkout-progress-item is-done">
						<div class="noble-checkout-progress-dot"><span class="material-symbols-outlined">check</span></div>
						<span class="noble-checkout-progress-label">اطلاعات</span>
					</div>
					<div class="noble-checkout-progress-line is-done"></div>
					<div class="noble-checkout-progress-item is-current">
						<div class="noble-checkout-progress-dot"><span class="material-symbols-outlined">local_shipping</span></div>
						<span class="noble-checkout-progress-label">ارسال</span>
					</div>
					<div class="noble-checkout-progress-line"></div>
					<div class="noble-checkout-progress-item">
						<div class="noble-checkout-progress-dot"><span class="material-symbols-outlined">payments</span></div>
						<span class="noble-checkout-progress-label">پرداخت</span>
					</div>
				</nav>

				<header class="mb-8">
					<h2 class="text-2xl font-black text-on-surface mb-2">انتخاب شیوه ارسال</h2>
					<p class="text-on-surface-variant text-sm">لطفاً زمان و هزینه مورد نظر خود را انتخاب کنید.</p>
				</header>

				<form method="post" action="<?php echo esc_url( $step_three_url ); ?>" class="noble-step2-form">
					<div class="noble-step2-layout">
						<div class="noble-step2-main space-y-4">
							<?php if ( empty( $all_rates ) ) : ?>
								<div class="rounded-2xl border border-primary/10 bg-white p-5 text-sm text-on-surface-variant">
									هنوز روش ارسال در دسترس نیست. لطفا آدرس را بررسی کنید.
								</div>
							<?php else : ?>
								<?php foreach ( $all_rates as $idx => $row ) : ?>
									<?php
									/** @var WC_Shipping_Rate $rate */
									$rate     = $row['rate'];
									$rate_id  = $row['id'];
									$pkg_idx  = (int) $row['package'];
									$selected = isset( $chosen[ $pkg_idx ] ) ? ( (string) $chosen[ $pkg_idx ] === (string) $rate_id ) : ( 0 === $idx );
									$label    = $rate ? $rate->get_label() : '';
									$cost_num = $rate ? (float) $rate->get_cost() : 0.0;
									$cost     = $rate ? wc_price( $cost_num ) : '';
									$method_id = $rate ? (string) $rate->get_method_id() : '';
									$desc = 'ارسال مطابق روش انتخابی شما';
									if ( false !== strpos( $method_id, 'free_shipping' ) ) {
										$desc = 'ارسال اقتصادی با بازه زمانی معمول';
									} elseif ( false !== strpos( $method_id, 'flat_rate' ) ) {
										$desc = 'ارسال استاندارد با رهگیری سفارش';
									} elseif ( false !== strpos( $method_id, 'local_pickup' ) ) {
										$desc = 'تحویل حضوری از فروشگاه';
									}
									?>
									<label class="noble-step2-option block relative cursor-pointer group">
										<input class="peer hidden" name="noble_chosen_shipping[<?php echo esc_attr( $pkg_idx ); ?>]" type="radio" value="<?php echo esc_attr( $rate_id ); ?>" data-shipping-label="<?php echo esc_attr( $label ); ?>" data-shipping-cost="<?php echo esc_attr( (string) $cost_num ); ?>" <?php checked( $selected ); ?> />
										<div class="noble-step2-option-card p-5 rounded-2xl bg-surface-container-lowest border-2 border-transparent peer-checked:border-primary transition-all shadow-sm flex items-start gap-4">
											<div class="w-12 h-12 rounded-xl bg-surface-container flex items-center justify-center overflow-hidden shrink-0">
												<span class="material-symbols-outlined text-primary">local_shipping</span>
											</div>
											<div class="flex-1">
												<div class="flex justify-between items-start mb-1">
													<h3 class="font-bold text-on-surface text-lg"><?php echo esc_html( $label ); ?></h3>
													<span class="text-primary font-bold"><?php echo wp_kses_post( $cost && 0.0 !== $cost_num ? $cost : 'رایگان' ); ?></span>
												</div>
												<p class="text-on-surface-variant text-sm"><?php echo esc_html( $desc ); ?></p>
											</div>
											<div class="w-6 h-6 rounded-full border-2 border-outline-variant peer-checked:border-primary peer-checked:bg-primary flex items-center justify-center mt-1 shrink-0">
												<div class="w-2 h-2 rounded-full bg-white opacity-0 peer-checked:opacity-100"></div>
											</div>
										</div>
									</label>
								<?php endforeach; ?>
							<?php endif; ?>
						</div>

						<aside class="noble-step2-aside mt-6 lg:mt-0 lg:sticky lg:top-24">
							<section class="p-6 rounded-3xl bg-white border border-primary/10 shadow-[0_12px_30px_rgba(21,28,38,0.06)]" data-step2-base-total="<?php echo esc_attr( (string) $step2_base_total ); ?>">
								<h4 class="text-sm font-extrabold text-primary mb-5 flex items-center gap-2">
									<span class="material-symbols-outlined text-lg">receipt_long</span>
									خلاصه سفارش
								</h4>
								<div class="space-y-3.5">
									<div class="flex justify-between items-center text-sm">
										<span class="text-on-surface-variant">قیمت کالاها (<?php echo esc_html( number_format_i18n( $cart_count ) ); ?> مورد)</span>
										<span class="text-on-surface font-semibold"><?php wc_cart_totals_subtotal_html(); ?></span>
									</div>
									<div class="flex justify-between items-center text-sm">
										<span class="text-on-surface-variant">هزینه ارسال</span>
										<span id="noble-step2-shipping-value" class="text-primary font-semibold"><?php echo wp_kses_post( $selected_shipping_cost_html ); ?></span>
									</div>
									<?php if ( '' !== $selected_shipping_label ) : ?>
										<div id="noble-step2-shipping-label" class="text-[11px] leading-6 text-on-surface-variant"><?php echo esc_html( $selected_shipping_label ); ?></div>
									<?php else : ?>
										<div id="noble-step2-shipping-label" class="text-[11px] leading-6 text-on-surface-variant"></div>
									<?php endif; ?>
									<div class="pt-4 border-t border-primary/10 flex justify-between items-center">
										<span class="font-extrabold text-on-surface">مبلغ قابل پرداخت</span>
										<span id="noble-step2-order-total-desktop" class="font-black text-primary text-xl"><?php wc_cart_totals_order_total_html(); ?></span>
									</div>
								</div>
								<button type="submit" class="noble-step1-cta hidden lg:inline-flex mt-6 w-full px-8 py-4 rounded-2xl font-bold text-base items-center justify-center gap-2 border-0">
									<span>تایید و ادامه خرید</span>
									<span class="material-symbols-outlined">chevron_left</span>
								</button>
								<a href="<?php echo esc_url( $step_one_url ); ?>" class="noble-step2-back-btn hidden lg:inline-flex mt-3 w-full px-8 py-3 rounded-2xl font-bold text-sm items-center justify-center gap-2">
									<span class="material-symbols-outlined text-[18px]">chevron_right</span>
									<span>بازگشت به مرحله اطلاعات</span>
								</a>
							</section>
						</aside>
					</div>

					<div class="noble-step2-mobile-bar fixed bottom-0 inset-x-0 w-full z-50 bg-surface py-4 pb-safe border-t border-primary/10 shadow-[0_-8px_24px_rgba(26,28,28,0.08)] rounded-t-3xl lg:hidden">
						<div class="noble-step2-mobile-bar-inner px-5 sm:px-6 lg:px-8">
							<div class="noble-step2-mobile-summary">
								<span class="noble-step2-mobile-summary-label">جمع نهایی</span>
								<span id="noble-step2-order-total-mobile" class="noble-step2-mobile-summary-value"><?php wc_cart_totals_order_total_html(); ?></span>
							</div>
							<div class="noble-step2-mobile-actions">
								<a href="<?php echo esc_url( $step_one_url ); ?>" class="noble-step2-back-btn noble-step2-back-btn-mobile px-4 py-3 rounded-2xl font-bold text-sm inline-flex items-center justify-center gap-1.5">
									<span class="material-symbols-outlined text-[18px]">chevron_right</span>
									<span>مرحله قبل</span>
								</a>
								<button type="submit" class="noble-step1-cta noble-step2-mobile-cta px-8 py-4 rounded-2xl font-bold text-base active:scale-95 transition-transform flex items-center justify-center gap-2 border-0">
									<span>تایید و ادامه خرید</span>
									<span class="material-symbols-outlined">chevron_left</span>
								</button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</main>
	<?php else : ?>
		<main class="w-full max-w-[1300px] mx-auto px-5 sm:px-6 lg:px-8 pt-20">
			<div class="max-w-[1300px] mx-auto">
				<section class="mb-8">
					<nav class="noble-checkout-progress px-1" aria-label="Checkout steps">
						<div class="noble-checkout-progress-item is-done">
							<div class="noble-checkout-progress-dot"><span class="material-symbols-outlined">check</span></div>
							<span class="noble-checkout-progress-label">اطلاعات</span>
						</div>
						<div class="noble-checkout-progress-line is-done"></div>
						<div class="noble-checkout-progress-item is-done">
							<div class="noble-checkout-progress-dot"><span class="material-symbols-outlined">check</span></div>
							<span class="noble-checkout-progress-label">ارسال</span>
						</div>
						<div class="noble-checkout-progress-line is-done"></div>
						<div class="noble-checkout-progress-item is-current">
							<div class="noble-checkout-progress-dot"><span class="material-symbols-outlined">payments</span></div>
							<span class="noble-checkout-progress-label">پرداخت</span>
						</div>
					</nav>
				</section>

				<div class="mb-5 px-1">
					<h2 class="text-xl font-extrabold text-on-surface">روش پرداخت</h2>
					<p class="text-sm text-on-surface-variant mt-1">روش پرداخت را انتخاب کن و سفارش را نهایی کن.</p>
				</div>

				<div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
					<div class="lg:col-span-7 min-w-0">
						<form name="checkout" method="post" class="checkout woocommerce-checkout noble-step3-checkout-form rounded-2xl border border-primary/10 bg-white p-5 md:p-6" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">
							<div id="order_review" class="woocommerce-checkout-review-order">
								<?php do_action( 'woocommerce_checkout_order_review' ); ?>
							</div>
						</form>
					</div>

					<aside class="lg:col-span-5 lg:sticky lg:top-24">
						<div class="rounded-2xl border border-primary/10 bg-white p-6 space-y-5 shadow-[0_12px_32px_rgba(26,28,28,0.06)]">
							<h3 class="text-lg font-extrabold text-on-surface">خلاصه سفارش</h3>
							<div class="space-y-3 pb-2">
								<?php foreach ( WC()->cart->get_cart() as $item ) : ?>
									<?php
									if ( empty( $item['data'] ) || ! is_object( $item['data'] ) ) {
										continue;
									}
									$summary_product = $item['data'];
									?>
									<div class="flex items-center gap-3">
										<div class="w-14 h-14 rounded-lg overflow-hidden bg-surface-container-high border border-primary/10 flex-shrink-0">
											<?php echo wp_kses_post( $summary_product->get_image( 'woocommerce_thumbnail', array( 'class' => 'w-full h-full object-cover' ) ) ); ?>
										</div>
										<div class="min-w-0 flex-1">
											<div class="text-sm font-bold text-on-surface line-clamp-1"><?php echo esc_html( $summary_product->get_name() ); ?></div>
											<div class="text-xs text-on-surface-variant mt-0.5">تعداد: <?php echo esc_html( number_format_i18n( (int) $item['quantity'] ) ); ?></div>
										</div>
										<div class="text-sm font-bold text-on-surface whitespace-nowrap"><?php echo wp_kses_post( WC()->cart->get_product_subtotal( $summary_product, (int) $item['quantity'] ) ); ?></div>
									</div>
								<?php endforeach; ?>
							</div>
							<div class="space-y-3 pt-4 border-t border-primary/10">
								<div class="flex justify-between text-sm">
									<span class="text-on-surface-variant">جمع جزء</span>
									<span class="font-medium text-on-surface"><?php wc_cart_totals_subtotal_html(); ?></span>
								</div>
								<div class="flex justify-between items-center pt-3 border-t border-primary/10">
									<span class="text-lg font-extrabold text-on-surface">جمع کل</span>
									<span class="text-2xl font-black text-primary"><?php wc_cart_totals_order_total_html(); ?></span>
								</div>
							</div>
							<button type="button" id="noble-place-order-trigger" data-noble-place-order-trigger="1" class="noble-step1-cta w-full py-4 rounded-2xl font-extrabold text-base inline-flex items-center justify-center border-0">
								تکمیل خرید و پرداخت
							</button>
							<a href="<?php echo esc_url( $step_two_url ); ?>" class="noble-step2-back-btn w-full py-3 rounded-2xl font-bold text-sm inline-flex items-center justify-center gap-2">
								<span class="material-symbols-outlined text-[18px]">chevron_right</span>
								<span>بازگشت به مرحله ارسال</span>
							</a>
						</div>
					</aside>
				</div>
				<div class="noble-step3-mobile-bar fixed bottom-0 inset-x-0 w-full z-50 bg-surface py-4 pb-safe border-t border-primary/10 shadow-[0_-8px_24px_rgba(26,28,28,0.08)] rounded-t-3xl lg:hidden">
					<div class="noble-step3-mobile-bar-inner px-5 sm:px-6">
						<a href="<?php echo esc_url( $step_two_url ); ?>" class="noble-step2-back-btn noble-step3-mobile-back inline-flex items-center justify-center gap-1.5">
							<span class="material-symbols-outlined text-[18px]">chevron_right</span>
							<span>مرحله قبل</span>
						</a>
						<button type="button" data-noble-place-order-trigger="1" class="noble-step1-cta noble-step3-mobile-cta inline-flex items-center justify-center gap-2 border-0">
							<span>تکمیل خرید و پرداخت</span>
							<span class="material-symbols-outlined">chevron_left</span>
						</button>
					</div>
				</div>
			</div>
		</main>
	<?php endif; ?>
</section>

<style>
	/* Force checkout frame width to match homepage container */
	.noble-checkout-wrap > header.fixed > .noble-checkout-header-inner {
		width: min(1300px, calc(100% - 40px)) !important;
		max-width: 1300px !important;
		margin-left: auto !important;
		margin-right: auto !important;
	}
	@media (min-width: 640px) {
		.noble-checkout-wrap > header.fixed > .noble-checkout-header-inner {
			width: min(1300px, calc(100% - 48px)) !important;
		}
	}
	@media (min-width: 1024px) {
		.noble-checkout-wrap > header.fixed > .noble-checkout-header-inner {
			width: min(1300px, calc(100% - 64px)) !important;
		}
	}
	.noble-checkout-wrap.noble-checkout-step-0 > main,
	.noble-checkout-wrap.noble-checkout-step-1 > main,
	.noble-checkout-wrap.noble-checkout-step-2 > main,
	.noble-checkout-wrap.noble-checkout-step-3 > main {
		width: min(1300px, calc(100% - 40px)) !important;
		max-width: 1300px !important;
		margin-left: auto !important;
		margin-right: auto !important;
	}
	@media (min-width: 640px) {
		.noble-checkout-wrap.noble-checkout-step-0 > main,
		.noble-checkout-wrap.noble-checkout-step-1 > main,
		.noble-checkout-wrap.noble-checkout-step-2 > main,
		.noble-checkout-wrap.noble-checkout-step-3 > main {
			width: min(1300px, calc(100% - 48px)) !important;
		}
	}
	@media (min-width: 1024px) {
		.noble-checkout-wrap.noble-checkout-step-0 > main,
		.noble-checkout-wrap.noble-checkout-step-1 > main,
		.noble-checkout-wrap.noble-checkout-step-2 > main,
		.noble-checkout-wrap.noble-checkout-step-3 > main {
			width: min(1300px, calc(100% - 64px)) !important;
		}
	}
	.noble-checkout-wrap .woocommerce-message,
	.noble-checkout-wrap .woocommerce-error,
	.noble-checkout-wrap .woocommerce-info {
		max-width: 1300px;
		margin: 0 auto 12px;
		border: 1px solid rgba(123, 33, 50, 0.14);
		border-radius: 14px;
		background: #fff;
		padding: 12px 14px 12px 44px;
		position: relative;
		color: #4b5563;
		font-size: 13px;
		line-height: 1.9;
		box-shadow: 0 8px 24px rgba(26, 28, 28, 0.05);
	}
	.noble-checkout-wrap .woocommerce-error {
		border-color: rgba(185, 28, 28, 0.2);
		background: #fff5f5;
		color: #7f1d1d;
	}
	.noble-checkout-wrap .woocommerce-error li,
	.noble-checkout-wrap .woocommerce-error > li {
		color: inherit;
		font-weight: 600;
	}
	.noble-checkout-wrap .noble-step1-inline-error {
		display: block;
		border: 1px solid rgba(185, 28, 28, 0.24);
		background: #fff5f5;
		color: #7f1d1d;
		border-radius: 14px;
		padding: 12px 14px;
		font-size: 13px;
		font-weight: 700;
		line-height: 1.9;
	}
	.noble-checkout-wrap .noble-step1-inline-error.hidden {
		display: none;
	}
	.noble-checkout-wrap .woocommerce-message::before,
	.noble-checkout-wrap .woocommerce-error::before,
	.noble-checkout-wrap .woocommerce-info::before {
		top: 12px;
		left: 14px;
		right: auto;
		margin: 0;
		font-size: 18px;
	}
	.noble-checkout-wrap .shop_table,
	.noble-checkout-wrap .woocommerce-terms-and-conditions-wrapper,
	.noble-checkout-wrap .woocommerce-checkout-payment .payment_box,
	.noble-checkout-wrap .woocommerce-privacy-policy-text,
	.noble-checkout-wrap.noble-checkout-step-0 .woocommerce-checkout-payment .wc_payment_methods,
	.noble-checkout-wrap.noble-checkout-step-1 .woocommerce-checkout-payment .wc_payment_methods {
		display: none !important;
	}
	.noble-checkout-wrap .primary-gradient {
		background: #051061;
	}
	.noble-checkout-wrap .noble-step1-cta {
		background: #051061;
		color: #ffffff !important;
		box-shadow: none;
		cursor: pointer;
	}
	.noble-checkout-wrap .noble-step1-cta .material-symbols-outlined {
		color: #ffffff !important;
	}
	.noble-checkout-wrap .noble-step2-back-btn {
		background: #ffffff;
		color: #051061;
		border: 1px solid rgba(5, 16, 97, 0.2);
		text-decoration: none;
	}
	.noble-checkout-wrap .noble-step2-back-btn:hover {
		background: rgba(5, 16, 97, 0.05);
	}
	.noble-checkout-wrap button,
	.noble-checkout-wrap [type="button"],
	.noble-checkout-wrap [type="submit"],
	.noble-checkout-wrap .button {
		cursor: pointer;
	}
	.noble-checkout-wrap.noble-checkout-step-3 #order_review_heading {
		display: none !important;
	}
	.noble-checkout-wrap.noble-checkout-step-3 .woocommerce-checkout-review-order-table {
		display: none !important;
	}
	.noble-checkout-wrap.noble-checkout-step-3 .noble-step3-checkout-form .wc_payment_methods {
		border: 0 !important;
		margin: 0 !important;
		padding: 0 !important;
		display: grid;
		gap: 12px;
		background: transparent !important;
	}
	.noble-checkout-wrap.noble-checkout-step-3 .noble-step3-checkout-form .woocommerce-checkout-payment,
	.noble-checkout-wrap.noble-checkout-step-3 .noble-step3-checkout-form #payment,
	.noble-checkout-wrap.noble-checkout-step-3 .noble-step3-checkout-form #payment .payment_methods,
	.noble-checkout-wrap.noble-checkout-step-3 .noble-step3-checkout-form #payment ul.payment_methods,
	.noble-checkout-wrap.noble-checkout-step-3 .noble-step3-checkout-form #payment ul.payment_methods > li {
		background: transparent !important;
	}
	.noble-checkout-wrap.noble-checkout-step-3 .noble-step3-checkout-form #payment div.payment_box,
	.noble-checkout-wrap.noble-checkout-step-3 .noble-step3-checkout-form #payment .payment_method_bacs .payment_box,
	.noble-checkout-wrap.noble-checkout-step-3 .noble-step3-checkout-form #payment .payment_method_cod .payment_box,
	.noble-checkout-wrap.noble-checkout-step-3 .noble-step3-checkout-form #payment .payment_method_cheque .payment_box,
	.noble-checkout-wrap.noble-checkout-step-3 .noble-step3-checkout-form #payment .payment_method_bacs > div,
	.noble-checkout-wrap.noble-checkout-step-3 .noble-step3-checkout-form #payment .payment_method_cod > div,
	.noble-checkout-wrap.noble-checkout-step-3 .noble-step3-checkout-form #payment .payment_method_cheque > div {
		background: #ffffff !important;
	}
	.noble-checkout-wrap.noble-checkout-step-3 .noble-step3-checkout-form .wc_payment_method {
		background: #ffffff !important;
		border: 1px solid rgba(5,16,97,0.16);
		border-radius: 14px;
		padding: 14px 16px;
	}
	.noble-checkout-wrap.noble-checkout-step-3 .noble-step3-checkout-form .wc_payment_method.payment_method_bacs,
	.noble-checkout-wrap.noble-checkout-step-3 .noble-step3-checkout-form .wc_payment_method.payment_method_cod,
	.noble-checkout-wrap.noble-checkout-step-3 .noble-step3-checkout-form .wc_payment_method.payment_method_cheque {
		background: #ffffff !important;
	}
	.noble-checkout-wrap.noble-checkout-step-3 .noble-step3-checkout-form .wc_payment_method > div,
	.noble-checkout-wrap.noble-checkout-step-3 .noble-step3-checkout-form .wc_payment_method > p {
		background: transparent !important;
	}
	.noble-checkout-wrap.noble-checkout-step-3 .noble-step3-checkout-form .wc_payment_method > label {
		font-weight: 700;
		color: #151c26;
	}
	.noble-checkout-wrap.noble-checkout-step-3 .noble-step3-checkout-form .payment_box {
		display: block !important;
		background: #ffffff !important;
		border: 1px solid rgba(5,16,97,0.1) !important;
		border-radius: 10px;
		margin-top: 10px !important;
		color: #4b5563 !important;
	}
	.noble-checkout-wrap.noble-checkout-step-3 .noble-step3-checkout-form .payment_box::before {
		border-bottom-color: #ffffff !important;
	}
	.noble-checkout-wrap.noble-checkout-step-3 .noble-step3-checkout-form #place_order {
		display: none !important;
	}
	.noble-checkout-wrap.noble-checkout-step-3 .noble-step3-checkout-form .place-order {
		padding: 0 !important;
		margin: 0 !important;
	}
	.noble-checkout-wrap.noble-checkout-step-3 .noble-step3-checkout-form .woocommerce-terms-and-conditions-wrapper {
		display: block !important;
		margin-top: 12px;
	}
	.noble-checkout-wrap.noble-checkout-step-3 {
		padding-bottom: 150px;
	}
	@media (min-width: 1024px) {
		.noble-checkout-wrap.noble-checkout-step-3 {
			padding-bottom: 2rem;
		}
	}
	.noble-checkout-wrap.noble-checkout-step-3 .noble-step3-mobile-bar-inner {
		width: min(1300px, 100%);
		margin-left: auto;
		margin-right: auto;
		display: grid;
		grid-template-columns: minmax(0, 1fr) 1.65fr;
		gap: 8px;
	}
	.noble-checkout-wrap.noble-checkout-step-3 .noble-step3-mobile-back,
	.noble-checkout-wrap.noble-checkout-step-3 .noble-step3-mobile-cta {
		height: 52px;
		border-radius: 14px;
		font-size: 13px;
		font-weight: 800;
		padding: 0 12px;
		white-space: nowrap;
	}
	.noble-checkout-wrap.noble-checkout-step-3 .noble-step3-mobile-cta {
		box-shadow: 0 8px 18px rgba(5,16,97,0.2);
	}
	.noble-checkout-wrap .noble-step1-aside-card {
		backdrop-filter: saturate(120%);
	}
	.noble-checkout-wrap .noble-checkout-item-card { box-shadow: 0 10px 24px rgba(26, 28, 28, 0.05); }
	.noble-checkout-wrap .noble-step0-items {
		display: flex;
		flex-direction: column;
		gap: 14px;
	}
	@media (min-width: 1024px) {
		.noble-checkout-wrap.noble-checkout-step-0 .noble-step0-layout {
			display: grid !important;
			grid-template-columns: minmax(0, 1fr) 360px !important;
			gap: 2.5rem !important;
			align-items: start;
		}
		.noble-checkout-wrap.noble-checkout-step-0 .noble-step0-aside {
			width: 360px !important;
			min-width: 360px !important;
			max-width: 360px !important;
			justify-self: end;
		}
		.noble-checkout-wrap .noble-step0-items {
			gap: 18px;
		}
	}
	.noble-checkout-wrap .noble-checkout-remove { position: absolute; left: 14px; top: 14px; }
	.noble-checkout-wrap .noble-checkout-qty-control { position: absolute; left: 14px; top: 52px; }
	.noble-checkout-wrap .noble-checkout-item-card .wc-item-meta { display: flex; flex-wrap: wrap; gap: 6px 12px; margin: 0; }
	.noble-checkout-wrap .noble-checkout-item-card .wc-item-meta dt,
	.noble-checkout-wrap .noble-checkout-item-card .wc-item-meta dd { margin: 0; font-size: 12px; color: #6b7280; }
	.noble-checkout-wrap .noble-checkout-item-card .wc-item-meta dd p { margin: 0; }
	.noble-checkout-wrap .noble-step1-input {
		transition: all 0.2s ease;
		font-family: inherit;
		direction: rtl;
		text-align: right;
	}
	.noble-checkout-wrap .noble-step1-input::placeholder { text-align: right; }
	.noble-checkout-wrap .noble-step1-address-search {
		padding-right: 3.2rem !important;
	}
	.noble-checkout-wrap .noble-step1-ltr {
		direction: ltr !important;
		unicode-bidi: plaintext;
		/* Keep UI aligned to the right in RTL layouts */
		text-align: right !important;
	}
	.noble-checkout-wrap .noble-step1-input:focus {
		outline: none;
		box-shadow: 0 0 0 2px rgba(5, 16, 97, 0.2);
	}
	.noble-checkout-wrap .noble-step1-product-card {
		backdrop-filter: saturate(120%);
	}
	.noble-checkout-wrap .noble-step1-product-row {
		transition: border-color 0.2s ease, background-color 0.2s ease;
		min-height: 84px;
		padding-inline: 18px;
	}
	.noble-checkout-wrap .noble-step1-product-row:hover {
		border-color: rgba(5, 16, 97, 0.22);
		background-color: #fff;
	}
	.noble-checkout-wrap .noble-step1-product-row .woocommerce-Price-amount {
		font-weight: 600;
		padding-inline-start: 8px;
	}
	.noble-checkout-wrap .noble-step1-qty-control {
		height: 32px;
	}
	.noble-checkout-wrap .noble-step1-qty-btn {
		width: 30px;
		height: 30px;
		display: inline-flex;
		align-items: center;
		justify-content: center;
		color: #051061;
		border-radius: 8px;
	}
	.noble-checkout-wrap .noble-step1-qty-btn:hover {
		background: rgba(5,16,97,.08);
	}
	.noble-checkout-wrap .noble-step1-qty-value {
		min-width: 28px;
		text-align: center;
		font-size: 12px;
		font-weight: 800;
		color: #051061;
	}
	.noble-checkout-wrap .noble-step1-remove-btn {
		width: 34px;
		height: 34px;
		display: inline-flex;
		align-items: center;
		justify-content: center;
		border-radius: 10px;
		color: #b91c1c;
		background: rgba(185,28,28,.08);
		flex-shrink: 0;
		transition: background-color .2s ease, color .2s ease;
	}
	.noble-checkout-wrap .noble-step1-remove-btn:hover {
		color: #fff;
		background: #b91c1c;
	}
	.noble-checkout-wrap .noble-step1-coupon-mobile {
		background: linear-gradient(135deg, rgba(5,16,97,0.08), rgba(5,16,97,0.03));
		border: 1px solid rgba(5, 16, 97, 0.16);
		border-radius: 16px;
		padding: 10px;
	}
	.noble-checkout-wrap .noble-step1-coupon-mobile-input-wrap {
		position: relative;
	}
	.noble-checkout-wrap .noble-step1-coupon-mobile-icon {
		position: absolute;
		right: 14px;
		top: 50%;
		transform: translateY(-50%);
		font-size: 19px;
		color: rgba(5, 16, 97, 0.7);
		pointer-events: none;
	}
	.noble-checkout-wrap .noble-step1-coupon-mobile-input {
		letter-spacing: 0.2px;
	}
	.noble-checkout-wrap .noble-step1-coupon-mobile-input::placeholder {
		color: rgba(21, 28, 38, 0.62);
	}
	.noble-checkout-wrap .noble-step1-coupon-mobile-btn {
		background: #051061;
		color: #fff;
	}
	.noble-checkout-wrap .noble-step1-coupon-mobile-btn:hover {
		background: #0b197f;
	}
	.noble-checkout-wrap .noble-checkout-progress {
		display: flex;
		align-items: flex-start;
		justify-content: space-between;
		gap: 8px;
	}
	.noble-checkout-wrap .noble-checkout-progress-item {
		display: flex;
		flex-direction: column;
		align-items: center;
		gap: 8px;
		min-width: 64px;
		opacity: 0.5;
	}
	.noble-checkout-wrap .noble-checkout-progress-dot {
		width: 40px;
		height: 40px;
		border-radius: 9999px;
		background: var(--color-surface-container-highest);
		color: var(--color-on-surface-variant);
		display: inline-flex;
		align-items: center;
		justify-content: center;
		font-weight: 800;
		border: 1px solid rgba(5, 16, 97, 0.15);
	}
	.noble-checkout-wrap .noble-checkout-progress-dot .material-symbols-outlined {
		font-size: 20px;
		line-height: 1;
	}
	.noble-checkout-wrap .noble-checkout-progress-label {
		font-size: 12px;
		font-weight: 700;
		color: var(--color-on-surface-variant);
		white-space: nowrap;
	}
	.noble-checkout-wrap .noble-checkout-progress-line {
		flex: 1;
		height: 2px;
		margin-top: 19px;
		background: rgba(5, 16, 97, 0.14);
		border-radius: 9999px;
	}
	.noble-checkout-wrap .noble-checkout-progress-item.is-current,
	.noble-checkout-wrap .noble-checkout-progress-item.is-done {
		opacity: 1;
	}
	.noble-checkout-wrap .noble-checkout-progress-item.is-current .noble-checkout-progress-dot {
		background: #051061;
		color: #ffffff;
		box-shadow: 0 0 0 4px rgba(5, 16, 97, 0.14);
		border-color: #051061;
	}
	.noble-checkout-wrap .noble-checkout-progress-item.is-current .noble-checkout-progress-label {
		color: #051061;
	}
	.noble-checkout-wrap .noble-checkout-progress-item.is-done .noble-checkout-progress-dot {
		background: rgba(5, 16, 97, 0.12);
		color: #051061;
		border-color: rgba(5, 16, 97, 0.25);
	}
	.noble-checkout-wrap .noble-checkout-progress-item.is-done .noble-checkout-progress-label {
		color: var(--color-on-surface);
	}
	.noble-checkout-wrap .noble-checkout-progress-line.is-done {
		background: rgba(5, 16, 97, 0.4);
	}
	.noble-checkout-wrap.noble-checkout-step-2 .noble-step2-inner {
		max-width: 1300px;
	}
	.noble-checkout-wrap.noble-checkout-step-2 .noble-step2-layout {
		display: block;
	}
	.noble-checkout-wrap.noble-checkout-step-2 .noble-step2-option-card {
		background: #fff;
		border-color: rgba(5, 16, 97, 0.14);
		transition: border-color 0.2s ease, box-shadow 0.2s ease, background-color 0.2s ease;
	}
	.noble-checkout-wrap.noble-checkout-step-2 .noble-step2-option:hover .noble-step2-option-card {
		border-color: rgba(5, 16, 97, 0.28);
		background: #fff;
	}
	.noble-checkout-wrap.noble-checkout-step-2 .noble-step2-option .peer:checked + .noble-step2-option-card {
		border-color: #051061 !important;
		box-shadow: 0 10px 26px rgba(5, 16, 97, 0.12);
	}
	.noble-checkout-wrap.noble-checkout-step-2 .noble-step2-mobile-bar {
		background: #ffffff;
		backdrop-filter: none !important;
		-webkit-backdrop-filter: none !important;
	}
	.noble-checkout-wrap.noble-checkout-step-2 .noble-step2-mobile-bar-inner {
		width: min(1300px, 100%);
		margin-left: auto;
		margin-right: auto;
		display: flex;
		flex-direction: column;
		gap: 10px;
	}
	.noble-checkout-wrap.noble-checkout-step-2 .noble-step2-mobile-summary {
		display: flex;
		align-items: center;
		justify-content: space-between;
		background: rgba(5,16,97,0.04);
		border: 1px solid rgba(5,16,97,0.1);
		border-radius: 14px;
		padding: 10px 12px;
	}
	.noble-checkout-wrap.noble-checkout-step-2 .noble-step2-mobile-summary-label {
		font-size: 11px;
		font-weight: 700;
		color: #6b7280;
	}
	.noble-checkout-wrap.noble-checkout-step-2 .noble-step2-mobile-summary-value {
		font-size: 17px;
		font-weight: 900;
		color: #111827;
	}
	.noble-checkout-wrap.noble-checkout-step-2 .noble-step2-mobile-actions {
		display: grid;
		grid-template-columns: minmax(0, 1fr) 1.65fr;
		gap: 8px;
		align-items: stretch;
	}
	.noble-checkout-wrap.noble-checkout-step-2 .noble-step2-mobile-cta,
	.noble-checkout-wrap.noble-checkout-step-2 .noble-step2-back-btn-mobile {
		width: 100%;
		height: 52px;
		padding: 0 12px;
		border-radius: 14px;
		font-size: 13px;
		font-weight: 800;
		white-space: nowrap;
	}
	.noble-checkout-wrap.noble-checkout-step-2 .noble-step2-mobile-cta {
		box-shadow: 0 8px 18px rgba(5,16,97,0.2);
	}
	.noble-checkout-wrap.noble-checkout-step-2 .noble-step2-back-btn-mobile {
		background: #fff;
	}
	.noble-checkout-wrap.noble-checkout-step-2 .noble-step2-mobile-actions .material-symbols-outlined {
		font-size: 18px;
	}
	.noble-checkout-wrap.noble-checkout-step-1 header.fixed {
		box-shadow: none;
	}
	.noble-checkout-wrap.noble-checkout-step-1 {
		padding-bottom: 180px;
	}
	@media (min-width: 768px) {
		.noble-checkout-wrap.noble-checkout-step-1 {
			padding-bottom: 2rem;
		}
		.noble-checkout-wrap.noble-checkout-step-1 .noble-step1-inner {
			display: grid !important;
			grid-template-columns: minmax(0, 1fr) 420px;
			gap: 2.5rem;
			max-width: 1300px;
			margin-left: auto;
			margin-right: auto;
			align-items: start;
		}
		.noble-checkout-wrap.noble-checkout-step-1 .noble-step1-main {
			min-width: 0;
			max-width: none;
		}
		.noble-checkout-wrap.noble-checkout-step-1 .noble-step1-aside {
			display: block !important;
		}
		.noble-checkout-wrap.noble-checkout-step-1 .noble-step1-aside-card {
			padding: 22px;
		}
		.noble-checkout-wrap.noble-checkout-step-1 .noble-step1-mobile-footer {
			display: none !important;
		}
		.noble-checkout-wrap.noble-checkout-step-1 .noble-step1-main section.space-y-6,
		.noble-checkout-wrap.noble-checkout-step-1 .noble-step1-main article.noble-step1-product-card {
			max-width: none;
		}
		.noble-checkout-wrap.noble-checkout-step-2 .noble-step2-layout {
			display: grid;
			grid-template-columns: minmax(0, 1fr) 420px;
			gap: 2.5rem;
			align-items: start;
		}
	}
</style>
<script>
document.addEventListener('click', function(e) {
	var btn = e.target.closest('[data-noble-place-order-trigger="1"], #noble-place-order-trigger');
	if (!btn) return;
	var placeOrder = document.querySelector('#place_order');
	if (placeOrder) placeOrder.click();
});
document.addEventListener('input', function(e) {
	var input = e.target;
	if (!input || input.name !== 'billing_phone') return;
	input.value = (input.value || '').replace(/\D+/g, '').slice(0, 11);
});
document.addEventListener('DOMContentLoaded', function() {
	var step1Form = document.getElementById('noble-step1-checkout-form');
	var inlineError = document.getElementById('noble-step1-inline-error');
	if (step1Form && inlineError) {
		step1Form.addEventListener('submit', function(e) {
			var firstName = step1Form.querySelector('[name="billing_first_name"]');
			var lastName = step1Form.querySelector('[name="billing_last_name"]');
			var phone = step1Form.querySelector('[name="billing_phone"]');
			var state = step1Form.querySelector('[name="billing_state"]');
			var city = step1Form.querySelector('[name="billing_city"]');
			var address1 = step1Form.querySelector('[name="billing_address_1"]');
			var postcode = step1Form.querySelector('[name="billing_postcode"]');

			var firstNameVal = firstName ? (firstName.value || '').trim() : '';
			var lastNameVal = lastName ? (lastName.value || '').trim() : '';
			var phoneVal = phone ? (phone.value || '').replace(/\D+/g, '') : '';
			var stateVal = state ? (state.value || '').trim() : '';
			var cityVal = city ? (city.value || '').trim() : '';
			var address1Val = address1 ? (address1.value || '').trim() : '';
			var postcodeVal = postcode ? (postcode.value || '').replace(/\D+/g, '') : '';

			var msg = '';
			if (!firstNameVal) msg = 'لطفا نام را وارد کنید.';
			else if (!lastNameVal) msg = 'لطفا نام خانوادگی را وارد کنید.';
			else if (!phoneVal) msg = 'لطفا شماره موبایل را وارد کنید.';
			else if (!/^09\d{9}$/.test(phoneVal)) msg = 'فرمت شماره موبایل صحیح نیست. مثال: 09123456789';
			else if (!stateVal) msg = 'لطفا استان را انتخاب کنید.';
			else if (!cityVal) msg = 'لطفا شهر را وارد کنید.';
			else if (!address1Val) msg = 'لطفا آدرس ارسال را وارد کنید.';
			else if (!postcodeVal) msg = 'لطفا کد پستی را وارد کنید.';
			else if (postcodeVal.length !== 10) msg = 'کد پستی باید 10 رقم باشد.';

			if (msg) {
				e.preventDefault();
				inlineError.textContent = msg;
				inlineError.classList.remove('hidden');
				inlineError.scrollIntoView({ behavior: 'smooth', block: 'center' });
			} else {
				inlineError.textContent = '';
				inlineError.classList.add('hidden');
			}
		});

		step1Form.addEventListener('input', function() {
			if (!inlineError.classList.contains('hidden')) {
				inlineError.textContent = '';
				inlineError.classList.add('hidden');
			}
		});
	}
});
document.addEventListener('DOMContentLoaded', function() {
	var shippingInputs = document.querySelectorAll('.noble-step2-form input[name^="noble_chosen_shipping"]');
	if (!shippingInputs.length) return;

	var shippingValueEl = document.getElementById('noble-step2-shipping-value');
	var shippingLabelEl = document.getElementById('noble-step2-shipping-label');
	var totalDesktopEl = document.getElementById('noble-step2-order-total-desktop');
	var totalMobileEl = document.getElementById('noble-step2-order-total-mobile');
	var baseTotalHolder = document.querySelector('.noble-step2-aside section[data-step2-base-total]');
	var baseTotal = baseTotalHolder ? parseFloat(baseTotalHolder.getAttribute('data-step2-base-total') || '0') : 0;
	if (!isFinite(baseTotal)) baseTotal = 0;

	function formatToman(amount) {
		var val = Math.max(0, Math.round(Number(amount) || 0));
		try {
			return new Intl.NumberFormat('fa-IR').format(val) + ' تومان';
		} catch (err) {
			return String(val) + ' تومان';
		}
	}

	function refreshSummaryByInput(input) {
		if (!input) return;
		var shippingCost = parseFloat(input.getAttribute('data-shipping-cost') || '0');
		var shippingLabel = input.getAttribute('data-shipping-label') || '';
		if (!isFinite(shippingCost)) shippingCost = 0;
		var nextTotal = baseTotal + shippingCost;
		var shippingText = shippingCost > 0 ? formatToman(shippingCost) : 'رایگان';
		var totalText = formatToman(nextTotal);

		if (shippingValueEl) shippingValueEl.textContent = shippingText;
		if (shippingLabelEl) shippingLabelEl.textContent = shippingLabel;
		if (totalDesktopEl) totalDesktopEl.textContent = totalText;
		if (totalMobileEl) totalMobileEl.textContent = totalText;
	}

	shippingInputs.forEach(function(input) {
		input.addEventListener('change', function() {
			refreshSummaryByInput(input);
		});
	});

	var checked = document.querySelector('.noble-step2-form input[name^="noble_chosen_shipping"]:checked');
	if (checked) refreshSummaryByInput(checked);
});
</script>
<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>
