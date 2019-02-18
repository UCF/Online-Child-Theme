<?php

/**
 * Overrides how term/post object h1 text is determined in the parent theme.
 *
 * @author Jo Dickson
 * @since 1.0.0
 * @param string $title The determined page title string
 * @param object $obj A WP_Post or WP_Term object
 * @return string The modified page title string
 */
function online_get_header_title_after( $title, $obj ) {
	// Forces the parent theme to return an empty value for single Person
	// h1's.  Prevents duplicate h1's on the single Person template.
	if ( $obj instanceof WP_Post && $obj->post_type === 'person' ) {
		$title = '';
	}

	return $title;
}

add_filter( 'ucfwp_get_header_title_after', 'online_get_header_title_after', 10, 2 );


/**
 * Short-circuit this function from the UCF WordPress Theme to always
 * return 'title', since page header subtitles are not used in this theme.
 *
 * @author Jo Dickson
 * @since 1.0.0
 * @param object $obj A WP_Post or WP_Term object
 * @return string Option value for the designated page header h1
 **/
function ucfwp_get_header_h1_option( $obj ) {
	return 'title';
}


/**
 * Overrides the UCF WordPress Theme function for returning a site nav to
 * display nothing.  We do this to more easily implement a custom navbar
 * in this theme, positioned outside of the .site-header elem.
 *
 * @author Jo Dickson
 * @since 1.0.0
 * @param bool $image Whether or not a media background is present in the page header (unused)
 * @return string Nav HTML
 */
function ucfwp_get_nav_markup( $image=true ) {
	return '';
}


/**
 * Modifies how the UCF WordPress theme determines the header type to
 * use for the given object's page header.
 *
 * @author Jo Dickson
 * @since TODO
 * @param string $header_type The determined header type slug (corresponds to a "header-" template part slug)
 * @param mixed $obj A queried object (e.g. WP_Post, WP_Term), or null
 * @return string The determined header type slug
 */
function online_get_header_type( $header_type, $obj ) {
	// TODO modify for landing pages
	return $header_type;
}

add_filter( 'ucfwp_get_header_type', 'online_get_header_type', 10, 2 );


/**
 * Modifies how the UCF WordPress theme determines the header content
 * type to use for the given object's page header.
 *
 * @author Jo Dickson
 * @since TODO
 * @param string $content_type The determined header content type slug (corresponds to a "header_content-" template part slug)
 * @param mixed $obj A queried object (e.g. WP_Post, WP_Term), or null
 * @return string The determined header content type slug
 */
function online_get_header_content_type( $content_type, $obj ) {
	$header_type  = ucfwp_get_header_type( $obj );

	// Required for compatibility with existing content type names:
	// if $header_content_type is empty or set to 'default',
	// update it to 'title_form' instead
	if ( $header_type === 'media' && in_array( $content_type, array( '', 'default' ) ) ) {
		$content_type = 'title_form';
	}

	return $content_type;
}

add_filter( 'ucfwp_get_header_content_type', 'online_get_header_content_type', 10, 2 );


/**
 * Displays the primary site navigation for UCF Online.
 *
 * TODO move to template part
 *
 * NOTE: This function intentionally echoes its output, rather than
 * returning a string, because we register this function as an action on the
 * `after_body_open` hook.
 *
 * @author Jo Dickson
 * @since 1.0.0
 * @return void
 **/
