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
		<a class="center-block text-center mb-2" href="<?php echo get_permalink( $degree ); ?>">
			<?php echo $degree_str; ?>
		</a>
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
 * Add custom profile people list layout for UCF Post List shortcode
 *
 * Adapted from Online-Theme
 *
 * @since 1.0.0
 */

function online_post_list_display_person_profile_before( $content, $items, $atts ) {
	ob_start();
?>
<div class="ucf-post-list online-post-list-person-profile">
<?php
	return ob_get_clean();
}

add_filter( 'ucf_post_list_display_person_profile_before', 'online_post_list_display_person_profile_before', 10, 3 );


function online_post_list_display_person_profile( $content, $items, $atts ) {
	if ( ! is_array( $items ) && $items !== false ) { $items = array( $items ); }
	ob_start();
?>
	<?php if ( $items ): ?>
	<div class="ucf-post-list-items">
		<?php
		foreach ( $items as $index => $item ):
			// Get the person's thumbnail; use the post list fallback if
			// no person thumbnail/person-specific fallback is returned
			$item_img_src = null;
			$item_img = ucfwp_get_person_thumbnail( $item );
			if ( !$item_img ) {
				$item_img_src = UCF_Post_List_Common::get_image_or_fallback( $item );
				if ( $item_img_src ) {
					ob_start();
				?>
					<div class="person-photo-wrap">
						<img src="<?php echo $item_img_src; ?>" alt="<?php echo $item->post_title; ?>" class="person-photo">
					</div>
				<?php
					$item_img = ob_get_clean();
				}
			}

			$name = ucfwp_get_person_name( $item ) ?: $item->post_title;
			$title = wptexturize( get_field( 'person_jobtitle', $item->ID ) );
			$degrees = get_field( 'person_degrees', $item->ID );
			$desc = has_excerpt( $item ) ? wp_trim_words( get_the_excerpt( $item ), 25 ) : wp_trim_words( $item->post_content, 25 );
			$desc = wptexturize( do_shortcode( $desc ) );
		?>
		<a class="ucf-post-list-item" href="<?php echo get_permalink( $item->ID ); ?>">
			<?php if ( $item_img ): ?>
			<div class="person-profile-photo" data-mh="ucf-post-list-person-profile-<?php echo $item->ID; ?>">
				<?php echo $item_img; ?>
			</div>
			<?php endif; ?>

			<div class="person-profile-card" data-mh="ucf-post-list-person-profile-<?php echo $item->ID; ?>">
				<?php if ( $degrees ): ?>
				<div class="person-profile-degrees">
					<?php foreach ( $degrees as $degree ): ?>
					<span class="label label-ucf"><?php echo wptexturize( $degree->post_title ); ?></span>
					<?php endforeach; ?>
				</div>
				<?php endif; ?>

				<h3 class="person-profile-name"><?php echo $name; ?></h3>

				<?php if ( $title ): ?>
				<div class="person-profile-title">
					<?php echo $title; ?>
				</div>
				<?php endif; ?>

				<?php if ( $desc ) : ?>
				<div class="person-profile-bio">
					<?php echo $desc; ?>
				</div>
				<?php endif; ?>
			</div>
		</a>
		<?php endforeach; ?>
	</div>
	<?php else: ?>
	<div class="ucf-post-list-error">No results found.</div>
	<?php endif; ?>
<?php
	return ob_get_clean();
}

add_filter( 'ucf_post_list_display_person_profile', 'online_post_list_display_person_profile', 10, 3 );


/**
 * Add custom blockquote people list layout for UCF Post List shortcode
 *
 * Adapted from Online-Theme
 *
 * @since 1.0.0
 */

function online_post_list_display_person_quote_before( $content, $items, $atts ) {
	ob_start();
?>
<div class="ucf-post-list online-post-list-person-quote">
<?php
	return ob_get_clean();
}

add_filter( 'ucf_post_list_display_person_quote_before', 'online_post_list_display_person_quote_before', 10, 3 );


function online_post_list_display_person_quote( $content, $items, $atts ) {
	if ( ! is_array( $items ) && $items !== false ) { $items = array( $items ); }
	ob_start();
?>
	<?php if ( $items ): ?>
	<div class="ucf-post-list-items">
		<?php
		foreach ( $items as $index => $item ):
			$item_img   = ucfwp_get_person_thumbnail( $item, 'rounded-circle' );
			$name       = ucfwp_get_person_name( $item ) ?: $item->post_title;
			$title      = wptexturize( get_field( 'person_jobtitle', $item->ID ) );
			$quote      = apply_filters( 'the_content', get_field( 'person_quote', $item->ID ) );
			$quote_cite = wptexturize( get_field( 'person_quote_citation', $item->ID ) );

			// Don't continue if we don't have a blockquote to use
			if ( $quote ):
		?>
		<div class="ucf-post-list-item">
			<?php if ( $item_img ): ?>
			<div class="person-quote-photo">
				<?php echo $item_img; ?>
			</div>
			<?php endif; ?>
			<div class="person-quote-content">
				<blockquote class="person-quote">
					<?php echo $quote; ?>

					<?php if ( $quote_cite ): ?>
					<footer class="person-quote-footer">
						<cite class="person-quote-cite"><?php echo $quote_cite; ?></cite>
					</footer>
					<?php endif; ?>
				</blockquote>
			</div>
		</div>
		<?php
			endif;
		endforeach;
		?>
	</div>
	<?php else: ?>
	<div class="ucf-post-list-error">No results found.</div>
	<?php endif; ?>
<?php
	return ob_get_clean();
}

add_filter( 'ucf_post_list_display_person_quote', 'online_post_list_display_person_quote', 10, 3 );


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
