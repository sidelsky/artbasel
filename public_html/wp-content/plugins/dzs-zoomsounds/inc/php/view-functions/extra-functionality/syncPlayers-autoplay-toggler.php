<?php


/**
 * @param array $atts
 * @param string $content
 * @return string
 */
function dzsap_shortcode_syncplayers_autoplay_toggler($atts = array(), $content = '') {

  global $dzsap;
  $dzsap->extraFunctionalities['syncPlayers_autoplayToggler'] = true;


  dzsap_view_syncPlayer_autoplay_toggler_scripts();
  return do_shortcode($content);
}


function dzsap_view_syncPlayer_autoplay_toggler_scripts() {



  wp_enqueue_style('dzsap-view-syncplayers-autoplay-toggler', DZSAP_BASE_URL.'inc/style/shortcodes/dzsap-view-syncplayers-autoplay-toggler.css');
  wp_enqueue_script('dzsap-view-syncplayers-autoplay-toggler', DZSAP_BASE_URL.'inc/js/shortcodes/dzsap-view-syncplayers-autoplay-toggler.js');
}