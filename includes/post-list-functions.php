<?php
/**
 * Plugin extras/overrides for the UCF Post List Plugin
 */


/**
 * Adds custom layouts for the UCF Post List plugin.
 *
 * @author Jo Dickson
 * @since 1.0.0
 */
function online_post_list_layouts( $layouts ) {
	$layouts['thumbnail']      = 'Thumbnail Layout';
	$layouts['person_profile'] = 'Person Profile Layout';
	$layouts['person_quote']   = 'Person Quote Layout';
	return $layouts;
}

add_filter( 'ucf_post_list_get_layouts', 'online_post_list_layouts' );


/**
 * Modifies the existing "default" layout for the [ucf-post-list] shortcode
 *
 * @since 1.0.0
 * @author Jo Dickson
 */

function online_post_list_display_default( $content, $posts, $atts ) {
	if ( $posts && ! is_array( $posts ) ) { $posts = array( $posts ); }
	ob_start();
?>
	<?php if ( $posts ): ?>
		<div class="row ucf-post-list-items">

		<?php
		foreach ( $posts as $index=>$item ) :
			$item_img = UCF_Post_List_Common::get_image_or_fallback( $item );
			$excerpt  = apply_filters( 'the_excerpt', get_post_field( 'post_excerpt', $item ) );
			$excerpt  = trim( $excerpt );

			if ( $atts['posts_per_row'] > 0 && $index !== 0 && ( $index % $atts['posts_per_row'] ) === 0 ) {
				echo '</div><div class="row ucf-post-list-items">';
			}
		?>
			<article class="col-12 col-lg mb-4 mb-lg-5 ucf-post-list-item">
				<a class="d-block text-secondary text-decoration-none" href="<?php echo get_permalink( $item->ID ); ?>">
					<?php if ( $item_img ) : ?>
					<div class="ucf-post-list-thumbnail-block media-background-container mb-4">
						<img src="<?php echo $item_img; ?>" class="ucf-post-list-thumbnail-image media-background object-fit-cover" alt="">
					</div>
					<?php endif; ?>

					<h3 class="h6 mb-3 text-uppercase ucf-post-list-item-title">
						<?php echo $item->post_title; ?>
					</h3>
				</a>

				<?php if ( $excerpt ): ?>
				<div class="hidden-md-down ucf-post-list-item-desc">
					<?php echo $excerpt; ?>
				</div>
				<?php endif; ?>
			</article>
		<?php endforeach; ?>

		</div>

	<?php else: ?>
		<div class="ucf-post-list-error">No results found.</div>
	<?php endif;

	return ob_get_clean();
}

add_filter( 'ucf_post_list_display_default', 'online_post_list_display_default', 10, 3 );


/**
 * Defines a new "thumbnail" layout for the [ucf-post-list] shortcode
 *
 * @since 1.0.0
 * @author Jo Dickson
 */

function online_post_list_display_thumbnail_before( $content, $posts, $atts ) {
	ob_start();
?>
<div class="ucf-post-list ucf-post-list-thumbnail" id="post-list-<?php echo $atts['list_id']; ?>">
<?php
	return ob_get_clean();
}

add_filter( 'ucf_post_list_display_thumbnail_before', 'online_post_list_display_thumbnail_before', 10, 3 );


function online_post_list_display_thumbnail( $content, $posts, $atts ) {
	if ( $posts && ! is_array( $posts ) ) { $posts = array( $posts ); }
	ob_start();
?>
	<?php if ( $posts ): ?>
		<div class="ucf-post-list-thumbnail-deck">

		<?php
		foreach ( $posts as $index=>$item ) :
			$date = date( "M d", strtotime( $item->post_date ) );
			$item_img = UCF_Post_List_Common::get_image_or_fallback( $item );

			if ( $atts['posts_per_row'] > 0 && $index !== 0 && ( $index % $atts['posts_per_row'] ) === 0 ) {
				echo '</div><div class="ucf-post-list-thumbnail-deck">';
			}
		?>
			<div class="ucf-post-list-thumbnail-item">
				<a class="ucf-post-list-thumbnail-link text-decoration-none d-flex flex-column" href="<?php echo get_permalink( $item->ID ); ?>">
					<span class="ucf-post-list-thumbnail-title"><?php echo $item->post_title; ?></span>

					<?php if ( $item_img ) : ?>
					<div class="ucf-post-list-thumbnail-block media-background-container h-100 hidden-sm-down">
						<img src="<?php echo $item_img; ?>" class="ucf-post-list-thumbnail-image media-background object-fit-cover" alt="">
					</div>
					<?php endif; ?>
				</a>
			</div>
		<?php endforeach; ?>

		</div>

	<?php else: ?>
		<div class="ucf-post-list-error">No results found.</div>
	<?php endif;

	return ob_get_clean();
}

