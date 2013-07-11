jQuery(document).ready(function($) {
    
    var showdirecttweets = false;
    var showretweets = true;
    var loadingHTML = '';
    
    loadingHTML += '<div id="twiget-ajax-loading"><img src="'+TwigetArgs.ajax_loader+'" width="16" height="16" /> Loading tweets..</div>';
	
	$('#twiget-feed').html(loadingHTML);

    $.getJSON(TwigetArgs.json_url, 
        function(feeds) {

            var feedHTML = '';
            var displayCounter = 1;
            for (var i=0; i<feeds.length; i++) {
                var tweetscreenname = feeds[i].user.name;
                var tweetusername = feeds[i].user.screen_name;
                var profileimage = feeds[i].user.profile_image_url_https;
                var status = feeds[i].text;
                var isaretweet = false;
                var isdirect = false;
                var tweetid = feeds[i].id_str;
                var tweetsource = feeds[i].source;
 
                //If the tweet has been retweeted, get the profile pic of the tweeter
                if(typeof feeds[i].retweeted_status != 'undefined'){
                   profileimage = feeds[i].retweeted_status.user.profile_image_url_https;
                   tweetscreenname = feeds[i].retweeted_status.user.name;
                   tweetusername = feeds[i].retweeted_status.user.screen_name;
                   tweetid = feeds[i].retweeted_status.id_str
                   isaretweet = true;
                 };
 
                 //Check to see if the tweet is a direct message
                 if (feeds[i].text.substr(0,1) == "@") {
                     isdirect = true;
                 }
 
                //console.log(feeds[i]);
 
                 if (((showretweets == true) || ((isaretweet == false) && (showretweets == false))) && ((showdirecttweets == true) || ((showdirecttweets == false) && (isdirect == false)))) {
                    if ((feeds[i].text.length > 1) && (displayCounter <= TwigetArgs.tweet_count)) {

                            status = addlinks(status);
 
                        feedHTML += '<div class="twiget-article">';
                        
                        if ( TwigetArgs.profile_pic == 1 ) {
                        
                        feedHTML += '<span class="twiget-pic"><a href="https://twitter.com/'+tweetusername+'" ><img src="'+profileimage+'"images/twitter-feed-icon.png" width="32" height="32" alt="twitter icon" /></a></span>';
                        }
                       
                        if ( TwigetArgs.show_username == 1 ) {
                        
                        feedHTML += '<span class="twiget-profile-link"><a href="https://twitter.com/'+tweetusername+'" >@'+tweetusername+'</a></span>';
                        }
                                                
                        feedHTML += '<div class="twiget-text">';
                        feedHTML += '<span class="twiget-status">'+status+'</span>';
                        feedHTML += '<span class="twiget-time"><a href="https://twitter.com/'+tweetusername+'/status/'+tweetid+'">'+relative_time(feeds[i].created_at)+'</a> </span>';
                        if ( TwigetArgs.show_client) {
                        feedHTML += '<span class="twiget-client">&nbsp;via '+tweetsource+'</span>';
                        }
                        feedHTML += '</div>';
                       
                        feedHTML += '</div>';
                        displayCounter++;
                    }
                 }
            }
 
            $('#twiget-feed').html(feedHTML);
    });
 
    //Function modified from Stack Overflow
    function addlinks(data) {
        //Add link to all http:// links within tweets
        data = data.replace(/((https?|s?ftp|ssh)\:\/\/[^"\s\<\>]*[^.,;'">\:\s\<\>\)\]\!])/g, function(url) {
            return '<a href="'+url+'" >'+url+'</a>';
        });
 
        //Add link to @usernames used within tweets
        data = data.replace(/\B@([_a-z0-9]+)/ig, function(reply) {
            return '<a href="http://twitter.com/'+reply.substring(1)+'" style="font-weight:lighter;" >'+reply.charAt(0)+reply.substring(1)+'</a>';
        });
        
        // Linkify hashtags
        data = data.replace(/(^|[^&\w'"]+)\#([a-zA-Z0-9_^"^<]+)/g, function(m, m1, m2) {
            return m.substr(-1) === '"' || m.substr(-1) == '<' ? m : m1 + '<strong>#<a href="http://twitter.com/#!/search/%23' + m2 + '">' + m2 + '</a></strong>';
        });
        
        // Link in new window
        if ( TwigetArgs.link_target == 1 ) {
        data = data.replace( /<a href=/gi, '<a target="_blank" href=' );
        }
        
        return data;
        
    }
 
    function relative_time(time_value) {
      var values = time_value.split(" ");
      time_value = values[1] + " " + values[2] + ", " + values[5] + " " + values[3];
      var parsed_date = Date.parse(time_value);
      var relative_to = (arguments.length > 1) ? arguments[1] : new Date();
      var delta = parseInt((relative_to.getTime() - parsed_date) / 1000);
      var shortdate = time_value.substr(4,2) + " " + time_value.substr(0,3);
      delta = delta + (relative_to.getTimezoneOffset() * 60);
 
      if (delta < 60) {
        return 'less than a minute ago';
      } else if(delta < 120) {
        return 'about a minute ago';
      } else if(delta < (60*60)) {
        return (parseInt(delta / 60)).toString() + ' minutes ago';
      } else if(delta < (120*60)) {
        return 'about an hour ago';
      } else if(delta < (24*60*60)) {
        return 'about ' + (parseInt(delta / 3600)).toString() + ' hours ago';
      } else if(delta < (48*60*60)) {
        return '1 day ago';
      } else {
        return (parseInt(delta / 86400)).toString() + ' days ago';
      }
    }
 
});
