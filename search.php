<?php
/**
 * Search results template.
 *
 * @package noble-theme
 */

get_header();
noble_breadcrumbs();
?>
<header class="mb-8">
	<h1 class="text-3xl font-bold">
		<?php
		printf( esc_html__( 'نتایج جستجو برای: %s', 'noble-theme' ), '<span>' . esc_html( get_search_query() ) . '</span>' );
		?>
	</h1>
</header>
<?php if ( have_posts() ) : ?>
	<div class="space-y-6">
		<?php while ( have_posts() ) : the_post(); ?>
			<?php get_template_part( 'template-parts/components/content', 'search' ); ?>
		<?php endwhile; ?>
	</div>
	<?php the_posts_pagination(); ?>
<?php else : ?>
	<?php get_template_part( 'template-parts/components/content', 'none' ); ?>
<?php endif; ?>
<?php get_footer(); ?>
