<?php
$videoTitle = $videoData["title"] ?? '';
$videoLink = "https://www.aparat.com/v/" . ($videoData["title"] ?? '');
?>
<li class="aparat-feed-item<?php echo " item-" . ($lastVideoIndex + 1); ?>">
    <div class="aparat-feed-item-box">
        <figure class="aparat-item-figure <?php echo $figure_size; ?>">
            <a href="<?php echo $videoLink; ?>" title="<?php echo $videoTitle; ?>" <?php if ( $open_in_new_tab ) echo 'target="_blank"'; ?> class="aparat-image-box" style="background-image: url(<?php echo $videoData["small_poster"] ?? ''; ?>);">
                <span class="aparat-video-ratio"></span>
                <span class="aparat-video-duration"><?php echo wp_aparat_human_duration($videoData["duration"] ?? 0); ?></span>
            </a>
        </figure>
        <div class="aparat-item-info">
            <h2 class="aparat-item-name"><a href="<?php echo $videoLink; ?>" title="<?php echo $videoTitle; ?>" <?php if ( $open_in_new_tab ) echo 'target="_blank"'; ?>><?php echo $videoTitle; ?></a></h2>
            <span class="aparat-item-pub-date"><?php echo $videoData["date_exact"] ?? '' ?></span>
        </div>
    </div>
</li>