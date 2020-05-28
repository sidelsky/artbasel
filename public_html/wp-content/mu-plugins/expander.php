<?php
/*
Plugin Name: Expander
Version: 0.3.3 
Description: Minimalistic accordion plugin. Text expander plugin. Just like popcorn. Click and pop.
Author: ThemeIsle
Author URI: http://themeisle.com

Copyright 2012, 2013, 2014, 2015, 2016 Ciprian Popescu (email: getbutterfly@gmail.com)

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program. If not, see <http://www.gnu.org/licenses/>.
*/

/*
 * Usage: [wpex class="wpex-link" more="Read more" less="Read less"]hidden text[/wpex]
 */

define('WPEX_PLUGIN_URL', WP_PLUGIN_URL . '/' . dirname(plugin_basename(__FILE__)));
define('WPEX_PLUGIN_PATH', WP_PLUGIN_DIR . '/' . dirname(plugin_basename(__FILE__)));

function wpex_register_shortcodes() {
	add_shortcode('wpex', 'wpex_main');
}

function wpex_main($atts, $content = null) {
	extract(shortcode_atts(array(
		'more' => 'Read more',
		'less' => 'Read less',
      'class' => 'wpex-link',
	), $atts));

	mt_srand((double)microtime() * 1000000);
	$rnum = mt_rand();

	$new_string = '<a onclick="wpex_toggle(' . $rnum . ', \'' . addslashes($more) . '\', \'' . addslashes($less) . '\'); return false;" class="' . addslashes($class) . '" id="wpexlink' . $rnum . '" href="#" style="color: black;">' . addslashes($more) . '</a><div class="wpex_div" id="wpex' . $rnum . '" style="display: none;">' . do_shortcode($content) . '</div>';

	return $new_string;
}

function wpex_javascript() {
    echo '<script>function expand(e){e.style.display="none"==e.style.display?"block":"none"}function wpex_toggle(e,n,l){el=document.getElementById("wpexlink"+e),el.innerHTML=el.innerHTML==n?l:n,expand(document.getElementById("wpex"+e))}</script>';
}

add_action('wp_footer', 'wpex_javascript');
add_action('init', 'wpex_register_shortcodes');

//add_filter('the_content', 'do_shortcode', 10);
add_filter('widget_text', 'do_shortcode', 10);
?>