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
							<div class="col-lg-3 text-center text-lg-right align-self-center">
								<img src="<?php the_sub_field( 'degree_quote_image' ); ?>" class="img-fluid"
									alt="<?php the_sub_field( 'degree_quote_image_alt' ); ?>">
							</div>
						<?php endif; ?>
						<?php if( get_sub_field( 'degree_quote_video' ) ) : ?>
							<div class="col-lg-3 text-center text-lg-right align-self-center">
								<div class="embed-responsive embed-responsive-16by9">
								<?php
									echo wp_video_shortcode( [
										'src' => the_sub_field( 'degree_quote_video' )
									] );
								?>
								</div>
							</div>
						<?php endif; ?>
						<?php $quote_col_class = ( get_sub_field( 'degree_quote_image' ) || get_sub_field( 'degree_quote_video' ) ) ? "col-lg-9" : "col-12 col-xl-10 offset-xl-1"; ?>
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
