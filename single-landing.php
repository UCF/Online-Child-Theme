<?php get_header(); the_post(); ?>

<?php
$subhead_img       = wp_get_attachment_image(
	get_field( 'landing_subheader_media_image', $post ),
	'medium_large',
	false,
	array( 'class' => 'img-thumbnail border-0 rounded-0 img-fluid' )
);
$subhead_media_pos = get_field( 'landing_subheader_media_position', $post ) ?: 'left';
$subhead_lead      = get_field( 'landing_subheader_content', $post );
$highlights        = online_get_landing_highlights( $post );
$has_subhead       = ( $subhead_img || $subhead_lead || $highlights );

$subhead_class       = '';
$subhead_media_col   = 'text-center';
$subhead_content_col = 'col-12';

if ( $subhead_img ) {
	$subhead_content_col = 'col-lg-5 pt-4 pt-md-5';

	// Add column classes based on whether or not lead + highlight
	// text will be adjacent to the subheader media:
	if ( $subhead_lead || $highlights ) {
		$subhead_class .= ' landing-1-subhead-pullup-lg';
		$subhead_media_col .= ' col-lg-7';

		// Add alignment classes that position subheader media left or right:
		if ( $subhead_media_pos === 'left' ) {
			$subhead_media_col .= ' pull-lg-5';
			$subhead_content_col .= ' push-lg-7 pl-lg-4';
		}
		elseif ( $subhead_media_pos === 'right' ) {
			$subhead_content_col .= ' pr-lg-4';
		}
	}
	else {
		$subhead_class .= ' landing-1-subhead-pullup';
		$subhead_media_col .= ' col-lg-8 offset-lg-2';
	}
}
?>

<?php if ( $has_subhead ): ?>
<div class="<?php echo $subhead_class; ?>">
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
<?php endif; ?>

<article class="<?php echo $post->post_status; ?> post-list-item">
	<div class="container mt-4 mt-sm-5 mb-5 pb-sm-4">
		<?php the_content(); ?>
	</div>
</article>

<?php get_footer(); ?>
