<?php
/**
 * Gift Box Builder page template.
 *
 * @package noble-theme
 */

defined( 'ABSPATH' ) || exit;

get_header();

$products_raw = function_exists( 'wc_get_products' ) ? wc_get_products(
	array(
		'status'  => 'publish',
		'limit'   => 36,
		'orderby' => 'date',
		'order'   => 'DESC',
		'return'  => 'objects',
	)
) : array();

$products = array();
foreach ( $products_raw as $product ) {
	if ( ! $product instanceof WC_Product ) {
		continue;
	}
	if ( ! $product->is_purchasable() || ! $product->is_in_stock() ) {
		continue;
	}

	$price = (float) $product->get_price();
	if ( $price <= 0 && $product->is_type( 'variable' ) ) {
		$price = (float) $product->get_variation_price( 'min', true );
	}
	if ( $price <= 0 ) {
		continue;
	}

	$gender_terms = wp_get_post_terms( $product->get_id(), 'pa_gender', array( 'fields' => 'names' ) );
	$gender_label = ( ! is_wp_error( $gender_terms ) && ! empty( $gender_terms ) ) ? (string) $gender_terms[0] : 'یونیسکس';

	$products[] = array(
		'id'     => $product->get_id(),
		'name'   => $product->get_name(),
		'price'  => (int) round( $price ),
		'image'  => wp_get_attachment_image_url( $product->get_image_id(), 'woocommerce_thumbnail' ) ?: wc_placeholder_img_src( 'woocommerce_thumbnail' ),
		'gender' => $gender_label,
		'family' => ( ! is_wp_error( wp_get_post_terms( $product->get_id(), 'pa_scent-family', array( 'fields' => 'names' ) ) ) && ! empty( wp_get_post_terms( $product->get_id(), 'pa_scent-family', array( 'fields' => 'names' ) ) ) ) ? (string) wp_get_post_terms( $product->get_id(), 'pa_scent-family', array( 'fields' => 'names' ) )[0] : 'عمومی',
		'season' => ( ! is_wp_error( wp_get_post_terms( $product->get_id(), 'pa_season', array( 'fields' => 'names' ) ) ) && ! empty( wp_get_post_terms( $product->get_id(), 'pa_season', array( 'fields' => 'names' ) ) ) ) ? (string) wp_get_post_terms( $product->get_id(), 'pa_season', array( 'fields' => 'names' ) )[0] : 'چهارفصل',
		'occasion' => ( ! is_wp_error( wp_get_post_terms( $product->get_id(), 'pa_occasion', array( 'fields' => 'names' ) ) ) && ! empty( wp_get_post_terms( $product->get_id(), 'pa_occasion', array( 'fields' => 'names' ) ) ) ) ? (string) wp_get_post_terms( $product->get_id(), 'pa_occasion', array( 'fields' => 'names' ) )[0] : 'عمومی',
	);
}

$payload = array(
	'products' => $products,
	'ajaxUrl'  => admin_url( 'admin-ajax.php' ),
	'nonce'    => wp_create_nonce( 'noble_gift_box_builder' ),
);
?>

