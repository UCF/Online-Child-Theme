<?php

/**
 * Enqueue front-end css and js.
 *
 * @author Jo Dickson
 * @since 1.0.0
 **/
function online_enqueue_frontend_assets() {
	// Register child theme stylesheet
	$theme = wp_get_theme();
	$theme_version = $theme->get( 'Version' );
	wp_enqueue_style( 'style-child', ONLINE_THEME_CSS_URL . '/style.min.css', array( 'style' ), $theme_version );

	global $post;

	if ( $post->post_type === 'landing' ) {
		wp_deregister_script( 'ucf-header' );
	}
}

add_action( 'wp_enqueue_scripts', 'online_enqueue_frontend_assets', 11, 0 );
