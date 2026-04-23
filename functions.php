<?php
/**
 * Theme bootstrap file.
 *
 * @package noble-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$noble_includes = array(
	'/inc/cache.php',
	'/inc/setup.php',
	'/inc/enqueue.php',
	'/inc/template-tags.php',
	'/inc/acf.php',
	'/inc/cpt.php',
	'/inc/woocommerce.php',
	'/inc/quiz.php',
	'/inc/gift-box-builder.php',
	'/inc/static-pages.php',
	'/inc/demo-seeder.php',
	'/inc/seo.php',
);

foreach ( $noble_includes as $file ) {
	$path = get_template_directory() . $file;
	if ( file_exists( $path ) ) {
		require_once $path;
	}
}
