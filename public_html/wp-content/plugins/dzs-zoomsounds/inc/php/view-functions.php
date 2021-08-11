<?php
function dzsap_generate_javascript_setting_for_playlist($videoPlaylistSettingsMerged) {
  global $dzsap;
  $foutArr = array();

  if (isset($videoPlaylistSettingsMerged)) {

    $arrPlayerSettingsArray = include(DZSAP_BASE_PATH . 'configs/playlist-options.php');


    foreach ($arrPlayerSettingsArray as $key => $optArr) {

      $jsName = $key;
      $jsName = str_replace('dzsap_meta_', '', $jsName);
      if (isset($optArr['jsName']) && $optArr['jsName']) {
        $jsName = $optArr['jsName'];
      }


      $value = null;


      if (isset($videoPlaylistSettingsMerged[$key]) && (!isset($optArr['canBeEmptyString']) || (isset($optArr['canBeEmptyString']) && ($optArr['canBeEmptyString'] === true || !$optArr['canBeEmptyString'] && $videoPlaylistSettingsMerged[$key])))) {
        $value = $videoPlaylistSettingsMerged[$key];
      }


      if (isset($optArr['default']) && $value !== null && $value === $optArr['default']) {
        continue;
      }


      if ($value !== null) {


        $foutArr[$jsName] = $value;
      }

    }
  }


  return array(
    'foutArr' => $foutArr,
  );
}

/**
 * @param $videoPlayerSettingsMerged - vpsettings merged with prev func margs
 * @return string[]
 */
