<?php get_header(); the_post(); ?>

<?php
$post = ucf_degree_append_meta( $post );

// Degree data markup
$details       = online_get_degree_details_markup( $post );
$duration      = online_get_degree_duration_markup( $post );
$tuition       = online_get_degree_tuition_markup( $post );
$badges        = online_get_degree_badges_markup( $post );
$career_paths  = online_get_degree_career_paths_markup( $post );
$quotes        = online_get_degree_quotes_markup( $post );
$video         = online_get_degree_video_markup( $post );
$video_caption = online_get_degree_video_caption_markup( $post );
$spotlight     = online_get_degree_spotlight_markup( $post );

// Helper variables
$has_at_a_glance = ( $details || $duration || $tuition || $badges );
$has_sidebar     = ( $career_paths || $quotes || $video );
?>

<article class="<?php echo $post->post_status; ?> post-list-item">

	<?php if ( $has_at_a_glance ): ?>
	<section class="jumbotron bg-faded pb-4 pb-md-5">
		<div class="container">
			<h2 class="h4 font-condensed text-uppercase mb-4 <?php if ( $details ){ ?>hidden-sm-up<?php } ?>">Program at a Glance</h2>
			<div class="row">

				<?php if ( $details ): ?>
				<div class="col pr-lg-4 mb-4 mb-lg-0">
					<h2 class="h4 font-condensed text-uppercase hidden-xs-down mb-4">Program at a Glance</h2>
					<?php echo $details; ?>
				</div>
				<?php endif; ?>

				<?php if ( $duration ): ?>
				<div class="col-auto mb-4 mb-lg-0">
					<?php echo $duration; ?>
				</div>
				<?php endif; ?>

				<div class="w-100 hidden-sm-down hidden-lg-up"></div>

				<?php if ( $tuition ): ?>
				<div class="col-md-6 col-lg-4 mb-4 mb-lg-0">
					<?php echo $tuition; ?>
				</div>
				<?php endif; ?>

				<?php if ( $badges ): ?>
				<div class="col-md-6 col-lg d-flex flex-row mb-4 mb-lg-0">
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
					<h2 class="h5 mb-3">
						<span class="badge badge-inverse bg-inverse-t-3 p-4">Career Opportunities</span>
					</h2>
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
					<?php if ( $video_caption ): ?>
						<?php echo $video_caption; ?>
					<?php endif; ?>
				</section>
				<?php endif; ?>

				<?php if ( $spotlight ): ?>
				<section>
					<?php echo $spotlight; ?>
				</section>
				<?php endif; ?>
			</div>
			<?php endif; ?>
		</div>
	</div>
</article>

<?php get_footer(); ?>
