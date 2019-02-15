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
		// Create a new, empty array ($items_sorted) whose keys
		// are the term IDs we need to sort against:
		$term_slugs = unserialize( ONLINE_DEGREE_PROGRAM_ORDER );
		$term_ids   = array();
		foreach ( $term_slugs as $term_slug ) {
			$term_obj = get_term_by( 'slug', $term_slug, 'program_types' );
			if ( $term_obj && isset( $term_obj->term_id ) ) {
				$term_ids[] = $term_obj->term_id;
			}
		}
		$items_sorted = array_fill_keys( $term_ids, false );

		// Perform sorting for each term. Add any terms we don't have
		// an explicit sorting rule for to the end of $items_sorted.
		foreach ( $terms as $key => $term ) {
			// Depending on what $args['fields'] is, $term may be a
			// WP_Term obj, term name, term slug, or term ID.
			// Get the term ID based on whatever $key or $term are:
			$term_id = null;

			switch ( $args['fields'] ) {
				case 'all':
				case 'all_with_object_id':
					$term_id = $term->term_id;
					break;
				case 'ids':
					$term_id = $term;
					break;
				case 'names':
					$term_obj = get_term_by( 'name', $term, 'program_types' );
					if ( $term_obj && isset( $term_obj->term_id ) ) {
						$term_id = $term_obj->term_id;
					}
					break;
				case 'id=>name':
				case 'id=>slug':
					$term_id = $key;
					break;
				default:
					break;
			}

			// If we know the term ID, we can sort the term in the
			// desired order. Else, it goes to the end of the list:
			if ( $term_id ) {
				$items_sorted[$term_id] = $term;
			}
			else {
				// Preserve existing keys if necessary:
				if ( in_array( $args['fields'], array( 'id=>name', 'id=>slug' ) ) ) {
					$items_sorted[$key] = $term;
				}
				else {
					$items_sorted[] = $term;
				}
			}
		}

		// Remove term_id-based keys if the requested returned fields
		// ($args['fields']) doesn't specify using them
		if ( ! in_array( $args['fields'], array( 'id=>name', 'id=>slug' ) ) ) {
			$items_sorted = array_values( $items_sorted );
		}

		// Remove any empty sorted items
		$items_sorted = array_filter( $items_sorted );

		$terms = $items_sorted;
	}
	return $terms;
}

add_filter( 'get_terms', 'online_program_types_terms_sorting', 10, 4 );


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
		if ( has_category( 'Resources', $post ) ) {
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
	$resources_posts = get_posts( array(
		'post_type' => 'post',
		'numberposts' => -1,
		'category_name' => 'resources'
	) );

	if ( $resources_posts ) {
		foreach ( $resources_posts as $resources_post ) {
			add_rewrite_rule( 'student-resources/([^/]+)/?$', 'index.php?post_type=post&name=$matches[1]', 'bottom' );
		}
	}
}

add_action( 'init', 'online_resources_cat_rewrite_rule', 10, 0 );
