<?php
/**
 * Inner header contents for Landing Page Concept 1
 */

$obj                 = ucfwp_get_queried_object();
$form                = online_get_header_form( $obj ); // The actual form data
$content_before      = get_field( 'landing_header_content_custom', $obj ); // Outer content to display before all other form markup
$content_after       = get_field( 'landing_header_content_after', $obj ); // Outer content to display after all other form markup
$form_class          = 'header-form header-form-landing text-left'; // Class surrounding the call-to-action and form contents
$cta_text            = ''; // Text within the call-to-action btn
$cta_markup          = ''; // Full <button> markup for the cta btn
$form_before         = ''; // Wrapper markup before inner contents + the rendered form
$form_after          = ''; // Wrapper markup after inner contents + the rendered form
$form_content_before = ''; // Content to display immediately before the form
$form_content_after  = ''; // Content to display immediately after the form
$form_markup         = ''; // The rendered form markup

if ( $form ) {
	$form_id     = $form['id'];
	$form_title  = wptexturize( $form['title'] );
	$form_desc   = wptexturize( $form['description'] );
	$form_markup = do_shortcode( '[gravityform id="' . $form_id . '" title="false" description="false" ajax="true"]' );
	$form_type   = get_field( 'landing_header_form_type', $obj );

	// Get call-to-action button and surrounding form content,
	// depending on the form type.
	//
	// NOTE: as of v1.2.0, the only available option is 'togglemodal'.
	// In future sprint(s), other types will be added that toggle an
	// inline form with a toggle button ('toggleinline') and just display
	// an inline form ('inline').
	switch ( $form_type ) {
		case 'togglemodal':

			// Set additional form class
			$form_class .= ' header-form-modal bg-secondary';

			// Set call-to-action text
			$cta_text = trim( do_shortcode( get_field( 'landing_form_cta_text', $obj ) ) );

			// Add wrapper markup for displaying the form in a modal
			ob_start();
?>
			<div class="modal fade mt-md-3 mt-lg-5" id="landing-dialog" data-dialog-action="modal" tabindex="-1" role="dialog" aria-label="Get more information" aria-hidden="true">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header d-flex flex-row justify-content-end">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body pt-0 px-sm-4">
<?php
			$form_before = trim( ob_get_clean() );

			ob_start();
?>
						</div>
					</div>
				</div>
			</div>
<?php
			$form_after = trim( ob_get_clean() );

			// Determine the pre-form content within the modal
			if ( get_field( 'landing_header_modal_content_type', $obj ) === 'formdata' ) {
				// Use form title+subtitle
				if ( $form_title ) {
					$form_content_before .= '<h2 class="h3">' . $form_title . '</h2>';
				}
				if ( $form_desc ) {
					$form_content_before .= '<div>' . $form_desc . '</div>';
				}
			}
			else {
				// Use custom pre-form content
				$form_content_before = get_field( 'landing_header_modal_content_custom', $obj );
			}

			// Determine the post-form content within the modal
			$form_content_after = get_field( 'landing_header_modal_content_after', $obj );

			break;
		default:
			break;
	}

	$cta_markup          = ( $cta_text ) ? '<button type="button" class="landing-cta btn btn-primary my-3 my-lg-4">' . $cta_text . '</button>' : '';
	$form_content_before = trim( $form_content_before );
	$form_content_after  = trim( $form_content_after );
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
		<?php echo $cta_markup; ?>
		<div class="<?php echo $form_class; ?>">
			<?php echo $form_before; ?>
				<?php echo $form_content_before; ?>
				<?php echo $form_markup; ?>
				<?php echo $form_content_after; ?>
			<?php echo $form_after; ?>
		</div>
	<?php endif; ?>

	<?php echo $content_after; ?>
</div>
