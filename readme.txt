=== TwiGet Twitter Widget ===
Contributors: prasannasp, silverks
Donate link: http://www.prasannasp.net/donate/
Tags: twitter, widget, tweets, twitter widget, sidebar
Requires at least: 2.5
Tested up to: 3.5
Stable tag: 1.0
License: GPLv3
License URI: http://www.gnu.org/copyleft/gpl.html

A widget to display the latest Twitter status updates.

== Description ==

TwiGet Twitter Widget lets you display your latest twitter status updates in any of the widgetized areas. Just add the TwiGet Twitter Widget to your sidebar or any of the widget areas and enter your twitter username. It will display your latest tweets along with a **follow @username** button to make it easier for people to follow you! You can configure widget title, twitter username, number of tweets to display, link target etc, in the widget configuration.

Features:

* Option to change widget title
* Option to change number of tweets to display
* Option to show followers count
* Option to open links in a new window
* Option to hide @replies
* Linkified @usernames
* Linkified #hashtags

TwiGet is a clone of the Twitter Widget in the [Graphene Theme](http://wordpress.org/extend/themes/graphene/) developed by [Syahir Hakim](http://www.khairul-syahir.com/). The plugin is developed by [Prasanna SP](http://www.prasannasp.net/).

**Translation**: If you want to translate this plugin to your language, please find the twiget.pot file in /languages/ folder.

**Demo**: See demo of this plugin [here](http://demo.prasannasp.net/). It is the second item in the sidebar.

**Support**: Please post your support questions at TwiGet Twitter Widget plugin [support forum](http://forum.prasannasp.net/forum/plugin-support/twiget/).

Visit [this page](http://www.prasannasp.net/wordpress-plugins/) for more **WordPress Plugins** by the developer.

== Installation ==

1. Upload `twiget` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Go to Appearance --> Widgets and add TwiGet Twitter Widget to any of the widgetized areas

== Frequently asked questions ==

= Where can I get help from? =

Post your questions or report issue in the [support forum](http://forum.prasannasp.net/forum/plugin-support/twiget/). You can directly contact the developer using this [contact form](http://www.prasannasp.net/contact/).

= How to style the widget? =

The widget is bundled with a stylesheet. If you want to use your own styles, dequeue `twiget-widget-css` and then style it using `.twiget-widget` css class. The follow @ button is wrapped in twigetfollow ID. So, use `#twigetfollow` in your Custom CSS to style it.

Dequeuing bundled stylesheet:

`wp_dequeue_style( 'twiget-widget-css' );`

== Screenshots ==

1. TwiGet Twitter Widget configuration
2. TwiGet Twitter Widget in twentytwelve theme
3. TwiGet Twitter Widget in Graphene Theme

== Changelog ==

= 1.0 =

* Initial public release.

== Upgrade notice ==

* No Upgrade Notice so far.
