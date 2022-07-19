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

defined( 'ABSPATH' ) || exit;

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

// Load plugin
require_once('aparat.php');