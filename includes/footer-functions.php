<?php
/**
 * Footer Related Functions
 **/

/**
 * Returns markup for the site footer. Will return an empty string if all
 * footer sidebars are empty.
 *
 * @author Jo Dickson
 * @since 1.0.0
 * @return string Footer HTML markup
 **/
function ucfwp_get_footer_markup() {
	ob_start();

	global $post;

	// Display vertical subfooter for Standard Verticals and Vertical Children
	$vertical_subfooter = '';
	if ( $post ) {
		$vertical_subfooter = online_get_vertical_subfooter( $post ) ?: '';
	}
	echo $vertical_subfooter;

	// Display subfooter section on pages
	$subfooter_section = online_get_theme_mod_or_default( 'subfooter_section' );
	if ( is_page() && $subfooter_section && shortcode_exists( 'ucf-section' ) ):
?>
	<div class="site-subfooter py-4 py-md-5">
		<?php echo do_shortcode( '[ucf-section id="' . $subfooter_section . '"]' ); ?>
	</div>
<?php
	endif;

	// Display primary site footer
	if (
		is_active_sidebar( 'footer-col-1' )
		|| is_active_sidebar( 'footer-col-2' )
		|| is_active_sidebar( 'footer-col-3' )
		|| is_active_sidebar( 'footer-col-4' )
	):
?>
	<footer class="site-footer bg-inverse-t-3 pt-4 py-md-5">
		<div class="container mt-4">
			<div class="row">

			<?php if ( is_active_sidebar( 'footer-col-2' ) ): ?>
				<section class="col-12 col-lg-3">
					<?php dynamic_sidebar( 'footer-col-2' ); ?>
				</section>
			<?php endif; ?>

			<?php if ( is_active_sidebar( 'footer-col-3' ) ): ?>
				<section class="col-12 col-lg-3">
					<?php dynamic_sidebar( 'footer-col-3' ); ?>
				</section>
			<?php endif; ?>

			<?php if ( is_active_sidebar( 'footer-col-4' ) ): ?>
				<section class="col-12 col-lg-3">
					<?php dynamic_sidebar( 'footer-col-4' ); ?>
				</section>
			<?php endif; ?>

			<?php if ( is_active_sidebar( 'footer-col-1' ) ): ?>
				<section class="col-12 col-lg-3 flex-lg-first">
					<?php dynamic_sidebar( 'footer-col-1' ); ?>
				</section>
			<?php endif; ?>

			</div>

			<?php
			if ( has_nav_menu( 'footer-menu' ) ) {
				wp_nav_menu( array(
					'container'       => '',
					'depth'           => 1,
					'fallback_cb'     => 'bs4Navwalker::fallback',
					'menu_class'      => 'site-footer-menu nav flex-column flex-lg-row align-items-center justify-content-center align-items-start text-uppercase mb-3 mt-lg-3',
					'theme_location'  => 'footer-menu',
					'walker'          => new bs4Navwalker()
				) );
			}
			?>

			<div class="text-center text-uppercase mb-5">
				<a class="text-primary" href="https://www.ucf.edu/" rel="nofollow">&copy; <?php echo date( 'Y' ); ?> University of Central Florida</a>
			</div>
		</div>
	</footer>
<?php
	endif;

	return ob_get_clean();
}
