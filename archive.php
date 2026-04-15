<?php
/**
 * Archive template.
 *
 * @package noble-theme
 */

get_header();
noble_breadcrumbs();
?>
<header class="mb-8">
	<h1 class="text-3xl font-bold"><?php the_archive_title(); ?></h1>
	<?php the_archive_description( '<div class="mt-2 text-slate-600">', '</div>' ); ?>
</header>
<?php if ( have_posts() ) : ?>
	<div class="grid gap-6 md:grid-cols-2">
		<?php while ( have_posts() ) : the_post(); ?>
			<?php get_template_part( 'template-parts/components/content', get_post_type() ); ?>
		<?php endwhile; ?>
	</div>
	<?php the_posts_pagination(); ?>
<?php else : ?>
	<?php get_template_part( 'template-parts/components/content', 'none' ); ?>
<?php endif; ?>
<?php get_footer(); ?>
