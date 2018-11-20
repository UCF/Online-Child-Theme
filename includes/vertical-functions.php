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
	// TODO update navbar markup for Athena Framework compat.
?>
<nav class="navbar-subnav" role="navigation">
	<div class="navbar navbar-default" id="secondary-nav">
		<div class="container">
			<div class="navbar-header">
				<a class="navbar-brand" href="<?php echo $brand_url; ?>"><?php echo wptexturize( $brand ); ?></a>
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#secondary-menu">
					<span class="navbar-toggle-text">Sections</span>
					<span class="fa fa-bars" aria-hidden="true"></span>
				</button>
			</div>
			<div class="collapse navbar-collapse navbar-right" id="secondary-menu">
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
function online_get_vertical_popular_programs_markup( $post ) {
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
 * Returns a subfooter for a vertical page or its child content.
 *
 * Adapted from Online-Theme
 *
 * @param object $post WP Post object
 * @return string subfooter markup
 *
 * @author Jo Dickson
 * @since 1.0.0
 */
function online_get_vertical_subfooter( $post ) {
	$vertical_id = online_get_post_vertical_id( $post );
	if ( !$vertical_id ) { return false; }

	$vertical    = get_post( $vertical_id );
	if ( !$vertical ) { return false; }

	$form_id     = get_field( 'vertical_contact_form', $vertical_id );
	$degrees_tag = get_term( get_field( 'vertical_degrees_tag', $vertical_id ) );

	// Require at least a list of degrees to continue
	if ( $degrees_tag ) {
		$degrees_tag = $degrees_tag->slug;
	}
	else {
		return false;
	}

	ob_start();
?>
<div class="container" id="degrees">
	<?php
	// Display divider above subfooter on child pages/posts
	if ( $vertical_id !== $post->ID ):
	?>
	<hr>
	<?php endif; ?>

	<div class="row">
		<div class="<?php echo ( $vertical_id !== $post->ID ) ? 'col-md-8' : 'col-md-12'; ?>">
			<h2><?php echo $vertical->post_title; ?> Degrees</h2>
			<?php echo do_shortcode( '[degree-list title="" filter_by_tax="post_tag" terms="' . $degrees_tag . '" groupby="program_types" groupby_field="program_types_alias"]' ); ?>
		</div>

		<?php
		// Display Vertical form in subfooter on child pages/posts
		if ( $vertical_id !== $post->ID ):
		?>
		<div class="col-md-4 mt-3 mt-md-0">
			<?php if ( $form_id ) : ?>
			<div class="degree-cta-section">
				<h2 class="h3 text-center mt-0">Request Info <span class="fa fa-envelope ml-2" aria-hidden="true"></span></h2>
				<?php echo do_shortcode( '[gravityform id="' . $form_id . '" ajax="true" title="false" description="true"]' ); ?>
			</div>
			<?php endif; ?>
		</div>
		<?php endif; ?>
	</div>
</div>
<?php
	return ob_get_clean();
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
