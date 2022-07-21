<?php

/*
Plugin Name: Aparat for WordPress
Plugin URI: https://alirezasedghi.com/plugins/aparat-for-wordPress/
Description: Show a list of your Aparat channel's videos in your WordPress site.
Version: 2.0
Author: Alireza Sedghi
Author URI: https://alirezasedghi.com
Text Domain: wp-aparat
Domain Path: /languages
*/

// Block direct access
if ( !defined( 'ABSPATH' ) ) {
    http_response_code(403);
    die('Forbidden');
}

$wp_aparat_plugin_version = '2.0';

// Translation of plugin description
$dummy_name = __( "Aparat for WordPress", "wp-aparat" );
$dummy_description = __( "Show a list of your Aparat channel's videos in your wordpress site.", "wp-aparat" );
$dummy_author = __( "Alireza Sedghi", "wp-aparat" );

// Load plugin settings
if ( defined( 'ABSPATH' ) && is_admin() ) {
    include("aparat-admin.php");
}

// Plugin directories
$wp_aparat_plugin_path = basename( dirname( __FILE__ ) );
$wp_aparat_plugin_full_path = WP_PLUGIN_DIR . '/' . basename( dirname( __FILE__ ) );
$wp_aparat_plugin_languages_path = $wp_aparat_plugin_path . '/languages';
$wp_aparat_plugin_url = plugin_dir_url( __FILE__ );

// Load languages
load_plugin_textdomain( 'wp-aparat', false, $wp_aparat_plugin_languages_path );

// Load files
require_once('functions.php');
require_once('wpAparat.php');
require_once('AparatWidget.php');

/**
 * Load scripts and styles
 */
function wp_aparat_enqueue() {
    global $wp_aparat_plugin_url, $wp_aparat_plugin_version;
    wp_enqueue_style( 'wp_aparat', $wp_aparat_plugin_url . "assets/css/style.css", false, $wp_aparat_plugin_version );
}
add_action( 'wp_enqueue_scripts', 'wp_aparat_enqueue' );

/**
 * Add CSS stylesheet to admin area
 */
function wp_aparat_admin_css() {
    if ( is_rtl() ) {
        echo '<style>
			.mce-container .mce-container-body {
				direction: rtl !important;
				text-align: right !important;
			} 
		</style>';
    }
    echo '<style>
		.mce-btn[aria-label="add Aparat video"] .mce-ico, .mce-btn[aria-label="افزودن ویدیوی آپارات"] .mce-ico {
			width: 62px !important;
		} 
	</style>';
}
add_action('admin_head', 'wp_aparat_admin_css');

/**
 * Add JS to admin area
 */
function wp_aparat_admin_js_variables() {
    echo '
	<script>
		let aparat_video_add		= "' . __( "add Aparat video", 'wp-aparat' ) . '";
		let aparat_video_id			= "' . __( "ID:", 'wp-aparat' ) . '";
		let aparat_video_id_insert	= "' . __( "Insert Aparat video ID :", 'wp-aparat' ) . '";
		let aparat_video_id_desc	= "' . __( "for example, the ID of https://www.aparat.com/v/GHseX is : GHseX", 'wp-aparat' ) . '";
		let aparat_video_width		= "' . __( "Width:", 'wp-aparat' ) . '";
		let aparat_video_width_desc	= "' . __( "Enter the Picture Width, e.g.: 300", 'wp-aparat' ) . '";
		let aparat_video_width_dft	= "' . __( "Default picture width: 600", 'wp-aparat' ) . '";
	</script>
	';
}
add_action( 'admin_head', 'wp_aparat_admin_js_variables' );

/**
 * Modify body classes
 *
 * @param $classes
 * @return mixed
 */
function wp_aparat_body_class( $classes ) {
    if ( is_rtl() ) {
        $classes[] = 'rtl';
    }
    return $classes;
}
add_filter( 'body_class', 'wp_aparat_body_class' );


/**
 * WordPress Aparat shortcode
 *
 * @param $atts
 * @return string
 */
function wp_aparat_shortcode($atts) {
	extract(
		shortcode_atts( array(
			'id'		=> '',
			'width'		=> '600',
		), $atts )
	);
    $id = $id ?? '';
    $width = $width ?? 600;
	$height = intval( 9 * $width / 16 );
	return "<iframe src='https://www.aparat.com/video/video/embed/videohash/{$id}/vt/frame' width='{$width}' height='{$height}' allowfullscreen='true' class='aparat-frame'></iframe>";
}
add_shortcode( 'aparat', 'wp_aparat_shortcode' );

/**
 * Add WP Aparat buttons to the list of WP editor buttons
 *
 * @param $buttons
 * @return mixed
 */
function wp_aparat_editor_button($buttons) {
	array_push($buttons, "separator", "aparat_shortcode");
	return $buttons;
}
add_filter('mce_buttons', 'wp_aparat_editor_button', 0);

// Add TinyMCE button
function wp_aparat_add_tinymce() {
    global $typenow;
    if( ! in_array( $typenow, array( 'post', 'page' ) ) )
        return ;
    add_filter( 'mce_external_plugins', 'wp_aparat_add_tinymce_plugin' );
}
add_action( 'admin_head', 'wp_aparat_add_tinymce' );

function wp_aparat_add_tinymce_plugin( $plugin_array ) {
    $plugin_array['aparat_shortcode'] = plugins_url('assets/js/editor_plugin.js', __FILE__);
    return $plugin_array;
}