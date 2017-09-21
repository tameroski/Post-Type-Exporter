<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://www.keybored.fr
 * @since      1.0.0
 *
 * @package    Post_Type_Exporter
 * @subpackage Post_Type_Exporter/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Post_Type_Exporter
 * @subpackage Post_Type_Exporter/admin
 * @author     Tameroski <jerome@keybored.fr>
 */
class Post_Type_Exporter_Admin {

	const DEFAULT_EXPORT_TYPE = 'xls'; // Or 'csv'

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}
	
	/**
	 * Default post types to export, overridable by filter
	 *
	 * @since    1.0.0
	 */
	public static function get_post_types() {

		$post_types = array(
			"post" => array(
				"fields"		=> array(
					'post_date'		=> __( 'Date', 'wordpress' ),
					'post_title'	=> __( 'Title', 'wordpress' )
				),
				"fields_acf"	=> array(),
			)
		);

		return apply_filters( 'pte_post_types', $post_types );
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		// Libraries
		wp_enqueue_style( 'pickadate', plugin_dir_url( __FILE__ ) . 'css/pickadate/classic.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'pickadate-date', plugin_dir_url( __FILE__ ) . 'css/pickadate/classic.date.css', array( 'pickadate' ), $this->version, 'all' );

		// Plugin's own styles		
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/post-type-exporter-admin.css', array( 'pickadate-date' ), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		// Libraries
		wp_enqueue_script( 'pickadate', plugin_dir_url( __FILE__ ) . 'js/pickadate/picker.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( 'pickadate-date', plugin_dir_url( __FILE__ ) . 'js/pickadate/picker.date.js', array( 'pickadate' ), $this->version, false );

		// Plugin's own JS
		wp_register_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/post-type-exporter-admin.js', array( 'pickadate' ), $this->version, false );

		// Localize the script with plugin data
		$data = array(
			"link"					=> plugins_url($this->plugin_name . '/process/export.php'),
			"post_types"			=> array_keys(self::get_post_types()),
			"label_export_button"	=> __('Export', 'post-type-exporter'),
			"label_start"			=> __('Start', 'post-type-exporter'),
			"label_end"				=> __('End', 'post-type-exporter'),
			"error_start"		=> __('Please select a start date', 'post-type-exporter'),
			"error_end"		=> __('Please select an end date', 'post-type-exporter'),
			"error_cpt"		=> __('Invalid post type', 'post-type-exporter'),
		);
		wp_localize_script( $this->plugin_name, 'data', $data );
		wp_enqueue_script( $this->plugin_name );

	}

}
