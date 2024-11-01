=== WP Forum ===
Contributors: fahlstad
Donate link: http://fahlstad.se/
Tags: forum, disscusion
Requires at least: 2.0.2
Tested up to: 2.1
Stable tag: 1.7.8

Forum plugin for Wordpress.

== Description ==

Simple discussion forum plugin for WordPress. With support for different skins, 3 included by default, changeable from the WP admin interface. Admin can choose if unregistered posting is allowed and Captcha (optional) is used for spam control. Tight interaction with Wordpress  makes an easy to use and aminister plugin.

== Installation ==

1. Rename wpforum to wp-forum and copy it to to wp-content/plugins
1. Go and activate the plugin
1. Create a page from the Manage tab
1. Click on the HTML button in Wordpress and then insert: `<!--WPFORUM-->`
1. Go to manage->wp-forum and start adding groups and forums.
1. You must then visit the WP-Forum options panel in WP admin.
1. Setup a link to your forum (unless you have your pages auto-linking in the navagation menu)
1. To show the latest acitvity in the sidebar add this code: `<?php forum_latest_acivity(numbers_to_show);?>` where numbers_to_show is an actual number like 1,2,3
1. If you upload a new version go to plugin managment and deactivate and the activate WP-Forum. Visit the structure page of the wp-forum managment.


== Screenshots ==

1. screenshot-1.png

== Arbitrary section ==