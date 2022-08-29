<?php
/**
 * Inner header contents for headers that:
 * - have a background image/video
 * - use the "Default" type of content (not "Custom content")
 */

$obj        = ucfwp_get_queried_object();
$title      = ucfwp_get_header_title( $obj );
$title_elem = ( is_home() || is_front_page() ) ? 'h2' : 'h1';

if ( $title ):
	$form = online_get_header_form( $obj );
?>
	<div class="header-content-inner align-self-start pt-4 pt-md-5">
		<div class="container">
			<div class="row">
				<div class="<?php echo ( $form ) ? 'col-8 offset-4 col-md-6 offset-md-6 col-xl-4 offset-xl-4' : 'col-12'; ?> mb-5 mb-sm-4 mb-md-5 mb-xl-0 mt-xl-4">
					<<?php echo $title_elem; ?> class="header-title mb-0 d-inline-block"><?php echo $title; ?></<?php echo $title_elem; ?>>
				</div>

				<?php if ( $form ): ?>
				<div class="col-sm-8 offset-sm-4 col-md-6 offset-md-6 col-xl-4 offset-xl-0 mt-lg-0">
					<?php
					$form_id    = $form['id'];
					$form_title = wptexturize( $form['title'] );
					$form_desc  = wptexturize( $form['description'] );
					$show_title = true;
					$show_desc  = true;

					// Modify how the form title and description are displayed
					// for specific types of content
					if ( $obj instanceof WP_Post ) {
						// Single degrees
						if ( $obj->post_type === 'degree' ) {
							$form_title = 'Get More Information';
							$form_desc  = "Fill out the form below, and we&rsquo;ll send you more information about the <strong>{$obj->post_title}</strong> program.";
						}
						// Vertical Children
						else if ( get_field( 'post_vertical', $obj->ID ) ) {
							$form_title = 'Request Info <span class="fa fa-envelope ml-2" aria-hidden="true"></span>';
						}
						// Standard Vertical
						else if ( get_post_meta( $obj->ID, '_wp_page_template', true ) === 'template-vertical.php' ) {
							$show_title = false;
						}
					}
					?>
					<div id="landing-dialog" class="header-form bg-inverse-t-4 mb-4 mb-lg-5 p-3 p-md-4">
						<?php if ( $show_title ): ?>
						<h2 class="h5 text-center">
							<?php echo $form_title; ?>
						</h2>
						<?php endif; ?>

						<?php if ( $show_desc ): ?>
						<div class="">
							<?php echo $form_desc; ?>
						</div>
						<?php endif; ?>

						<?php echo do_shortcode( '[gravityform id="' . $form_id . '" title="false" description="false" ajax="true"]' ); ?>
					</div>
				</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
<?php endif; ?>
