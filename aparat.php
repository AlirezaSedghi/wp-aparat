<?php
/*
Plugin Name: Aparat for WordPress
Plugin URI: http://alirezas.ir/aparat-for-wordPress/
Description: Show a list of your Aparat channel's videos in your wordpress site.
Version: 1.5
Author: Alireza Sedghi
Author URI: http://alirezas.ir/
Text Domain: wpaparat
Domain Path: /languages
*/


/* Plugin Descriptions Translation */

$name = __( "Aparat for WordPress", "wpaparat" );
$description = __( "Show a list of your Aparat channel's videos in your wordpress site.", "wpaparat" );
$author = __( "Alireza Sedghi", "wpaparat" );

/* Load Plugin Text Domain */

load_plugin_textdomain('wpaparat', false, basename(dirname(__FILE__)).'/languages');

/* Load Plugin Files */

require('aparat-admin.php');


/* Configurations */

$wpaparat_options = get_option('wpaparat_options');

$open_in_new	= $wpaparat_options["wpaparat_field_newtab"];
$apimgwidth		= $wpaparat_options["wpaparat_field_width"];
$apimgheight	= $wpaparat_options["wpaparat_field_height"];
$apfgrwidth		= $wpaparat_options["wpaparat_field_figurewidth"];
$custom_picture	= $wpaparat_options["wpaparat_field_custom_picture"];

if ( !isset($open_in_new) )	$open_in_new = true;
if ( !isset($apimgwidth) )	$apimgwidth = 139;
if ( !isset($apimgheight) )	$apimgheight = 78;
if ( !isset($apfgrwidth) )	$apfgrwidth = 35;



/* Aparat for Wordpress Classes and Functions */

function wpAparatFeed( $channelID, $number ) {
	
	$channel_rss = "http://www.aparat.com/rss/".$channelID;
	
	if ( function_exists('fetch_feed') ) {
		
		$feed = fetch_feed( $channel_rss );
		
		if ( !is_wp_error($feed) ) : $feed->init();
			$feed->set_output_encoding('UTF-8');		// set encoding
			$feed->handle_content_type();				// ensure encoding
			$feed->set_cache_duration(21600);			// six hours in seconds
			$limit = $feed->get_item_quantity($number);	// get feed items
			$items = $feed->get_items(0, $limit);		// set array
		endif;
		
	}
	
	if ( $limit == 0 ) { 
		echo '<p>' . __( "Channel ID is incorrect or access to APARAT.com is currently unavailable.", "wpaparat" ) . '</p>'; 
	}
	else {
		
		?>
		
		<ul id="wpaparat" class="wpaparat-box">
		
		<?php
		
		$item_counter = 1;
		
		foreach ($items as $item) {
									
			global $open_in_new, $apimgheight, $apfgrwidth;
			
		?>
		
			<li class="aparat-feed-item<?php echo " item-".$item_counter; $item_counter++;?>">
			
				<div class="aparat-feed-item-box">
				
					<figure class="aparat-item-figure"<?php if( $apfgrwidth != 35 ) echo " style='width: $apfgrwidth%'"; ?>>
					<?php
						
						$item_page = file_get_contents( $item->get_permalink() );					
						$item_tags = new DOMDocument();
						@$item_tags->loadHTML( $item_page );
						
						$meta_og_img = null;
						$meta_og_duration = null;
						
						foreach ( $item_tags->getElementsByTagName('meta') as $meta ) {
							
							if($meta->getAttribute('property')=='og:image'){ 
								
								$meta_og_img = $meta->getAttribute('content');
							}
							if($meta->getAttribute('property')=='video:duration'){ 
								
								$meta_og_duration = $meta->getAttribute('content');
							}
						}
							
					?>
					
						<a href="<?php echo $item->get_permalink(); ?>" title="<?php echo str_replace( array( "&amp;", "&laquo;", "&raquo;" ), array( "&", "«", "»" ), $item->get_title() ); ?>" <?php if ( $open_in_new ) echo 'target="_blank"'; ?> class="aparatimgbox" style="background-image: url(<?php echo aparat_get_thumb( $meta_og_img ); ?>);">
							<span class="aparat-video-ratio"></span>
							<span class="aparat-video-duration">
								<?php
									if ( $meta_og_duration < 60 ) {
										echo "00:".$meta_og_duration;
									}
									else {
										$minutes = intval( $meta_og_duration / 60 );
										$meta_og_duration = $meta_og_duration % 60;
										
										if ( $minutes < 60 ) {
											if( $meta_og_duration < 10 ) {
												echo $minutes.":0".$meta_og_duration;
											} else {
												echo $minutes.":".$meta_og_duration;
											}
										}
										else {
											$hours = intval( $minutes / 60 );
											$minutes = $minutes % 60;
											
											if( $meta_og_duration < 10 ) {
												echo $hours.":".$minutes.":0".$meta_og_duration;
											} else {
												echo $hours.":".$minutes.":".$meta_og_duration;
											}
										}
									}
								?>
							</span>
						</a>
					</figure>
					
					<div class="aparat-item-info" style="min-height: <?php echo ( $apimgheight - 15 )?>px;<?php if( $apfgrwidth != 35 ) echo " width: " . (98-$apfgrwidth) . "%"; ?>">
						<h2 class="aparat-item-name"><a href="<?php echo $item->get_permalink(); ?>" title="<?php echo str_replace( array( "&amp;", "&laquo;", "&raquo;" ), array( "&", "«", "»" ), $item->get_title() ); ?>" <?php if ( $open_in_new ) echo 'target="_blank"'; ?>><?php echo str_replace( array( "&amp;", "&laquo;", "&raquo;" ), array( "&", "«", "»" ), $item->get_title() ); ?></a></h2>
						<span class="aparat-item-pub-date"><i class="icon-clock"></i> <?php if ( function_exists( 'jdate' ) ) { echo jdate( "j F Y", $item->get_date() ); } else { echo date( "j F Y", $item->get_date() ); } ?></span>
					</div>
				
				</div>
				
			</li>
			
		<?php
		
		}
		
		?>
		
		</ul>
		
		<?php
		
	}
	
}


