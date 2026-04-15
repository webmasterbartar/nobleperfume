<?php
/**
 * Quiz page (slug: quiz).
 * 5-step scent finder that redirects to /shop/ with filters.
 *
 * @package noble-theme
 */

defined( 'ABSPATH' ) || exit;

get_header();

$shop_url = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/shop/' );

// Build 5 quiz questions from existing WooCommerce global attributes (pa_*).
$quiz_questions = array();
if ( function_exists( 'wc_get_attribute_taxonomies' ) && function_exists( 'wc_attribute_taxonomy_name' ) ) {
	$attrs = wc_get_attribute_taxonomies();
	if ( ! empty( $attrs ) ) {
		$attr_map = array();
		foreach ( $attrs as $attr ) {
			if ( empty( $attr->attribute_name ) ) {
				continue;
			}
			$attr_map[ (string) $attr->attribute_name ] = $attr;
		}

		// Your site's intended 5 questions (in order).
		$desired = array( 'gender', 'longevity', 'occasion', 'scent-family', 'season' );
		$icons   = array(
			'gender'      => 'man',
			'longevity'   => 'schedule',
			'occasion'    => 'celebration',
			'scent-family'=> 'local_florist',
			'season'      => 'wb_sunny',
		);

		foreach ( $desired as $attr_name ) {
			if ( empty( $attr_map[ $attr_name ] ) ) {
				continue;
			}

			$attr     = $attr_map[ $attr_name ];
			$taxonomy = wc_attribute_taxonomy_name( $attr_name ); // e.g. pa_gender
			if ( ! taxonomy_exists( $taxonomy ) ) {
				continue;
			}

			$terms = get_terms(
				array(
					'taxonomy'   => $taxonomy,
					// We want the quiz to work even before products are fully assigned.
					// Showing terms with no products helps testing & onboarding.
					'hide_empty' => false,
					'number'     => 10,
					'orderby'    => 'name',
					'order'      => 'ASC',
				)
			);
			if ( is_wp_error( $terms ) || empty( $terms ) ) {
				continue;
			}

			$options = array();
			$seen_labels = array();
			foreach ( $terms as $term ) {
				$label = trim( (string) $term->name );
				if ( '' === $label ) {
					continue;
				}
				$label_key = mb_strtolower( $label );
				if ( isset( $seen_labels[ $label_key ] ) ) {
					continue;
				}
				$seen_labels[ $label_key ] = true;
				$options[] = array(
					'label'    => $label,
					'icon'     => 'check_circle',
					'filter'   => 'filter_' . $taxonomy, // query arg WooCommerce expects
					'value'    => $term->slug,
					'taxonomy' => $taxonomy,
				);
			}

			$label = ! empty( $attr->attribute_label ) ? $attr->attribute_label : $taxonomy;
			$quiz_questions[] = array(
				'key'        => $taxonomy,
				'title'      => $label,
				'title_full' => 'انتخاب ' . $label,
				'icon'       => isset( $icons[ $attr_name ] ) ? $icons[ $attr_name ] : 'check_circle',
				'options'    => $options,
			);
		}
	}
}

// Fallback: if attributes aren't available, keep a minimal static set so the page never breaks.
if ( empty( $quiz_questions ) ) {
	$quiz_questions = array(
		array(
			'key'   => 'pa_brand',
			'title' => 'انتخاب برند',
			'options' => array(
				array( 'label' => 'Noble', 'icon' => 'verified', 'filter' => 'filter_pa_brand', 'value' => 'noble', 'taxonomy' => 'pa_brand' ),
				array( 'label' => 'Tom Ford', 'icon' => 'workspace_premium', 'filter' => 'filter_pa_brand', 'value' => 'tom-ford', 'taxonomy' => 'pa_brand' ),
			),
		),
	);
}
?>

