<?php
/**
 * Functions pertaining to landing page markup.
 */

/**
 * Determines which classes to apply to the highlight div based on
 * what landing page template is used.
 *
 * @since 1.2.0
 * @author Cadie Brown
 * @param object $post The landing page WP_Post object
 * @return string Column classes for the highlight div
 * @see online_get_landing_highlights()
 **/
function online_get_highlight_classes( $post ) {
	$landing_template = get_page_template_slug( $post );

	// Right now, the same set of classes are applied to all landing
	// page templates except for the default template (which returns
	// an empty string for get_page_template_slug()).
	if ( ! empty( $landing_template ) ) {
		$col_classes = 'col-12 col-lg-4 mb-3 mb-md-4 mb-lg-0';
	} else {
		$col_classes = 'col-12 mb-3 mb-md-4';
	}

	return $col_classes;
}


/**
 * Returns markup for a landing page's highlights (icon + text)
 * or an empty string if none are set for the given landing page.
 *
 * @since 1.2.0
 * @author Cadie Brown
 * @param object $post The landing page WP_Post object
 * @return string HTML markup
 */
function online_get_landing_highlights( $post ) {
	$col_classes = online_get_highlight_classes( $post );

	ob_start();

	if ( have_rows( 'landing_highlights', $post->ID ) ) :
	?>
	<div class="row">
	<?php
		while ( have_rows( 'landing_highlights', $post->ID ) ) : the_row();
			$icon_type        = get_sub_field( 'landing_highlight_icon_type' );
			$content_text     = get_sub_field( 'landing_highlight_content' )['text'];
			$content_sup_text = get_sub_field( 'landing_highlight_content' )['sup_text'];
	?>
			<div class="<?php echo $col_classes; ?> d-flex">
				<div class="mr-3">
				<?php
				if ( $icon_type === 'image' ) :
				$image_icon_url = get_sub_field( 'landing_highlight_icon_image' )['url'];
				?>
					<div class="landing-highlight-icon-image-wrapper">
						<img class="landing-highlight-icon-image" src="<?php echo $image_icon_url; ?>" alt="">
					</div>
				<?php
				elseif ( $icon_type === 'fonticon' ) :
					$font_icon_classes = get_sub_field( 'landing_highlight_icon_font_options' )['landing_highlight_icon_font_classes'];
					$font_icon_color   = get_sub_field( 'landing_highlight_icon_font_options' )['landing_highlight_icon_font_color'];
				?>
					<span class="<?php echo $font_icon_classes; ?> fa-3x fa-fw" aria-hidden="true" style="color: <?php echo $font_icon_color; ?>"></span>
				<?php endif; ?>
				</div>
				<div class="landing-highlight-content">
				<?php if ( ! empty( $content_text ) ) : ?>
					<span class="landing-highlight-content-text d-block text-uppercase font-weight-bold"><?php echo $content_text; ?></span>
				<?php
				endif;
				if ( ! empty( $content_sup_text ) ) :
				?>
					<span class="landing-highlight-content-sup-text"><?php echo $content_sup_text; ?></span>
				<?php endif; ?>
				</div>
			</div>
	<?php endwhile; ?>
	</div>
	<?php
	endif;

	return ob_get_clean();
}


/**
 * Customizes the color picker default palette for
 * the highlight color picker ACF field.
 *
 * @since 1.2.0
 **/
function online_highlight_acf_custom_color_palette() {
    ?>
    <script type="text/javascript">
    (function($){

        acf.add_filter('color_picker_args', function( args, $field ){

			if ( $field === undefined ) return args;

			if ( $field.length > 0 ) $field = $field[0]

			if ($field.dataset.name === 'landing_highlight_icon_font_color') {
				args.palettes = ['#fc0', '#bbb', '#767676', '#000']
			}

            return args;
        });

    })(jQuery);
    </script>
    <?php
}

add_action('acf/input/admin_footer', 'online_highlight_acf_custom_color_palette');
