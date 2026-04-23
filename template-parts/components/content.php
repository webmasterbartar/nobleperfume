<?php
/**
 * Generic content card.
 *
 * @package noble-theme
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'noble-blog-card' ); ?>>
	<a class="noble-blog-card__thumb" href="<?php the_permalink(); ?>" aria-label="<?php echo esc_attr( get_the_title() ); ?>">
		<?php if ( has_post_thumbnail() ) : ?>
			<?php the_post_thumbnail( 'large', array( 'loading' => 'lazy' ) ); ?>
		<?php else : ?>
			<span class="material-symbols-outlined" aria-hidden="true">article</span>
		<?php endif; ?>
	</a>
	<div class="noble-blog-card__body">
		<div class="noble-blog-card__meta">
			<span><?php echo esc_html( get_the_date() ); ?></span>
			<?php
			$cat_list = get_the_category();
			if ( ! empty( $cat_list ) && isset( $cat_list[0]->name ) ) :
				?>
				<span>•</span>
				<span><?php echo esc_html( (string) $cat_list[0]->name ); ?></span>
			<?php endif; ?>
		</div>
		<h2 class="noble-blog-card__title">
			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
		</h2>
		<div class="noble-blog-card__excerpt"><?php the_excerpt(); ?></div>
		<a class="noble-blog-card__more" href="<?php the_permalink(); ?>">
			<?php esc_html_e( 'مطالعه مقاله', 'noble-theme' ); ?>
			<span class="material-symbols-outlined" aria-hidden="true">arrow_back</span>
		</a>
	</div>
</article>
