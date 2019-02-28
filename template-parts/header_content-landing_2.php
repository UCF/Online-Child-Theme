<?php
/**
 * Inner header contents for Landing Page Concept 2
 */

$obj  = ucfwp_get_queried_object();
$meta = new OCTHeaderContentConcept2( $obj );
if ( $meta->form ) :
?>
<div class="header-content-inner align-self-start pt-4 pt-lg-5" id="landing-dialog">
	<div class="container">
		<div class="row">
			<div class="col-md-7 offset-md-5 col-lg-6 offset-lg-6 col-xl-5 offset-xl-7">
				<div class="header-form header-form-landing bg-secondary mb-4 mb-lg-5 p-3 p-md-4">
					<?php echo $meta->content_before; ?>
					<?php echo $meta->form_markup; ?>
					<?php echo $meta->content_after; ?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php endif; ?>
