<?php
function dzsap_view_embed_init_listeners(){




  add_action('wp_enqueue_scripts', 'dzsap_view_embed_handle_wp_enqueue_scripts', 500);
}

function dzsap_view_embed_handle_wp_enqueue_scripts(){

  if (isset($_GET['action'])) {
    if ($_GET['action'] == 'embed_zoomsounds') {
      DZSZoomSoundsHelper::enqueueMainScrips();

      wp_enqueue_style('dzsap-shortcode-zoomsounds-embed', DZSAP_BASE_URL.'inc/style/shortcodes/dzs-zoomsounds-embed.css');
      wp_enqueue_script('dzsap-shortcode-zoomsounds-embed', DZSAP_BASE_URL.'inc/js/shortcodes/zoomsounds-embed.js');
    }
  }
}

/**
 * user for embed
 */
function dzsap_view_embed_generateHtml() {

  global $dzsap;





  echo '<div class="zoomsounds-embed-con">';

  $shortcodePlayerAtts = array();
  if (isset($_GET['type']) && $_GET['type'] == 'gallery') {

    $shortcodePlayerAtts = array(
      'id' => $_GET['id'],
      'embedded' => 'on',
    );


    if (isset($_GET['db'])) {
      $shortcodePlayerAtts['db'] = $_GET['db'];
    };
    echo $dzsap->classView->shortcode_playlist_main($shortcodePlayerAtts);

  }
  if (isset($_GET['type']) && $_GET['type'] == 'playlist') {

    $shortcodePlayerAtts = array(
      'ids' => $_GET['ids'],
      'embedded' => 'on',
    );


    if (isset($_GET['db'])) {
      $shortcodePlayerAtts['db'] = $_GET['db'];
    };
    echo $dzsap->classView->shortcode_playlist_main($shortcodePlayerAtts);

  }


  if (isset($_GET['type']) && $_GET['type'] == 'player') {



    $shortcodePlayerAtts = array();
    try {

      $shortcodePlayerAtts = @unserialize((stripslashes($_GET['margs'])));
    } catch (Exception $e) {


    }




    if (is_array($shortcodePlayerAtts)) {

    } else {
      $shortcodePlayerAtts = array();












      $shortcodePlayerAtts = json_decode((stripslashes(base64_decode($_GET['margs']))), true);



      if (is_object($shortcodePlayerAtts) || is_array($shortcodePlayerAtts)) {

      } else {
        $shortcodePlayerAtts = array();
      }

    }

    $shortcodePlayerAtts['embedded'] = 'on';
    $shortcodePlayerAtts['extra_classes'] = ' test';
    $shortcodePlayerAtts['called_from'] = 'embed';



    echo $dzsap->classView->shortcode_player($shortcodePlayerAtts);

  }
  echo '</div>';
}
