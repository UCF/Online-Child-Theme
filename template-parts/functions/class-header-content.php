<?php
/**
 * Class that defines fields and functions
 * for handling header template part logic
 */
if ( ! class_exists( 'OCTHeaderContent' ) ) {
	class OCTHeaderContent {
		protected
			$obj,        // The WordPress object
			$cta_text,   // Text within the call-to-action btn
			$form_title, // The form title
			$form_desc,  // The form description
			$form_type,  // The type of form being used
			$use_modal; // If the form is wrapped in a modal

		public
			$form, // The form object
			$content_before, // Outer content to display before all other form markup
			$content_after, // Outer content to display after all other form markup
			$form_class          = '', // Class surrounding the call-to-action and form contents
			$cta_markup          = '', // Full <button> markup for the cta btn
			$form_before         = '', // Wrapper markup before inner contents + the rendered form
			$form_after          = '', // Wrapper markup after inner contents + the rendered form
			$form_content_before = '', // Content to display immediately before the form
			$form_content_after  = '', // Content to display immediately after the form
			$form_markup         = ''; // The rendered form markup

		/**
		 * The object constructor
		 * @author Jim Barnes
		 * @since 1.2.0
		 * @param WP_Post $obj The post object
		 */
		public function __construct( $obj, $args=array() ) {
			$this->obj = $obj;

			$args = shortcode_atts( array(
				'use_modal'         => false,
				'form_class'        => ''
			), $args );

			$this->form       = online_get_header_form( $this->obj );
			$this->use_modal  = $args['use_modal'];
			$this->form_class = $args['form_class'];
			$this->set_values();
		}

		/**
		 * Helper function for the constructor
		 * @author Jim Barnes
		 * @since 1.2.0
		 */
		protected function set_values() {
			$this->form_markup         = $this->generate_form_markup();

			$this->content_before      = $this->generate_content_before();
			$this->content_after       = $this->generate_content_after();
			$this->form_type           = get_field( 'landing_header_form_type', $obj );
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
			$retval = '';

			if ( $this->form ) {
				// Get form markup
				$id    = $this->form['id'];
				$this->form_title = wptexturize( $this->form['title'] );
				$this->form_desc  = wptexturize( $this->form['description'] );

				$args = array(
					'id'          => $id,
					'title'       => "false",
					'description' => "false",
					'ajax'        => "true"
				);

				$arg_string = $this->generate_arg_string( $args );

				$retval = do_shortcode( "[gravityform $arg_string]" );
			}

			return $retval;
		}

		/**
		 * Generates the content_before markup
		 * @author Jim Barnes
		 * @since 1.2.0
		 * @return string The content_before markup
		 */
		protected function generate_content_before() {
			$retval = '';

			if ( get_field( 'landing_header_content_type', $obj ) === 'custom' || $this->use_modal ) {
				$retval = trim( get_field( 'landing_header_content_custom', $obj ) );
			} else {
				if ( $this->form_title ) {
					$retval .= "<h1 class=\"h3\">" . $this->form_title . "</h1>";
				}
				if ( $this->form_desc ) {
					$retval .= "<div class=\"mb-3\">$this->form_desc</div>";
				}
			}

			return $retval;
		}

		/**
		 * Generates the content_after markup
		 * @author Jim Barnes
		 * @since 1.2.0
		 * @return string The content_after markup
		 */
		protected function generate_content_after() {
			$retval = '';

			if ( $this->use_modal ) {
				return get_field( 'landing_header_content_after', $obj );
			}

			$content_after  = trim( get_field( 'landing_header_content_after', $obj ) );

			if ( $content_after ) {
				$retval = "<div class=\"mt-4\">$content_after</div>";
			}

			return $retval;
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

			switch( $this->form_type ) {
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

			switch( $this->form_type ) {
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

		/**
		 * Generates shortcode args based on input array
		 * @author Jim Barnes
		 * @since 1.2.0
		 * @param array $args The argument array
		 * @return string The argument string
		 */
		protected function generate_arg_string( $args ) {
			// Implode array_map output with spaces
			$arg_string = implode(' ',
				array_map(
					function($k, $v) {
						return "$k=\"$v\"";
					},
					array_keys( $args ),
					array_values( $args )
				) // end array_map
			); // end implode

			return $arg_string;
		}
	}
}
