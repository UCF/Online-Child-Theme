<?php

/**
 * Returns a HTML string of stylized tuition data,
 * given a resident or non-resident tuition rate.
 *
 * @since 1.0.0
 * @author Jo Dickson
 * @param string $tuition_str The resident or nonresident tuition value for a degree
 * @return string formatted HTML markup
 */
function online_format_tuition_value( $tuition_str ) {
	$pattern = '/^(\$[0-9\.]+) (.*)$/i';
	$replacement = '<span class="d-block display-4 tuition-value">${1}</span><span class="d-block small text-uppercase tuition-description"> ${2}</span>';

	return preg_replace( $pattern, $replacement, $tuition_str );
}


/**
 * Returns HTML markup for a single degree's brief details list
 * (program type and college).
 *
 * @since 1.0.0
 * @author Jo Dickson
 * @param object $degree WP_Post object for a single degree
 * @return string HTML markup for a single degree's details, like college and program type
 */
function online_get_degree_details_markup( $degree ) {
	$degree = ucf_degree_append_meta( $degree );

	// Program type data
	$program_types = $degree->taxonomies['program_types'];
	$program_type  = ( $program_types ) ? $program_types[0] : null;

	// College data
	$colleges      = $degree->taxonomies['colleges'];
	$college       = ( $colleges ) ? $colleges[0] : null;
	$college_url   = null;
	$college_name  = null;
	if ( $college ) {
		$college_name = get_term_meta( $college->term_id, 'colleges_alias', true ) ?: $college->name;
		$college_url  = get_term_meta( $college->term_id, 'colleges_url', true );
	}

	ob_start();
	if ( $program_type || $college ):
?>
	<dl>
		<?php if ( $program_type ): ?>
		<dt class="h6 text-uppercase text-default">Type</dt>
		<dd class="h5 mb-4"><?php echo $program_type->name; ?></dd>
		<?php endif; ?>

		<?php if ( $college ): ?>
		<dt class="h6 text-uppercase text-default">College</dt>
		<dd class="h5">
			<?php if ( $college_url ): ?>
			<a href="<?php echo $college_url; ?>" target="_blank">
			<?php endif; ?>

			<?php echo $college_name; ?>

			<?php if ( $college_url ): ?>
			</a>
			<?php endif; ?>
		</dd>
		<?php endif; ?>
	</dl>
<?php
	endif;
	return ob_get_clean();
}


/**
 * Returns HTML markup for a single degree's credit hour info.
 *
 * @since 1.0.0
 * @author Jo Dickson
 * @param object $degree WP_Post object for a single degree
 * @return string HTML markup for a single degree's credit hour info
 */
function online_get_degree_hours_markup( $degree ) {
	$credit_hours = get_field( 'degree_hours', $degree );

	ob_start();
	if ( $credit_hours ):
?>
	<div class="card h-100">
		<div class="card-block text-center d-flex flex-column justify-content-center px-sm-4">
			<span class="fa fa-3x fa-desktop text-primary mb-2" aria-hidden="true"></span>
			<strong class="d-block display-4"><?php echo $credit_hours; ?></strong>
			<span class="d-block small text-uppercase text-nowrap">Credit Hours</span>
		</div>
	</div>
<?php
	endif;
	return ob_get_clean();
}


/**
 * Returns HTML markup for a single degree's tuition data.
 *
 * @since 1.0.0
 * @author Jo Dickson
 * @param object $degree WP_Post object for a single degree
 * @return string HTML markup for a single degree's tuition + fees interface
 */
