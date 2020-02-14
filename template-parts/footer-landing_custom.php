<?php
/**
 * Footer template used when necessary for Custom Landing Pages
 */

$obj     = ucfwp_get_queried_object();
$content = get_field( 'landing_custom_footer_custom_content', $obj ) ?: '';

if ( $content ) :
?>
<footer class="site-footer">
	<?php echo $content; ?>
</footer>
<?php endif; ?>