<main class="pt-0 pb-20 bg-background">
	<style>
	/* Safety: ensure Tailwind's .hidden exists on this page */
	.hidden { display: none !important; }

	.quiz-shell { width: 100%; }
	.quiz-hero { background: rgba(255,255,255,.92); border: 1px solid rgba(5,16,97,.08); border-radius: 18px; padding: 28px 16px; box-shadow: 0 18px 42px -34px rgba(5,16,97,.45); }
	.quiz-icon { width: 72px; height: 72px; border-radius: 999px; background: rgba(5,16,97,.06); display:flex; align-items:center; justify-content:center; margin: 0 auto 10px; }
	.quiz-progress { display:flex; gap: 6px; margin: 14px 0 14px; }
	.quiz-progress .bar { height: 5px; flex: 1; border-radius: 999px; background: rgba(198,197,210,.35); overflow: hidden; }
	.quiz-progress .bar.isActive { background: rgba(5,16,97,.18); }
	.quiz-progress .bar.isDone { background: #051061; }
	.quiz-card { background: rgba(255,255,255,.9); border: 1px solid rgba(5,16,97,.08); border-radius: 18px; padding: 14px; box-shadow: 0 16px 34px -30px rgba(5,16,97,.45); }
	.quiz-stepLabel { color: #c8a84b; font-weight: 800; letter-spacing: .1em; font-size: 10px; }
	.quiz-question { color: #051061; font-weight: 900; font-size: 22px; line-height: 1.5; margin-top: 4px; }
	.quiz-options { display:grid; grid-template-columns: repeat(2,minmax(0,1fr)); gap: 10px; margin-top: 12px; }
	.quiz-opt { background: rgba(255,255,255,.95); border: 1px solid rgba(198,197,210,.35); border-radius: 12px; padding: 14px 8px; display:flex; flex-direction: column; align-items:center; gap: 6px; transition: border-color .18s ease, background .18s ease; }
	.quiz-opt .material-symbols-outlined { font-size: 28px; color: rgba(118,118,130,.95); }
	.quiz-opt span.label { font-weight: 800; color: #151c26; font-size: 15px; }
	.quiz-opt.isSelected { border-color: rgba(5,16,97,.55); background: rgba(5,16,97,.04); }
	.quiz-opt.isSelected .material-symbols-outlined { color: #051061; font-variation-settings: "FILL" 1; }
	.quiz-actions { display:flex; gap: 8px; justify-content: center; margin-top: 12px; flex-wrap: wrap; }
	.quiz-btn { border: 1px solid rgba(5,16,97,.12); background: rgba(255,255,255,.9); color: #051061; font-weight: 900; border-radius: 10px; padding: 9px 14px; display:inline-flex; align-items:center; gap: 6px; font-size: 13px; }
	.quiz-btnPrimary { background: #051061; border-color: #051061; color: #fff; }
	.quiz-btn[disabled] { opacity: .45; cursor: not-allowed; }
	.quiz-panel { position: relative; }
	.quiz-panel.animOut { opacity: 0; transform: translateY(10px); transition: opacity .18s ease, transform .18s ease; }
	.quiz-panel.animIn { opacity: 1; transform: translateY(0); transition: opacity .18s ease, transform .18s ease; }
	@media (max-width: 640px){
		.quiz-hero { padding: 22px 12px; }
		.quiz-question { font-size: 19px; }
		.quiz-options { grid-template-columns: 1fr; gap: 10px; }
		.quiz-opt { padding: 12px 10px; }
		.quiz-opt span.label { font-size: 14px; }
	}
	</style>

	<div class="container mx-auto px-5 sm:px-6 lg:px-8 pt-28">
	<div class="quiz-shell">
		<section class="quiz-hero text-center">
			<div class="quiz-icon">
				<span class="material-symbols-outlined text-4xl text-primary" style="font-variation-settings:'FILL' 1;">sanitizer</span>
			</div>
			<h1 class="text-2xl sm:text-3xl font-extrabold text-primary mb-2">عطر ایده‌آل شما را پیدا می‌کنیم</h1>
			<p class="text-on-surface-variant text-sm sm:text-base mb-0">۵ سوال ساده — نتیجه کاملا شخصی‌سازی شده</p>
		</section>

		<div id="quiz-app" class="mt-8">
			<div id="quiz-progress" class="quiz-progress" aria-label="Quiz progress"></div>

			<section class="quiz-card">
				<div id="quiz-panel" class="quiz-panel animIn">
					<div class="text-center">
						<div id="quiz-stepLabel" class="quiz-stepLabel">گام ۱ از ۵</div>
						<h2 id="quiz-question" class="quiz-question">...</h2>
					</div>

					<div id="quiz-options" class="quiz-options" role="list"></div>

					<div class="quiz-actions">
						<button id="quiz-prev" class="quiz-btn" type="button">
							<span class="material-symbols-outlined text-[18px]">chevron_right</span>
							سوال قبل
						</button>
						<button id="quiz-next" class="quiz-btn quiz-btnPrimary" type="button" disabled>
							سوال بعد
							<span class="material-symbols-outlined text-[18px]">chevron_left</span>
						</button>
						<button id="quiz-finish" class="quiz-btn quiz-btnPrimary hidden" type="button">
							مشاهده عطرهای پیشنهادی
							<span class="material-symbols-outlined text-[18px]">arrow_back</span>
						</button>
					</div>
				</div>
			</section>
		</div>
	</div>
	</div>

	<script type="application/json" id="noble-quiz-payload"><?php echo wp_json_encode( array( 'shopUrl' => $shop_url, 'questions' => array_values( $quiz_questions ) ) ); ?></script>
	<script>
	(function(){
		try {
			const payloadEl = document.getElementById('noble-quiz-payload');
			const payload = payloadEl ? JSON.parse(payloadEl.textContent || '{}') : {};
			const SHOP_URL = payload.shopUrl || <?php echo wp_json_encode( $shop_url ); ?>;
			const QUESTIONS = Array.isArray(payload.questions) ? payload.questions : [];

			const state = {
				step: 0,
				answers: new Array(QUESTIONS.length).fill(null),
			};

			const progress = document.getElementById('quiz-progress');
			const panel = document.getElementById('quiz-panel');
			const stepLabel = document.getElementById('quiz-stepLabel');
			const questionEl = document.getElementById('quiz-question');
			const optionsEl = document.getElementById('quiz-options');
			const prevBtn = document.getElementById('quiz-prev');
			const nextBtn = document.getElementById('quiz-next');
			const finishBtn = document.getElementById('quiz-finish');

			if (!Array.isArray(QUESTIONS) || QUESTIONS.length === 0) {
				questionEl.textContent = 'فعلاً سوالی برای نمایش موجود نیست.';
				optionsEl.innerHTML = '<div style="grid-column:1/-1; text-align:center; font-size:12px; color:rgba(69,70,81,.9);">اتریبیوت‌ها/termها را در ووکامرس ثبت کنید و دوباره تست بگیرید.</div>';
				nextBtn.disabled = true;
				prevBtn.disabled = true;
				finishBtn.classList.add('hidden');
				return;
			}

			function buildProgress(){
				progress.innerHTML = "";
				for(let i=0;i<QUESTIONS.length;i++){
					const bar = document.createElement('div');
					bar.className = 'bar' + (i < state.step ? ' isDone' : (i === state.step ? ' isActive' : ''));
					progress.appendChild(bar);
				}
			}

			function setAnimating(out){
				panel.classList.remove('animIn','animOut');
				panel.offsetHeight; // force reflow
				panel.classList.add(out ? 'animOut' : 'animIn');
			}

			function render(){
				buildProgress();
				const q = QUESTIONS[state.step];
				stepLabel.textContent = `گام ${state.step+1} از ${QUESTIONS.length}`;
				questionEl.textContent = (q && (q.title_full || q.title)) ? (q.title_full || q.title) : '...';

				optionsEl.innerHTML = "";
				const opts = (q && Array.isArray(q.options)) ? q.options : [];
				if (opts.length === 0) {
					optionsEl.innerHTML = '<div style="grid-column:1/-1; text-align:center; font-size:12px; color:rgba(69,70,81,.9);">برای این سوال گزینه‌ای پیدا نشد. لطفاً برای این اتریبیوت مقدار (term) تعریف کنید.</div>';
					prevBtn.disabled = state.step === 0;
					nextBtn.disabled = state.step === QUESTIONS.length - 1;
					if (state.step === QUESTIONS.length - 1) {
						nextBtn.classList.add('hidden');
						finishBtn.classList.remove('hidden');
					} else {
						nextBtn.classList.remove('hidden');
						finishBtn.classList.add('hidden');
					}
					return;
				}

				opts.forEach((opt, idx) => {
					const btn = document.createElement('button');
					btn.type = 'button';
					btn.className = 'quiz-opt' + (state.answers[state.step] === idx ? ' isSelected' : '');
					btn.innerHTML = `
						<span class="material-symbols-outlined">${opt.icon || q.icon || 'check_circle'}</span>
						<span class="label">${opt.label}</span>
					`;
					btn.addEventListener('click', () => {
						state.answers[state.step] = idx;
						render();
					});
					optionsEl.appendChild(btn);
				});

				prevBtn.disabled = state.step === 0;
				nextBtn.disabled = state.step === QUESTIONS.length - 1;

				if (state.step === QUESTIONS.length - 1) {
					nextBtn.classList.add('hidden');
					finishBtn.classList.remove('hidden');
				} else {
					nextBtn.classList.remove('hidden');
					finishBtn.classList.add('hidden');
				}
			}

			function go(delta){
				const next = state.step + delta;
				if (next < 0 || next >= QUESTIONS.length) return;
				setAnimating(true);
				window.setTimeout(() => {
					state.step = next;
					render();
					setAnimating(false);
				}, 180);
			}

			function redirectToShop(){
				let url = new URL(SHOP_URL, window.location.origin);
				const parts = [];

				for (let i=0;i<QUESTIONS.length;i++){
					const q = QUESTIONS[i];
					const ansIdx = state.answers[i];
					if (ansIdx === null) continue;
					const opt = q.options[ansIdx];

					if (opt.filter && opt.value) {
						let v = String(opt.value);
						// If the slug is already percent-encoded, DO NOT encode again (% -> %25).
						const encodedValue = v.includes('%') ? v : encodeURIComponent(v);
						parts.push(encodeURIComponent(String(opt.filter)) + '=' + encodedValue);
						if (opt.taxonomy) {
							parts.push(
								encodeURIComponent('query_type_' + String(opt.taxonomy)) + '=or'
							);
						}
					}
				}

				const qs = parts.join('&');
				const dest = String(url.toString());
				const normalized = dest.endsWith('/') ? dest : (dest + '/');
				window.location.href = qs ? (normalized + '?' + qs) : dest;
			}

			prevBtn.addEventListener('click', () => go(-1));
			nextBtn.addEventListener('click', () => go(1));
			finishBtn.addEventListener('click', redirectToShop);

			render();
		} catch (err) {
			const questionEl = document.getElementById('quiz-question');
			const optionsEl = document.getElementById('quiz-options');
			if (questionEl) questionEl.textContent = 'خطا در اجرای کوییز';
			if (optionsEl) optionsEl.innerHTML = `<div style="grid-column:1/-1; text-align:center; font-size:12px; color:#ba1a1a;">${String(err && err.message ? err.message : err)}</div>`;
			console.error(err);
		}
	})();
	</script>
</main>

<?php
get_footer();
?>