function online_nav_markup() {
	global $post;

	if ( $post && $post->post_type === 'landing' ) {
		return;
	}

	$title_elem      = ( is_home() || is_front_page() ) ? 'h1' : 'span';
	$vertical_subnav = ( $post ) ? online_get_vertical_subnav( $post ) : '';
	$sticky_class    = ( $vertical_subnav ) ? '' : 'sticky-top';

	ob_start();
?>
	<nav class="site-nav navbar navbar-toggleable-md navbar-custom navbar-light bg-primary <?php echo $sticky_class; ?>" role="navigation" aria-label="Site navigation">
		<div class="container d-flex flex-row flex-nowrap justify-content-between">
			<<?php echo $title_elem; ?> class="mb-0">
				<a class="navbar-brand font-weight-black text-uppercase letter-spacing-1 mr-lg-4" href="<?php echo get_home_url(); ?>"><?php echo bloginfo( 'name' ); ?></a>
			</<?php echo $title_elem; ?>>
			<button class="navbar-toggler ml-auto align-self-start collapsed" type="button" data-toggle="collapse" data-target="#header-menu" aria-controls="header-menu" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-text">Menu</span>
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse align-self-lg-stretch" id="header-menu">
				<?php
				if ( has_nav_menu( 'header-menu' ) ) {
					wp_nav_menu( array(
						'container'       => '',
						'depth'           => 2,
						'fallback_cb'     => 'bs4Navwalker::fallback',
						'menu_class'      => 'nav navbar-nav ml-md-auto',
						'theme_location'  => 'header-menu',
						'walker'          => new bs4Navwalker()
					) );
				}
				?>
			</div>
		</div>
	</nav>
<?php
	// If this is a standard Vertical or Vertical Child,
	// display its subnav:
	if ( $vertical_subnav ) { echo $vertical_subnav; }

	echo ob_get_clean();
}

add_action( 'after_body_open', 'online_nav_markup', 10, 0 );


/**
 * Returns markup for a header form.
 *
 * TODO move to template part
 *
 * @since 1.0.0
 * @author Jo Dickson
 * @param object $obj A WP_Post or WP_Term object
 * @return string HTML for the header form
 */
function online_get_header_form_markup( $obj ) {
	$field_id            = ucfwp_get_object_field_id( $obj );
	$form_id             = null;
	$obj_custom_form_val = get_field( 'page_header_form_option', $field_id );
	$form                = null;

	// Determine which form to retrieve
	if ( $obj_custom_form_val !== 'none' ) {
		// Retrieve a custom form ID first
		if ( $obj_custom_form_val === 'custom' ) {
			$form_id = get_field( 'page_header_form_custom', $field_id );
		}

		// If we can't retrieve a custom form ID, or if
		// the page is configured to just use the default form,
		// use the default
		if (
			( $obj_custom_form_val === 'custom' && ! $form_id )
			|| $obj_custom_form_val === 'default'
			|| ! $obj_custom_form_val
		) {
			$form_id = online_get_theme_mod_or_default( 'default_header_form' );
		}
	}

	// Retrieve the form by ID
	if ( $form_id && class_exists( 'GFAPI' ) ) {
		$form = GFAPI::get_form( $form_id );
	}

	// Stop now if we can't retrieve the actual form object
	if ( ! $form ) { return ''; }

	$form_title = $form['title'];
	$form_desc  = $form['description'];
	$show_title = true;
	$show_desc  = true;

	// Modify how the form title and description are displayed
	// for specific types of content
	if ( $obj instanceof WP_Post ) {
		// Single degrees
		if ( $obj->post_type === 'degree' ) {
			$form_title = 'Get More Information';
			$form_desc  = "Fill out the form below, and we'll send you more information about the <strong>{$obj->post_title}</strong> program.";
		}
		// Vertical Children
		else if ( get_field( 'post_vertical', $obj->ID ) ) {
			$form_title = 'Request Info <span class="fa fa-envelope ml-2" aria-hidden="true"></span>';
		}
		// Standard Vertical
		else if ( get_post_meta( $obj->ID, '_wp_page_template', true ) === 'template-vertical.php' ) {
			$show_title = false;
		}
	}

	ob_start();
?>
	<div class="header-form bg-inverse-t-4 mb-4 mb-lg-5 p-3 p-md-4">
		<?php if ( $show_title ): ?>
		<h2 class="h5 text-center">
			<?php echo $form_title; ?>
		</h2>
		<?php endif; ?>

		<?php if ( $show_desc ): ?>
		<div class="">
			<?php echo wptexturize( $form_desc ); ?>
		</div>
		<?php endif; ?>

		<?php echo do_shortcode( '[gravityform id="' . $form_id . '" title="false" description="false" ajax="true"]' ); ?>
	</div>
<?php
	return ob_get_clean();
}