function dzsap_generate_javascript_setting_for_player($videoPlayerSettingsMerged) {

  global $dzsap;
  $fout = '';
  $foutArr = array();

  if (isset($videoPlayerSettingsMerged)) {

    $arrPlayerSettingsArray = include(DZSAP_BASE_PATH . 'configs/config-player-config.php');




    foreach ($arrPlayerSettingsArray as $key => $optArr) {

      $jsName = $key;
      $jsName = str_replace(DZSAP_META_OPTION_PREFIX, '', $jsName);
      if (isset($optArr['jsName']) && $optArr['jsName']) {
        $jsName = $optArr['jsName'];
      }


      $value = null;

      if (isset($videoPlayerSettingsMerged[$key])) {
        $value = $videoPlayerSettingsMerged[$key];
      }else{
        if (isset($videoPlayerSettingsMerged[$jsName])) {
          $value = $videoPlayerSettingsMerged[$jsName];
        }
      }


      // -- leave it for now - these options do not exist any more
      if ($key == 'skinwave_wave_mode_canvas_waves_number' || $key == 'skinwave_wave_mode_canvas_waves_padding' || $key == 'skinwave_wave_mode_canvas_reflection_size') {
        if (!$value) {
          $value = $dzsap->mainoptions[$key];
        }
      }


      if (isset($optArr['default']) && $value !== null && $value === $optArr['default']) {
        continue;
      }


      if ($value !== null) {
        $foutArr[$jsName] = $value;

        if ($fout) {
          $fout .= ',';
        }
        $fout .= '' . $jsName . ':"' . DZSZoomSoundsHelper::sanitize_for_javascript_double_quote_value($value) . '"';
      }
    }
  }


  $jsName = 'pause_method';
  if ($dzsap->mainoptions['player_pause_method'] == 'stop') {
    $fout .= ',' . $jsName . ':"' . $dzsap->mainoptions['player_pause_method'] . '"';
    $foutArr[$jsName] = $value;
  }


  $jsName = 'skinwave_comments_mode_outer_selector';
  $value = '.zoomsounds-comment-wrapper';
  if (isset($videoPlayerSettingsMerged['outer_comments_field']) && $videoPlayerSettingsMerged['outer_comments_field'] == 'on') {
    $fout .= ',' . $jsName . ': "' . $value . '"';
    $foutArr[$jsName] = $value;
  }


  $jsName = 'skinwave_comments_mode_outer_selector';
  if ($dzsap->mainoptions['skinwave_wave_mode'] !== 'canvas') {
    $value = $dzsap->mainoptions['skinwave_wave_mode'];

    $fout .= ',' . $jsName . ':"' . $value . '"';
    $foutArr[$jsName] = $value;
  }


  $jsName = 'soundcloud_apikey';

  if ($dzsap->mainoptions['soundcloud_api_key']) {
    $value = $dzsap->mainoptions['soundcloud_api_key'];
    $fout .= ',"' . $jsName . '":"' . $value . '"';
    $foutArr[$jsName] = $value;


    $jsName = 'php_retriever';
    $value = DZSAP_BASE_URL . 'inc/php/soundcloudretriever.php';
    $fout .= ',"' . $jsName . '":"' . $value . '"';
    $foutArr[$jsName] = $value;
  }


  if (isset($dzsap->mainoptions['skinwave_wave_mode_canvas_normalize']) && $dzsap->mainoptions['skinwave_wave_mode_canvas_normalize'] === 'off') {
    $jsName = 'skinwave_wave_mode_canvas_normalize';
    $value = $dzsap->mainoptions['skinwave_wave_mode_canvas_normalize'];
    $fout .= ',"' . $jsName . '":"' . $value . '"';
    $foutArr[$jsName] = $value;
  }


  if ($dzsap->mainoptions['failsafe_repair_media_element'] == 'on') {
    $jsName = 'failsafe_repair_media_element';
    $value = 1000;
    $fout .= ',"' . $jsName . '":"' . $value . '"';
    $foutArr[$jsName] = $value;
  }


  $lab = 'footer_btn_playlist';
  if (isset($videoPlayerSettingsMerged['called_from']) && $videoPlayerSettingsMerged['called_from'] == 'footer_player' && isset($videoPlayerSettingsMerged[$lab]) && $videoPlayerSettingsMerged[$lab] && $videoPlayerSettingsMerged[$lab] == 'on') {
    $jsName = $lab;
    $value = $videoPlayerSettingsMerged[$lab];
    $fout .= ',"' . $jsName . '":"' . $value . '"';
    $foutArr[$jsName] = $value;


  }


  if ($dzsap->mainoptions['mobile_disable_footer_player'] == 'on') {
    if (isset($videoPlayerSettingsMerged['called_from']) && $videoPlayerSettingsMerged['called_from'] == 'footer_player') {

      $jsName = 'mobile_delete';
      $value = 'on';
      $fout .= ',"' . $jsName . '":"' . $value . '"';
      $foutArr[$jsName] = $value;

    } else {

      $jsName = 'mobile_disable_fakeplayer';
      $value = 'on';
      $fout .= ',"' . $jsName . '":"' . $value . '"';
      $foutArr[$jsName] = $value;
    }
  }


  if (isset($videoPlayerSettingsMerged['js_settings_extrahtml_in_float_right']) && $videoPlayerSettingsMerged['js_settings_extrahtml_in_float_right']) {
    // -- here we set it

    $jsName = 'settings_extrahtml_in_float_right';
    $value = $videoPlayerSettingsMerged['js_settings_extrahtml_in_float_right'];

    $fout .= ',"' . $jsName . '":"' . $value . '"';
    $foutArr[$jsName] = $value;

    if (strpos($videoPlayerSettingsMerged['js_settings_extrahtml_in_float_right'], 'dzsap-multisharer-but') !== false) {
      $dzsap->isEnableMultisharer = true;
    }
  }


  $has_extra_html = false;


  $propertiesToCheckForExtraHtml = array(
    'enable_views', 'enable_downloads_counter', 'enable_likes', 'enable_rates', 'extra_html'
  );

  // -- enable likes in player
  if (isset($videoPlayerSettingsMerged)) {
    foreach ($propertiesToCheckForExtraHtml as $prop) {
      if (isset($videoPlayerSettingsMerged[$prop]) && $videoPlayerSettingsMerged[$prop] && $videoPlayerSettingsMerged[$prop] !== 'off') {

        $has_extra_html = true;
      }
    }
  }

  if (!(isset($videoPlayerSettingsMerged['embedded']) && $videoPlayerSettingsMerged['embedded'] == 'on')) {

    $shouldShowEmbedButton = !!(isset($videoPlayerSettingsMerged['enable_embed_button']) && ($videoPlayerSettingsMerged['enable_embed_button'] == 'on' || $videoPlayerSettingsMerged['enable_embed_button'] == 'in_player_controls' || $videoPlayerSettingsMerged['enable_embed_button'] == 'in_extra_html' || $videoPlayerSettingsMerged['enable_embed_button'] == 'in_lightbox'));

    if ($shouldShowEmbedButton) {
      $enc_margs = '';
      $embed_code = '';


      // -- if we have embed_code already set
      if (isset($videoPlayerSettingsMerged['embed_code']) && $videoPlayerSettingsMerged['embed_code']) {
        $embed_code = $videoPlayerSettingsMerged['embed_code'];
      } else {

      }


      if ($has_extra_html) {

      } else {

        if ($videoPlayerSettingsMerged['enable_embed_button'] == 'on' || $videoPlayerSettingsMerged['enable_embed_button'] == 'in_player_controls') {


          $jsName = 'enable_embed_button';
          $value = 'on';
          $fout .= ',"' . $jsName . '":"' . $value . '"';
          $foutArr[$jsName] = $value;
        }

      }
    }
  }


  $jsName = 'settings_php_handler';
  $value = 'wpdefault';
  $fout .= ',"' . $jsName . '":"' . $value . '"';
  $foutArr[$jsName] = $value;


  $lab = 'pcm_data_try_to_generate';
  if (isset($videoPlayerSettingsMerged[$lab]) && $videoPlayerSettingsMerged[$lab]) {


    $jsName = $lab;
    $value = $videoPlayerSettingsMerged[$lab];
    $fout .= ',"' . $jsName . '":"' . $value . '"';
    $foutArr[$jsName] = $value;
  }
  $lab = 'pcm_notice';
  if (isset($videoPlayerSettingsMerged[$lab]) && $videoPlayerSettingsMerged[$lab]) {


    $jsName = $lab;
    $value = $videoPlayerSettingsMerged[$lab];
    $fout .= ',"' . $jsName . '":"' . $value . '"';
    $foutArr[$jsName] = $value;
  }
  $lab = 'notice_no_media';
  if (isset($videoPlayerSettingsMerged[$lab]) && $videoPlayerSettingsMerged[$lab]) {

    $jsName = $lab;
    $value = $videoPlayerSettingsMerged[$lab];
    $fout .= ',"' . $jsName . '":"' . $value . '"';
    $foutArr[$jsName] = $value;
  }

  if ($dzsap->mainoptions['analytics_enable'] == 'on') {

    $jsName = 'action_video_contor_60secs';
    $value = 'wpdefault';
    $fout .= ',"' . $jsName . '":"' . $value . '"';
    $foutArr[$jsName] = $value;
  }


  if ($dzsap->mainoptions['failsafe_repair_media_element'] == 'on') {
    $fout .= ',"failsafe_repair_media_element":1000';


    $jsName = 'failsafe_repair_media_element';
    $value = '1000';
    $fout .= ',"' . $jsName . '":"' . $value . '"';
    $foutArr[$jsName] = $value;
  }

  if ($dzsap->mainoptions['settings_trigger_resize'] == 'on') {
    $jsName = 'settings_trigger_resize';
    $value = '1000';
    $fout .= ',"' . $jsName . '":"' . $value . '"';
    $foutArr[$jsName] = $value;
  };

  if ($dzsap->mainoptions['wavesurfer_pcm_length'] != '200') {

    $jsName = 'wavesurfer_pcm_length';
    $value = $dzsap->mainoptions['wavesurfer_pcm_length'];
    $fout .= ',"' . $jsName . '":"' . $value . '"';
    $foutArr[$jsName] = $value;

  };


  // -- end todo


  return array(
    'foutArr' => $foutArr,
    'fout' => $fout
  );
}

