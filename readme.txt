=== a3 Lazy Load ===

Contributors: a3rev, A3 Revolution Software Development team
Tags: a3 lazy load, Lazy Loading , image lazy load, lazyload
Requires at least: 4.0
Tested up to: 4.1.0
Stable tag: 1.0.2
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Use a3 Lazy Load to instantly improve your sites load time while dramatically improving site visitors user experience.

== Description ==

Inspired by Facebook lazy Load UI - a3 lazy Load is a Mobile Oriented, easily customizable plugin that will speed up site performance, the more content heavy your site the better the plugin will perform and the more you will see the improvements in performance. a3 Lazy Load is inspired by and powered by the ressio [Lazy-Load-xt JavaScript](https://github.com/ressio/lazy-load-xt).

a3 Lazy load reduces the number of http calls when a page loads by only loading elements on a page that are visible in the users browser. As the user scroll down the page the next lot of elements are loaded as they become visible in the view port.

= Easy Configuration =

From the admin panel use ON | OFF switches to activate or deactivate lazy load on these elements. - See Screenshot

* Lazy load images
* Apply to content
* Apply to text widgets
* Apply to post thumbnails
* Apply to gravatars

= Lazy Load Effects =

a3 lazy Load gives you the option to lazy load images with a FADE IN or SPINNER effect.

* FADEIN [see demo](http://ressio.github.io/lazy-load-xt/demo/fadein.htm)
* SPINNER [see demo](http://ressio.github.io/lazy-load-xt/demo/spinner.htm)

= WooCommerce =

a3 lazy Load is built and tested to be fully compatible with the WooCommerce plugin.

= Asset Load =

a3 Lazy Load gives you the option to load its script from your sites HEAD or from the FOOT.

* Note that your theme must have the wp_footer() function if you select FOOTER load.
* Note the plugin CSS is always loaded from the HEAD.

= Exempt Images =

a3 Lazy Load allows you to easily exempt any image from having the Lazy Load effect applied.

= Theme Compatibility =

* Work with any WordPress theme that follows the WordPress Theme Codex.
* Fully compatible with WPTouch plugin - Set to not apply on Mobiles if WPTouch is installed.
* Fully compatible with MobilePress plugin - Set to not apply on Mobiles if MobilePress is installed.

= Plugin Compatibility =

* Will not conflict with any plugin that has lazy load built in.
* Plugin Developers a3 lazy load filter allows them to let lazy load apply to their plugin.
* Tested 100% compatible with WP Super Cache and W3 Total Cache plugins.
* Tested 100% compatible with Amazon Cloudfront.
* Fully compatible with CDN architecture.

= Full Browser Support =

a3 lazy Load is tested 100% compatible with all major browsers.

= PC's =
* IE 6-11
* Chrome 1-39 (current)
* Firefox 1.5-33 (current)
* Safari 3-8 (current)
* Opera 10.6-25 (current)

= Mobile =
* iOS 5-8 (current)
* Android 2.3-4.4 (current)
* Amazon Kindle Fire 4-4.3 (Current)
* Windows Mobile 8.1 (Current)

= More Features =

* Full support for responsive images.
* Full support of jQueryMobile framework
* WordPress Multi site ready.
* Backend support for RTL display.
* Translation ready


== Installation ==

= Minimum Requirements =

* WordPress 4.0
* PHP version 5.2.4 or greater
* MySQL version 5.1.73 or greater

= Automatic installation =

Automatic installation is the easiest option as WordPress handles the file transfers itself and you don't even need to leave your web browser. To do an automatic install of a3 Lazy Load, log in to your WordPress admin panel, navigate to the Plugins menu and click Add New. Search a3 Lazy load, select and install.

== Screenshots ==

1. Admin Dashboard.


== Usage ==

1. Install and activate the plugin

2. On wp-admin a3 Lazy Laod one page admin panel check the settings.

3. Enjoy


== Changelog ==

= 1.0.2 - 2015/01/20 =
* Tweak - Include wp_deregister_script( 'jquery-lazyloadxt' ) before wp_enqueue_script( 'jquery-lazyloadxt' ) for compatibility with a3 Portfolio plugin which supports video slides
* Dev - Add new parameter $noscript boolen type for 'a3_lazy_load_html' and 'a3_lazy_load_images' filters to exclude or include noscript from content return of 3rd party plugins.
* Dev - Added default exclude class 'a3-notlazy' so developers can easily apply it to images that they don't want Lazy Load to apply to.

= 1.0.1 - 2014/12/23 =
* Tweak - Applied lazy load for 'wp_get_attachment_image_attributes' filter tag for WordPress default [gallery] shortcode
* Tweak - Added link to a3 Lazy Load wordpress.org support forum on plugins description that show on plugins menu
* Dev - Defined 'a3_lazy_load_enable' function when Enable Lazy Load is set to ON. Plugin and Theme developers can use the function to edit their image attribute so that a3 lazy load script can apply to the images.
* Dev - Defined 'a3_lazy_load_skip_classes' filter tag. Developers can add another class css name for images that they don't want lazy load applied too.
* Fix - Lazy Load does not apply to images added by shortcode, excluding the WordPress default [gallery] shortcode

= 1.0.0 - 2014/12/20 =
* First working release


== Update Notice ==

= 1.0.2 =
Upgrade now for an a3 Portfolio plugin compatibility code tweak and a new Dev parameter and exclude class.

= 1.0.1 =
Upgrade your plugin now for apply lazy load to shortcodes fix plus plus a new developer function and class.