<main class="pt-24 pb-16 bg-background">
	<style>
	.gift-builder-wrap { max-width: 1300px; margin-left: auto; margin-right: auto; }
	.gift-step { border: 1px solid rgba(5,16,97,.1); border-radius: 16px; padding: 14px; background: #fff; }
	.gift-right-stack > section { margin-top: 24px; }
	.gift-right-stack > section:first-child { margin-top: 0; }
	.gift-step-title { font-size: 15px; font-weight: 800; color: #051061; margin-bottom: 10px; }
	.gift-chip { height: 38px; padding: 0 14px; border-radius: 999px; border: 1px solid rgba(5,16,97,.15); font-size: 13px; font-weight: 700; color: #051061; background: #fff; }
	.gift-chip.is-active { background: #051061; border-color: #051061; color: #fff; }
	.gift-filter-group { border: 1px solid rgba(5,16,97,.1); background: #f8faff; border-radius: 12px; padding: 10px; margin-top: 10px; }
	.gift-filter-title { font-size: 12px; font-weight: 800; color: #334155; margin-bottom: 8px; }
	.gift-check-wrap { display: grid; grid-template-columns: repeat(2,minmax(0,1fr)); gap: 8px; }
	.gift-check-item { display: inline-flex; align-items: center; justify-content: space-between; gap: 8px; border: 1px solid rgba(5,16,97,.12); border-radius: 9px; background: #fff; padding: 7px 9px; font-size: 12px; color: #334155; }
	.gift-check-item input[type="checkbox"] { width: 14px; height: 14px; accent-color: #051061; }
	.gift-product-card { text-align: right; padding: 8px; border-radius: 12px; border: 1px solid rgba(5,16,97,.1); background: #f8f9ff; position: relative; }
	.gift-product-card.is-active { border-color: #051061; background: rgba(5,16,97,.04); }
	.gift-product-check { position: absolute; top: 8px; left: 8px; width: 18px; height: 18px; border-radius: 999px; background: #051061; color: #fff; font-size: 13px; display: inline-flex; align-items: center; justify-content: center; }
	#gift-products-grid { max-height: 420px; overflow-y: auto; }
	.gift-submit[disabled] { opacity: .45; cursor: not-allowed; }
	@media (min-width: 1024px){
		.gift-sticky-preview {
			position: sticky !important;
			top: 110px !important;
			align-self: start;
		}
	}
	</style>
	<div class="gift-builder-wrap px-5 sm:px-6 lg:px-8">
		<div class="mb-8 text-center">
			<h1 class="text-3xl md:text-4xl font-extrabold text-primary mb-2">باکس هدیه لوکس نوبل</h1>
			<p class="text-on-surface-variant text-sm md:text-base">باکس اختصاصی خودت را بساز و مستقیم ثبت سفارش کن</p>
		</div>

		<div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
			<div class="lg:col-span-5">
				<div class="gift-sticky-preview rounded-3xl border border-primary/10 bg-white p-6 md:p-8">
					<div class="gift-left-block">
					<div class="relative w-full aspect-square max-w-[380px] mx-auto">
						<img src="<?php echo esc_url( get_template_directory_uri() . '/gift_box_builder_page/screen.png' ); ?>" alt="Gift box preview" class="w-full h-full object-cover rounded-2xl opacity-20" />
						<div class="absolute inset-0 flex flex-col items-center justify-center">
							<div class="w-24 h-24 rounded-full bg-primary/5 flex items-center justify-center mb-3">
								<span class="material-symbols-outlined text-primary">redeem</span>
							</div>
							<div class="text-primary font-bold">پیش‌نمایش باکس</div>
						</div>
					</div>
					</div>
					<div class="gift-left-block mt-8 pt-6 border-t border-dashed border-primary/15">
						<div class="text-xs text-on-surface-variant mb-2">عطرهای انتخابی</div>
						<div id="gift-preview-items" class="flex gap-2 flex-wrap"></div>
					</div>
					<div class="gift-left-block mt-8 pt-6 border-t border-dashed border-primary/15 rounded-2xl bg-primary/5 border border-primary/10 p-5 flex items-center justify-between">
						<span class="font-bold text-primary">جمع کل</span>
						<span id="gift-total-price" class="font-extrabold text-primary text-lg">۰ تومان</span>
					</div>
				</div>
			</div>

			<div class="lg:col-span-7 rounded-2xl border border-primary/10 bg-white p-5 md:p-7">
				<div class="gift-right-stack">
					<section class="gift-step">
						<div class="gift-step-title">۱) تعداد عطرهای باکس</div>
						<div id="gift-count-options" class="flex gap-2 flex-wrap"></div>
					</section>

					<section class="gift-step">
						<div class="gift-step-title">۲) فیلتر رایحه</div>
						<div class="gift-filter-group">
							<div class="gift-filter-title">جنسیت</div>
							<div id="gift-gender-options" class="gift-check-wrap"></div>
						</div>
						<div class="gift-filter-group">
							<div class="gift-filter-title">خانواده رایحه</div>
							<div id="gift-family-options" class="gift-check-wrap"></div>
						</div>
						<div class="gift-filter-group">
							<div class="gift-filter-title">فصل</div>
							<div id="gift-season-options" class="gift-check-wrap"></div>
						</div>
						<div class="gift-filter-group">
							<div class="gift-filter-title">مناسبت</div>
							<div id="gift-occasion-options" class="gift-check-wrap"></div>
						</div>
					</section>

					<section class="gift-step">
						<div class="flex items-center justify-between mb-3">
							<div class="gift-step-title !mb-0">۳) انتخاب عطرها</div>
							<span id="gift-selected-count" class="text-xs font-bold text-primary/80">۰ از ۳</span>
						</div>
						<div id="gift-select-hint" class="text-[11px] text-on-surface-variant mb-2">به تعداد باکس عطر انتخاب کن</div>
						<div id="gift-products-grid" class="grid grid-cols-2 md:grid-cols-3 gap-3 no-scrollbar"></div>
					</section>

					<section class="gift-step">
						<div class="gift-step-title">۴) پیام کارت هدیه</div>
						<textarea id="gift-message" class="w-full min-h-[96px] rounded-xl border border-primary/10 bg-background p-3 text-sm outline-none focus:border-primary/40" maxlength="150" placeholder="متن کارت هدیه..."></textarea>
						<div id="gift-message-count" class="text-xs text-on-surface-variant mt-2">۰ / ۱۵۰</div>
					</section>

					<section class="gift-step">
						<div class="gift-step-title">۵) نوع بسته‌بندی</div>
						<div id="gift-package-options" class="grid grid-cols-1 md:grid-cols-2 gap-3"></div>
					</section>

					<section class="rounded-2xl bg-primary text-white p-4">
						<div class="font-bold mb-3">۶) خلاصه سفارش</div>
						<div id="gift-summary-lines" class="text-sm space-y-2 opacity-90"></div>
						<div class="border-t border-white/20 mt-3 pt-3 flex items-center justify-between font-bold">
							<span>مبلغ پرداختی</span>
							<span id="gift-summary-total">۰ تومان</span>
						</div>
						<button id="gift-submit" class="gift-submit w-full mt-4 h-11 rounded-xl bg-tertiary text-white font-bold" disabled>ادامه و ثبت سفارش</button>
					</section>
				</div>
			</div>
		</div>
	</div>

	<script type="application/json" id="gift-builder-payload"><?php echo wp_json_encode( $payload ); ?></script>
	<script>
	(function() {
		const payloadEl = document.getElementById('gift-builder-payload');
		if (!payloadEl) return;
		const payload = JSON.parse(payloadEl.textContent || '{}');
		const products = Array.isArray(payload.products) ? payload.products : [];
		const ajaxUrl = payload.ajaxUrl || '';
		const nonce = payload.nonce || '';

		const counts = [1,3,5,10];
		const genders = Array.from(new Set(products.map(p => p.gender).filter(Boolean)));
		const families = Array.from(new Set(products.map(p => p.family).filter(Boolean)));
		const seasons = Array.from(new Set(products.map(p => p.season).filter(Boolean)));
		const occasions = Array.from(new Set(products.map(p => p.occasion).filter(Boolean)));
		const packages = [
			{id:'standard',label:'استاندارد',price:0,desc:'جعبه استاندارد نوبل'},
			{id:'premium',label:'پریمیوم طلایی',price:50000,desc:'جعبه لوکس + کارت ویژه'}
		];

		const state = { count: 3, genders: [], families: [], seasons: [], occasions: [], selected: [], package: 'standard', message: '' };

		const countWrap = document.getElementById('gift-count-options');
		const genderWrap = document.getElementById('gift-gender-options');
		const familyWrap = document.getElementById('gift-family-options');
		const seasonWrap = document.getElementById('gift-season-options');
		const occasionWrap = document.getElementById('gift-occasion-options');
		const packageWrap = document.getElementById('gift-package-options');
		const grid = document.getElementById('gift-products-grid');
		const selectedCount = document.getElementById('gift-selected-count');
		const preview = document.getElementById('gift-preview-items');
		const total = document.getElementById('gift-total-price');
		const summaryLines = document.getElementById('gift-summary-lines');
		const summaryTotal = document.getElementById('gift-summary-total');
		const selectHint = document.getElementById('gift-select-hint');
		const messageInput = document.getElementById('gift-message');
		const messageCount = document.getElementById('gift-message-count');
		const submitBtn = document.getElementById('gift-submit');

		function fmt(n){ try { return Number(n).toLocaleString('fa-IR') + ' تومان'; } catch(e){ return n + ' تومان'; } }

		function renderChoiceButtons(target, list, current, onClick){
			target.innerHTML = '';
			list.forEach(item => {
				const value = typeof item === 'object' ? item.id : item;
				const label = typeof item === 'object' ? item.label : item;
				const btn = document.createElement('button');
				btn.type = 'button';
				btn.className = 'gift-chip ' + (String(current) === String(value) ? 'is-active' : '');
				btn.textContent = label;
				btn.addEventListener('click', () => onClick(item));
				target.appendChild(btn);
			});
		}

		function renderCheckboxGroup(target, list, selected, onToggle){
			target.innerHTML = '';
			list.forEach(label => {
				const isChecked = selected.includes(label);
				const id = 'chk-' + Math.random().toString(36).slice(2, 9);
				const row = document.createElement('label');
				row.className = 'gift-check-item';
				row.setAttribute('for', id);
				row.innerHTML = `<span>${label}</span><input id="${id}" type="checkbox" ${isChecked ? 'checked' : ''} />`;
				row.querySelector('input').addEventListener('change', (e) => onToggle(label, e.target.checked));
				target.appendChild(row);
			});
		}

		function filteredProducts(){
			return products.filter(p => {
				const genderOk = state.genders.length === 0 || state.genders.includes(p.gender);
				const familyOk = state.families.length === 0 || state.families.includes(p.family);
				const seasonOk = state.seasons.length === 0 || state.seasons.includes(p.season);
				const occasionOk = state.occasions.length === 0 || state.occasions.includes(p.occasion);
				return genderOk && familyOk && seasonOk && occasionOk;
			});
		}

		function toggleProduct(id){
			const idx = state.selected.indexOf(id);
			if (idx >= 0) {
				state.selected.splice(idx,1);
			} else {
				state.selected.push(id);
				if (state.selected.length > state.count) {
					state.selected.shift();
				}
			}
			render();
		}

		function renderProducts(){
			grid.innerHTML = '';
			filteredProducts().forEach(p => {
				const active = state.selected.includes(p.id);
				const card = document.createElement('button');
				card.type = 'button';
				card.className = 'gift-product-card ' + (active ? 'is-active' : '');
				card.innerHTML = `
					${active ? '<span class="gift-product-check">✓</span>' : ''}
					<div class="aspect-square rounded-lg overflow-hidden bg-white mb-2">
						<img src="${p.image}" alt="${p.name}" class="w-full h-full object-cover">
					</div>
					<div class="text-xs font-bold text-primary truncate">${p.name}</div>
					<div class="text-[11px] text-on-surface-variant">${p.gender}</div>
					<div class="text-[11px] font-bold text-primary mt-1">${fmt(p.price)}</div>
				`;
				card.addEventListener('click', () => toggleProduct(p.id));
				grid.appendChild(card);
			});
		}

		function selectedProducts(){
			const map = new Map(products.map(p => [p.id,p]));
			return state.selected.map(id => map.get(id)).filter(Boolean);
		}

		function renderSummary(){
			const items = selectedProducts();
			const packageObj = packages.find(p => p.id === state.package) || packages[0];
			const productTotal = items.reduce((s,p) => s + Number(p.price || 0), 0);
			const grand = productTotal + Number(packageObj.price || 0);
			const isReady = items.length === state.count;

			selectedCount.textContent = `${items.length} از ${state.count}`;
			if (selectHint) {
				selectHint.textContent = isReady ? 'تعداد انتخاب کامل شد' : `هنوز ${state.count - items.length} عطر دیگر انتخاب کن`;
			}
			preview.innerHTML = items.length ? items.map(p => `<div class="h-10 px-3 rounded-full bg-white border border-primary/10 text-xs inline-flex items-center">${p.name}</div>`).join('') : '<div class="text-xs text-on-surface-variant">هنوز عطری انتخاب نشده</div>';
			total.textContent = fmt(grand);
			summaryTotal.textContent = fmt(grand);
			submitBtn.disabled = !isReady;

			summaryLines.innerHTML = `
				<div class="flex justify-between"><span>${items.length} عطر انتخابی</span><span>${fmt(productTotal)}</span></div>
				<div class="flex justify-between"><span>${packageObj.label}</span><span>${fmt(packageObj.price)}</span></div>
			`;
		}

		function renderPackages(){
			packageWrap.innerHTML = '';
			packages.forEach(p => {
				const active = p.id === state.package;
				const btn = document.createElement('button');
				btn.type = 'button';
				btn.className = 'text-right p-3 rounded-xl border ' + (active ? 'border-tertiary bg-tertiary/10' : 'border-primary/10');
				btn.innerHTML = `<div class="font-bold text-primary">${p.label}</div><div class="text-xs text-on-surface-variant">${p.desc}</div><div class="text-xs font-bold text-tertiary mt-1">${fmt(p.price)}</div>`;
				btn.addEventListener('click', () => { state.package = p.id; renderSummary(); });
				packageWrap.appendChild(btn);
			});
		}

		function render(){
			renderChoiceButtons(countWrap, counts, state.count, (v) => { state.count = Number(v); if (state.selected.length > state.count) state.selected = state.selected.slice(-state.count); render(); });
			renderCheckboxGroup(genderWrap, genders, state.genders, (label, checked) => {
				state.genders = checked ? [...state.genders, label] : state.genders.filter(v => v !== label);
				renderProducts(); renderSummary();
			});
			renderCheckboxGroup(familyWrap, families, state.families, (label, checked) => {
				state.families = checked ? [...state.families, label] : state.families.filter(v => v !== label);
				renderProducts(); renderSummary();
			});
			renderCheckboxGroup(seasonWrap, seasons, state.seasons, (label, checked) => {
				state.seasons = checked ? [...state.seasons, label] : state.seasons.filter(v => v !== label);
				renderProducts(); renderSummary();
			});
			renderCheckboxGroup(occasionWrap, occasions, state.occasions, (label, checked) => {
				state.occasions = checked ? [...state.occasions, label] : state.occasions.filter(v => v !== label);
				renderProducts(); renderSummary();
			});
			renderPackages();
			renderProducts();
			renderSummary();
		}

		messageInput.addEventListener('input', () => {
			state.message = messageInput.value || '';
			messageCount.textContent = `${state.message.length} / 150`;
		});

		submitBtn.addEventListener('click', async () => {
			if (state.selected.length !== state.count) {
				alert('تعداد انتخاب عطر باید دقیقاً با تعداد باکس برابر باشد.');
				return;
			}
			submitBtn.disabled = true;
			submitBtn.textContent = 'در حال پردازش...';
			try {
				const form = new FormData();
				form.append('action', 'noble_build_gift_box_cart');
				form.append('nonce', nonce);
				state.selected.forEach((id) => form.append('product_ids[]', String(id)));
				form.append('package_type', state.package);
				form.append('gift_message', state.message);

				const res = await fetch(ajaxUrl, { method: 'POST', body: form, credentials: 'same-origin' });
				const json = await res.json();
				if (!json || !json.success) throw new Error((json && json.data && json.data.message) ? json.data.message : 'خطا در ثبت باکس');
				window.location.href = json.data.redirect;
			} catch (err) {
				alert(err.message || 'مشکلی رخ داد');
				submitBtn.disabled = false;
				submitBtn.textContent = 'ادامه و ثبت سفارش';
			}
		});

		render();
	})();
	</script>
</main>

<?php
get_footer();

