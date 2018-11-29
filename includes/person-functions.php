<?php

/**
 * Displays related degrees for a person. For use on single-person.php
 *
 * Adapted from Online-Theme
 *
 * @author Jo Dickson
 * @since 1.0.0
 * @param $post object | Person post object
 * @return Mixed | HTML string or void
 **/
function online_get_person_degrees_markup( $post ) {
	if ( $post->post_type !== 'person' ) { return; }

	$degrees = get_field( 'person_degrees', $post->ID );
	$people_groups = wp_get_post_terms( $post->ID, 'people_group', array(
		'fields' => 'names'
	) );

	ob_start();

	if ( $degrees ):
		foreach ( $degrees as $degree ):
			// Degree name to print in each list item.  Prefix with "Online",
			// only if the word "Online" isn't already present in the degree
			// name.
			$degree_name_str = $degree->post_title;
			if ( strpos( $degree->post_title, 'Online' ) === false ) {
				$degree_name_str = 'Online ' . $degree_name_str;
			}

			// Phrase to print for each degree.  Assumes any non-student person
			// group is faculty/teaching-related.
			$degree_str = '';
			if ( $people_groups && in_array( 'Student', $people_groups ) ) {
				$degree_str = $degree_name_str . ' Student';
			}
			else {
				$degree_str = 'Teaching in the ' . $degree_name_str . ' Program';
			}
	?>
		<p class="text-center">
			<a href="<?php echo get_permalink( $degree ); ?>">
				<?php echo $degree_str; ?>
			</a>
		</p>
	<?php
		endforeach;
	endif;

	return ob_get_clean();
}


/**
 * Displays contact buttons for a person. For use on single-person.php
 *
 * Adapted from Online-Theme
 *
 * @author Jo Dickson
 * @since 1.0.0
 * @param $post object | Person post object
 * @return Mixed | Grid and contact btn HTML or void
 **/
function online_get_person_contact_btns_markup( $post ) {
	if ( $post->post_type !== 'person' ) { return; }

	$email      = get_field( 'person_email', $post->ID );
	$has_phones = have_rows( 'person_phone_numbers', $post->ID );
	$phones     = get_field( 'person_phone_numbers', $post->ID );

	ob_start();
	if ( $email || $phones ):
?>
	<div class="row my-5">
		<?php if ( $email ): ?>
		<div class="col-md-6 offset-md-0 my-1 email-button-container">
			<a href="mailto:<?php echo $email; ?>" class="btn btn-primary btn-block contact-button">Email</a>
		</div>
		<?php endif; ?>
		<?php if ( $has_phones ): ?>
		<div class="col-md-6 offset-md-0 my-1">
			<a href="tel:<?php echo preg_replace( "/\D/", '', $phones[0]['number'] ); ?>" class="btn btn-primary btn-block contact-button">Phone</a>
		</div>
		<?php endif; ?>
	</div>
<?php
	endif;
	return ob_get_clean();
}


/**
 * Display's a person's office location in a condensed table-like format.
 * For use on single-person.php
 *
 * Adapted from Online-Theme
 *
 * @author Jo Dickson
 * @since 1.0.0
 * @param $post object | Person post object
 * @return Mixed | Grid and contact info HTML or void
 **/
function online_get_person_office_markup( $post ) {
	if ( $post->post_type !== 'person' ) { return; }

	ob_start();
	if ( $room = get_field( 'person_room', $post->ID ) ):
?>
	<div class="row">
		<div class="col-sm-4 col-md-5 person-label">
			Office
		</div>
		<div class="col-sm-8 col-md-7 person-attr">
			<?php if ( $room_url = get_field( 'person_room_url', $post->ID ) ): ?>
			<a href="<?php echo $room_url; ?>">
				<?php echo $room; ?>
			</a>
			<?php else: ?>
			<span>
				<?php echo $room; ?>
			</span>
			<?php endif; ?>
		</div>
	</div>
	<hr class="my-2">
<?php
	endif;
	return ob_get_clean();
}


/**
 * Display's a person's email in a condensed table-like format.
 * For use on single-person.php
 *
 * Adapted from Online-Theme
 *
 * @author Jo Dickson
 * @since 1.0.0
 * @param $post object | Person post object
 * @return Mixed | Grid and contact info HTML or void
 **/
