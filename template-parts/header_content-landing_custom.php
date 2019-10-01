<?php
/**
 * Inner header contents for the Custom Landing Page template
 */

$obj     = ucfwp_get_queried_object();
$content = get_field( 'landing_custom_header_content', $obj ) ?: '';

if ( $content ) :
?>
<div class="header-content-inner">
	<?php echo $content; ?>
</div>
<?php endif; ?>
