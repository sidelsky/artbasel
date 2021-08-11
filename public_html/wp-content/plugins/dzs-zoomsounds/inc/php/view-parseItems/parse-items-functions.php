<?php
function dzsap_view_parseItemsInitialClassSetup($its, $i, $post, $singleItemInstance, $singlePlayerOptions){

  $audioplayerClasses = 'audioplayer-tobe ';


  $str_post_id = '';

  if ($post) {
    $str_post_id = '_' . $post->ID;
  }


  $audioplayerClasses .= ' playerid-' . $singleItemInstance['playerId_computed'];


  if (isset($its[$i]['player_index']) && $its[$i]['player_index']) {
    $audioplayerClasses .= ' ap_idx' . $str_post_id . '_' . $its[$i]['player_index'];
  }

  if (isset($singlePlayerOptions['is_single']) && $singlePlayerOptions['is_single'] == 'on') {
    $audioplayerClasses .= ' is-single-player';
  }




  if(isset($singleItemInstance['source'])){
    if(strpos($singleItemInstance['source'], '{{generatenonce}}')!==false){


    }
  }






  if($singlePlayerOptions['called_from']==='footer_player'){
    $audioplayerClasses.=' '.DZSAP_VIEW_STICKY_PLAYER_ID;
  }

  if ($its && $its['settings'] && isset($its['settings']['vpconfig']) && $its['settings']['vpconfig']) {
    $aux = DZSZoomSoundsHelper::string_sanitizeToCssClass($its['settings']['vpconfig']);
    $audioplayerClasses .= ' apconfig-' . $aux;






    if (isset($singlePlayerOptions['skin_ap']) && $singlePlayerOptions['skin_ap']) {


      if ($singlePlayerOptions['called_from'] == 'gallery') {

        $audioplayerClasses .= ' ' . $singlePlayerOptions['skin_ap'];
      }


    }



    if (isset($its['settings']['button_aspect']) && $its['settings']['button_aspect'] != 'default') {
      $audioplayerClasses .= ' ' . $its['settings']['button_aspect'];

      if (isset($its['settings']['colorhighlight']) && $its['settings']['colorhighlight']) {
        // TODO: maybe force aspect noir filled ? if aspect noir is set


      }
    }
  }


  if (isset($singleItemInstance['wrapper_image_type']) && $singleItemInstance['wrapper_image_type']) {

    $audioplayerClasses .= ' ' . $singleItemInstance['wrapper_image_type'];
  }




  if (isset($singlePlayerOptions['extra_classes_player'])) {
    $audioplayerClasses .= ' ' . $singlePlayerOptions['extra_classes_player'];
  }

  if ($singlePlayerOptions['called_from'] == 'footer_player' || $singlePlayerOptions['called_from'] == 'player' || $singlePlayerOptions['called_from'] == 'gallery') {


    $audioplayerClasses .= ' ' . $singlePlayerOptions['skin_ap'];
  }


  if (isset($singlePlayerOptions['enable_alternate_layout']) && $singlePlayerOptions['skinwave_mode'] == 'normal' && $singlePlayerOptions['enable_alternate_layout'] == 'on') {
    $audioplayerClasses .= ' alternate-layout';
  }

  if (isset($its['settings']['extra_classes_player'])) {
    $audioplayerClasses .= ' ' . $its['settings']['extra_classes_player'];
  }
  if (isset($its['settings']['skinwave_mode'])) {

    if ($singlePlayerOptions['skinwave_mode'] == 'alternate') {
      $audioplayerClasses .= ' alternate-layout';
    }
    if ($singlePlayerOptions['skinwave_mode'] == 'nocontrols') {
      $audioplayerClasses .= ' skin-wave-mode-nocontrols';
    }
  }

  $audioplayerClasses .= ' ap' . $singleItemInstance['playerId_computed'];

  if (isset($its['settings']) && isset($its['settings']['disable_volume']) && $its['settings']['disable_volume'] == 'on') {
    $audioplayerClasses .= ' disable-volume';
  }

  if (isset($singleItemInstance['extra_classes']) && $singleItemInstance['extra_classes']) {
    $audioplayerClasses .= ' ' . $singleItemInstance['extra_classes'];
  }
  if (isset($singleItemInstance['embedded']) && $singleItemInstance['embedded'] == 'on') {
    $audioplayerClasses .= ' ' . ' is-in-embed-player';
  }

  if (isset($singlePlayerOptions['auto_init_player']) && $singlePlayerOptions['auto_init_player'] == 'on') {
    $audioplayerClasses .= ' auto-init';
  }

  return $audioplayerClasses;
}
function dzsap_view_parseItems_embedAdditionalScripts($playerConfigSettings){



  if(isset($playerConfigSettings['skinwave_comments_enable']) && $playerConfigSettings['skinwave_comments_enable']==='on'){

    wp_enqueue_script('dzsap-player-'.'skinwave_comments_enable',DZSAP_BASE_URL.'audioplayer/parts/helper-functions/helper-functions.js');
  }
  if($playerConfigSettings['skin_ap']==='skin-minimal' || $playerConfigSettings['skin_ap']==='skin-justthumbandbutton' || $playerConfigSettings['skin_ap']==='skin-default' || $playerConfigSettings['skin_ap']==='skin-aria' || $playerConfigSettings['skin_ap']==='skin-redlights' || $playerConfigSettings['skin_ap']==='skin-steel' || $playerConfigSettings['skin_ap']==='skin-minion' || $playerConfigSettings['skin_ap']==='skin-silver' || $playerConfigSettings['skin_ap']==='skin-pro'){
    wp_enqueue_style('dzsap-player-'.$playerConfigSettings['skin_ap'],DZSAP_BASE_URL.'audioplayer/parts/player-skins/player-skin--'.$playerConfigSettings['skin_ap'].'.css');
  }
}

