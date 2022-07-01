<?php
$post = isset( $post ) ? $post : get_queried_object();

if ( $post->post_type === 'degree' ) :

	$degree_related_programs_layout = get_field( 'degree_related_programs_layout', $post );
	$degree_related_programs_tag = get_field( 'degree_related_programs_tag', $post );

	if( $degree_related_programs_layout && $degree_related_programs_tag ) :
?>
	<section id="related-programs" aria-label="Related Online Programs" class="py-5">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<h2 class="h3 mb-4">Related Online Programs</h2>
					<?php echo do_shortcode( sprintf( '[ucf-post-list post_type="degree" posts_per_page="3" posts_per_row="3" layout="%s" tag="%s"]', $degree_related_programs_layout, $degree_related_programs_tag ) ); ?>
				</div>
			</div>
		</div>
	</section>
<?php
	endif;
endif;
