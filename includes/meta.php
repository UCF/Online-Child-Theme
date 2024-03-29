<?php

/**
 * Enqueue front-end css and js.
 *
 * @author Jo Dickson
 * @since 1.0.0
 **/
function online_enqueue_frontend_assets() {
	$theme = wp_get_theme();
	$theme_version = $theme->get( 'Version' );

	wp_enqueue_style( 'style-child', ONLINE_THEME_CSS_URL . '/style.min.css', array( 'style' ), $theme_version );
	wp_enqueue_script( 'script-child', ONLINE_THEME_JS_URL . '/script.min.js', array( 'jquery', 'script', 'ucf-degree-search-js', 'ucf-degree-picker-js' ), $theme_version, true );

	global $post;

	if ( $post && $post->post_type === 'landing' ) {
		wp_deregister_script( 'ucf-header' );
	}

	if ( $post && $post instanceof WP_Post && $optimizely_code = get_field( 'optimizely_code', $post->ID ) ) {
		wp_enqueue_script( 'optimizely-script', "https://cdn.optimizely.com/js/$optimizely_code.js", null, null, false );
	}
}

add_action( 'wp_enqueue_scripts', 'online_enqueue_frontend_assets', 11, 0 );
