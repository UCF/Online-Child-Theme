<?php
/**
 * Inner header contents for Landing Page Concept 2
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
<div class="header-content-inner align-self-start pt-4 pt-lg-5" id="landing-dialog">
	<div class="container">
		<div class="row">
			<div class="col-md-7 offset-md-5 col-lg-6 offset-lg-6 col-xl-5 offset-xl-7">
				<div class="header-form header-form-landing bg-secondary mb-4 mb-lg-5 p-3 p-md-4">
					<?php echo $content_before; ?>
					<?php echo $form_markup; ?>
					<?php echo $content_after; ?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php endif; ?>
