<?php
/**
 * Buying guide system page.
 *
 * @package noble-theme
 */

defined( 'ABSPATH' ) || exit;

get_header();

$guide_steps = array(
	array(
		'icon'        => 'manage_search',
		'title'       => '۱) انتخاب رایحه مناسب',
		'description' => 'در صفحه هر محصول، نت های بویایی، فصل پیشنهادی و گروه رایحه را بررسی کنید. اگر مردد هستید، از کوییز رایحه استفاده کنید.',
	),
	array(
		'icon'        => 'tune',
		'title'       => '۲) انتخاب حجم و واریانت',
		'description' => 'برای محصولات متغیر، ابتدا واریانت مورد نظر را انتخاب کنید تا قیمت و موجودی نهایی نمایش داده شود.',
	),
	array(
		'icon'        => 'shopping_bag',
		'title'       => '۳) افزودن به سبد خرید',
		'description' => 'پس از انتخاب صحیح، محصول را به سبد خرید اضافه کنید و تعداد را در مرحله پرداخت تنظیم کنید.',
	),
	array(
		'icon'        => 'local_shipping',
		'title'       => '۴) تکمیل اطلاعات ارسال',
		'description' => 'در مرحله تسویه حساب، نام، موبایل، آدرس دقیق و روش ارسال را ثبت کنید تا سفارش بدون تاخیر پردازش شود.',
	),
	array(
		'icon'        => 'payments',
		'title'       => '۵) پرداخت و ثبت نهایی',
		'description' => 'روش پرداخت را انتخاب کنید و سفارش را نهایی کنید. پس از ثبت، وضعیت سفارش از طریق پیامک/حساب کاربری قابل پیگیری است.',
	),
);

