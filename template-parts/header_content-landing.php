<?php
/**
 * Inner header contents for Landing Page Concept 1
 */

$obj  = ucfwp_get_queried_object();
$meta = new OCTHeaderContent( $obj, array(
	'use_modal'  => true,
	'form_class' => 'header-form header-form-landing text-left'
) );
?>
<div class="container text-center">
	<div class="row mb-3 mb-lg-4">
		<div class="col-2 offset-5 col-sm-2 offset-sm-5 col-md-12 offset-md-0 px-2 px-sm-3 align-items-start justify-content-center">
			<img class="img-fluid" src="<?php echo ONLINE_THEME_IMG_URL; ?>/ucf-tab.svg" alt="University of Central Florida">
		</div>
	</div>

	<?php echo $meta->content_before; ?>

	<?php if ( $meta->form ): ?>
		<?php echo $meta->cta_markup; ?>
		<div class="<?php echo $meta->form_class; ?>">
			<?php echo $meta->form_before; ?>
				<?php echo $meta->form_content_before; ?>
				<?php echo $meta->form_markup; ?>
				<?php echo $meta->form_content_after; ?>
			<?php echo $meta->form_after; ?>
		</div>
	<?php endif; ?>

	<?php echo $meta->content_after; ?>
</div>
