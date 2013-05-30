=== Plugin Name ===
Contributors: Adam Losier
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=DPNGNWR3BVAGS
Tags: open graph, social graph, facebook open graph, open graph protocol, facebook open graph protocol, social graph protocol, facebook, open graph meta, open graph meta data
Requires at least: 3.3
Tested up to: 3.5.1
Stable tag: 2.0.4/social-graph-protocol

The Social Graph Protocol plugin developed by CodeHooks enables you to integrate your websites content into the Facebook Open Graph.

== Description ==

The Social Graph Protocol plugin developed by [CodeHooks](http://www.codehooks.com "CodeHooks") is a quick and easy way for you to integrate all of your websites content into the open graph developed by Facebook.
The plugin can be used as simple as activating it and forgetting it or it can be used for more adavnced purposes such as adding Video, Audio, and Location data to specific posts that you write.
Additionally the plugin supports custom post types so that you can fully integrate the social graph in all aspects of your website.

This plugin also enables you to integrate Facebook insights data with your website and provides you with very detailed information on how to do it, along with very detailed information related
to how the open graph works and the benefits of using the open graph.

Languages Supported:

1. English

== Installation ==

Installation is made as simple as possible just follow the steps below.

1. Jump into your WordPress admin panel and navigate to the Plugins menu.

2. Click the "Add New" button near the top of that page.

3. Do a search for "Social Graph Protocol" and find the one by CodeHooks and/or Adam Losier

4. Press the install now button.

5. If the plugin was not activated (it should be) go into your Wordpress administration panel, click Plugins and then activate when you see this plugin in the list.

== Frequently Asked Questions ==

Please refer to the post [Social Graph Protocol Plugin For Wordpress](http://www.codehooks.com/social-graph-protocol-version-2-0-0-documentation/ "Social Graph Protocol Plugin For Wordpress") on the [CodeHooks](http://www.codehooks.com/ "CodeHooks") website.
Feel free to direct all of your questions in the comments section of that page or in the Wordpress support forums. Thank You.

== Screenshots ==

1. Facebook Insights
2. Plugin Documentation
3. Social Graph Protocol Settings
4. Facebook Insights Setup
5. Post/Page Custom Meta Box For The Social Graph Plugin

== Changelog ==

= 2.0.4 =
* Properly fixed an error some would get using the Facebook Debug tool when dealing with certain unsupported locale's

= 2.0.3 =
* Temporary hot-fix was introduced to prevent certain Wordpress locale's from causing an error in the Facebook debugger. I'll be pushing out a proper fix for this soon.

= 2.0.2 =
* Fixed a bug that would cause an error message related to FB Admins tag when using the Facebook debugger tool

= 2.0.1 =
* Hotfix that should take care of php warning message "in_array() [function.in-array]: Wrong datatype for second argument in /public_html/wp-content/plugins/social-graph-protocol/index.php on line 318" for WPMU users.

= 2.0.0 =
* Pretty much re-wrote the entire plugin
* All posts now created should not return any errors in the [Facebook debugging tool](https://developers.facebook.com/tools/debug "Facebook Debugging Tool")

= 1.2.2 =
* Removed the duplication of og meta tags when using this plugin along side the WP Facebook plugin [Facebook Plugin](http://wordpress.org/extend/plugins/facebook/)
* Fixed a bug where the user might wind up with a php error while entering empty values in the general settings after updating to version 1.2.1

= 1.2.1 =
* Updated the plugins contextual help to be a little more clear
* Cleaned up the source code and made quite a few improvements (Adpated to the Settings API)
* Started to re-add language support, plugin now supports six different languages (English, French, Arabic, Spanish, Hindi, and Portuguese)
* Updated the screenshots for the plugin

= 1.2.0 =
* Updated Contextual help for Wordpress 3.3 which means its incompatible with wordpress versions prior to 3.3.
* Removed language translations but will continue to work on getting the languages back after I write better help docs, only language supported for the time being is English.
* Fixed some post titles not being included on the post pages
* Added collapsable and expandable menus on post and edit pages to minimize things a bit
* Please let me know if you have any suggestions or see anything that needs to be fixed. (Email: adam@codehooks.com)

= 1.1.3 =
* During the last SVN update I made a naming mistake on the folder, this should fix the issue with that upadate sorry for the error.

= 1.1.2 =
* Fixed a bug where the admins tag was showing up as og:admins and not fb:admins, this will fix the "No admin data found at root webpage" Facebook insights error you might of seen.

= 1.1.1 =
* Fixed a bug where it would toss a fatal error when if post thumbnails are not enabled in the theme, special thanks to [adrian7](http://wordpress.org/support/profile/adrian7) for picking up on this.
* Now supports 12 different languages instead of 9

= 1.1.0 =
* Fixed a bug where the og:image tag would display the full html image instead of just the src
* Began to localize the plugin, now supports 9 different languages
* Fixed a few confusing naming issues

= 1.0.2 =
* You no longer need to remember your custom post type slugs, plugin will now auto-detect them

= 1.0.1 =
* Fixed a naming bug that caused the plug-ins includes to fail on the settings and post/page edit screens

= 1.0.0 =
* No changes since this is the first release

== Upgrade Notice ==

= 1.2.0 =
* Updated Contextual help for Wordpress 3.3 which means its incompatible with wordpress versions prior to 3.3.
* Removed language translations but will continue to work on getting the languages back after I write better help docs, only language supported for the time being is English.
* Fixed some post titles not being included on the post pages
* Added collapsable and expandable menus on post and edit pages to minimize things a bit
* Please let me know if you have any suggestions or see anything that needs to be fixed. (Email: adam@bizzylabs.com)

= 1.1.3 =
* During the last SVN update I made a naming mistake on the folder, this should fix the issue with that upadate sorry for the error.

= 1.1.2 =
* Fixed a bug where the admins tag was showing up as og:admins and not fb:admins, this will fix the "No admin data found at root webpage" Facebook insights error you might of seen.

= 1.1.1 =
* Fixed a bug where it would toss a fatal error when if post thumbnails are not enabled in the theme, special thanks to [adrian7](http://wordpress.org/support/profile/adrian7) for picking up on this.
* Now supports 12 different languages instead of 9

= 1.1.0 =
* Added language support for Spanish, French, and Puerto Rican as well as fixed a og:image bug

= 1.0.2 =
* In this release you no longer need to remember all your custom post type slugs, the plugin will automatically detect them and add the meta boxes.

= 1.0.1 =
* I forgot when I renamed the plugin to change over some includes this caused version 1.0.0 to fail, didn't notice it right away but it should be working fine now.

= 1.0.0 =
* The Open Graph Protocol plugin provides you with a lot more flexability over the other related plugins.