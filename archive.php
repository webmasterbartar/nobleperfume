<?php
/**
 * Archive template.
 *
 * @package noble-theme
 */

get_header();

$is_blog_archive = is_home() || is_category() || is_tag() || is_author() || is_date() || is_post_type_archive( 'post' );
$archive_title   = $is_blog_archive ? __( 'مجله نوبل', 'noble-theme' ) : get_the_archive_title();
$archive_desc    = $is_blog_archive ? __( 'مطالب کاربردی درباره انتخاب عطر، نت های بویایی و تجربه خرید حرفه ای.', 'noble-theme' ) : wp_strip_all_tags( get_the_archive_description() );

$categories = get_categories(
	array(
		'taxonomy'   => 'category',
		'hide_empty' => true,
		'number'     => 12,
	)
);
?>
<section class="noble-blog-archive-page bg-background min-h-screen pb-16">
	<div class="container mx-auto px-5 sm:px-6 lg:px-8 pt-8">
		<?php noble_breadcrumbs(); ?>

		<header class="noble-blog-archive-hero rounded-3xl border border-primary/10 p-6 sm:p-8 lg:p-10 mb-8">
			<div class="noble-blog-archive-hero__eyebrow"><?php esc_html_e( 'Noble Journal', 'noble-theme' ); ?></div>
			<h1 class="noble-blog-archive-hero__title"><?php echo esc_html( $archive_title ); ?></h1>
			<?php if ( '' !== trim( (string) $archive_desc ) ) : ?>
				<p class="noble-blog-archive-hero__desc"><?php echo esc_html( $archive_desc ); ?></p>
			<?php endif; ?>
		</header>

		<div class="noble-blog-archive-toolbar mb-7">
			<form class="noble-blog-search" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
				<input type="hidden" name="post_type" value="post">
				<label class="screen-reader-text" for="noble-blog-search-input"><?php esc_html_e( 'جستجو در مقالات', 'noble-theme' ); ?></label>
				<input id="noble-blog-search-input" type="search" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="<?php esc_attr_e( 'جستجو در مقالات...', 'noble-theme' ); ?>">
				<button type="submit"><?php esc_html_e( 'جستجو', 'noble-theme' ); ?></button>
			</form>

			<?php if ( ! empty( $categories ) ) : ?>
				<div class="noble-blog-cats" aria-label="<?php esc_attr_e( 'دسته بندی مقالات', 'noble-theme' ); ?>">
					<a class="noble-blog-cats__chip<?php echo is_home() ? ' is-active' : ''; ?>" href="<?php echo esc_url( get_post_type_archive_link( 'post' ) ? get_post_type_archive_link( 'post' ) : home_url( '/blog/' ) ); ?>">
						<?php esc_html_e( 'همه', 'noble-theme' ); ?>
					</a>
					<?php foreach ( $categories as $cat ) : ?>
						<a class="noble-blog-cats__chip<?php echo is_category( $cat->term_id ) ? ' is-active' : ''; ?>" href="<?php echo esc_url( get_category_link( $cat ) ); ?>">
							<?php echo esc_html( $cat->name ); ?>
						</a>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>

		<?php if ( have_posts() ) : ?>
			<div class="noble-blog-grid">
				<?php while ( have_posts() ) : the_post(); ?>
					<?php get_template_part( 'template-parts/components/content', get_post_type() ); ?>
				<?php endwhile; ?>
			</div>

			<?php
			$pagination = paginate_links(
				array(
					'type'      => 'list',
					'mid_size'  => 1,
					'end_size'  => 1,
					'prev_text' => '<span class="material-symbols-outlined" aria-hidden="true">chevron_right</span>',
					'next_text' => '<span class="material-symbols-outlined" aria-hidden="true">chevron_left</span>',
				)
			);
			if ( $pagination ) :
				?>
				<nav class="noble-blog-pagination mt-10" aria-label="<?php esc_attr_e( 'صفحه بندی مقالات', 'noble-theme' ); ?>">
					<?php echo wp_kses_post( $pagination ); ?>
				</nav>
			<?php endif; ?>
		<?php else : ?>
			<?php get_template_part( 'template-parts/components/content', 'none' ); ?>
		<?php endif; ?>
	</div>
</section>
<?php get_footer(); ?>
