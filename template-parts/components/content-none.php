<?php
/**
 * Empty state template.
 *
 * @package noble-theme
 */
?>
<section class="rounded-lg border bg-white p-8 text-center">
	<h2 class="mb-2 text-2xl font-semibold"><?php esc_html_e( 'Nothing found', 'noble-theme' ); ?></h2>
	<p class="mb-4 text-slate-600"><?php esc_html_e( 'No content matched your request.', 'noble-theme' ); ?></p>
	<?php get_search_form(); ?>
</section>
