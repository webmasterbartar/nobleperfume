<?php
/**
 * Theme helper tags.
 *
 * @package noble-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function noble_breadcrumbs() {
	if ( is_front_page() ) {
		return;
	}

	echo '<nav class="noble-breadcrumbs text-sm mb-6" aria-label="' . esc_attr__( 'مسیر صفحه', 'noble-theme' ) . '">';
	echo '<a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'خانه', 'noble-theme' ) . '</a>';

	if ( is_singular() ) {
		echo ' / <span>' . esc_html( get_the_title() ) . '</span>';
	} elseif ( is_archive() ) {
		echo ' / <span>' . esc_html( post_type_archive_title( '', false ) ) . '</span>';
	} elseif ( is_search() ) {
		echo ' / <span>' . esc_html__( 'جستجو', 'noble-theme' ) . '</span>';
	}

	echo '</nav>';
}

function noble_related_posts( $post_id = 0, $limit = 3 ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	if ( ! $post_id ) {
		return;
	}

	$terms = wp_get_post_terms( $post_id, 'category', array( 'fields' => 'ids' ) );
	if ( empty( $terms ) ) {
		return;
	}

	$cache_key = 'noble_related_' . $post_id;
	$cached    = get_transient( $cache_key );
	if ( false !== $cached ) {
		echo $cached; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		return;
	}

	$query = new WP_Query(
		array(
			'post_type'           => 'post',
			'posts_per_page'      => $limit,
			'post__not_in'        => array( $post_id ),
			'ignore_sticky_posts' => true,
			'tax_query'           => array(
				array(
					'taxonomy' => 'category',
					'field'    => 'term_id',
					'terms'    => $terms,
				),
			),
		)
	);

	ob_start();
	if ( $query->have_posts() ) {
		echo '<section class="noble-related-posts mt-10"><h3 class="text-xl mb-4">' . esc_html__( 'مطالب مرتبط', 'noble-theme' ) . '</h3><div class="grid gap-4 md:grid-cols-3">';
		while ( $query->have_posts() ) {
			$query->the_post();
			echo '<article class="border p-4 rounded"><a href="' . esc_url( get_permalink() ) . '">' . esc_html( get_the_title() ) . '</a></article>';
		}
		echo '</div></section>';
	}
	wp_reset_postdata();

	$html = ob_get_clean();
	set_transient( $cache_key, $html, HOUR_IN_SECONDS );
	echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

function noble_default_primary_menu() {
	echo '<ul class="flex items-center gap-8">';
	echo '<li><a class="nav-link active text-primary font-bold" href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'خانه', 'noble-theme' ) . '</a></li>';
	echo '<li><a class="nav-link text-slate-500 font-medium hover:text-primary" href="' . esc_url( home_url( '/shop/' ) ) . '">' . esc_html__( 'فروشگاه', 'noble-theme' ) . '</a></li>';
	echo '<li><a class="nav-link text-slate-500 font-medium hover:text-primary" href="' . esc_url( home_url( '/blog/' ) ) . '">' . esc_html__( 'مجله', 'noble-theme' ) . '</a></li>';
	echo '</ul>';
}

function noble_default_footer_menu() {
	echo '<ul class="flex gap-6">';
	echo '<li><a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'خانه', 'noble-theme' ) . '</a></li>';
	echo '<li><a href="tel:09121551396">' . esc_html__( 'تماس با ما', 'noble-theme' ) . '</a></li>';
	echo '</ul>';
}
