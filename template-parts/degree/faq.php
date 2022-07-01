<?php
$post = isset( $post ) ? $post : get_queried_object();

if ( $post->post_type === 'degree' ) :

	$degree_faq_topic = get_field( 'degree_faq_topic', $post );
	$degree_faq_tags = get_field( 'degree_faq_tags', $post );

	if( $degree_faq_topic && $degree_faq_tags ) :
?>
	<section id="faq" aria-label="FAQ">
		<div class="container">
			<div class="row my-5 py-3">
				<div class="col-md-12" class="faq-container">
					<?php do_shortcode( '[ucf-faq-list topic="' . $degree_faq_topic . '" title="Frequently Asked Questions" topic_class="invisible" tags="' . $degree_faq_tags . '"]' ); ?>
				</div>
			</div>
		</div>
	</section>
<?php
	endif;
endif;
