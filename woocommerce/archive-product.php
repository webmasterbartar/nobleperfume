<?php
/**
 * Product archive override.
 *
 * @package noble-theme
 */

defined( 'ABSPATH' ) || exit;

get_header( 'shop' );
?>
<section class="shop-page-wrap min-h-screen bg-background pb-16 pt-0">
	<main class="pt-0">
		<section class="border-b border-outline-variant/10 bg-white/40 py-4">
			<div class="container mx-auto px-5 sm:px-6 lg:px-8">
				<div class="no-scrollbar flex items-center gap-3 overflow-x-auto">
					<a class="shop-tab-active" href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>">همه محصولات</a>
					<?php
					$top_categories = get_terms(
						array(
							'taxonomy'   => 'product_cat',
							'hide_empty' => true,
							'number'     => 6,
						)
					);
					if ( ! is_wp_error( $top_categories ) && ! empty( $top_categories ) ) :
						foreach ( $top_categories as $top_category ) :
							?>
							<a class="shop-tab" href="<?php echo esc_url( get_term_link( $top_category ) ); ?>"><?php echo esc_html( $top_category->name ); ?></a>
							<?php
						endforeach;
					endif;
					?>
				</div>
			</div>
		</section>

		<div class="container mx-auto px-5 py-8 sm:px-6 lg:px-8">
			<div class="shop-layout flex flex-col gap-10 md:flex-row">
				<aside class="shop-sidebar hidden w-full shrink-0 md:block md:w-80 md:self-start md:sticky md:top-24">
					<style>
					.shop-filter-card.compact { padding: 16px; border-radius: 16px; }
					.shop-filter-card.compact .filter-check-row {
						display: flex;
						align-items: center;
						gap: 8px;
						padding: 8px 10px;
						border: 1px solid rgba(5,16,97,.08);
						border-radius: 8px;
						font-size: 12px;
						color: #334155;
						background: #fff;
						justify-content: space-between;
					}
					.shop-filter-card.compact .filter-check-row input[type="checkbox"] { width: 14px; height: 14px; accent-color: #051061; }
					.shop-filter-card.compact .filter-section-title {
						font-size: 12px; font-weight: 800; color: #051061;
						display: inline-flex; align-items: center; gap: 6px;
					}
					.shop-filter-card.compact .filter-section-toggle {
						display: flex;
						align-items: center;
						justify-content: space-between;
						cursor: pointer;
						list-style: none;
					}
					.shop-filter-card.compact .filter-section-toggle::-webkit-details-marker { display: none; }
					.shop-filter-card.compact .filter-section-toggle .toggle-icon {
						font-size: 16px;
						color: #64748b;
						transition: transform .2s ease;
					}
					.shop-filter-card.compact details[open] > .filter-section-toggle .toggle-icon {
						transform: rotate(180deg);
					}
					.shop-filter-card.compact .filter-section-block {
						border-top: 1px dashed rgba(5,16,97,.12);
						padding-top: 12px;
						margin-top: 12px;
					}
					.shop-filter-card.compact .filter-group-scroll {
						max-height: 168px; /* roughly 4.5 rows */
						overflow-y: auto;
						padding-left: 1px;
					}
					.shop-filter-card.compact .filter-section-block:first-child {
						border-top: 0;
						padding-top: 0;
						margin-top: 0;
					}
					.shop-filter-card.compact .filter-submit-btn {
						width: 100%;
						height: 36px;
						border-radius: 10px;
						background: #051061;
						color: #fff;
						font-size: 12px;
						font-weight: 800;
						border: 0;
					}
					/* Hide scrollbars while keeping scroll behavior */
					.shop-sidebar,
					.shop-sidebar * {
						scrollbar-width: none;
						-ms-overflow-style: none;
					}
					.shop-sidebar::-webkit-scrollbar,
					.shop-sidebar *::-webkit-scrollbar {
						width: 0;
						height: 0;
						display: none;
					}
					</style>
					<div class="space-y-4">
						<div class="shop-filter-card compact rounded-2xl border border-primary/10 bg-white p-6">
							<div class="mb-4 flex items-center justify-between">
								<div>
									<h3 class="text-base font-bold text-primary">فیلترها</h3>
									<p class="mt-1 text-[10px] uppercase tracking-[0.16em] text-on-surface-variant/70">Refine your choice</p>
								</div>
								<a class="text-[10px] font-bold text-primary/70 hover:text-primary" href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>">پاک کردن</a>
							</div>

							<form id="shop-filter-form" class="space-y-6" method="get" action="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>">
								<?php
								foreach ( $_GET as $k => $v ) {
									$k = (string) $k;
									if ( 0 === strpos( $k, 'filter_pa_' ) || 0 === strpos( $k, 'query_type_pa_' ) || 'paged' === $k ) {
										continue;
									}
									if ( is_array( $v ) ) {
										foreach ( $v as $vv ) {
											echo '<input type="hidden" name="' . esc_attr( $k ) . '[]" value="' . esc_attr( wp_unslash( (string) $vv ) ) . '">';
										}
									} else {
										echo '<input type="hidden" name="' . esc_attr( $k ) . '" value="' . esc_attr( wp_unslash( (string) $v ) ) . '">';
									}
								}
								?>
								<details class="shop-filter-accordion" open>
									<summary class="shop-filter-summary">
										<span>برندهای برتر</span>
										<span class="material-symbols-outlined text-sm">expand_more</span>
									</summary>
									<div class="shop-filter-panel grid grid-cols-2 gap-2 pt-3">
										<?php
										$brand_terms = get_terms(
											array(
												'taxonomy'   => 'pa_brand',
												'hide_empty' => true,
												'number'     => 4,
											)
										);
										if ( ! is_wp_error( $brand_terms ) && ! empty( $brand_terms ) ) :
											foreach ( $brand_terms as $brand_term ) :
												$short_brand = strtoupper( mb_substr( $brand_term->name, 0, 4 ) );
												?>
												<a class="shop-brand-pill" href="<?php echo esc_url( add_query_arg( 'filter_pa_brand', $brand_term->slug ) ); ?>">
													<span class="shop-brand-icon"><?php echo esc_html( $short_brand ); ?></span>
													<span class="truncate text-[11px]"><?php echo esc_html( $brand_term->name ); ?></span>
												</a>
												<?php
											endforeach;
										else :
											?>
											<p class="col-span-full text-xs text-on-surface-variant">فعلاً برندی برای فیلتر موجود نیست.</p>
											<?php
										endif;
										?>
									</div>
								</details>

								<details class="shop-filter-accordion" open>
									<summary class="shop-filter-summary">
										<span>ویژگی‌های محصول</span>
										<span class="material-symbols-outlined text-sm">expand_more</span>
									</summary>
									<div class="shop-filter-panel pt-3">
										<?php
										$normalize_filter_value = function( $value ) {
											$value = trim( (string) $value );
											$prev  = null;
											while ( $value !== $prev && false !== strpos( $value, '%' ) ) {
												$prev  = $value;
												$value = rawurldecode( $value );
											}
											return trim( $value );
										};
										$attr_filters = array(
											'pa_gender'      => array( 'label' => 'جنسیت', 'icon' => 'wc' ),
											'pa_longevity'   => array( 'label' => 'ماندگاری', 'icon' => 'schedule' ),
											'pa_occasion'    => array( 'label' => 'مناسبت', 'icon' => 'celebration' ),
											'pa_scent-family'=> array( 'label' => 'خانواده رایحه', 'icon' => 'local_florist' ),
											'pa_season'      => array( 'label' => 'فصل', 'icon' => 'wb_sunny' ),
										);

										foreach ( $attr_filters as $tax => $meta ) :
											if ( ! taxonomy_exists( $tax ) ) {
												continue;
											}
											$terms = get_terms(
												array(
													'taxonomy'   => $tax,
													'hide_empty' => true,
													'number'     => 10,
													'orderby'    => 'name',
													'order'      => 'ASC',
												)
											);
											$selected_values = array();
											if ( isset( $_GET[ 'filter_' . $tax ] ) ) {
												$current_raw = wp_unslash( $_GET[ 'filter_' . $tax ] );
												if ( is_array( $current_raw ) ) {
													foreach ( $current_raw as $cv ) {
														$selected_values[] = $normalize_filter_value( $cv );
													}
												} else {
													$selected_values = array_map( $normalize_filter_value, explode( ',', (string) $current_raw ) );
												}
											}
											$selected_values = array_values( array_unique( array_filter( $selected_values ) ) );
											?>
											<details class="filter-section-block" open>
												<summary class="filter-section-toggle mb-3">
													<h4 class="filter-section-title">
														<span class="material-symbols-outlined text-[18px] text-on-surface-variant"><?php echo esc_html( $meta['icon'] ); ?></span>
														<?php echo esc_html( $meta['label'] ); ?>
													</h4>
													<span class="material-symbols-outlined toggle-icon">expand_more</span>
												</summary>
												<?php if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) : ?>
													<div class="filter-group-scroll grid grid-cols-1 gap-2">
														<?php foreach ( $terms as $term ) : ?>
															<?php
															$term_slug = $normalize_filter_value( $term->slug );
															$is_checked = in_array( $term_slug, $selected_values, true );
															?>
															<label class="filter-check-row">
																<input type="checkbox" name="<?php echo esc_attr( 'filter_' . $tax ); ?>[]" value="<?php echo esc_attr( $term_slug ); ?>" <?php checked( $is_checked ); ?> />
																<span class="truncate"><?php echo esc_html( $term->name ); ?></span>
															</label>
														<?php endforeach; ?>
													</div>
													<input type="hidden" name="<?php echo esc_attr( 'query_type_' . $tax ); ?>" value="or" />
												<?php else : ?>
													<p class="text-xs text-on-surface-variant">فعلاً گزینه‌ای برای این فیلتر موجود نیست.</p>
												<?php endif; ?>
											</details>
										<?php endforeach; ?>
									</div>
								</details>

								<div>
									<div class="mb-2 flex items-center justify-between">
										<h4 class="text-sm font-bold text-primary">محدوده قیمت</h4>
										<span class="text-[10px] font-bold text-tertiary">تومان</span>
									</div>
									<?php
									global $wpdb;
									$max_price_db = (int) $wpdb->get_var( "SELECT MAX(max_price) FROM {$wpdb->wc_product_meta_lookup}" );
									$price_min_bound = 0;
									$price_max_bound = $max_price_db > 0 ? $max_price_db : 50000000;
									$selected_min_price = isset( $_GET['min_price'] ) ? max( 0, (int) wp_unslash( (string) $_GET['min_price'] ) ) : $price_min_bound;
									$selected_max_price = isset( $_GET['max_price'] ) && '' !== (string) $_GET['max_price'] ? (int) wp_unslash( (string) $_GET['max_price'] ) : $price_max_bound;
									if ( $selected_max_price < $selected_min_price ) {
										$selected_max_price = $selected_min_price;
									}
									?>
									<input type="hidden" name="min_price" id="shop-min-price" value="<?php echo esc_attr( $price_min_bound ); ?>" />
									<input type="hidden" name="max_price" id="shop-max-price" value="<?php echo esc_attr( $selected_max_price ); ?>" />
									<div class="space-y-2">
										<input type="range" id="shop-price-max-range" min="<?php echo esc_attr( $price_min_bound ); ?>" max="<?php echo esc_attr( $price_max_bound ); ?>" value="<?php echo esc_attr( $selected_max_price ); ?>" step="100000" class="shop-range w-full" />
										<div class="flex items-center justify-between text-[11px] font-bold text-on-surface-variant">
											<span><?php echo esc_html( number_format_i18n( $price_min_bound ) ); ?></span>
											<span id="shop-price-max-label"><?php echo esc_html( number_format_i18n( $selected_max_price ) ); ?></span>
										</div>
									</div>
								</div>
							</form>
							<script>
							document.addEventListener('DOMContentLoaded', function() {
								const form = document.getElementById('shop-filter-form');
								if (!form) return;
								const checks = form.querySelectorAll('input[type="checkbox"]');
								const maxHidden = document.getElementById('shop-max-price');
								const maxRange = document.getElementById('shop-price-max-range');
								const maxLabel = document.getElementById('shop-price-max-label');
								let timer = null;
								function formatNum(n) {
									try { return Number(n).toLocaleString('fa-IR'); } catch(e) { return String(n); }
								}
								function syncPriceInputs() {
									if (!maxRange || !maxHidden) return;
									let maxVal = parseInt(maxRange.value || '0', 10);
									maxHidden.value = String(maxVal);
									if (maxLabel) maxLabel.textContent = formatNum(maxVal);
								}
								function submitSoon() {
									if (timer) clearTimeout(timer);
									timer = setTimeout(() => form.submit(), 180);
								}
								checks.forEach((el) => el.addEventListener('change', submitSoon));
								if (maxRange) {
									maxRange.addEventListener('input', syncPriceInputs);
									maxRange.addEventListener('change', () => { syncPriceInputs(); submitSoon(); });
									syncPriceInputs();
								}
							});
							</script>
						</div>

						<div class="rounded-2xl bg-primary p-6 text-white">
							<span class="material-symbols-outlined mb-3 text-2xl text-tertiary">auto_fix_high</span>
							<h4 class="mb-2 text-sm font-bold">عطر مورد علاقه‌ت رو پیدا کن</h4>
							<p class="mb-4 text-xs leading-6 text-white/70">با چند سوال ساده، بهترین رایحه متناسب با شخصیتت رو پیشنهاد می‌دیم.</p>
							<a class="inline-flex w-full items-center justify-center rounded-lg bg-white/10 px-4 py-2.5 text-xs font-bold hover:bg-white hover:text-primary" href="<?php echo esc_url( home_url( '/quiz/' ) ); ?>">شروع تست رایحه</a>
						</div>
					</div>
				</aside>

				<section class="min-w-0 flex-1">
					<!-- CSS-Only Robust Mobile Drawer & FAB -->
					<style>
					/* CSS-Only Robust Mobile Drawer & FAB */
					.n-fab-btn {
						position: fixed; bottom: 20px; left: 50%; transform: translateX(-50%);
						background: #051061; color: white; border: none; border-radius: 99px;
						padding: 10px 20px; display: flex; align-items: center; gap: 8px;
						font-weight: bold; font-family: inherit; font-size: 14px;
						box-shadow: 0 4px 14px rgba(5,16,97,0.4); z-index: 90;
						cursor: pointer; transition: transform 0.2s; white-space: nowrap;
					}
					.n-fab-btn:active { transform: translateX(-50%) scale(0.95); }
					@media (min-width: 768px) { .n-fab-btn, .n-mobile-only-header { display: none !important; } }
					
					.n-drawer-overlay {
						position: fixed; top: 0; left: 0; right: 0; bottom: 0;
						background: rgba(5,16,97,0.5); backdrop-filter: blur(2px);
						z-index: 9998; opacity: 0; pointer-events: none;
						transition: opacity 0.3s ease;
					}
					.n-drawer-overlay.active { opacity: 1; pointer-events: auto; }
					
					.n-drawer-panel {
						position: fixed; left: 0; right: 0; bottom: 0;
						background: #fff; z-index: 9999;
						border-radius: 24px 24px 0 0;
						transform: translateY(100%);
						transition: transform 0.3s cubic-bezier(0.32, 0.72, 0, 1);
						max-height: 85vh; display: flex; flex-direction: column;
						box-shadow: 0 -10px 40px rgba(0,0,0,0.15);
					}
					.n-drawer-panel.active { transform: translateY(0); }
					
					.n-drawer-header {
						display: flex; justify-content: space-between; align-items: center;
						padding: 16px 20px; border-bottom: 1px solid #eee;
						direction: rtl;
					}
					.n-drawer-close {
						width: 32px; height: 32px; border-radius: 50%; border: none;
						background: #f3f4f6; color: #6b7280; display: flex; align-items: center; justify-content: center;
						cursor: pointer;
					}
					.n-drawer-body { 
						flex: 1; overflow-y: auto; padding: 0 20px; 
						direction: rtl; text-align: right;
					}
					.n-drawer-footer {
						padding: 16px; border-top: 1px solid #eee; display: flex; gap: 12px;
						background: #fff; direction: rtl;
					}
					.n-drawer-btn-primary {
						flex: 1; background: #051061; color: white; border: none; padding: 12px;
						border-radius: 12px; font-weight: bold; font-family: inherit; font-size: 14px;
					}
					.n-drawer-btn-secondary {
						padding: 12px 20px; background: #f3f4f6; color: #4b5563; text-decoration: none;
						border-radius: 12px; font-weight: bold; font-size: 13px; display: flex; align-items: center; justify-content: center;
					}
					
					/* Internal robust components */
					.n-filter-section {
						padding: 20px 0;
						border-bottom: 1px solid #eee;
					}
					.n-filter-section:last-child {
						border-bottom: none;
					}
					.n-filter-title {
						font-size: 14px; font-weight: bold; color: #051061; margin: 0 0 12px 0;
						display: flex; align-items: center; gap: 8px;
					}
					.n-filter-select-wrapper form {
						margin: 0; padding: 0;
					}
					.n-filter-select-wrapper select {
						width: 100%; padding: 12px; border: 1px solid #ddd;
						border-radius: 8px; background: #fafafa; font-family: inherit;
						font-size: 13px; color: #333; outline: none; margin: 0; height: auto;
					}
					.n-brand-wrap {
						display: flex; flex-wrap: wrap; gap: 8px;
					}
					.n-brand-btn {
						padding: 8px 12px; background: #fafafa; border: 1px solid #ddd;
						color: #555; border-radius: 8px; font-size: 12px; font-weight: 600;
						text-decoration: none; display: inline-block;
					}
					.n-scent-grid {
						display: grid; grid-template-columns: 1fr 1fr; gap: 12px;
					}
					.n-checkbox-item {
						display: flex; align-items: center; gap: 8px; padding: 10px;
						background: #fafafa; border: 1px solid #ddd; border-radius: 8px;
						font-size: 12px; color: #444; margin: 0; cursor: pointer;
					}
					.n-checkbox-item input {
						margin: 0; width: 16px; height: 16px; accent-color: #051061;
					}
					</style>

					<!-- Floating Mobile Filter Button -->
					<button id="n-fab-trigger" class="n-fab-btn">
						<span class="material-symbols-outlined" style="font-size: 20px;">tune</span>
						فیلتر و مرتب‌سازی
					</button>

					<!-- Classic trigger for the top of the products list -->
					<div class="n-mobile-only-header mb-6" style="margin-bottom:24px;">
						<button id="n-top-trigger" style="width:100%; display:flex; align-items:center; justify-content:space-between; background:#fff; border:1px solid #eee; border-radius:12px; padding:12px 16px; font-size:14px; font-weight:bold; color:#051061; box-shadow:0 2px 4px rgba(0,0,0,0.05); direction:rtl;">
							<div style="display:flex; align-items:center; gap:8px;">
								<span class="material-symbols-outlined" style="font-size:18px;">tune</span>
								<span>فیلتر پیشرفته محصولات</span>
							</div>
							<span style="font-size:10px; background:#f0f0f0; color:#666; padding:4px 8px; border-radius:4px;">باز کردن</span>
						</button>
					</div>

					<!-- Mobile Filter Drawer overlay-->
					<div id="n-filter-overlay" class="n-drawer-overlay"></div>
					
					<!-- Mobile Filter Drawer panel -->
					<div id="n-filter-drawer" class="n-drawer-panel">
						<!-- Header -->
						<div class="n-drawer-header">
							<h3 style="font-size:16px; margin:0; color:#051061;">فیلتر پیشرفته</h3>
							<button id="n-close-trigger" class="n-drawer-close">
								<span class="material-symbols-outlined" style="font-size:18px;">close</span>
							</button>
						</div>
						
						<!-- Body (scrollable) -->
						<div class="n-drawer-body">
							
							<!-- Price Range -->
							<div class="n-filter-section">
								<h4 class="n-filter-title"><span class="material-symbols-outlined" style="font-size:18px;">payments</span> محدوده قیمت (تومان)</h4>
								<div class="n-price-wrap" style="display:flex; gap:10px; align-items:center; justify-content:space-between;">
									<input type="number" id="n-min-price" placeholder="از" style="flex:1; width:0; padding:10px; border:1px solid #ddd; border-radius:8px; background:#fafafa; font-family:inherit; font-size:13px; text-align:center; outline:none;" value="<?php echo isset($_GET['min_price']) ? esc_attr($_GET['min_price']) : ''; ?>">
									<span style="color:#aaa;">-</span>
									<input type="number" id="n-max-price" placeholder="تا" style="flex:1; width:0; padding:10px; border:1px solid #ddd; border-radius:8px; background:#fafafa; font-family:inherit; font-size:13px; text-align:center; outline:none;" value="<?php echo isset($_GET['max_price']) ? esc_attr($_GET['max_price']) : ''; ?>">
								</div>
							</div>
							
							<!-- Brands -->
							<div class="n-filter-section">
								<h4 class="n-filter-title"><span class="material-symbols-outlined" style="font-size:18px;">branding_watermark</span> برندهای برتر</h4>
								<div class="n-brand-wrap">
									<?php
									$mobile_brand_terms = get_terms( array( 'taxonomy' => 'pa_brand', 'hide_empty' => true, 'number' => 8 ) );
									if ( ! is_wp_error( $mobile_brand_terms ) && ! empty( $mobile_brand_terms ) ) :
										foreach ( $mobile_brand_terms as $mobile_brand_term ) :
											?>
											<a class="n-brand-btn" href="<?php echo esc_url( add_query_arg( 'filter_pa_brand', $mobile_brand_term->slug ) ); ?>">
												<?php echo esc_html( $mobile_brand_term->name ); ?>
											</a>
											<?php
										endforeach;
									else :
										echo '<span style="font-size:12px; color:#999;">برندی موجود نیست.</span>';
									endif;
									?>
								</div>
							</div>
							
							<!-- Scent Family -->
							<div class="n-filter-section">
								<h4 class="n-filter-title"><span class="material-symbols-outlined" style="font-size:18px;">spa</span> خانواده بویایی</h4>
								<div class="n-scent-grid">
									<label class="n-checkbox-item"><input type="checkbox"> <span>چوبی و معطر</span></label>
									<label class="n-checkbox-item"><input type="checkbox"> <span>گلی و پودری</span></label>
									<label class="n-checkbox-item"><input type="checkbox"> <span>خنک و تابستانی</span></label>
									<label class="n-checkbox-item"><input type="checkbox"> <span>شرقی و تند</span></label>
								</div>
							</div>

						</div>
						
						<!-- Footer (fixed actions) -->
						<div class="n-drawer-footer">
							<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="n-drawer-btn-secondary">پاک کردن</a>
							<button id="n-apply-trigger" class="n-drawer-btn-primary">مشاهده محصولات</button>
						</div>
					</div>

					<script>
					document.addEventListener('DOMContentLoaded', function() {
						const overlay = document.getElementById('n-filter-overlay');
						const drawer = document.getElementById('n-filter-drawer');
						const applyBtn = document.getElementById('n-apply-trigger');
						const openTriggers = [document.getElementById('n-fab-trigger'), document.getElementById('n-top-trigger')];
						const closeTriggers = [document.getElementById('n-close-trigger'), applyBtn, overlay];

						function openDrawer() {
							overlay.classList.add('active');
							drawer.classList.add('active');
							document.body.style.overflow = 'hidden';
						}

						function closeDrawer() {
							overlay.classList.remove('active');
							drawer.classList.remove('active');
							document.body.style.overflow = '';
						}

						openTriggers.forEach(btn => { if(btn) btn.addEventListener('click', openDrawer); });
						closeTriggers.forEach(btn => { if(btn) btn.addEventListener('click', closeDrawer); });

						if(applyBtn) {
							applyBtn.addEventListener('click', function(e) {
								e.preventDefault();
								const minPrice = document.getElementById('n-min-price')?.value;
								const maxPrice = document.getElementById('n-max-price')?.value;
								let url = new URL(window.location.href);
								if(minPrice) url.searchParams.set('min_price', minPrice); else url.searchParams.delete('min_price');
								if(maxPrice) url.searchParams.set('max_price', maxPrice); else url.searchParams.delete('max_price');
								window.location.href = url.toString();
							});
						}
					});
					</script>

					<div class="mb-8 rounded-2xl border border-white/60 bg-white/60 p-4 sm:p-5 backdrop-blur-sm">
						<div class="shop-toolbar flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
							<div class="shop-breadcrumb flex items-center gap-2 text-xs text-on-surface-variant">
								<a class="hover:text-primary" href="<?php echo esc_url( home_url( '/' ) ); ?>">خانه</a>
								<span class="material-symbols-outlined text-[12px] opacity-40">chevron_left</span>
								<span class="font-bold text-primary"><?php woocommerce_page_title(); ?></span>
							</div>
							<div class="shop-toolbar-meta flex items-center gap-4 sm:gap-6">
								<div class="shop-toolbar-count text-[10px] font-bold uppercase tracking-[0.16em] text-on-surface-variant/70">
									<?php woocommerce_result_count(); ?>
								</div>
								<div class="shop-toolbar-order border-r border-outline-variant/30 pr-3 sm:pr-4">
									<?php woocommerce_catalog_ordering(); ?>
								</div>
							</div>
						</div>
					</div>

					<?php if ( woocommerce_product_loop() ) : ?>
						<style>
						/* Override any restrictive WooCommerce default wrapper widths and ensure grid layout natively */
						.noble-grid-enforcer {
							display: grid !important;
							grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
							gap: 12px !important;
						}
						.noble-grid-enforcer::before, .noble-grid-enforcer::after { content: none !important; }
						@media (max-width: 767px) { .shop-sidebar { display: none !important; } }
						@media (min-width: 768px) { .noble-grid-enforcer { grid-template-columns: repeat(3, minmax(0, 1fr)) !important; gap: 24px !important; } }
						@media (min-width: 1024px) { .noble-grid-enforcer { grid-template-columns: repeat(4, minmax(0, 1fr)) !important; gap: 32px !important; } }
						.noble-grid-enforcer li.product { width: 100% !important; margin: 0 !important; max-width: none !important; clear: none !important; float: none !important; }
						</style>
						<ul class="products noble-grid-enforcer" aria-label="لیست محصولات فروشگاه">
							<?php
							while ( have_posts() ) :
								the_post();
								wc_get_template_part( 'content', 'product' );
							endwhile;
							?>
							<li class="shop-quiz-tile group flex flex-col items-center justify-center rounded-2xl border border-dashed border-primary/25 bg-gradient-to-b from-white to-[#f0f5ff] p-8 text-center shadow-[0_18px_40px_-28px_rgba(11,42,87,0.35)] transition-all duration-300 hover:border-primary/45 hover:shadow-[0_22px_48px_-24px_rgba(11,42,87,0.45)]">
								<div class="mb-6 flex h-16 w-16 items-center justify-center rounded-2xl bg-primary/[0.06] ring-1 ring-primary/10 transition-transform duration-300 group-hover:scale-105">
									<span class="material-symbols-outlined text-3xl text-primary" aria-hidden="true">search_insights</span>
								</div>
								<h3 class="mb-2 text-lg font-bold text-primary">هنوز مطمئن نیستی؟</h3>
								<p class="mb-6 max-w-[17rem] text-[11px] leading-relaxed text-on-surface-variant">بر اساس سلیقه و خاطراتت، عطر ایده‌آلت را پیشنهاد می‌دهیم.</p>
								<a class="inline-flex items-center justify-center rounded-xl bg-primary px-6 py-3 text-xs font-bold text-white shadow-md transition-all hover:bg-[#12396f] hover:shadow-lg" href="<?php echo esc_url( home_url( '/quiz/' ) ); ?>"><?php echo esc_html__( 'تست رایحه', 'noble-theme' ); ?></a>
							</li>
						</ul>

						<div class="shop-custom-pagination flex justify-center mt-20 mb-10 gap-3">
							<?php
							$total_pages  = max( 1, (int) wc_get_loop_prop( 'total_pages' ) );
							$current_page = max( 1, (int) wc_get_loop_prop( 'current_page' ) );
							$base_link    = html_entity_decode( get_pagenum_link( 1 ) );

							if ( $total_pages > 1 ) {
								echo wp_kses_post(
									paginate_links(
										array(
											'base'      => trailingslashit( $base_link ) . '%_%',
											'format'    => user_trailingslashit( 'page/%#%/', 'paged' ),
											'current'   => $current_page,
											'total'     => $total_pages,
											'mid_size'  => 1,
											'end_size'  => 1,
											'type'      => 'list',
											'prev_text' => '<span class="material-symbols-outlined text-[18px]">chevron_right</span>',
											'next_text' => '<span class="material-symbols-outlined text-[18px]">chevron_left</span>',
										)
									)
								);
							} else {
								?>
								<ul class="page-numbers">
									<li><span class="page-numbers current">۱</span></li>
								</ul>
								<?php
							}
							?>
						</div>
					<?php else : ?>
						<?php do_action( 'woocommerce_no_products_found' ); ?>
					<?php endif; ?>
				</section>
			</div>
		</div>

	</main>
</section>
<?php
get_footer( 'shop' );
