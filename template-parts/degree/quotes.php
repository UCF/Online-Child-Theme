<?php
$post = isset( $post ) ? $post : get_queried_object();

if ( $post->post_type === 'degree' ) :
	if ( have_rows( 'degree_quotes', $post ) ) :
?>
	<section id="quotes" aria-label="Quotes/Testimonials">
		<div class="jumbotron jumbotron-fluid bg-faded mb-0">
			<div class="container">
				<?php while ( have_rows( 'degree_quotes', $post ) ) : the_row(); ?>
					<div class="row">
						<?php if( get_sub_field( 'degree_quote_heading' ) ) : ?>
							<div class="col-12">
								<h2 class="font-condensed h6 letter-spacing-2 mb-3 text-uppercase">
									<?php the_sub_field( 'degree_quote_heading' ); ?>
								</h2>
							</div>
						<?php endif; ?>
					</div>
					<div class="row">
						<?php if( get_sub_field( 'degree_quote_image' ) ) : ?>
							<div class="col-lg-3">
								<img src="<?php the_sub_field( 'degree_quote_image' ); ?>" class="img-fluid"
									alt="<?php the_sub_field( 'degree_quote_image_alt' ); ?>">
							</div>
						<?php endif; ?>
						<?php if( get_sub_field( 'degree_quote_video' ) ) : ?>
							<div class="col-lg-6">
								<div class="embed-responsive embed-responsive-16by9">
									<?php echo wp_oembed_get( get_sub_field( 'degree_quote_video' ) ); ?>
								</div>
							</div>
						<?php endif; ?>
						<?php
							if( get_sub_field( 'degree_quote_image' ) ) {
								$quote_col_class = "col-lg-9 col-xl-8 offset-xl-1 mt-4 mt-lg-0";
							} else if( get_sub_field( 'degree_quote_video' ) ) {
								$quote_col_class = "col-lg-6 mt-4 mt-lg-0";
							} else {
								$quote_col_class = "col-12 col-xl-10 offset-xl-1";
							}
						?>
						<div class="<?php echo $quote_col_class; ?>">
							<blockquote class="blockquote blockquote-quotation">
								<p class="mb-0"><?php the_sub_field( 'degree_quote' ); ?></p>
								<footer class="blockquote-footer"><?php the_sub_field( 'degree_quote_footer' ); ?></footer>
							</blockquote>
						</div>
					</div>
				<?php endwhile; ?>
			</div>
		</div>
	</section>
<?php
	endif;
endif;
