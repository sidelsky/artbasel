'use strict';
/**
 *
 * @param {string} topLevelPageName selector
 * @param {string} topLevelPostName selector
 */
window.dzsap_wp_pseudoHighlightMenuItem = function(topLevelPageName, topLevelPostName){
  var $ = jQuery;
  $("#toplevel_page_"+topLevelPageName+", #toplevel_page_"+topLevelPageName+" > a").addClass("wp-has-current-submenu");
  $("#toplevel_page_"+topLevelPageName+" .wp-first-item").addClass("current");
  $("#menu-posts-"+topLevelPostName+", #menu-posts-"+topLevelPostName+">a").removeClass("wp-has-current-submenu wp-menu-open");
}


jQuery(document).ready(function ($) {
  window.wp_pseudoHighlightMenuItem('dzsap_menu', 'dzsap_items');
});