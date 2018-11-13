<?php
/**
 * Template Name: Vertical
 * Template Post Type: page, post
 */
?>
<?php get_header(); the_post(); echo online_get_typeahead_markup(); ?>
<article class="<?php echo $post->post_status; ?> post-list-item">
	<div class="container mt-4 mt-sm-5 mb-5 pb-sm-4">
		<?php the_content(); ?>
	</div>
</article>

<?php get_footer(); ?>
