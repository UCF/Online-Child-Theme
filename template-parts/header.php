<?php
$obj = get_query_var( 'ucfwp_obj', ucfwp_get_queried_object() );
?>

<?php get_template_part( ucfwp_get_template_part_slug( 'header_content' ), $header_content_type ); ?>
