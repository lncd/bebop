<?php

/**
 * Post update to Twitter
 */

function bebop_twitter_post_update($content = "", $shortLink = "", $user_id = 0) {
    
    global $bp;
    
    //handle oauth calls
    $buddystreamOAuth = new BuddyStreamOAuth();
    $buddystreamOAuth->setRequestTokenUrl('http://api.twitter.com/oauth/request_token');
    $buddystreamOAuth->setAccessTokenUrl('http://api.twitter.com/oauth/access_token');
    $buddystreamOAuth->setAuthorizeUrl('https://api.twitter.com/oauth/authorize');
    $buddystreamOAuth->setCallbackUrl($bp->root_domain);
    $buddystreamOAuth->setConsumerKey(get_site_option("tweetstream_consumer_key"));
    $buddystreamOAuth->setConsumerSecret(get_site_option("tweetstream_consumer_secret"));
    $buddystreamOAuth->setAccessToken(get_user_meta($bp->loggedin_user->id,'tweetstream_token', 1));
    $buddystreamOAuth->setAccessTokenSecret(get_user_meta($bp->loggedin_user->id,'tweetstream_tokensecret', 1));
    $buddystreamOAuth->setRequestType('POST');
    $buddystreamOAuth->setParameters(array('status' => BuddyStreamFilters::filterPostContent($content, $shortLink, 140)));
    $buddystreamOAuth->oAuthRequest('https://api.twitter.com/1/statuses/update.json');  
}

//called from  menu item creation on bebop_page_loader.php
function bebop_twitter() {
    bebop_extensions::page_loader('twitter');
}