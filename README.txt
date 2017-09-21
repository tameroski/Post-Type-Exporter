=== Plugin Name ===
Contributors: tameroski
Donate link: http://www.keybored.fr
Tags: cpt, export
Requires at least: 4.0
Tested up to: 4.9
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Stable tag: 1.0

Plugin for exporting a list of custom post type entries to a CSV file.

== Description ==

Plugin for exporting a list of custom post type entries to a CSV file.

By default, the plugin is only exporting posts. You need to use a filter to add your own configuration. Let's say you want to add the export feature to pages and a custom post type with slug `my-cpt` :

```
function my_post_types( $post_types ){
    $post_types = array(
        "my-cpt" => array(
            "fields"        => array(
                'post_date'     => __('Date', 'wordpress'),
                'post_title'    => __('Title', 'wordpress')
            ),
            "fields_acf"    => array(
                'field_1'       => __('Field 1', 'my-text-domain'), 
                'field_2'       => __('Field 2', 'my-text-domain')
            )
        ),
        "page" => array(
            "fields"        => array(
                'post_date'     => __('Date', 'wordpress'),
                'post_title'    => __('Title', 'wordpress')
            ),
            "fields_acf"    => array(
                'field_A'       => __('Field A', 'my-text-domain'), 
                'field_B'       => __('Field B', 'my-text-domain')
            )
        )
    );
    
    return $post_types;
}
add_filter( 'pte_post_types', 'my_post_types' );
```

For each post type, array keys represents the fields to export, and array values represents the corresponding column title in the exported file.

By default, the plugin is exporting xls file. But it is also possible to change this to csv : 

```
function my_export_type( $type ){
    return 'csv';
}
add_filter( 'pte_export_type', 'my_export_type' );
```

== Installation ==



== Screenshots ==



== Changelog ==

= 1.0 =
* Initial version