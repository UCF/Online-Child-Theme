<?php
/**
 * Filter permalinks for standard posts that are assigned to the 'Resources' category.
 *
 * Posts with an assigned category of 'Resources' should have "student-resources/"
 * prefixed before the post's slug, e.g.
 * "www.ucf.edu/online/student-resources/post-slug/".
 *
 *
 * @author Cadie Brown
 * @since 1.1.0
 */

function online_resources_cat_post_replace_link( $url, $post ) {
	if ( $post && $post->post_type === 'post' ) {
		if ( has_category( 'resources', $post ) ) {
			return home_url( 'student-resources/' . $post->post_name );
		} else {
			return $url;
		}
	} else {
		return $url;
	}
}

add_filter( 'post_link', 'online_resources_cat_post_replace_link', 1, 2 );


/**
 * Adds permalink rewrite rules for all posts that are assigned to the 'Resources' category.
 *
 *
 * @see online_resources_cat_post_replace_link()
 * @author Cadie Brown
 * @since 1.1.0
 */

function online_resources_cat_rewrite_rule() {
	$resources = get_posts( array(
		'post_type'     => 'post',
		'numberposts'   => -1,
		'category_name' => 'resources'
	) );

	if ( $resources ) {
		foreach ( $resources as $resources ) {
			add_rewrite_rule( 'student-resources/([^/]+)/?$', 'index.php?post_type=post&name=$matches[1]', 'bottom' );
		}
	}
}

add_action( 'init', 'online_resources_cat_rewrite_rule', 10, 0 );
