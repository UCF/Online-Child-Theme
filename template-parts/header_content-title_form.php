<?php
$obj        = ucfwp_get_queried_object();
$title      = ucfwp_get_header_title( $obj );
$title_elem = ( is_home() || is_front_page() ) ? 'h2' : 'h1';

if ( $title ):
	$form_markup = online_get_header_form_markup( $obj );
?>
	<div class="header-content-inner align-self-start pt-4 pt-md-5">
		<div class="container">
			<div class="row">
				<div class="<?php echo ( $form_markup ) ? 'col-8 offset-4 col-md-6 offset-md-6 col-xl-4 offset-xl-4' : 'col-12'; ?> mb-5 mb-sm-4 mb-md-5 mb-xl-0 mt-xl-4">
					<<?php echo $title_elem; ?> class="header-title mb-0 d-inline-block"><?php echo $title; ?></<?php echo $title_elem; ?>>
				</div>

				<?php if ( $form_markup ): ?>
				<div class="col-sm-8 offset-sm-4 col-md-6 offset-md-6 col-xl-4 offset-xl-0 mt-lg-0">
					<?php echo $form_markup; ?>
				</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
<?php endif; ?>
