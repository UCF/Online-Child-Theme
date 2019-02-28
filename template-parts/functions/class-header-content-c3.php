<?php
/**
 * Class for handling header templates parts
 */
if ( ! class_exists( 'OCTHeaderContentConcept3' ) ) {
	class OCTHeaderContentConcept3 extends OCTHeaderContent {
		/**
		 * Helper function for the constructor
		 * @author Jim Barnes
		 * @since 1.2.0
		 */
		protected function set_values() {
			// Generate the form markup
			$this->form_markup = $this->generate_form_markup();

			// Generate wrapper elements
			$this->content_before      = $this->generate_content_before();
			$this->content_after       = $this->generate_content_after();
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
		 * Generates the content_before markup
		 * @author Jim Barnes
		 * @since 1.2.0
		 * @return string The content_before markup
		 */
		protected function generate_content_before() {
			$retval = '';

			if ( get_field( 'landing_header_content_type', $obj ) === 'custom' ) {
				$retval = trim( get_field( 'landing_header_content_custom', $obj ) );
			}
			// Otherwise, generate before-form markup based on
			// the form title and description
			else {
				if ( $this->form_title ) {
					$retval .= '<h1 class="h3">' . $form_title . '</h1>';
				}
				if ( $this->form_desc ) {
					$retval .= '<div class="mb-3">' . $form_desc . '</div>';
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

			$content_after = trim( get_field( 'landing_header_content_after', $obj ) );

			if ( $content_after ) {
				$retval = "<div class=\"mt-4\">$content_after</div>";
			}

			return $retval;
		}
	}
}
