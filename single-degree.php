<?php get_header(); the_post(); ?>

<?php
// Degree data markup
$details      = online_get_degree_details_markup( $post );
$credit_hours = online_get_degree_hours_markup( $post );
$tuition      = online_get_degree_tuition_markup( $post );
$badges       = online_get_degree_badges_markup( $post );
$career_paths = online_get_degree_career_paths_markup( $post );
$quotes       = online_get_degree_quotes_markup( $post );
$video        = online_get_degree_video_markup( $post );

// Helper variables
$has_at_a_glance = ( $details || $credit_hours || $tuition || $badges );
$has_sidebar     = ( $career_paths || $quotes || $video );
?>

<article class="<?php echo $post->post_status; ?> post-list-item">

	<?php if ( $has_at_a_glance ): ?>
	<section class="jumbotron bg-faded">
		<div class="container">
			<h2 class="h4 font-condensed text-uppercase mb-4 <?php if ( $details ){ ?>hidden-sm-up<?php } ?>">Program at a Glance</h2>
			<div class="row">

				<?php if ( $details ): ?>
				<div class="col col-lg-3 mb-3 mb-lg-0">
					<h2 class="h4 font-condensed text-uppercase hidden-xs-down mb-4">Program at a Glance</h2>
					<?php echo $details; ?>
				</div>
				<?php endif; ?>

				<?php if ( $credit_hours ): ?>
				<div class="col-auto mb-3 mb-lg-0">
					<?php echo $credit_hours; ?>
				</div>
				<?php endif; ?>

				<?php if ( $tuition ): ?>
				<div class="col-md-6 col-lg-5 col-xl-4 mb-3 mb-lg-0">
					<?php echo $tuition; ?>
				</div>
				<?php endif; ?>

				<?php if ( $badges ): ?>
				<div class="col-lg d-flex flex-row mb-3 mb-lg-0">
					<?php echo $badges; ?>
				</div>
				<?php endif; ?>

			</div>
		</div>
	</section>
	<?php endif; ?>

	<div class="container mt-4 mt-sm-5 mb-5 pb-sm-4">
		<div class="row">
			<div class="col mb-4 mb-lg-0">
				<?php the_content(); ?>
			</div>

			<?php if ( $has_sidebar ): ?>
			<div class="col-auto hidden-md-down">
				<hr class="hr-3 hr-vertical">
			</div>
			<div class="col-lg-4">
				<?php if ( $career_paths ): ?>
				<section>
					<h2>Career Opportunities</h2>
					<?php echo $career_paths; ?>
				</section>
				<?php endif; ?>

				<?php if ( $quotes ): ?>
				<section>
					<?php echo $quotes; ?>
				</section>
				<?php endif; ?>

				<?php if ( $video ): ?>
				<section>
					<?php echo $video; ?>
				</section>
				<?php endif; ?>
			</div>
			<?php endif; ?>
		</div>
	</div>
</article>

<?php get_footer(); ?>
