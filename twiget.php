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
*/

function load_twiget_plugin_textdomain() {
  load_plugin_textdomain( 'twiget', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}
add_action('plugins_loaded', 'load_twiget_plugin_textdomain');

define( 'TWIGET_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
require_once TWIGET_PLUGIN_PATH . 'twiget-functions.php';
require_once TWIGET_PLUGIN_PATH . 'twiget-options-form.php';

/**
 * Register custom Twitter widgets.
*/

class Twiget_Twitter_Widget extends WP_Widget{
	
	function Twiget_Twitter_Widget(){
		// Widget settings
		$widget_ops = array( 'classname' => 'twiget-widget', 'description' => __( 'Display the latest Twitter status updates.', 'twiget' ) );
		
		// Widget control settings
		$control_ops = array( 'id_base' => 'twiget-widget' );
		
		// Create the widget
		$this->WP_Widget( 'twiget-widget', 'TwiGet Twitter Widget', $widget_ops, $control_ops);
			
	}
	
	function widget( $args, $instance ){		// This function displays the widget
		extract( $args );
	
		$twiget_options = get_option('twiget_options');

		$twiget_title = apply_filters( 'twiget_widget_title', empty($instance['twiget_title']) ? __( 'Latest tweets', 'twiget' ) : $instance['twiget_title'], $instance, $this->id_base);	
		
		$wrapper_id = 'tweet-wrap-' . $args['widget_id'];
		
		$twiget_follower_count_attr = (isset($twiget_options['followers_count']) == 1) ? 'data-show-count="true"' : 'data-show-count="false"';

		echo $args['before_widget'].$args['before_title'].$twiget_title.$args['after_title'];
		?>
        	<div id="twiget-feed"></div>
        	 <p id="twigetfollow">
<a href="https://twitter.com/<?php echo $twiget_username; ?>" class="twitter-follow-button" <?php echo $twiget_follower_count_attr; ?> data-width="100%" data-align="right"><?php printf( __( 'Follow %s', 'twiget' ), '@' . $twiget_options['user_name'] ); ?></a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
</p>
            <?php do_action( 'twiget_twitter_widget' ); ?>
        <?php echo $args['after_widget']; ?>
        
        <?php
	}
	
	function update( $new_instance, $old_instance ){	// This function processes and updates the settings
		$instance = $old_instance;
		
		// Strip tags (if needed) and update the widget settings
		$instance['twiget_title'] = strip_tags( $new_instance['twiget_title'] );
	
		return $instance;
	}
	
	function form( $instance ){		// This function sets up the settings form
		
		// Set up default widget settings
		$defaults = array( 'twiget_title' => __( 'Latest tweets', 'twiget' ) );
		$instance = wp_parse_args( (array) $instance, $defaults );
		?>
        <p>
        	<label for="<?php echo $this->get_field_id( 'twiget_title' ); ?>"><?php _e( 'Widget Title:', 'twiget' ); ?></label>
			<input id="<?php echo $this->get_field_id( 'twiget_title' ); ?>" type="text" name="<?php echo $this->get_field_name( 'twiget_title' ); ?>" value="<?php echo $instance['twiget_title']; ?>" class="widefat" />
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
** Thanks David Gwyer for Plugin Options Starter Kit plugin!
*/

// Delete options table entries ONLY when plugin deactivated AND deleted
function twiget_delete_plugin_options() {
	delete_option('twiget_options');
}
register_uninstall_hook(__FILE__, 'twiget_delete_plugin_options');

// Define default option settings
function twiget_add_defaults() {
	$tmp = get_option('twiget_options');
    if(($tmp['twiget_default_options_db']=='1')||(!is_array($tmp))) {
		delete_option('twiget_options');
		$arr = array(	"link_target" => "1",
				"show_client" => "1",
				"twiget_default_options_db" => ""
		);
		update_option('twiget_options', $arr);
	}
}
register_activation_hook(__FILE__, 'twiget_add_defaults');

// Init plugin options to white list our options
function twiget_init() {
	register_setting( 'twiget_plugin_options', 'twiget_options', 'twiget_validate_options' );
}
add_action('admin_init', 'twiget_init' );

// Add menu page
function twiget_add_options_page() {
	add_options_page('Twiget Twitter Plugin Settings', 'Twiget Settings', 'manage_options', __FILE__, 'twiget_render_form');
}
add_action('admin_menu', 'twiget_add_options_page');

// Sanitize and validate input. Accepts an array, return a sanitized array.
function twiget_validate_options($input) {
	 // strip html from textboxes
	$input['user_name'] =  wp_filter_nohtml_kses($input['user_name']); // Sanitize textbox input (strip html tags, and escape characters)
	$input['tweet_count'] =  wp_filter_nohtml_kses($input['tweet_count']);
	$input['consumer_key'] =  wp_filter_nohtml_kses($input['consumer_key']);
	$input['consumer_secret'] =  wp_filter_nohtml_kses($input['consumer_secret']);
	$input['access_token'] =  wp_filter_nohtml_kses($input['access_token']);
	$input['access_token_secret'] =  wp_filter_nohtml_kses($input['access_token_secret']);
	return $input;
}

// Donate link on manage plugin page
function twiget_pluginspage_links( $links, $file ) {

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

// Display a Support forum link on the main Plugins page
function twiget_plugin_action_links( $links, $file ) {

	if ( $file == plugin_basename( __FILE__ ) ) {
		$twiget_link1 = '<a href="http://forum.prasannasp.net/forum/plugin-support/twiget/" title="'.esc_attr__('TwiGet Twitter Widget support', 'twiget').'" target="_blank">'.__('Support', 'twiget').'</a>';
		$twiget_link2 = '<a href="'.get_admin_url().'options-general.php?page=twiget/twiget.php">'.__('Settings', 'twiget').'</a>';

		array_unshift( $links, $twiget_link1, $twiget_link2 );
	}

	return $links;
}
add_filter('plugin_action_links', 'twiget_plugin_action_links', 10, 2 );
