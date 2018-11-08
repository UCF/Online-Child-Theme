<?php
/**
 * General utilities
 **/

/**
 * Returns a Online Child Theme mod's default value.
 *
 * @since 1.0.0
 * @param string $theme_mod The name of the theme mod
 * @return mixed Theme mod default value, or false if a default is not set
 **/
function online_get_theme_mod_default( $theme_mod ) {
	return ucfwp_get_theme_mod_default( $theme_mod, ONLINE_THEME_CUSTOMIZER_DEFAULTS );
}


/**
 * Returns a Online Child Theme mod value or a default value
 * if the theme mod value hasn't been set yet.
 *
 * @since 1.0.0
 * @param string $theme_mod The name of the theme mod
 * @return mixed Theme mod value or its default
 **/
function online_get_theme_mod_or_default( $theme_mod ) {
	return ucfwp_get_theme_mod_or_default( $theme_mod, ONLINE_THEME_CUSTOMIZER_DEFAULTS );
}


/**
 * Helper function that returns a list of form ids + titles from Gravity Forms.
 *
 * @since 1.0.0
 * @return array
 **/
function online_get_gf_choices() {
	$retval = array();

	// only proceed if Gravity Forms is enabled.
	if ( class_exists( 'GFAPI' ) ) {

		// Get form data from Gravity Forms
		$forms = GFAPI::get_forms();
		foreach( $forms as $form ) {
			if ( $form['is_active'] === "1" && $form['is_trash'] === "0" ) {
				$retval[$form['id']] = $form['title'];
			}
		}

	}

	return $retval;
}


/**
 * Helper function that returns a list of all published post ids + titles
 * by post type for use in a <select> element's <option>s.
 *
 * @since 1.0.0
 * @author Jo Dickson
 * @return array
 */
function online_get_post_choices( $post_type='post', $args=array() ) {
	$retval = array();

	$posts = get_posts(
		array_merge(
			array(
				'post_type' => $post_type,
				'posts_per_page' => -1,
				'post_status' => 'publish'
			),
			$args
		)
	);

	if ( $posts ) {
		foreach( $posts as $p ) {
			$retval[$p->ID] = $p->post_title;
		}
	}

	return $retval;
}
