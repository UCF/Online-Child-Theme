<?php
/**
 * Handle all degree-search related functions here
 */

if ( ! function_exists( 'ucf_degree_picker_inline_layout' ) ) {
	/**
	 * Provides the 'inline' layout for the `ucf-degree-picker` shortcode.
	 * @param array $args The argument array
	 * @return string
	 */
	function ucf_degree_picker_inline_layout( $args ) {
		ob_start();
	?>
		<form class="form" action="" method="GET" data-degree-picker>
			<div class="container">
				<div class="row">
					<label class="sr-only" for="sel_program_type">Select Program Type</label>
					<select class="custom-select col-12 col-md mb-2 mr-md-1 text-uppercase" id="sel_program_type" name="sel_program_type">
						<option value="" selected>Degree Level</option>
					</select>
					<label class="sr-only" for="sel_interest">Select an Area of Interest</label>
					<select class="custom-select col-12 col-md mb-2 mr-md-1 text-uppercase" id="sel_interest" name="sel_interest" disabled>
						<option value="" selected>Interest</option>
					</select>
					<label class="sr-only" for="sel_program">Select Program</label>
					<select class="custom-select col-12 col-md mb-2 mr-md-1 text-uppercase" id="sel_program" name="sel_program" disabled>
						<option value="" selected>Program</option>
					</select>
					<button type="submit" class="col-12 col-md-2 col-lg-1 btn btn-primary h-100" name="submit" disabled aria-label="View Program"><span class="hidden-md-up" aria-hidden="true">View Program</span> <span class="fa fa-arrow-right" aria-hidden="true"></span></button>
				</div>
			</div>
		</form>
	<?php
		return ob_get_clean();
	}

	add_filter( 'ucf_degree_picker_inline_display', 'ucf_degree_picker_inline_layout', 10, 1 );
}


/**
 * Adds a degree's thumbnail image next to its name in degree search
 * autosuggestions.
 *
 * Adapted from Online-Theme
 *
 * @since 1.0.0
 * @return string
 */
function online_degree_search_suggestion() {
	ob_start();
?>
	<p class="d-flex flex-row mb-0 ucf-degree-search-suggestion">
		<img src="{{thumbnail}}" class="rounded-circle mr-2 suggestion-image" alt=""><span class="align-self-center suggestion-text">{{encodeString title.rendered}} <em class="suggestion-match-type text-capitalize">{{matchString}}</em></span>
	</p>
<?php
	return ob_get_clean();
}

add_filter( 'ucf_degree_search_suggestion', 'online_degree_search_suggestion', 10, 0 );


/**
 * Modifies the footer of the degree search autosuggestion box.
 *
 * Adapted from Online-Theme
 *
 * @since 1.0.0
 * @return string
 */
function online_degree_search_footer() {
	$majors = get_page_by_path( 'majors' );
	$masters = get_page_by_path( 'masters' );
	$docorates = get_page_by_path( 'doctorates' );
	$certificates = get_page_by_path( 'certificates' );
	ob_start();
?>
	<div class="dropdown-divider"></div>
	<p class="ucf-degree-search-suggestion ml-3 mt-3 mb-2 font-weight-bold">What kind of degree are you interested in?</p>
	<a href="<?php echo get_permalink( $majors->ID ); ?>" class="ucf-degree-search-suggestion tt-suggestion tt-selectable">Bachelor</a>
	<a href="<?php echo get_permalink( $masters->ID ); ?>" class="ucf-degree-search-suggestion tt-suggestion tt-selectable">Master</a>
	<a href="<?php echo get_permalink( $docorates->ID ); ?>" class="ucf-degree-search-suggestion tt-suggestion tt-selectable">Doctorate</a>
	<a href="<?php echo get_permalink( $certificates->ID ); ?>" class="ucf-degree-search-suggestion tt-suggestion tt-selectable">Certificate</a>
<?php
	return ob_get_clean();
}

add_filter( 'ucf_degree_search_footer', 'online_degree_search_footer', 10, 0 );
