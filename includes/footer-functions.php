<?php
/**
 * Footer Related Functions
 **/

/**
 * Modifies the type of footer to use for the given object in this theme.
 * The value returned will represent an equivalent template part's name.
 *
 * @author Jo Dickson
 * @since 1.2.0
 * @param string $footer_type The determined footer type name
 * @param mixed $obj A queried object (e.g. WP_Post, WP_Term), or null
 * @return string The footer type name
 */
function online_get_footer_type( $footer_type, $obj ) {
	if ($obj instanceof WP_Post && $obj->post_type === 'landing' ) {
		if (
			get_post_meta( $obj->ID, '_wp_page_template', true ) === 'template-landing-custom.php'
			&& get_field( 'landing_custom_footer_content_type', $obj ) === 'custom'
		) {
			$footer_type = 'landing_custom';
		} else {
			$footer_type = 'landing';
		}
	}

	return $footer_type;
}

add_filter( 'ucfwp_get_footer_type', 'online_get_footer_type', 10, 2 );
