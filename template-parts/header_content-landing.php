<?php
/**
 * Inner header contents for Landing Page Concept 1
 */

$obj                 = ucfwp_get_queried_object();
$form                = online_get_header_form( $obj );
$content_before      = get_field( 'landing_header_content_custom', $obj );
$content_after       = get_field( 'landing_header_content_after', $obj );
$form_content_before = '';
$form_content_after  = '';
$form_markup         = '';

if ( $form ) {
	$form_id     = $form['id'];
	$form_title  = $form['title'];
	$form_desc   = $form['description'];
	$form_markup = do_shortcode( '[gravityform id="' . $form_id . '" title="false" description="false" ajax="true"]' );
	$form_type   = get_field( 'landing_header_form_type', $obj );

	// Get inner pre-form content, depending on the form type:
	if ( $form_type === 'togglemodal' ) {
		// Use form title+subtitle
		if ( get_field( 'landing_header_modal_content_type', $obj ) === 'formdata' ) {
			if ( $form_title ) {
				$form_content_before .= '<h2 class="h5 text-center">' . $form_title . '</h2>';
			}
			if ( $form_desc ) {
				$form_content_before .= '<div>' . $form_desc . '</div>';
			}
		}
		// Use custom pre-form content
		else {
			$form_content_before = get_field( 'landing_header_modal_content_custom', $obj );
		}
	}
	else if ( $form_type === 'toggleinline' ) {
		// Use form instruction text
		$form_content_before = get_field( 'landing_header_form_instructions', $obj );
	}

	$form_content_before = trim( $form_content_before );
}
?>
<div class="container text-center">
	<div class="row mb-3 mb-lg-4">
		<div class="col-2 offset-5 col-sm-2 offset-sm-5 col-md-12 offset-md-0 px-2 px-sm-3 align-items-start justify-content-center">
			<img class="img-fluid" src="<?php echo ONLINE_THEME_IMG_URL; ?>/ucf-tab.svg" alt="University of Central Florida">
		</div>
	</div>

	<?php echo $content_before; ?>

	<?php if ( $form ): ?>
	<div class="header-form mb-4 mb-lg-5 p-3 p-md-4">
		<?php // TODO handle conditional form markup (e.g. modal wrapper, show/hide div + toggle) depending on landing_header_form_type value ?>
		<?php echo $form_content_before; ?>
		<?php echo $form_markup; ?>
		<?php echo $form_content_after; ?>
	</div>
	<?php endif; ?>

	<?php echo $content_after; ?>
</div>
