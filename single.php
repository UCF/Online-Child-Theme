<?php get_header(); the_post(); ?>

<?php
$subnav    = online_get_vertical_subnav( $post );
$subfooter = online_get_vertical_subfooter( $post );
?>

<?php if ( $subnav ) { echo $subnav; } ?>

<article class="<?php echo $post->post_status; ?> post-list-item">
	<div class="container mt-4 mt-sm-5 mb-5 pb-sm-4">
		<?php the_content(); ?>
	</div>
</article>

<?php if ( $subfooter ) { echo $subfooter; } ?>

<?php get_footer(); ?>
