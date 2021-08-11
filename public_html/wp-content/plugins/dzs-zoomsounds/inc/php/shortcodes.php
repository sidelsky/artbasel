<?php

function dzsap_shortcode_init_listeners() {

  add_shortcode('dzsap_woo_grid', 'dzsap_shortcode_woo_grid');
  add_shortcode('player_button', 'dzsap_shortcode_player_button');


  add_shortcode(DZSAP_ZOOMSOUNDS_ACRONYM . '_in_lightbox', 'dzsap_shortcode_lightbox');
  add_shortcode('dzsap_wishlist', 'dzsap_shortcode_wishlist');
  add_shortcode('dzsap_search_con', 'dzsap_shortcode_search_con');
  add_shortcode('dzsap_search', 'dzsap_shortcode_search');

  // -- [zoomsounds] in DzsapView.php
}

/**
 * [player_button style="player-but"]label[/player_button]
 * @param $atts
 * @param null $content
 * @return string
 */
function dzsap_shortcode_player_button($atts, $content = null) {


  global $dzsap;

  $fout = '';


  $margs = array(
    'link' => '',
    'style' => '', // -- "btn-zoomsounds" or "player-but"
    'label' => '',
    'icon' => '',
    'color' => '',
    'target' => '',
    'background_color' => '',
    'extra_classes' => '',
    'wrap_object' => 'on', // -- this will allow a inside a html tag ( default on )
    'extraattr' => '',
    'post_id' => '',
  );

  if ($atts) {

    $margs = array_merge($margs, $atts);
  }

  // process
  if ($content) {
    $margs['label'] = $content;
  }

  // END process margs


  $startDomTag = 'div';
  if ($margs['link']) {
    $startDomTag = 'a';
    if ($margs['wrap_object'] == 'on') {
      $startDomTag = 'object';
    }
  }


  $anchorExtraAttr = '';

  if ($margs['link']) {
    $anchorExtraAttr .= ' href="' . $margs['link'] . '"';
  }


  if ($margs['target']) {

    $anchorExtraAttr .= ' target="' . $margs['target'] . '"';
  } else {

    $anchorExtraAttr .= ' target="' . '' . '"';
  }

  $anchorExtraAttr .= ' class="' . $margs['style'] . '';

  if ($margs['style'] === 'player-but') {
    $anchorExtraAttr .= ' dzstooltip-con';
  }


  $anchorExtraAttr .= ' ' . $margs['extra_classes'];

  $anchorExtraAttr .= '"';
  $anchorExtraAttr .= ' style="';

  if ($margs['color']) {
    $anchorExtraAttr .= 'color: ' . $margs['color'] . ';';
  }

  $anchorExtraAttr .= '"';



  if ($margs['post_id']) {

    $anchorExtraAttr .= ' data-post_id="' . $margs['post_id'] . '"';


    if (strpos($margs['extra_classes'], 'dzsap-wishlist-but') !== false) {


      $arr_wishlist = $dzsap->classView->get_wishlist();


      if (in_array($margs['post_id'], $arr_wishlist)) {


        $margs['icon'] = str_replace('fa-star-o', 'fa-star', $margs['icon']);
      }

    }


    $margs['extraattr'] = str_replace('{{posturl}}', get_permalink($margs['post_id']), $margs['extraattr']);
  }

  $anchorExtraAttr .= ' ' . $margs['extraattr'];


  $fout .= '<' . $startDomTag;


  $fout .= $anchorExtraAttr;

  $fout .= '>';


  if ($margs['wrap_object'] == 'on' && $margs['link']) {

    $fout .= ' <a rel="nofollow" href="' . $margs['link'] . '" ' . $anchorExtraAttr . '>';
  }

  if ($margs['style'] == 'player-but') {
    $fout .= '<span class="the-icon-bg';

    if (strpos($margs['extra_classes'], 'dzsap-btn-info') !== false) {
      $fout .= ' tooltip-indicator';
    }

    $fout .= '"></span>';
  }
  if ($margs['style'] == 'btn-zoomsounds') {
    $fout .= '<span class="the-bg" style="';

    if ($margs['background_color']) {
      $fout .= 'background-color: ' . $margs['background_color'] . ';';
    }

    $fout .= '"></span>';
  }

  if (strpos($margs['icon'], 'fa-') !== false) {
    wp_enqueue_style('fontawesome', DZSAP_URL_FONTAWESOME_EXTERNAL);
  }

  if ($margs['style'] == 'player-but') {
    $fout .= '<i class="svg-icon';


    if (strpos($margs['extra_classes'], 'dzsap-btn-info') !== false) {
      $fout .= ' tooltip-indicator';
    }

    $fout .= ' fa ' . $margs['icon'] . '"></i>';
  }
  if ($margs['style'] == 'btn-zoomsounds') {
    $fout .= '<span class="the-icon"><i class="fa ' . $margs['icon'] . '"></i></span>';
  }


  if ($margs['style'] == 'btn-zoomsounds') {
    $fout .= '<span class="the-label ">' . $margs['label'] . '</span>';
  }


  if ($margs['style'] == 'player-but') {

    $tooltip_class = 'dzstooltip arrow-from-start transition-slidein arrow-bottom skin-black align-right talign-end style-rounded color-dark-light';
    $fout .= '<span class="' . $tooltip_class . '" style="width: auto; white-space: nowrap;"><span class="dzstooltip--inner" style="margin-right: -10px;">' . $margs['label'] . '</span></span>';
  }


  if ($margs['wrap_object'] == 'on' && $margs['link']) {

    $fout .= '</a>';
  }
  $fout .= '</' . $startDomTag . '>';



  return $fout;

}


