<?php
/**
 * The standard site navigation
 */

global $post;

$title_elem      = ( is_home() || is_front_page() ) ? 'h1' : 'span';
$vertical_subnav = ( $post ) ? online_get_vertical_subnav( $post ) : '';
$sticky_class    = ( $vertical_subnav ) ? '' : 'sticky-top';
?>
<nav class="site-nav navbar navbar-toggleable-md navbar-custom navbar-light bg-primary <?php echo $sticky_class; ?>" aria-label="Site navigation">
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
?>
