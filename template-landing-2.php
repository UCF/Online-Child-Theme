<?php
/**
 * Template Name: Landing Page Concept 2
 * Template Post Type: landing
 */
?>

<?php get_header(); the_post(); ?>

<?php
$images        = ucfwp_get_header_images( $post );
$bg_image_srcs = ( $images ) ? ucfwp_get_header_media_picture_srcs( 'header-media-default', $images ) : array();
$highlights    = online_get_landing_highlights( $post );
?>

<?php if ( $highlights ) : ?>
<div class="container my-3 my-sm-4 my-md-5">
	<?php echo $highlights; ?>
	<div class="row hidden-sm-down mt-lg-5">
		<div class="col-md-8 offset-md-2 col-lg-6 offset-lg-3">
			<hr class="hr-3">
		</div>
	</div>
</div>
<?php endif; ?>

<?php if ( $images['header_image_xs'] ) : ?>
<div class="container text-center mt-3 mt-sm-4 hidden-md-up">
	<img class="img-fluid" src="<?php echo $bg_image_srcs['xs']; ?>" alt="">
</div>
<?php endif; ?>

<article class="<?php echo $post->post_status; ?> post-list-item">
	<div class="container mt-4 mt-sm-5 mb-5 pb-sm-4">
		<?php the_content(); ?>
	</div>
</article>

<?php get_footer(); ?>
