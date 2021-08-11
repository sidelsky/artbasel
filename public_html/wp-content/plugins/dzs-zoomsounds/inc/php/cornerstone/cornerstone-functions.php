<?php

function dzsap_cs_home_before() {
  global $dzsap;

  // -- enqueue in cusotmizer

  wp_enqueue_script('dzsap-admin-for-cornerstone', DZSAP_BASE_URL . 'assets/admin/admin-for-cornerstone.js', array('jquery'));
  wp_enqueue_script('dzsap-admin-global', DZSAP_BASE_URL . 'admin/admin_global.js', array('jquery'));
  wp_enqueue_style('dzsap-admin-global', DZSAP_BASE_URL . 'admin/admin_global.css');

}


function dzsap_cs_register_elements() {
  global $dzsap;


  cornerstone_register_element('CS_DZSAP', 'dzsap', DZSAP_BASE_PATH . 'inc/php/inc-cornerstone/dzsap');
  cornerstone_register_element('CS_DZSAP_PLAYLIST', 'dzsap_playlist', DZSAP_BASE_PATH . 'inc/php/inc-cornerstone/dzsap_playlist');


}

function dzsap_cs_enqueue() {
  DZSZoomSoundsHelper::enqueueMainScrips();
}

function dzsap_cs_icon_map($icon_map) {
  global $dzsap;
  $icon_map['dzsap'] = DZSAP_BASE_URL . '/assets/svg/icons.svg';
  return $icon_map;
}