add_filter( 'ucf_post_list_display_thumbnail', 'online_post_list_display_thumbnail', 10, 3 );


/**
 * Helper function that returns an <img> tag for this theme's modified
 * post list card layout.
 *
 * Adapted from Online-Theme
 *
 * @param object $item WP Post object
 * @param string $size a registered attachment thumbnail size
 * @return string
 *
 * @author Jo Dickson
 * @since 1.0.0
 */
function online_get_post_list_card_img( $item, $size='16x9-lg' ) {
	$item_img = $item_img_src = '';
	$item_img_id = $header_img = null;
	// Use the post's featured image, or header image as a fallback
	// (if available)
	$item_img_id = get_post_thumbnail_id( $item );
	if ( !$item_img_id ) {
		$header_img = get_field( 'page_header_image', $item->ID );
		if ( $header_img ) {
			$item_img_id = $header_img['id'];
		}
	}
	// Generate an image with srcsets, or use the Post List
	// plugin's provided fallback img url
	if ( $item_img_id ) {
		$item_img = wp_get_attachment_image( $item_img_id, $size, false, array(
			'class' => 'ucf-post-list-thumbnail-image img-fluid',
			'alt' => $item->post_title
		) );
	}
	else {
		$item_img_src = UCF_Post_List_Common::get_image_or_fallback( $item );
		if ( $item_img_src ) {
			$item_img = '<img class="ucf-post-list-thumbnail-image img-fluid" src="'. $item_img_src .'" alt="'. $item->post_title .'">';
		}
	}
	return $item_img;
}


/**
 * Modify UCF Post List card layout for this theme.
 *
 * Adapted from Online-Theme
 *
 * @author Jo Dickson
 * @since 1.0.0
 */
function online_post_list_display_card( $content, $posts, $atts ) {
	if ( $posts && ! is_array( $posts ) ) { $posts = array( $posts ); }
	ob_start();
?>
	<?php
	if ( $posts ):
		// Make sure posts_per_row value is sane/compatible with Bootstrap
		$col_width = 4;
		if (
			$atts['posts_per_row'] > 0
			&& 12 % $atts['posts_per_row'] === 0
			&& $atts['posts_per_row'] <= 12
		) {
			$col_width = 12 / $atts['posts_per_row'];
		}
		$col_class = 'col-sm-' . $col_width;
	?>
		<div class="row">

		<?php
		foreach ( $posts as $index => $item ) :
			// Handle post description output
			$desc = has_excerpt( $item ) ? wp_trim_words( get_the_excerpt( $item ), 25 ) : wp_trim_words( $item->post_content, 25 );
			$desc = wptexturize( do_shortcode( $desc ) );
			// Handle img output
			$item_img = '';
			if ( $atts['show_image'] ) {
				if ( $col_width === 12 ) {
					$item_img = online_get_post_list_card_img( $item, $size='16x9-lg' );
				}
				else if ( $col_width >= 6 && $col_width < 12 ) {
					$item_img = online_get_post_list_card_img( $item, $size='16x9-md' );
				}
				else if ( $col_width >= 4 && $col_width < 6 ) {
					$item_img = online_get_post_list_card_img( $item, $size='16x9-sm' );
				}
				else {
					$item_img = online_get_post_list_card_img( $item, $size='16x9-xs' );
				}
			}
			// Break into new row if necessary
			if ( $index !== 0 && $atts['posts_per_row'] > 0 && ( $index % $atts['posts_per_row'] ) === 0 ) {
				echo '</div><div class="row">';
			}
			// Echo card contents
		?>
			<div class="<?php echo $col_class; ?>" data-mh="ucf-post-list-cards">
				<div class="ucf-post-list-card">
					<a class="ucf-post-list-card-link" href="<?php echo get_permalink( $item->ID ); ?>">
						<?php if ( $atts['show_image'] && $item_img ): ?>
						<div class="ucf-post-list-thumbnail">
							<?php echo $item_img; ?>
						</div>
						<?php endif; ?>

						<div class="ucf-post-list-card-block">
							<h3 class="ucf-post-list-card-title"><?php echo $item->post_title; ?></h3>
							<?php if ( $desc ) : ?>
							<div class="ucf-post-list-card-text">
								<?php echo $desc; ?>
							</div>
							<?php endif; ?>
						</div>
					</a>
				</div>
			</div>
		<?php endforeach; ?>

		</div>

	<?php else: ?>
		<div class="ucf-post-list-error">No results found.</div>
	<?php endif;
	return ob_get_clean();
}

add_filter( 'ucf_post_list_display_card', 'online_post_list_display_card', 10, 3 );
