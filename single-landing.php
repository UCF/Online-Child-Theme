<?php get_header(); the_post(); ?>

<?php
$subhead_img       = wp_get_attachment_image(
	get_field( 'landing_subheader_media_image', $post ),
	'medium_large',
	false,
	array( 'class' => 'img-thumbnail border-0 rounded-0 img-fluid' )
);
$subhead_media_pos = get_field( 'landing_subheader_media_position', $post );
$subhead_lead      = get_field( 'landing_subheader_content', $post );
$highlights        = online_get_landing_highlights( $post );

$subhead_media_col   = 'text-center col-lg-8';
$subhead_content_col = 'col-12';
if ( $subhead_img ) {
	$subhead_content_col = 'col-lg-4';

	if ( $subhead_media_pos === 'left' ) {
		$subhead_media_col .= ' pull-lg-4';
		$subhead_content_col .= ' push-lg-8';
	}
	elseif ( ! $subhead_lead && ! $highlights ) {
		$subhead_media_col .= ' offset-lg-2';
	}
}
?>

<div class="landing-1-subhead">
	<div class="container">
		<div class="row">
			<?php if ( $subhead_lead || $highlights ): ?>
			<div class="<?php echo $subhead_content_col; ?>">
				<?php if ( $subhead_lead ): ?>
				<div class="lead">
					<?php echo $subhead_lead; ?>
				</div>
				<?php endif; ?>

				<?php if ( $subhead_lead && $highlights ): ?>
				<hr class="my-4">
				<?php endif; ?>

				<?php echo $highlights; ?>
			</div>
			<?php endif; ?>

			<?php if ( $subhead_img ): ?>
			<div class="<?php echo $subhead_media_col; ?>">
				<?php echo $subhead_img; ?>
			</div>
			<?php endif; ?>
		</div>
	</div>
</div>

<article class="<?php echo $post->post_status; ?> post-list-item">
	<div class="container mt-4 mt-sm-5 mb-5 pb-sm-4">
		<?php the_content(); ?>
	</div>
</article>

<?php get_footer(); ?>