function online_get_degree_tuition_markup( $degree ) {
	$resident_tuition    = online_format_tuition_value( $degree->degree_resident_tuition );
	$nonresident_tuition = online_format_tuition_value( $degree->degree_nonresident_tuition );

	ob_start();
	if ( $resident_tuition || $nonresident_tuition ):
?>
	<div class="card h-100 text-center">
		<div class="card-header">
			<ul class="nav nav-tabs card-header-tabs" id="tuition-tabs" role="tablist">
				<?php if ( $resident_tuition ): ?>
				<li class="nav-item text-nowrap">
					<a class="nav-link active" id="resident-tuition-tab" data-toggle="tab" href="#resident-tuition" role="tab" aria-controls="resident-tuition" aria-selected="true">
						In State<span class="sr-only"> Tuition</span>
					</a>
				</li>
				<?php endif; ?>

				<?php if ( $nonresident_tuition ): ?>
				<li class="nav-item text-nowrap">
					<a class="nav-link <?php if ( ! $resident_tuition ){ ?>active<?php } ?>" id="nonresident-tuition-tab" data-toggle="tab" href="#nonresident-tuition" role="tab" aria-controls="nonresident-tuition" aria-selected="<?php echo ( ! $resident_tuition ); ?>">
						Out of State<span class="sr-only"> Tuition</span>
					</a>
				</li>
				<?php endif; ?>
			</ul>
		</div>
		<div class="card-block d-flex flex-column justify-content-center px-sm-4 px-md-2 px-xl-3 pt-4 tab-content" id="tuition-tabs">
			<?php if ( $resident_tuition ): ?>
			<div class="tab-pane fade show active" id="resident-tuition" role="tabpanel" aria-labelledby="resident-tuition-tab">
				<?php echo $resident_tuition; ?>
			</div>
			<?php endif; ?>

			<?php if ( $nonresident_tuition ): ?>
			<div class="tab-pane fade <?php if ( ! $resident_tuition ){ ?>show active<?php } ?>" id="nonresident-tuition" role="tabpanel" aria-labelledby="nonresident-tuition-tab">
				<?php echo $nonresident_tuition; ?>
			</div>
			<?php endif; ?>
		</div>
	</div>
<?php
	endif;
	return ob_get_clean();
}


/**
 * TODO Returns HTML markup for a single degree's badges and awards.
 *
 * @since 1.0.0
 * @author Jo Dickson
 * @param object $degree WP_Post object for a single degree
 * @return string HTML markup for a single degree's badges/awards
 */
function online_get_degree_badges_markup( $degree ) {
	ob_start();
	if ( have_rows( 'degree_awards', $degree->ID ) ):
?>
	<div class="degree-badges">

	<?php while ( have_rows( 'degree_awards', $degree->ID ) ) : the_row(); ?>
		<?php
		$img_obj = get_sub_field( 'degree_award_graphic' );
		$details = get_sub_field( 'degree_award_details' );

		if ( ! $img_obj ) { break; }

		// Fallback details
		$img_caption = $img_obj['caption'];
		$img_desc    = $img_obj['description'];
		$img_alt     = $img_obj['alt'];

		$badge = $img_obj['sizes']['thumbnail'];
		$alt   = $details ?: $img_caption ?: $img_desc ?: $img_alt;
		?>
		<div class="degree-badge-col">
			<img src="<?php echo $badge; ?>" alt="<?php echo $alt; ?>" class="degree-badge">
		</div>
	<?php endwhile; ?>

	</div>
<?php
	endif;
	return ob_get_clean();
}


/**
 * TODO Returns HTML markup for a single degree's career paths list.
 *
 * @since 1.0.0
 * @author Jo Dickson
 * @param object $degree WP_Post object for a single degree
 * @return string HTML markup for a single degree's career paths list
 */
function online_get_degree_career_paths_markup( $degree ) {
	ob_start();
?>
<?php
	return ob_get_clean();
}


/**
 * TODO Returns markup for a single degree's quotes.
 *
 * @since 1.0.0
 * @author Jo Dickson
 * @param object $degree WP_Post object for a single degree
 * @return string HTML markup for a single degree's quotes
 */
function online_get_degree_quotes_markup( $degree ) {
	ob_start();
?>
<?php
	return ob_get_clean();
}


/**
 * Returns markup for a single degree's video embed.
 *
 * @since 1.0.0
 * @author Jo Dickson
 * @param object $degree WP_Post object for a single degree
 * @return string HTML markup for a single degree's video
 */
function online_get_degree_video_markup( $degree ) {
	// NOTE: get_field() can return URL string OR iframe markup here
	$video = apply_filters( 'the_content', get_field( 'degree_header_video', $degree ) );
	$video = trim( $video );
	if ( substr( $video, 0, 3 ) === 'http' ) {
		// auto oembed-ification failed; ignore the bad value
		$video = null;
	}

	ob_start();
	if ( $video ):
?>
	<div class="embed-responsive embed-responsive-16by9">
		<?php echo $video; ?>
	</div>
<?php
	endif;
	return ob_get_clean();
}
