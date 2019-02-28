<?php
/**
 * Inner header contents for Landing Page Concept 3
 */

$obj  = ucfwp_get_queried_object();
$meta = new OCTHeaderContentConcept3( $obj );

if ( $meta->form ):
?>
<div class="header-content-inner align-self-start" id="landing-dialog">
	<div class="container">
		<div class="row">
			<div class="col-2 offset-5 col-sm-2 offset-sm-5 col-md-5 offset-md-0 col-lg-6 col-xl-7 px-2 px-sm-3 d-flex align-items-start justify-content-center justify-content-md-start">
				<img class="img-fluid" src="<?php echo ONLINE_THEME_IMG_URL; ?>/ucf-tab.svg" alt="University of Central Florida">
			</div>
			<div class="col-md-7 col-lg-6 col-xl-5 pt-3 pt-md-4 pt-lg-5">
				<div class="header-form header-form-landing bg-inverse-t-2 mb-4 mb-lg-5 p-3 p-md-4">
					<?php echo $meta->content_before; ?>
					<?php echo $meta->form_markup; ?>
					<?php echo $meta->content_after; ?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php endif; ?>
