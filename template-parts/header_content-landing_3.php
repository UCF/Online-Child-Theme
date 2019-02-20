<?php
/**
 * Inner header contents for Landing Page Concept 3
 */

$obj            = ucfwp_get_queried_object();
$content_before = ''; // TODO
$form_markup    = online_get_header_form_markup( $obj ) ?: '';
$content_after  = ''; // TODO
?>
<div class="header-content-inner align-self-start">
	<div class="container">
		<div class="row">
			<div class="col-2 offset-5 col-sm-2 offset-sm-5 col-md-6 offset-md-0 col-xl-7 px-2 px-sm-3 d-flex align-items-start justify-content-center justify-content-md-start">
				<img class="img-fluid" src="<?php echo ONLINE_THEME_IMG_URL; ?>/ucf-tab.svg" alt="University of Central Florida">
			</div>
			<div class="col-md-6 col-xl-5 pt-3 pt-md-4 pt-lg-5">
				<?php echo $content_before; ?>
				<?php echo $form_markup; ?>
				<?php echo $content_after; ?>
			</div>
		</div>
	</div>
</div>
