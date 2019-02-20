<?php

/**
 * Overrides the UCF WordPress Theme function for returning a site nav
 * type to always return either the simplified landing page nav or
 * the default nav (the main site nav should never be used in this theme.)
 *
 * @author Jo Dickson
 * @since 1.2.0
 * @param string $nav_type The determined nav type name
 * @return string The nav type name
 */
function online_get_nav_type( $nav_type ) {
	global $post;
	if ( $post && $post->post_type === 'landing' ) {
		return 'landing';
	}

	return '';
}

add_filter( 'ucfwp_get_nav_type', 'online_get_nav_type', 10, 1 );


/**
 * Displays the primary site navigation for UCF Online.
 *
 * NOTE: This function intentionally echoes its output, rather than
 * returning a string, because we register this function as an action on the
 * `after_body_open` hook.
 *
 * @author Jo Dickson
 * @since 1.0.0
 * @return void
 **/
function online_nav_markup() {
	echo ucfwp_get_nav_markup( false );
}

add_action( 'after_body_open', 'online_nav_markup', 10, 0 );
