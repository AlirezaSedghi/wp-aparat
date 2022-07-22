<?php

// Add WP Aparat admin menu
function wp_aparat_options_page() {
    add_menu_page( __( "Aparat for WordPress", "wp-aparat" ), __( "Aparat", "wp-aparat" ), 'manage_options', 'wp-aparat', 'wp_aparat_options_page_html', plugins_url('assets/images/icon.svg', __FILE__) );
}
add_action('admin_menu', 'wp_aparat_options_page');

// Show and render admin options menu
function wp_aparat_options_page_html() {
    if (!current_user_can('manage_options'))
        return;

    if (isset($_GET['settings-updated']))
        add_settings_error('wpaparat_messages', 'wp_aparat_message', __('Settings saved successfully.', 'wp-aparat'), 'updated');

    settings_errors('wpaparat_messages');

    ?>
    <div class="wrap">
        <h1><?php esc_html_e(get_admin_page_title()); ?></h1>
        <form action="options.php" method="post">
            <?php
            settings_fields('wp-aparat');
            do_settings_sections('wp-aparat');
            submit_button(__('Save Settings', 'wp-aparat'));
            ?>
        </form>
    </div>
    <?php
}

// Settings validations
function wp_aparat_validation_function( $input ) {
    $output = $input;

    if ($output['wpaparat_field_newtab'] == "newtab" || empty($output['wpaparat_field_newtab'])) {
        $output['wpaparat_field_newtab'] = true;
    } else {
        $output['wpaparat_field_newtab'] = false;
    }

    if ( empty($output['wpaparat_field_figure_size']) ) {
        $output['wpaparat_field_figure_size'] = 'one-third';
    }

    return apply_filters( 'wp_aparat_validation_function', $output, $input );
}

// WP Aparat Settings page
function wp_aparat_settings_init()
{
    register_setting('wp-aparat', 'wpaparat_options', 'wp_aparat_validation_function');
    add_settings_section(
        'wp_aparat_option_section',
        __('Widget option', 'wp-aparat'),
        '',
        'wp-aparat'
    );
    add_settings_field(
        'wpaparat_field_newtab',
        __('New Tab', 'wp-aparat'),
        'wp_aparat_field_open_new_tab_cb',
        'wp-aparat',
        'wp_aparat_option_section',
        [
            'label_for'		=> 'wpaparat_field_newtab',
            'class'			=> 'wp-aparat-row'
        ]
    );
    add_settings_field(
        'wpaparat_field_figure_size',
        __('Picture size in widget', 'wp-aparat'),
        'wp_aparat_field_figure_size_cb',
        'wp-aparat',
        'wp_aparat_option_section',
        [
            'label_for'		=> 'wpaparat_field_figure_size',
            'class'			=> 'wp-aparat-row'
        ]
    );
    add_settings_field(
        'wpaparat_field_default_video_size',
        __('Default video size', 'wp-aparat'),
        'wp_aparat_field_default_video_size_cb',
        'wp-aparat',
        'wp_aparat_option_section',
        [
            'label_for'		=> 'wpaparat_field_default_video_size',
            'class'			=> 'wp-aparat-row'
        ]
    );
}
 
// Register WP Aparat settings to WordPress admin
add_action('admin_init', 'wp_aparat_settings_init');

/**
 * Callback functions
 */
function wp_aparat_field_open_new_tab_cb($args) {
    $open_in_new_tab = get_wp_aparat_option_open_new_tab();
?>
    <select id="<?php echo esc_attr($args['label_for']); ?>" name="wpaparat_options[<?php echo esc_attr($args['label_for']); ?>]" style="min-width: 200px">
        <option value="newtab" <?php selected($open_in_new_tab, true); ?>>
            <?php esc_html_e('Open links in a new tab', 'wp-aparat'); ?>
        </option>
        <option value="self" <?php selected($open_in_new_tab, false); ?>>
            <?php esc_html_e('Open links in a same window', 'wp-aparat'); ?>
        </option>
    </select>
<?php
}
 
function wp_aparat_field_figure_size_cb($args) {
    $figure_size = get_wp_aparat_option_figure_size();
?>
    <select id="<?php echo esc_attr($args['label_for']); ?>" name="wpaparat_options[<?php echo esc_attr($args['label_for']); ?>]" style="min-width: 200px">
        <option value="one-third" <?php selected($figure_size, 'one-third'); ?>>
            <?php esc_html_e('One Third', 'wp-aparat'); ?>
        </option>
        <option value="half" <?php selected($figure_size, 'half'); ?>>
            <?php esc_html_e('Half', 'wp-aparat'); ?>
        </option>
        <option value="full" <?php selected($figure_size, 'full'); ?>>
            <?php esc_html_e('Full', 'wp-aparat'); ?>
        </option>
    </select>
    <p class="description">
        <?php esc_html_e('Image size of each video in the widget. Default: one third', 'wp-aparat'); ?>
    </p>
<?php
}

function wp_aparat_field_default_video_size_cb($args) {
    $default_video_size = get_wp_aparat_option_default_video_size();
?>
    <select id="<?php echo esc_attr($args['label_for']); ?>" name="wpaparat_options[<?php echo esc_attr($args['label_for']); ?>]" style="min-width: 200px">
        <option value="full" <?php selected($default_video_size, 'full'); ?>>
            <?php esc_html_e('Full', 'wp-aparat'); ?>
        </option>
        <option value="half" <?php selected($default_video_size, 'half'); ?>>
            <?php esc_html_e('Half', 'wp-aparat'); ?>
        </option>
        <option value="600" <?php selected($default_video_size, '600'); ?>>
            <?php esc_html_e('600 pixel', 'wp-aparat'); ?>
        </option>
        <option value="400" <?php selected($default_video_size, '400'); ?>>
            <?php esc_html_e('400 pixel', 'wp-aparat'); ?>
        </option>
    </select>
    <p class="description">
        <?php esc_html_e('Default size for videos in posts and pages. Default: full', 'wp-aparat'); ?>
    </p>
<?php
}