/* Thumbnail Creator */

function aparat_get_thumb( $src_url = '', $width = null, $height = null, $crop = true, $cached = true ) {

	global $apimgwidth, $apimgheight, $custom_picture;

	if ( empty( $src_url ) ) throw new Exception( 'Invalid source URL' );
	if ( empty( $width ) ) $width = $apimgwidth;
	if ( empty( $height ) ) $height = $apimgheight;

	$src_info = pathinfo( $src_url );
	
	if ( !empty($src_info['basename']) ) {

		$upload_info = wp_upload_dir();

		$src_filename = explode( '__', $src_info['filename'] );
		$src_filename = $src_filename[0];
		
		$upload_dir = $upload_info['basedir'];
		$upload_url = $upload_info['baseurl'];
		$thumb_name = $src_filename."_".$width."X".$height.".".$src_info['extension'];

		if ( FALSE === strpos( $src_url, home_url() ) ) {
			
			$source_path = $upload_info['path'].'/'.$src_filename.".".$src_info['extension'];
			$thumb_path = $upload_info['path'].'/'.$thumb_name;
			$thumb_url = $upload_info['url'].'/'.$thumb_name;
			
			if ( !file_exists($source_path) && !copy($src_url, $source_path) ) {
				throw new Exception( 'No permission on upload directory: '.$upload_info['path'] );
			}

		} else {
			
			// define path of image
			$rel_path = str_replace( $upload_url, '', $src_url );
			$source_path = $upload_dir . $rel_path;
			$source_path_info = pathinfo($source_path);
			$thumb_path = $source_path_info['dirname'].'/'.$thumb_name;

			$thumb_rel_path = str_replace( $upload_dir, '', $thumb_path);
			$thumb_url = $upload_url . $thumb_rel_path;
			
		}

		if( $cached && file_exists($thumb_path) ) return $thumb_url;

		$editor = wp_get_image_editor( $source_path );
		$editor->resize( $width, $height, $crop );
		$new_image_info = $editor->save( $thumb_path );

		if( empty($new_image_info) ) {
			throw new Exception( 'Failed to create thumb: '.$thumb_path );
			if ( !empty($custom_picture) ) {
				$thumb_url = $custom_picture;
			} else {
				$thumb_url = plugin_dir_url( __FILE__ )."assets/images/aparat-nothumb.png";
			}
		}
		
	}
	
	if ( empty($thumb_url) ) {
	
		if ( !empty($custom_picture) ) {
			$thumb_url = $custom_picture;
		} else {
			$thumb_url = plugin_dir_url( __FILE__ )."assets/images/aparat-nothumb.png";
		}
		
	}

	return $thumb_url;
	
}


/* Aparat Widget Class */

class wpAparat extends WP_Widget {

	function __construct() {
		parent::__construct( 'wpaparat_widget', __('Aparat for Wordpress', 'wpaparat'), array( 'description' => __( "Show your Aparat channel's videos on sidebar or footer", 'wpaparat' ), ) );
	}
	
	public function widget( $args, $instance ) {
		
		$title = apply_filters( 'widget_title', $instance['title'] );
		$channelID = apply_filters( 'widget_title', $instance['channelID'] );
		$aparatnumber = apply_filters( 'widget_title', $instance['aparatnumber'] );
		
		echo $args['before_widget'];
		
		if ( ! empty( $title ) )
			echo $args['before_title'] . $title . $args['after_title'];
		
		wpAparatFeed( $channelID, $aparatnumber );
		
		echo $args['after_widget'];
		
	}
	
