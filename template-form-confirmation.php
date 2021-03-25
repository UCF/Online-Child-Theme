<?php
/**
 * Template Name: Form Redirect Confirmation Page
 **/
?>
<?php get_header(); the_post(); ?>

<?php
$degree                  = null;
$degree_name             = 'UCF degree';
$label                   = filter_input( INPUT_GET, 'label', FILTER_SANITIZE_STRING );
$catalog_thumb_url       = get_theme_mod( 'degree_catalog_thumbnail' ) ?: online_get_theme_mod_default( 'degree_catalog_thumbnail' ); // Always return a value, even if the customizer option is empty
$confirmation_video      = '';
$brochure_document       = '';
$confirmation_next_steps = '';

if ( $label ) {
	$degree = get_page_by_title( $label, OBJECT, 'degree' );
}

if ( $degree ) {
	$degree_name        	 = get_the_title( $degree );
	$confirmation_video 	 = get_field( 'degree_confirmation_video', $degree->ID );
	$brochure_document  	 = get_field( 'degree_brochure_document_file', $degree->ID );
	$confirmation_next_steps = get_field( 'confirmation_next_steps', $post );
}
?>

<article id="form-confirmation">

<?php if ( $confirmation_video || $brochure_document || $confirmation_next_steps ): ?>

	<div class="jumbotron bg-faded mb-0">
		<div class="container">
			<div class="row">
				<div class="col-md-6 col-lg-5 align-self-lg-center">

					<?php if ( $confirmation_video ): ?>
						<div class="embed-responsive embed-responsive-16by9 mb-4 mb-lg-5">
							<iframe title="UCF Online Thank You" src="<?php echo $confirmation_video; ?>"
								width="500" height="281" frameborder="0" allow="autoplay; fullscreen; picture-in-picture">
							</iframe>
						</div>
					<?php endif; ?>

					<?php if ( $brochure_document ): ?>
						<p class="text-center">
							<a class="btn btn-primary" target="_blank" href="<?php echo $brochure_document; ?>">
								<span class="fa fa-file-pdf-o" aria-hidden="true"></span> Download Now
							</a>
						</p>
						<p class="mb-4">
							Download your information guide for the <strong><?php echo $_GET['label']; ?></strong> program. 
							It contains additional degree information including course lists, degree requirements, and more.
						</p>
					<?php endif; ?>

				</div>

				<?php if ( $confirmation_next_steps ): ?>
					<div class="col-md-6 col-lg-6 offset-lg-1">
						<?php echo $confirmation_next_steps; ?>
					</div>
				<?php endif; ?>

			</div>
		</div>
	</div>

<?php endif; ?>

	<?php the_content(); ?>

	<?php if ( isset( $_GET['category'] ) && isset( $_GET['action'] ) && isset( $_GET['label'] ) ): ?>
	<script>
		if (typeof ga !== 'undefined') {
			ga('send', 'event', '<?php echo $_GET['category']; ?>', '<?php echo $_GET['action']; ?>', '<?php echo $_GET['label']; ?>');
		}
	</script>
	<?php endif; ?>

</article>

<?php get_footer(); ?>
