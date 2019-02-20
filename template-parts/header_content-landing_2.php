<?php
/**
 * Inner header contents for Landing Page Concept 2
 */

$obj            = ucfwp_get_queried_object();
$content_before = ''; // TODO
$form_markup    = online_get_header_form_markup( $obj ) ?: '';
$content_after  = ''; // TODO
?>
<div class="header-content-inner align-self-start pt-4 pt-lg-5">
	<div class="container">
		<div class="row">
			<div class="col-md-6 offset-md-6 col-xl-5 offset-xl-7">
				<?php echo $content_before; ?>
				<?php echo $form_markup; ?>
				<?php echo $content_after; ?>
			</div>
		</div>
	</div>
</div>
