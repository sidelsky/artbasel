=== Ajax Load More: WooCommerce ===

Contributors: dcooney, connekthq
Author: Connekt Media
Author URI: https://connekthq.com/
Plugin URI: https://connekthq.com/plugins/ajax-load-more/add-ons/woocommerce/
Requires at least: 4.0
Tested up to: 5.5.0
Stable tag: trunk
Homepage: https://connekthq.com/
Version: 1.2.0

== Copyright ==
Copyright 2021 Connekt Media

This software is NOT to be distributed, but can be INCLUDED in WP themes: Premium or Contracted.
This software is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.

== Description ==

The WooCommerce add-on automatically integrates infinite scrolling into your existing shop templates.

http://connekthq.com/plugins/ajax-load-more/add-ons/woocommerce/

== Installation ==

= Uploading in WordPress Dashboard =

1. Navigate to the 'Add New' in the plugins dashboard
2. Navigate to the 'Upload' area
3. Select `ajax-load-more-woocommerce.zip` from your computer
4. Click 'Install Now'
5. Activate the plugin in the Plugin dashboard

= Using FTP =

1. Download `ajax-load-more-woocommerce.zip`.
2. Extract the `ajax-load-more-woocommerce` directory to your computer.
3. Upload the `ajax-load-more-woocommerce` directory to the `/wp-content/plugins/` directory.
4. Ensure Ajax Load More is installed prior to activating the plugin.
5. Activate the plugin in the WP plugin dashboard.

== Changelog ==

= 1.2.0 - July 8, 2021 =
* UPGRADE NOTICE - Users are required to update core Ajax Load More to v5.5 or greater when updating this add-on.
* NEW - Added support for loading products in a reverse load more using a Load Previous button for users who land on a paged URL. e.g. website.com/shop/page/4
* FIX - Fixed issue with Light Grey loading style not working.


= 1.1.1 - April 20, 2021 =
* NEW - Added new Customizer setting and filter (`alm_woocommerce_permalink_structure`) that provides a method to change the URL permalink structure.
This is helpful for when a site or plugin (Divi, Elementor) uses a paging permalink structure outside of the default WooCommerce `/page/3/` structure.
	add_filter('alm_woocommerce_permalink_structure', function() {
		return '?product-page={page}';
	});

= 1.1.0.1 - February 16, 2021 =
* HOTFIX - Fix for potential issues with trailing commas causing fatal errors on servers PHP 7.2.x and lower.


= 1.1.0 - February 11, 2021 =
* NEW - Added new customizer options for setting the WooCommerce container and product classes. Previously, these values needed to be entered using custom [filter hooks](https://connekthq.com/plugins/ajax-load-more/docs/add-ons/woocommerce/#filter-hooks).
* NEW - Adding console warnings for when ALM cannot find WooCommerce containers.
* UPDATE - Various updates to support PHP 8.0+.


= 1.0.2 - September 10, 2020 =

**UPDATE NOTICE**
Users upgrading to WooCommerce add-on version 1.0.2 must also update core Ajax Load More to `5.3.8`. Failure to update will result is broken functionality.

-  NEW - Added support for [WOOF Filters](https://wordpress.org/plugins/woocommerce-products-filter/) and [Advanced AJAX Product Filters](https://wordpress.org/plugins/woocommerce-ajax-filters/) via Ajax Load More `reset` method.
-  UPDATE - Updated the method ALM sets global WooCommerce configuration data. Previously, data was set in a window scoped JS variable. I've since moved this logic into JSON styled data atributes to allow for dynamic re-rendering of ALM during a filter.
-  UPDATE - Adding WooCommerce `WC requires` and `WC tested up to` meta parameters.
-  UPDATE - Updated the `alm_woocommerce_pagination_class` filter to remove the `.` before the classname.
-  UPDATE - Updated WooCommerce JavaScript to improve performance.

= 1.0.1 - May 29, 2020 =

-  NEW - Added support for Ajax Load More [Cache add-on](https://connekthq.com/plugins/ajax-load-more/add-ons/cache/). When cache is enabled, a new customizer settings will be created for both the main Shop page and Shop archives allowing.

= 1.0 - April 22, 2020 =

-  Initial Release.
