<?php
/**
 * Search result item.
 *
 * @package noble-theme
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'rounded-lg border bg-white p-5' ); ?>>
	<h2 class="text-xl font-semibold"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
	<p class="mt-2 text-slate-600"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 28 ) ); ?></p>
</article>
