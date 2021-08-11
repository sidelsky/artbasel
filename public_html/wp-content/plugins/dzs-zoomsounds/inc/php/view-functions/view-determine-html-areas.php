<?php

function dzsap_view_determineHtmlAreas_controlsAfterConControls($playerConfig, $playerOptions) {


  $afterConControls = '';
  if (isset($playerConfig['settings_extrahtml_after_con_controls_from_config']) && $playerConfig['settings_extrahtml_after_con_controls_from_config']) {


    $che['settings_extrahtml_after_con_controls_from_config'] = dzsap_sanitize_from_extra_html_props($playerConfig['settings_extrahtml_after_con_controls_from_config'], '', $playerOptions);
    $afterConControls .= '<div hidden class="feed-dzsap feed-dzsap-after-con-controls">';
    $afterConControls .= '<span class="con-after-con-controls">';
    $afterConControls .= $che['settings_extrahtml_after_con_controls_from_config'];
    $afterConControls .= '</span>';
    $afterConControls .= '</div>';
  }

  return $afterConControls;


}

function dzsap_view_determineHtmlAreas_controlsAfterPlayPause($playerConfig) {

  $afterPlayPause = '';
  if (isset($playerConfig['settings_extrahtml_after_playpause_from_config']) && $playerConfig['settings_extrahtml_after_playpause_from_config']) {

    $afterPlayPause .= '<div hidden class="feed-dzsap feed-dzsap-after-playpause">';
    $afterPlayPause .= '<span class="con-after-playpause">';
    $afterPlayPause.=dzsap_sanitize_from_extra_html_props($playerConfig['settings_extrahtml_after_playpause_from_config']);
    $afterPlayPause .= '</span>';
    $afterPlayPause .= '</div>';
  }

  return $afterPlayPause;

}

function dzsap_view_determineHtmlAreas_controlsRight($dzsap, $playerAttributes, $playerConfig, $che_post, $playlistAndPlayerOptions) {

  global $dzsap;


  $post_content = '';

  if (isset($playerAttributes['post_content']) && $playerAttributes['post_content']) {
    $post_content = $playerAttributes['post_content'];
  } else {
    if (isset($playerAttributes['content_inner']) && $playerAttributes['content_inner']) {
      $post_content = $playerAttributes['content_inner'];
    }
  }


  $stringInfoButton = '';
  $str_multishare_btn = '';
  if ((isset($playlistAndPlayerOptions['menu_right_enable_info_btn']) && $playlistAndPlayerOptions['menu_right_enable_info_btn'] == 'on') && isset($playerAttributes) && $post_content) {
    // -- infobtn / info button
    $stringInfoButton .= do_shortcode('[player_button extra_classes="dzsap-btn-info" style="player-but"  icon="fa-info" link=""]' . wpautop(do_shortcode($post_content)) . '[/player_button]');
  }



  // -- com
  if (isset($playlistAndPlayerOptions['menu_right_enable_multishare']) && $playlistAndPlayerOptions['menu_right_enable_multishare'] == 'on') {
    $dzsap->isEnableMultisharer = true;
    $str_multishare_btn .= ' <div class="player-but sharer-dzsap-but dzsap-multisharer-but"><div class="the-icon-bg"></div>{{svg_share_icon}}</div>';
  }

  $extraHtmlRightControls = '';

  if (isset($playerOptions['extra_html_in_controls_right']) && $playerOptions['extra_html_in_controls_right']) {
    $extraHtmlRightControls .= $playerOptions['extra_html_in_controls_right'];
  }


  if (isset($playerConfig['js_settings_extrahtml_in_float_right_from_config']) && $playerConfig['js_settings_extrahtml_in_float_right_from_config'] && !(isset($playerAttributes['extrahtml_in_float_right_from_player']) && $playerAttributes['extrahtml_in_float_right_from_player'])) {
    $extraHtmlRightControls .= $playerConfig['js_settings_extrahtml_in_float_right_from_config'];
  }


  if ((isset($playerAttributes['extra_html_in_controls_right']) && $playerAttributes['extra_html_in_controls_right'])) {
    $playerAttributes['extra_html_in_controls_right'] = dzsap_sanitize_from_extra_html_props($playerAttributes['extra_html_in_controls_right'], $playerAttributes['playerid'], $playerAttributes);
    $extraHtmlRightControls .= '' . (($playerAttributes['extra_html_in_controls_right'])) . '';
    if ($che_post) {
      $extraHtmlRightControls = '' . dzsap_sanitize_to_extra_html($extraHtmlRightControls, $playerAttributes) . '';
    }
  }
  if (isset($playerAttributes['extrahtml_in_float_right_from_player']) && $playerAttributes['extrahtml_in_float_right_from_player']) {
    $extraHtmlRightControls .= '' . $playerAttributes['extrahtml_in_float_right_from_player'] . '';
  }
  if (strpos($extraHtmlRightControls, 'dzsap-multisharer-but') !== false) {
    $dzsap->isEnableMultisharer = true;
  }

  $extraHtmlRightControlsFout = '';
  if (isset($playlistAndPlayerOptions) && ($stringInfoButton || $str_multishare_btn || $extraHtmlRightControls)) {
    // -- extra-html in right controls set in parse_items


    $extraHtmlRightControlsFout .= '<div class="feed-dzsap feed-dzsap--extra-html-in-controls-right" style="opacity:0;">';

    $extraHtmlRightControlsFout .= $stringInfoButton;
    $extraHtmlRightControlsFout .= $str_multishare_btn;
    $extraHtmlRightControlsFout .= do_shortcode(dzsap_sanitize_from_extra_html_props(DZSZoomsoundsHelper::sanitizeForShortcodeAttr($extraHtmlRightControls, $playerAttributes), $playerAttributes['playerid'], $playerAttributes));

    $extraHtmlRightControlsFout .= '</div>';
  }

  return $extraHtmlRightControlsFout;

}

