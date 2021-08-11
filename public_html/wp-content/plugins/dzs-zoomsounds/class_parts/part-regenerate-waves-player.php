<?php


$id = DZSZoomSoundsHelper::sanitize_toKey($_GET['dzsap_generate_pcm']);

$lab = 'dzsap_pcm_data_' . ($id);


update_option($lab, '');

$a = array();
$b = array();
$source = $this->get_track_source($id, $a, $b);


if ($source) {

  $lab = 'dzsap_pcm_data_' . (DZSZoomSoundsHelper::sanitize_toKey($source));
  update_option($lab, '');
}




wp_enqueue_style('dzsap-regenerate-waveforms', DZSAP_BASE_URL.'inc/style/shortcodes/dzs-admin-regenerate-waveforms.css');
wp_enqueue_script('dzsap-regenerate-waveforms', DZSAP_BASE_URL.'admin/admin-page/regenerate-waveforms.js');
DZSZoomSoundsHelper::enqueueMainScrips();