<?php
/**
 * Handle all degree-search related funtions here
 */
if ( ! function_exists( 'ucf_degree_picker_inline_layout' ) ) {
	/**
	 * Provides the 'inline' layout for the `ucf-degree-picker` shortcode.
	 * @param array $args The argument array
	 */
	function ucf_degree_picker_inline_layout( $args ) {
		ob_start();
	?>
		<form class="form" action="" method="GET" data-degree-picker>
			<div class="row">
				<label class="sr-only" for="sel_program_type">Select Program Type</label>
				<select class="custom-select form-control col-12 col-md mb-2 text-uppercase" id="sel_program_type" name="sel_program_type">
					<option value="">Degree Level</option>
				</select>
				<label class="sr-only" for="sel_interest">Select an Area of Interest</label>
				<select class="custom-select form-control col-12 col-md mb-2 text-uppercase" id="sel_interest" name="sel_interest" disabled>
					<option value="">Interest</option>
				</select>
				<label class="sr-only" for="sel_program">Select Program</label>
				<select class="custom-select form-control col-12 col-md mb-2 text-uppercase" id="sel_program" name="sel_program" disabled>
					<option value="">Program</option>
				</select>
				<button type="submit" class="col-12 col-md-2 col-lg-1 btn btn-primary h-100" name="submit" disabled><span class="hidden-md-up">View Program</span> <span class="fa fa-arrow-right" aria-hidden="true"></span></button>
			</div>
		</form>
	<?php
		return ob_get_clean();
	}

	add_filter( 'ucf_degree_picker_inline_display', 'ucf_degree_picker_inline_layout', 10, 1 );
}
