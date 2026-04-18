<?php
/**
 * Header template.
 *
 * @package noble-theme
 */
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class( 'overflow-x-hidden bg-background text-on-surface' ); ?>>
<?php wp_body_open(); ?>
<?php get_template_part( 'template-parts/header/header-main' ); ?>
<main>