function dzsap_shortcode_lightbox($atts, $content = null) {

  global $dzsap;
  $fout = '';

  $dzsap->front_scripts();

  DZSZoomSoundsHelper::enqueueUltibox();

  $args = array(
    'id' => 'default'
  , 'db' => ''
  , 'category' => ''
  , 'width' => ''
  , 'height' => ''
  , 'gallerywidth' => '800'
  , 'galleryheight' => '370'
  );
  $args = array_merge($args, $atts);
  $fout .= '<div class="ultibox"';

  if ($args['width'] != '') {
    $fout .= ' data-width="' . $args['width'] . '"';
  }
  if ($args['height'] != '') {
    $fout .= ' data-height="' . $args['height'] . '"';
  }
  if ($args['gallerywidth'] != '') {
    $fout .= ' data-bigwidth="' . $args['gallerywidth'] . '"';
  }
  if ($args['galleryheight'] != '') {
    $fout .= ' data-bigheight="' . $args['galleryheight'] . '"';
  }

  $fout .= 'data-src="' . DZSAP_BASE_URL . 'inc/php/retriever.php?id=' . $args['id'] . '" data-type="ajax">' . $content . '</div>';

  wp_enqueue_script('dzsap-lightbox', DZSAP_BASE_URL.'inc/js/shortcodes/lightbox.js');
  return $fout;
}



function dzsap_shortcode_wishlist($pargs = array()) {

  global $dzsap;

  $margs = array();


  if (!is_array($pargs)) {
    $pargs = array();
  }


  $arr_wishlist = $dzsap->classView->get_wishlist();




  $fout = '';
  $fout .= '<div class="dzsap-wishlist">';


  if (get_current_user_id()) {

    foreach ($arr_wishlist as $pl) {


      $fout .= $dzsap->classView->shortcode_player(array(
        'source' => $pl,
        'called_from' => 'shortcode_wishlist',
        'config' => 'wishlist-player',
      ));
    }
  } else {
    $fout .= '<div class="dzsap-warning warning">' . esc_html__("You need to be logged in to have a wishlist.") . '</div>';
  }

  $fout .= '</div>';

  return $fout;
}

/** [dzsap_search_con]
 * @param array $pargs
 * @return string
 */
function dzsap_shortcode_search_con($pargs = array()) {



  $margs = array(
    'extra_classes' => 'search-align-right',
  );

  if (!is_array($pargs)) {
    $pargs = array();
  }

  $margs = array_merge($margs, $pargs);


  $fout = '';

  $fout .= '<div class="zoomsounds-search-con ' . $margs['extra_classes'] . '">';
  $fout .= dzsap_shortcode_dzsap_search($margs);
  $fout .= '</div>';

  return $fout;
}

/**
 * [dzsap_search]
 * @param array $pargs
 * @return string
 */
function dzsap_shortcode_dzsap_search($pargs = array()) {

  $margs = array(
    'extra_classes' => '',
    'target' => '',
  );

  if (!is_array($pargs)) {
    $pargs = array();
  }


  $margs = array_merge($margs, $pargs);


  $fout = '';

  $fout .= '<input class="zoomsounds-search-field ' . $margs['extra_classes'] . '" placeholder="' . esc_html__('Search tracks...', 'dzsap') . '"/>';

  return $fout;
}

