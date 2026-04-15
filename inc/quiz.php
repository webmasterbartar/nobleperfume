<?php
/**
 * Quiz route (/quiz/) without requiring a WP Page.
 *
 * @package noble-theme
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register rewrite rule for /quiz/.
 */
function noble_register_quiz_route() {
	add_rewrite_rule( '^quiz/?$', 'index.php?noble_quiz=1', 'top' );
}
add_action( 'init', 'noble_register_quiz_route' );

/**
 * Add custom query var.
 *
 * @param array<int,string> $vars Query vars.
 * @return array<int,string>
 */
function noble_add_quiz_query_var( $vars ) {
	$vars[] = 'noble_quiz';
	return $vars;
}
add_filter( 'query_vars', 'noble_add_quiz_query_var' );

/**
 * Load quiz template when route matches.
 *
 * @param string $template Template path.
 * @return string
 */
function noble_quiz_template_include( $template ) {
	if ( (int) get_query_var( 'noble_quiz' ) === 1 ) {
		$quiz_template = get_template_directory() . '/page-quiz.php';
		if ( file_exists( $quiz_template ) ) {
			return $quiz_template;
		}
	}
	return $template;
}
add_filter( 'template_include', 'noble_quiz_template_include', 99 );

/**
 * Flush rewrite rules once after route is introduced.
 */
function noble_quiz_maybe_flush_rewrite() {
	if ( get_option( 'noble_quiz_route_flushed' ) ) {
		return;
	}
	flush_rewrite_rules( false );
	update_option( 'noble_quiz_route_flushed', 1 );
}
add_action( 'admin_init', 'noble_quiz_maybe_flush_rewrite' );

