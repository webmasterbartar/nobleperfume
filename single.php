<?php
/**
 * Single post template.
 *
 * @package noble-theme
 */

get_header();
noble_breadcrumbs();
while ( have_posts() ) :
	the_post();
	?>
	<article <?php post_class( 'prose max-w-none' ); ?>>
		<h1><?php the_title(); ?></h1>
		<?php the_content(); ?>
	</article>
	<?php noble_related_posts(); ?>
<?php endwhile; get_footer(); ?>
