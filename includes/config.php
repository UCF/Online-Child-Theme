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
	'degree_catalog_thumbnail' => ONLINE_THEME_IMG_URL . '/degree-catalog-thumb.jpg'
) ) );
define( 'ONLINE_DEGREE_PROGRAM_ORDER', serialize( array(
	'online-major',
	'online-master',
	'online-doctorate',
	'online-certificate'
) ) );


/**
 * Initialization functions to be fired early when WordPress loads the theme.
 *
 * @author Jo Dickson
 * @since 1.0.0
 */
function online_init() {
	// Remove and re-register header image sizes from UCF WP Theme, with
	// custom dimensions applied:
	remove_image_size( 'header-img' );
	remove_image_size( 'header-img-sm' );
	remove_image_size( 'header-img-md' );
	remove_image_size( 'header-img-lg' );
	remove_image_size( 'header-img-xl' );
	add_image_size( 'header-img', 575, 750, true );
	add_image_size( 'header-img-sm', 767, 750, true );
	add_image_size( 'header-img-md', 991, 800, true );
	add_image_size( 'header-img-lg', 1199, 800, true );
	add_image_size( 'header-img-xl', 1600, 650, true );

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

	// Add section for degree-specific settings
	$wp_customize->add_section(
		ONLINE_THEME_CUSTOMIZER_PREFIX . 'degrees',
		array(
			'title' => 'Degrees'
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


	// Degrees
	$wp_customize->add_setting(
		'degree_catalog_thumbnail',
		array(
			'default' => online_get_theme_mod_default( 'degree_catalog_thumbnail' )
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Image_Control(
		   $wp_customize,
		   'degree_catalog_thumbnail',
		   array(
			   'label'       => 'Degree Catalog Thumbnail',
			   'description' => 'The catalog thumbnail to display on form redirect confirmation pages. Will only be displayed if the confirmation page is related to a degree, and that degree has a Catalog URL meta value set.',
			   'section'     => ONLINE_THEME_CUSTOMIZER_PREFIX . 'degrees'
		   )
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
 * Hide unused admin tools (Links, Comments, etc)
 *
 * @since 1.0.0
 **/
function hide_admin_links() {
	remove_menu_page( 'edit-comments.php' );
}
add_action( 'admin_menu', 'hide_admin_links' );
