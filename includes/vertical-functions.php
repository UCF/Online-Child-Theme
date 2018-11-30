<?php

/**
 * Returns a vertical ID related to the given post object.
 *
 * Adapted from Online-Theme
 *
 * @param object $post WP Post object
 * @return mixed Vertical page ID, or false on failure
 *
 * @author Jo Dickson
 * @since 1.0.0
 */
function online_get_post_vertical_id( $post ) {
	$vertical_id = false;
	if ( $post->post_type === 'page' && get_post_meta( $post->ID, '_wp_page_template', true ) === 'template-vertical.php' ) {
		$vertical_id = $post->ID;
	}
	else {
		$vertical_id = get_field( 'post_vertical', $post->ID ) ?: false;
	}

	return $vertical_id;
}


/**
 * Returns a subnav navbar for a vertical page or its child content.
 *
 * Adapted from Online-Theme
 *
 * @param object $post WP Post object
 * @return string subnav navbar markup
 *
 * @author Jo Dickson
 * @since 1.0.0
 */
function online_get_vertical_subnav( $post ) {
	$vertical_id = online_get_post_vertical_id( $post );
	$vertical    = get_post( $vertical_id );
	if ( !$vertical_id || !$vertical ) { return false; }

	$submenu_id = get_field( 'vertical_submenu', $vertical_id );
	if ( !$submenu_id ) { return false; }

	$brand     = get_field( 'vertical_submenu_brand', $vertical_id ) ?: $vertical->post_title;
	$brand_url = get_permalink( $vertical_id );

	ob_start();
?>
<nav class="navbar navbar-toggleable-md navbar-light bg-faded sticky-top navbar-subnav" id="secondary-nav" role="navigation">
	<div class="container">
		<a class="navbar-brand" href="<?php echo $brand_url; ?>">
			<?php echo wptexturize( $brand ); ?>
		</a>
		<button type="button" class="navbar-toggler collapsed" data-toggle="collapse" data-target="#secondary-menu" aria-controls="secondary-menu" aria-expanded="false" aria-label="Toggle sub-navigation">
			<span class="navbar-toggler-text">Sections</span>
			<span class="navbar-toggler-icon" aria-hidden="true"></span>
		</button>
		<div class="collapse navbar-collapse" id="secondary-menu">
			<?php
			wp_nav_menu( array(
				'container'       => 'false',
				'depth'           => 2,
				'fallback_cb'     => 'bs4Navwalker::fallback',
				'menu'            => $submenu_id,
				'menu_class'      => 'nav navbar-nav ml-md-auto',
				'walker'          => new bs4Navwalker()
			) );
			?>
		</div>
	</div>
</nav>
<?php
	return ob_get_clean();
}


/**
 * Returns the markup for the ucf-degree-typeahead for
 * the simple vertical template.
 *
 * @author Jim Barnes
 * @since 1.0.0
 * @param object $post WP_Post object
 * @return string
 */
