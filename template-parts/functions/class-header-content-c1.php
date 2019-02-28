<?php
/**
 * Class for handling header templates parts
 */
if ( ! class_exists( 'OCT_HeaderContent' ) ) {
	class OCTHeaderContentConcept1 extends OCTHeaderContent {
		/**
		 * The object constructor
		 * @author Jim Barnes
		 * @since 1.2.0
		 * @param WP_Post $obj The post object
		 */
		public function __construct( $obj ) {
			$this->form_class = 'header-form header-form-landing text-left';
			parent::__construct( $obj );
		}

		/**
		 * Helper function for the constructor
		 * Called by the constructor
		 * @author Jim Barnes
		 * @since 1.2.0
		 */
		protected function set_values() {
			// Generate the form markup
			$this->form_markup = $this->generate_form_markup();

			// Generate wrapper elements
			$this->form_type           = get_field( 'landing_header_form_type', $obj );
			$this->content_before      = get_field( 'landing_header_content_custom', $obj );
			$this->content_after       = get_field( 'landing_header_content_after', $obj );
			$this->form_before         = $this->generate_form_before();
			$this->form_after          = $this->generate_form_after();
			$this->form_content_before = $this->generate_form_content_before();
			$this->form_content_after  = $this->generate_form_content_after();
			$this->cta_markup          = $this->generate_cta_markup();
		}

		/**
		 * Returns the form markup
		 * @author Jim Barnes
		 * @since 1.2.0
		 * @param array $form The form array
		 */
		protected function generate_form_markup() {
			// Use default markup
			return parent::generate_form_markup();
		}

		/**
		 * Generates the form_before markup
		 * @author Jim Barnes
		 * @since 1.2.0
		 * @return string The form_before markup
		 */
		protected function generate_form_before() {
			$retval = '';

			switch( $this->form_type ) {
				case 'togglemodal':
					// Set additional form class
					$this->form_class .= ' header-form-modal bg-secondary';
					// Set call-to-action text
					$this->cta_text = trim( do_shortcode( get_field( 'landing_form_cta_text', $this->obj ) ) );

					ob_start();
?>
					<div class="modal fade mt-md-3 mt-lg-5" id="landing-dialog" data-dialog-action="modal" tabindex="-1" role="dialog" aria-label="Get more information" aria-hidden="true">
						<div class="modal-dialog" role="document">
							<div class="modal-content">
								<div class="modal-header d-flex flex-row justify-content-end">
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class="modal-body pt-0 px-sm-4">
<?php
					$retval = trim( ob_get_clean() );

					break;
				default:
					break;
			}

			return $retval;
		}

		/**
		 * Generates the form_after markup
		 * @author Jim Barnes
		 * @since 1.2.0
		 * @return string The form_after markup
		 */
		protected function generate_form_after() {
			$retval = '';

			switch( $this->form_type ) {
				case 'togglemodal':
					ob_start();
?>
								</div>
							</div>
						</div>
					</div>
<?php
					$retval = trim( ob_get_clean() );
					break;
				default:
					break;
			}

			return $retval;
		}

		/**
		 * Generates additional content_before based on form_type
		 * @author Jim Barnes
		 * @since 1.2.0
		 * @author Jim Barnes
		 * @return string The form_content_before markup
		 */
		protected function generate_form_content_before() {
			$retval = '';

			switch( $this->form_style ) {
				case 'togglemodal':
					if ( get_field( 'landing_header_modal_content_type', $this->obj ) === 'formdata' ) {
						if ( $this->form_title ) {
							$retval .= "<h2 class=\"h3\">$this->form_title</h2>";
						}
						if ( $this->form_desc ) {
							$retval .= "<div class=\"mb-3\">$this->form_desc</div>";
						}
					} else {
						$retval = trim( get_field( 'landing_header_modal_content_custom', $this->obj ) );
					}
					break;
				default:
					break;
			}

			return $retval;
		}

		/**
		 * Generates additional content_after based on form_type
		 * @author Jim Barnes
		 * @since 1.2.0
		 * @author Jim Barnes
		 * @return string The form_content_after markup
		 */
		protected function generate_form_content_after() {
			$retval = '';

			switch( $this->form_style ) {
				case 'togglemodal':
					$content_after = trim( get_field( 'landing_header_modal_content_after', $this->obj ) );

					if ( $content_after ) {
						$retval = "<div class=\"mt-4\">$content_after</div>";
					}
					break;
				default:
					break;
			}

			return $retval;
		}

		/**
		 * Generates the CTA button markup.
		 * @author Jim Barnes
		 * @since 1.2.0
		 * @return string The CTA button markup
		 */
		protected function generate_cta_markup() {
			ob_start();

			if ( $this->cta_text ) :
?>
			<button type="button" class="landing-cta btn btn-lg btn-primary mb-3 mt-sm-2 mt-md-3 mb-lg-4">
				<?php echo $this->cta_text; ?>
			</button>
<?php
			endif;

			return trim( ob_get_clean() );
		}
	}
}
