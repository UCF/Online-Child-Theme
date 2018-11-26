<?php
/**
 * Adds the simple layout for displaying News stories
 **/
if ( ! function_exists( 'ucf_news_display_simple' ) ) {
	function ucf_news_display_simple( $content, $items, $args, $display_type, $fallback_message='' ) {
		if ( $items === false ) {
			$items = array();
		}
		else if ( ! is_array( $items ) ) {
			$items = array( $items );
		}
		ob_start();
	?>
		<div class="ucf-news-items">
	<?php if ( count( $items ) === 0 ) : echo $fallback_message; ?>
	<?php else : ?>
	<?php
        foreach( $items as $item ) :
	?>
			<div class="ucf-news-item flex-column">
                <div class="ucf-news-item-title">
                    <a href="<?php echo $item->link; ?>" class="font-weight-bold">
                        <?php echo $item->title->rendered; ?>
                    </a>
                </div>
                <div class="ucf-news-item-date text-primary font-weight-bold text-uppercase">
                    <?php echo date_format( date_create( $item->date ), 'F j, Y' ); ?>
                </div>
			</div>
	<?php
		endforeach;
	endif; // End if item count
	?>
	</div>
	<?php
		return ob_get_clean();
	}
	add_filter( 'ucf_news_display_simple', 'ucf_news_display_simple', 10, 5 );
}