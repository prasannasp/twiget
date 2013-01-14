<?php
/*
Plugin Name: TwiGet Twitter Widget
Plugin URI: http://www.prasannasp.net/wordpress-plugins/twiget-twitter-widget/
Description: A widget to display the latest Twitter status updates.
Author: Prasanna SP
Version: 1.0
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
*/

function load_twiget_plugin_textdomain() {
  load_plugin_textdomain( 'twiget', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' ); 
}
add_action('plugins_loaded', 'load_twiget_plugin_textdomain');

/**
 * Register custom Twitter widgets.
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
		if ( is_active_widget( false, false, $this->id_base, true ) && ! is_admin() )
			wp_enqueue_script( 'twiget-widget-js', plugins_url( '/js/twitter.js' , __FILE__ ), array(), '', false );
			wp_enqueue_style( 'twiget-widget-css', plugins_url( '/css/twiget.css' , __FILE__ ), array(), '', false );
	}
	
	function widget( $args, $instance ){		// This function displays the widget
		extract( $args );

		// User selected settings
		global $twiget_username;
		global $twiget_tweetcount;
		global $twiget_followercount;
		global $twiget_hide_replies;
		global $twiget_twitter_newwindow;

		$twiget_title = apply_filters( 'twiget_widget_title', empty($instance['twiget_title']) ? __( 'Latest tweets', 'twiget' ) : $instance['twiget_title'], $instance, $this->id_base);	
		$twiget_username = $instance['twiget_username'];
		$twiget_tweetcount = $instance['twiget_tweetcount'];
		$twiget_followercount = $instance['twiget_followercount'];
		$twiget_hide_replies = ( array_key_exists( 'twiget_hide_replies', $instance ) ) ? $instance['twiget_hide_replies'] : false ;
		$twiget_new_window = $instance['twiget_new_window'];
		$twiget_twitter_newwindow = $twiget_new_window;
		$wrapper_id = 'tweet-wrap-' . $args['widget_id'];
		
		$twiget_follower_count_attr = ( $twiget_followercount ) ? 'data-show-count="true"' : 'data-show-count="false"';
		$hide_replies_attr = ( $twiget_hide_replies ) ? 'exclude_replies=true' : 'exclude_replies=false';
		
		echo $args['before_widget'].$args['before_title'].$twiget_title.$args['after_title'];
		?>
        	<ul id="<?php echo $wrapper_id; ?>">
            	<li><img src="<?php echo '' .plugins_url( '/images/ajax-loader.gif' , __FILE__ ). ''; ?>" width="16" height="16" alt="" /> <?php _e( 'Loading tweets...', 'twiget' ); ?></li>
            </ul>
            <p id="twigetfollow">
            	<a href="https://twitter.com/<?php echo $twiget_username; ?>" class="twitter-follow-button" <?php echo $twiget_follower_count_attr; ?> data-width="100%" data-align="right"><?php printf( __( 'Follow %s', 'twiget' ), '@' . $twiget_username ); ?></a>
			<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
            </p>
            
            <script src="http://api.twitter.com/1/statuses/user_timeline.json?screen_name=<?php echo $twiget_username; ?>&count=<?php echo $twiget_tweetcount; ?>&page=1&include_rts=true&<?php echo $hide_replies_attr; ?>&include_entities=true&callback=twigetGetTweet" type="text/javascript"></script>
            <script type="text/javascript">				
				twigetTwitter( '<?php echo $wrapper_id; ?>', 
									{
										<?php if ( $twiget_new_window ) echo 'newwindow: true,' ?>
										id: '<?php echo $twiget_username; ?>',
										count: <?php echo $twiget_tweetcount; ?>
									});
			</script>
            
            <?php do_action( 'twiget_twitter_widget' ); ?>
        <?php echo $args['after_widget']; ?>
        
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
        	<label for="<?php echo $this->get_field_id( 'twiget_hide_replies' ); ?>"><?php _e( 'Hide @replies', 'twiget' ); ?></label>
			<input id="<?php echo $this->get_field_id( 'twiget_hide_replies' ); ?>" type="checkbox" name="<?php echo $this->get_field_name( 'twiget_hide_replies' ); ?>" value="true" <?php checked( $instance['twiget_hide_replies'] ); ?> /><br />
			<span class="description"><?php $showtweetcount = $instance['twiget_tweetcount']; printf( __('Note: Selecting this sometimes result in showing less than %s tweets', 'twiget' ), $showtweetcount ); ?></span>
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

// Display a Support forum link on the main Plugins page
function twiget_plugin_action_links( $links, $file ) {

	if ( $file == plugin_basename( __FILE__ ) ) {
		$twiget_link = '<a href="http://forum.prasannasp.net/forum/plugin-support/twiget/" title="'.esc_attr__('TwiGet Twitter Widget support', 'twiget').'" target="_blank">'.__('Support', 'twiget').'</a>';

		array_unshift( $links, $twiget_link );
	}

	return $links;
}
add_filter('plugin_action_links', 'twiget_plugin_action_links', 10, 2 );

// Donate link on manage plugin page
function twiget_pluginspage_links( $links, $file ) {

$plugin = plugin_basename(__FILE__);

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
?>
