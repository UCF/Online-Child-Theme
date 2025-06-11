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
	$layouts['thumbnail'] = 'Thumbnail Layout';
	$layouts['link']      = 'Link Layout';
	$layouts['select']    = 'Select Layout';
	return $layouts;
}

add_filter( 'ucf_post_list_get_layouts', 'online_post_list_layouts' );


/**
 * Adds custom attributes per layout for the UCF Post List plugin.
 *
 * @author Cadie Brown
 * @since 1.1.0
 */
function online_post_list_sc_atts( $atts, $layout ) {
	if ( $layout === 'link' ) {
		$atts['link_layout__link_classes']  = 'mb-3 mb-lg-4';
	} elseif ( $layout === 'select' ) {
		$atts['select_layout__label_text']  = 'Select an Option';
		$atts['select_layout__label_classes']  = '';
		$atts['select_layout__option_text'] = 'Select an Option';
		$atts['select_layout__button_text'] = 'Go';
	}
	return $atts;
}

add_filter( 'ucf_post_list_get_sc_atts', 'online_post_list_sc_atts', 10, 2 );


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
			$item_img = UCF_Post_List_Common::get_image_or_fallback( $item, 'post-list-default-img' );
			$excerpt  = apply_filters( 'the_excerpt', get_post_field( 'post_excerpt', $item ) );
			$excerpt  = trim( $excerpt );

			if ( $atts['posts_per_row'] > 0 && $index !== 0 && ( $index % $atts['posts_per_row'] ) === 0 ) {
				echo '</div><div class="row ucf-post-list-items">';
			}
		?>
			<article class="col-12 col-lg mb-4 mb-lg-5 ucf-post-list-item">
				<a class="d-block text-secondary text-decoration-none hover-parent" href="<?php echo get_permalink( $item->ID ); ?>">
					<?php if ( $item_img ) : ?>
					<div class="ucf-post-list-thumbnail-block media-background-container mb-4">
						<img src="<?php echo $item_img; ?>" class="ucf-post-list-thumbnail-image media-background object-fit-cover" alt="">
					</div>
					<?php endif; ?>

					<span class="d-block h6 mb-3 text-uppercase hover-child-text-underline ucf-post-list-item-title">
						<?php echo $item->post_title; ?>
					</span>
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
			$item_img = UCF_Post_List_Common::get_image_or_fallback( $item, 'post-list-thumbnail-img' );

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
 * Defines a new "link" layout for the [ucf-post-list] shortcode.
 *
 * @since 1.1.0
 * @author Cadie Brown
 */

function online_post_list_display_link_before( $content, $posts, $atts ) {
	ob_start();
?>
<div class="ucf-post-list ucf-post-list-link" id="post-list-<?php echo $atts['list_id']; ?>">
<?php
	return ob_get_clean();
}

add_filter( 'ucf_post_list_display_link_before', 'online_post_list_display_link_before', 10, 3 );


function online_post_list_display_link( $content, $posts, $atts ) {
	if ( $posts && ! is_array( $posts ) ) { $posts = array( $posts ); }
	ob_start();
?>
	<?php if ( $posts ) : ?>
		<div class="row ucf-post-list-items">

		<?php
		foreach ( $posts as $index=>$item ) :
			if ( $atts['posts_per_row'] > 0 && $index !== 0 && ( $index % $atts['posts_per_row'] ) === 0 ) {
				echo '</div><div class="row ucf-post-list-items">';
			}
		?>
			<a class="col-12 col-lg ucf-post-list-item <?php echo $atts['link_layout__link_classes']; ?>" href="<?php echo get_permalink( $item->ID ); ?>">
				<?php echo $item->post_title; ?>
			</a>
		<?php endforeach; ?>

		</div>

	<?php else : ?>
		<div class="ucf-post-list-error">No results found.</div>
	<?php endif;

	return ob_get_clean();
}

add_filter( 'ucf_post_list_display_link', 'online_post_list_display_link', 10, 3 );


/**
 * Defines a new "select" layout for the [ucf-post-list] shortcode.
 *
 * @since 1.1.0
 * @author Cadie Brown
 */

function online_post_list_display_select_before( $content, $posts, $atts ) {
	ob_start();
?>
<div class="ucf-post-list ucf-post-list-select" id="post-list-<?php echo $atts['list_id']; ?>">
<?php
	return ob_get_clean();
}

add_filter( 'ucf_post_list_display_select_before', 'online_post_list_display_select_before', 10, 3 );


function online_post_list_display_select( $content, $posts, $atts ) {
	if ( $posts && ! is_array( $posts ) ) { $posts = array( $posts ); }
	$label_classes = !empty( $atts['select_layout__label_classes'] ) ? 'class="' . $atts['select_layout__label_classes'] . '"' : '';
	ob_start();
?>
	<?php if ( $posts ) : ?>
		<form action="#" id="ucf-post-list-select-form-<?php echo $atts['list_id']; ?>" class="d-flex align-items-end" data-post-list-form>
			<div class="mr-2 w-100">
				<label <?php echo $label_classes; ?> for="ucf-post-list-select-<?php echo $atts['list_id']; ?>"><?php echo $atts['select_layout__label_text']; ?></label>
				<select class="ucf-post-list-select custom-select form-control" id="ucf-post-list-select-<?php echo $atts['list_id']; ?>" data-post-list-select>
					<option value="" selected disabled><?php echo $atts['select_layout__option_text']; ?></option>
					<?php foreach ( $posts as $item ) : ?>
					<option value="<?php echo get_permalink( $item->ID ); ?>" class="ucf-post-list-item">
						<?php echo $item->post_title; ?>
					</option>
					<?php endforeach; ?>
				</select>
			</div>
			<button type="submit" class="btn btn-primary"><?php echo $atts['select_layout__button_text']; ?></button>
		</form>
	<?php else : ?>
		<div class="ucf-post-list-error">No results found.</div>
	<?php endif;

	return ob_get_clean();
}

add_filter( 'ucf_post_list_display_select', 'online_post_list_display_select', 10, 3 );
