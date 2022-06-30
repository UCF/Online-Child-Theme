<?php
$post = isset( $post ) ? $post : get_queried_object();

if ( $post->post_type === 'degree' ) :
	$degree_skills_heading     = trim( get_field( 'degree_skills_heading', $post ) ) ?: 'Skills You&rsquo;ll Learn';
	$degree_careers_heading    = trim( get_field( 'degree_careers_heading', $post ) ) ?: 'Career Opportunities';
	$degree_projection_heading = trim( get_field( 'degree_projection_heading', $post ) ) ?: 'Career Projections';

	$degree_prj_openings          = number_format( floatval( get_field( 'degree_prj_openings' ) ) );
	$degree_prj_change_percentage = get_field( 'degree_prj_change_percentage' );
	$degree_prj_begin_year        = get_field( 'degree_prj_begin_year' );
	$degree_prj_end_year          = get_field( 'degree_prj_end_year' );

?>
<h2 class="font-condensed text-primary text-uppercase mb-4">
	<?php echo $degree_skills_heading; ?>
</h2>
<div class="row">

	<?php if ( have_rows( 'degree_skills_list', $post ) ) : ?>
	<div class="col-lg-7 py-lg-3">
		<ul class="pl-4 mb-0">
		<?php while ( have_rows( 'degree_skills_list', $post ) ) : the_row(); ?>
			<?php if ( get_sub_field( 'degree_skills_list_item' ) ) : ?>
			<li class="degree-skill-list-item mb-3 mb-lg-4">
				<?php the_sub_field( 'degree_skills_list_item' ); ?>
			</li>
			<?php endif; ?>
		<?php endwhile; ?>
		</ul>

		<hr class="hr-primary pb-3">

		<h2 class="font-condensed text-primary text-uppercase mb-4">
			<?php echo $degree_projection_heading; ?>
		</h2>

		<div class="row">
			<div class="col-auto">
				<div class="h1 text-uppercase text-center"><?php echo $degree_prj_openings; ?></div>
				<p class="text-center d-block">Annual Job<br>Openings</p>
			</div>
			<div class="col-auto">
				<div class="h1 text-uppercase text-center"><?php echo $degree_prj_change_percentage; ?>%</div>
				<p class="text-center d-block">Job Growth<br>Between<br><?php echo $degree_prj_begin_year; ?> - <?php echo $degree_prj_end_year; ?></p>
			</div>
		</div>

		<p>Source: Emsi Burning Glass - economicmodeling.com</p>

	</div>
	<?php endif; ?>

	<div class="col-lg-4 offset-lg-1 mt-4 mt-lg-0">
		<h2 class="font-condensed h5 text-uppercase mb-4 pt-3">
			<?php echo $degree_careers_heading; ?>
		</h2>

		<?php get_template_part( 'template-parts/degree/skills_careers/careers' ); ?>
	</div>

</div>
<?php
endif;
