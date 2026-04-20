<?php
/**
 * Page template.
 *
 * @package noble-theme
 */

get_header();

$noble_is_checkout_page = function_exists( 'is_checkout' ) && is_checkout();

if ( ! $noble_is_checkout_page ) {
	noble_breadcrumbs();
}

while ( have_posts() ) :
	the_post();
	?>
	<article <?php post_class( 'prose max-w-none' ); ?>>
		<?php if ( ! $noble_is_checkout_page ) : ?>
			<h1><?php the_title(); ?></h1>
		<?php endif; ?>
		<?php the_content(); ?>
	</article>
<?php endwhile; get_footer(); ?>
