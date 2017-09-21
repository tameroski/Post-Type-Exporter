<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://www.keybored.fr
 * @since             1.0.0
 * @package           Post_Type_Exporter
 *
 * @wordpress-plugin
 * Plugin Name:       Post Type Exporter
 * Plugin URI:        http://www.keybored.fr
 * Description:       Export a list of entries from any custom or regular post type.
 * Version:           1.0.0
 * Author:            Tameroski
 * Author URI:        http://www.keybored.fr
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       post-type-exporter
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'PLUGIN_VERSION', '1.0.0' );

define( 'Post_Type_Exporter_SEPARATOR', ';' );
define( 'Post_Type_Exporter_FILENAME', 'export' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-post-type-exporter-activator.php
 */
function activate_Post_Type_Exporter() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-post-type-exporter-activator.php';
	Post_Type_Exporter_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-post-type-exporter-deactivator.php
 */
function deactivate_Post_Type_Exporter() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-post-type-exporter-deactivator.php';
	Post_Type_Exporter_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_Post_Type_Exporter' );
register_deactivation_hook( __FILE__, 'deactivate_Post_Type_Exporter' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-post-type-exporter.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_Post_Type_Exporter() {

	$plugin = new Post_Type_Exporter();
	$plugin->run();

}
run_Post_Type_Exporter();
