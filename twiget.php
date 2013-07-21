<?php
/*
Plugin Name: TwiGet Twitter Widget
Plugin URI: http://www.prasannasp.net/wordpress-plugins/twiget/
Description: A widget to display the latest Twitter status updates.
Author: Prasanna SP
Version: 1.1
Author URI: http://www.prasannasp.net/
*/

/*  This file is part of TwiGet Twitter Widget plugin, developed by Syahir Hakim (email : syahir at khairul dash syahir dot com) and Prasanna SP (email: prasanna[AT]prasannasp.net)

    TwiGet Twitter Widget is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    TwiGet Twitter Widget is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with TwiGet Twitter Widget plugin. If not, see <http://www.gnu.org/licenses/>.
*/

/**
 * Load plugin textdomain
 *
 * @package Twiget Twitter Widget
 * @since 1.0
 */

function load_twiget_plugin_textdomain() {
  load_plugin_textdomain( 'twiget', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' ); 
}
add_action('plugins_loaded', 'load_twiget_plugin_textdomain');

define( 'TWIGET_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
require_once TWIGET_PLUGIN_PATH . 'twiget-options-form.php';

require_once TWIGET_PLUGIN_PATH . 'twiget-get-tweets.php';

/**
 * Register custom Twitter widgets.
 *
 * @package Twiget Twitter Widget
 * @since 1.0
 */
global $twiget_username;
global $twiget_tweetcount;
$twiget_username = '';
$twiget_tweetcount = 1;

class Twiget_Twitter_Widget extends WP_Widget{
	
	function Twiget_Twitter_Widget(){
		// Widget settings
		$widget_ops = array( 'classname' => 'twiget-widget', 'description' => __( 'Display the latest Twitter status updates.', 'twiget' ) );
		
		// Widget control settings
		$control_ops = array( 'id_base' => 'twiget-widget' );
		
		// Create the widget
		$this->WP_Widget( 'twiget-widget', 'TwiGet Twitter Widget', $widget_ops, $control_ops);
		
		/* Enqueue the twitter script and css if widget is active */
		if ( is_active_widget( false, false, $this->id_base, true ) && ! is_admin() ) {
			wp_enqueue_script( 'twiget-widget-js', plugins_url( '/js/twiget.js' , __FILE__ ), array(), '', false );
			wp_enqueue_style( 'twiget-widget-css', plugins_url( '/css/twiget.css' , __FILE__ ), array(), '', false );
		}
	}
	
	function widget( $args, $instance ){		// This function displays the widget
		extract( $args );

		// User selected settings
		global $twiget_username;
		global $twiget_tweetcount;
		global $twiget_followercount;
		global $twiget_hide_replies;
		global $twiget_twitter_newwindow;
		global $profile_pic;
		global $show_username;
		global $twitter_client;
		
		$twiget_title = apply_filters( 'twiget_widget_title', empty($instance['twiget_title']) ? __( 'Latest tweets', 'twiget' ) : $instance['twiget_title'], $instance, $this->id_base);	
		$twiget_username = $instance['twiget_username'];
		$twiget_tweetcount = $instance['twiget_tweetcount'];
		$twiget_followercount = $instance['twiget_followercount'];
		$twiget_hide_replies = ( array_key_exists( 'twiget_hide_replies', $instance ) ) ? $instance['twiget_hide_replies'] : false ;
		$twiget_new_window = $instance['twiget_new_window'];
		$profile_pic = ( array_key_exists( 'profile_pic', $instance ) ) ? $instance['profile_pic'] : false ;
		$show_username = ( array_key_exists( 'show_username', $instance ) ) ? $instance['show_username'] : false ;
		$twitter_client = ( array_key_exists( 'twitter_client', $instance ) ) ? $instance['twitter_client'] : false ;
		$wrapper_id = 'tweet-wrap-' . $args['widget_id'];
		
		$twiget_follower_count_attr = ( $twiget_followercount ) ? 'data-show-count="true"' : 'data-show-count="false"';
	
		$hide_replies_attr = ( $twiget_hide_replies ) ? 'true' : 'false';
		
		echo $args['before_widget'].$args['before_title'].$twiget_title.$args['after_title'];
		?>
		
		<div class="twiget-feed">
		<ul id="<?php echo $wrapper_id; ?>">
            	<li><img src="<?php echo '' .plugins_url( '/images/ajax-loader.gif' , __FILE__ ). ''; ?>" width="16" height="16" alt="" /> <?php _e( 'Loading tweets...', 'twiget' ); ?></li>
            </ul>
            <p id="twigetfollow">
            	<a href="https://twitter.com/<?php echo $twiget_username; ?>" class="twitter-follow-button" <?php echo $twiget_follower_count_attr; ?> data-width="100%" data-align="right"><?php printf( __( 'Follow %s', 'twiget' ), '@' . $twiget_username ); ?></a>
			<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
            </p>
            <script type="text/javascript">
                jQuery(document).ready(function($) {
                    var tweetOptions = {
                            screen_name: '<?php echo $twiget_username; ?>',
                            count: <?php echo $twiget_tweetcount; ?>,
                            include_rts: true,
							exclude_replies: <?php echo $hide_replies_attr; ?>
                    };
                    $.getJSON( '<?php echo get_home_url( '', '?twiget=json', '' ); ?>', tweetOptions, function(data){
                       TwigetTwitter( data, '<?php echo $wrapper_id; ?>', {<?php if ( $twiget_new_window ) echo 'newwindow:true,'; 
																		         if ( $profile_pic ) echo 'profilepic:true,'; 
																			     if ( $show_username ) echo 'showusername:true,';
																			     if ( $twitter_client ) echo 'twitterclient:true,'; ?>});
                    });
                });
            </script>
            <?php do_action( 'twiget_twitter_widget' ); ?>
        <?php echo $args['after_widget']; ?>
        </div>
        <?php
	}
	
	function update( $new_instance, $old_instance ){	// This function processes and updates the settings
		$instance = $old_instance;
		
		// Strip tags (if needed) and update the widget settings
		$instance['twiget_username'] = strip_tags( $new_instance['twiget_username']);
		$instance['twiget_tweetcount'] = strip_tags( $new_instance['twiget_tweetcount']);
		$instance['twiget_title'] = strip_tags( $new_instance['twiget_title'] );
		$instance['twiget_followercount'] = ( isset( $new_instance['twiget_followercount'] ) ) ? true : false ;
		$instance['twiget_hide_replies'] = ( isset( $new_instance['twiget_hide_replies'] ) ) ? true : false ;
		$instance['twiget_new_window'] = ( isset( $new_instance['twiget_new_window'] ) ) ? true : false ;
		$instance['profile_pic'] = ( isset( $new_instance['profile_pic'] ) ) ? true : false ;
		$instance['show_username'] = ( isset( $new_instance['show_username'] ) ) ? true : false ;
		$instance['twitter_client'] = ( isset( $new_instance['twitter_client'] ) ) ? true : false ;
	
		return $instance;
	}
	
	function form( $instance ){		// This function sets up the settings form
		
		// Set up default widget settings
		$defaults = array( 'twiget_username' => '',
						'twiget_tweetcount' => 5,
						'twiget_title' => __( 'Latest tweets', 'twiget' ),
						'twiget_followercount' => false,
						'twiget_hide_replies' => false,
						'twiget_new_window' => false,
						'profile_pic' => false,
						'show_username' => false,
						'twitter_client' => false
						);
		$instance = wp_parse_args( (array) $instance, $defaults );
		?>
        <p>
        	<label for="<?php echo $this->get_field_id( 'twiget_title' ); ?>"><?php _e( 'Title:', 'twiget' ); ?></label>
			<input id="<?php echo $this->get_field_id( 'twiget_title' ); ?>" type="text" name="<?php echo $this->get_field_name( 'twiget_title' ); ?>" value="<?php echo $instance['twiget_title']; ?>" class="widefat" />
        </p>
        <p>
        	<label for="<?php echo $this->get_field_id( 'twiget_username' ); ?>"><?php _e( 'Twitter Username:', 'twiget' ); ?></label>
			<input id="<?php echo $this->get_field_id( 'twiget_username' ); ?>" type="text" name="<?php echo $this->get_field_name( 'twiget_username' ); ?>" value="<?php echo $instance['twiget_username']; ?>" class="widefat" />
        </p>
        <p>
        	<label for="<?php echo $this->get_field_id( 'twiget_tweetcount' ); ?>"><?php _e( 'Number of tweets to display:', 'twiget' ); ?></label>
			<input id="<?php echo $this->get_field_id( 'twiget_tweetcount' ); ?>" type="text" name="<?php echo $this->get_field_name( 'twiget_tweetcount' ); ?>" value="<?php echo $instance['twiget_tweetcount']; ?>" size="1" />
        </p>
        <p>
        	<label for="<?php echo $this->get_field_id( 'twiget_followercount' ); ?>"><?php _e( 'Show followers count', 'twiget' ); ?></label>
			<input id="<?php echo $this->get_field_id( 'twiget_followercount' ); ?>" type="checkbox" name="<?php echo $this->get_field_name( 'twiget_followercount' ); ?>" value="true" <?php checked( $instance['twiget_followercount'] ); ?> />
        </p>
        <p>
        	<label for="<?php echo $this->get_field_id( 'profile_pic' ); ?>"><?php _e( 'Show profile picture', 'twiget' ); ?></label>
			<input id="<?php echo $this->get_field_id( 'profile_pic' ); ?>" type="checkbox" name="<?php echo $this->get_field_name( 'profile_pic' ); ?>" value="true" <?php checked( $instance['profile_pic'] ); ?> />
        </p>
        <p>
        	<label for="<?php echo $this->get_field_id( 'show_username' ); ?>"><?php _e( 'Show twitter username with each tweet', 'twiget' ); ?></label>
			<input id="<?php echo $this->get_field_id( 'show_username' ); ?>" type="checkbox" name="<?php echo $this->get_field_name( 'show_username' ); ?>" value="true" <?php checked( $instance['show_username'] ); ?> />
        </p>
        <p>
        	<label for="<?php echo $this->get_field_id( 'twitter_client' ); ?>"><?php _e( 'Show twitter client used', 'twiget' ); ?></label>
			<input id="<?php echo $this->get_field_id( 'twitter_client' ); ?>" type="checkbox" name="<?php echo $this->get_field_name( 'twitter_client' ); ?>" value="true" <?php checked( $instance['twitter_client'] ); ?> /><br />
			<span class="description"><?php _e( 'Eg: via Twitter for Android', 'twiget' ); ?></span>
        </p>
         <p>
        	<label for="<?php echo $this->get_field_id( 'twiget_hide_replies' ); ?>"><?php _e( 'Hide @replies', 'twiget' ); ?></label>
			<input id="<?php echo $this->get_field_id( 'twiget_hide_replies' ); ?>" type="checkbox" name="<?php echo $this->get_field_name( 'twiget_hide_replies' ); ?>" value="true" <?php checked( $instance['twiget_hide_replies'] ); ?> /><br />
			<span class="description"><?php $showtweetcount = $instance['twiget_tweetcount']; printf( __('Note: Selecting this sometimes result in showing less than %d tweets', 'twiget' ), $showtweetcount ); ?></span>
        </p>
        <p>
        	<label for="<?php echo $this->get_field_id( 'twiget_new_window' ); ?>"><?php _e( 'Open links in new window', 'twiget' ); ?></label>
			<input id="<?php echo $this->get_field_id( 'twiget_new_window' ); ?>" type="checkbox" name="<?php echo $this->get_field_name( 'twiget_new_window' ); ?>" value="true" <?php checked( $instance['twiget_new_window'] ); ?> />
        </p>
        <?php
	}
}


/**
 * Register the custom widget by passing the twiget_load_widgets() function to widgets_init
 * action hook.
*/ 
function twiget_load_widgets(){
	register_widget( 'Twiget_Twitter_Widget' );
}
add_action( 'widgets_init', 'twiget_load_widgets' );

/*
 ** Delete options table entries ONLY when plugin deactivated AND deleted (twiget-options-form.php)
 */
function twiget_delete_plugin_options(){
	delete_option('twiget_options');
}
register_uninstall_hook(__FILE__, 'twiget_delete_plugin_options');

/* 
 ** Define default option settings
 */ 
function twiget_add_defaults(){
	$tmp = get_option('twiget_options');
    if(($tmp['twiget_default_options_db']=='1')||(!is_array($tmp))) {
		delete_option('twiget_options');
		$arr = array(	"twiget_default_options_db" => ""
		);
		update_option('twiget_options', $arr);
	}
}
register_activation_hook(__FILE__, 'twiget_add_defaults');

/* 
 ** Init plugin options to white list our options
 */ 
function twiget_init(){
	register_setting( 'twiget_plugin_options', 'twiget_options', 'twiget_validate_options' );
}
add_action('admin_init', 'twiget_init' );

/*
 ** Add menu page (wp-admin/options-general.php?page=twiget/twiget.php)
 */
function twiget_add_options_page(){
	add_options_page('Twiget Twitter Plugin Settings', 'Twiget Settings', 'manage_options', __FILE__, 'twiget_render_form');
}
add_action('admin_menu', 'twiget_add_options_page');

/*
 ** Sanitize and validate input. Accepts an array, return a sanitized array.
 */
function twiget_validate_options($input){
	 // strip html from textboxes
	$input['consumer_key'] =  wp_filter_nohtml_kses($input['consumer_key']);
	$input['consumer_secret'] =  wp_filter_nohtml_kses($input['consumer_secret']);
	$input['access_token'] =  wp_filter_nohtml_kses($input['access_token']);
	$input['access_token_secret'] =  wp_filter_nohtml_kses($input['access_token_secret']);
	return $input;
}

/* 
 ** More plugins link on manage plugin page
 */
function twiget_pluginspage_links( $links, $file ){

$plugin = plugin_basename (__FILE__);

// create links
if ( $file == $plugin ) {
return array_merge(
$links,
array( '<a href="http://www.prasannasp.net/donate/" target="_blank" title="'.esc_attr__('Donate for this plugin via PayPal', 'twiget').'">'.__('Donate', 'twiget').'</a>',
'<a href="http://www.prasannasp.net/wordpress-plugins/" target="_blank" title="'.esc_attr__('View more plugins from the developer', 'twiget').'">'.__('More Plugins', 'twiget').'</a>',
'<a href="http://twitter.com/prasannasp" target="_blank" title="'.esc_attr__('Follow me on twitter!', 'twiget').'">'.__('twitter!', 'twiget').'</a>'
 )
);
			}
return $links;

	}
add_filter( 'plugin_row_meta', 'twiget_pluginspage_links', 10, 2 );

/*
 ** Display a Support forum link on the main Plugins page
 */
function twiget_plugin_action_links( $links, $file ){

	if ( $file == plugin_basename( __FILE__ ) ) {
		$twiget_link1 = '<a href="http://forum.prasannasp.net/forum/plugin-support/twiget/" title="'.esc_attr__('TwiGet Twitter Widget support', 'twiget').'" target="_blank">'.__('Support', 'twiget').'</a>';
		$twiget_link2 = '<a href="'.get_admin_url().'options-general.php?page=twiget/twiget.php">'.__('Settings', 'twiget').'</a>';

		array_unshift( $links, $twiget_link1, $twiget_link2 );
	}

	return $links;
}
add_filter('plugin_action_links', 'twiget_plugin_action_links', 10, 2 );

/**
 * Display admin notice if Twitter oAuth info is missing.
 *
 * @package Twiget Twitter Widget
 * @since 1.1
 */
function twiget_admin_notice_missing_api(){
	$opts = get_option( 'twiget_options' );
	$req_opts = array( 'consumer_key', 'consumer_secret', 'access_token', 'access_token_secret' );
	$api_exists = true;
	foreach ( $req_opts as $req_opt ) {
		if ( ! array_key_exists( $req_opt, $opts ) ) {
			$api_exists = false;
			break;
		} elseif ( ! $opts[$req_opt] ) {
			$api_exists = false;
			break;
		}
	}
	if ( $api_exists ) return;
	?>
    <div class="error">
       <p><?php printf( __( 'Twiget Twitter Widget plugin requires your Twitter API credentials to work. See <a href="%s">Twiget\'s Options Page</a> for instructions on how to set this up.', 'twiget' ), admin_url( 'options-general.php?page=twiget/twiget.php' ) ); ?></p>
    </div>
    <?php
}
add_action( 'admin_notices', 'twiget_admin_notice_missing_api' );


/*
** Tell WordPress to process "?twiget" custom URL parameter
 *
 * @package Twiget Twitter Widget
 * @since 1.1
*/
function twiget_query_vars($vars){
    $vars[] = 'twiget';
    return $vars;
}
add_filter('query_vars', 'twiget_query_vars');

/*
** Call twiget_get_tweets_and_encode() function to return JSON data in site.url/?twiget=json
 *
 * @package Twiget Twitter Widget
 * @since 1.1
*/
function twiget_query_var_check(){
    if( get_query_var('twiget') == 'json' ) {
	twiget_get_tweets_and_encode();
    	exit;
    }
}
add_action('template_redirect', 'twiget_query_var_check');

/*
** Localize timestamp strings
 *
 * @package Twiget Twitter Widget
 * @since 1.1
*/

function twiget_localize_scripts(){
	$twiget_args = array( 	
			'About'  => __( 'about&nbsp;', 'twiget' ),
			'LessThanMin'  => __( 'less than a minute ago', 'twiget' ),
			'AboutAMin'  => __( 'about a minute ago', 'twiget' ),
			'MinutesAgo'  => __( '&nbsp;minutes ago', 'twiget' ),
			'AnHourAgo'  => __( 'about an hour ago', 'twiget' ),
			'HoursAgo'  => __( '&nbsp;hours ago', 'twiget' ),
			'OneDayAgo'  => __( '1 day ago', 'twiget' ),
			'DaysAgo'  => __( '&nbsp;days ago', 'twiget' )		
		    );
   wp_localize_script( 'twiget-widget-js', 'TwigetArgs', $twiget_args );
}
add_action( 'wp_enqueue_scripts', 'twiget_localize_scripts' );
