<?php
/**
 * Generic content card.
 *
 * @package noble-theme
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'rounded-lg border bg-white p-6' ); ?>>
	<h2 class="mb-2 text-2xl font-semibold">
		<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
	</h2>
	<div class="text-sm text-slate-500"><?php echo esc_html( get_the_date() ); ?></div>
	<div class="mt-4"><?php the_excerpt(); ?></div>
</article>
