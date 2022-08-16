<?php

/**
 * Returns the child program_type assigned to the given degree.
 *
 * @since 1.5.0
 * @author Jo Dickson
 * @param object $degree  WP_Post object
 * @return mixed  WP_Term object, or null on failure
 */
function get_degree_program_type( $degree ) {
	$retval = null;
	$args   = array( 'childless' => true );
	$terms  = wp_get_post_terms( $degree->ID, 'program_types', $args );

	if ( !empty( $terms ) && ! is_wp_error( $terms ) ) {
		$retval = $terms[0];
	}

	return $retval;
}


/**
 * Splits a provided tuition string into two parts:
 * the tuition value, and "per" string (e.g. per credit hour).
 *
 * @since 1.5.0
 * @author Jo Dickson
 * @param string $tuition_val
 * @return array
 */
function get_degree_tuition_parts( $tuition ) {
	if ( ! $tuition ) return array();

	$tuition       = str_replace( '.00', '', $tuition );
	$tuition_parts = array();

	preg_match( '/^(\$[\d,.]+)/', $tuition, $tuition_parts );

	$tuition_val = $tuition_parts[1] ?? '';
	$tuition_per = trim( str_replace( $tuition_val, '', $tuition ) );

	return array(
		'value' => $tuition_val,
		'per'   => $tuition_per
	);
}


/**
 * Formats degree meta
 * @author Jim Barnes
 * @since 1.5.0
 * @param array $post_meta The post_meta array
 * @return array
 */
function online_format_degree_data( $post_meta ) {
	setlocale(LC_MONETARY, 'en_US');

	if ( isset( $post_meta['degree_avg_annual_earnings'] ) && ! empty( $post_meta['degree_avg_annual_earnings'] ) ) {
		$post_meta['degree_avg_annual_earnings'] = sprintf( '%01.2f', floatval( $post_meta['degree_avg_annual_earnings'] ) );
	}

	if ( isset( $post_meta['degree_employed_full_time'] ) && ! empty( $post_meta['degree_employed_full_time'] ) ) {
		$post_meta['degree_employed_full_time'] = number_format( floatval( $post_meta['degree_employed_full_time'] ) ) . '%';
	}

	if ( isset( $post_meta['degree_continuing_education'] ) && ! empty( $post_meta['degree_continuing_education'] ) ) {
		$post_meta['degree_continuing_education'] = number_format( floatval( $post_meta['degree_continuing_education'] ) ) . '%';
	}

	if ( isset( $post_meta['degree_prj_begin_employment'] ) &&  ! empty( 'degree_prj_begin_employment' ) ) {
		$post_meta['degree_prj_begin_employment'] = number_format( floatval( $post_meta['degree_prj_begin_employment'] ) );
	}

	if ( isset( $post_meta['degree_prj_end_employment'] ) &&  ! empty( $post_meta['degree_prj_end_employment'] ) ) {
		$post_meta['degree_prj_end_employment'] = number_format( floatval( $post_meta['degree_prj_end_employment'] ) );
	}

	if ( isset( $post_meta['degree_prj_change'] ) &&  ! empty( $post_meta['degree_prj_change'] ) ) {
		$post_meta['degree_prj_change'] = number_format( floatval( $post_meta['degree_prj_change'] ) );
	}

	if ( isset( $post_meta['degree_prj_change_percentage'] ) &&  ! empty( $post_meta['degree_prj_change_percentage'] ) ) {
		$post_meta['degree_prj_change_percentage'] = number_format( floatval( $post_meta['degree_prj_change_percentage'] ), 2 ) . '%';
	}

	if ( isset( $post_meta['degree_prj_openings'] ) && ! empty( $post_meta['degree_prj_openings'] ) ) {
		$post_meta['degree_prj_openings'] = number_format( floatval( $post_meta['degree_prj_openings'] ) );
	}

	return $post_meta;
}


/**
 * Returns a complete URL for the graduate RFI form, with
 * optional params.
 *
 * @author Jo Dickson
 * @since 1.5.0
 * @param array $params Assoc. array of query params + values to append to the URL string
 * @return mixed URL string, or null if the URL base or form ID aren't set
 */
