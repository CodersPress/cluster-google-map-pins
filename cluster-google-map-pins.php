<?php
/*
Plugin Name: Cluster Pro Map Markers
Plugin URI: http://coderspress.com/
Description: lklkllkGrid-based clustering works by dividing the map into squares of a certain size and then grouping the markers into each grid square.
Version: 2015.0508
Updated: 8th May 2015
Author: sMarty
Author URI: http://coderspress.com/
WP_Requires: 3.8.1
WP_Compatible: 4.2.2
License: http://creativecommons.org/licenses/GPL/2.0
*/
add_action( 'init', 'clp_plugin_updater' );
function clp_plugin_updater() {
	if ( is_admin() ) { 
	include_once( dirname( __FILE__ ) . '/updater.php' );
		$config = array(
			'slug' => plugin_basename( __FILE__ ),
			'proper_folder_name' => 'cluster-google-map-pins',
			'api_url' => 'https://api.github.com/repos/CodersPress/cluster-google-map-pins',
			'raw_url' => 'https://raw.github.com/CodersPress/cluster-google-map-pins/master',
			'github_url' => 'https://github.com/CodersPress/cluster-google-map-pins',
			'zip_url' => 'https://github.com/CodersPress/cluster-google-map-pins/zipball/master',
			'sslverify' => true,
			'access_token' => 'de82b27a4c9319264b744100a91ec49d952d59c9',
		);
		new WP_CMP_UPDATER( $config );
	}
}

