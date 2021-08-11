<?php
/*
  Plugin Name: DZS ZoomSounds
  Plugin URI: https://digitalzoomstudio.net/
  Description: Creates cool audio players with optional playlists for your site.
  Version: 6.45
  Author: Digital Zoom Studio
  Author URI: https://digitalzoomstudio.net/
 */

const DZSAP_VERSION = '6.45';

if (function_exists('plugin_dir_url')) {
  define('DZSAP_BASE_URL', plugin_dir_url(__FILE__));
}
if (function_exists('plugin_dir_path')) {
  define('DZSAP_BASE_PATH', plugin_dir_path(__FILE__));
}

include_once(DZSAP_BASE_PATH . 'dzs_functions.php');

$classPath = DZSAP_BASE_PATH . 'class-dzsap.php';

if (!class_exists('DZSAudioPlayer')) {
  include_once($classPath);
}


$dzsap = new DZSAudioPlayer();
