<?php
$videoTitle = $videoData["title"] ?? '';
$videoLink = "https://www.aparat.com/v/" . ($videoData["uid"] ?? '');
?>
<li class="aparat-feed-item<?php echo " aparat-feed-item-" . ($lastVideoIndex + 1); ?>">
    <figure class="aparat-item-figure <?php echo $figure_size; ?>">
        <a href="<?php echo $videoLink; ?>" title="<?php echo $videoTitle; ?>" <?php if ( $open_in_new_tab ) echo 'target="_blank"'; ?> class="aparat-image-box" style="background-image: url(<?php echo $videoData["small_poster"] ?? ''; ?>);">
            <span class="aparat-video-ratio"></span>
            <span class="aparat-video-duration"><?php echo wp_aparat_human_duration($videoData["duration"] ?? 0); ?></span>
        </a>
    </figure>
    <div class="aparat-item-info">
        <h4 class="aparat-item-title"><a href="<?php echo $videoLink; ?>" title="<?php echo $videoTitle; ?>" <?php if ( $open_in_new_tab ) echo 'target="_blank"'; ?>><?php echo $videoTitle; ?></a></h4>
        <div class="aparat-item-details">
            <span class="aparat-item-visits"><?php echo ( $videoData["visit_cnt"] ?? 0 ) . __("visits", "wp-aparat"); ?></span>
            <span class="aparat-item-pub-date"><?php echo $videoData["date_exact"] ?? ''; ?></span>
        </div>
    </div>
</li>