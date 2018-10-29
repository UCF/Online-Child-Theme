<?php

/**
 * Handle all theme configuration here
 **/
define( 'ONLINE_THEME_URL', get_stylesheet_directory_uri() );
define( 'ONLINE_THEME_STATIC_URL', ONLINE_THEME_URL . '/static' );
define( 'ONLINE_THEME_CSS_URL', ONLINE_THEME_STATIC_URL . '/css' );
define( 'ONLINE_THEME_JS_URL', ONLINE_THEME_STATIC_URL . '/js' );
define( 'ONLINE_THEME_IMG_URL', ONLINE_THEME_STATIC_URL . '/img' );
define( 'ONLINE_THEME_CUSTOMIZER_PREFIX', 'online_' );
define( 'ONLINE_THEME_CUSTOMIZER_DEFAULTS', serialize( array(
	'site_navbar_cta_text' => 'Get Started'
) ) );


/**
 * Initialization functions to be fired early when WordPress loads the theme.
 *
 * @author Jo Dickson
 * @since 1.0.0
 */
function online_init() {
	// Un-register sidebars provided by UCF WordPress Theme, then re-register
	// to set custom descriptions and markup adjustments
	unregister_sidebar( 'footer-col-1' );
	unregister_sidebar( 'footer-col-2' );
	unregister_sidebar( 'footer-col-3' );
	unregister_sidebar( 'footer-col-4' );

	register_sidebar( array(
		'name'          => __( 'Footer - Column 1' ),
		'id'            => 'footer-col-1',
		'description'   => 'First column in the site footer. Shifts to the very bottom of the page at smaller screen sizes.',
		'before_widget' => '<div id="%1$s" class="widget mb-5 %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h2 class="widget-heading heading-underline letter-spacing-3">',
		'after_title'   => '</h2>',
	) );
	register_sidebar( array(
		'name'          => __( 'Footer - Column 2' ),
		'id'            => 'footer-col-2',
		'description'   => 'Second column in the site footer.',
		'before_widget' => '<div id="%1$s" class="widget mb-5 %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h2 class="widget-heading heading-underline letter-spacing-3">',
		'after_title'   => '</h2>',
	) );
	register_sidebar( array(
		'name'          => __( 'Footer - Column 3' ),
		'id'            => 'footer-col-3',
		'description'   => 'Third column in the site footer.',
		'before_widget' => '<div id="%1$s" class="widget mb-5 %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h2 class="widget-heading heading-underline letter-spacing-3">',
		'after_title'   => '</h2>',
	) );
	register_sidebar( array(
		'name'          => __( 'Footer - Column 4' ),
		'id'            => 'footer-col-4',
		'description'   => 'Last column in the site footer (third at smaller screen sizes).',
		'before_widget' => '<div id="%1$s" class="widget mb-5 %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h2 class="widget-heading heading-underline letter-spacing-3">',
		'after_title'   => '</h2>',
	) );
}

add_action( 'after_setup_theme', 'online_init', 11 );


/**
 * Defines sections used in the WordPress Customizer.
 *
 * @author Jo Dickson
 * @since 1.0.0
 */
function online_define_customizer_sections( $wp_customize ) {
	// Remove Navigation Settings section from UCF WP Theme since we don't
	// utilize the fallback Main Site navigation in this theme
	if ( defined( 'UCFWP_THEME_CUSTOMIZER_PREFIX' ) ) {
		$wp_customize->remove_section( UCFWP_THEME_CUSTOMIZER_PREFIX . 'nav_settings' );
	}

	// Add section for customizing site navbar settings
	$wp_customize->add_section(
		ONLINE_THEME_CUSTOMIZER_PREFIX . 'site_navbar',
		array(
			'title' => 'Site Navbar'
		)
	);
}

add_action( 'customize_register', 'online_define_customizer_sections', 11 );


/**
 * Defines settings and controls used in the WordPress Customizer.
 *
 * @author Jo Dickson
 * @since 1.0.0
 */
function online_define_customizer_fields( $wp_customize ) {
	// Site Navbar
	$wp_customize->add_setting(
		'site_navbar_cta_text',
		array(
			'default' => online_get_theme_mod_default( 'site_navbar_cta_text' )
		)
	);

	$wp_customize->add_control(
		'site_navbar_cta_text',
		array(
			'type'        => 'text',
			'label'       => 'Site Navbar CTA Text',
			'description' => 'Text to display in the site navbar\'s call-to-action button (at the end of the site navigation menu).',
			'section'     => ONLINE_THEME_CUSTOMIZER_PREFIX . 'site_navbar'
		)
	);
}

add_action( 'customize_register', 'online_define_customizer_fields' );
