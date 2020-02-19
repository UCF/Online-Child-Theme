<?php
/**
 * Functions for sections
 */

function online_get_post_sections( $sections ) {
	$subfooter_section = online_get_theme_mod_or_default( 'subfooter_section' );

	if ( $subfooter_section ) {
		$section = get_post( $subfooter_section );

		if ( $section ) {
			$sections[$subfooter_section] = $section;
		}
	}

	return $sections;
}

add_filter( 'get_post_sections', 'online_get_post_sections', 9, 1 );
