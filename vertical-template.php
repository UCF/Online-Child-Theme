<?php
/**
 * Template Name: Vertical
 * Template Post Type: page, post
 */
?>
<?php get_header(); the_post(); ?>
<?php echo online_get_degree_typeahead_markup(); ?>
<?php echo online_get_popular_programs(); ?>
<article class="<?php echo $post->post_status; ?> post-list-item">
	<div class="container mt-4 mt-sm-5 mb-5 pb-sm-4">
		<?php the_content(); ?>
	</div>
</article>

<?php get_footer(); ?>
