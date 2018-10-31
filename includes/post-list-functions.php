<?php
/**
 * Plugin extras/overrides for the UCF Post List Plugin
 */


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
					<span class="ucf-post-list-thumbnail-title btn btn-primary btn-sm btn-block py-2"><?php echo $item->post_title; ?></span>

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
