<?php


	add_theme_support( 'post-thumbnails' );
// Prepend the new column to the columns array
function ssid_column($cols) {
    $cols['ssid'] = 'ID';
    return $cols;
}
// Echo the ID for the new column
function ssid_value($column_name, $id) {
    if ($column_name == 'ssid')
        echo $id;
}
function ssid_return_value($value, $column_name, $id) {
    if ($column_name == 'ssid')
        $value = $id;
    return $value;
}
// Output CSS for width of new column
function ssid_css() {
?>
<style type="text/css">
    #ssid { width: 50px; } /* Simply Show IDs */
</style>
<?php
}
// Actions/Filters for various tables and the css output
function ssid_add() {
    add_action('admin_head', 'ssid_css');
    add_filter('manage_posts_columns', 'ssid_column');
    add_action('manage_posts_custom_column', 'ssid_value', 10, 2);
    add_filter('manage_pages_columns', 'ssid_column');
    add_action('manage_pages_custom_column', 'ssid_value', 10, 2);
    add_filter('manage_media_columns', 'ssid_column');
    add_action('manage_media_custom_column', 'ssid_value', 10, 2);
    add_filter('manage_link-manager_columns', 'ssid_column');
    add_action('manage_link_custom_column', 'ssid_value', 10, 2);
    add_action('manage_edit-link-categories_columns', 'ssid_column');
    add_filter('manage_link_categories_custom_column', 'ssid_return_value', 10, 3);
    foreach ( get_taxonomies() as $taxonomy ) {
        add_action("manage_edit-${taxonomy}_columns", 'ssid_column');
        add_filter("manage_${taxonomy}_custom_column", 'ssid_return_value', 10, 3);
    }
    add_action('manage_users_columns', 'ssid_column');
    add_filter('manage_users_custom_column', 'ssid_return_value', 10, 3);
    add_action('manage_edit-comments_columns', 'ssid_column');
    add_action('manage_comments_custom_column', 'ssid_value', 10, 2);
}
add_action('admin_init', 'ssid_add');

remove_filter( 'sanitize_title', 'sanitize_title_with_dashes' );
add_filter( 'sanitize_title', 'use_capital_letter_in_slug' );
function use_capital_letter_in_slug($title) {
$title = strip_tags($title);
// Preserve escaped octets.
$title = preg_replace('|%([a-fA-F0-9][a-fA-F0-9])|', '---$1---', $title);
// Remove percent signs that are not part of an octet.
$title = str_replace('%', '', $title);
// Restore octets.
$title = preg_replace('|---([a-fA-F0-9][a-fA-F0-9])---|', '%$1', $title);
$title = remove_accents($title);
if (seems_utf8($title)) {
//if (function_exists('mb_strtolower')) {
// $title = mb_strtolower($title, 'UTF-8');
//}
$title = utf8_uri_encode($title, 200);
}

//$title = strtolower($title);
$title = preg_replace('/&.+?;/', '', $title); // kill entities
$title = str_replace('.', '-', $title);
// Keep upper-case chars too!
$title = preg_replace('/[^%a-zA-Z0-9 _-]/', '', $title);
$title = preg_replace('/s+/', '-', $title);
$title = preg_replace('|-+|', '-', $title);
$title = trim($title, '-');

return $title;
}

// ???WordPress????????????????????????????????? ??????

class PTCFP{

function __construct(){

add_action( 'init', array( $this, 'taxonomies_for_pages' ) );

/**
* ????????????????????????????????????????????????????????????????????????????????? 
*/
if ( ! is_admin() ) {
add_action( 'pre_get_posts', array( $this, 'category_archives' ) );
add_action( 'pre_get_posts', array( $this, 'tags_archives' ) );
} // ! is_admin

} // __construct

/**
* ????????????????????????????????????????????????
*
* @uses register_taxonomy_for_object_type
*/
function taxonomies_for_pages() {
register_taxonomy_for_object_type( 'post_tag', 'page' );
register_taxonomy_for_object_type( 'category', 'page' );
} // taxonomies_for_pages

/**
* ????????????????????????????????????
*/
function tags_archives( $wp_query ) {

if ( $wp_query->get( 'tag' ) )
$wp_query->set( 'post_type', 'any' );

} // tags_archives

/**
* ????????????????????????????????????
*/
function category_archives( $wp_query ) {

if ( $wp_query->get( 'category_name' ) || $wp_query->get( 'cat' ) )
$wp_query->set( 'post_type', 'any' );

} // category_archives

} // PTCFP

$ptcfp = new PTCFP();

// ???WordPress????????????????????????????????? ??????


function only_filter_search($query) {
	if (!$query->is_admin && $query->is_search) {
		$query->set('post_type', 'page');
      $query->set('category__not_in', '168');
	}
	return $query;
}
add_filter('pre_get_posts', 'only_filter_search');
if( function_exists('register_nav_menus') ){   
    register_nav_menus(   
        array(   
            'primary' => __( '???????????????' ),   
        )   
    );   
}  

require get_template_directory() . '/ashuwp_framework/ashuwp_framework_core.php'; //??????ashuwp_framework??????
require get_template_directory() . '/ashuwp_framework/config-example.php'; //?????????????????????config-example.php??????????????????
