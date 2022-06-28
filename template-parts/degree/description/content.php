<?php
$post = isset( $post ) ? $post : get_queried_object();

if ( $post->post_type === 'degree' ) :
	$description = trim( get_field( 'degree_description', $post ) );

	if ( $description ) :
?>
	<div class="col py-lg-3 my-4 my-sm-5">
		<div class="degree-catalog-description">
			<?php echo $description; ?>
		</div>

	</div>
<?php
	endif;
endif;
