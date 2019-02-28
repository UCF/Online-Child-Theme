<?php
/**
 * Non-abstract class that defines fields and functions
 * for handling header template part logic
 * This should not be called directly. Use one of the
 * OCTHeaderContentConcept classes.
 */
if ( ! class_exists( 'OCT_HeaderContent' ) ) {
	class OCTHeaderContent {
		protected
			$obj,        // The WordPress object
			$cta_text,   // Text within the call-to-action btn
			$form_title, // The form title
			$form_desc,  // The form description
			$form_type;  // The type of form being used

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
		public function __construct( $obj ) {
			$this->obj = $obj;
			$this->form = online_get_header_form( $this->obj );
			$this->set_values();
		}

		/**
		 * Helper function for the constructor
		 * @author Jim Barnes
		 * @since 1.2.0
		 */
		protected function set_values() {}

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
					'title'       => false,
					'description' => false,
					'ajax'        => true
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
			return '';
		}

		/**
		 * Generates the content_after markup
		 * @author Jim Barnes
		 * @since 1.2.0
		 * @return string The content_after markup
		 */
		protected function generate_content_after() {
			return '';
		}

		/**
		 * Generates the form_before markup
		 * @author Jim Barnes
		 * @since 1.2.0
		 * @return string The form_before markup
		 */
		protected function generate_form_before() {
			return '';
		}

		/**
		 * Generates the form_after markup
		 * @author Jim Barnes
		 * @since 1.2.0
		 * @return string The form_after markup
		 */
		protected function generate_form_after() {
			return '';
		}

		/**
		 * Generates additional content_before based on form_type
		 * @author Jim Barnes
		 * @since 1.2.0
		 * @author Jim Barnes
		 * @return string The form_content_before markup
		 */
		protected function generate_form_content_before() {
			return '';
		}

		/**
		 * Generates additional content_after based on form_type
		 * @author Jim Barnes
		 * @since 1.2.0
		 * @author Jim Barnes
		 * @return string The form_content_after markup
		 */
		protected function generate_form_content_after() {
			return '';
		}

		/**
		 * Generates the CTA button markup.
		 * @author Jim Barnes
		 * @since 1.2.0
		 * @return string The CTA button markup
		 */
		protected function generate_cta_markup() {
			return '';
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