$guide_faqs = array(
	array(
		'q' => 'زمان ارسال سفارش چقدر است؟',
		'a' => 'در اکثر سفارش ها، آماده سازی سفارش همان روز انجام می شود و زمان تحویل بسته به شهر مقصد و روش ارسال متفاوت است.',
	),
	array(
		'q' => 'اگر رایحه مطابق سلیقه من نبود چه کنم؟',
		'a' => 'پیشنهاد می کنیم قبل از خرید نهایی از راهنمای نت های بویایی و کوییز رایحه استفاده کنید. همچنین برای راهنمایی دقیق تر با پشتیبانی تماس بگیرید.',
	),
	array(
		'q' => 'چطور کد تخفیف را اعمال کنم؟',
		'a' => 'در مرحله اول تسویه حساب، فیلد کد تخفیف وجود دارد. کد معتبر را وارد کرده و روی دکمه اعمال بزنید.',
	),
	array(
		'q' => 'چطور وضعیت سفارش را پیگیری کنم؟',
		'a' => 'پس از ثبت سفارش، اطلاعات پیگیری در پنل کاربری و کانال های اطلاع رسانی ثبت می شود. در صورت نیاز می توانید با تیم پشتیبانی هماهنگ کنید.',
	),
);
?>
<section class="noble-guide-page bg-background min-h-screen pb-16">
	<div class="container mx-auto px-5 sm:px-6 lg:px-8 pt-8">
		<nav class="noble-guide-page__breadcrumb text-xs sm:text-sm mb-5" aria-label="<?php echo esc_attr__( 'مسیر صفحه', 'noble-theme' ); ?>">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'خانه', 'noble-theme' ); ?></a>
			<span aria-hidden="true">/</span>
			<span><?php esc_html_e( 'راهنمای خرید', 'noble-theme' ); ?></span>
		</nav>

		<header class="noble-guide-hero rounded-3xl border border-primary/10 p-6 sm:p-8 lg:p-10 mb-8">
			<div class="noble-guide-hero__eyebrow"><?php esc_html_e( 'Noble Purchase Flow', 'noble-theme' ); ?></div>
			<h1 class="noble-guide-hero__title"><?php esc_html_e( 'راهنمای خرید نوبل', 'noble-theme' ); ?></h1>
			<p class="noble-guide-hero__desc"><?php esc_html_e( 'تمام مراحل خرید از انتخاب رایحه تا ثبت نهایی سفارش، به صورت شفاف و مرحله به مرحله.', 'noble-theme' ); ?></p>
			<div class="noble-guide-hero__actions">
				<a class="noble-guide-btn noble-guide-btn--primary" href="<?php echo esc_url( home_url( '/shop/' ) ); ?>">
					<span class="material-symbols-outlined" aria-hidden="true">storefront</span>
					<?php esc_html_e( 'شروع خرید', 'noble-theme' ); ?>
				</a>
				<a class="noble-guide-btn noble-guide-btn--secondary" href="<?php echo esc_url( home_url( '/quiz/' ) ); ?>">
					<span class="material-symbols-outlined" aria-hidden="true">quiz</span>
					<?php esc_html_e( 'کوییز انتخاب رایحه', 'noble-theme' ); ?>
				</a>
			</div>
		</header>

		<section class="mb-10" aria-labelledby="noble-guide-steps-title">
			<div class="noble-guide-section-head mb-4">
				<h2 id="noble-guide-steps-title"><?php esc_html_e( 'مراحل خرید', 'noble-theme' ); ?></h2>
				<p><?php esc_html_e( 'این مسیر پیشنهادی کمک می کند سریع تر به انتخاب مناسب برسید.', 'noble-theme' ); ?></p>
			</div>
			<div class="noble-guide-steps-grid">
				<?php foreach ( $guide_steps as $step ) : ?>
					<article class="noble-guide-step-card">
						<div class="noble-guide-step-card__icon" aria-hidden="true">
							<span class="material-symbols-outlined"><?php echo esc_html( $step['icon'] ); ?></span>
						</div>
						<h3><?php echo esc_html( $step['title'] ); ?></h3>
						<p><?php echo esc_html( $step['description'] ); ?></p>
					</article>
				<?php endforeach; ?>
			</div>
		</section>

		<section class="mb-10" aria-labelledby="noble-guide-faq-title">
			<div class="noble-guide-section-head mb-4">
				<h2 id="noble-guide-faq-title"><?php esc_html_e( 'سوالات پرتکرار', 'noble-theme' ); ?></h2>
				<p><?php esc_html_e( 'پاسخ سریع به سوالات رایج کاربران قبل از ثبت سفارش.', 'noble-theme' ); ?></p>
			</div>
			<div class="noble-guide-faq-list">
				<?php foreach ( $guide_faqs as $item ) : ?>
					<details class="noble-guide-faq-item">
						<summary>
							<span><?php echo esc_html( $item['q'] ); ?></span>
							<span class="material-symbols-outlined noble-guide-faq-item__chevron" aria-hidden="true">expand_more</span>
						</summary>
						<div class="noble-guide-faq-item__body">
							<p><?php echo esc_html( $item['a'] ); ?></p>
						</div>
					</details>
				<?php endforeach; ?>
			</div>
		</section>

		<section class="noble-guide-support-grid" aria-label="<?php echo esc_attr__( 'راهنمای تکمیلی', 'noble-theme' ); ?>">
			<article class="noble-guide-support-card">
				<span class="material-symbols-outlined" aria-hidden="true">support_agent</span>
				<h3><?php esc_html_e( 'نیاز به مشاوره قبل از خرید؟', 'noble-theme' ); ?></h3>
				<p><?php esc_html_e( 'برای انتخاب رایحه و حجم مناسب، با تیم پشتیبانی نوبل در ارتباط باشید.', 'noble-theme' ); ?></p>
				<a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'ارتباط با پشتیبانی', 'noble-theme' ); ?></a>
			</article>
			<article class="noble-guide-support-card">
				<span class="material-symbols-outlined" aria-hidden="true">package_2</span>
				<h3><?php esc_html_e( 'پیگیری سفارش', 'noble-theme' ); ?></h3>
				<p><?php esc_html_e( 'پس از ثبت سفارش، وضعیت بسته از طریق پنل کاربری و اطلاعات ثبت شده در دسترس است.', 'noble-theme' ); ?></p>
				<a href="<?php echo esc_url( home_url( '/my-account/' ) ); ?>"><?php esc_html_e( 'ورود به حساب کاربری', 'noble-theme' ); ?></a>
			</article>
		</section>
	</div>
</section>
<?php
get_footer();
