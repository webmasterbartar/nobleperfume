<?php
/**
 * Search results template.
 *
 * @package noble-theme
 */

get_header();

$search_term = get_search_query();
$paged       = max( 1, (int) get_query_var( 'paged' ), (int) get_query_var( 'page' ) );
$per_page    = function_exists( 'wc_get_default_products_per_row' ) ? 12 : 12;

$products_query = new WP_Query(
	array(
		'post_type'           => 'product',
		'post_status'         => 'publish',
		's'                   => $search_term,
		'posts_per_page'      => $per_page,
		'paged'               => $paged,
		'ignore_sticky_posts' => true,
	)
);
?>
<section class="shop-page-wrap min-h-screen bg-background pb-16 pt-0">
	<main class="pt-0">
		<div class="container mx-auto px-5 py-8 sm:px-6 lg:px-8">
			<div class="mb-8 rounded-2xl border border-white/60 bg-white/70 p-4 sm:p-5 backdrop-blur-sm">
				<div class="shop-toolbar flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
					<div class="shop-breadcrumb flex items-center gap-2 text-xs text-on-surface-variant">
						<a class="hover:text-primary" href="<?php echo esc_url( home_url( '/' ) ); ?>">خانه</a>
						<span class="material-symbols-outlined text-[12px] opacity-40">chevron_left</span>
						<span class="font-bold text-primary">
							<?php
							printf(
								esc_html__( 'نتایج جستجو: %s', 'noble-theme' ),
								esc_html( $search_term )
							);
							?>
						</span>
					</div>
					<div class="shop-toolbar-meta flex items-center gap-4 sm:gap-6">
						<div class="shop-toolbar-count text-[10px] font-bold uppercase tracking-[0.16em] text-on-surface-variant/70">
							<?php echo esc_html( number_format_i18n( (int) $products_query->found_posts ) ); ?> محصول
						</div>
					</div>
				</div>
			</div>

			<?php if ( $products_query->have_posts() ) : ?>
				<style>
				.noble-grid-enforcer {
					display: grid !important;
					grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
					gap: 12px !important;
				}
				.noble-grid-enforcer::before, .noble-grid-enforcer::after { content: none !important; }
				@media (min-width: 768px) { .noble-grid-enforcer { grid-template-columns: repeat(3, minmax(0, 1fr)) !important; gap: 24px !important; } }
				@media (min-width: 1024px) { .noble-grid-enforcer { grid-template-columns: repeat(4, minmax(0, 1fr)) !important; gap: 32px !important; } }
				.noble-grid-enforcer li.product { width: 100% !important; margin: 0 !important; max-width: none !important; clear: none !important; float: none !important; }
				</style>

				<ul class="products noble-grid-enforcer" aria-label="نتایج محصولات">
					<?php
					while ( $products_query->have_posts() ) :
						$products_query->the_post();
						wc_get_template_part( 'content', 'product' );
					endwhile;
					?>
				</ul>

				<div class="shop-custom-pagination mt-12 mb-6 flex justify-center gap-3">
					<?php
					echo wp_kses_post(
						paginate_links(
							array(
								'base'      => esc_url_raw( add_query_arg( 'paged', '%#%' ) ),
								'format'    => '',
								'current'   => $paged,
								'total'     => max( 1, (int) $products_query->max_num_pages ),
								'mid_size'  => 1,
								'end_size'  => 1,
								'type'      => 'list',
								'prev_text' => '<span class="material-symbols-outlined text-[18px]">chevron_right</span>',
								'next_text' => '<span class="material-symbols-outlined text-[18px]">chevron_left</span>',
							)
						)
					);
					?>
				</div>
			<?php else : ?>
				<div class="rounded-2xl border border-border-light bg-white p-8 text-center">
					<h2 class="mb-2 text-lg font-bold text-primary">محصولی پیدا نشد</h2>
					<p class="text-sm text-on-surface-variant">لطفا عبارت دیگری را جستجو کنید یا از دسته‌بندی‌های فروشگاه استفاده کنید.</p>
				</div>
			<?php endif; ?>
			<?php wp_reset_postdata(); ?>
		</div>
	</main>
</section>
<?php get_footer(); ?>