function get_degree_request_info_url_graduate( $params=array() ) {
	$base = get_theme_mod_or_default( 'degrees_graduate_rfi_url_base' );
	if ( ! $base ) return null;

	$form_id = get_theme_mod_or_default( 'degrees_graduate_rfi_form_id' );
	if ( ! $form_id ) return null;

	$params['id'] = $form_id;
	$separator = ( strpos( $base, '?' ) !== false ) ? '&' : '?';

	$url = $base . $separator . http_build_query( $params );
	return $url;
}


/**
 * Returns true/false if the given degree $post represents
 * a program that cannot be completed on its own (must be
 * completed alongside a full program.)
 *
 * @since 1.5.0
 * @author Jo Dickson
 * @param object $post WP_Post object representing a degree post
 * @return boolean
 */
function is_supplementary_degree( $post ) {
	$is_supplementary = false;
	$terms = wp_get_post_terms( $post->ID, 'program_types' );

	foreach ( $terms as $term ) {
		if ( in_array( $term->slug, array( 'minor', 'undergraduate-certificate' ) ) ) {
			$is_supplementary = true;
			break;
		}
	}


	return $is_supplementary;
}


/**
 * Returns true/false if the given degree $post is
 * an undergraduate program.
 *
 * @since 1.5.0
 * @author Jo Dickson
 * @param object $post  WP_Post object
 * @return boolean
 */
function is_undergraduate_degree( $post ) {
	$is_undergraduate = false;
	$terms = wp_get_post_terms( $post->ID, 'program_types' );

	foreach ( $terms as $term ) {
		if ( $term->slug === 'undergraduate-program' ) {
			$is_undergraduate = true;
			break;
		}
	}
	return $is_undergraduate;
}


/**
 * Returns details for a promotional graphic to display
 * alongside degree catalog descriptions.
 *
 * @since 1.5.0
 * @author Jo Dickson
 * @param object $post WP_Post object
 * @return array
 */
function get_degree_promo( $post ) {
	$promo               = array();
	$disable_promo       = get_field( 'degree_disable_sidebar_promo', $post ) ?: false;
	$theme_mod_name_base = '';
	$promo_img           = null;

	if ( ! $disable_promo ) {
		if ( is_graduate_degree( $post ) ) {
			$theme_mod_name_base = 'degrees_sidebar_promo_graduate';
		} else if ( is_undergraduate_degree( $post ) ) {
			$theme_mod_name_base = 'degrees_sidebar_promo_undergraduate';
		}
	}

	if ( $theme_mod_name_base ) {
		$promo_img = get_theme_mod( $theme_mod_name_base );
	}

	if ( $promo_img ) {
		$promo = array(
			'img'        => $promo_img,
			'alt'        => get_theme_mod( $theme_mod_name_base . '_alt', '' ),
			'link_url'   => get_theme_mod( $theme_mod_name_base . '_link_url', '' ),
			'link_rel'   => get_theme_mod( $theme_mod_name_base . '_link_rel', '' ),
			'new_window' => get_theme_mod( $theme_mod_name_base . '_link_new_window', false ),
		);
	}

	return $promo;
}


/**
 * Returns an array of deadlines, grouped by deadline type
 * (e.g. domestic/transfer/international).
 *
 * If custom application deadlines are defined, they are
 * returned instead in an unnamed group.
 *
 * @since 1.5.0
 * @author Jo Dickson
 * @param object $post WP_Post object
 * @return array
 */
