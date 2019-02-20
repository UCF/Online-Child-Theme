<?php
global $post;
$nav_title = get_field( 'landing_nav_title', $post );
$template  = get_post_meta( $post->ID, '_wp_page_template', true );

if ( $template === 'template-landing-2.php' ):
?>
<div class="bg-inverse">
	<div class="container d-flex justify-content-center justify-content-sm-between align-items-center">
		<img class="img-fluid" src="//placehold.it/260x40/" alt="University of Central Florida"><?php // TODO ?>

		<?php if ( $nav_title ): ?>
		<strong class="hidden-xs-down"><?php echo $nav_title; ?></strong>
		<?php endif; ?>
	</div>
</div>
<?php endif; ?>
