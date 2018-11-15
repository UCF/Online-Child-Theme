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
define( 'ONLINE_DEGREE_PROGRAM_ORDER', serialize( array(
	'online-major',
	'online-master',
	'online-doctorate',
	'online-certificate'
) ) ); // TODO make a customizer option?


/**
 * Initialization functions to be fired early when WordPress loads the theme.
 *
 * @author Jo Dickson
 * @since 1.0.0
 */
function online_init() {
	// Register custom image sizes
	add_image_size( '16x9-xs', 320, 129, true );
	add_image_size( '16x9-sm', 767, 431, true );
	add_image_size( '16x9-md', 970, 546, true );
	add_image_size( '16x9-lg', 1200, 675, true );

	// Register custom footer menu for this theme
	register_nav_menu( 'footer-menu', __( 'Footer Menu' ) );

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
		ONLINE_THEME_CUSTOMIZER_PREFIX . 'navbar',
		array(
			'title' => 'Navbar'
		)
	);

	// Add section for customizing the site subfooter
	$wp_customize->add_section(
		ONLINE_THEME_CUSTOMIZER_PREFIX . 'subfooter',
		array(
			'title' => 'Subfooter'
		)
	);

	// Add section for form-specific settings
	$wp_customize->add_section(
		ONLINE_THEME_CUSTOMIZER_PREFIX . 'forms',
		array(
			'title' => 'Forms'
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
		'navbar_cta_text',
		array(
			'default' => online_get_theme_mod_default( 'navbar_cta_text' )
		)
	);

	$wp_customize->add_control(
		'navbar_cta_text',
		array(
			'type'        => 'text',
			'label'       => 'Navbar Call-to-Action (CTA) Text',
			'description' => 'Text to display in the site navbar\'s call-to-action button (at the end of the site navigation menu).',
			'section'     => ONLINE_THEME_CUSTOMIZER_PREFIX . 'navbar'
		)
	);


	// Site Subfooter
	$wp_customize->add_setting(
		'subfooter_section',
		array(
			'default' => online_get_theme_mod_default( 'subfooter_section' )
		)
	);

	$wp_customize->add_control(
		'subfooter_section',
		array(
			'type'        => 'select',
			'label'       => 'Subfooter Section',
			'description' => 'Choose an existing Section to display immediately above the site footer on pages. This section is <strong>not</strong> displayed on single degrees, landing pages, or other types of content.',
			'section'     => ONLINE_THEME_CUSTOMIZER_PREFIX . 'subfooter',
			'choices'     => online_get_post_choices( 'ucf_section' )
		)
	);


	// Forms
	$wp_customize->add_setting(
		'default_header_form'
	);

	$wp_customize->add_control(
		'default_header_form',
		array(
			'type'        => 'select',
			'label'       => 'Default Header Form',
			'description' => 'The form shown by default in page and degree headers.',
			'section'     => ONLINE_THEME_CUSTOMIZER_PREFIX . 'forms',
			'choices'     => online_get_gf_choices()
		)
	);
}

add_action( 'customize_register', 'online_define_customizer_fields' );


/**
 * Adds a custom ACF WYSIWYG toolbar called 'Inline Text' that only includes
 * simple inline text formatting tools and link insertion/deletion.
 *
 * @since 1.0.0
 * @author Jo Dickson
 * @param array $toolbars Array of toolbar information from ACF
 * @return array
 */
function online_acf_text_toolbar( $toolbars ) {
	$toolbars['Inline Text'] = array();
	$toolbars['Inline Text'][1] = array( 'bold', 'italic', 'link', 'unlink', 'undo', 'redo' );
	return $toolbars;
}
add_filter( 'acf/fields/wysiwyg/toolbars', 'online_acf_text_toolbar' );


/**
 * Modify settings for supported plugins to prevent duplicate registration and
 * enqueuing of assets.
 */

function online_post_list_js_deps( $deps ) {
	return array( 'jquery', 'typeaheadjs', 'handlebars' );
}

add_filter( 'ucf_post_list_js_deps', 'online_post_list_js_deps', 10, 1 );


if ( filter_var( get_option( 'ucf_post_list_include_js_libs' ), FILTER_VALIDATE_BOOLEAN ) !== false ) {
	update_option( 'ucf_post_list_include_js_libs', false );
}
if ( filter_var( get_option( 'ucf_post_list_include_js' ), FILTER_VALIDATE_BOOLEAN ) !== true ) {
	update_option( 'ucf_post_list_include_js', true );
}
if ( filter_var( get_option( 'ucf_degree_search_include_typeahead' ), FILTER_VALIDATE_BOOLEAN ) !== false ) {
	update_option( 'ucf_degree_search_include_typeahead', false );
}