function online_get_vertical_degree_typeahead_markup( $post ) {
	if ( class_exists( 'UCF_Degree_Search_Common' ) ) {
		$college      = get_field( 'vertical_college_filter', $post->ID );
		$program_type = get_field( 'vertical_program_type_filter', $post->ID );
		$interest     = get_field( 'vertical_interest_filter', $post->ID );
		$tag          = get_field( 'vertical_degrees_tag', $post->ID );
		$placeholder  = get_field( 'degree_search_placeholder', $post->ID );
		$atts = array();

		if ( $college ) $atts['colleges'] = $college->slug;
		if ( $program_type ) $atts['program_types'] = $program_type->slug;
		if ( $interest ) $atts['interests'] = $interest->slug;
		if ( $tag ) $atts['post_tag'] = $tag->slug;

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
function online_get_vertical_popular_programs_markup( $post ) {
	$retval = '';

	if ( function_exists( 'sc_ucf_post_list' ) ) {

		$mode         = get_field( 'popular_programs_config', $post->ID );

		$college      = get_field( 'vertical_college_filter', $post->ID );
		$program_type = get_field( 'vertical_program_type_filter', $post->ID );
		$interest     = get_field( 'vertical_interest_filter', $post->ID );
		$tag          = get_field( 'vertical_degrees_tag', $post->ID );

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
			$args['tax_interests'] = $interest->slug;
			$args['tax_interests__field'] = 'slug';
		}

		$tags = array();

		if ( $tag ) {
			$tags[] = $tag->slug;
		}

		if ( $mode === 'tag_override' ) {
			$override_tags = get_field( 'popular_programs_tag_override', $post->ID );
			foreach( $override_tags as $ot ) {
				$tags[] = $ot->slug;
			}
		} else {
			$tags[] = 'popular';
		}

		if ( count( $tags ) > 1 ) {
			$args['post_tag__and'] = implode( ',', $tags );
		} else {
			// We still have to parse the array, and this is the safest way
			$args['tag'] = implode( ',', $tags );
		}

		if ( $mode === 'manual' ) {
			$posts = get_field( 'popular_programs_programs', $post->ID );

			$args = array(
				'post_type'      => 'degree',
				'posts_per_page' => 3,
				'posts_per_row'  => 3,
				'layout'         => 'thumbnail',
				'post__in'       => implode( ',', $posts )
			);
		}

		ob_start();
	?>
		<div class="bg-inverse">
			<div class="container py-4">
				<div class="row">
					<div class="col-lg-4 col-xl-3 align-self-lg-center">
						<h2 class="h3 text-uppercase font-condensed mb-4 mb-lg-2"><?php echo $heading_text; ?></h2>
					</div>
					<div class="col-lg-8 col-xl-9">
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
 * Updates the Vertical Children Fields > Related Vertical ACF field's
 * available options to only include pages with the 'Vertical' template
 *
 * Adapted from Online-Theme
 *
 * @author Jo Dickson
 * @since 1.0.0
 */

function online_related_vertical_query( $args, $field, $post ) {
	$args['meta_query'] = array(
		array(
			'key' => '_wp_page_template',
			'value' => 'template-vertical.php',
			'compare' => '='
		)
	);
	return $args;
}

add_filter( 'acf/fields/post_object/query/name=post_vertical', 'online_related_vertical_query', 10, 3 );


/**
 * Filter permalinks for people that have an assigned vertical.
 *
 * People with an assigned vertical should have the vertical's slug and
 * "/person/" prefixed before the person's slug, e.g.
 * "www.ucf.edu/online/healthcare/person/person-slug/".
 *
 * People with no vertical should use a normal permalink structure, e.g.
 * "www.ucf.edu/online/person/person-slug/"
 *
 * Adapted from Online-Theme
 *
 * @author Jo Dickson
 * @since 1.0.0
 */

function online_vertical_person_replace_link( $url, $post ) {
	if ( $post && $post->post_type === 'person' ) {
		$vertical_id = online_get_post_vertical_id( $post );
		if ( !$vertical_id ) { return $url; }

		$vertical = get_post( $vertical_id );
		if ( !$vertical ) { return $url; }

		return home_url( $vertical->post_name . '/person/' . $post->post_name );
	} else {
		return $url;
	}
}

add_filter( 'post_type_link', 'online_vertical_person_replace_link', 1, 2 );


/**
 * Filter permalinks for standard posts that have an assigned vertical.
 *
 * Posts with an assigned vertical should have the vertical's slug and "/news/"
 * prefixed before the post's slug, e.g.
 * "www.ucf.edu/online/healthcare/news/post-slug/".
 *
 * Posts with no vertical should use a normal permalink structure, e.g.
 * "www.ucf.edu/online/post-slug/"
 *
 * Adapted from Online-Theme
 *
 * @author Jo Dickson
 * @since 1.0.0
 */

function online_vertical_post_replace_link( $url, $post ) {
	if ( $post && $post->post_type === 'post' ) {
		$vertical_id = online_get_post_vertical_id( $post );
		if ( !$vertical_id ) { return $url; }

		$vertical = get_post( $vertical_id );
		if ( !$vertical ) { return $url; }

		return home_url( $vertical->post_name . '/news/' . $post->post_name );
	} else {
		return $url;
	}
}

add_filter( 'post_link', 'online_vertical_post_replace_link', 1, 2 );


/**
 * Adds permalink rewrite rules for all verticals' related people and posts.
 *
 * Adapted from Online-Theme
 *
 * @see online_vertical_post_replace_link()
 * @see online_vertical_person_replace_link()
 * @author Jo Dickson
 * @since 1.0.0
 */

function online_verticals_rewrite_rule() {
	$verticals = get_posts( array(
		'post_type'   => 'page',
		'numberposts' => -1,
		'meta_query'  => array(
			array(
				'key'     => '_wp_page_template',
				'value'   => 'template-vertical.php',
				'compare' => '='
			)
		)
	) );

	if ( $verticals ) {
		foreach ( $verticals as $vertical ) {
			// People
			add_rewrite_rule( $vertical->post_name . '/person/([^/]+)/?$', 'index.php?post_type=person&name=$matches[1]', 'bottom' );

			// Posts (news)
			add_rewrite_rule( $vertical->post_name . '/news/([^/]+)/?$', 'index.php?post_type=post&name=$matches[1]', 'bottom' );
		}
	}
}

add_action( 'init', 'online_verticals_rewrite_rule', 10, 0 );
