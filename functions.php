<?php

/**
 * Load and return WP Aparat options
 *
 * @return false|mixed
 */
function get_wp_aparat_options() {
    return get_option('wpaparat_options');
}

/**
 * Get WP Aparat option : Open in new tab
 *
 * @param string $option
 * @return bool|mixed
 */
function get_wp_aparat_option_open_new_tab($option = '') {
    if ( empty($option) )
        $option = get_wp_aparat_options();

    return $wp_aparat_options["wpaparat_field_newtab"] ?? true;
}

/**
 * Get WP Aparat option : Image width
 *
 * @param string $option
 * @return bool|mixed
 */
function get_wp_aparat_option_image_width($option = '') {
    if ( empty($option) )
        $option = get_wp_aparat_options();

    return $wp_aparat_options["wpaparat_field_width"] ?? 139;
}

/**
 * Get WP Aparat option : Image height
 *
 * @param string $option
 * @return bool|mixed
 */
function get_wp_aparat_option_image_height($option = '') {
    if ( empty($option) )
        $option = get_wp_aparat_options();

    return $wp_aparat_options["wpaparat_field_height"] ?? 78;
}

/**
 * Get WP Aparat option : Figure width
 * DEPRECATED
 *
 * @param string $option
 * @return bool|mixed
 */
function get_wp_aparat_option_figure_width($option = '') {
    if ( empty($option) )
        $option = get_wp_aparat_options();

    return $wp_aparat_options["wpaparat_field_figurewidth"] ?? 35;
}

/**
 * Get WP Aparat option : Figure size
 *
 * @param string $option
 * @return bool|mixed
 */
function get_wp_aparat_option_figure_size($option = '') {
    if ( empty($option) )
        $option = get_wp_aparat_options();

    return $wp_aparat_options["wpaparat_field_figure_size"] ?? 'one-third';
}

/**
 * Get WP Aparat option : Custom picture
 *
 * @param string $option
 * @return bool|mixed
 */
function get_wp_aparat_option_custom_picture($option = '') {
    if ( empty($option) )
        $option = get_wp_aparat_options();

    return $wp_aparat_options["wpaparat_field_custom_picture"] ?? '';
}

/**
 * Convert number duration into human-readable text
 *
 * @param int $duration
 * @return string
 */
function wp_aparat_human_duration( $duration = 0 ) {
    if ( $duration < 60 ) {
        return "00:" . str_pad($duration, 2, "0", STR_PAD_LEFT);
    }
    else {
        $minutes = intval( $duration / 60 );
        $duration = $duration % 60;

        if ( $minutes < 60 ) {
            return $minutes . ":" . str_pad($duration, 2, "0", STR_PAD_LEFT);
        }
        else {
            $hours = intval( $minutes / 60 );
            $minutes = $minutes % 60;
            echo $hours . ":" . $minutes . ":" . str_pad($duration, 2, "0", STR_PAD_LEFT);
        }
    }
    return '';
}