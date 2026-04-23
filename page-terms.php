<?php
/**
 * Terms and conditions page template.
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
			<span><?php esc_html_e( 'قوانین و مقررات', 'noble-theme' ); ?></span>
		</nav>

		<header class="noble-legal-hero rounded-3xl border border-primary/10 p-6 sm:p-8 lg:p-10 mb-8">
			<div class="noble-legal-hero__eyebrow"><?php esc_html_e( 'Legal', 'noble-theme' ); ?></div>
			<h1 class="noble-legal-hero__title"><?php esc_html_e( 'قوانین و مقررات استفاده از نوبل', 'noble-theme' ); ?></h1>
			<p class="noble-legal-hero__desc"><?php esc_html_e( 'استفاده از خدمات نوبل به معنای پذیرش مفاد زیر است. لطفا پیش از خرید، موارد را با دقت مطالعه کنید.', 'noble-theme' ); ?></p>
			<div class="noble-legal-hero__meta"><?php echo esc_html( sprintf( __( 'آخرین بروزرسانی: %s', 'noble-theme' ), $updated_at ) ); ?></div>
		</header>

		<article class="noble-legal-content rounded-3xl border border-primary/10 bg-white p-5 sm:p-8">
			<section>
				<h2><?php esc_html_e( '۱) کلیات', 'noble-theme' ); ?></h2>
				<p><?php esc_html_e( 'تمام فعالیت‌های نوبل مطابق قوانین تجارت الکترونیک جمهوری اسلامی ایران انجام می‌شود. کاربران نیز متعهد هستند از خدمات سایت در چارچوب قانون استفاده کنند.', 'noble-theme' ); ?></p>
			</section>

			<section>
				<h2><?php esc_html_e( '۲) ثبت سفارش و پرداخت', 'noble-theme' ); ?></h2>
				<ul>
					<li><?php esc_html_e( 'ثبت سفارش به معنی رزرو قطعی کالا نیست و تا پیش از نهایی‌سازی پرداخت، امکان تغییر قیمت یا موجودی وجود دارد.', 'noble-theme' ); ?></li>
					<li><?php esc_html_e( 'پس از پرداخت موفق و تایید نهایی، سفارش وارد فرایند آماده‌سازی می‌شود.', 'noble-theme' ); ?></li>
					<li><?php esc_html_e( 'مسئولیت صحت اطلاعات ثبت‌شده مانند نام، شماره تماس و آدرس بر عهده کاربر است.', 'noble-theme' ); ?></li>
				</ul>
			</section>

			<section>
				<h2><?php esc_html_e( '۳) ارسال سفارش', 'noble-theme' ); ?></h2>
				<p><?php esc_html_e( 'زمان‌بندی ارسال بسته به مقصد، روش انتخابی ارسال و شرایط روزهای کاری متفاوت است. نوبل همواره تلاش می‌کند سفارش‌ها در کوتاه‌ترین زمان ممکن ارسال شوند.', 'noble-theme' ); ?></p>
			</section>

			<section>
				<h2><?php esc_html_e( '۴) مرجوعی و خدمات پس از خرید', 'noble-theme' ); ?></h2>
				<p><?php esc_html_e( 'شرایط مرجوعی مطابق سیاست‌های بهداشتی و نوع محصول تعیین می‌شود. در صورت وجود مغایرت یا مشکل در سفارش، کاربر باید در کوتاه‌ترین زمان با پشتیبانی تماس بگیرد.', 'noble-theme' ); ?></p>
			</section>

			<section>
				<h2><?php esc_html_e( '۵) مالکیت فکری', 'noble-theme' ); ?></h2>
				<p><?php esc_html_e( 'تمام محتوای سایت شامل متن، تصویر، هویت بصری و ساختار فنی متعلق به نوبل است و هرگونه استفاده بدون مجوز کتبی ممنوع است.', 'noble-theme' ); ?></p>
			</section>

			<section>
				<h2><?php esc_html_e( '۶) تغییرات قوانین', 'noble-theme' ); ?></h2>
				<p><?php esc_html_e( 'نوبل می‌تواند در هر زمان این قوانین را بروزرسانی کند. نسخه جدید از زمان انتشار در همین صفحه معتبر خواهد بود.', 'noble-theme' ); ?></p>
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
