<?php

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
 * Returns HTML markup for the primary site navigation for UCF Online.
 *
 * @author Jo Dickson
 * @since 1.0.0
 * @return void
 **/
function online_get_nav_markup() {
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

add_action( 'after_body_open', 'online_get_nav_markup', 10, 0 );


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
