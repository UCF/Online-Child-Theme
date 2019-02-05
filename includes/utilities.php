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


/**
 * Filter for get_terms hook that, by default, sorts any
 * program_types term queries by the ONLINE_DEGREE_PROGRAM_ORDER sort order.
 *
 * Allows program types displayed in the degree picker interface to be
 * sorted as expected. Also applies anywhere get_terms() is called with
 * params that call for sorted term results.
 *
 * @author Jo Dickson
 * @since 1.0.0
 */
function online_program_types_terms_sorting( $terms, $taxonomies, $args, $term_query ) {
	// Only perform sorting if this is a query exclusively for
	// program_types terms that are sortable (e.g. not a request
	// for a term count or parent terms)
	if (
		$taxonomies === array( 'program_types' )
		&& in_array( $args['fields'], array( 'all', 'all_with_object_id', 'ids', 'names', 'id=>name', 'id=>slug' ) )
	) {
		// Create a new, empty assoc. array ($items_sorted) whose keys
		// are the term slugs we need to sort against:
		$term_slugs = unserialize( ONLINE_DEGREE_PROGRAM_ORDER );
		$items_sorted = array_fill_keys( $term_slugs, false );

		// Perform sorting for each term. Add any terms we don't have
		// an explicit sorting rule for to the end of $items_sorted.
		foreach ( $terms as $key => $term ) {
			// Depending on what $args['fields'] is, $term may be a
			// WP_Term obj, term name, term slug, or term ID.
			// Determine the term slug based on whatever $term is:
			$term_slug = '';
			$term_obj  = null;

			switch ( $args['fields'] ) {
				case 'all':
				case 'all_with_object_id':
					$term_slug = $term->slug;
					break;
				case 'ids':
					$term_obj = get_term( $term, 'program_types' );
					break;
				case 'names':
					$term_obj = get_term_by( 'name', $term, 'program_types' );
					break;
				case 'id=>name':
					$term_obj = get_term( $key, 'program_types' );
					break;
				case 'id=>slug':
					$term_slug = $term;
					break;
				default:
					break;
			}

			if ( ! $term_slug && $term_obj && isset( $term_obj->slug ) ) {
				$term_slug = $term_obj->slug;
			}

			// If we know the term slug, we can sort the term in the
			// desired order. Else, it goes to the end of the list:
			if ( $term_slug ) {
				$items_sorted[$term_slug] = $term;
			}
			else {
				$items_sorted[] = $term;
			}
		}

		// Replace associative keys with numeric keys
		$items_sorted = array_values( $items_sorted );

		// Remove any empty sorted items
		$items_sorted = array_filter( $items_sorted );

		$terms = $items_sorted;
	}
	return $terms;
}

add_filter( 'get_terms', 'online_program_types_terms_sorting', 10, 4 );
