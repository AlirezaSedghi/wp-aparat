<?php

// Aparat Class
class wpAparat {

    private $channelID = '';

    /**
     * Construction
     */
    function __construct( $channelID ) {
        $this->setChannelID($channelID);
    }

    /**
     * @return string
     */
    public function getChannelID() {
        return $this->channelID;
    }

    /**
     * @param string $channelID
     */
    public function setChannelID(string $channelID) {
        $this->channelID = $channelID;
    }

    /**
     * Get channel data from API
     * with cache for 3 minutes
     *
     * @return mixed
     */
    public function getChannelData() {
        $transient_key = "wp-aparat-channel-" . $this->getChannelID();
        if ( false === ( $data = get_transient( $transient_key ) ) ) {
            $response = wp_remote_get( esc_url_raw( "https://www.aparat.com/api/fa/v1/user/user/profilehome/username/" . $this->getChannelID() ), [ 'timeout' => 5, 'user-agent' => 'WordPress/WP-Aparat', 'headers' => array('Content-Type' => 'application/json') ] );
            if ( is_array( $response ) && ! is_wp_error( $response ) ) {
                $response_body = wp_remote_retrieve_body( $response );
                $response = json_decode( $response_body, true );
                if ( !empty($response) ) {
                    $data = $response;
                    if ( $data )
                        set_transient($transient_key, $data, 3 * MINUTE_IN_SECONDS);    // Cache for 3 minutes
                }
            }
        }
        return $data;
    }

    /**
     * Split channel rows data
     *
     * @param array $data
     * @return array|string
     */
    private function getChannelRows($data = []) {
        return $data["data"] ?? [];
    }

    /**
     * Split channel included videos data
     *
     * @param array $data
     * @return array|mixed
     */
    private function getChannelIncludes($data = []) {
        return $data["included"] ?? [];
    }

    /**
     * Get last video items
     *
     * @param array $data
     * @return array|mixed|string
     */
    public function getLastVideos($data = []) {
        $channelRows = $this->getChannelRows($data);
        $lastVideosIndex = array_search('last_videos', array_column($channelRows, 'id'));
        $lastVideosData = [];
        if ( $lastVideosIndex !== false ) {
            $lastVideos = $channelRows[$lastVideosIndex] ?? [];
            $lastVideosData = $lastVideos["relationships"]["video"]["data"] ?? [];
        }
        return $lastVideosData;
    }

    public function getVideoData($data = [], $id = false) {
        $videosData = [];
        if ( $id ) {
            $channelIncludes = $this->getChannelIncludes($data);
            $videosIndex = array_search($id, array_column($channelIncludes, 'id'));
            if ($videosIndex !== false) {
                $videosData = $channelIncludes[$videosIndex]["attributes"] ?? [];
            }
        }
        return $videosData;
    }

}