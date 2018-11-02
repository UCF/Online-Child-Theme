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
	$title_elem = ( is_home() || is_front_page() ) ? 'h1' : 'span';

	ob_start();
?>
	<nav class="site-nav navbar navbar-toggleable-md navbar-custom navbar-light bg-primary sticky-top" role="navigation">
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
						'menu_class'      => 'nav navbar-nav ml-md-auto mr-lg-4',
						'theme_location'  => 'header-menu',
						'walker'          => new bs4Navwalker()
					) );
				}
				?>
				<?php
				$navbar_cta_text = online_get_navbar_cta_text();
				if ( $navbar_cta_text ):
				?>
				<div class="pb-3 pb-lg-2 pt-lg-2 my-auto mx-3 mx-lg-0">
					<button class="btn btn-complementary btn-block">
						<?php echo $navbar_cta_text; ?>
					</button>
				</div>
				<?php endif; ?>
			</div>
		</div>
	</nav>
<?php
	echo ob_get_clean();
}

add_action( 'after_body_open', 'online_nav_markup', 10, 0 );


/**
 * Returns formatted text to display within the site navbar's
 * call-to-action button.
 *
 * @since 1.0.0
 * @author Jo Dickson
 * @return string
 */
function online_get_navbar_cta_text() {
	$val = online_get_theme_mod_or_default( 'site_navbar_cta_text' );
	if ( $val ) {
		return wptexturize( $val );
	}
	return '';
}


/**
 * Returns inner markup for page headers that use a media background.
 * Despite the function name, subtitles are not used in this theme.
 *
 * @author Jo Dickson
 * @since 1.0.0
 * @param object $obj A WP_Post or WP_Term object
 * @return string HTML for the page title
 **/
function ucfwp_get_header_content_title_subtitle( $obj ) {
	$title      = ucfwp_get_header_title( $obj );
	$title_elem = ( is_home() || is_front_page() ) ? 'h2' : 'h1';

	ob_start();

	if ( $title ):
?>
	<div class="header-content-inner align-self-start pt-4 pt-md-5">
		<div class="container">
			<div class="row">
				<div class="col-9 offset-3 col-md-8 offset-md-4">
					<div class="row">
						<div class="col">
							<<?php echo $title_elem; ?>><?php echo $title; ?></<?php echo $title_elem; ?>>
						</div>
						<?php // TODO add header form here in .col if present ?>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php
	endif;

	return ob_get_clean();
}
