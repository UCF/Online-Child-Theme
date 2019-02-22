<?php
/**
 * Template Name: Landing Page Concept 3
 * Template Post Type: landing
 */
?>

<?php get_header(); the_post(); ?>

<?php
$images        = ucfwp_get_header_images( $post );
$bg_image_srcs = ( $images ) ? ucfwp_get_header_media_picture_srcs( 'header-media-default', $images ) : array();
$highlights    = online_get_landing_highlights( $obj );
?>

<?php if ( $highlights ): ?>
<div class="landing-3-subhead mb-3 mb-md-4">
	<div class="container">
		<?php echo $highlights; ?>
	</div>
</div>
<?php endif; ?>

<?php if ( $images ): ?>
<div class="header-media header-media-default mb-3 hidden-md-up">
	<div class="header-media-background-wrap">
		<div class="header-media-background media-background-container">
			<?php echo ucfwp_get_media_background_picture( $bg_image_srcs ); ?>
		</div>
	</div>
</div>
<?php endif; ?>

<article class="<?php echo $post->post_status; ?> post-list-item">
	<div class="container mt-4 mt-sm-5 mb-5 pb-sm-4">
		<?php the_content(); ?>
	</div>
</article>

<?php get_footer(); ?>
