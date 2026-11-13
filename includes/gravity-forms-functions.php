<?php
/**
 * Gravity Forms extras/overrides
 **/

/**
 * Disables the gf_page_{form_id} query argument that Gravity Forms
 * (3.2.1+) appends to URLs for multi-page forms, which pollutes
 * analytics. Forms with the Validation Summary enabled keep the
 * argument, since the summary's error links depend on it to open
 * the page where the invalid field appears.
 *
 * @since 1.7.1
 * @param bool $disabled Whether the page query argument is disabled.
 * @param array $form The current form object.
 * @return bool
 **/
if ( ! function_exists( 'online_gform_disable_page_query_arg' ) ) {
	function online_gform_disable_page_query_arg( $disabled, $form ) {
		if ( is_array( $form ) && ! empty( $form['validationSummary'] ) ) {
			return $disabled;
		}

		return true;
	}
	add_filter( 'gform_disable_page_query_arg', 'online_gform_disable_page_query_arg', 10, 2 );
}