function dzsap_view_determineHtmlAreas_controlsLeft($playerAttributes) {


  $stringControlsLeft = '';

  // -- controls Left
  if ((isset($playerAttributes['extra_html_in_controls_left']) && $playerAttributes['extra_html_in_controls_left'])) {

    $stringControlsLeft = '<div class="feed-dzsap feed-dzsap--extra-html-in-controls-left" style="opacity:0;">' . $playerAttributes['extra_html_in_controls_left'] . '</div>';
  }
  // -- end controls left

  return $stringControlsLeft;

}

function dzsap_view_determineHtmlAreas_bottomLeft($dzsap, $playerAttributes, $playerOptions, $playlistAndPlayerOptions, $playerConfig, $playerid) {


  $stringExtraHtml_leftContent = '';
  if ($playlistAndPlayerOptions['enable_likes'] == 'on') {
    $stringExtraHtml_leftContent .= $dzsap->mainoptions['str_likes_part1'];

    if (isset($_COOKIE["dzsap_likesubmitted-" . $playerid])) {
      $stringExtraHtml_leftContent = str_replace('<span class="btn-zoomsounds btn-like">', '<span class="btn-zoomsounds btn-like active">', $stringExtraHtml_leftContent);
    }
  }






  if ((isset($playerAttributes['enable_download_button']) && $playerAttributes['enable_download_button'] == 'on')) {

    include_once DZSAP_BASE_PATH . 'inc/php/view-parseItems/parse-items--buttons.php';
    $stringExtraHtml_leftContent .= dzsap_parse_items__button_download_generate($dzsap, $playerAttributes, $playerid);

  }

  // -- end download button



  if ($playerOptions['is_single'] == 'on') {


    if (($playerConfig['enable_embed_button'] == 'in_lightbox' || $playerConfig['enable_embed_button'] == 'in_extra_html')) {

      if (isset($playerOptions['embed_code']) && $playerOptions['embed_code']) {

        $stringExtraHtml_leftContent .= '<span class=" btn-zoomsounds dzstooltip-con for-hover btn-embed">  ';


        $stringExtraHtml_leftContent .= '<span class="tooltip-indicator"><span class="the-icon"><i class="fa fa-share"></i></span><span class="the-label ">' . esc_html__('Embed', DZSAP_ID) . '</span></span>';


        $stringExtraHtml_leftContent .= '<span class="dzstooltip transition-slidein arrow-bottom talign-start style-rounded color-dark-light " style="width: 350px; "><span class="dzstooltip--inner"><span style="max-height: 150px; font-size: 8px; overflow:hidden; display: block; white-space: normal; font-family: monospace, monospace;font-weight: normal;     display: -webkit-box;-webkit-line-clamp: 9; -webkit-box-orient: vertical;">{{embed_code}}</span> <span class="copy-embed-code-btn"><i class="fa fa-clipboard"></i> ' . esc_html__('Copy Embed', DZSAP_ID) . '</span> </span></span> ';


        $stringExtraHtml_leftContent .= '</span>';
      }
    }
  }


  return $stringExtraHtml_leftContent;
}