	public function form( $instance ) {
		
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = __( 'My Aparat Videos', 'wpaparat' );
		}
		
		if ( isset( $instance[ 'channelID' ] ) ) {
			$channelID = $instance[ 'channelID' ];
		}
		else {
			$channelID = '';
		}
		
		if ( isset( $instance[ 'aparatnumber' ] ) ) {
			$aparatnumber = $instance[ 'aparatnumber' ];
		}
		else {
			$aparatnumber = 5;
		}
		
		?>
			<p>
				<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
				<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'channelID' ); ?>"><?php _e( 'Channel ID: (Username)', 'wpaparat' ); ?></label> 
				<input class="widefat" id="<?php echo $this->get_field_id( 'channelID' ); ?>" name="<?php echo $this->get_field_name( 'channelID' ); ?>" type="text" value="<?php echo esc_attr( $channelID ); ?>" placeholder="<?php _e('Insert your channel ID', 'wpaparat') ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'aparatnumber' ); ?>"><?php _e( 'Number of videos:', 'wpaparat' ); ?></label> 
				<input class="widefat" id="<?php echo $this->get_field_id( 'aparatnumber' ); ?>" name="<?php echo $this->get_field_name( 'aparatnumber' ); ?>" type="number" min="1" max="10" value="<?php echo esc_attr( $aparatnumber ); ?>" />
			</p>
		<?php 
		
	}
	
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['channelID'] = ( ! empty( $new_instance['channelID'] ) ) ? strip_tags( $new_instance['channelID'] ) : '';
		$instance['aparatnumber'] = ( ! empty( $new_instance['aparatnumber'] ) ) ? strip_tags( $new_instance['aparatnumber'] ) : '';
		return $instance;
	}
}

function wpaparat_load_widget() {
	register_widget( 'wpAparat' );
}
add_action( 'widgets_init', 'wpaparat_load_widget' );


/* Wordpress Editor And Shortcode */

function wpaparat_shortcode($atts) {
	extract(
		shortcode_atts( array(
			'id'		=> '',
			'width'		=> '600',
		), $atts )
	);
	$height = intval( 9 * $width / 16 );
	return "<iframe src='http://www.aparat.com/video/video/embed/videohash/{$id}/vt/frame' width='{$width}' height='{$height}' allowfullscreen='true' class='aparat-frame'></iframe>";
}
add_shortcode( 'aparat', 'wpaparat_shortcode' );

function wpaparat_editor_botton($buttons) {
	array_push($buttons, "separator", "aparat_shortcode");
	return $buttons;
}
add_filter('mce_buttons', 'wpaparat_editor_botton', 0);

	
	
/* Load Scripts And Styles To Site */

function wpaparat_adding_scripts() {
	wp_enqueue_style( 'wpaparat', plugin_dir_url( __FILE__ ) . "assets/css/style.css", null, null );
}
add_action( 'wp_enqueue_scripts', 'wpaparat_adding_scripts' );

add_filter( 'body_class', 'wpaparat_body_class' );
function wpaparat_body_class( $classes ) {
    if ( is_rtl() ) {
        $classes[] = 'rtl';
    }
    return $classes;
}

function wpaparat_admin_css() {
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
add_action('admin_head', 'wpaparat_admin_css');


/* Add TinyMCE Button */

function wpaparat_add_tinymce() {
    global $typenow;

    if( ! in_array( $typenow, array( 'post', 'page' ) ) )
        return ;

    add_filter( 'mce_external_plugins', 'wpaparat_add_tinymce_plugin' );
}
add_action( 'admin_head', 'wpaparat_add_tinymce' );

function wpaparat_add_tinymce_plugin( $plugin_array ) {
    $plugin_array['aparat_shortcode'] = plugins_url('assets/js/editor_plugin.js', __FILE__);
    return $plugin_array;
}

function wpaparat_admin_js_variables() {
	echo '
	<script>
		var aparat_video_add		= "' . __( "add Aparat video", 'wpaparat' ) . '";
		var aparat_video_id			= "' . __( "ID:", 'wpaparat' ) . '";
		var aparat_video_id_insert	= "' . __( "Insert Aparat video ID :", 'wpaparat' ) . '";
		var aparat_video_id_desc	= "' . __( "for example, the ID of http://www.aparat.com/v/GHseX is : GHseX", 'wpaparat' ) . '";
		var aparat_video_width		= "' . __( "Width:", 'wpaparat' ) . '";
		var aparat_video_width_desc	= "' . __( "Enter the Picture Width, e.g.: 300", 'wpaparat' ) . '";
		var aparat_video_width_dft	= "' . __( "Default picture width: 600", 'wpaparat' ) . '";
	</script>
	';
}
add_action( 'admin_head', 'wpaparat_admin_js_variables' );

?>