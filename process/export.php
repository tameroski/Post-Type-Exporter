<?php

/**
 * The export process
 *
 * @link       http://www.keybored.fr
 * @since      1.0.0
 *
 * @package    Post_Type_Exporter
 * @subpackage Post_Type_Exporter/process
 */

require('../../../../wp-load.php');

$user = wp_get_current_user();

// Security First
if (!$user || !user_can($user, 'manage_options')) {
    return;
}

$post_type = isset($_REQUEST['post_type']) ? $_REQUEST['post_type'] : '';
$start = isset($_REQUEST['start']) ? $_REQUEST['start'] : '';
$end = isset($_REQUEST['end']) ? $_REQUEST['end'] : '';

// No empty parameters
if (empty($post_type) || empty($start) || empty($end)) {
    return;
}

$date_start = DateTime::createFromFormat('d/m/Y', $start);
$date_end = DateTime::createFromFormat('d/m/Y', $end);

// Query
$query_args = array(
    'post_type'            => $post_type,
    'posts_per_page'    => -1,
    'date_query' => array(
        array(
            'after'     => array(
                'year'  => $date_start->format('Y'),
                'month' => $date_start->format('m'),
                'day'   => $date_start->format('d')
            ),
            'before'    => array(
                'year'  => $date_end->format('Y'),
                'month' => $date_end->format('m'),
                'day'   => $date_end->format('d')
            ),
            'inclusive' => true,
        ),
    ),
);

$query = new WP_Query($query_args);

$cpts = Post_Type_Exporter_Admin::get_post_types();
$cpt = $cpts[$post_type];

$export_type = apply_filters('pte_export_type', Post_Type_Exporter_Admin::DEFAULT_EXPORT_TYPE);

$fields = $cpt['fields'];
$fields_acf = $cpt['fields_acf'];

$csv_fields = array_merge($fields, $fields_acf);
$csv_values = array(array_values($csv_fields));

if ($query->have_posts()) {
    while ($query->have_posts()) {
        $query->the_post();

        $current_line = array();
        foreach ($fields as $field_slug => $field_title) {
            $current_line[$field_slug] = $post->{$field_slug};
        }
        if (function_exists('get_field')) {
            foreach ($fields_acf as $field_slug => $field_title) {
                $field_value = get_field($field_slug, $post);
                error_log(print_r($field_value, true));
                $current_line[$field_slug] = $field_value;
            }
        } else {
            foreach ($fields_acf as $field_slug => $field_title) {
                $current_line[$field_slug] = '';
            }
        }
        $csv_values[] = $current_line;
    }
    wp_reset_postdata();
}

// Filename
$default_filename = Post_Type_Exporter_FILENAME . "-". $post_type.'-'.date('dMY_Hi');
$export_filename = apply_filters('pte_export_filename', $default_filename, $post_type);

if ($export_type == 'csv') {
    // Building file props
    $filename = $export_filename.'.csv';
    header('Content-Encoding: UTF-8');
    header("Content-type: text/csv; charset=utf-8");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header('Content-Description: File Transfer');
    header("Content-Disposition: attachment; filename={$filename}");
    header("Expires: 0");
    header("Pragma: public");
    echo "\xEF\xBB\xBF"; // UTF-8 BOM

    // Output file
    $fh = @fopen('php://output', 'w');
    foreach ($csv_values as $data) {
        fputcsv($fh, $data, Post_Type_Exporter_SEPARATOR);
    }
    fclose($fh);
    exit;
} elseif ($export_type == 'xls') {
    // Building file props
    $filename = $export_filename.'.xls';
    header('Content-Encoding: UTF-8');
    header("Content-Type: Application/vnd.ms-excel; charset=utf-8");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header('Content-Description: File Transfer');
    header("Content-Disposition: Attachment; Filename=\"$filename\"");
    header("Expires: 0");
    header("Pragma: public");

    // Cleaning stuff for Excell
    function cleanData(&$str)
    {
        // is it a string ?
        if (!is_string($str))
            $str = '';

        $str = preg_replace("/\t/", "\\t", $str);
        $str = preg_replace("/\r?\n/", "\\n", $str);
        if (strstr($str, '"')) {
            $str = '"' . str_replace('"', '""', $str) . '"';
        }
    }

    // Output data
    foreach ($csv_values as $data) {
        array_walk($data, 'cleanData');

        $data_string = implode("\t", array_map('utf8_decode', array_values($data)));

        echo $data_string . "\r\n";
    }
    exit;
}
