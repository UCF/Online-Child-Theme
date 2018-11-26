<?php

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
 * Overrides UCF WordPress Theme function to return the markup for
 * page headers with media backgrounds.
 *
 * @author Jo Dickson
 * @since 1.0.0
 * @param object $obj A WP_Post or WP_Term object
 * @param array $videos Assoc. array of video Attachment IDs for use in page header media background
 * @param array $images Assoc. array of image Attachment IDs for use in page header media background
 * @return string HTML for the page header
 **/
function ucfwp_get_header_media_markup( $obj, $videos, $images ) {
	$field_id   = ucfwp_get_object_field_id( $obj );
	$videos     = $videos ?: ucfwp_get_header_videos( $obj );
	$images     = $images ?: ucfwp_get_header_images( $obj );
	$video_loop = get_field( 'page_header_video_loop', $field_id );
	$header_text_color   = get_field( 'page_header_text_color', $field_id );
	$header_content_type = get_field( 'page_header_content_type', $field_id );
	$header_height       = get_field( 'page_header_height', $field_id ) ?: 'header-media-default'; // for imported, unmodified pages
	$exclude_nav         = get_field( 'page_header_exclude_nav', $field_id );

	// We modify the header's text color using bg utilities to make sure we
	// still meet color contrast req's when bg imgs/videos fail to load
	$header_bg_class = '';
	switch ( $header_text_color ) {
		case 'white':
			$header_bg_class = 'bg-inverse';
			break;
		default:
			break;
	}

	ob_start();
?>
	<div class="header-media <?php echo $header_height; ?> <?php echo $header_bg_class; ?> mb-0 d-flex flex-column">
		<div class="header-media-background-wrap">
			<div class="header-media-background media-background-container">
				<?php
				// Display the media background (video + picture)

				if ( $videos ) {
					echo ucfwp_get_media_background_video( $videos, $video_loop );
				}
				if ( $images ) {
					$bg_image_srcs = ucfwp_get_header_media_picture_srcs( $header_height, $images );
					echo ucfwp_get_media_background_picture( $bg_image_srcs );
				}
				?>
			</div>
		</div>

		<?php
		// Display the inner header contents
		?>
		<div class="header-content">
			<div class="header-content-flexfix">
				<?php
				if ( $header_content_type === 'custom' ) {
					echo ucfwp_get_header_content_custom( $obj );
				}
				else {
					echo online_get_header_content_default( $obj );
				}
				?>
			</div>
		</div>

		<?php
		// Print a spacer div for headers with background videos (to make
		// control buttons accessible), and for headers showing a standard
		// title/subtitle to push them up a bit
		if ( $videos || $header_content_type === 'default' ):
		?>
		<div class="header-media-controlfix"></div>
		<?php endif; ?>
	</div>
<?php
	return ob_get_clean();
}


/**
 * Displays the primary site navigation for UCF Online.
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
		echo online_landing_page_header_bar_markup();
		return;
	}

	$title_elem      = ( is_home() || is_front_page() ) ? 'h1' : 'span';
	$vertical_subnav = ( $post ) ? online_get_vertical_subnav( $post ) : '';
	$sticky_class    = ( $vertical_subnav ) ? '' : 'sticky-top';

	ob_start();
?>
	<nav class="site-nav navbar navbar-toggleable-md navbar-custom navbar-light bg-primary <?php echo $sticky_class; ?>" role="navigation">
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
 * Returns a simplfied site navbar for use on Landing Pages.
 *
 * Adapted from Online-Theme
 *
 * @author Jim Barnes
 * @since 1.0.0
 * @return string
 */
function online_landing_page_header_bar_markup() {
	global $post;
	if ( ! $post ) { return; }

	$title = $post->post_title;

	ob_start();
?>
	<div class="bg-inverse">
		<div class="container">
			<div class="row align-items-center justify-content-between">
				<?php if ( $title ) : ?>
					<h1 class="col h3 mb-0"><?php echo $title; ?></h1>
				<?php endif; ?>
				<div class="col-1">
					<img src="<?php echo ONLINE_THEME_IMG_URL . '/ucf-tab.jpg'; ?>" alt="University of Central Florida">
				</div>
			</div>
		</div>
	</div>
<?php
	return ob_get_clean();
}


/**
 * Returns default inner content markup for page headers that
 * use a media background.
 *
 * @author Jo Dickson
 * @since 1.0.0
 * @param object $obj A WP_Post or WP_Term object
 * @return string HTML for the page title
 **/
function online_get_header_content_default( $obj ) {
	$title      = ucfwp_get_header_title( $obj );
	$title_elem = ( is_home() || is_front_page() ) ? 'h2' : 'h1';

	ob_start();

	if ( $title ):
		$form_markup = online_get_header_form_markup( $obj );
?>
	<div class="header-content-inner align-self-start pt-4 pt-md-5">
		<div class="container">
			<div class="row">
				<div class="<?php echo ( $form_markup ) ? 'header-title-container col-12 col-sm-8 offset-sm-4 col-md-6 offset-md-6 col-xl-4 offset-xl-4 mb-0' : 'col-12 mb-5'; ?> mb-sm-4 mb-md-5 mb-xl-0 mt-xl-4">
					<<?php echo $title_elem; ?> class="header-title mb-0 d-inline-block"><?php echo $title; ?></<?php echo $title_elem; ?>>
				</div>

				<?php if ( $form_markup ): ?>
				<div class="header-form-container col-sm-8 offset-sm-4 col-md-6 offset-md-6 col-xl-4 offset-xl-0 mt-lg-0">
					<?php echo $form_markup; ?>
				</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
<?php
	endif;

	return ob_get_clean();
}


/**
 * Returns markup for a header form.
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
		// TODO any other exceptions??

	}

	ob_start();
?>
	<div class="header-form bg-inverse mb-4 mb-lg-5 p-3 p-md-4">
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
