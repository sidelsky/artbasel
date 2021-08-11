<?php

function dzsap_preview_player__headScripts() {


  wp_enqueue_style('dzsap-admin-preview-player', DZSAP_BASE_URL.'admin/admin-page/admin-preview-player.css');
}

function dzsap_preview_player() {

  global $dzsap;
  ?>
  <div class="wrap wrap-for-player-preview">
  <?php


  $config = '';

  if (isset($_GET['config']) && $_GET['config']) {
    $config = $_GET['config'];
  }
  $args = array(
    'source' => 'https://soundbible.com/mp3/Hummingbird-SoundBible.com-623295865.mp3',
    'config' => $config,
    'artistname' => 'artist',
    'songname' => 'song',
    'thumb' => 'https://i.imgur.com/jCLdxjj.jpg',
  );

  echo $dzsap->classView->shortcode_player($args);
  ?>
  </div><?php

}