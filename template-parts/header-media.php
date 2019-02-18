<?php
$obj        = get_query_var( 'ucfwp_obj', ucfwp_get_queried_object() );
$videos     = ucfwp_get_header_videos( $obj );
$images     = ucfwp_get_header_images( $obj );
$video_loop = get_field( 'page_header_video_loop', $obj );
$header_text_color   = get_field( 'page_header_text_color', $obj );
$header_content_type = ucfwp_get_header_content_type( $obj );
$header_height       = get_field( 'page_header_height', $obj ) ?: 'header-media-default'; // for imported, unmodified pages

// We modify the header's text color using bg utilities to make sure we
// still meet color contrast req's when bg imgs/videos fail to load
$header_bg_class = '';
switch ( $header_text_color ) {
	case 'white':
		$header_bg_class = 'bg-inverse';
		break;
	default:
		break;
}
?>
<div class="header-media <?php echo $header_height; ?> <?php echo $header_bg_class; ?> mb-0 d-flex flex-column">
	<div class="header-media-background-wrap">
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
			<?php get_template_part( ucfwp_get_template_part_slug( 'header_content' ), $header_content_type ); ?>
		</div>
	</div>

	<?php
	// Print a spacer div for headers with background videos (to make
	// control buttons accessible), and for headers showing a standard
	// title/subtitle to push them up a bit
	if ( $videos || $header_content_type === 'title_form' ):
	?>
	<div class="header-media-controlfix"></div>
	<?php endif; ?>
</div>
