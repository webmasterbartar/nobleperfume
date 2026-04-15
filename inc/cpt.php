<?php
/**
 * CPT and taxonomy registration.
 *
 * @package noble-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function noble_register_cpt() {
	register_post_type(
		'noble_story',
		array(
			'labels'       => array(
				'name'          => __( 'Stories', 'noble-theme' ),
				'singular_name' => __( 'Story', 'noble-theme' ),
			),
			'public'       => true,
			'has_archive'  => true,
			'show_in_rest' => true,
			'menu_icon'    => 'dashicons-format-aside',
			'supports'     => array( 'title', 'editor', 'excerpt', 'thumbnail' ),
			'rewrite'      => array( 'slug' => 'stories' ),
		)
	);

	register_taxonomy(
		'noble_story_topic',
		'noble_story',
		array(
			'labels'       => array(
				'name'          => __( 'Story Topics', 'noble-theme' ),
				'singular_name' => __( 'Story Topic', 'noble-theme' ),
			),
			'public'       => true,
			'hierarchical' => true,
			'show_in_rest' => true,
			'rewrite'      => array( 'slug' => 'story-topic' ),
		)
	);
}
add_action( 'init', 'noble_register_cpt' );
