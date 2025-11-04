<?php
$post = isset( $post ) ? $post : get_queried_object();

if ( $post->post_type === 'degree' ) :
	$degree_skills_heading     = trim( get_field( 'degree_skills_heading', $post ) ) ?: 'Skills You&rsquo;ll Learn';
	$degree_careers_heading    = trim( get_field( 'degree_careers_heading', $post ) ) ?: 'Career Opportunities';
	$degree_projection_heading = trim( get_field( 'degree_projection_heading', $post ) ) ?: 'Career Projections';

	$degree_skills_content     = trim( get_field( 'degree_skills_content', $post ) );

	$degree_prj_openings          = number_format( floatval( get_field( 'degree_prj_openings', $post ) ) );
	$degree_prj_change_percentage = get_field( 'degree_prj_change_percentage', $post );
	$degree_prj_begin_year        = get_field( 'degree_prj_begin_year', $post );
	$degree_prj_end_year          = get_field( 'degree_prj_end_year', $post );

	$degree_outcome_salary_amount = get_field( 'degree_outcome_salary_amount', $post );
	$degree_outcome_salary_info   = get_field( 'degree_outcome_salary_info', $post );

	$projection_disclaimer  = get_theme_mod( 'projection_disclaimer', null );

?>
<div class="row">

<?php if ( have_rows( 'degree_skills_list', $post ) ) : ?>

	<div class="col-lg-7 py-lg-3">
		<h2 class="font-condensed text-primary text-uppercase mb-4">
			<?php echo $degree_skills_heading; ?>
		</h2>

		<?php
		if ( $degree_skills_content ) {
			echo $degree_skills_content;
		}
		?>

		<ul class="pl-4 mb-0">
		<?php while ( have_rows( 'degree_skills_list', $post ) ) : the_row(); ?>
			<?php if ( get_sub_field( 'degree_skills_list_item' ) ) : ?>
			<li class="degree-skill-list-item mb-3 mb-lg-4">
				<?php the_sub_field( 'degree_skills_list_item' ); ?>
			</li>
			<?php endif; ?>
		<?php endwhile; ?>
		</ul>

		<?php if( $degree_projection_heading ) : ?>

			<hr class="hr-primary pb-3">

			<h2 class="font-condensed h3 text-primary text-uppercase mb-4">
				<?php echo $degree_projection_heading; ?>
			</h2>

			<div class="row">

			<?php if( $degree_prj_openings ) : ?>

				<div class="col-4">
					<span class="h2 text-uppercase text-center d-block"><?php echo $degree_prj_openings; ?></span>
					<p class="text-center d-block">Annual Job<br>Openings</p>
				</div>

			<?php endif; ?>

			<?php if( $degree_prj_change_percentage && $degree_prj_begin_year && $degree_prj_end_year ) : ?>

				<div class="col-4">
					<span class="h2 text-uppercase text-center d-block"><?php echo $degree_prj_change_percentage; ?>%</span>
					<p class="text-center d-block">
						Job Growth<br>Between<br>
						<?php echo $degree_prj_begin_year; ?> - <?php echo $degree_prj_end_year; ?>
					</p>
				</div>

			<?php endif; ?>

			<?php if ( $degree_outcome_salary_amount && $degree_outcome_salary_info ) : ?>

				<div class="col-4">
					<span class="h2 text-uppercase text-center d-block"><?php echo $degree_outcome_salary_amount; ?></span>
					<p class="text-center d-block">
						<?php echo $degree_outcome_salary_info; ?>
					</p>
				</div>

			<?php endif; ?>

			</div>

			<p><?php echo $projection_disclaimer; ?></p>

		<?php endif; ?>
	</div>

<?php endif; ?>

	<div class="col-lg-4 offset-lg-1 py-lg-3 mt-4 mt-lg-0">
		<h2 class="font-condensed h3 text-primary text-uppercase mb-4">
			<?php echo $degree_careers_heading; ?>
		</h2>

		<?php get_template_part( 'template-parts/degree/skills_careers/careers' ); ?>
	</div>

</div>
<?php
endif;
