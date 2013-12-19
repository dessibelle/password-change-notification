=== Plugin Name ===
Contributors: chokladzingo, rtweedie
Donate link: http://dessibelle.se/
Tags: password, change, email, notification
Requires at least: 2.7
Tested up to: 3.8
Stable tag: 1.0b2

A WordPress plugin for sending users an email notification when their password changes

== Description ==
This plugin lets you send users an email with the new password whenever their password changes.

**Disclainmer**: It should be noted that sending passwords in cleartext is [VERY MUCH ADVISED AGAINST](http://security.stackexchange.com/questions/17979/is-sending-password-to-user-email-secure), and you should <s>probably not</s> **never ever** be using this plugin in production envorinment.

== Changelog ==

= 1.0b2 =
* Added option to user profile to indicate wether or not to send notification email
* General bugfixes and improvements

= 1.0b1 =
* Initial Release

== Filters ==

All filters include a default value and the user id.

* `pwcn_to_name` - Email To: header name
* `pwcn_to_email` - Email To: header email address
* `pwcn_to_header` - Email To: header
* `pwcn_site_url` - Site URL used in notification email
* `pwcn_login_url` - Site login URL used in notification email
* `pwcn_login_url_redirect` - Login redirect
* `pwcn_site_name` - Site name used in notification email
* `pwcn_site_hostname` - Site hostname used in notification email
* `pwcn_subject` - Subject line of notification email
* `pwcn_message` - Message sent in notification email
* `pwcn_notification_default_value` - Wether or not to send notification emails for a given user
