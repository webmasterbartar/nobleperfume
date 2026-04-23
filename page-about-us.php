<?php
/**
 * About us page.
 *
 * @package noble-theme
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<section class="noble-about-page bg-background min-h-screen pb-16">
	<div class="container mx-auto px-5 sm:px-6 lg:px-8 pt-8">
		<nav class="noble-about-page__breadcrumb text-xs sm:text-sm mb-5" aria-label="<?php echo esc_attr__( 'مسیر صفحه', 'noble-theme' ); ?>">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'خانه', 'noble-theme' ); ?></a>
			<span aria-hidden="true">/</span>
			<span><?php esc_html_e( 'درباره ما', 'noble-theme' ); ?></span>
		</nav>

		<header class="noble-about-hero rounded-3xl border border-primary/10 p-6 sm:p-8 lg:p-10 mb-8">
			<div class="noble-about-hero__eyebrow"><?php esc_html_e( 'About Noble', 'noble-theme' ); ?></div>
			<h1 class="noble-about-hero__title"><?php esc_html_e( 'نوبل؛ تجربه‌ای لوکس از دنیای عطر', 'noble-theme' ); ?></h1>
			<p class="noble-about-hero__desc"><?php esc_html_e( 'در نوبل، انتخاب عطر فقط خرید محصول نیست؛ یک تجربه شخصی و دقیق است. ما تلاش می‌کنیم با ترکیب اصالت، سلیقه و مشاوره درست، مسیر خرید عطر را ساده و لذت‌بخش کنیم.', 'noble-theme' ); ?></p>
		</header>

		<section class="noble-about-grid mb-8" aria-label="<?php echo esc_attr__( 'ارزش‌های نوبل', 'noble-theme' ); ?>">
			<article class="noble-about-card">
				<span class="material-symbols-outlined" aria-hidden="true">verified</span>
				<h2><?php esc_html_e( 'اصالت و کیفیت', 'noble-theme' ); ?></h2>
				<p><?php esc_html_e( 'تمام محصولات با تمرکز بر کیفیت و تجربه واقعی مشتری انتخاب می‌شوند تا خریدی مطمئن داشته باشید.', 'noble-theme' ); ?></p>
			</article>
			<article class="noble-about-card">
				<span class="material-symbols-outlined" aria-hidden="true">support_agent</span>
				<h2><?php esc_html_e( 'مشاوره صادقانه', 'noble-theme' ); ?></h2>
				<p><?php esc_html_e( 'تیم نوبل با توجه به سلیقه، فصل و کاربرد، کمک می‌کند رایحه‌ای مناسب خودتان پیدا کنید.', 'noble-theme' ); ?></p>
			</article>
			<article class="noble-about-card">
				<span class="material-symbols-outlined" aria-hidden="true">local_shipping</span>
				<h2><?php esc_html_e( 'ارسال مطمئن', 'noble-theme' ); ?></h2>
				<p><?php esc_html_e( 'از ثبت سفارش تا تحویل، فرایند ارسال با شفافیت کامل دنبال می‌شود تا تجربه خرید بی‌دغدغه باشد.', 'noble-theme' ); ?></p>
			</article>
		</section>

		<section class="noble-about-story rounded-3xl border border-primary/10 p-6 sm:p-8">
			<h2><?php esc_html_e( 'داستان ما', 'noble-theme' ); ?></h2>
			<p><?php esc_html_e( 'نوبل با یک ایده ساده شروع شد: اینکه هر فرد بتواند بدون سردرگمی، عطر مناسب شخصیت خودش را پیدا کند. امروز هم همین مسیر را ادامه می‌دهیم؛ با انتخاب دقیق محصولات، تجربه کاربری حرفه‌ای و همراهی واقعی با مشتری.', 'noble-theme' ); ?></p>
			<div class="noble-about-story__actions">
				<a class="noble-about-btn noble-about-btn--primary" href="<?php echo esc_url( home_url( '/shop/' ) ); ?>">
					<span class="material-symbols-outlined" aria-hidden="true">storefront</span>
					<?php esc_html_e( 'مشاهده محصولات', 'noble-theme' ); ?>
				</a>
				<a class="noble-about-btn noble-about-btn--secondary" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">
					<span class="material-symbols-outlined" aria-hidden="true">mail</span>
					<?php esc_html_e( 'ارتباط با ما', 'noble-theme' ); ?>
				</a>
			</div>
		</section>
	</div>
</section>
<?php
get_footer();
