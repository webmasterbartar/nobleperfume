<?php
/**
 * Single post template.
 *
 * @package noble-theme
 */

get_header();

while ( have_posts() ) :
	the_post();

	$post_categories = get_the_category();
	$reading_time    = max( 1, (int) ceil( str_word_count( wp_strip_all_tags( get_the_content() ) ) / 220 ) );
	$thumb_url       = has_post_thumbnail() ? get_the_post_thumbnail_url( get_the_ID(), 'full' ) : '';
	$author_name     = get_the_author();
	?>
	<section class="noble-blog-single-page bg-background min-h-screen pb-16">
		<div class="container mx-auto px-5 sm:px-6 lg:px-8 pt-8">
			<?php noble_breadcrumbs(); ?>

			<article <?php post_class( 'noble-blog-single-article' ); ?>>
				<header class="noble-blog-single-hero rounded-3xl border border-primary/10 p-6 sm:p-8 mb-8">
					<div class="noble-blog-single-hero__meta">
						<span><?php echo esc_html( get_the_date() ); ?></span>
						<span>•</span>
						<span><?php echo esc_html( sprintf( __( '%d دقیقه مطالعه', 'noble-theme' ), $reading_time ) ); ?></span>
						<?php if ( ! empty( $post_categories ) && isset( $post_categories[0]->name ) ) : ?>
							<span>•</span>
							<span><?php echo esc_html( (string) $post_categories[0]->name ); ?></span>
						<?php endif; ?>
					</div>
					<h1 class="noble-blog-single-hero__title"><?php the_title(); ?></h1>
					<div class="noble-blog-single-hero__author">
						<span class="material-symbols-outlined" aria-hidden="true">person</span>
						<span><?php echo esc_html( sprintf( __( 'نویسنده: %s', 'noble-theme' ), $author_name ) ); ?></span>
					</div>
				</header>

				<?php if ( $thumb_url ) : ?>
					<div class="noble-blog-single-cover mb-8">
						<?php the_post_thumbnail( 'large', array( 'loading' => 'eager' ) ); ?>
					</div>
				<?php endif; ?>

				<div class="noble-blog-single-content rounded-3xl border border-primary/10 bg-white p-5 sm:p-8">
					<?php the_content(); ?>
				</div>
			</article>

			<nav class="noble-blog-single-nav mt-8">
				<div class="noble-blog-single-nav__item noble-blog-single-nav__item--prev">
					<?php previous_post_link( '%link', '<span class="material-symbols-outlined" aria-hidden="true">arrow_forward</span> ' . esc_html__( 'مقاله قبلی', 'noble-theme' ) ); ?>
				</div>
				<div class="noble-blog-single-nav__item noble-blog-single-nav__item--next">
					<?php next_post_link( '%link', esc_html__( 'مقاله بعدی', 'noble-theme' ) . ' <span class="material-symbols-outlined" aria-hidden="true">arrow_back</span>' ); ?>
				</div>
			</nav>

			<?php noble_related_posts(); ?>
		</div>
	</section>
<?php endwhile; get_footer(); ?>