function online_get_person_email_markup( $post ) {
	if ( $post->post_type !== 'person' ) { return; }

	ob_start();
	if ( $email = get_field( 'person_email', $post->ID ) ):
?>
	<div class="row">
		<div class="col-sm-4 col-md-5 person-label">
			E-mail
		</div>
		<div class="col-sm-8 col-md-7 person-attr">
			<a href="mailto:<?php echo $email; ?>" class="person-email">
				<?php echo $email; ?>
			</a>
		</div>
	</div>
	<hr class="my-2">
<?php
	endif;
	return ob_get_clean();
}


/**
 * Display's a person's phone numbers in a condensed table-like format.
 * For use on single-person.php
 *
 * Adapted from Online-Theme
 *
 * @author Jo Dickson
 * @since 1.0.0
 * @param $post object | Person post object
 * @return Mixed | Grid and contact info HTML or void
 **/
function online_get_person_phones_markup( $post ) {
	if ( $post->post_type !== 'person' ) { return; }

	ob_start();
	if ( have_rows( 'person_phone_numbers', $post->ID ) ):
?>
	<div class="row">
		<div class="col-sm-4 col-md-5 person-label">
			Phone
		</div>
		<div class="col-sm-8 col-md-7 person-attr">
			<ul class="list-unstyled mb-0">
			<?php
			while ( have_rows( 'person_phone_numbers', $post->ID ) ): the_row();
				$phone = get_sub_field( 'number' );
				if ( $phone ):
			?>
				<li>
					<a href="tel:<?php echo preg_replace( "/\D/", '', $phone ); ?>" class="person-tel">
						<?php echo $phone; ?>
					</a>
				</li>
			<?php
				endif;
			endwhile;
			?>
			</ul>
		</div>
	</div>
	<hr class="my-2">
<?php
	endif;
	return ob_get_clean();
}


/**
 * Modifies searchable values for each person in a 'people' post list search.
 *
 * Adapted from Online-Theme
 *
 * @since 1.0.0
 **/

function online_search_people_localdata( $localdata, $posts, $atts ) {
	$data = json_decode( $localdata, true ) ?: array();

	if ( !empty( $data ) && $atts['layout'] === 'people' ) {
		foreach ( $data as $index => $item ) {
			if ( isset( $item['id'] ) && get_post_type( $item['id'] ) === 'person' ) {
				$person = get_post( $item['id'] );
				$name = ucfwp_get_person_name( $person );
				$job_title = get_field( 'person_jobtitle', $person->ID );
				$job_title = $job_title ? strip_tags( $job_title ) : false; // Fix stupid job title hackery

				// Update person datum matches
				$matches = array( $name );
				if ( $job_title ) {
					$matches[] = $job_title;
				}

				$data[$index]['matches'] = array_merge( $item['matches'], $matches );

				// Update displayKey for each person
				$display = $name;
				if ( $job_title ) {
					$display .= ' ' . $job_title;
				}

				$data[$index]['display'] = $display;

				// Add extra template data
				ob_start();
	?>
				<div class="media">
					<div class="d-flex mr-2">
						<?php echo ucfwp_get_person_thumbnail( $person ); ?>
					</div>
					<div class="media-body">
						<?php echo $name; ?>
						<?php if ( $job_title ): ?>
						<div class="small">
							<?php echo $job_title; ?>
						</div>
						<?php endif; ?>
					</div>
				</div>
	<?php
				$tmpl_suggestion = ob_get_clean();
				$data[$index]['template']['suggestion'] = $tmpl_suggestion;
			}
		}
	}

	return json_encode( $data );
}

add_filter( 'ucf_post_list_search_localdata', 'online_search_people_localdata', 10, 3 );


/**
 * Modifies templates for the 'people' post list layout typeahead results.
 *
 * Adapted from Online-Theme
 *
 * @since 1.0.0
 **/

function online_search_people_templates( $templates, $posts, $atts ) {
	if ( $atts['layout'] !== 'people' ) { return $templates; }

	ob_start();
?>
	{
		suggestion: Handlebars.compile('<div>{{{template.suggestion}}}</div>')
	}
<?php
	return ob_get_clean();
}

add_filter( 'ucf_post_list_search_templates', 'online_search_people_templates', 10, 3 );
