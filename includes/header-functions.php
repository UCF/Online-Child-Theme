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
 * Modifies how the UCF WordPress theme determines the header type to
 * use for the given object's page header.
 *
 * @author Jo Dickson
 * @since 1.2.0
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
 * @since 1.2.0
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

	// TODO add modifications for landing pages

	return $content_type;
}

add_filter( 'ucfwp_get_header_content_type', 'online_get_header_content_type', 10, 2 );


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
