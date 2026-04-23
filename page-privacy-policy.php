<?php
/**
 * Privacy policy page template.
 *
 * @package noble-theme
 */

defined( 'ABSPATH' ) || exit;

get_header();

$updated_at = (string) get_the_modified_date( 'Y/m/d' );
?>
<section class="noble-legal-page bg-background min-h-screen pb-16">
	<div class="container mx-auto px-5 sm:px-6 lg:px-8 pt-8">
		<nav class="noble-legal-page__breadcrumb text-xs sm:text-sm mb-5" aria-label="<?php echo esc_attr__( 'مسیر صفحه', 'noble-theme' ); ?>">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'خانه', 'noble-theme' ); ?></a>
			<span aria-hidden="true">/</span>
			<span><?php esc_html_e( 'حریم خصوصی', 'noble-theme' ); ?></span>
		</nav>

		<header class="noble-legal-hero rounded-3xl border border-primary/10 p-6 sm:p-8 lg:p-10 mb-8">
			<div class="noble-legal-hero__eyebrow"><?php esc_html_e( 'Privacy', 'noble-theme' ); ?></div>
			<h1 class="noble-legal-hero__title"><?php esc_html_e( 'سیاست حفظ حریم خصوصی', 'noble-theme' ); ?></h1>
			<p class="noble-legal-hero__desc"><?php esc_html_e( 'حفظ اطلاعات شخصی کاربران برای نوبل یک اصل مهم است. این صفحه توضیح می‌دهد چه داده‌هایی جمع‌آوری می‌شود و چگونه از آن‌ها استفاده می‌کنیم.', 'noble-theme' ); ?></p>
			<div class="noble-legal-hero__meta"><?php echo esc_html( sprintf( __( 'آخرین بروزرسانی: %s', 'noble-theme' ), $updated_at ) ); ?></div>
		</header>

		<article class="noble-legal-content rounded-3xl border border-primary/10 bg-white p-5 sm:p-8">
			<section>
				<h2><?php esc_html_e( '۱) اطلاعاتی که جمع‌آوری می‌کنیم', 'noble-theme' ); ?></h2>
				<p><?php esc_html_e( 'برای ثبت و پردازش سفارش، اطلاعاتی مانند نام، شماره تماس، آدرس، اطلاعات پرداخت و سوابق خرید جمع‌آوری می‌شود.', 'noble-theme' ); ?></p>
			</section>

			<section>
				<h2><?php esc_html_e( '۲) نحوه استفاده از اطلاعات', 'noble-theme' ); ?></h2>
				<ul>
					<li><?php esc_html_e( 'پردازش سفارش و ارسال کالا', 'noble-theme' ); ?></li>
					<li><?php esc_html_e( 'ارائه پشتیبانی و پاسخگویی به کاربران', 'noble-theme' ); ?></li>
					<li><?php esc_html_e( 'بهبود تجربه کاربری و کیفیت خدمات', 'noble-theme' ); ?></li>
				</ul>
			</section>

			<section>
				<h2><?php esc_html_e( '۳) کوکی‌ها و داده‌های فنی', 'noble-theme' ); ?></h2>
				<p><?php esc_html_e( 'برای حفظ سبد خرید، امنیت نشست کاربری و بهبود عملکرد سایت، از کوکی‌ها و داده‌های فنی ضروری استفاده می‌شود.', 'noble-theme' ); ?></p>
			</section>

			<section>
				<h2><?php esc_html_e( '۴) اشتراک‌گذاری اطلاعات', 'noble-theme' ); ?></h2>
				<p><?php esc_html_e( 'اطلاعات کاربران به اشخاص ثالث فروخته نمی‌شود. فقط در حد لازم برای پردازش سفارش (مانند شرکت‌های حمل‌ونقل یا درگاه پرداخت) داده‌ها منتقل می‌گردد.', 'noble-theme' ); ?></p>
			</section>

			<section>
				<h2><?php esc_html_e( '۵) امنیت اطلاعات', 'noble-theme' ); ?></h2>
				<p><?php esc_html_e( 'نوبل از اقدامات فنی و اجرایی مناسب برای محافظت از اطلاعات کاربران استفاده می‌کند. با این حال، کاربران نیز باید در حفظ اطلاعات حساب خود دقت کنند.', 'noble-theme' ); ?></p>
			</section>

			<section>
				<h2><?php esc_html_e( '۶) حقوق کاربران', 'noble-theme' ); ?></h2>
				<p><?php esc_html_e( 'کاربران می‌توانند برای ویرایش یا حذف اطلاعات حساب خود و دریافت توضیحات بیشتر، از طریق صفحه تماس با ما با تیم پشتیبانی ارتباط بگیرند.', 'noble-theme' ); ?></p>
			</section>

			<?php if ( have_posts() ) : ?>
				<?php while ( have_posts() ) : the_post(); ?>
					<?php if ( '' !== trim( (string) get_the_content() ) ) : ?>
						<section class="noble-legal-content__custom">
							<?php the_content(); ?>
						</section>
					<?php endif; ?>
				<?php endwhile; ?>
			<?php endif; ?>
		</article>
	</div>
</section>
<?php
get_footer();
