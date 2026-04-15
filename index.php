<?php
/**
 * Main index template.
 *
 * @package noble-theme
 */

get_header();
noble_breadcrumbs();
?>
<div class="space-y-8">
	<?php if ( have_posts() ) : ?>
		<?php while ( have_posts() ) : the_post(); ?>
			<?php get_template_part( 'template-parts/components/content', get_post_type() ); ?>
		<?php endwhile; ?>
		<?php the_posts_pagination(); ?>
	<?php else : ?>
		<?php get_template_part( 'template-parts/components/content', 'none' ); ?>
	<?php endif; ?>
</div>
<?php
get_footer();