// create custom plugin settings menu
add_action('admin_menu', 'cluster_map_menu');
function cluster_map_menu() {
	add_menu_page('Cluster Map Settings', 'Cluster Map', 'administrator', __FILE__, 'cluster_map_settings_page',plugins_url('/images/cluster.png', __FILE__));
	add_action( 'admin_init', 'register_mapsettings' );
}
function cluster_map_admin_scripts() {
wp_enqueue_script('media-upload');
wp_enqueue_script('thickbox');
wp_enqueue_script('jquery');
}
function cluster_map_admin_styles() {
wp_enqueue_style('thickbox');
}
add_action('admin_print_scripts', 'cluster_map_admin_scripts');
add_action('admin_print_styles', 'cluster_map_admin_styles');
function register_mapsettings() {
	register_setting( 'cluster-map-settings-group', 'gridSize_option' );
	register_setting( 'cluster-map-settings-group', 'minimum_zoom' );
	register_setting( 'cluster-map-settings-group', 'm1_font_color' );
    register_setting( 'cluster-map-settings-group', 'm2_font_color' );
	register_setting( 'cluster-map-settings-group', 'm3_font_color' );
	register_setting( 'cluster-map-settings-group', 'cluster_m1_img');
    register_setting( 'cluster-map-settings-group', 'cluster_m2_img');
	register_setting( 'cluster-map-settings-group', 'cluster_m3_img');
}
function cluster_map_defaults()
{
    $option = array(
        'gridSize_option' => '50',
        'minimum_zoom' => '4',
        'm1_font_color' => '#fff',
        'm2_font_color' => '#fff',
        'm3_font_color' => '#fff',
        'cluster_m1_img' => plugins_url( 'cluster-google-map-pins/images/m1.png' ),
        'cluster_m2_img' => plugins_url( 'cluster-google-map-pins/images/m2.png' ),
        'cluster_m3_img' => plugins_url( 'cluster-google-map-pins/images/m3.png' ),
    );
    foreach ( $option as $key => $value )
    {
       if (get_option($key) == NULL) {
        update_option($key, $value);
       }
    }
    return;
}
register_activation_hook(__FILE__, 'cluster_map_defaults');
function cluster_map_settings_page() {
?>
<script language="JavaScript">
var upload_image_button=false;
    jQuery(document).ready(function() {
    jQuery('.upload_image_button').click(function() {
        upload_image_button =true;
        formfieldID=jQuery(this).prev().attr("id");
     formfield = jQuery("#"+formfieldID).attr('name');
     tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
        if(upload_image_button==true){
                var oldFunc = window.send_to_editor;
                window.send_to_editor = function(html) {
                imgurl = jQuery('img', html).attr('src');
                jQuery("#"+formfieldID).val(imgurl);
                 tb_remove();
                window.send_to_editor = oldFunc;
                }
        }
        upload_image_button=false;
    });
});
</script>
<div class="wrap">
<h2>Cluster Map Settings</h2>
<form method="post" action="options.php">
    <?php settings_fields( 'cluster-map-settings-group' ); ?>
    <?php do_settings_sections( 'cluster-map-settings-group' ); ?>
    <table class="form-table">
<tr valign="top">
        <th scope="row">Marker Font Color</th>
        <td>
    M 0-9: <input type="text" name="m1_font_color" value="<?php echo get_option( 'm1_font_color' ); ?>"/>
    M 10-99: <input type="text" name="m2_font_color" value="<?php echo get_option('m2_font_color'); ?>"/>
    M 100: <input type="text" name="m3_font_color" value="<?php echo get_option('m3_font_color'); ?>"/>
<br />Specifies the text color for each of the <b>M</b>arkers. Default : #fff (white)
        </tr>
        <tr valign="top">
        <th scope="row">Cluster Icon Images</th>
<td> <button id="button_m1_img">Load Default</button>
<script>
  jQuery("#button_m1_img").click(function(){
    jQuery("#cluster_m1_img").val("<?php echo plugins_url( 'cluster-google-map-pins/images/m1.png' );?>");
  });
</script>
        <input id="cluster_m1_img" type="text" size="40" name="cluster_m1_img" value="<?php echo get_option( 'cluster_m1_img' ); ?>" />
        <input class="upload_image_button" type="button" value="Upload Custom" /><img src="<?php echo get_option( 'cluster_m1_img'); ?>">
        <br />URL or Upload Custom image for Clusters 0-9. Size: width 54px X height 54px 
</td>
        </tr>
        <tr valign="top">
        <th scope="row"></th>
<td><button id="button_m2_img">Load Default</button>
<script>
  jQuery("#button_m2_img").click(function(){
    jQuery("#cluster_m2_img").val("<?php echo plugins_url( 'cluster-google-map-pins/images/m2.png' );?>");
  });
</script>
        <input id="cluster_m2_img" type="text" size="40" name="cluster_m2_img" value="<?php echo get_option( 'cluster_m2_img' ); ?>" />
        <input class="upload_image_button" type="button" value="Upload Custom" /><img src="<?php echo get_option( 'cluster_m2_img' ); ?>">
        <br />URL or Upload Custom image for Clusters 10-99. Size: width 56px X height 55px  
</td>
        </tr>
<tr valign="top">
        <th scope="row"></th>
<td><button id="button_m3_img">Load Default</button>
<script>
  jQuery("#button_m3_img").click(function(){
    jQuery("#cluster_m3_img").val("<?php echo plugins_url( 'cluster-google-map-pins/images/m3.png' );?>");
  });
</script>
        <input id="cluster_m3_img" type="text" size="40" name="cluster_m3_img" value="<?php echo get_option( 'cluster_m3_img' ); ?>" />
        <input class="upload_image_button" type="button" value="Upload Custom" /><img src="<?php echo get_option( 'cluster_m3_img' ); ?>">
        <br />URL or Upload Custom image for Clusters 100. Size: width 66px X height 65px 
</td>
        </tr>
        <tr valign="top">
        <th scope="row">gridSize Level</th>
        <td>
<select name="gridSize_option" />
    <option value="10" <?php if ( get_option('gridSize_option') == 10 ) echo 'selected="selected"'; ?>>10</option>
    <option value="20" <?php if ( get_option('gridSize_option') == 20 ) echo 'selected="selected"'; ?>>20</option>
    <option value="30" <?php if ( get_option('gridSize_option') == 30 ) echo 'selected="selected"'; ?>>30</option>
    <option value="40" <?php if ( get_option('gridSize_option') == 40 ) echo 'selected="selected"'; ?>>40</option>
    <option value="50" <?php if ( get_option('gridSize_option') == 50 ) echo 'selected="selected"'; ?>>50</option>
    <option value="60" <?php if ( get_option('gridSize_option') == 60 ) echo 'selected="selected"'; ?>>60</option>
    <option value="70" <?php if ( get_option('gridSize_option') == 70 ) echo 'selected="selected"'; ?>>70</option>
    <option value="80" <?php if ( get_option('gridSize_option') == 80 ) echo 'selected="selected"'; ?>>80</option>
    <option value="90" <?php if ( get_option('gridSize_option') == 90 ) echo 'selected="selected"'; ?>>90</option>
    <option value="100" <?php if ( get_option('gridSize_option') == 100 ) echo 'selected="selected"'; ?>>100</option>
</select> Default: 50
<br />Specifies the size of a grid for each cluster group in pixel.
</td></tr>
        <tr valign="top">
        <th scope="row">Lowest Zoom Level</th>
        <td>
<select name="minimum_zoom" />
    <option value="2" <?php if ( get_option('minimum_zoom') == 2 ) echo 'selected="selected"'; ?>>2</option>
    <option value="3" <?php if ( get_option('minimum_zoom') == 3 ) echo 'selected="selected"'; ?>>3</option>
    <option value="4" <?php if ( get_option('minimum_zoom') == 4 ) echo 'selected="selected"'; ?>>4</option>
    <option value="5" <?php if ( get_option('minimum_zoom') == 5 ) echo 'selected="selected"'; ?>>5</option>
    <option value="6" <?php if ( get_option('minimum_zoom') == 6 ) echo 'selected="selected"'; ?>>6</option>
    <option value="7" <?php if ( get_option('minimum_zoom') == 7 ) echo 'selected="selected"'; ?>>7</option>
    <option value="8" <?php if ( get_option('minimum_zoom') == 8 ) echo 'selected="selected"'; ?>>8</option>
    <option value="9" <?php if ( get_option('minimum_zoom') == 9 ) echo 'selected="selected"'; ?>>9</option>
    <option value="10" <?php if ( get_option('minimum_zoom') == 10 ) echo 'selected="selected"'; ?>>10</option>
</select> Recommend: 4
<br />Reduces map(globe) overlapping.
</td></tr>
    </table>
    <?php submit_button(); ?>
</form>
</div>
<?php }
add_action( 'wp_footer', 'cluster_pins');
function cluster_pins(){
if ( is_home() ) {
?>
<script src="http://google-maps-utility-library-v3.googlecode.com/svn/trunk/markerclusterer/src/markerclusterer.js" type="text/javascript"></script>
<script type="text/javascript">
setTimeout(function(){
if (AllMarkers.length > 0) {
    for (var i = 0; i < AllMarkers.length; i++) {
        AllMarkers[i].setMap(null);
    }
}
}, 100);
var clusterStyles = [
  	 {
    textColor: '<?php echo get_option('m1_font_color'); ?>',
    url: ' <?php echo get_option('cluster_m1_img'); ?>',
 	width: 53,
	height: 53
  },
 	 {
    textColor: '<?php echo get_option('m2_font_color'); ?>',
    url: ' <?php echo get_option('cluster_m2_img'); ?>',
	width: 56,
	height: 55
  },
 	 {
    textColor: '<?php echo get_option('m3_font_color'); ?>',
    url: ' <?php echo get_option('cluster_m3_img'); ?>',
	width: 66,
	height: 65
}];
var listing_item = locations;
var latlnglist = [];
var markers = [];
var marker;
for (i = 0; i < listing_item.length; i++) {
    var position = new google.maps.LatLng(listing_item[i][0], listing_item[i][1]);
    latlnglist.push(position);
    bounds.extend(position);
    var plink = listing_item[i][2];
    var pinfo = listing_item[i][3];
    var ptitle = listing_item[i][5];
    map_icon = eval("icon_" + listing_item[i][6]);
    var markerOptions = {
        position: position,
        icon: map_icon,
        map: map,
        title: ptitle,
        info: '<div class="wlt-marker-wrapper"><div class="wlt-marker-title"> <a href="'+ plink +'">'+ ptitle +'</a>  <div class="close" onClick=\'javascript:infoBox.close();\'><span class="glyphicon glyphicon-remove"></span></div> </div><div class="wlt-marker-content" style="padding-left:5px;">'+pinfo+'<div class="readmore"><a href="'+ plink +'"><?php global $CORE; echo $CORE->_e(array('button','40','flag_noedit')); ?></a></div><div class="clearfix"></div></div>',
    }
    marker = new google.maps.Marker(markerOptions);
    markers.push(marker);
    var infoBoxOptions = {
        content: document.createElement("div"),
        pixelOffset: new google.maps.Size(-10, -220),
        pane: "floatPane",
        enableEventPropagation: true,
        closeBoxURL: ""
    };
    infoBox = new InfoBox(infoBoxOptions);
    google.maps.event.addListener(marker, 'click', (function (marker, i) {
        return function () {
            //infoBox.close();
            infoBox.setContent(this.info);
            infoBox.open(map, this);
        }
    })(marker, i));
}
var clusterOptions = {
    styles: clusterStyles,
    gridSize: <?php echo get_option( 'gridSize_option');?>
}
var markerCluster = new MarkerClusterer(map, markers, clusterOptions);
var bounds = new google.maps.LatLngBounds();
for (var i = 0, len = latlnglist.length; i < len; i++) {
    bounds.extend(latlnglist[i]);
}
var miniZoom = google.maps.event.addListener(map, 'zoom_changed', function() {
    if (map.getZoom() < <?php echo get_option( 'minimum_zoom');?> ) map.setZoom(<?php echo get_option( 'minimum_zoom');?>);
  });
map.fitBounds(bounds);
setTimeout(function(){
google.maps.event.removeListener(miniZoom);
}, 4500);
</script>
<?php }  } ?>