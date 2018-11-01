<?php

/**
 * Applies data-object-position attributes to header <picture> <img>s.
 *
 * @author Jo Dickson
 * @since 1.0.0
 */
function online_media_background_object_position( $val ) {
	// Return the object-position property value we use on header media
	// bg's at the -xl breakpoint in this theme, to accommodate IE and
	// Edge desktop users:
	return '30% 30%';
}

add_filter( 'ucfwp_media_background_picture_object_position', 'online_media_background_object_position', 10, 1 );
add_filter( 'ucfwp_media_background_video_object_position', 'online_media_background_object_position', 10, 1 );
