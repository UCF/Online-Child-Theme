<?php
/**
 * Inner header contents for Landing Page Concept 3
 */

$obj            = ucfwp_get_queried_object();
$form           = online_get_header_form( $obj );
$content_before = '';
$content_after  = get_field( 'landing_header_content_after', $obj );
$form_markup    = '';

if ( $form ):
	$form_id     = $form['id'];
	$form_title  = wptexturize( $form['title'] );
	$form_desc   = wptexturize( $form['description'] );
	$form_markup = do_shortcode( '[gravityform id="' . $form_id . '" title="false" description="false" ajax="true"]' );

	// Use custom pre-form content if chosen
	if ( get_field( 'landing_header_content_type', $obj ) === 'custom' ) {
		$content_before = get_field( 'landing_header_content_custom', $obj );
	}
	// Otherwise, generate before-form markup based on
	// the form title and description
	else {
		if ( $form_title ) {
			$content_before .= '<h1 class="h3">' . $form_title . '</h1>';
		}
		if ( $form_desc ) {
			$content_before .= '<div>' . $form_desc . '</div>';
		}
	}
	$content_before = trim( $content_before );
?>
<div class="header-content-inner align-self-start">
	<div class="container">
		<div class="row">
			<div class="col-2 offset-5 col-sm-2 offset-sm-5 col-md-6 offset-md-0 col-xl-7 px-2 px-sm-3 d-flex align-items-start justify-content-center justify-content-md-start">
				<img class="img-fluid" src="<?php echo ONLINE_THEME_IMG_URL; ?>/ucf-tab.svg" alt="University of Central Florida">
			</div>
			<div class="col-md-6 col-xl-5 pt-3 pt-md-4 pt-lg-5">
				<div class="header-form bg-inverse-t-2 mb-4 mb-lg-5 p-3 p-md-4">
					<?php echo $content_before; ?>
					<?php echo $form_markup; ?>
					<?php echo $content_after; ?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php endif; ?>