function get_degree_application_deadlines( $post ) {
	$deadlines = array();

	if ( have_rows( 'application_deadlines', $post ) ) {
		// Custom deadlines
		$custom_deadlines = get_field( 'application_deadlines', $post );
		foreach ( $custom_deadlines as $deadline ) {
			$deadlines[''][] = array(
				'term'     => $deadline['deadline_term'],
				'deadline' => $deadline['deadline']
			);
		}
	} else if ( have_rows( 'degree_application_deadlines', $post ) ) {
		// If a deadline group order is defined, use it:
		$group_order = array();
		if ( is_undergraduate_degree( $post ) ) {
			$group_order = array_filter( array_map( 'trim', explode( ',', get_theme_mod_or_default( 'degree_deadlines_undergraduate_deadline_order' ) ) ) );
		} else if ( is_graduate_degree( $post ) ) {
			$group_order = array_filter( array_map( 'trim', explode( ',', get_theme_mod_or_default( 'degree_deadlines_graduate_deadline_order' ) ) ) );
		}

		if ( $group_order ) {
			$deadlines = array_fill_keys( $group_order, array() );
		}

		// Assign imported deadlines to groups:
		$imported_deadlines = get_field( 'degree_application_deadlines', $post );
		foreach ( $imported_deadlines as $deadline ) {
			if (
				isset( $deadline['deadline_type'] )
				&& isset( $deadline['admission_term'] )
				&& isset( $deadline['deadline'] )
			) {
				$deadlines[$deadline['deadline_type']][] = array(
					'term'     => $deadline['admission_term'],
					'deadline' => $deadline['deadline']
				);
			}
		}

		// If $group_order contains an invalid group name, make sure
		// it doesn't generate an empty set of deadlines:
		$deadlines = array_filter( $deadlines );
	}

	return $deadlines;
}


/**
 * Returns true|false if program_type is a graduate program.
 *
 * @since 1.5.0
 * @author RJ Bruneel
 * @param object $post  WP_Post object
 * @return boolean
 */
function is_graduate_degree( $post ) {
	$is_graduate = false;
	$terms = wp_get_post_terms( $post->ID, 'program_types' );

	foreach ( $terms as $term ) {
		if ( $term->slug === 'graduate-program' ) {
			$is_graduate = true;
			break;
		}
	}
	return $is_graduate;
}


/**
 * Returns an array of career paths assigned to a degree.
 * Results are limited to a fixed amount and are randomized.
 *
 * @author Jim Barnes
 * @since 1.5.0
 * @param object $post WP_Post object
 * @return array
 */
function online_get_degree_careers( $post, $limit=20 ) {
	$careers = array();
	$terms   = array();

	if ( have_rows( 'degree_career_list', $post ) ) {
		while ( have_rows( 'degree_career_list', $post ) ) : the_row();
			$careers[] = trim( get_sub_field( 'degree_career_list_item' ) );
		endwhile;

		$careers = array_filter( $careers );
	}

	if ( ! $careers ) {
		$terms = wp_get_post_terms(
			$post->ID,
			'career_paths',
			array(
				'fields' => 'id=>name'
			)
		);

		if ( ! is_wp_error( $terms ) ) {
			shuffle( $terms );

			if ( $limit > 0 ) {
				$terms = array_slice( $terms, 0, $limit );
			}

			usort( $terms, function( $a, $b ) {
				return strcmp( $a, $b );
			} );

			$careers = $terms;
		}
	}

	return $careers;
}


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
		<dt class="h6 text-uppercase lavender">Type</dt>
		<dd class="h5 mb-4"><?php echo $program_type->name; ?></dd>
		<?php endif; ?>

		<?php if ( $college ): ?>
		<dt class="h6 text-uppercase lavender">College</dt>
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
 * Returns HTML markup for a single degree's duration and credit hour info.
 *
 * @since 1.0.0
 * @author Jo Dickson
 * @param object $degree WP_Post object for a single degree
 * @return string HTML markup for a single degree's duration and credit hour info
 */
