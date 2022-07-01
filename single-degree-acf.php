<?php
/**
 * Template Name: ACF Template
 * Template Post Type: degree
 */
?>

<?php get_header(); the_post(); ?>

<?php
	$colleges = wp_get_post_terms( $post->ID, 'colleges' );
	$college  = is_array( $colleges ) ? $colleges[0] : null;

	get_template_part( 'template-parts/degree/program_at_a_glance' );
	get_template_part( 'template-parts/degree/description' );
	get_template_part( 'template-parts/degree/deadlines_apply' );
	get_template_part( 'template-parts/degree/course_overview' );
	get_template_part( 'template-parts/degree/quotes' );
	get_template_part( 'template-parts/degree/skills_careers' );
	get_template_part( 'template-parts/degree/faq' );
	get_template_part( 'template-parts/degree/related-programs' );
	get_template_part( 'template-parts/degree/news' );

	get_template_part( 'template-parts/degree/catalog_modal' );
	get_template_part( 'template-parts/degree/request_info_modal' );
?>

<?php get_footer(); ?>
