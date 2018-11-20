<?php
/**
 * Template Name: Simple Vertical
 * Template Post Type: page
 */
?>
<?php get_header(); the_post(); ?>

<?php echo online_get_vertical_degree_typeahead_markup( $post ); ?>
<?php echo online_get_vertical_popular_programs_markup( $post ); ?>

<article class="<?php echo $post->post_status; ?> post-list-item">
	<div class="container mt-4 mt-sm-5 mb-5 pb-sm-4">
		<?php the_content(); ?>
	</div>
</article>

<?php get_footer(); ?>
