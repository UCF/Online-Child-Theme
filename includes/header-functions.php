<?php


/**
 * Returns title text for use in the page header.
 **/
function get_header_title( $obj ) {
	if ( is_404() ) return '';

	$title = '';

	if ( is_tax() || is_category() || is_tag() ) {
		$title = $obj->name;
	}
	else if ( $obj instanceof WP_Post ) {
		$title = $obj->post_title;
	}

	// Apply custom header title override, if available
	if ( $custom_header_title = get_field( 'page_header_title', $obj ) ) {
		$title = do_shortcode( $custom_header_title );
	}

	return wptexturize( $title );
}


/**
 * Overrides how term/post object h1 text is determined in the parent theme.
 *
 * @author Jo Dickson
 * @since 1.0.0
 * @param string $title The determined page title string
 * @param object $obj A WP_Post or WP_Term object
 * @return string The modified page title string
 */
function online_get_header_title_after( $title, $obj ) {
	// Forces the parent theme to return an empty value for single Person
	// h1's.  Prevents duplicate h1's on the single Person template.
	if ( $obj instanceof WP_Post && $obj->post_type === 'person' ) {
		$title = '';
	}

	return $title;
}

add_filter( 'ucfwp_get_header_title_after', 'online_get_header_title_after', 10, 2 );


/**
 * Short-circuit this function from the UCF WordPress Theme to always
 * return 'title', since page header subtitles are not used in this theme.
 *
 * @author Jo Dickson
 * @since 1.0.0
 * @param object $obj A WP_Post or WP_Term object
 * @return string Option value for the designated page header h1
 **/
function ucfwp_get_header_h1_option( $obj ) {
	return 'title';
}


/**
 * Modifies how the UCF WordPress theme determines the header type to
 * use for the given object's page header.
 *
 * @author Jo Dickson
 * @since 1.2.0
 * @param string $header_type The determined header type slug (corresponds to a "header-" template part slug)
 * @param mixed $obj A queried object (e.g. WP_Post, WP_Term), or null
 * @return string The determined header type slug
 */
function online_get_header_type( $header_type, $obj ) {
	if ( $obj instanceof WP_Post && $obj->post_type === 'landing' ) {
		$header_type = 'landing';

		switch ( get_post_meta( $obj->ID, '_wp_page_template', true ) ) {
			case 'template-landing-2.php':
				$header_type .= '_2';
				break;
			case 'template-landing-3.php':
				$header_type .= '_3';
				break;
			case 'template-landing-custom.php':
				$header_type .= '_custom';
				break;
			case 'default':
			default:
				break;
		}
	}

	return $header_type;
}

add_filter( 'ucfwp_get_header_type', 'online_get_header_type', 10, 2 );


/**
 * Modifies how the UCF WordPress theme determines the header content
 * type to use for the given object's page header.
 *
 * @author Jo Dickson
 * @since 1.2.0
 * @param string $content_type The determined header content type slug (corresponds to a "header_content-" template part slug)
 * @param mixed $obj A queried object (e.g. WP_Post, WP_Term), or null
 * @return string The determined header content type slug
 */
function online_get_header_content_type( $content_type, $obj ) {
	$header_type  = ucfwp_get_header_type( $obj );

	// Required for compatibility with existing content type names:
	// if $header_content_type is empty or set to 'default',
	// update it to 'title_form' instead
	if ( $header_type === 'media' && in_array( $content_type, array( '', 'default' ) ) ) {
		$content_type = 'title_form';
	}

	// Modifications for landing pages
	if ( substr( $header_type, 0, 7 ) === 'landing' ) {
		$content_type = 'landing';

		switch ( get_post_meta( $obj->ID, '_wp_page_template', true ) ) {
			case 'template-landing-2.php':
				$content_type .= '_2';
				break;
			case 'template-landing-3.php':
				$content_type .= '_3';
				break;
			case 'template-landing-custom.php':
				$content_type .= '_custom';
				break;
			case 'default':
			default:
				break;
		}
	}

	return $content_type;
}

add_filter( 'ucfwp_get_header_content_type', 'online_get_header_content_type', 10, 2 );


/**
 * Returns the header form assigned for a given object.
 *
 * @since 1.1.0
 * @author Jo Dickson
 * @param mixed $obj A queried object (e.g. WP_Post, WP_Term), or null
 * @return mixed An array representing a single GravityForm's data, or null
 */
function online_get_header_form( $obj ) {
	$obj_custom_form_val = get_field( 'page_header_form_option', $obj );
	$form_id             = null;
	$form                = null;

	// Landing pages don't have the `page_header_form_option` field;
	// set $obj_custom_form_val manually here to accommodate
	if ( $obj instanceof WP_Post && $obj->post_type === 'landing' ) {
		$obj_custom_form_val = 'custom';
	}

	// Determine which form to retrieve
	if ( $obj_custom_form_val !== 'none' ) {
		// Retrieve a custom form ID first
		if ( $obj_custom_form_val === 'custom' ) {
			$form_id = get_field( 'page_header_form_custom', $obj );
		}

		// If we can't retrieve a custom form ID, or if
		// the page is configured to just use the default form,
		// use the default
		if (
			( $obj_custom_form_val === 'custom' && ! $form_id )
			|| $obj_custom_form_val === 'default'
			|| ! $obj_custom_form_val
		) {
			$form_id = online_get_theme_mod_or_default( 'default_header_form' );
		}
	}

	// Retrieve the form by ID
	if ( $form_id && class_exists( 'GFAPI' ) ) {
		$form = GFAPI::get_form( $form_id );
	}

	return $form;
}
