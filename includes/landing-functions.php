<?php
/**
 * Functions pertaining to landing page markup.
 */

/**
 * Determines which classes to apply to the highlight div based on what landing page template is used.
 *
 * @since 1.2.0
 * @param object $post The landing page WP_Post object
 * @return string Column classes for the highlight div
 * @see online_get_landing_highlights()
 **/
function online_highlight_classes( $post ) {
	$landing_template = get_page_template_slug( $post );

	if ( ! empty( $landing_template ) ) {
		$highlight_col_classes = 'col-12 col-md-4 mb-3 mb-md-0';
	} else {
		$highlight_col_classes = 'col-12 mb-3 mb-md-4';
	}

	return $highlight_col_classes;
}


/**
 * Returns markup for a landing page's highlights (icon + text)
 * or an empty string if none are set for the given landing page.
 *
 * @since 1.2.0
 * @param object $post The landing page WP_Post object
 * @return string HTML markup
 */
function online_get_landing_highlights( $post ) {
	$col_classes = online_highlight_classes( $post );

	ob_start();

	if ( have_rows( 'landing_highlights', $post->ID ) ) :
	?>
	<div class="row">
	<?php
		while ( have_rows( 'landing_highlights', $post->ID ) ) : the_row();
			$icon_type = get_sub_field( 'landing_highlight_icon_type' );
			$highlight_content = get_sub_field( 'landing_highlight_content' );
			$highlight_content_text = $highlight_content['text'];
			$highlight_content_supplementary_text = $highlight_content['sub_text'];
			?>
			<div class="<?php echo $col_classes; ?> d-flex">
				<div class="mr-3">
				<?php
				if ( $icon_type === 'image' ) : ?>
					<p>get_sub_field('landing_highlight_icon_image');</p>
				<?php elseif ( $icon_type === 'fonticon' ) :
					$font_icon_options = get_sub_field( 'landing_highlight_icon_font_options' );
				?>
					<span class="<?php echo $font_icon_options['landing_highlight_icon_font_classes']; ?> fa-3x" aria-hidden="true" style="color: <?php echo $font_icon_options['landing_highlight_icon_font_color']; ?>"></span>
				<?php endif; ?>
				</div>
				<div class="text">
					<span class="d-block text-uppercase font-weight-bold"><?php echo $highlight_content_text; ?></span>
					<span class=""><?php echo $highlight_content_supplementary_text; ?></span>
				</div>
			</div>
	<?php endwhile; ?>
	</div>

	<?php endif;

	return ob_get_clean();
}
