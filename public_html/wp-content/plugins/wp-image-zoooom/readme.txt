=== WP Image Zoom ===
Created: 21/11/2015
Contributors: diana_burduja
Email: diana@burduja.eu
Tags: e-commerce, featured image, hover over image zoom, image, image zoom, image zoom plugin, image magnification, image magnifier, jquery picture zoom, magnifier, magnify image, magnifying glass, mouse over image zoom, panorama, picture zoom, product image, product zoom, product magnification, product magnifier, responsive, woocommerce product zoom, woocommerce zoom, woocommerce  magnifying glass, zoom, zoom image, zoom plugin, woocommerce image zoom, woocommerce product image zoom, woocommerce zoom magnifier
Requires at least: 3.0.1
Tested up to: 5.8 
Stable tag: 1.47.1
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html
Requires PHP: 5.2.4

Awesome image zoom plugin for images in posts/pages and for WooCommerce products.

== Description ==

= Awesome image zoom for images in posts/pages and for WooCommerce products =

WP Image Zoom is a robust, modern and very configurable image zoom plugin. It allows you to easily create a magnifying glass on your images, all from a very intuitive WP admin interface.

Your visitors will be able to see the beautiful details of your images. This will improve your users' experience and hopefully also your revenue.

[youtube https://www.youtube.com/watch?v=JSkcItXaZK4] 

= Features =

* **4 Zooming Types** - Inner zoom, Round lens, Square lens and outer zoom (with Zoom Window).
* **Animation Easing Effect** - the zooming lense will follow the mouse over the image with a sleak delay. This will add a touch of elegance to the zooming experience.
* **Fade Effect** - the zoomed part will gracefully fade in or fade out.
* **Extremely configurable** - control zooming lens size, border color, border size, shadow, rounded corner, and others ...
* **Works with WooCommerce** - easily enable the zoom on all your products' images. Only a checkbox away.
* **Works in Pages and Posts** - within the post's/page's editor you'll find a button for applying the zooming effect on any image.

= Using the plugin with a page bulider =
For applying the zoom on an image on a page/post from within a page builder, you need to add the "zoooom" CSS class to the image. Here are screenshots on how to do this with the most popular page builders:
* Gutenberg - [screenshot](https://www.silkypress.com/wp-content/uploads/2018/10/zoom-gutenberg.png)
* WPBakery - depending on the page builder's version: 1) [screenshot](https://www.silkypress.com/wp-content/uploads/2017/05/image-zoom-js_composer.png) with the "large" or "full" for the Image Size setting. Or 2) [screenshot](https://www.silkypress.com/wp-content/uploads/2019/06/wpbakery-zoooom.png).
* Page Builder by SiteOrigin - [screenshot](https://www.silkypress.com/wp-content/uploads/2020/04/site-origin-zoooom.png)
* Elementor Page Builder - [screenshot](https://www.silkypress.com/wp-content/uploads/2016/09/image-zoom-elementor.png). It works with all the Image Size options, except Custom.
* Beaver Builder - [screenshot](https://www.silkypress.com/wp-content/uploads/2020/04/beaver-builder-zoooom.png)
* Divi Builder - [screenshot](https://www.silkypress.com/wp-content/uploads/2016/09/divi-builder.png) (used by the Divi theme)
* Avia Layout Builder - [screenshot](https://www.silkypress.com/wp-content/uploads/2019/04/enfold-apply-zoooom.png) (used by the Enfold theme)
* Fusion Page Builder - [screenshot](https://www.silkypress.com/wp-content/uploads/2021/06/fusion-zoooom.png)
* Brizy Page Builder - [screenshot](https://www.silkypress.com/wp-content/uploads/2019/01/zoom-brizy.png)
* Tatsu Page Builder - [screencast](https://www.dropbox.com/h?preview=tatsu-builder-zoom.flv)
The zoom works alright only with Image elements. Unfortunately, trying to apply the zoom on an image gallery will make the zoom work only on the first image of the gallery. With the WP Image Zoom Pro the zoom can also be applied on image galleries. 

= Why should you upgrade to WP Image Zoom Pro? =

* Responsive (the zoom window will fit to the browser width)
* Mousewheel Zoom
* Works with WooCommerce variations
* Works with Portfolio images
* Works with Easy Digital Downloads featured images
* Works with MarketPress - WordPress eCommerce
* Zoom within Lightboxes and Carousels
* You can choose the zoom window position (left or right from the image)
* You can use on more than one image on the same page
* Custom theme support

= Notes =

* This plugin is provided "as-is"; within the scope of WordPress. We will update this plugin to remain secure, and to follow WP coding standards.
* If you prefer more dedicated support, with more advanced and powerful plugin features, please consider upgrading to [WP Image Zoom Pro](https://www.silkypress.com/wp-image-zoom-plugin/?utm_source=wordpress&utm_campaign=iz_free&utm_medium=banner).


== Installation ==

* From the WP admin panel, click "Plugins" -> "Add new".
* In the browser input box, type "WP Image Zoom".
* Select the "WP Image Zoom" plugin and click "Install".
* Activate the plugin.

OR...

* Download the plugin from this page.
* Save the .zip file to a location on your computer.
* Open the WP admin panel, and click "Plugins" -> "Add new".
* Click "upload".. then browse to the .zip file downloaded from this page.
* Click "Install".. and then "Activate plugin".

OR...

* Download the plugin from this page.
* Extract the .zip file to a location on your computer.
* Use either FTP or your hosts cPanel to gain access to your website file directories.
* Browse to the `wp-content/plugins` directory.
* Upload the extracted `wp-image-zoooom` folder to this directory location.
* Open the WP admin panel.. click the "Plugins" page.. and click "Activate" under the newly added "WP Image Zoom" plugin.

== Frequently Asked Questions ==

= Does it work with caching plugins ? =
Yes

= The zoom will show up only on the first image on my WooCommerce gallery =
The zoom should work fine with all the images on the default WooCommerce gallery, but some themes replace entirely the gallery with a [Owl Carousel](https://owlcarousel2.github.io/OwlCarousel2/) or a another gallery/carousel/slider. Note that this plugin doesn't change the gallery, it only tries to add a zoom to the present gallery and we cannot guarantee compatibility with each gallery/carousel/slider implementation out there.

= It displays the zoom lens, but the picture is not enlarged =
In order for the zoom to work you have to upload a bigger picture than the one presented on the website. For more control over the zoom level you can try upgrading to the PRO version. There you can set the zoom level to 2x or 3x the size of the presented picture.

In case you did upload a bigger picture and the zoom still isn't working, you might try to deactivate the Jetpack Photon module. The module resizes the image and interferes with the zoom.

= The zoom window is about 1cm lower than the zoomed image =
This is an effect caused by the WordPres Admin Bar. Try logging out and check the zoom again.

Another cause could be the sticky header. When the page is loaded, the zoom window is built and set in the right position (next to the zoomed image). When you scroll down, the sticky header changes its height but the zoom window keeps staying in the same position. In order to solve this you can choose between removing the header's sticky effect or upgrading to the WP Image Zoom PRO, as there the zoom window is totally differently built and the sticky header doesn't affect the zoom position.

Another cause could be the "CSS Animation" settings within WPBakery. If you want to keep the animation effect and still have the zoom, I recommend you upgrade to the WP Image Zoom PRO. 

= How to zoom an image without the button in the editor? =
When you add a CSS class called 'zoooom' to any image, the zoom will be applied on that particular image. Remember that the zooming works only when the displayed image is smaller than the loaded image (i.e. the image is shrinked with "width" and "height" attributes).

= If I want to use a "lazy load" plugin will it work? =
We can ensure compatibility with [Unveil Lazy Load](https://wordpress.org/plugins/unveil-lazy-load/), [WP images lazy loading](https://wordpress.org/plugins/wp-images-lazy-loading/) and [Lazy Load](https://wordpress.org/plugins/lazy-load/) plugins. 


= My image is within a tab =
The zoom lens is built on page load relative to the image and it will be shown in mouse hover no matter if the image is hidden in another tab. We cannot do anything about this, the zoom is not built to work with images within tabs. 

Alternatively you can upgrade to the Pro version, as there the zoom lens is built on mouse hover and not on page load, which means that the zoom will work also with images within tabs. 

= Known Incompatibilities =

* When both the **Black Studio TinyMCE Widget** and the "Site Builder by SiteOrigin" plugins are installed on the website, then the WP Image Zoom button doesn't show up in the Edit Post and Edit Page editor. But you can still apply the zoom if you manage to add the "zoooom" CSS class to the image.

* The zoom doesn't work well with **Image Carousel** on **Avada** theme. You cannot use the zoom and the carousel on the same page.

* The zoom doesn't work at all with the **WooCommerce Dynamic Gallery** plugin. 

* The zoom will not work with the WooCommerce gallery on the **Avada** theme. The Avada theme changes entirely the default WooCommerce gallery with the [Flexslider gallery](https://woocommerce.com/flexslider/) and the zoom plugin does not support the Flexslider gallery. Please check the [PRO version](https://www.silkypress.com/wp-image-zoom-plugin/?utm_source=wordpress&utm_campaign=iz_free&utm_medium=banner) of the plugin for compatibility with the Flexslider gallery. 

= Credits =

* Demo photo from https://pixabay.com/en/camera-retro-ricoh-old-camera-813814/ under CC0 Public Domain license


== Screenshots ==

1. Configuration menu for the Round Lens

2. Configuration menu for the Square Lens

3. Configuration menu for the Zoom Window

4. Application of zoom on an image in a post

5. General configuration menu

6. WooCommerce product page with the Zoom Window applied on the featured image

7. Apply the zoom from WPBakery, the Single Image element

8. Apply the zoom from Page Builder by SiteOrigin, the Image Widget

== Changelog ==

= 1.47.1 =
* 06/21/2021
* Fix: check $_GET variables against an array of allowed values
* Fix: use "window.parent" instead of "document.parent" when checking if the page is within a page builder or not

= 1.46 =
* 03/10/2021
* Fix: remove zoom in Divi Visual Builder for newer versions of Divi
* The largest image wasn't chosen from the "srcset" attribute

= 1.45 =
* 12/18/2020
* Enable the "Custom CSS" field for the Avia page builder elements
* Declare incompatibility with the Product Gallery Slider for WooCommerce plugin
* Test with PHP 8.0, WordPress 5.6, WooCommerce 4.8, jQuery 3.5.1

= 1.44 =
* 09/26/2020
* Disable the Images Lazy Loading functionality from the Litespeed Cache plugin
* Warning for the Additional Variation Images Gallery for WooCommerce plugin

= 1.43 =
* 08/12/2020
* Test with WordPress 5.5, WooCommerce 4.4 and jQuery 3.5.1
* Show warning for Additional Variation Images Gallery plugin

= 1.42 =
* 07/10/2020
* Fix: remove PHP warning
* Show warning for incompatibility with Smart Image Resize for WooCommerce plugin
* Fix: the zoom was making it hard to select the image element in Elementor, Divi Builder and WPBakery

= 1.41 =
* 05/29/2020
* Fix: compatibility with the WooCommerce product gallery on the Enfold theme
* Declare compatibility with WooCommerce 4.1

= 1.40 =
* 03/04/2020
* Fix: the WC product images were distorted on the Flatsome theme
* Fix: allow saving options only if the current user has the "manage_options" capability
* Changes to the backend form
* Declare compatibility with WooCommerce 4.0

= 1.39 =
* 02/09/2020
* Fix: zoom was showing only in customizer if the Lazy Load option from Jetpack is enabled 
* Declare compatibility with WooCommerce 3.9

= 1.38 =
* 11/20/2019
* Fix: product image gallery was broken on the Brooklyn theme
* Tweak: update the plugin's presentation video on Youtube

= 1.37 =
* 11/05/2019
* Declare compatibility with WordPress 5.3 and WooCommerce 3.8
* Tweak: change "Visual Composer" to "WPBakery" in notices and documentation

= 1.36 =
* 09/07/2019
* Fix: Elementor lightbox skewed if zoom applied on it (https://wordpress.org/support/topic/elementor-lightbox-2/)
* Fix: zoom wasn't initiating when some lazy load plugins are installed on the website
* Add notice about the WooCommerce product gallery from the Avada theme

= 1.35 =
* 07/19/2019
* Add "with Zoom" style to the Gutenberg image block

= 1.34 =
* 06/22/2019
* Fix: zoom wasn't initialized for post/page images served by the ShortPixel CDN
* Add Turkish translation


= 1.33 =
* 05/08/2019
* Fix: the zoom is hidden behind a wrapper for all the Edge-Themes
* Declare compatibility with WooCommerce 3.6
* Declare compatibility with WordPress 5.2

= 1.32 =
* 03/19/2019
* Compatibility with WooCommerce gallery for the theGem theme
* Fix: Add the `Custom CSS Class` option for Enfold theme builder elements
* Tweak: update the Bootstrap library used in the admin side to 3.4.1 version

= 1.31 =
* 02/23/2019
* Compatibility with the Image Hotspot plugin by DevVN 

= 1.30 =
* 12/13/2018
* Fix: remove "trunk" folder from tags. This leads to a "The plugin does not have a valid header" error during the installation

= 1.29 =
* 12/09/2018
* Check and declare compatibility with WooCommerce 3.5
* Check and declare compatibility with WordPress 5.0
* Code refactoring

= 1.28 =
* 10/23/2018
* Fix: the zoom is hidden behind a wrapper for all the Mikado-Themes

= 1.27 =
* 10/10/2018
* Fix: the zoom is hidden behind a wrapper on the Salient theme
* Describe how to apply the zoom with different page builders

= 1.26 =
* 08/11/2018
* Fix: add back prettyPhoto for the Sovereign theme
* Fix: WooCommerce 3.0+ gallery with one image and with a flex gallery on the same page was not working
* Fix: Flatsome theme with the default WooCommerce gallery, don't remove the gallery-slider support 
* Tweak: update the list of themes that add a whole page wrapper with the z-index higher than the zoom  
* Tweak: change the detectmobilebrowser library from jQuery to pure JS

= 1.25 =
* 03/31/2018
* Fix: Fatal error for PHP older than 5.5
* Code refactory

= 1.24 =
* 03/29/2018
* Fix: Allow the default WooCommerce lightbox along with the zoom
* Fix: WC3 with the "Remove Lightbox" option disabled showed a lingering image after closing the lightbox. 
* Security fix: check the option name against an array of allowed values. (https://advisories.dxw.com/advisories/wp-image-zoom-dos/)
* Tweak: update the list of themes that add a whole page wrapper with the z-index higher than the zoom  

= 1.23 =
* 02/08/2018
* Fix: on window resize, show zoom on WooCommerce gallery only if the option is enabled (https://wordpress.org/support/topic/disabling-the-zoom-not-working/)
* Fix: if the image's width is set to 0, then there is "division by zero" warning

= 1.22 =
* 01/30/2018
* Fix: on window resize, show zoom on WooCommerce category pages only if the option is enabled (https://wordpress.org/support/topic/disabling-the-zoom-not-working/)

= 1.21 =
* 12/04/2017
* Tweak: remove the "Exchange the thumbnail with main image on WooCommerce products" for WooCommerce 3, as it doesn't have an effect anymore
* Compatibility with plugins that load WooCommerce products with AJAX
* Info about supported lightboxes
* Compatibility with the YITH WooCommerce Ajax Product Filter plugin

= 1.19 =
* 11/05/2017
* Change demo image, presentation video and screenshots
* Fix: bug https://wordpress.org/support/topic/error-in-the-console-undefined-404/
* Show warning incompatibility with WooSwipe plugin

= 1.18 =
* 10/19/2017
* Declare compatibility with WooCommerce 3.2 (https://woocommerce.wordpress.com/2017/08/28/new-version-check-in-woocommerce-3-2/)

= 1.17 =
* 10/14/2017
* Feature: compatibility with the Stockholm theme

= 1.16 =
* 09/14/2017
* Feature: support select-themes.com which add a wrapper on top of the page

= 1.15 =
* 08/20/2017
* Feature: support zoom for images within Courses and Quizzes for LearnPress
* Fix: the "Enable zoom on WooCommerce category pages" works also on archive pages 

= 1.14 =
* 08/16/2017
* Add French and Romanian translations

= 1.13 =
* 07/11/2017
* Fix: https://wordpress.org/support/topic/conflict-with-black-studio-tinymce-widget-3/ 
* Fix: the zoom overtakes the hover action when a dropdown menu is open
* Feature: compatibility with the Dorian theme

= 1.12 = 
* 06/10/2017
* Feature: support for custom post type

= 1.11 =
* 06/07/2017
* Fix: when a caching plugin is present, a tablet is still considered a mobile device 
* Fix: remove woocommerce slider support for the Kiddy theme
* Fix: with jQuery v<1.11 the mobile browser needs to be checked with $. instead of jQuery.

= 1.10 =
* 05/10/2017
* Fix: warning for the Shopkeeper theme
* Fix: don't add/remove theme support for the gallery if is not enabled for WooCommerce 

= 1.9 =
* 04/09/2017
* Fix: if the image has data-large_image attribute, then use that for the zoom
* Fix: if data-zoom-image attribute present, then exchange it with the thumbnails in WooCommerce gallery
* Fix: remove click action on the WooCommerce images

= 1.8 =
* 04/04/2017
* Feature: compatibility with WooCommerce 3.0.+

= 1.7 =
* 03/27/2017
* Feature: compatibility with the Nouveau theme
* Feature: compatibility with the WP-Cache Super for the `enable on mobiles` option
* Fix: don't add the full size image to the srcset if the image is cropped
* Fix: replace the `move` cursor type with `zoom-in`
* Fix: compatibility with the 2.8.6+ Virtue theme, see https://wordpress.org/support/topic/woocommerce_single_product_image_html-filter/

= 1.6 =
* 02/21/2017
* Feature: compatibility with the Lazy Load plugin (https://wordpress.org/plugins/lazy-load/)
* Fix: remove the "Compatible with LazyLoad (unveil)" option and apply the fix automatically if the $.unveil function is present

= 1.5 =
* 01/22/2017
* Feature: plugin ready for translation
* Feature: translation for Romanian

= 1.4 =
* 12/08/2016
* Feature: "Exchange the thumbnail with the main image on WooCommerce products" option
* Feature: compatibility with the Artcore theme 
* Feature: show a notice about BWP Minify configurations

= 1.3.1 =
* 09/16/2016
* Fix: remove the prettyPhoto only if WooCommerce is active and only on product pages

= 1.3.0 =
* 08/17/2016
* Fix: PHP Notice when adding two arrays without checking first the variable type
* Fix: add the attachment-shop_single and attachment-thumbnail classes to the WooCommerce product images if these are missing
* Added grayed out fields as in the PRO version

= 1.2.9 =
* 07/20/2016
* Fix: if the full image isn't present in the srcset, add it
* Fix: compatibility with the Bridge theme
* Fix: set the data-zoom-image attribute as having priority over the srcset attribute

= 1.2.8 =
* 04/21/2016
* Fix: For WooCommerce galleries keep the thumnail's src in data-thumbnail-src. This will fix some esthetic issue with long images
* Feature: add data-zoom-image attribute if the srcset is not present, but the "zoooom" class is present

= 1.2.7 =
* 04/11/2016
* Feature: add TinyMCE button to the LearnDash post types

= 1.2.6 = 
* 02/14/2016
* Feature: Enable the zoom on the WooCommerce category pages 
* Fix: replaced the <?= ?> with <?php echo ?> to make it work for PHP < 5.4 and short_open_tag = Off
* Feature: you can tag a div with "zoooom" class in order to apply the zoom
* Feature: compatibility with WPBakery 

= 1.2.5 = 
* 01/19/2016
* Added admin-notices 

= 1.2.4 =
* 12/24/2015
* Fix: With WordPress 4.4 the WooCommerce thumbnail images were not switched for the main image

= 1.2.3 = 
* 11/21/2015
* Fix: The tooltips for checkboxes were not working
* Fix: 'Distance from the Main Image' was not having an effect on the frontend
* Fix: removed the mousewheel zoom as it was not working
* Fix: TinyMCE in the WP-Lister Templates was not working

= 1.2.2 =
* 11/06/2015
* Fix: 'Force to work on woocommerce' also on JPEG files 

= 1.2.1 =
* 10/20/2015
* Added compatibility with LazyLoad (unveil.js)
* Fix: with round and square lens the zoom was flickering when getting out of the image area. (https://wordpress.org/support/topic/lazyload-conflicts-more)

= 1.2.0 =
* 10/13/2015
* Added .pot file for translation.

= 1.1.4 =
* 10/07/2015
* Fix: https://wordpress.org/support/topic/problem-when-resize-window (regenerate the zoomed image when the page is resized)
* Fix: https://wordpress.org/support/topic/not-working-1307 (when choosing another image from woocommerce gallery, if the image is not big enough to generate a zoom, it was still showing the previously chosen image)

= 1.1.3 =
* 08/18/2015
* The Shadow Thickness was not working. 

= 1.1.2 =
* 08/05/2015
* Remove the WooCommerce lightbox. It doesn't make sense to have the lightbox and zoom at the same time

= 1.1.1 =
* 08/04/2015
* Fix: https://wordpress.org/support/topic/not-working-1307 (force it to work with WooCommerce images, even when the templates tries to load a smaller size image)

= 1.1.0 =
* 08/03/2015
* Update to WP 4.2.3

= 1.0.9 =
* 06/29/2015
* Fix: https://wordpress.org/support/topic/zoom-appears-behind-an-product-image 

= 1.0.8 =
* 06/23/2015
* Fix: https://wordpress.org/support/topic/zoom-configuration-settings-not-available

= 1.0.7 =
* 06/21/2015
* Added: right side box with WP Image Zoom Pro

= 1.0.6 =
* 06/03/2015
* Fix: https://wordpress.org/support/topic/the-zoom-button-does-not-appear (it was assumed that the path to the plugin is the standard one. Now it loads the .png from a path relative to tinyMCE-button.js)

= 1.0.5 =
* 06/01/2015
* Fix: https://wordpress.org/support/topic/parse-error-334 (retrieval of static variables for PHP<5.2 is done differently)

= 1.0.4 =
* 05/27/2015
* Fix: solved the JS bug that was leading to "works in the upper-left of the image"

= 1.0.3 =
* 05/26/2015
* Fix: add version number to the css, otherwise the css was taken from the cache from the previous version

= 1.0.2 =
* 05/26/2015
* Added: "Like this Plugin?" box in the admin
* Tweak: refactored the "Zoom Settings" page in the admin and added steps

= 1.0 =
* 05/19/2015
* Initial commit

== Upgrade Notice ==

Nothing at the moment