/**
 * returns a string with { "optKey": "optValue" ... }
 * @param array $pargs
 * @param array $vpsettings
 * @param array $its
 * @param array $prev_func_margs
 * @return string
 */
function dzsap_generate_audioplayer_settings($pargs = array(), $vpsettings = array(), $its = array(), $prev_func_margs = array()) {
  // -- @call from shortcode_player

  global $current_user, $post, $dzsap;
  $margs = array(
    'extra_classes' => 'search-align-right',
    'call_from' => 'default',
    'playerid' => '12345',
    'extra_init_settings' => array(),
    'enc_margs' => '',
  );

  $fout = '';

  if (!is_array($pargs)) {
    $pargs = array();
  }
  $margs = array_merge($margs, $pargs);


  $player_id = $margs['playerid'];


  if ($margs['call_from'] == 'zoombox_open') {

    $fout .= '{';
    $fout .= 'design_skin: "' . $vpsettings['settings']['skin_ap'] . '"
    ,skinwave_dynamicwaves:"' . $vpsettings['settings']['skinwave_dynamicwaves'] . '"
    ,disable_volume:"' . $vpsettings['settings']['disable_volume'] . '"
    ,skinwave_enableSpectrum:"' . $vpsettings['settings']['skinwave_enablespectrum'] . '"
    ,skinwave_enableReflect:"' . $vpsettings['settings']['skinwave_enablereflect'] . '"
    ,skinwave_comments_enable:"' . $vpsettings['settings']['skinwave_comments_enable'] . '"';

    $fout .= ',settings_php_handler:window.ajaxurl';
    if (isset($vpsettings['settings']['settings_backup_type']) && $vpsettings['settings']['settings_backup_type']) {
      $fout .= ',settings_backup_type:"' . $vpsettings['settings']['settings_backup_type'] . '"';
    }


    if (isset($vpsettings['settings']['disable_scrubbar'])) {
      $fout .= ',disable_scrub:"' . $vpsettings['settings']['disable_scrubbar'] . '"';
    }
    $fout .= '}';
  }


  // -- shortcode
  if ($margs['call_from'] == 'shortcode_player') {


    $audioPlayerSettingsArray = dzsap_generate_javascript_setting_for_player(array_merge($vpsettings['settings'], $prev_func_margs))['foutArr'];

    if (is_array($margs['extra_init_settings'])) {
      $audioPlayerSettingsArray = array_merge($audioPlayerSettingsArray, $margs['extra_init_settings']);
    }


    if (isset($prev_func_margs['called_from']) && $prev_func_margs['called_from'] === 'footer_player') {

      unset($audioPlayerSettingsArray['settings_php_handler']);
      unset($audioPlayerSettingsArray['settings_php_handler2']);
      unset($audioPlayerSettingsArray['action_received_time_total']);
    }


    $fout .= json_encode($audioPlayerSettingsArray);


  }

  return $fout;
}