function online_get_degree_duration_markup( $degree ) {
	$credit_hours      = get_field( 'degree_hours', $degree );
	$duration          = get_field( 'degree_duration', $degree );
	$is_duration_set   = !empty( $duration['degree_duration_amount'] );
	$font_icon_classes = $duration['degree_duration_font_icon_classes'] ?: 'fa fa-3x fa-desktop';

	ob_start();
	if ( $credit_hours ):
?>
	<div class="card h-100">
		<div class="card-block text-center d-flex flex-column justify-content-center px-sm-4">
			<span class="<?php echo $font_icon_classes; ?> text-primary mb-2" aria-hidden="true"></span>
			<?php if ( $credit_hours && $is_duration_set ):
				$duration_amount     = $duration['degree_duration_amount'];
				$duration_descriptor = $duration['degree_duration_descriptor'] ?: 'Months';
				$duration_notice     = $duration['degree_duration_notice'] ?: 'Approx. program length';
			?>
			<strong class="d-block display-4"><?php echo $duration_amount; ?></strong>
			<span class="d-block text-uppercase text-nowrap font-weight-bold"><?php echo $duration_descriptor; ?></span>
			<span class="d-block small"><?php echo $duration_notice; ?></span>
			<span class="d-block mt-4"><span class="font-weight-bold"><?php echo $credit_hours; ?></span> Credit Hours</span>
			<?php else: ?>
			<strong class="d-block display-4"><?php echo $credit_hours; ?></strong>
			<span class="d-block small text-uppercase text-nowrap">Credit Hours</span>
			<?php endif; ?>
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

	$tuition_details      = get_field( 'degree_tuition_details', $degree );
	$tuition_details_link = $tuition_details['degree_tuition_details_link'];
	$tuition_details_text = $tuition_details['degree_tuition_details_text'];

	ob_start();
	if ( $resident_tuition || $nonresident_tuition ):
?>
	<div class="card h-100 text-center">
		<div class="card-header" role="navigation">
			<ul class="nav nav-tabs card-header-tabs" id="tuition-tabs">
				<?php if ( $resident_tuition ): ?>
				<li class="nav-item text-nowrap" role="presentation">
					<a class="nav-link active" id="resident-tuition-tab" data-toggle="tab" href="#resident-tuition" aria-controls="resident-tuition" aria-selected="true">
						In State<span class="sr-only"> Tuition</span>
					</a>
				</li>
				<?php endif; ?>

				<?php if ( $nonresident_tuition ): ?>
				<li class="nav-item text-nowrap" role="presentation">
					<a class="nav-link <?php if ( ! $resident_tuition ){ ?>active<?php } ?>" id="nonresident-tuition-tab" data-toggle="tab" href="#nonresident-tuition" aria-controls="nonresident-tuition" aria-selected="<?php echo ( ! $resident_tuition ) ? 'true' : 'false'; ?>">
						Out of State<span class="sr-only"> Tuition</span>
					</a>
				</li>
				<?php endif; ?>
			</ul>
		</div>
		<div class="card-block d-flex flex-column justify-content-center px-sm-4 px-md-2 px-xl-3 pt-4 py-md-5 pt-lg-4 pb-lg-3 tab-content" id="tuition-panes">
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

			<?php if ( $tuition_details_link && $tuition_details_text ) : ?>
			<a class="small mt-2 mt-md-3" href="<?php echo $tuition_details_link; ?>"><?php echo $tuition_details_text; ?></a>
			<?php elseif ( $tuition_details_text ): ?>
			<span class="small mt-2 mt-md-3"><?php echo $tuition_details_text; ?></span>
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
 * Returns markup for a single degree's video caption.
 *
 * @since 1.2.3
 * @author RJ Bruneel
 * @param object $degree WP_Post object for a single degree
 * @return string HTML markup for a single degree's video caption
 */
function online_get_degree_video_caption_markup( $degree ) {
	$video_caption = get_field( 'degree_video_caption', $degree );

	ob_start();
	if ( $video_caption ):
?>
	<p class="degree-video-caption">
		<?php echo $video_caption; ?>
	</p>
<?php
	endif;
	return ob_get_clean();
}

/**
 * Returns markup for a single degree's spotlight.
 *
 * @since 1.2.3
 * @author RJ Bruneel
 * @param object $degree WP_Post object for a single degree
 * @return string HTML markup for a single degree's spotlight
 */
function online_get_degree_spotlight_markup( $degree ) {
	$spotlight = get_field( 'degree_spotlight', $degree );

	ob_start();
	if ( $spotlight ):
?>
	<div class="mt-5">
		<?php echo $spotlight; ?>
	</div>
<?php
	endif;
	return ob_get_clean();
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
