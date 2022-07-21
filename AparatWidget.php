<?php

// Aparat Widget Class
class AparatWidget extends WP_Widget {

    /**
     * Construction
     */
    function __construct() {
        parent::__construct( 'wpaparat_widget', __('Aparat for Wordpress', 'wp-aparat'), array( 'description' => __( "Show your Aparat channel's videos on sidebar or footer", 'wp-aparat' ), ) );
    }

    /**
     * Load Widget
     *
     * @param array $args
     * @param array $instance
     */
    public function widget( $args, $instance ) {
        $title = apply_filters( 'widget_title', $instance['title'] ?? '' );
        $channelID = apply_filters( 'widget_title', $instance['channelID'] ?? '' );
        $aparatNumber = apply_filters( 'widget_title', $instance['aparatnumber'] ?? '' );

        if ( !empty($channelID) && $aparatNumber ) {

            global $wp_aparat_plugin_full_path;

            echo $args['before_widget'] ?? '';

            if (!empty($title))
                echo ($args['before_title'] ?? '') . $title . ($args['after_title'] ?? '');

            $wp_aparat = new wpAparat($channelID);
            $data = $wp_aparat->getChannelData();
            $lastVideos = $wp_aparat->getLastVideos($data);

            if ( empty($lastVideos) ) {
                echo '<p>' . __( "Channel ID is incorrect or access to APARAT.com is currently unavailable.", "wp-aparat" ) . '</p>';
            }
            else {
                echo '<ul id="wp-aparat" class="wp-aparat-box">';

                $wp_aparat_options  = get_wp_aparat_options();
                $open_in_new_tab	= get_wp_aparat_option_open_new_tab($wp_aparat_options);
                $image_width		= get_wp_aparat_option_image_width($wp_aparat_options);
                $image_height		= get_wp_aparat_option_image_height($wp_aparat_options);
                $figure_width		= get_wp_aparat_option_figure_width($wp_aparat_options);

                foreach ($lastVideos as $lastVideoIndex => $lastVideo) {
                    if ( ($lastVideo["type"] ?? '') == "Video" ) {
                        $videoData = $wp_aparat->getVideoData($data, $lastVideo["id"] ?? false);
                        if ( !empty($videoData) ) {
                            include($wp_aparat_plugin_full_path . "/templates/wp-aparat-widget-item.php");
                        }
                    }
                }

                echo '</ul>';
            }

            echo $args['after_widget'] ?? '';

        }
    }

    /**
     * Widget Settings
     *
     * @param array $instance
     * @return void
     */
    public function form( $instance ) {
        $title = $instance['title'] ?? __('My Aparat Videos', 'wp-aparat');
        $channelID = $instance['channelID'] ?? '';
        $aparatNumber = $instance['aparatnumber'] ?? 5;

        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'channelID' ); ?>"><?php _e( 'Channel ID: (Username)', 'wp-aparat' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'channelID' ); ?>" name="<?php echo $this->get_field_name( 'channelID' ); ?>" type="text" value="<?php echo esc_attr( $channelID ); ?>" placeholder="<?php _e('Insert your channel ID', 'wp-aparat') ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'aparatnumber' ); ?>"><?php _e( 'Number of videos:', 'wp-aparat' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'aparatnumber' ); ?>" name="<?php echo $this->get_field_name( 'aparatnumber' ); ?>" type="number" min="1" max="10" value="<?php echo esc_attr( $aparatNumber ); ?>" />
        </p>
        <?php
    }

    /**
     * Save Settings
     *
     * @param array $new_instance
     * @param array $old_instance
     * @return array
     */
    public function update( $new_instance, $old_instance ) {
        return [
                'title' => ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '',
                'channelID' => ( ! empty( $new_instance['channelID'] ) ) ? strip_tags( $new_instance['channelID'] ) : '',
                'aparatnumber' => ( ! empty( $new_instance['aparatnumber'] ) ) ? strip_tags( $new_instance['aparatnumber'] ) : ''
            ];
    }
}

function wp_aparat_load_widget() {
    register_widget( 'AparatWidget' );
}
add_action( 'widgets_init', 'wp_aparat_load_widget' );