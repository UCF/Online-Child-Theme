<?php
/**
 * Template Name: Form Redirect Confirmation Page
 **/
?>
<?php get_header(); the_post(); ?>

<?php
$degree            = null;
$degree_name       = 'UCF degree';
$label             = filter_input( INPUT_GET, 'label', FILTER_SANITIZE_STRING );
$catalog_thumb_url = get_theme_mod( 'degree_catalog_thumbnail' ) ?: online_get_theme_mod_default( 'degree_catalog_thumbnail' ); // Always return a value, even if the customizer option is empty
$catalog_doc_url   = '';

if ( $label ) {
	$degree = get_page_by_title( $label, OBJECT, 'degree' );
}

if ( $degree ) {
	$degree_name = get_the_title( $degree );
	$catalog_doc = get_field( 'catalog_document', $degree->ID );

	if ( isset( $catalog_doc ) ) {
		$catalog_doc_url = $catalog_doc['url'];
	}
}
?>

<article id="form-confirmation">
	<div class="container mt-4 mt-sm-5 mb-5 pb-sm-4">

		<?php if ( $catalog_doc_url ): ?>
		<div class="jumbotron bg-faded">
			<div class="row">
				<div class="col-8 offset-2 col-sm-6 offset-sm-3 col-md-4 offset-md-1 mb-5 mb-md-0 text-center">
					<a target="_blank" href="<?php echo $catalog_doc_url; ?>" class="d-block gtm-viewbook-link">
						<img class="rotate-left box-shadow-soft img-fluid publication-image" src="<?php echo $catalog_thumb_url; ?>">
					</a>
				</div>
				<div class="col-10 offset-1 col-sm-10 col-md-6 align-self-md-center">
					<div class="publication-content">
						<h3 class="h4 text-uppercase">Learn More</h3>
						<p class="mb-4">Download your information guide for the <strong><?php echo $degree_name; ?></strong> program. It contains additional degree information including course lists, degree requirements, and more.</p>
						<p class="text-center">
							<a class="btn btn-primary" target="_blank" href="<?php echo $catalog_doc_url; ?>"><span class="fa fa-file-pdf-o" aria-hidden="true"></span> Download Now</a>
						</p>
					</div>
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
	</div>
</article>

<?php get_footer(); ?>
