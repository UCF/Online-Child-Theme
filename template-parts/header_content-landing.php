<?php
/**
 * Inner header contents for Landing Page Concept 1
 */

$obj            = ucfwp_get_queried_object();
$content_before = ''; // TODO
$form_markup    = online_get_header_form_markup( $obj ) ?: '';
$content_after  = ''; // TODO
?>
<div class="container text-center">
	<div class="row mb-3 mb-lg-4">
		<div class="col-2 offset-5 col-sm-2 offset-sm-5 col-md-12 offset-md-0 px-2 px-sm-3 align-items-start justify-content-center">
			<img class="img-fluid" src="<?php echo ONLINE_THEME_IMG_URL; ?>/ucf-tab.svg" alt="University of Central Florida">
		</div>
	</div>

	<?php echo $content_before; ?>
	<?php echo $form_markup; ?>
	<?php echo $content_after; ?>
</div>