/**
 * @param DzsapView $dzsapView
 * @param string $vpConfigId
 * @param array $vpConfig
 * @param array $its
 */
function dzsap_view_parseItemsAddFooterExtraStyling($dzsapView, $vpConfigId, $vpConfig, $its){
  global $dzsap;
  if (!in_array($vpConfigId, $dzsap->extraCssConsumedConfigurations)) {
    $dzsapView->footer_style .= '.audioplayer-tobe{  opacity:0; }';
    $dzsapView->footer_style .= DZSZoomSoundsHelper::generateCssPlayerCustomColors(array(
      'configId' => $vpConfigId,
      'config' => $vpConfig,
    ));
    $dzsapView->footer_style .= '';



    if (isset($vpConfig['config_extra_css']) && $vpConfig['config_extra_css']) {

      if (in_array(DZSZoomSoundsHelper::sanitize_for_css_class($its['settings']['vpconfig']), $dzsapView->footer_style_configs) == false) {

        $vpConfig['config_extra_css'] = str_replace('$classmain', DZSAP_VIEW_APCONFIG_PREFIX . DZSZoomSoundsHelper::sanitize_for_css_class($vpConfigId), $vpConfig['config_extra_css']);
        $dzsapView->footer_style .= $vpConfig['config_extra_css'];

        array_push($dzsapView->footer_style_configs, DZSZoomSoundsHelper::sanitize_for_css_class($vpConfigId));
      }
    }
  }

}
function dzsap_view_parseItemsInitialSettingsSetup(&$its, &$playerOptions){


  if (isset($playerOptions['single'])) {
    $playerOptions['is_single'] = $playerOptions['single'];
  }





  // -- sanitizing
  if ($playerOptions['wrapper_image'] == '') {
    if (isset($playerOptions['cover']) && $playerOptions['cover']) {
      $playerOptions['wrapper_image'] = $playerOptions['cover'];
    } else {
      $playerOptions['wrapper_image_type'] = '';
    }
  }


  if (isset($its['settings'])) {

    if (isset($its['settings']['enable_views']) == false) {
      $its['settings']['enable_views'] = 'off';
    }
    if (isset($its['settings']['enable_likes']) == false) {
      $its['settings']['enable_likes'] = 'off';
    }
    if (isset($its['settings']['enable_rates']) == false) {
      $its['settings']['enable_rates'] = 'off';
    }
    if (isset($its['settings']['enable_downloads_counter']) == false) {
      $its['settings']['enable_downloads_counter'] = 'off';
    }


    if (isset($playerOptions['enable_views']) && $playerOptions['enable_views'] === 'on') {
      $its['settings']['enable_views'] = 'on';
    }
    if (isset($playerOptions['enable_downloads_counter']) && $playerOptions['enable_downloads_counter'] === 'on') {
      $its['settings']['enable_downloads_counter'] = 'on';
    }

    if (isset($playerOptions['enable_likes']) && $playerOptions['enable_likes'] === 'on') {
      $its['settings']['enable_likes'] = 'on';
    }
    if (isset($playerOptions['enable_rates']) && $playerOptions['enable_rates'] === 'on') {
      $its['settings']['enable_rates'] = 'on';
    }
    if ($playerOptions['is_single'] == 'on' && isset($its['settings']['id']) && $its['settings']['id']) {
      $its['settings']['vpconfig'] = $its['settings']['id'];
    }


    if (isset($its['settings']['enable_alternate_layout']) && $its['settings']['enable_alternate_layout'] === 'on') {
      $playerOptions['enable_alternate_layout'] = 'on';
      $playerOptions['skinwave_mode'] = 'alternate';
    }
  }

  dzsap_view_parseItems_embedAdditionalScripts($its['playerConfigSettings']);


}