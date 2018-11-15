<?php get_header(); the_post(); ?>

<?php
$subnav    = online_get_vertical_subnav( $post );
$subfooter = online_get_vertical_subfooter( $post );
?>

<?php if ( $subnav ) { echo $subnav; } ?>

<article class="<?php echo $post->post_status; ?> post-list-item">
	<div class="container my-5">
		<div class="row">
			<div class="col-md-8 offset-md-2 col-lg-4 offset-lg-0 mb-5">

				<aside class="person-contact-container">

					<div class="mb-4">
						<?php echo ucfwp_get_person_thumbnail( $post, 'rounded-circle' ); ?>
					</div>

					<h1 class="h5 person-title text-center mb-3">
						<?php echo ucfwp_get_person_name( $post ); ?>
					</h1>

					<?php if ( $job_title = get_field( 'person_jobtitle' ) ): ?>
					<div class="person-job-title text-center mb-2"><?php echo $job_title; ?></div>
					<?php endif; ?>

					<?php echo online_get_person_degrees_markup( $post ); ?>

					<?php echo online_get_person_contact_btns_markup( $post ); ?>

					<?php echo online_get_person_office_markup( $post ); ?>
					<?php echo online_get_person_email_markup( $post ); ?>
					<?php echo online_get_person_phones_markup( $post ); ?>

				</aside>

			</div>

			<div class="col-md-7 offset-md-1">

				<section class="person-content">
					<h2 class="person-subheading">Biography</h2>
					<?php if ( $post->post_content ) {
						the_content();
					}
					else {
						echo '<p>No biography available.</p>';
					}
					?>
				</section>

			</div>
		</div>
	</div>

	<?php if ( $subfooter ) { echo $subfooter; } ?>
</article>

<?php get_footer(); ?>
