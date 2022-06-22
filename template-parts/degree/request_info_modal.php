<?php
$post = isset( $post ) ? $post : get_queried_object();

if ( $post->post_type === 'degree' ) :
	$disable_deadlines = get_field( 'disable_deadlines', $post );
	if ( $disable_deadlines !== true ) :
?>
	<div class="modal fade" id="formModal" tabindex="-1" role="dialog" aria-labelledby="formModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header px-4 pt-4">
					<h5 id="formModalLabel" class="modal-title text-center">Request Information</h5>
					<button type="button" class="close ml-auto" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body mb-2 px-4 px-sm-5 pb-4">
					Fill out the form below and one of our success coaches will contact you about the online <strong><?php echo get_the_title( $post ); ?></strong> program.
					<?php echo do_shortcode( "[gravityform id=1 title=false description=False]" ); ?>
				</div>
			</div>
		</div>
	</div>
<?php
	endif;
endif;