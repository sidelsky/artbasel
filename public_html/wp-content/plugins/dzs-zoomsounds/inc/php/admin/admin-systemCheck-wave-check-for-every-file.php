<?php

function dzsap_admin_systemCheck_wavesCheckEacHFileInit() {
  // todo: add search + pagination
  global $dzsap;
  $currentPageIndex = 1;
  if (isset($_GET['paged']) && $_GET['paged']) {
    $currentPageIndex = $_GET['paged'];
  }
  $searchedVal = '';
  if(isset($_GET['track_search'])&&$_GET['track_search']){
    $searchedVal = $_GET['track_search'];
  }

  $getAudioAttachmentsArgs = array
  (
    'post_type' => 'attachment',
    'post_status' => 'inherit',
    'post_mime_type' => 'audio',
    'paged' => $currentPageIndex,
    'posts_per_page' => '3',
    'cache_results' => true,
  );
  if($searchedVal){


    if(is_numeric($searchedVal)){

      $getAudioAttachmentsArgs['p'] = $searchedVal;
    }else{

      $getAudioAttachmentsArgs['s'] = $searchedVal;
      $getAudioAttachmentsArgs['s_meta_keys'] = array('short_desc', 'post_title','post_id', 'post_content', 'ID');
    }
  }
  $audioQuery = new WP_Query($getAudioAttachmentsArgs);



  $fout = '';
  $fout .= '<hr>';


  $audioPosts = $audioQuery->posts;
  foreach ($audioPosts as $file) {
    $url = wp_get_attachment_url($file->ID);

    $fout .= '<h3>' . $file->post_title . '</h3>';

    $fout .= $dzsap->classView->shortcode_player(array(
      'source' => $file->ID
    ));

    $fout .= '<button style="margin-top:10px;" class="button-secondary systemCheck_waves_check-regenerate-waveform" data-track_url="' . $url . '">' . esc_html__('Regenerate waveform', DZSAP_ID) . '</button>';


  }
  $fout .= '<br><br>';
  $fout .= dzs_pagination($audioQuery->max_num_pages, 2, array(
    'paged' => $currentPageIndex,
  ));
  $fout .= '<hr>';

  echo $fout;
}