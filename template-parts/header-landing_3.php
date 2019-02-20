<?php
/**
 * Header template for Landing Page Concept 3
 */

$obj        = ucfwp_get_queried_object();
$videos     = ucfwp_get_header_videos( $obj );
$images     = ucfwp_get_header_images( $obj );
$video_loop = get_field( 'page_header_video_loop', $obj );
$header_content_type = ucfwp_get_header_content_type( $obj );
$header_height       = 'header-media-default';
?>
<div class="header-media header-media-fluid bg-secondary mb-0 d-flex flex-column">
	<div class="header-media-background-wrap hidden-sm-down">
		<div class="header-media-background media-background-container">
			<?php
			// Display the media background (video + picture)

			if ( $videos ) {
				echo ucfwp_get_media_background_video( $videos, $video_loop );
			}
			if ( $images ) {
				$bg_image_srcs = ucfwp_get_header_media_picture_srcs( $header_height, $images );
				echo ucfwp_get_media_background_picture( $bg_image_srcs );
			}
			?>
		</div>
	</div>

	<?php
	// Display the inner header contents
	?>
	<div class="header-content">
		<div class="header-content-flexfix">
			<?php echo ucfwp_get_header_content_markup(); ?>
		</div>
	</div>

	<?php
	// Print a spacer div for headers with background videos (to make
	// control buttons accessible)
	if ( $videos ):
	?>
	<div class="header-media-controlfix"></div>
	<?php endif; ?>
</div>
