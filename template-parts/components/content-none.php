<?php
/**
 * Empty state template.
 *
 * @package noble-theme
 */
?>
<section class="rounded-2xl border border-primary/10 bg-white p-8 text-center">
	<h2 class="mb-2 text-2xl font-semibold text-primary"><?php esc_html_e( 'محتوایی پیدا نشد', 'noble-theme' ); ?></h2>
	<p class="mb-4 text-on-surface-variant"><?php esc_html_e( 'موردی مطابق جستجو یا فیلتر شما وجود ندارد. عبارت دیگری را امتحان کنید.', 'noble-theme' ); ?></p>
	<?php get_search_form(); ?>
</section>
