<?php
/**
 * Authenticate to Twitter using oAuth, and retrieve tweets
 */
session_start();
function twiget_get_tweets_and_encode() {
	require_once( 'lib/twitteroauth.php' );

	$twiget_options = get_option('twiget_options');
 
	$consumerkey = $twiget_options['consumer_key'];
	$consumersecret = $twiget_options['consumer_secret']; 
	$accesstoken = $twiget_options['access_token']; 
	$accesstokensecret = $twiget_options['access_token_secret'];

	$defaults = array(
				'user_id'				=> NULL,
				'screen_name'			=> NULL,
				'since_id'				=> NULL,
				'count'					=> 5,
				'max_id'				=> NULL,
				'trim_user'				=> NULL,
				'exclude_replies'		=> NULL,
				'include_rts'			=> NULL,
				'contributor_details'	=> NULL
			);
	$options = array_intersect_key( array_merge( $defaults, $_GET ), $defaults );

	$connection = new TwitterOAuth( $consumerkey, $consumersecret, $accesstoken, $accesstokensecret ); 
	$tweets = $connection->get( 'https://api.twitter.com/1.1/statuses/user_timeline.json?' . http_build_query( $options ) );
 
	echo json_encode( $tweets );
}
