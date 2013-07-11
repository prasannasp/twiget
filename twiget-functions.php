<?php
/*
** Enqueue twiget.js
*/
function twiget_enqueue_scripts(){
	wp_enqueue_script( 'twiget-widget-js', plugins_url( 'js/twiget.js' , __FILE__ ), array( 'jquery' ), '', false );
}
add_action( 'wp_enqueue_scripts', 'twiget_enqueue_scripts' );

/*
** Register twiget.css
*/
function twiget_stylesheet() {
        wp_register_style( 'twiget-style', plugins_url( 'css/twiget.css' , __FILE__ ) );
        wp_enqueue_style( 'twiget-style' );
}
add_action( 'wp_enqueue_scripts', 'twiget_stylesheet' );

/*
** localize scripts so that the twiget.js can access plugin options
*/
function twiget_localize_scripts() {
$options = get_option('twiget_options');
$tweet_count = $options['tweet_count'];
$link_target = $options['link_target'];
$show_client = $options['show_client'];
$show_username = $options['show_username'];
$profile_pic = $options['profile_pic'];

$twiget_args = array( 	'json_url' => get_home_url( '', '?twiget=json', '' ),  //plugins_url( 'twiget-json.php' , __FILE__ ),
			'ajax_loader' => plugins_url( 'images/ajax-loader.gif' , __FILE__ ),
			'tweet_count' => $tweet_count,
			'link_target' => $link_target,
			'show_client' => $show_client,
			'show_username' => $show_username,
			'profile_pic' => $profile_pic,
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

/*
** Get connection to Twitter OAuth. Requires twitteroauth.php in lib
*/

function twiget_get_connection($cons_key, $cons_secret, $oauth_token, $oauth_token_secret) {
  require_once TWIGET_PLUGIN_PATH . 'lib/twitteroauth.php';
  $connection = new TwitterOAuth($cons_key, $cons_secret, $oauth_token, $oauth_token_secret);
  return $connection;
}

/*
** Get tweets and echo the encoded json data. Uses twiget_get_connection() function.
*/

function twiget_get_tweets_and_encode() { 

	$twiget_options = get_option('twiget_options');
 
	$twitteruser = $twiget_options['user_name']; 
	$notweets = $twiget_options['tweet_count']; 
	$consumerkey = $twiget_options['consumer_key'];
	$consumersecret = $twiget_options['consumer_secret']; 
	$accesstoken = $twiget_options['access_token']; 
	$accesstokensecret = $twiget_options['access_token_secret']; 
	
   $connection = twiget_get_connection($consumerkey, $consumersecret, $accesstoken, $accesstokensecret);
 
   $tweets = $connection->get("https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name=".$twitteruser."&include_rts=true&count=".$notweets);

	echo json_encode($tweets);
}

/*
** Tell WordPress to process "?twiget" custom URL parameter
*/
function twiget_query_vars($vars) {
    $vars[] = 'twiget';
    return $vars;
}
add_filter('query_vars', 'twiget_query_vars');

/*
** Call twiget_get_tweets_and_encode() function to return JSON data in site.url/?twiget=json
*/
function twiget_query_var_check() {
    if( get_query_var('twiget') == 'json' ) {
	twiget_get_tweets_and_encode();
    	exit;
    }
}
add_action('template_redirect', 'twiget_query_var_check');
