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
	$pattern = '/^(\$[0-9\.\,]+) (.*)$/i';
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
	if ( ! isset( $degree->taxonomies ) ) {
		$degree = ucf_degree_append_meta( $degree );
	}

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
		<div class="card-block d-flex flex-column justify-content-center px-sm-4 px-md-2 px-xl-3 pt-4 py-md-5 pt-lg-4 pb-lg-3 tab-content" id="tuition-tabs">
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
 * Returns HTML markup for a single degree's badges and awards.
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
 * Returns HTML markup for a single degree's career paths list.
 *
 * @since 1.0.0
 * @author Jo Dickson
 * @param object $degree WP_Post object for a single degree
 * @return string HTML markup for a single degree's career paths list
 */
function online_get_degree_career_paths_markup( $degree ) {
	if ( ! isset( $degree->taxonomies ) ) {
		$degree = ucf_degree_append_meta( $degree );
	}

	$career_paths = isset( $degree->taxonomies['career_paths'] ) ? $degree->taxonomies['career_paths'] : null;

	ob_start();
	if ( $career_paths ):
?>
	<ul class="list-bullets mb-5 pl-0">
		<?php foreach ( $career_paths as $term ): ?>
		<li class="text-capitalize">
			<?php echo $term->name; ?>
		</li>
		<?php endforeach; ?>
	</ul>
<?php
	endif;
	return ob_get_clean();
}


/**
 * Returns markup for a single degree's quotes.
 *
 * @since 1.0.0
 * @author Jo Dickson
 * @param object $degree WP_Post object for a single degree
 * @return string HTML markup for a single degree's quotes
 */
function online_get_degree_quotes_markup( $degree ) {
	ob_start();
	if ( have_rows( 'degree_quotes', $degree->ID ) ):
		while ( have_rows( 'degree_quotes', $degree->ID ) ) : the_row();

			$quote           = get_sub_field( 'degree_quote' );
			$source_title    = get_sub_field( 'degree_quote_source_title' );
			$source_subtitle = get_sub_field( 'degree_quote_source_subtitle' );
			$has_cite        = get_sub_field( 'degree_quote_source_has_cite' );

			if ( ! $quote ) { break; }
?>
			<blockquote class="blockquote blockquote-quotation font-serif mb-4">
				<?php echo $quote; ?>
				<footer class="blockquote-footer text-uppercase">
					<?php if ( $has_cite ): ?>
					<cite>
					<?php endif; ?>

					<?php if ( $source_title ): ?>
					<strong class="d-block mb-1"><?php echo $source_title; ?></strong>
					<?php endif; ?>

					<?php if ( $source_subtitle ): ?>
					<span class="d-block"><?php echo $source_subtitle; ?></span>
					<?php endif; ?>

					<?php if ( $has_cite ): ?>
					</cite>
					<?php endif; ?>
				</footer>
			</blockquote>
<?php
		endwhile;
	endif;
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


/**
 * Returns the markup for the ucf-degree-typeahead for
 * the single degree template.
 *
 * @author Jim Barnes
 * @since 1.0.0
 * @param object $post WP_Post object
 * @return string
 */
function online_get_degree_typeahead_markup( $post ) {
	if ( class_exists( 'UCF_Degree_Search_Common' ) ) {
		$college      = get_field( 'vertical_college_filter', $post->ID );
		$program_type = get_field( 'vertical_program_type_filter', $post->ID );
		$interest     = get_field( 'vertical_interest_filter', $post->ID );
		$placeholder  = get_field( 'degree_search_placeholder', $post->ID );

		$atts = array();

		if ( $college ) $atts['colleges'] = $college->slug;
		if ( $program_type ) $atts['program_types'] = $program_type->slug;
		if ( $interest ) $atts['interest'] = $interest->slug;

		$query_params = '?' . http_build_query( $atts );

		$query_params .= ( $query_params === '?' ) ? 'search=%q' : '&search=%q';

		$retval = '';

		ob_start();
	?>
		<div class="bg-inverse">
			<div class="container py-4">
				<div class="row py-lg-1">
					<div class="col-12 col-lg-auto mb-3 mb-lg-0 align-self-lg-center">
					<h2 class="h6 text-uppercase letter-spacing-3 mb-0"><span class="fa fa-search fa-2x text-primary mr-2" style="vertical-align: sub;"></span> Search for a Degree</h2>
					</div>
					<div class="col-12 col-lg">
						<?php echo do_shortcode( '[ucf-degree-search placeholder="' . $placeholder . '" form_action="" query_params="' . $query_params . '"]' ); ?>
					</div>
				</div>
			</div>
		</div>
	<?php
		$retval = ob_get_clean();
	}

	return $retval;
}


/**
 * Returns the markup for the the popular-programs section on vertical pages.
 * @author Jim Barnes
 * @since 1.0.0
 * @param object $post WP_Post object
 * @return string
 */
function online_get_popular_programs_markup( $post ) {
	$retval = '';

	if ( function_exists( 'sc_ucf_post_list' ) ) {

		$college      = get_field( 'vertical_college_filter', $post->ID );
		$program_type = get_field( 'vertical_program_type_filter', $post->ID );
		$interest     = get_field( 'vertical_interest_filter', $post->ID );

		$heading_text = get_field( 'popular_programs_text', $post->ID );

		$heading_text = isset( $heading_text ) ? $heading_text : 'Popular Online Programs';

		$args = array(
			'post_type'      => 'degree',
			'posts_per_page' => 3,
			'posts_per_row'  => 3,
			'layout'         => 'thumbnail',
			'tag'            => 'popular'
		);

		if ( $college ) {
			$args['tax_colleges'] = $college->slug;
			$args['tax_colleges__field'] = 'slug';
		}

		if ( $program_type ) {
			$args['tax_program_types'] = $program_type->slug;
			$args['tax_program_types__field'] = 'slug';
		}

		if ( $interest ) {
			$args['tax_interest'] = $interest->slug;
			$args['tax_interests__field'] = 'slug';
		}

		ob_start();
	?>
		<div class="bg-inverse">
			<div class="container py-4">
				<div class="row">
					<div class="col-lg-3">
						<h2 class="text-uppercase font-condensed mb-4 mb-md-2"><?php echo $heading_text; ?></h2>
					</div>
					<div class="col-lg-9">
						<?php echo sc_ucf_post_list( $args ); ?>
					</div>
				</div>
			</div>
		</div>
	<?php
		$retval = ob_get_clean();

	}

	return $retval;
}


/**
 * Modifies the sort order of grouped degree lists.
 *
 * Adapted from Online-Theme
 *
 * @author Jo Dickson
 * @since 1.0.0
 * @param array $items An array of grouped post data processed by the [degree-list] shortcode callback
 * @return array Modified array of post data
 */
function online_degree_list_sort_grouped_degrees( $items ) {
	$slugs = unserialize( ONLINE_DEGREE_PROGRAM_ORDER );
	$items_sorted = array();

	foreach ( $slugs as $slug ) {
		$term = get_term_by( 'slug', $slug, 'program_types' );
		if ( $term ) {
			$items_sorted[$term->name] = array();
		}
	}

	// Insert items in the desired order into $items_sorted.
	// Program types not specified in $slugs should be added to the end
	// of the sorted list.
	foreach ( $items as $item ) {
		$items_sorted[$item['term']['name']] = $item;
	}

	// Remove any empty sorted items
	$items_sorted = array_filter( $items_sorted );

	return $items_sorted;
}

add_filter( 'ucf_degree_list_sort_grouped_degrees', 'online_degree_list_sort_grouped_degrees', 10, 1 );
