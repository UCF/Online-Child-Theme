<?php
/**
 * Simplified replacement for the primary site nav on landing pages
 */

global $post;
$nav_title = get_field( 'landing_nav_title', $post );
$template  = get_post_meta( $post->ID, '_wp_page_template', true );

if ( $template === 'template-landing-2.php' ):
?>
<div class="bg-inverse py-2">
	<div class="container d-flex justify-content-center justify-content-md-between align-items-center">
		<img class="d-flex img-fluid" src="<?php echo ONLINE_THEME_IMG_URL; ?>/signature-with-pegasus.svg" alt="University of Central Florida">

		<?php if ( $nav_title ): ?>
		<strong class="hidden-sm-down"><?php echo $nav_title; ?></strong>
		<?php endif; ?>
	</div>
</div>
<?php endif; ?>
