<?php
/**
 * 404 template.
 *
 * @package noble-theme
 */

get_header();
?>
<section class="rounded-lg border bg-white p-10 text-center">
	<h1 class="mb-3 text-4xl font-bold"><?php esc_html_e( 'صفحه پیدا نشد', 'noble-theme' ); ?></h1>
	<p class="mb-6 text-slate-600"><?php esc_html_e( 'صفحه مورد نظر شما وجود ندارد.', 'noble-theme' ); ?></p>
	<a class="inline-flex rounded border px-4 py-2" href="<?php echo esc_url( home_url( '/' ) ); ?>">
		<?php esc_html_e( 'بازگشت به خانه', 'noble-theme' ); ?>
	</a>
</section>
<?php
get_footer();
