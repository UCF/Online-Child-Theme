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
	wp_enqueue_script( 'script-child', ONLINE_THEME_JS_URL . '/script.min.js', array( 'jquery', 'script' ), $theme_version, true );

	wp_register_script( 'typeaheadjs', 'https://cdnjs.cloudflare.com/ajax/libs/corejs-typeahead/1.0.1/typeahead.bundle.min.js', array( 'jquery' ), null, false );
	wp_register_script( 'handlebars', 'https://cdnjs.cloudflare.com/ajax/libs/handlebars.js/4.0.6/handlebars.min.js', null, null, false );

	global $post;

	if ( $post && $post->post_type === 'landing' ) {
		wp_deregister_script( 'ucf-header' );
	}
}

add_action( 'wp_enqueue_scripts', 'online_enqueue_frontend_assets', 11, 0 );


/**
 * Modify settings for supported plugins to prevent duplicate registration and
 * enqueuing of assets.
 *
 * Adapted from Online-Theme
 */

function online_post_list_js_deps( $deps ) {
	return array( 'jquery', 'typeaheadjs', 'handlebars' );
}

add_filter( 'ucf_post_list_js_deps', 'online_post_list_js_deps', 10, 1 );


if ( filter_var( get_option( 'ucf_post_list_include_js_libs' ), FILTER_VALIDATE_BOOLEAN ) !== false ) {
	update_option( 'ucf_post_list_include_js_libs', false );
}
if ( filter_var( get_option( 'ucf_post_list_include_js' ), FILTER_VALIDATE_BOOLEAN ) !== true ) {
	update_option( 'ucf_post_list_include_js', true );
}
if ( filter_var( get_option( 'ucf_degree_search_include_typeahead' ), FILTER_VALIDATE_BOOLEAN ) !== false ) {
	update_option( 'ucf_degree_search_include_typeahead', false );
}