function dzsap_view_determineHtmlAreas_bottom($dzsap, &$playerAttributes, $playlistAndPlayerOptions, $playerid) {
  $extraHtmlBottom = '';
  if (isset($playerAttributes['extra_html_left'])) {
    $playerAttributes['extra_html_left'] = dzs_esc__($playerAttributes['extra_html_left']);
  }


  if ((isset($playerAttributes['extra_html_left']) && $playerAttributes['extra_html_left'])) {
    $extraHtmlBottom .= '<div class="extra-html--left">' . $playerAttributes['extra_html_left'] . '</div>';
    $extraHtmlBottom .= '<-- END .extra-html--left -->';
  }


  if ($playlistAndPlayerOptions['enable_rates'] == 'on') {


    // -- 1 to 5
    $decInitialRateIndex = get_post_meta($playerid, '_dzsap_rate_index', true);

    // -- 1 to 5
    if ($decInitialRateIndex == '') {
      $decInitialRateIndex = 0;
    } else {
      $decInitialRateIndex = floatval($decInitialRateIndex) / 5;
    }
    if ($decInitialRateIndex > 5) {
      $decInitialRateIndex = 5;
    }

    $percInitialRateIndex = floatval(($decInitialRateIndex) * 100);


    $extraHtmlBottom .= '<div class="star-rating-con" data-initial-rating-index="' . $decInitialRateIndex . '">';



    $stringStars = '<span class="star-rating-bg "><span class="rating-inner">{{starssvg}}</span></span>';


    $stringStars .= '<div class=\'star-rating-clip star-rating-set-clip\' style=\'width: ' . $percInitialRateIndex . '%;\'>
                  <div class=\'star-rating-prog\'>{{starssvg}}</div>
                </div>';
    $stringStars .= '<div class=\'star-rating-clip star-rating-prog-clip\' style=\'\'>
                  <div class=\'star-rating-prog\'>{{starssvg}}</div>
                </div>';



    $stringStars = str_replace('{{starssvg}}', '&#9733;&#9733;&#9733;&#9733;&#9733;', $stringStars);

    $extraHtmlBottom .= $stringStars;
    $extraHtmlBottom .= '</div>';


    wp_enqueue_script('dzsap-part-ratings', DZSAP_BASE_URL . 'audioplayer/parts/star-ratings/dzsap-star-ratings.js', array(), DZSAP_VERSION);
    wp_enqueue_style('dzsap-part-ratings', DZSAP_BASE_URL . 'audioplayer/parts/star-ratings/dzsap-star-ratings.css', array(), DZSAP_VERSION);
  }


  if ($playlistAndPlayerOptions['enable_views'] == 'on') {
    $extraHtmlBottom .= $dzsap->mainoptions['str_views'];


    $aux = get_post_meta($playerid, DZSAP_DB_VIEWS_META_NAME, true);
    if ($aux == '') {
      $aux = 0;
    }
    $extraHtmlBottom = str_replace('{{get_plays}}', $aux, $extraHtmlBottom);
  }
  if ($playlistAndPlayerOptions['enable_downloads_counter'] == 'on') {
    $extraHtmlBottom .= $dzsap->mainoptions['str_downloads_counter'];
    $aux = get_post_meta($playerid, '_dzsap_downloads', true);
    if ($aux == '') {
      $aux = 0;
    }
    $extraHtmlBottom = str_replace('{{get_downloads}}', $aux, $extraHtmlBottom);
  }


  if ($playlistAndPlayerOptions['enable_likes'] == 'on') {
    $extraHtmlBottom .= $dzsap->mainoptions['str_likes_part2'];

    $nr_likes = DZSZoomSoundsHelper::get_likes_for_track($playerid);


    if ($nr_likes == '' || $nr_likes == '-1') {
      $nr_likes = 0;
    }
    $extraHtmlBottom = str_replace('{{get_likes}}', $nr_likes, $extraHtmlBottom);
  }


  if ($playlistAndPlayerOptions['enable_rates'] == 'on') {
    $extraHtmlBottom .= $dzsap->mainoptions['str_rates'];
    $aux = get_post_meta($playerid, '_dzsap_rate_nr', true);
    if ($aux == '') {
      $aux = 0;
    }
    $extraHtmlBottom = str_replace('{{get_rates}}', $aux, $extraHtmlBottom);


    if (isset($_COOKIE['dzsap_ratesubmitted-' . $playerid])) {
      $extraHtmlBottom .= '{{ratesubmitted=' . $_COOKIE['dzsap_ratesubmitted-' . $playerid] . '}}';
    };
  }


  if ((isset($playerAttributes['extra_html']) && $playerAttributes['extra_html'])) {
    $extraHtmlBottom .= '' . $playerAttributes['extra_html'];
  }

  if (strpos($extraHtmlBottom, '<i class="fa') !== false) {
    $url = DZSAP_URL_FONTAWESOME_EXTERNAL;
    if ($dzsap->mainoptions['fontawesome_load_local'] == 'on') {
      $url = DZSAP_BASE_URL . 'libs/fontawesome/font-awesome.min.css';
    }
    wp_enqueue_style('fontawesome', $url);

  }


  return $extraHtmlBottom;
}