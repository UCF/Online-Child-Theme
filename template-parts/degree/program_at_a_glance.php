<?php
$post = isset( $post ) ? $post : get_queried_object();

if ( $post->post_type === 'degree' ) :
	$program_type        = get_degree_program_type( $post );
	$raw_postmeta        = get_post_meta( $post->ID );
	$post_meta           = format_raw_postmeta( $raw_postmeta );

	$colleges    = wp_get_post_terms( $post->ID, 'colleges' );

	$program_length_image  = get_field( 'program_length_image', $post );
	$program_length_number = get_field( 'program_length_number', $post );
	$program_length_text   = get_field( 'program_length_text', $post );
	$has_duration          = ( $program_length_image && $program_length_number && $program_length_text );

	$tuition_disclaimer  = get_theme_mod( 'tuition_disclaimer', null );
	$tuition_resident    = isset( $post_meta['degree_resident_tuition'] ) ? get_degree_tuition_parts( $post_meta['degree_resident_tuition'] ) : null;
	$tuition_nonresident = isset( $post_meta['degree_nonresident_tuition'] ) ? get_degree_tuition_parts( $post_meta['degree_nonresident_tuition'] ) : null;
	$has_tuition = true;
	if (
		(
			isset( $post_meta['degree_tuition_skip'] )
			&& $post_meta['degree_tuition_skip'] === 'on'
		)
		|| ! $tuition_resident
		|| ! $tuition_nonresident
	) {
		$has_tuition = false;
	}

	$section = get_field( 'degree_program_section', $post );

	$details_col_classes = 'pr-lg-4 pr-xl-5 mb-4 mb-lg-0 col';
	if ( $has_tuition ) {
		// Set a fixed column size at -lg+ when tuition is available.
		// (Otherwise, this column will effectively calculate out to
		// a col-lg-6)
		$details_col_classes .= ' col-lg-5';

		if ( $has_duration ) {
			// If program duration info is present, make even more room at -xl+
			$details_col_classes .= ' col-xl-6';
		}
	}
?>
<section id="program-at-a-glance" aria-labelledby="program-at-a-glance-heading" role="region">
	<div class="jumbotron jumbotron-fluid bg-faded pb-4 pb-md-5 mb-0">
		<div class="container">
			<div class="row">

				<div class="<?php echo $details_col_classes; ?>">
					<h2 id="program-at-a-glance-heading" class="h4 font-condensed text-uppercase mb-4 pb-sm-2">
						Program at a Glance
					</h2>
					<div class="row">

						<div class="col">
							<dl>
								<?php if ( $program_type ) : ?>
								<dt class="h6 text-uppercase text-inverse-aw">Program</dt>
								<dd class="h5 mb-4"><?php echo $program_type->name; ?></dd>
								<?php endif; ?>

								<?php if ( $colleges ) : ?>
								<dt class="h6 text-uppercase text-inverse-aw">College(s)</dt>
								<dd class="h5 mb-4">
									<?php foreach ( $colleges as $college ) : ?>
										<span class="d-block">
											<?php echo $college->name; ?>
										</span>
									<?php endforeach; ?>
								</dd>
								<?php endif; ?>
							</dl>
						</div>

						<?php if ( $has_duration ): ?>
						<div class="col-auto mb-4 mb-lg-0 px-lg-4">
							<div class="text-center mb-3">
								<img class="program-length-image img-fluid" src="<?php echo $program_length_image; ?>" alt="">
							</div>
							<div class="h1 mb-0 text-center"><?php echo $program_length_number; ?></div>
							<div class="h6 text-secondary text-uppercase text-center"><?php echo $program_length_text; ?></div>
						</div>
						<?php endif; ?>

					</div>
				</div>

				<div class="w-100 hidden-sm-down hidden-lg-up"></div>

				<?php if ( $has_tuition ): ?>
				<div class="col-md-6 col-lg-4 mb-4 mb-lg-0">
					<div class="card h-100 text-center">

						<div class="card-header">
							<ul class="nav nav-tabs card-header-tabs" id="tuition-tabs" role="tablist">
								<?php if ( $tuition_resident ): ?>
								<li class="nav-item text-nowrap">
									<a class="nav-link active" id="resident-tuition-tab" data-toggle="tab" href="#resident-tuition" aria-controls="resident-tuition" aria-selected="true" role="tab">
										In State<span class="sr-only"> Tuition</span>
									</a>
								</li>
								<?php endif; ?>

								<?php if ( $tuition_nonresident ): ?>
								<li class="nav-item text-nowrap">
									<a class="nav-link" id="nonresident-tuition-tab" data-toggle="tab" href="#nonresident-tuition" aria-controls="nonresident-tuition" aria-selected="false" role="tab">
										Out of State<span class="sr-only"> Tuition</span>
									</a>
								</li>
								<?php endif; ?>
							</ul>
						</div>

						<div class="card-block d-flex flex-column justify-content-center px-sm-4 px-md-2 px-xl-3 pt-4 py-md-5 pt-lg-4 pb-lg-3 tab-content" id="tuition-panes">
							<?php if ( $tuition_resident ): ?>
							<div class="tab-pane fade show active" id="resident-tuition" aria-labelledby="resident-tuition-tab" role="tabpanel">
								<span class="tuition-amount">
									<?php echo $tuition_resident['value']; ?>
								</span>
								<span class="d-block small text-uppercase font-weight-bold"> <?php echo $tuition_resident['per']; ?></span>
							</div>
							<?php endif; ?>

							<?php if ( $tuition_nonresident ): ?>
							<div class="tab-pane fade <?php if ( ! $tuition_resident ) { ?>show active<?php } ?>" id="nonresident-tuition" aria-labelledby="nonresident-tuition-tab" role="tabpanel">
								<span class="tuition-amount">
									<?php echo $tuition_nonresident['value']; ?>
								</span>
								<span class="d-block small text-uppercase font-weight-bold"> <?php echo $tuition_nonresident['per']; ?></span>
							</div>
							<?php endif; ?>

							<?php if ( $tuition_disclaimer ) : ?>
							<p class="mt-4 mx-3 mb-0" style="line-height: 1.2;"><small><?php echo $tuition_disclaimer; ?></small></p>
							<?php endif; ?>
						</div>

					</div>
				</div>
				<?php endif; ?>

				<?php if ( $section ): ?>
				<div class="col-md d-flex flex-row mb-4 mb-lg-0 pl-lg-4 justify-content-center">
					<div class="section">
						<?php echo $section->post_content; ?>
					</div>
				</div>
				<?php endif; ?>

			</div>
		</div>
	</div>
</section>
<?php endif; ?>
