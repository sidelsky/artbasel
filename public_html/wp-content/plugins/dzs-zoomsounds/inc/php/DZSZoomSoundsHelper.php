<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 04/05/2019
 * Time: 16:11
 */


if (!defined('ABSPATH')) // Or some other WordPress constant
  exit;

if (!function_exists('')) {
  function esc_some_html__($string, $arguments) {
    return wp_kses(sprintf(esc_html__($string, DZSAP_ID), $arguments
    ), array(
      'p' => array(
        'class' => array(),
        'style' => array(),
      ),
      'strong' => array(),
      'em' => array(),
      'br' => array(),
      'a' => array(
        'href' => array(),
        'target' => array(),
        'style' => array(),
        'class' => array(),
      ),
      'span' => array(
        'style' => array(),
        'class' => array(),
      ),
      'i' => array(
        'style' => array(),
        'class' => array(),
      ),
    ));
  }
}


class DZSZoomSoundsHelper {

  /**
   * @param string $stringClass
   * @return string|string[]
   */
  public static function string_sanitizeToCssClass($stringClass) {
    return str_replace(' ', '-', $stringClass);
  }

  public static function view_getSongNameFromComputed($source, $che = array()) {

    $songName = '';
    $home_url = get_home_url();


    if (strpos($source, $home_url) !== false) {
      $mp3path = str_replace($home_url, ABSPATH, $source);
      if (function_exists('id3_get_tag')) {
        $tag = id3_get_tag($mp3path);
      } else {
        if (dzsap_get_songname_from_attachment($che)) {
          $songName = dzsap_get_songname_from_attachment($che);
        }

      }
    } else {
      // -- outer domain source;
      if (dzsap_get_songname_from_attachment($che)) {
        $songName = dzsap_get_songname_from_attachment($che);
      }
    }
    return $songName;
  }

  /**
   * sanitize for shortcode
   * @param $stringSource
   * @param array $che
   * @return string|string[]
   */
  public static function sanitizeForShortcodeAttr($stringSource, $che = array()) {

    $stringSource = str_replace('{{lsqb}}', '[', $stringSource);
    $stringSource = str_replace('{{rsqb}}', ']', $stringSource);
    $stringSource = str_replace('{{quote}}', '"', $stringSource);
    $stringSource = str_replace('&#8221;', '', $stringSource);


    $stringSource = str_replace('{{linkedid}}', '', $stringSource);

    global $post;

    $pid = '';


    if ($post) {
      $pid = $post->ID;
    }


    $stringSource = str_replace('{{postid}}', $pid, $stringSource);

    $lab = 'itunes_link';
    if (isset($che[$lab])) {
      $stringSource = str_replace('{{' . $lab . '}}', $che[$lab], $stringSource);
    } else {

      $stringSource = str_replace('{{' . $lab . '}}', '', $stringSource);
    }
    $stringSource = str_replace('&#8221;', '', $stringSource);


    return $stringSource;
  }

  public static function adminSystemCheckSupportedOrNotEcho($isCondition) {

    if ($isCondition) {
      echo '<div class="setting-text-ok"><i class="fa fa-check"></i> ' . '' . esc_html__("supported") . '</div>';
    } else {
      echo '<div class="setting-text-notok">' . '' . esc_html__("not supported") . '</div>';
    }
  }

  /**
   * get main items in $dzsap->mainitems
   * used in various options for normal mode
   * used for slidersAdmin in legacy mode
   */
  public static function dbReadMainItems($dzsap) {
    if ($dzsap->db_has_read_mainitems == false) {

      $currDb = '';
      if (isset($_GET['dbname'])) {
        $dzsap->currDb = $_GET['dbname'];
        $currDb = $_GET['dbname'];
      }


      if ($dzsap->mainoptions['playlists_mode'] == 'normal') {


        $tax = DZSAP_TAXONOMY_NAME_SLIDERS;


        $terms = get_terms($tax, array(
          'hide_empty' => false,
        ));


        $dzsap->mainitems = array();
        foreach ($terms as $tm) {

          if ($tm && is_object($tm)) {

            $aux = array(
              'label' => $tm->name,
              'value' => $tm->slug,
              'term_id' => $tm->term_id,
            );

            array_push($dzsap->mainitems, $aux);
          }
        }

      } else {

        dzsap_legacy_sliders__getSliders($currDb);
      }

      $dzsap->db_has_read_mainitems = true;


      // -- legacy / *deprecated
      if (!$dzsap->mainoptions['playlists_mode'] == 'normal') {
        $dbname_mainitems = DZSAP_DBNAME_MAINITEMS;
        if ($currDb != 'main' && $currDb != '') {
          $dbname_mainitems .= '-' . $currDb;
          $dzsap->mainitems = get_option($dbname_mainitems);
        }

        if ($dzsap->mainitems == '') {
          return;
        }
      }
    }

  }

  /**
   * @param DZSAudioPlayer $dzsap
   * @param string $targetSlug
   */
  public static function legacySlidersAdmin_getSliderIdFromSlug($dzsap, $targetSlug) {


    foreach ($dzsap->mainitems_configs as $lab => $vpConfig) {
      if ($vpConfig['settings']['id'] === $targetSlug) {
        return $lab;
      }
    }

    return null;

  }

  public static function admin_legacySliders_determineCurrSlider($dzsap) {

    if (isset($_GET[DZSAP_LEGACY_SLIDERS__GET_CURRSLIDER])) {
      $dzsap->currSlider = $_GET[DZSAP_LEGACY_SLIDERS__GET_CURRSLIDER];
    } else {
      $dzsap->currSlider = 0;
    }


    if (isset($_GET[DZSAP_LEGACY_SLIDERS__GET_CURRSLIDER_FINDER])) {

      $findId = DZSZoomSoundsHelper::legacySlidersAdmin_getSliderIdFromSlug($dzsap, $_GET[DZSAP_LEGACY_SLIDERS__GET_CURRSLIDER_FINDER]);

      if ($findId !== null) {
        $dzsap->currSlider = $findId;
      }
    }
  }

  /**
   * @param DZSAudioPlayer $dzsap
   */
  public static function dbReadOptions($dzsap) {

    global $pagenow;


    if (isset($_GET) && isset($_GET['dzsap_debug']) && $_GET['dzsap_debug'] == 'on') {
      $dzsap->debug = true;
    }


    $dzsap->mainitems_configs = get_option(DZSAP_DBNAME_AUDIO_PLAYERS_CONFIGS);


    $dzsap->options_item_meta = include(DZSAP_BASE_PATH . "configs/options-item-meta.php");


    $dzsap->options_item_meta_sanitized = array_merge(array(), $dzsap->options_item_meta);
    foreach ($dzsap->options_item_meta_sanitized as $lab => $val) {

      if (isset($val['name'])) {

        if (strpos($val['name'], DZSAP_META_OPTION_PREFIX . 'item_') !== false) {
          $newname = str_replace(DZSAP_META_OPTION_PREFIX . 'item_', '', $val['name']);
          $dzsap->options_item_meta_sanitized[$lab]['name'] = $newname;
        } else {

          if (strpos($val['name'], DZSAP_META_OPTION_PREFIX . '') !== false) {
            $newname = str_replace(DZSAP_META_OPTION_PREFIX . '', '', $val['name']);
            $dzsap->options_item_meta_sanitized[$lab]['name'] = $newname;
          }
        }
      }

    }


    // -- lets us import default serialized vpconfigs
    if ($dzsap->mainitems_configs == '' || (is_array($dzsap->mainitems_configs) && count($dzsap->mainitems_configs) == 0)) {

      $dzsap->mainitems_configs = array();
      // -- the default configs
      $vpConfigsDefaultSerialized = include_once(DZSAP_BASE_PATH . 'configs/vpconfigs_default_serialized.php');
      $dzsap->mainitems_configs = unserialize($vpConfigsDefaultSerialized);


      // TODO: saving
      update_option(DZSAP_DBNAME_AUDIO_PLAYERS_CONFIGS, $dzsap->mainitems_configs);
    }
    $dzsap->vpconfigsstr = '';

    $i23 = 0;


    foreach ($dzsap->mainitems_configs as $vpconfig) {


      if (isset($vpconfig['settings']['id'])) {

        $dzsap->vpconfigsstr .= '<option data-sliderlink="' . $i23 . '" value="' . $vpconfig['settings']['id'] . '">' . $vpconfig['settings']['id'] . '</option>';

      } else {

      }

      $i23++;
    }


    if (defined('dzsap_db_sample_data')) {
      $dzsap->sample_data = unserialize(dzsap_db_sample_data);
    } else {
      $dzsap->sample_data = get_option(DZSAP_DBNAME_SAMPLEDATA);
    }


    $defaultOpts = $dzsap->general_assets['default_options'];


    $dzsap->mainoptions = get_option(DZSAP_DBNAME_OPTIONS);


    // -- default opts / inject into db
    if ($dzsap->mainoptions == '') {
      // -- new install

      $defaultOpts['playlists_mode'] = 'normal';


      $dzsap->mainoptions = $defaultOpts;
      update_option(DZSAP_DBNAME_OPTIONS, $dzsap->mainoptions);
    } else {

      // -- previous install

      $defaultOpts['playlists_mode'] = 'legacy';
    }

    $dzsap->mainoptions = array_merge($defaultOpts, $dzsap->mainoptions);

    // -- translation stuff
    load_plugin_textdomain(DZSAP_ID, false, basename(dirname(__FILE__)) . '/languages');


    if ($dzsap->mainoptions['i18n_buy'] == '') {
      $dzsap->mainoptions['i18n_buy'] = esc_html__("Buy", DZSAP_ID);
    }
    if ($dzsap->mainoptions['i18n_play'] == '') {
      $dzsap->mainoptions['i18n_play'] = esc_html__("Play", DZSAP_ID);
    }
    if ($dzsap->mainoptions['i18n_title'] == '') {
      $dzsap->mainoptions['i18n_title'] = esc_html__("Title", DZSAP_ID);
    }
    if ($dzsap->mainoptions['i18n_register_to_download'] == '') {
      $dzsap->mainoptions['i18n_register_to_download'] = esc_html__("Register to download", DZSAP_ID);
    }


    if ($pagenow == 'admin.php' || $dzsap->mainoptions['always_embed'] == 'on') {
      if ($dzsap->mainoptions['playlists_mode'] == 'legacy') {
        $dzsap->db_read_mainitems();
      }
    }
  }

  /**
   * replace {{source}} {{postid}} {{thumb}}
   * @param $aux
   * @param WP_Post $argpo
   * @return string|string[]
   */
  public static function sanitize_from_shortcode_pattern($aux, $argpo) {

    $a = array();
    $b = array();
    $src = DZSZoomSoundsHelper::media_getTrackSource($argpo->ID, $a, $b);


    $type = 'audio';


    if ($argpo->post_type == DZSAP_REGISTER_POST_TYPE_NAME) {
      $type = get_post_meta($argpo->ID, DZSAP_META_OPTION_PREFIX . 'type', true);
    }


    $aux = str_replace('{{source}}', $src, $aux);
    $aux = str_replace('{{postid}}', $argpo->ID, $aux);
    $aux = str_replace('{{postcontent}}', do_shortcode($argpo->post_content), $aux);
    $aux = str_replace('{{postcontent_filtered}}', $argpo->post_content_filtered, $aux);
    $aux = str_replace('{{thumb}}', DZSZoomSoundsHelper::view_getComputedDzsapThumbnail($argpo->ID), $aux);
    $aux = str_replace('{{type}}', $type, $aux);

    return $aux;
  }


  public static function sanitizeToValidObjectLabel($s) {
    return preg_replace("/[^a-zA-Z0-9\-]+/", "", $s);
  }

  public static function get_post_thumb_src($it_id) {
    $imgsrc = wp_get_attachment_image_src(get_post_thumbnail_id($it_id), "full");

    if ($imgsrc && $imgsrc[0]) {

      return $imgsrc[0];
    }
    return '';
  }

  public static function sanitizeToPx($dimension) {
    if ($dimension !== '' && strpos($dimension, 'px') === false && strpos($dimension, 'auto') === false && strpos($dimension, 'em') === false) {
      return $dimension . 'px';
    }

    return $dimension;
  }


  /**
   * returns TRUE if it has any extra-html at all ( including right-html etc )
   * @param $singleItemInstance
   * @param $argPlaylistOptions
   * @param $argPlayerOptions
   * @param $argPlayerConfig
   * @return bool
   */
  public static function isPlayerHasExtrahtml($singleItemInstance, $argPlaylistOptions, $argPlayerOptions, $argPlayerConfig) {


    $playlistOptions = array(
      'enable_downloads_counter' => 'off'
    );
    $playlistAndPlayerOptions = array(
      'enable_rates' => 'off',
      'enable_views' => 'off',
      'enable_likes' => 'off',
      'enable_download_button' => 'off',
      'menu_right_enable_info_btn' => 'off',
      'enable_embed_button' => 'off',
      'menu_right_enable_multishare' => 'off',
      'js_settings_extrahtml_in_float_right_from_config' => '',
      'js_settings_extrahtml_in_bottom_controls_from_config' => '',
    );
    $playerConfig = array(
      'enable_embed_button' => 'off',
      'settings_extrahtml_after_con_controls_from_config' => '',
    );

    if (count($playerConfig)) {
      $playerConfig = array_merge($playerConfig, $argPlayerConfig);
    }

    if (count($argPlaylistOptions)) {
      $playlistOptions = array_merge($playlistOptions, $argPlaylistOptions);
    }
    $playlistAndPlayerOptions = array_merge($playlistAndPlayerOptions, $playlistOptions);
    $playlistAndPlayerOptions = array_merge($playlistAndPlayerOptions, $argPlayerConfig);
    $playlistAndPlayerOptions = array_merge($playlistAndPlayerOptions, $argPlayerOptions);


    if (
      $playlistAndPlayerOptions['enable_views'] == 'on' ||
      $playlistOptions['enable_downloads_counter'] == 'on' ||
      $playlistAndPlayerOptions['enable_likes'] == 'on' ||
      $playlistOptions['enable_rates'] == 'on' ||
      $playlistAndPlayerOptions['menu_right_enable_info_btn'] == 'on' ||
      ($playerConfig['enable_embed_button'] === 'in_extra_html' || $playerConfig['enable_embed_button'] === 'in_lightbox') ||

      (isset($singleItemInstance['extra_html']) && $singleItemInstance['extra_html'])
      ||

      $playlistAndPlayerOptions['menu_right_enable_multishare'] == 'on' ||
      $playlistAndPlayerOptions['enable_download_button'] == 'on' ||
      (isset($singleItemInstance['enable_download_button']) && $singleItemInstance['enable_download_button'] == 'on') ||
      $playlistAndPlayerOptions['js_settings_extrahtml_in_float_right_from_config'] ||
      $playlistAndPlayerOptions['js_settings_extrahtml_in_bottom_controls_from_config'] ||
      $playerConfig['settings_extrahtml_after_con_controls_from_config'] ||

      (isset($singleItemInstance['extrahtml_in_float_right_from_player']) && $singleItemInstance['extrahtml_in_float_right_from_player'])
      ||

      (isset($singleItemInstance['extra_html_left']) && $singleItemInstance['extra_html_left'])

    ) {


      return true;
    }

    return false;

  }

  public static function get_views_for_track($argid) {
    return get_post_meta($argid, DZSAP_DB_VIEWS_META_NAME, true);
  }

  public static function sort_commnr($a, $b) {
    $key = 'commnr';
    return $b[$key] - $a[$key];
  }

  public static function get_likes_for_track($argid) {
    if (get_post_meta($argid, DZSAP_DB_LIKES_META_NAME, true)) {
      return get_post_meta($argid, DZSAP_DB_LIKES_META_NAME, true);
    } else {
      return 0;
    }
  }

  /**
   * @param int $argid
   * @return int|mixed
   */
  public static function get_downloads_for_track($argid) {
    if (get_post_meta($argid, DZSAP_DB_DOWNLOADS_META_NAME, true)) {
      return get_post_meta($argid, DZSAP_DB_DOWNLOADS_META_NAME, true);
    } else {
      return 0;
    }
  }

  /**
   * @param array $pargs
   * @param bool $isForPlayer
   * @return string
   */
  public static function generate_embed_code($pargs = array(), $isForPlayer = true) {

    $margs = array(
      'extra_classes' => 'search-align-right',
      'call_from' => 'default',
      'enc_margs' => '',
      'playlistId' => '', // -- only for playlists
    );

    $embed_code = '';
    if (!is_array($pargs)) {
      $pargs = array();
    }
    $margs = array_merge($margs, $pargs);


    if ($isForPlayer) {

      $embed_code = '<iframe src=\'' . site_url() . '?action=embed_zoomsounds&type=player&margs=' . urlencode($margs['enc_margs']) . '\' style="overflow:hidden; transition: height 0.3s ease-out;" width="100%" height="' . DZSAP_VIEW_EMBED_IFRAME_HEIGHT . '" scrolling="no" frameborder="0"></iframe>';
      $embed_code = str_replace('"', "'", $embed_code);
      $embed_code = $embed_code;
    } else {

      $embed_code = '<iframe src=\'' . site_url() . '?action=embed_zoomsounds&type=gallery&id=' . urlencode($margs['playlistId']) . '\' style="overflow:hidden; transition: height 0.3s ease-out;" width="100%" height="' . DZSAP_VIEW_EMBED_IFRAME_HEIGHT . '" scrolling="no" frameborder="0"></iframe>';
    }

    return htmlentities($embed_code, ENT_QUOTES);

  }

  public static function sanitize_term_slug_to_id($arg, $taxonomy_name = 'dzsvideo_category') {


    if (is_numeric($arg)) {

    } else {

      $term = get_term_by('slug', $arg, $taxonomy_name);

      if ($term) {
        $arg = $term->term_id;
      }

    }


    return $arg;
  }

  public static function user_has_role_cap($u, $role_or_cap) {


    //$u->roles Wrong way to do it as in the accepted answer.
    $roles_and_caps = $u->get_role_caps(); //Correct way to do it as wp do multiple checks to fetch all roles

    if (isset ($roles_and_caps[$role_or_cap]) and $roles_and_caps[$role_or_cap] === true) {
      return true;
    }

  }

  public static function encode_to_number($urlString) {
    if (strpos($urlString, '?')) {
      $exploda = explode('?', $urlString);
      $urlString = $exploda[0];
    }

    return substr(sprintf("%u", crc32($urlString)), 0, 8);
  }

  public static function sanitize_from_meta_textarea($arg) {
    $arg = stripslashes($arg);
    $arg = str_replace('{{quots}}', '\'', $arg);
    return $arg;
  }


  public static function get_avatar_url($arg) {
    preg_match("/src='(.*?)'/i", $arg, $matches);
    if (isset($matches[1])) {
      return $matches[1];
    }
    return '';
  }

  public static function enqueueMainScrips() {

    $js_url = DZSAP_URL_AUDIOPLAYER . "audioplayer.js";
    $css_url = DZSAP_URL_AUDIOPLAYER . "audioplayer.css";


    global $dzsap;
    if ($dzsap->mainoptions['enable_ie11_compatibility'] === 'on') {
      $js_url = DZSAP_URL_AUDIOPLAYER . "deprecated/audioplayer.ie11.js";
    }

    wp_enqueue_script(DZSAP_ID, $js_url, array('jquery'), DZSAP_VERSION);
    wp_enqueue_style(DZSAP_ID, $css_url, array(), DZSAP_VERSION);
  }


  /**
   * @param number $id - WP Post ID
   * @param array $pargs
   * @return array|mixed|string
   */
  public static function view_getComputedDzsapThumbnail($id, $pargs = array()) {

    $margs = array(
      'size' => 'thumbnail',
      'try_to_get_wrapper_image' => 'off',
    );
    if (!is_array($pargs)) {
      $pargs = array();
    }
    $margs = array_merge($margs, $pargs);

    $imgsrc = wp_get_attachment_image_src(get_post_thumbnail_id($id), 'thumbnail');


    $thumb = '';

    if ($margs['try_to_get_wrapper_image'] == 'on' && $id && get_post_meta($id, DZSAP_META_OPTION_PREFIX . 'wrapper_image', true)) {
      $thumb = get_post_meta($id, DZSAP_META_OPTION_PREFIX . 'wrapper_image', true);

    } else {

      if ($imgsrc) {

        if (is_array($imgsrc)) {
          $thumb = $imgsrc[0];
        } else {
          $thumb = $imgsrc;
        }

      } else {
        if (get_post_meta($id, 'dzsvp_thumb', true)) {
          $thumb = get_post_meta($id, 'dzsvp_thumb', true);
        } else {
          if (get_post_meta($id, DZSAP_META_OPTION_PREFIX . 'item_thumb', true)) {
            $thumb = get_post_meta($id, DZSAP_META_OPTION_PREFIX . 'item_thumb', true);
          }
        }
      }
    }

    return $thumb;
  }

  public static function embedZoomTabsAndAccordions() {


    wp_enqueue_script('dzstaa', DZSAP_BASE_URL . 'libs/dzstabsandaccordions/dzstabsandaccordions.js', array('jquery'), DZSAP_VERSION);
    wp_enqueue_style('dzstaa', DZSAP_BASE_URL . 'libs/dzstabsandaccordions/dzstabsandaccordions.css');
  }


  /**
   * @param array $zoomsoundsIts -- mutated
   * @param array $margs
   */
  public static function sort_zoomsoundsItems(&$zoomsoundsIts, $margs = array()) {


    $sortFunction = "dzsap_sort_by_views";

    if ($margs['orderby'] == 'views') {

    }


    if ($margs['orderby'] == 'likes') {
      $sortFunction = "dzsap_sort_by_likes";
    }
    if ($margs['orderby'] == 'downloads') {
      $sortFunction = "dzsap_sort_by_downloads";
    }

    if ($margs['orderby'] == 'views' || $margs['orderby'] == 'likes' || $margs['orderby'] == 'downloads') {

      usort($zoomsoundsIts, $sortFunction);
    }
  }

  /**
   * adjust from dzsap filters to dzs accepted correction
   * @param $margs
   * @return array
   */
  public static function query_adjustForFilters($margs) {

    $argsForAdjust = array(
      'query_order' => $margs['order'],
      'query_orderby' => $margs['orderby'],
    );
    if ($margs['orderby'] == 'likes' || $margs['orderby'] == 'downloads' || $margs['orderby'] == 'views') {
      $argsForAdjust['query_orderby'] = 'meta';
    }

    if (isset($margs['cat']) && $margs['cat']) {
      $argsForAdjust['query_taxonomy_name'] = DZSAP_REGISTER_POST_TYPE_CATEGORY;
      $argsForAdjust['query_term_slug'] = $margs['cat'];
    }
    if (isset($margs['slider']) && $margs['slider']) {


      $argsForAdjust['query_taxonomy_name'] = DZSAP_TAXONOMY_NAME_SLIDERS;
      $argsForAdjust['query_term_slug'] = $margs['slider'];
    }

    if ($margs['orderby'] == 'likes') {
      $argsForAdjust['query_meta_key'] = DZSAP_DB_LIKES_META_NAME;
    }
    if ($margs['orderby'] == 'downloads') {
      $argsForAdjust['query_meta_key'] = DZSAP_DB_DOWNLOADS_META_NAME;
    }
    if ($margs['orderby'] == 'views') {
      $argsForAdjust['query_meta_key'] = DZSAP_DB_VIEWS_META_NAME;
    }



    return $argsForAdjust;
  }

  /**
   *  used in showcase shortcode ( front_shortcode_showcase.php )
   * @param WP_Post[] $postItems WP_POSTS
   * @param array $pargs
   * @return array             <pre>[extra_classes] =>
   * [id] => number
   * [type] =>
   * [date] => datetime
   * [views] => number
   * [likes] => 0
   * [source] => http://ngi:81/wp2/wp-content/uploads/2020/12/adg3.mp3
   * [thumbnail] => string
   * [title] => string
   * [permalink] => string
   * [permalink_to_post] => string
   * [artistname] => admin
   * [original_author_name] => string
   * [songname] => string</pre>
   */
  public static function transform_to_array_for_parse($postItems, $pargs = array()) {


    global $post;
    $margs = array(
      'type' => 'video_items',
      'mode' => 'posts',
    );

    if (!is_array($pargs)) {
      $pargs = array();
    }
    $margs = array_merge($margs, $pargs);


    $its = array();


    foreach ($postItems as $it) {


      $aux25 = array();

      $aux25['extra_classes'] = '';


      if ($margs['feed_from'] == 'audio_items' || $margs['feed_from'] == DZSAP_REGISTER_POST_TYPE_NAME) {
        $it_id = $it->ID;
        $aux25['id'] = $it->ID;
        $imgsrc = wp_get_attachment_image_src(get_post_thumbnail_id($it_id), "full");


        if ($imgsrc) {

          if (is_array($imgsrc)) {
            $aux25['thumbnail'] = $imgsrc[0];
          } else {
            $aux25['thumbnail'] = $imgsrc;
          }

        } else {
          if (get_post_meta($it_id, 'dzsvp_thumb', true)) {
            $aux25['thumbnail'] = get_post_meta($it_id, 'dzsvp_thumb', true);
          }
        }


        $aux25['type'] = get_post_meta($it_id, 'dzsvp_item_type', true);
        $aux25['date'] = $it->post_date;


        // -- we need this for view in shortcodes
        $aux25['views'] = DZSZoomSoundsHelper::get_views_for_track($it_id);
        $aux25['likes'] = DZSZoomSoundsHelper::get_likes_for_track($it_id);
        $aux25['downloads'] = DZSZoomSoundsHelper::get_downloads_for_track($it_id);
        if (isset($margs['orderby'])) {

          if ($margs['orderby'] == 'views') {

          }
          if ($margs['orderby'] == 'likes') {


          }
        }


        $args = array();
        $aux = DZSZoomSoundsHelper::media_getTrackSource($it_id, $it_id, $args);


        $aux25['source'] = $aux;


        $thumb = DZSZoomSoundsHelper::get_post_thumb_src($it_id);


        $thumb_from_meta = get_post_meta($it_id, DZSAP_META_OPTION_PREFIX . 'item_thumb', true);

        if ($thumb_from_meta) {

          $thumb = $thumb_from_meta;
        }

        if ($thumb) {

          $aux25['thumbnail'] = $thumb;
        }


        if (isset($it->post_title)) {

          $aux25['title'] = $it->post_title;
        }
        $aux25['id'] = $it_id;


        $aux25['permalink'] = get_permalink($it_id);
        $aux25['permalink_to_post'] = get_permalink($it_id);


        $maxlen = 50;
        if (isset($margs['desc_count'])) {

          $maxlen = $margs['desc_count'];
        }


        if ($maxlen == 'default') {

          if ($margs['mode'] == 'scrollmenu') {
            $maxlen = 50;
          }
        }
        if ($maxlen == 'default') {
          $maxlen = 100;
        }


        if (isset($margs['desc_readmore_markup']) && $margs['desc_readmore_markup'] == 'default') {
          if ($margs['mode'] == 'scrollmenu') {
            $margs['desc_readmore_markup'] = ' <span style="opacity:0.75;">[...]</span>';
          }
        }
        if (isset($margs['desc_readmore_markup']) && $margs['desc_readmore_markup'] == 'default') {
          $margs['desc_readmore_markup'] = '';
        }


        if ($post && $post->ID === $it_id) {
          $aux25['extra_classes'] .= ' active';
        }


        if ($it) {

          $user_info = get_userdata($it->post_author);


          // -- artist name

          if (isset($user_info->user_nicename) && $user_info->user_nicename) {
            $aux25['artistname'] = $user_info->user_nicename;
          } else {

            if (isset($user_info->first_name) && $user_info->first_name) {
              $aux25['artistname'] = $user_info->last_name . " " . $user_info->first_name;
            } else {
              if (isset($user_info->user_login) && $user_info->user_login) {
                $aux25['artistname'] = $user_info->user_login;
              }
            }
          }
          $aux25['original_author_name'] = $aux25['artistname'];


          if (get_post_meta($it_id, DZSAP_META_OPTION_PREFIX . 'replace_artistname', true)) {

            $aux25['artistname'] = get_post_meta($it_id, DZSAP_META_OPTION_PREFIX . 'replace_artistname', true);
          }

          if (get_post_meta($it_id, DZSAP_META_OPTION_PREFIX . 'replace_menu_artistname', true)) {

            $aux25['menu_artistname'] = get_post_meta($it_id, DZSAP_META_OPTION_PREFIX . 'replace_menu_artistname', true);
          }

          if (get_post_meta($it_id, DZSAP_META_OPTION_PREFIX . 'replace_menu_songname', true)) {

            $aux25['menu_songname'] = get_post_meta($it_id, DZSAP_META_OPTION_PREFIX . 'replace_menu_songname', true);
          }

          $lab = 'wrapper_image';
          if (get_post_meta($it_id, DZSAP_META_OPTION_PREFIX . '' . $lab, true)) {

            $aux25[$lab] = get_post_meta($it_id, DZSAP_META_OPTION_PREFIX . '' . $lab, true);
          }


          // -- get from post title


          if (isset($post->post_title)) {

            $aux25['songname'] = $post->post_title;
          }


          array_push($its, $aux25);
        }
      }


    }


    return $its;

  }


  /**
   * @param $argTrackUrl
   * @param $argTrackId
   * @param $argTrackUrlId
   */
  public static function media_getUrlIdAndSourceId($argTrackUrl = '', $argTrackId = '', $argTrackUrlId = '') {


    $trackUrl = $argTrackUrl;
    $trackId = $argTrackId;
    $trackUrlId = $argTrackUrlId;


    if ($argTrackId) {

      $trackSourceComputed = DZSZoomSoundsHelper::media_getTrackSource($argTrackId, $playerid, $margs);

      if (!$argTrackUrl && $trackSourceComputed && $trackSourceComputed != $trackId) {
        $trackUrl = $trackSourceComputed;
      }
      if (!$argTrackUrlId && $trackSourceComputed && $trackSourceComputed != $trackId) {
        $trackUrlId = DZSZoomSoundsHelper::sanitize_toKey($trackSourceComputed);
      }
    }

    if ($argTrackUrl) {


      $arr_pcm_to_id_links = array();
      if (get_option(DZSAP_DBNAME_PCM_LINKS)) {
        $arr_pcm_to_id_links = get_option('dzsap_pcm_to_id_links');


        if (!$argTrackId) {

          foreach ($arr_pcm_to_id_links as $key => $pcmLink) {

            if (DZSZoomSoundsHelper::sanitize_toKey($pcmLink) === DZSZoomSoundsHelper::sanitize_toKey($argTrackUrl)) {
              $trackId = $key;
              break;
            }
          }
        }
      }


      if (!$argTrackUrlId && $argTrackUrl) {
        $trackUrlId = DZSZoomSoundsHelper::sanitize_toKey($argTrackUrl);
      }
    }

    return array(
      'trackUrl' => $trackUrl,
      'trackId' => $trackId,
      'trackUrlId' => $trackUrlId,
    );
  }

  /**
   *
   * @param $stringSource - mandatory - can be id
   * @param $playerid - will mutate
   * @param $margs - will mutate
   * @return bool|false|mixed|string
   */
  public static function media_getTrackSource($stringSource, &$playerid, &$margs) {
    global $dzsap;


    if (intval($stringSource)) {
      $player_post_id = intval($stringSource);
      $player_post = get_post(intval($stringSource));

      if ($player_post && $player_post->post_type == 'attachment') {
        $media = wp_get_attachment_url($player_post_id);


        $stringSource = $media;
        if ($playerid) {

        } else {
          $playerid = $player_post_id;
          $margs['playerid'] = $player_post_id;
        }


      }
      if ($player_post && $player_post->post_type == 'product') {


        $stringSource = get_post_meta($player_post->ID, DZSAP_META_WOOCOMMERCE_ZOOMSOUNDS_MP3, true);


        if ($stringSource == '') {
          $aux = get_post_meta($player_post->ID, '_downloadable_files', true);
          if ($aux && is_array($aux)) {
            $aux = array_values($aux);

            if (isset($aux[0]) && isset($aux[0]['file']) && strpos(strtolower($aux[0]['file']), '.mp3') !== false) {
              $stringSource = $aux[0]['file'];
            }
          }


        }

        if ($playerid) {

        } else {
          $playerid = $player_post_id;
          $margs['playerid'] = $player_post_id;
        }


      }
      if ($player_post && $player_post->post_type == DZSAP_REGISTER_POST_TYPE_NAME) {
        $stringSource = get_post_meta($player_post->ID, DZSAP_META_OPTION_PREFIX . 'item_source', true);
      }


      if ($stringSource == '') {
        if (function_exists('get_field')) {
          $arr = get_field('long_preview', $player_post_id);


          if ($arr) {

            $media = wp_get_attachment_url($arr);


            $stringSource = $media;
          }

          if ($stringSource == '') {
            if (function_exists('get_field')) {
              $arr = get_field('short_preview', $player_post_id);


              if ($arr) {

                $media = wp_get_attachment_url($arr);


                $stringSource = $media;
              }
            }
          }
        }
      }
    } else {


      if ($stringSource == '{{postid}}') {

        global $post;


        if ($post) {
          $player_post = $post;
        }


        $stringSource = get_post_meta($player_post->ID, 'dzsap_woo_product_track', true);


        if ($stringSource == '') {
          $aux = get_post_meta($player_post->ID, '_downloadable_files', true);
          if ($aux && is_array($aux)) {

            $aux = array_values($aux);


            if (isset($aux[0]) && isset($aux[0]['file']) && strpos(strtolower($aux[0]['file']), '.mp3') !== false) {

              $stringSource = $aux[0]['file'];
            }
          }


        }


        if ($margs['playerid'] == '') {
          $margs['playerid'] = $player_post->ID;
        }
      }


    }

    return $stringSource;
  }

  /**
   * prefers wrapper image
   * @param $track
   */
  public static function getImageSourceFromThumbnailOrId($track) {

    $src_thumb = '';
    // -- this mode will prefer wrapper_image
    if (isset($track['wrapper_image'])) {
      $src_thumb = $track['wrapper_image'];
    } else {
      if (isset($track['thumbnail'])) {
        $src_thumb = DZSZoomSoundsHelper::getImageSourceFromId($track['thumbnail']);
      }
      if ($src_thumb == '' && isset($track['id'])) {
        $src_thumb = DZSZoomSoundsHelper::view_getComputedDzsapThumbnail($track['id']);
      }
    }
    return $src_thumb;
  }

  public static function getThumbnailFromItemInstance($singleItemInstance) {

    $thumbnailUri = '';
    if ($singleItemInstance['thumb'] && $singleItemInstance['thumb'] != 'default') {
      if (intval($singleItemInstance['thumb'])) {

        $thumbnailUri = DZSZoomSoundsHelper::getImageSourceFromId($singleItemInstance['thumb']);
      }
    }


    if ($thumbnailUri == '') {

      if (isset($singleItemInstance['item_thumb']) && $singleItemInstance['item_thumb']) {
        if (is_string($singleItemInstance['item_thumb'])) {

          $thumbnailUri = $singleItemInstance['item_thumb'];
        }
        if (is_array($singleItemInstance['item_thumb'])) {

          $thumbnailUri = DZSZoomSoundsHelper::getImageSourceFromId($singleItemInstance['item_thumb']['id']);
        }

      }
    }
    if ($thumbnailUri == '') {
      if (isset($singleItemInstance['post_type']) && $singleItemInstance['post_type']) {
        $thumbnailUri = DZSZoomSoundsHelper::view_getComputedDzsapThumbnail($singleItemInstance['wpPlayerPostId']);
      }
    }


    if (isset($singleItemInstance['thumb']) && $singleItemInstance['thumb'] == 'none') {
      $thumbnailUri = '';
    }

    return $thumbnailUri;
  }

  /**
   * @param $arg
   * @return string|array image src from media library
   */
  public static function getImageSourceFromId($arg) {

    if (is_numeric($arg)) {
      $imgsrc = wp_get_attachment_image_src($arg, 'full');
      return $imgsrc[0];
    } else {
      if (is_array($arg)) {
        if (isset($arg['url'])) {
          return $arg['url'];
        }
      }
    }
    return $arg;


  }

  public static function autoupdaterUpdate($zipUrl = '', $zipTargetPath = '') {


    global $dzsap;
    if (!$zipUrl) {
      $zipUrl = 'https://zoomthe.me/updater_dzsap/servezip.php?purchase_code=' . $dzsap->mainoptions['dzsap_purchase_code'] . '&site_url=' . site_url();
    }
    if (!$zipTargetPath || $zipTargetPath == '') {
      $zipTargetPath = DZSAP_BASE_PATH . 'update.zip';
    }

    $res = DZSHelpers::get_contents($zipUrl);


    if ($res === false) {
      echo 'server offline';
    } else {
      if (strpos($res, '<div class="error') === 0) {
        echo $res;


        if (strpos($res, '<div class="error">error: in progress') === 0) {

          $dzsap->mainoptions['dzsap_purchase_code_binded'] = 'on';
          update_option(DZSAP_DBNAME_OPTIONS, $dzsap->mainoptions);
        }
      } else {

        file_put_contents($zipTargetPath, $res);
        if (class_exists('ZipArchive')) {
          $zip = new ZipArchive;
          $zipOpenResp = $zip->open($zipTargetPath);

          if ($zipOpenResp === TRUE) {

            $zip->extractTo(DZSAP_BASE_PATH);
            $zip->close();


            $dzsap->mainoptions['dzsap_purchase_code_binded'] = 'on';
            update_option(DZSAP_DBNAME_OPTIONS, $dzsap->mainoptions);


            echo esc_html__('Update succesful.', DZSAP_PREFIX_LOWERCASE);
          } else {
            echo 'failed, code:' . $res;
          }
        } else {

          echo esc_html__('ZipArchive class not found.');
        }

      }
    }
  }

  public static function isTheTrackHasFromCurrentUser($id, $pargs = array()) {

    global $dzsap;

    $po = get_post($id);

    $margs = array(
      'allow_manage_control' => true,
    );
    if (!is_array($pargs)) {
      $pargs = array();
    }
    $margs = array_merge($margs, $pargs);
    if ($margs['allow_manage_control']) {
      if (current_user_can(DZSAP_PERMISSION_ULTIMATE)) {
        return true;
      }
    }

    if ($po) {
      if ($po->post_author == $dzsap->current_user_id) {
        return true;
      }
    }

    return false;
  }

  public static function isBotScraping() {


    return (
    (isset($_SERVER['HTTP_USER_AGENT'])
      && preg_match('/bot|crawl|slurp|spider|metrix|mediapartners/i', $_SERVER['HTTP_USER_AGENT'])
    )
    );
  }

  public static function generateTranslationsForJsMainOptions() {
    return ',translate_add_gallery : "' . esc_html__('Add Playlist', DZSAP_ID) . '"
,translate_add_player : "' . esc_html__('Add Player', DZSAP_ID) . '"
,translate_nag_intro_title : "' . esc_html__('Welcome to ZoomSounds Audio Player', DZSAP_ID) . '"
,translate_nag_intro_col1 : "' . esc_html__('Players can be setup from the ZoomSounds Player Gutenberg block or the shortcode generator in the classic block, with the possibility to import players with one click.', DZSAP_ID) . '"
,translate_nag_intro_col2 : "' . esc_html__('Playlists can be setup from the ZoomSounds Playlist Gutenberg block or the shortcode generator in the classic block, with the possibility to import playlists with one click.', DZSAP_ID) . '"
,translate_nag_intro_1 : "' . esc_html__('Thank you for installing', DZSAP_ID) . ' <strong>ZoomSounds Audio Player</strong>, ' . esc_html__(' you can create playlists from the Playlists menu, players configurations from the submenu here or just use custom data and tailor it to your needs.', DZSAP_ID) . '"
,translate_nag_intro_title_1 : "' . esc_html__('Players', DZSAP_ID) . '"
,translate_nag_intro_title_2 : "' . esc_html__('Playlists', DZSAP_ID) . '"';
  }

  public static function enqueueUltibox() {
    wp_enqueue_style('ultibox', DZSAP_BASE_URL . 'libs/ultibox/ultibox.css');
    wp_enqueue_script('ultibox', DZSAP_BASE_URL . 'libs/ultibox/ultibox.js');
  }

  public static function enqueueSelector() {
    wp_enqueue_style('dzssel', DZSAP_BASE_URL . 'libs/dzsselector/dzsselector.css');
    wp_enqueue_script('dzssel', DZSAP_BASE_URL . 'libs/dzsselector/dzsselector.js');

  }

  public static function enqueueScriptsForAdminGeneral() {
    global $dzsap;

    $adminPhpLinkToPage = admin_url('admin.php?page=' . DZSAP_ADMIN_PAGENAME_LEGACY_SLIDERS_ADMIN_SLIDERS);


    if (isset($_GET['page']) && $_GET['page'] == DZSAP_ADMIN_PAGENAME_LEGACY_SLIDERS_ADMIN_VPCONFIGS) {
      $adminPhpLinkToPage = admin_url('admin.php?page=' . DZSAP_ADMIN_PAGENAME_LEGACY_SLIDERS_ADMIN_VPCONFIGS);
    }

    wp_enqueue_style('dzsap_admin_global', DZSAP_BASE_URL . 'admin/admin_global.css', array(), DZSAP_VERSION);
    wp_enqueue_script('dzsap_admin_global', DZSAP_BASE_URL . 'admin/admin_global.js', array('jquery'), DZSAP_VERSION);
    if ($dzsap->mainoptions['activate_comments_widget'] == 'on') {
      wp_enqueue_script('googleapi', 'https://www.google.com/jsapi');
    }


    DZSZoomSoundsHelper::enqueueUltibox();
    DZSZoomSoundsHelper::enqueueSelector();

    $params = array('currslider' => '_currslider_');
    $newurl = add_query_arg($params, $adminPhpLinkToPage);

    $params = array('deleteslider' => '_currslider_');
    $delurl = add_query_arg($params, $adminPhpLinkToPage);


    wp_enqueue_script('media-upload');
    wp_enqueue_script('tiny_mce');
    wp_enqueue_script('thickbox');
    wp_enqueue_style('thickbox');
    wp_enqueue_script('dzs.farbtastic', DZSAP_BASE_URL . "libs/farbtastic/farbtastic.js");
    wp_enqueue_style('dzs.farbtastic', DZSAP_BASE_URL . 'libs/farbtastic/farbtastic.css');

    wp_enqueue_style('dzs.dzstoggle', DZSAP_BASE_URL . 'libs/dzstoggle/dzstoggle.css');
    wp_enqueue_script('dzs.dzstoggle', DZSAP_BASE_URL . 'libs/dzstoggle/dzstoggle.js');
    wp_enqueue_script('jquery-ui-core');
    wp_enqueue_script('jquery-ui-sortable');


    if (isset($_GET['from']) && $_GET['from'] == 'shortcodegenerator') {
      wp_enqueue_style('dzs.remove_wp_bar', DZSAP_BASE_URL . 'tinymce/remove_wp_bar.css');
    }


    if (isset($_GET['page'])) {
      if ($_GET['page'] == DZSAP_ADMIN_PAGENAME_DESIGNER_CENTER) {
        wp_enqueue_style('dzsap-dc.style', DZSAP_BASE_URL . 'deploy/designer/style/style.css');
        wp_enqueue_script('dzs.farbtastic', DZSAP_BASE_URL . "libs/farbtastic/farbtastic.js");
        wp_enqueue_style('dzs.farbtastic', DZSAP_BASE_URL . 'libs/farbtastic/farbtastic.css');
        wp_enqueue_script('dzsap-dc.admin', DZSAP_BASE_URL . 'deploy/designer/js/admin.js');
      }
      if ($_GET['page'] == DZSAP_ADMIN_PAGENAME_LEGACY_SLIDERS_ADMIN_SLIDERS || $_GET['page'] == DZSAP_ADMIN_PAGENAME_LEGACY_SLIDERS_ADMIN_VPCONFIGS) {

        wp_enqueue_script('dzsap_legacy_sliders_admin', DZSAP_BASE_URL . "admin/legacy-sliders-admin.js", array('jquery'), DZSAP_VERSION);
        wp_enqueue_style('dzsap_legacy_sliders_admin', DZSAP_BASE_URL . 'admin/legacy-sliders-admin.css');

        DZSZoomSoundsHelper::enqueueUltibox();


        $url = DZSAP_URL_FONTAWESOME_EXTERNAL;

        if ($dzsap->mainoptions['fontawesome_load_local'] == 'on') {
          $url = DZSAP_BASE_URL . 'libs/fontawesome/font-awesome.min.css';
        }


        wp_enqueue_style('fontawesome', $url);


        DZSZoomSoundsHelper::embedZoomTabsAndAccordions();
      }
    }


    ob_start();

    // -- admin head


    echo 'window.ultibox_options_init = {
                \'settings_deeplinking\' : \'off\'
                ,\'extra_classes\' : \'close-btn-inset\'
            };
            
        window.init_zoombox_settings = { settings_disableSocial : "on" ,settings_deeplinking : "off" }; window.dzsap_settings = { thepath: "' . DZSAP_BASE_URL . '",the_url: "' . DZSAP_BASE_URL . '" ,siteurl: "' . site_url() . '",site_url: "' . site_url() . '",admin_url: "' . admin_url() . '" 
            , is_safebinding: "' . $dzsap->mainoptions['is_safebinding'] . '", "' . DZSAP_ADMIN_PAGENAME_MAINOPTIONS_WAVE_GENERATOR_AUTO_GENERATE_PARAM . '": "' . $dzsap->mainoptions[DZSAP_ADMIN_PAGENAME_MAINOPTIONS_WAVE_GENERATOR_AUTO_GENERATE_PARAM] . '", admin_close_otheritems:"' . $dzsap->mainoptions['admin_close_otheritems'] . '",settings_wavestyle:"' . $dzsap->mainoptions['settings_wavestyle'] . '"
,url_vpconfig:"' . admin_url('admin.php?page=' . DZSAP_ADMIN_PAGENAME_LEGACY_SLIDERS_ADMIN_VPCONFIGS . '&currslider={{currslider}}') . '"
,shortcode_generator_url: "' . admin_url('admin.php?page=' . DZSAP_ADMIN_PAGENAME_MAINOPTIONS) . '&dzsap_shortcode_builder=on"
,shortcode_generator_player_url: "' . admin_url('admin.php?page=' . DZSAP_ADMIN_PAGENAME_MAINOPTIONS) . '&' . DZSAP_ADMIN_SHORTCODE_PLAYER_GENERATOR_KEY . '=on"
' . DZSZoomSoundsHelper::generateTranslationsForJsMainOptions();


    if ($dzsap->mainoptions['soundcloud_api_key']) {
      echo ',soundcloud_apikey : "' . $dzsap->mainoptions['soundcloud_api_key'] . '"';
    }


    // -- playlists admin
    if (isset($_GET['page']) && $_GET['page'] == DZSAP_ADMIN_PAGENAME_LEGACY_SLIDERS_ADMIN_SLIDERS && (isset($dzsap->mainitems[$dzsap->currSlider]) == false || $dzsap->mainitems[$dzsap->currSlider] == '')) {
      echo ', addslider:"on"';
    }
    // -- configs admin
    if (isset($_GET['page']) && $_GET['page'] == DZSAP_ADMIN_PAGENAME_LEGACY_SLIDERS_ADMIN_VPCONFIGS && (isset($dzsap->mainitems_configs[$dzsap->currSlider]) == false || $dzsap->mainitems_configs[$dzsap->currSlider] == '')) {
      echo ', addslider:"on"';
    }


    echo ',urldelslider:"' . $delurl . '", urlcurrslider:"' . $newurl . '", currSlider:"' . $dzsap->currSlider . '", currdb:"' . $dzsap->currDb . '", color_waveformbg:"' . DZSZoomSoundsHelper::sanitizeToHexNonHash($dzsap->mainoptions['color_waveformbg']) . '", color_waveformprog:"' . DZSZoomSoundsHelper::sanitizeToHexNonHash($dzsap->mainoptions['color_waveformprog']) . '", waveformgenerator_multiplier:"' . $dzsap->mainoptions['waveformgenerator_multiplier'] . '"';


    if (!(isset($dzsap->mainoptions['acknowledged_intro_data']) && $dzsap->mainoptions['acknowledged_intro_data'] == 'on')) {


      echo ',nag_intro_data: "' . 'on' . '"';
      wp_enqueue_style('dzs.tooltip', DZSAP_BASE_URL . 'libs/dzstooltip/dzstooltip.css');

    }


    echo ',sliders:[';

    $dzsap->db_read_mainitems();

    if ($dzsap->mainoptions['playlists_mode'] == 'normal') {


      foreach ($dzsap->mainitems as $mainitem) {
        echo '{ value: "' . $mainitem['value'] . '",label: "' . $mainitem['label'] . '",term_id: "' . $mainitem['term_id'] . '" },';
      }
    } else {

      foreach ($dzsap->mainitems as $mainitem) {
        echo '{ value: "' . dzs_sanitize_for_js_double_quote($mainitem['settings']['id']) . '",label: "' . dzs_sanitize_for_js_double_quote($mainitem['settings']['id']) . '" },';
      }
    }
    echo ']';


    // -- from options-item-meta
    echo ',player_options:\'';
    echo addslashes(json_encode($dzsap->options_item_meta_sanitized));
    echo '\'';
    echo '};';

    ?>
    window.dzsap_gutenberg_player_options_for_js_init = {};
    try {
    JSON.parse(dzsap_settings.player_options).forEach((el) => {


    let aux = {};

    aux.type = 'string';
    if ((el.type)) {
    aux.type = el.type;
    }
    if ((el['default'])) {

    aux['default'] = el['default'];
    }

    // -- sanitizing
    if (aux.type === 'text' || aux.type === 'textarea') {
    aux.type = 'string';
    }


    if (aux.type === 'string' || aux.type === 'attach' || aux.type === 'select') {
    window.dzsap_gutenberg_player_options_for_js_init[el.name] = aux;
    }

    })
    } catch (err) {
    console.info('no options');
    }
    window.dzsap_gutenberg_playlist_options_for_js_init = {
    'dzsap_select_id': {
    'type': 'string',
    'default': ''
    }, 'examples_con_opened': {'type': 'string', 'default': ''}
    };
    ;<?php


    $scriptString = ob_get_clean();
    wp_register_script('dzsap-script-hook-for-ub', '');
    wp_enqueue_script('dzsap-script-hook-for-ub');
    wp_add_inline_script('dzsap-script-hook-for-ub', $scriptString);

  }

  /**
   * adds js_settings_extrahtml_in_bottom_controls_from_config to parameter
   * @param $initialValue
   * @param $playerConfigSettings
   * @return string
   */
  public static function parseItemDetermineExtraHtml($initialValue, $playerConfigSettings) {


    if (isset($playerConfigSettings['js_settings_extrahtml_in_bottom_controls_from_config']) && $playerConfigSettings['js_settings_extrahtml_in_bottom_controls_from_config']) {
      $playerConfigSettings['js_settings_extrahtml_in_bottom_controls_from_config'] = str_replace(array("\r", "\r\n", "\n"), '', $playerConfigSettings['js_settings_extrahtml_in_bottom_controls_from_config']);

      $initialValue .= (stripslashes($playerConfigSettings['js_settings_extrahtml_in_bottom_controls_from_config']));
    }

    return $initialValue;
  }

  static function generateOptionsFromConfigForMainOptions($config_main_options, $category = 'main', $classForMainOptions = null) {


    $fout = '';
    foreach ($config_main_options as $key => $main_option) {
      if ($main_option['category'] !== $category) {
        continue;
      }

      $lab = $key;


      $seekValue = '';

      if (isset($main_option['default']) && $main_option['default']) {
        $seekValue = $main_option['default'];
      }

      if (isset($classForMainOptions->mainoptions[$lab]) && $classForMainOptions->mainoptions[$lab]) {
        $seekValue = $classForMainOptions->mainoptions[$lab];
      }


      $fout .= '<div class="setting"';


      if (isset($main_option['dependency'])) {

        $fout .= ' data-dependency=\'' . json_encode($main_option['dependency']) . '\'';
      }

      $fout .= '>';
      $fout .= '<h4 class="setting-label">' . $main_option['title'] . '</h4>';

      $inputExtraClasses = ' ';
      if (isset($main_option['extra_classes'])) {
        $inputExtraClasses = $main_option['extra_classes'];
      }

      $argsForInput = array(
        'id' => $lab,
        'val' => '',
        'class' => ' ' . $inputExtraClasses,
        'seekval' => $seekValue,
      );

      if ($main_option['type'] == 'textarea') {
        $fout .= DZSHelpers::generate_input_textarea($lab, $argsForInput);
      }
      if ($main_option['type'] == 'text') {
        $fout .= DZSHelpers::generate_input_text($lab, $argsForInput);
      }
      if ($main_option['type'] == 'select') {
        $argsForInput['class'] .= ' dzs-style-me skin-beige ' . $inputExtraClasses;
        $argsForInput['options'] = $main_option['choices'];
        $fout .= DZSHelpers::generate_select($lab, $argsForInput);
      }
      if ($main_option['type'] == 'checkbox') {
        $fout .= DZSHelpers::generate_input_text($lab, array('id' => $lab, 'val' => 'off', 'input_type' => 'hidden'));
        $fout .= '<div class="dzscheckbox skin-nova">';
        $fout .= DZSHelpers::generate_input_checkbox($lab, array('id' => $lab, 'val' => 'on', 'seekval' => $seekValue));
        $fout .= '<label for="' . $lab . '"></label>';
        $fout .= '</div>';
      }
      if (isset($main_option['sidenote']) && $main_option['sidenote']) {
        $fout .= '<div class="sidenote">' . $main_option['sidenote'] . '</div>';
      }

      $fout .= '</div>';


    }
    return $fout;

  }


  public static function generateCssPlayerCustomColors($pargs = array()) {


    $margs = array(
      'colorhighlight' => '',
      'skin_ap' => '',
      'selector' => '',

      'config' => array(),
      'configId' => '',
      'call_from' => '',
    );

    $margs = array_merge($margs, $pargs);


    $colorUi = '';
    $colorHighlight = '';

    if (isset($margs['config']['color_ui'])) {
      $colorUi = DZSZoomSoundsHelper::sanitizeToHexNonHash($margs['config']['color_ui']);
    }
    if (isset($margs['config']['colorhighlight'])) {
      $colorHighlight = DZSZoomSoundsHelper::sanitizeToHexNonHash($margs['config']['colorhighlight']);
    }

    $fout = '';

    global $dzsap;


    array_push($dzsap->extraCssConsumedConfigurations, $margs['configId']);


    if (!$colorHighlight && !$colorUi) {

      return '';
    }


    $selectorApConfig = '.audioplayer' . DZSAP_VIEW_APCONFIG_PREFIX . DZSZoomSoundsHelper::string_sanitizeToCssClass($margs['configId']);


    $colorsFromColorsCss = 'body .audioplayer:not(.scrubbar-type-wave) .ap-controls .scrubbar .scrub-bg{background-color:#828080}body .audioplayer .volume_active{background-color:#b657b5}body .extra-html{color:#222113}body .audioplayer .con-controls>.the-bg{background-color:#222113}body .audioplayer .audioplayer-inner .the-thumb{background-color:#222113}body .controls-volume.controls-volume-vertical .volume-holder{background-color:#222113}body .audioplayer.skin-wave .player-but .the-icon-bg{background-color:#b657b5}body .audioplayer.skin-wave .meta-artist .the-artist{color:#b657b5}body .audioplayer.skin-wave .volume_active{background-color:#b657b5}body .audioplayer.skin-wave .volume_static:before{background-color:#222113}body .audioplayer.skin-wave .meta-artist .the-name,body .audioplayer.skin-wave .meta-artist .the-name>a,body .audioplayer.skin-wave .meta-artist .the-name object>a{color:#222113}body .btn-zoomsounds.btn-like:hover .the-icon>svg path,body .btn-zoomsounds.btn-like.active .the-icon>svg path{fill:#b657b5}body .audioplayer.skin-wave .volumeicon{background:#222113}body .audioplayer.skin-wave .volumeicon:before{border-right-color:#222113}body .audioplayer.skin-wave.button-aspect-noir i.svg-icon{color:#222113}body .audioplayer.skin-wave.button-aspect-noir .con-playpause .the-icon-bg{border-color:#222113}body .audioplayer.skin-wave.button-aspect-noir .player-but .the-icon-bg{background-color:transparent;border-color:#222113}body .audioplayer.skin-wave.button-aspect-noir.button-aspect-noir--filled .playbtn .the-icon-bg,body .audioplayer.skin-wave.button-aspect-noir.button-aspect-noir--filled .pausebtn .the-icon-bg,body .audioplayer.skin-wave.button-aspect-noir.button-aspect-noir--filled .player-but .the-icon-bg{background-color:#222113}body .audioplayer.skin-wave.button-aspect-noir.button-aspect-noir--filled i.svg-icon{color:#ddd} body .audioplayer.skin-wave.button-aspect-noir.button-aspect-noir--filled .svg-icon path{fill:#ddd}body .audioplayer.skin-wave.button-aspect-noir.button-aspect-noir--stroked i.svg-icon{color:#222113}body .audioplayer.skin-wave.button-aspect-noir.button-aspect-noir--stroked .svg-icon path{stroke:#222113}body .audioplayer.skin-aria .ap-controls .ap-controls-left{background-color:#b657b5}body .audioplayer.skin-aria .ap-controls .ap-controls-left .con-playpause{background-color:#996b99}body .audioplayer.skin-aria .ap-controls .ap-controls-left .con-playpause path{fill:#ddd}body .audioplayer.skin-aria .ap-controls .ap-controls-right{background-color:#e7e5e5}body .audioplayer.skin-aria .ap-controls .ap-controls-right .meta-artist-con{color:#b657b5}body .audioplayer.skin-silver{line-height:1}body .audioplayer.skin-silver a{color:#b657b5}body .audioplayer.skin-silver .ap-controls .meta-artist-con{color:#222113}body .audioplayer.skin-silver .ap-controls .total-time,body .audioplayer.skin-silver .ap-controls .curr-time{color:#222113}body .dzsap-color_brand_bg{background-color:#b657b5}body .dzsap-color_inverse_ui_text{color:#b657b5}body .dzsap-color_inverse_ui_fill:not(.a) path{fill:#ddd} body .audioplayer .player-but .svg-icon path{fill:#222113}body .audioplayer.skin-wave .svg-icon path{fill:#ddd}body .audioplayer.skin-wave.button-aspect-noir .svg-icon path{fill:#222113}
';


    $colorsFromColorsCss = str_replace('body .audioplayer', $selectorApConfig, $colorsFromColorsCss);
    $colorsFromColorsCss = str_replace('b657b5', $colorHighlight, $colorsFromColorsCss);
    $colorsFromColorsCss = str_replace('#222113', '#' . $colorUi, $colorsFromColorsCss);

    $fout .= $colorsFromColorsCss;


    return $fout;
  }

  public static function sanitizeToHexNonHash($arg) {
    $arg = str_replace('#', '', $arg);
    return $arg;
  }

  public static function enqueueAudioPlayerShowcase() {


    wp_enqueue_style('audioplayer_showcase', DZSAP_BASE_URL . 'libs/audioplayer_showcase/audioplayer_showcase.css', array(), DZSAP_VERSION);
    wp_enqueue_script('audioplayer_showcase', DZSAP_BASE_URL . 'libs/audioplayer_showcase/audioplayer_showcase.js', array('jquery'), DZSAP_VERSION);

  }

  public static function enqueueScriptsForAdminMainOptions() {

    global $dzsap;
    wp_enqueue_style('dzscheckbox', DZSAP_BASE_URL . 'libs/dzscheckbox/dzscheckbox.css');


    wp_enqueue_style('dzs.dzstoggle', DZSAP_BASE_URL . 'libs/dzstoggle/dzstoggle.css');
    wp_enqueue_script('dzs.dzstoggle', DZSAP_BASE_URL . 'libs/dzstoggle/dzstoggle.js');

    dzsap_enqueue_fontawesome();

    DZSZoomSoundsHelper::embedZoomTabsAndAccordions();


    if (isset($_GET['dzsap_shortcode_builder']) && $_GET['dzsap_shortcode_builder'] == 'on') {

      wp_enqueue_style('dzsap_shortcode_builder_style', DZSAP_BASE_URL . 'tinymce/popup.css');
      wp_enqueue_script('dzsap_shortcode_builder', DZSAP_BASE_URL . 'tinymce/popup.js');
      DZSZoomSoundsHelper::embedZoomTabsAndAccordions();

      DZSZoomSoundsHelper::enqueueUltibox();
    } else {


      if (isset($_GET[DZSAP_ADMIN_SHORTCODE_PLAYER_GENERATOR_KEY]) && $_GET[DZSAP_ADMIN_SHORTCODE_PLAYER_GENERATOR_KEY] == 'on') {


        wp_enqueue_style('dzsap_shortcode_builder_style', DZSAP_BASE_URL . 'tinymce/popup.css');
        wp_enqueue_style(DZSAP_ADMIN_SHORTCODE_PLAYER_GENERATOR_KEY, DZSAP_BASE_URL . 'shortcodegenerator/generator_player.css');
        wp_enqueue_script(DZSAP_ADMIN_SHORTCODE_PLAYER_GENERATOR_KEY, DZSAP_BASE_URL . 'shortcodegenerator/generator_player.js');

        DZSZoomSoundsHelper::embedZoomTabsAndAccordions();

        DZSZoomSoundsHelper::enqueueUltibox();


        wp_enqueue_style('dzs.tooltip', DZSAP_BASE_URL . 'libs/dzstooltip/dzstooltip.css');
      } else {

        wp_enqueue_script('jquery-ui-core');
        wp_enqueue_script('jquery-ui-sortable');
        wp_enqueue_script('jquery-ui-slider');
        $url = "https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css";
        wp_enqueue_style('jquery-ui-smoothness', $url, false, null);
        wp_enqueue_script('dzs.farbtastic', DZSAP_BASE_URL . "libs/farbtastic/farbtastic.js");
        wp_enqueue_style('dzs.farbtastic', DZSAP_BASE_URL . 'libs/farbtastic/farbtastic.css');
      }

    }

  }

  public static function registerDzsapPages() {


    global $dzsap;
    $capability = 'dzsap_manage_options';

    if (current_user_can(DZSAP_PERMISSION_ULTIMATE)) {
      $capability = DZSAP_PERMISSION_ULTIMATE;
    }


    $dzsap_page = add_menu_page(esc_html__('Playlists', DZSAP_ID), esc_html__('ZoomSounds', DZSAP_ID), $capability, DZSAP_ADMIN_PAGENAME_PARENT, array($dzsap->classAdmin, 'admin_page'), 'dashicons-media-audio');


    $capability = 'dzsap_manage_vpconfigs';

    if (current_user_can(DZSAP_PERMISSION_ULTIMATE)) {
      $capability = DZSAP_PERMISSION_ULTIMATE;
    }


    $dzsap_subpage = add_submenu_page(DZSAP_ADMIN_PAGENAME_PARENT, esc_html__('Playlists', DZSAP_ID), esc_html__('Playlists', DZSAP_ID), $capability, DZSAP_ADMIN_PAGENAME_PARENT, array($dzsap->classAdmin, 'admin_page'), 10);


    $capability = 'dzsap_manage_vpconfigs';

    if (current_user_can(DZSAP_PERMISSION_ULTIMATE)) {
      $capability = DZSAP_PERMISSION_ULTIMATE;
    }


    $dzsap_subpage = add_submenu_page(DZSAP_ADMIN_PAGENAME_PARENT, 'ZoomSounds ' . esc_html__('Player Configs', DZSAP_ID), esc_html__('Player Configs', DZSAP_ID), $capability, DZSAP_ADMIN_PAGENAME_LEGACY_SLIDERS_ADMIN_VPCONFIGS, array($dzsap->classAdmin, 'admin_page_vpc'), 20);


    $capability = 'dzsap_manage_options';

    if (current_user_can(DZSAP_PERMISSION_ULTIMATE)) {
      $capability = DZSAP_PERMISSION_ULTIMATE;
    }

    $dzsap_subpage = add_submenu_page(DZSAP_ADMIN_PAGENAME_PARENT, esc_html__('ZoomSounds Settings', DZSAP_ID), esc_html__('Settings', DZSAP_ID), $capability, DZSAP_ADMIN_PAGENAME_MAINOPTIONS, array($dzsap->classAdmin, 'admin_page_mainoptions'), 30);


    $capability = DZSAP_PERMISSION_ULTIMATE;


    $dzsap_subpage = add_submenu_page(DZSAP_ADMIN_PAGENAME_PARENT, esc_html__('Autoupdater', DZSAP_ID), esc_html__('Autoupdater', DZSAP_ID), $dzsap->admin_capability, DZSAP_ADMIN_PAGENAME_AUTOUPDATER, array($dzsap->classAdmin, 'admin_page_autoupdater'), 40);


    $capability = 'delete_posts';
    if (current_user_can(DZSAP_PERMISSION_ULTIMATE)) {
      $capability = DZSAP_PERMISSION_ULTIMATE;
    }
    $dzsap_subpage = add_submenu_page(DZSAP_ADMIN_PAGENAME_PARENT, esc_html__('About ZoomSounds', DZSAP_ID), esc_html__('About', DZSAP_ID), $capability, DZSAP_ADMIN_PAGENAME_ABOUT, array($dzsap->classAdmin, 'admin_page_about'), 50);


    if ($dzsap->mainoptions['dzsap_items_hide'] == 'on') {
      remove_menu_page('edit.php?post_type=' . DZSAP_REGISTER_POST_TYPE_NAME);
    }

  }

  /**
   * get the source
   * @param $defaultSource
   * @param $shortcodePlayerAtts
   * @return mixed|string
   */
  public static function player_parseItems_getSource($defaultSource, $shortcodePlayerAtts) {

    $source = $defaultSource;

    if (isset($shortcodePlayerAtts[DZSAP_META_OPTION_PREFIX . 'item_source']) && $shortcodePlayerAtts[DZSAP_META_OPTION_PREFIX . 'item_source'] && (!($shortcodePlayerAtts['source']) || $shortcodePlayerAtts['source'] == '')) {
      $source = $shortcodePlayerAtts[DZSAP_META_OPTION_PREFIX . 'item_source'];
    }
    if (isset($shortcodePlayerAtts['item_source']) && $shortcodePlayerAtts['item_source'] && (!($source) || $source == '')) {
      $source = $shortcodePlayerAtts['item_source'];
    }
    if (isset($shortcodePlayerAtts['item_replace_source']) && $shortcodePlayerAtts['item_replace_source']) {
      $source = $shortcodePlayerAtts['item_replace_source'];
    }


    if (isset($shortcodePlayerAtts['is_amazon_s3']) && $shortcodePlayerAtts['is_amazon_s3'] == 'on') {
      $source = DZSZoomSoundsHelper::player_parseItems_getAwsSource($source);
    }

    if ($shortcodePlayerAtts['source'] == '{{postid}}') {

      global $post;
      if ($post) {
        $source = $post->ID;
      }
    }


    return $source;

  }

  public static function player_parseItems_getAwsSource($original_source) {


    global $dzsap;

    // -- amazon s3
    // todo: maybe move to parse_items

    if (!class_exists('Aws\S3\S3Client')) {

      $path = DZSAP_BASE_PATH . 'class_parts/aws/aws-autoloader.php';

      if (file_exists($path)) {
        require_once($path);
      }
    }

    if (class_exists('Aws\S3\S3Client')) {

      $s3 = null;


      try {

        $s3 = new Aws\S3\S3Client(array(

          'credentials' => array(
            'key' => $dzsap->mainoptions['aws_key'],
            'secret' => $dzsap->mainoptions['aws_key_secret']
          ),
          'version' => 'latest',
          'region' => $dzsap->mainoptions['aws_region']
        ));
      } catch (Exception $e) {


        $credentials = new Credentials($dzsap->mainoptions['aws_key'], $dzsap->mainoptions['aws_key_secret']);

        $s3_client = new S3Client(array(
          'version' => 'latest',
          'region' => $dzsap->mainoptions['aws_region'],
          'credentials' => $credentials
        ));
      }


      if ($s3) {


        $cmd = $s3->getCommand('GetObject', array(
          'Bucket' => $dzsap->mainoptions['aws_bucket'],
          'Key' => $original_source,
          'ResponseContentDisposition' => 'filename=' . str_replace(array('%21', '%2A', '%27', '%28', '%29', '%20'), array('!', '*', '\'', '(', ')', ' '), rawurlencode('ceva' . '.' . pathinfo('ceva', PATHINFO_EXTENSION)))
        ));


        $req = $s3->createPresignedRequest($cmd, '1 day');
        return (string)$req->getUri();
      }

    }

    return null;
  }

  public static function sanitize_for_extraHtml($arg, $singleItemInstance) {

    $fout = $arg;
    $fout = dzs_esc__($fout, array(), true);
    $fout = str_replace('{{heart_svg}}', dzs_read_from_file_ob(DZSAP_BASE_PATH . 'assets/svg/heart.svg'), $fout);


    if ($singleItemInstance && isset($singleItemInstance['wpPlayerPostId']) && $singleItemInstance['wpPlayerPostId']) {

      $fout = str_replace('{{meta1val}}', get_post_meta($singleItemInstance['wpPlayerPostId'], 'dzsap_meta_extra_meta_label_1', true), $fout);
      $fout = str_replace('{{meta2val}}', get_post_meta($singleItemInstance['wpPlayerPostId'], 'dzsap_meta_extra_meta_label_2', true), $fout);
      $fout = str_replace('{{meta3val}}', get_post_meta($singleItemInstance['wpPlayerPostId'], 'dzsap_meta_extra_meta_label_3', true), $fout);
    }

    return $fout;
  }

  public static function sanitize_for_javascript_double_quote_value($arg) {

    $arg = str_replace('"', '', $arg);

    if ($arg == '/') {
      $arg = '';
    }

    return $arg;

  }

  public static function sanitize_to_gallery_item($che) {

    global $dzsap;
    $po_id = $che->ID;


    $che = (array)$che;


    $user_info = get_userdata($che['post_author']);

    if (isset($user_info) && $user_info && isset($user_info->first_name) && $user_info->first_name) {

      $che['artistname'] = $user_info->last_name . " " . $user_info->first_name;
    } else {

      if (isset($user_info->user_login)) {
        $che['artistname'] = $user_info->user_login;
      }
    }


    if (get_post_meta($po_id, DZSAP_META_OPTION_PREFIX . 'replace_artistname', true)) {

      $che['artistname'] = get_post_meta($po_id, DZSAP_META_OPTION_PREFIX . 'replace_artistname', true);
    }

    if (get_post_meta($po_id, DZSAP_META_OPTION_PREFIX . 'replace_menu_artistname', true)) {

      $che['menu_artistname'] = get_post_meta($po_id, DZSAP_META_OPTION_PREFIX . 'replace_menu_artistname', true);
    }

    if (get_post_meta($po_id, DZSAP_META_OPTION_PREFIX . 'replace_menu_songname', true)) {

      $che['menu_songname'] = get_post_meta($po_id, DZSAP_META_OPTION_PREFIX . 'replace_menu_songname', true);
    }


    $che['sourceogg'] = '';
    $che['source'] = get_post_meta($po_id, DZSAP_META_OPTION_PREFIX . 'item_source', true);

    $che['songname'] = $che['post_title'];
    $che['playfrom'] = '0';
    $che['thumb'] = get_post_meta($po_id, DZSAP_META_OPTION_PREFIX . 'item_thumb', true);
    $che['type'] = get_post_meta($po_id, DZSAP_META_OPTION_PREFIX . 'type', true);
    $che['playerid'] = $po_id;


    foreach ($dzsap->options_item_meta as $oim) {


      if (isset($oim['name'])) {
        if ($oim['name'] === 'post_content') {
          continue;
        }


        $long_name = $oim['name'];
        $short_name = str_replace(DZSAP_META_OPTION_PREFIX . 'item_', '', $oim['name']);
        $short_name = str_replace(DZSAP_META_OPTION_PREFIX . '', '', $short_name);


        $che[$oim['name']] = get_post_meta($po_id, $oim['name'], true);
        $che[$short_name] = get_post_meta($po_id, $long_name, true);
      } else {
        continue;
      }
    }


    $lab = DZSAP_META_OPTION_PREFIX . 'source_attachment_id';
    if (get_post_meta($po_id, $lab, true)) {
      $che[$lab] = get_post_meta($po_id, $lab, true);
    }


    return $che;
  }


  public static function get_assets() {
    $default_options = include_once DZSAP_BASE_PATH . 'configs/main-options-default.php';
    $default_options_new = include_once DZSAP_BASE_PATH . 'configs/config-main-options.php';

    foreach ($default_options_new as $optionKey => $optionArr) {
      $default_options[$optionKey] = $optionArr['default'];
    }

    return array(
      'default_options' => $default_options,
      'hearts_svg' => '<svg xmlns:svg="https://www.w3.org/2000/svg" xmlns="https://www.w3.org/2000/svg" version="1.0" width="15" height="15"  viewBox="0 0 645 700" id="svg2"> <defs id="defs4" /> <g id="layer1"> <path d="M 297.29747,550.86823 C 283.52243,535.43191 249.1268,505.33855 220.86277,483.99412 C 137.11867,420.75228 125.72108,411.5999 91.719238,380.29088 C 29.03471,322.57071 2.413622,264.58086 2.5048478,185.95124 C 2.5493594,147.56739 5.1656152,132.77929 15.914734,110.15398 C 34.151433,71.768267 61.014996,43.244667 95.360052,25.799457 C 119.68545,13.443675 131.6827,7.9542046 172.30448,7.7296236 C 214.79777,7.4947896 223.74311,12.449347 248.73919,26.181459 C 279.1637,42.895777 310.47909,78.617167 316.95242,103.99205 L 320.95052,119.66445 L 330.81015,98.079942 C 386.52632,-23.892986 564.40851,-22.06811 626.31244,101.11153 C 645.95011,140.18758 648.10608,223.6247 630.69256,270.6244 C 607.97729,331.93377 565.31255,378.67493 466.68622,450.30098 C 402.0054,497.27462 328.80148,568.34684 323.70555,578.32901 C 317.79007,589.91654 323.42339,580.14491 297.29747,550.86823 z" id="path2417" style="" /> <g transform="translate(129.28571,-64.285714)" id="g2221" /> </g> </svg>',
      'svg_star' => '<svg enable-background="new -1.23 -8.789 141.732 141.732" height="141.732px" id="Livello_1" version="1.1" viewBox="-1.23 -8.789 141.732 141.732" width="141.732px" xml:space="preserve" xmlns="https://www.w3.org/2000/svg" xmlns:xlink="https://www.w3.org/1999/xlink"><g id="Livello_100"><path d="M139.273,49.088c0-3.284-2.75-5.949-6.146-5.949c-0.219,0-0.434,0.012-0.646,0.031l-42.445-1.001l-14.5-37.854   C74.805,1.824,72.443,0,69.637,0c-2.809,0-5.168,1.824-5.902,4.315L49.232,42.169L6.789,43.17c-0.213-0.021-0.43-0.031-0.646-0.031   C2.75,43.136,0,45.802,0,49.088c0,2.1,1.121,3.938,2.812,4.997l33.807,23.9l-12.063,37.494c-0.438,0.813-0.688,1.741-0.688,2.723   c0,3.287,2.75,5.952,6.146,5.952c1.438,0,2.766-0.484,3.812-1.29l35.814-22.737l35.812,22.737c1.049,0.806,2.371,1.29,3.812,1.29   c3.393,0,6.143-2.665,6.143-5.952c0-0.979-0.25-1.906-0.688-2.723l-12.062-37.494l33.806-23.9   C138.15,53.024,139.273,51.185,139.273,49.088"/></g><g id="Livello_1_1_"/></svg>',
      'svg_stick_to_bottom_close_hide' => '<svg version="1.1" class="svg-icon icon-hide" xmlns="https://www.w3.org/2000/svg" xmlns:xlink="https://www.w3.org/1999/xlink" x="0px" y="0px" width="144.883px" height="145.055px" viewBox="0 0 144.883 145.055" enable-background="new 0 0 144.883 145.055" xml:space="preserve"> <g> <g> <g> <g> <g> <path fill="#5A5B5D" d="M72.527,145.055C32.535,145.055,0,112.52,0,72.527S32.535,0,72.527,0c37.921,0,69.7,29.6,72.35,67.387 c0.097,1.377-0.942,2.572-2.319,2.669c-1.384,0.087-2.571-0.941-2.669-2.319C137.423,32.557,107.834,5,72.527,5 C35.293,5,5,35.293,5,72.527s30.293,67.527,67.527,67.527c35.271,0,64.858-27.525,67.355-62.665 c0.098-1.377,1.302-2.396,2.672-2.316c1.377,0.099,2.414,1.294,2.316,2.672C142.188,115.488,110.41,145.055,72.527,145.055z"/> </g> </g> <g> <g> <g> <path fill="#5A5B5D" d="M45.658,101.897c-0.64,0-1.279-0.244-1.768-0.732c-0.977-0.976-0.977-2.559,0-3.535l25.102-25.103 L43.891,47.425c-0.977-0.977-0.977-2.56,0-3.535c0.977-0.977,2.559-0.977,3.535,0l26.869,26.87 c0.977,0.977,0.977,2.559,0,3.535l-26.869,26.87C46.938,101.653,46.298,101.897,45.658,101.897z"/> </g> </g> <g> <g> <path fill="#5A5B5D" d="M99.396,101.896c-0.64,0-1.279-0.244-1.768-0.732L70.76,74.295c-0.977-0.977-0.977-2.559,0-3.535 l26.869-26.87c0.977-0.977,2.559-0.977,3.535,0c0.977,0.976,0.977,2.559,0,3.535L76.062,72.527l25.102,25.102 c0.977,0.977,0.977,2.559,0,3.535C100.676,101.652,100.036,101.896,99.396,101.896z"/> </g> </g> </g> </g> </g> </g> </svg>',
      'svg_stick_to_bottom_close_show' => '<svg version="1.1" class="svg-icon icon-show" xmlns="https://www.w3.org/2000/svg" xmlns:xlink="https://www.w3.org/1999/xlink" x="0px" y="0px" width="148.025px" height="148.042px" viewBox="0 0 148.025 148.042" enable-background="new 0 0 148.025 148.042" xml:space="preserve"> <g> <g> <g> <g> <g> <g> <path fill="#5A5B5D" d="M74.038,148.042c-8.882,0-17.778-1.621-26.329-4.873C14.546,130.561-5.043,96.09,1.132,61.206 c0.241-1.359,1.537-2.268,2.897-2.026c1.359,0.241,2.267,1.538,2.026,2.897c-5.757,32.523,12.508,64.662,43.431,76.418 c17.222,6.551,35.964,6.003,52.771-1.544c16.809-7.547,29.672-21.188,36.221-38.411c6.552-17.222,6.004-35.963-1.543-52.771 c-7.546-16.809-21.188-29.672-38.411-36.222C68.706-1.792,35.266,8.613,17.206,34.85c-0.783,1.138-2.338,1.424-3.478,0.642 c-1.137-0.783-1.424-2.34-0.642-3.478C32.458,3.874,68.324-7.283,100.301,4.873c18.472,7.024,33.103,20.821,41.195,38.848 c8.094,18.027,8.682,38.127,1.655,56.597c-7.023,18.472-20.819,33.102-38.846,41.195 C94.624,145.859,84.342,148.041,74.038,148.042z"/> </g> </g> </g> <g> <g> <g> <g> <g> <path fill="#5A5B5D" d="M53.523,111.167c-0.432,0-0.863-0.111-1.25-0.335c-0.773-0.446-1.25-1.271-1.25-2.165V39.376 c0-0.894,0.477-1.719,1.25-2.165c0.773-0.447,1.727-0.447,2.5,0l60.014,34.646c0.773,0.446,1.25,1.271,1.25,2.165 s-0.477,1.719-1.25,2.165l-60.014,34.645C54.387,111.056,53.955,111.167,53.523,111.167z M56.023,43.706v60.631 l52.514-30.314L56.023,43.706z"/> </g> </g> </g> </g> </g> </g> </g> </g> </svg>',
    );
  }

  public static function get_soundcloud_track_source($che) {
    global $dzsap;
    $fout = '';

    $sw_was_cached = false;


    $cacher = get_option('dzsap_cache_soundcloudtracks');

    if (is_array($cacher) == false) {
      $cacher = array();
    }


    if (isset($cacher[$che['soundcloud_track_id']])) {
      $fout = $cacher[$che['soundcloud_track_id']]['source'];
      $sw_was_cached = true;
    }


    if ($sw_was_cached == false) {

      $aux = DZSHelpers::get_contents('https://api.soundcloud.com/tracks/' . $che['soundcloud_track_id'] . '.json?secret_token=' . $che['soundcloud_secret_token'] . '&client_id=' . $dzsap->mainoptions['soundcloud_api_key']);


      $auxa = json_decode($aux);


      $fout = $auxa->stream_url . '&client_id=' . $dzsap->mainoptions['soundcloud_api_key'];


      $cacher[$che['soundcloud_track_id']] = array(
        'source' => $fout
      );


      if ($fout) {

        update_option('dzsap_cache_soundcloudtracks', $cacher);
      }


    }

    return $fout;
  }


  public static function checkIfPostActuallyExistsById($id) {
    if (FALSE === get_post_status($id)) {    // The post does not exist	}
      return false;
    }
    return true;
  }

  public static function generateExtraButtonsForPlayerDeleteEdit($playerId) {
    global $dzsap, $post;
    $extra_buttons_html = '';


    if (isset($dzsap->mainoptions['dzsaap_enable_allow_users_to_edit_own_tracks'])) {


      $id = '';

      if (DZSZoomSoundsHelper::checkIfPostActuallyExistsById($playerId)) {
        $extra_buttons_html .= ' <a rel="nofollow" data-type="iframe" data-source="' . admin_url('post.php') . '?post=' . $playerId . '&action=edit&remove-wp-admin-navigation=on" data-suggested-width="80vw" data-suggested-height="90vh" data-scaling="fill"  class="dzs-button-simple ultibox-item zoomsounds-portal--edit-track-btn" data-playerid="' . $playerId . '"><span class="btn-label">' . esc_html__('Edit', DZSAP_ID) . '</span></a>';
      }

      DZSZoomSoundsHelper::enqueueUltibox();

    }


    $link = site_url();
    $link = dzs_add_query_arg($link, 'dzsap_action', 'delete_track');
    $link = dzs_add_query_arg($link, 'track_id', $playerId);

    // -- generate for .extra-btns-con

    if (DZSZoomSoundsHelper::checkIfPostActuallyExistsById($playerId)) {
      $extra_buttons_html .= ' <a rel="nofollow" onclick=\'if(!window.confirm("' . esc_html__('Are you sure you want to delete this track ?', DZSAP_ID) . ')){ return false; }"\' class="zoomsounds-delete-track-btn" href="' . $link . '" data-playerid="' . $playerId . '"><span class="btn-label">' . esc_html__('Delete', DZSAP_ID) . '</span></a>';
    }


    return $extra_buttons_html;

  }

  public static function sanitize_for_css_class($string) {


    return preg_replace('/[^A-Za-z0-9\-]/', '-', $string); // Removes special chars.
  }

  public static function sanitize_toKey($string) {
    $string = str_replace(' ', '_', $string); // Replaces all spaces with hyphens.
    $string = str_replace('-', '_', $string); // Replaces all spaces with hyphens.

    return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
  }

  /**
   * outputs script
   * @param $dzsap
   */
  public static function echoJavascriptKeyboardControls($dzsap) {
    if ($dzsap->mainoptions['keyboard_show_tooltips'] != 'off' ||
      $dzsap->mainoptions['keyboard_play_trigger_step_back'] != 'off' ||
      $dzsap->mainoptions['keyboard_step_back_amount'] != '5' ||
      $dzsap->mainoptions['keyboard_step_back'] != '37' ||
      $dzsap->mainoptions['keyboard_step_forward'] != '39' ||
      $dzsap->mainoptions['keyboard_sync_players_goto_prev'] != '' ||
      $dzsap->mainoptions['keyboard_sync_players_goto_next'] != '' ||
      $dzsap->mainoptions['keyboard_pause_play'] != '32'
    ) {


      $keyboardControls = array(
        'play_trigger_step_back'=>$dzsap->mainoptions['keyboard_play_trigger_step_back'],
        'step_back_amount'=>$dzsap->mainoptions['keyboard_step_back_amount'],
        'step_back'=>$dzsap->mainoptions['keyboard_step_back'],
        'step_forward'=>$dzsap->mainoptions['keyboard_step_forward'],
        'sync_players_goto_prev'=>$dzsap->mainoptions['keyboard_sync_players_goto_prev'],
        'sync_players_goto_next'=>$dzsap->mainoptions['keyboard_sync_players_goto_next'],
        'pause_play'=>$dzsap->mainoptions['keyboard_pause_play'],
        'show_tooltips'=>$dzsap->mainoptions['keyboard_show_tooltips'],
      );

      echo '<script id="dzsap-keyboard-controls" type="application/json">';
      echo json_encode($keyboardControls);
      echo '</script>';
    }
  }

  /** here we will detect video player configs and call parse_items To Be Continued...
   * audio player configuration setup
   * @param $vpconfig_id
   * @param array $margs
   * @return array|false|mixed|string[][]|void $vpsettings['settings'] --- returns default-settings-for-zoomsounds if none found
   */
  public static function getVpSettings($vpconfig_id, $margs = array()) {

    global $dzsap;
    $vpsettingsdefault = array(
      'id' => 'default',
      'skin_ap' => 'skin-wave',
      'skinwave_dynamicwaves' => 'off',
      'skinwave_enablespectrum' => 'off',
      'skinwave_enablereflect' => 'on',
      'skinwave_comments_enable' => 'off',
      'disable_volume' => 'default',
      'playfrom' => 'default',
      'enable_embed_button' => 'off',
      'loop' => 'off',
      'soundcloud_track_id' => '',
      'soundcloud_secret_token' => '',
      'cue_method' => 'on',
    );

    $vpsettings = array();

    $vpconfig_k = null;


    $tryToGetVpConfigDefault = DZSZoomSoundsHelper::getVpConfigFromConfigsDatabase(DZSAP_VPCONFIGS_DEFAULT_SETTINGS_NAME);

    if ($tryToGetVpConfigDefault !== null) {
      $vpsettingsdefault = $tryToGetVpConfigDefault['settings'];

    }

    // -- if we have config as array
    if (isset($margs['config']) && is_array($margs['config'])) {
      $vpsettings['settings'] = $margs['config'];
    } else {
      $tryToGetVpConfig = DZSZoomSoundsHelper::getVpConfigFromConfigsDatabase($vpconfig_id);


      if ($tryToGetVpConfig !== null) {
        $vpsettings = $tryToGetVpConfig;
      } else {
        $vpsettings['settings'] = $vpsettingsdefault;
      }

      if (is_array($vpsettings) == false || is_array($vpsettings['settings']) == false) {
        $vpsettings = array('settings' => $vpsettingsdefault);
      }
    }

    if (isset($margs['config']) && $margs['config'] == 'called_from_vpconfig_admin_preview') {
      $vpsettings = get_option('dzsap_temp_vpconfig');
    }


    return $vpsettings;
  }

  public static function getVpConfigFromConfigsDatabase($vpconfig_id) {
    global $dzsap;
    $vpconfig_k = null;


    $vpsettingsdefault = array(
      'id' => 'default',
      'skin_ap' => 'skin-wave',
      'skinwave_dynamicwaves' => 'off',
      'skinwave_enablespectrum' => 'off',
      'skinwave_enablereflect' => 'on',
      'skinwave_comments_enable' => 'off',
      'skinwave_mode' => 'normal',
    );

    for ($i = 0; $i < count($dzsap->mainitems_configs); $i++) {
      if ((isset($vpconfig_id)) && isset($dzsap->mainitems_configs[$i]) && isset($dzsap->mainitems_configs[$i]['settings']) && ($vpconfig_id == $dzsap->mainitems_configs[$i]['settings']['id'])) {
        $vpconfig_k = $i;
      }
    }

    if ($vpconfig_k !== null) {
      return $dzsap->mainitems_configs[$vpconfig_k];
    }

    return array('settings' => $vpsettingsdefault);
  }

  public static function check_playlist_exists($term_name) {


    global $dzsap;

    $new_term_name = $term_name;
    $new_term_slug = $new_term_name;


    if (is_array(term_exists($new_term_name, DZSAP_TAXONOMY_NAME_SLIDERS))) {
      return true;
    }


    return false;
  }

  public static function player_parseItems_generateSinglePlayerIds(&$isPlayerIdFake, &$singleItemInstance, $singlePlayerOptions) {


    if (isset($singleItemInstance['ID']) && $singleItemInstance['playerid'] == false && is_numeric($singleItemInstance['ID'])) {
      $singleItemInstance['wpPlayerPostId'] = $singleItemInstance['ID'];
    } else {

      if (isset($singleItemInstance['playerid']) && $singleItemInstance['playerid'] != '') {
        if (get_post_status($singleItemInstance['playerid'])) {

          $singleItemInstance['wpPlayerPostId'] = $singleItemInstance['playerid'];
        }
      }
    }

    if (isset($singlePlayerOptions['player_id']) && $singlePlayerOptions['player_id']) {

      $singlePlayerOptions['wpPlayerPostId'] = $singlePlayerOptions['player_id'];
    } else {

      if (isset($singlePlayerOptions['wpPlayerPostId']) && $singlePlayerOptions['wpPlayerPostId']) {
        $singlePlayerOptions['player_id'] = $singlePlayerOptions['wpPlayerPostId'];
      }
    }


    if (isset($singleItemInstance['wpPlayerPostId']) && $singleItemInstance['wpPlayerPostId'] != '') {
      $singleItemInstance['playerId_computed'] = $singleItemInstance['wpPlayerPostId'];
    }


    if (!(isset($singleItemInstance['playerId_computed']) && $singleItemInstance['playerId_computed'])) {
      $singleItemInstance['playerId_computed'] = DZSZoomSoundsHelper::encode_to_number($singleItemInstance['source']);
      $isPlayerIdFake = true;
    }
  }

  /**
   * @param string $inputName
   * @param array $inputOptions
   * @param $seekval
   */
  public static function admin_generate_selectVisual($inputName, $inputOptions, $seekval) {
    $lab = $inputName;
    echo DZSHelpers::generate_select($lab, array('class' => 'dzs-style-me  dzs-dependency-field opener-listbuttons', 'options' => $inputOptions, 'seekval' => $seekval));

    ?>

    <ul class="dzs-style-me-feeder">
    <?php
    foreach ($inputOptions as $inputOption) {
      ?>
      <div class="bigoption">
      <span class="option-con"><img
          src="<?php echo $inputOption['visual_option_image_src']; ?>"><span
          class="option-label"><?php echo $inputOption['visual_option_label']; ?></span></span>
      </div><?php
    }
    ?>
    </ul><?php

  }

  public static function player_parseItems_generateTags($playerid) {

    $i_fout = '';


    $taxonomy = 'dzsap_tags';


    $term_list = wp_get_post_terms($playerid, $taxonomy, array("fields" => "all"));


    if (is_array($term_list) && count($term_list) > 0) {


      // -- todo: outside player, do we need it inside ?
      $i_fout .= '
    <div class="tag-list">';

      $cach_tag = $term_list[0];
      $i_fout .= ' <a rel="nofollow" class="dzsap-tag" href="';


      $i_fout .= add_query_arg(array(
        'query_song_tag' => $cach_tag->slug
      ), dzs_curr_url());

      $i_fout .= '">';
      $i_fout .= $cach_tag->name;
      $i_fout .= '</a>';
      if (count($term_list) > 2) {


        $i_fout .= '<span class="dzstooltip-con dzsap--tag" style=""><span
          class="dzstooltip talign-end style-rounded transition-slidein arrow-top   color-dark-light style-rounded"
          style="width: auto;white-space:nowrap; width: 5px; height: 5px; border-radius: 50%;"><span
            class="dzstooltip--inner">';

        foreach ($term_list as $lab => $term) {


          if ($lab) {


            $cach_tag = $term;
            $i_fout .= ' <a rel="nofollow" class="dzsap-tag" href="';


            $i_fout .= add_query_arg(array(
              'query_song_tag' => $cach_tag->slug
            ), dzs_curr_url());

            $i_fout .= '">';
            $i_fout .= $cach_tag->name;

            $i_fout .= '</a>';

          }

        }
        $i_fout .= '</span></span>';

        $i_fout .= '<span class="the-label">...</span>';
      }

      $i_fout .= '</div>';

    }

    return $i_fout;
  }

  public static function sanitize_item_for_parse_items($i, $singleItemInstance, $its) {


    global $dzsap;


    // -- sanitizing
    if (isset($singleItemInstance['wrapper_image']) == false || $singleItemInstance['wrapper_image'] == '') {
      if (isset($singleItemInstance['cover']) && $singleItemInstance['cover']) {
        $singleItemInstance['wrapper_image'] = $singleItemInstance['cover'];
      } else {
        $singleItemInstance['wrapper_image_type'] = '';
      }
    }


    $audioPostId = '';

    // -- let us assign default
    if ($singleItemInstance['songname']) {

    } else {
      $singleItemInstance['songname'] = 'default';

    }


    if ($singleItemInstance['artistname']) {

    } else {
      $singleItemInstance['artistname'] = 'default';
    }


    if ($singleItemInstance['songname'] == 'default') {
      if (isset($singleItemInstance['the_post_title']) && $singleItemInstance['the_post_title']) {

        $singleItemInstance['songname'] = $singleItemInstance['the_post_title'];
      }
      if ($singleItemInstance['menu_songname'] && $singleItemInstance['menu_songname'] != 'default' && $singleItemInstance['menu_songname'] != 'none') {
        $singleItemInstance['songname'] = $singleItemInstance['menu_songname'];
      }


    }

    if ($singleItemInstance['artistname'] == 'default') {
      if ($singleItemInstance['menu_artistname'] && $singleItemInstance['menu_artistname'] != 'default' && $singleItemInstance['menu_artistname'] != 'none') {
        $singleItemInstance['artistname'] = $singleItemInstance['menu_artistname'];
      }
    }


    $singleItemInstance['extra_html'] = str_replace('{{lsqb}}', '[', $singleItemInstance['extra_html']);
    $singleItemInstance['extra_html'] = str_replace('{{rsqb}}', ']', $singleItemInstance['extra_html']);


    if ($singleItemInstance['source'] && is_numeric($singleItemInstance['source'])) {
      $player_post_id = intval($singleItemInstance['source']);
      $player_post = get_post(intval($singleItemInstance['source']));


      if ($player_post && $player_post->post_type == 'attachment') {
        $media = wp_get_attachment_url($player_post_id);

        $singleItemInstance['source'] = $media;
        if (isset($audioPostId)) {

        } else {
          $audioPostId = $player_post_id;
          $singleItemInstance['playerid'] = $player_post_id;
        }


      }


      if (isset($singleItemInstance['ID']) && $singleItemInstance['playerid'] == false && is_numeric($singleItemInstance['ID'])) {
        $singleItemInstance['playerid'] = $singleItemInstance['ID'];
      }


      if (isset($its['settings']['js_settings_extrahtml_in_bottom_controls_from_config']) && $its['settings']['js_settings_extrahtml_in_bottom_controls_from_config']) {

        if (isset($singleItemInstance['extra_html_in_bottom_controls']) && $singleItemInstance['extra_html_in_bottom_controls']) {

        } else {


          $singleItemInstance['extra_html_in_bottom_controls'] = DZSZoomSoundsHelper::sanitize_from_meta_textarea($its['settings']['js_settings_extrahtml_in_bottom_controls_from_config']);
        }
      }
    }


    if (isset($singleItemInstance['playerid']) && $singleItemInstance['playerid'] != '') {
      $audioPostId = $singleItemInstance['playerid'];
    }
    if (isset($singleItemInstance['ID']) && $singleItemInstance['ID'] != '') {
      $audioPostId = $singleItemInstance['ID'];
    }


    if ($audioPostId == '' && isset($singleItemInstance['linktomediafile']) && $singleItemInstance['linktomediafile'] != '') {
      $audioPostId = $singleItemInstance['linktomediafile'];
    }


    $po = null;


    if ($audioPostId) {
      $po = get_post($audioPostId);


      $meta = wp_get_attachment_metadata($audioPostId);


      // -- we need to get source from library on mediafile
      if ($singleItemInstance['type'] == 'mediafile') {
        $singleItemInstance['source'] = '';
      }


      // -- from mediafile
      if (@wp_get_attachment_url($audioPostId)) {
        if ($singleItemInstance['source'] == '') {

          $singleItemInstance['source'] = @wp_get_attachment_url($audioPostId);
        }
      }


      if ($singleItemInstance['source'] == '' && $po) {
        $singleItemInstance['source'] = $po->guid;

      }


      if ((!isset($singleItemInstance['artistname_from_meta']) || $singleItemInstance['artistname_from_meta'] == '')) {


        if (isset($meta['artist'])) {

          $singleItemInstance['artistname_from_meta'] = $meta['artist'];
        }
      };


      if ((!isset($singleItemInstance['songname_from_meta']) || $singleItemInstance['songname_from_meta'] == '')) {


        if (isset($meta['title'])) {

          $singleItemInstance['songname_from_meta'] = $meta['title'];
        }
      };
      if ((!isset($singleItemInstance['publisher']) || $singleItemInstance['publisher'] == '')) {


        if (isset($meta['publisher'])) {

          $singleItemInstance['publisher'] = $meta['publisher'];
        }
      };


      // -- @deprecated
      if ((!isset($singleItemInstance['waveformbg']) || $singleItemInstance['waveformbg'] == '') && $po && get_post_meta($po->ID, '_waveformbg', true) != '') {
        $singleItemInstance['waveformbg'] = get_post_meta($po->ID, '_waveformbg', true);
      };


      if ((!isset($singleItemInstance['waveformprog']) || $singleItemInstance['waveformprog'] == '') && $po && get_post_meta($po->ID, '_waveformprog', true) != '') {
        $singleItemInstance['waveformprog'] = get_post_meta($po->ID, '_waveformprog', true);
      };
      // -- @deprecated waveform jpeg END


      if ((isset($singleItemInstance['thumb']) == false || $singleItemInstance['thumb'] == '') && isset($po)) {


        if (get_post_meta($po->ID, '_dzsap-thumb', true)) {

          $singleItemInstance['thumb'] = get_post_meta($po->ID, '_dzsap-thumb', true);
        } else {

        }
      };


    }


    if ($dzsap->mainoptions['try_to_hide_url'] == 'on' && ((isset($singleItemInstance['linktomediafile']) && $singleItemInstance['linktomediafile']) || is_int($audioPostId) || (isset($singleItemInstance['product_id']) && $singleItemInstance['product_id']) || (isset($singleItemInstance['wpPlayerPostId']) && $singleItemInstance['wpPlayerPostId']))) {

      $srcNonce = rand(0, 10000);
      $id_for_nonce = '';
      if (is_int($audioPostId)) {
        $id_for_nonce = $audioPostId;
      } else {
        if ((isset($singleItemInstance['product_id']) && $singleItemInstance['product_id'])) {
          $id_for_nonce = $singleItemInstance['product_id'];
        } else {
          if ((isset($singleItemInstance['wpPlayerPostId']) && $singleItemInstance['wpPlayerPostId'])) {
            $id_for_nonce = $singleItemInstance['wpPlayerPostId'];
          }
        }
      }
      $labelNonceKey = 'dzsap_nonce_for_' . urlencode($id_for_nonce) . '_ip_' . urlencode($_SERVER['REMOTE_ADDR']);


      $labelNonceKey = DZSZoomSoundsHelper::sanitize_toKey($labelNonceKey);

      $srcNonce = '{{generatenonce}}';
      $str_queryPlayInFooterPlayer = '';

      if (isset($singleItemInstance['play_in_footer_player']) && $singleItemInstance['play_in_footer_player'] === 'on') {
        $str_queryPlayInFooterPlayer = '&play_in_footer_player=on';
      }


      $src = site_url() . '/index.php?dzsap_action=' . DZSAP_VIEW_NONCE_IDENTIFIER . '&id=' . $id_for_nonce . '&' . $labelNonceKey . '=' . $srcNonce . $str_queryPlayInFooterPlayer;

      $singleItemInstance['source'] = $src;
    }


    if (isset($singleItemInstance['artistname_from_meta'])) {
      if ($singleItemInstance['artistname_from_meta'] && $singleItemInstance['artistname_from_meta'] != 'default') {
        if ($singleItemInstance['artistname'] == '' || $singleItemInstance['artistname'] == 'default') {
          $singleItemInstance['artistname'] = $singleItemInstance['artistname_from_meta'];
        }
      }
    }


    if ($singleItemInstance['songname'] == 'default' || $singleItemInstance['songname'] == '') {

      if (get_post_meta($audioPostId, 'songname', true)) {
        $singleItemInstance['songname'] = get_post_meta($audioPostId, 'songname', true);
      } else {


        if (get_post_meta($audioPostId, DZSAP_META_OPTION_PREFIX . 'replace_songname', true)) {
          $singleItemInstance['songname'] = get_post_meta($audioPostId, DZSAP_META_OPTION_PREFIX . 'replace_songname', true);
        } else {

          if ($po) {


            // -- parse item get forom post_title

            if ($po->post_title) {
              if (isset($singleItemInstance['title_is_permalink']) && $singleItemInstance['title_is_permalink'] == 'on') {

                $singleItemInstance['songname'] = ' <a rel="nofollow" href="' . get_permalink($po->ID) . '">' . $po->post_title .
                  '</a>';
              } else {

                $singleItemInstance['songname'] = $po->post_title;
              }
            }

          } else {

            if (isset($singleItemInstance['linktomediafile']) && $singleItemInstance['linktomediafile']) {
              $po_att = get_post($singleItemInstance['linktomediafile']);

              if ($po_att->post_title) {
                $singleItemInstance['songname'] = $po_att->post_title;
              }
            }
          }
        }
      }


    }
    if ($singleItemInstance['artistname'] == 'default') {


      if (get_post_meta($audioPostId, 'artistname', true)) {
        $singleItemInstance['artistname'] = get_post_meta($audioPostId, 'artistname', true);
      } else {
        if (get_post_meta($audioPostId, DZSAP_META_OPTION_PREFIX . 'replace_artistname', true)) {
          $singleItemInstance['artistname'] = get_post_meta($audioPostId, DZSAP_META_OPTION_PREFIX . 'replace_artistname', true);
        } else {

          if (isset($singleItemInstance['linktomediafile']) && $singleItemInstance['linktomediafile']) {
            $po_att = get_post($singleItemInstance['linktomediafile']);


            $user_info = get_userdata($po_att->post_author);
            if ($user_info->user_login) {
              $singleItemInstance['artistname'] = $user_info->user_login;
            }
          }
        }
      }


    }

    if (isset($singleItemInstance['songname_from_meta'])) {
      if ($singleItemInstance['songname_from_meta'] && $singleItemInstance['songname_from_meta'] != 'default') {

        if ($singleItemInstance['songname'] === '' || $singleItemInstance['songname'] == 'default') {

          $singleItemInstance['songname'] = $singleItemInstance['songname_from_meta'];
        }
      }
    }

    $singleItemInstance['menu_artistname'] = stripslashes($singleItemInstance['menu_artistname']);
    $singleItemInstance['menu_songname'] = stripslashes($singleItemInstance['menu_songname']);

    if ($singleItemInstance['menu_songname'] === '' || $singleItemInstance['menu_songname'] == 'default') {
      $singleItemInstance['menu_songname'] = $singleItemInstance['songname'];
    }
    if ($singleItemInstance['menu_artistname'] === '' || $singleItemInstance['menu_artistname'] == 'default') {
      $singleItemInstance['menu_artistname'] = $singleItemInstance['artistname'];
    }


    if ($singleItemInstance['songname'] == 'none' || $singleItemInstance['songname'] == 'default') {
      $singleItemInstance['songname'] = '';
    }
    if ($singleItemInstance['artistname'] == 'none' || $singleItemInstance['artistname'] == 'default') {
      $singleItemInstance['artistname'] = '';
    }
    if ($singleItemInstance['menu_songname'] == 'none' || $singleItemInstance['menu_songname'] == 'default') {
      $singleItemInstance['menu_songname'] = '';
    }
    if ($singleItemInstance['menu_artistname'] == 'none' || $singleItemInstance['menu_artistname'] == 'default') {
      $singleItemInstance['menu_artistname'] = '';
    }


    if ($singleItemInstance['menu_artistname'] == 'default') {


      if ($singleItemInstance['artistname']) {
        $singleItemInstance['menu_artistname'] = $singleItemInstance['artistname'];
      } else {
        if ($audioPostId) {


          $singleItemInstance['menu_artistname'] = $po->post_title;
        }
      }


    }


    if ($singleItemInstance['menu_songname'] == 'default') {

      if ($singleItemInstance['songname']) {
        $singleItemInstance['menu_songname'] = $singleItemInstance['songname'];
      } else {
        if ($audioPostId) {


          if ($po->post_content) {
            $singleItemInstance['menu_songname'] = $po->post_content;
          }


          if ($po->post_excerpt) {
            $singleItemInstance['menu_songname'] = $po->post_excerpt;
          }

          if ($po->post_type == 'attachment') {
            $po_metadata = wp_get_attachment_metadata($audioPostId);


          }

        }

      }
    }
    if ($singleItemInstance['menu_artistname'] == 'default') {


      $singleItemInstance['menu_artistname'] = '';
    }
    if ($singleItemInstance['menu_songname'] == 'default') {
      $singleItemInstance['menu_songname'] = '';
    }


    return $singleItemInstance;


  }
}

function dzsap_register_links() {

  global $dzsap;


  register_taxonomy(DZSAP_REGISTER_POST_TYPE_CATEGORY, DZSAP_REGISTER_POST_TYPE_NAME, array('label' => esc_html__('Audio Categories', DZSAP_ID), 'query_var' => true, 'show_ui' => true, 'hierarchical' => true, 'rewrite' => array('slug' => $dzsap->mainoptions['dzsap_categories_rewrite']),));


  register_taxonomy(DZSAP_REGISTER_POST_TYPE_TAGS, DZSAP_REGISTER_POST_TYPE_NAME, array('label' => esc_html__('Song tags', DZSAP_ID), 'query_var' => true, 'show_ui' => true, 'hierarchical' => false, 'rewrite' => array('slug' => $dzsap->mainoptions['dzsap_tags_rewrite']),));


  $labels = array(
    'name' => esc_html__('Audio galleries', DZSAP_ID),
    'singular_name' => esc_html__('Audio gallery', DZSAP_ID),
    'search_items' => esc_html__('Search galleries', DZSAP_ID),
    'all_items' => esc_html__('All galleries', DZSAP_ID),
    'parent_item' => esc_html__('Parent gallery', DZSAP_ID),
    'parent_item_colon' => esc_html__('Parent gallery', DZSAP_ID),
    'edit_item' => esc_html__('Edit gallery', DZSAP_ID),
    'update_item' => esc_html__('Update gallery', DZSAP_ID),
    'add_new_item' => esc_html__('Add playlist', DZSAP_ID),
    'new_item_name' => esc_html__('New gallery name', DZSAP_ID),
    'menu_name' => esc_html__('Galleries', DZSAP_ID),


  );


  $cap_manage_terms = DZSAP_TAXONOMY_NAME_SLIDERS . '_manage_categories';

  if (current_user_can(DZSAP_PERMISSION_ULTIMATE)) {
    $cap_manage_terms = DZSAP_PERMISSION_ULTIMATE;
  }

  register_taxonomy(DZSAP_TAXONOMY_NAME_SLIDERS, DZSAP_REGISTER_POST_TYPE_NAME, array(

    'label' => esc_html__('Audio Playlists', DZSAP_ID),
    'labels' => $labels,
    'query_var' => true,
    'show_ui' => true,
    'hierarchical' => false,
    'rewrite' => array('slug' => $dzsap->mainoptions['dzsap_sliders_rewrite']),
    'show_in_menu' => false,
    'capabilities' => array(
      'manage_terms' => $cap_manage_terms,
      'edit_terms' => $cap_manage_terms,
      'delete_terms' => $cap_manage_terms,
      'assign_terms' => $cap_manage_terms,
    ),
  ));


  add_action('category_edit_form_fields', 'dzsap_term_meta_fields', 10, 10);


  add_action('edited_category', 'dzsap_save_taxonomy_custom_meta', 10, 2);


  $labels = array('name' => esc_html__('Audio Items', DZSAP_ID), 'singular_name' => esc_html__('Audio Item', DZSAP_ID),);

  $permalinks = get_option('dzsap_permalinks');


  $item_slug_permalink = empty($permalinks['item_base']) ? _x('audio', 'slug', DZSAP_ID) : $permalinks['item_base'];


  $exclude_from_search = false;

  if (isset($dzsap->mainoptions['exclude_from_search']) && $dzsap->mainoptions['exclude_from_search'] == 'on') {
    $exclude_from_search = true;
  }


  $post_supports = array('title', 'editor', 'author', 'thumbnail', 'post-thumbnail', 'comments', 'excerpt', 'custom-fields');
  // -- todo: allow custom-fields in developer mode
  $args = array(
    'labels' => $labels,
    'public' => true,
    'has_archive' => true,
    'hierarchical' => false,
    'supports' => $post_supports,


    'can_export' => true,
    'menu_icon' => 'dashicons-controls-volumeon',
    'show_in_menu' => true, 'rewrite' => array('slug' => $item_slug_permalink),
    'yarpp_support' => true,
    'show_ui' => true,
    'exclude_from_search' => $exclude_from_search,


    'capabilities' => array(
      'edit_post' => 'edit_' . DZSAP_REGISTER_POST_TYPE_NAME,
      'edit_posts' => 'edit_' . DZSAP_REGISTER_POST_TYPE_NAME,
      'edit_others_posts' => 'edit_others_' . DZSAP_REGISTER_POST_TYPE_NAME,
      'edit_published_posts' => 'edit_others_' . DZSAP_REGISTER_POST_TYPE_NAME,
      'delete_post' => 'edit_' . DZSAP_REGISTER_POST_TYPE_NAME,
    ),

  );

  if (current_user_can(DZSAP_PERMISSION_ULTIMATE)) {
    if (!current_user_can('edit_' . DZSAP_REGISTER_POST_TYPE_NAME)) {
      $role = get_role('administrator');
      $role->add_cap('edit_' . DZSAP_REGISTER_POST_TYPE_NAME);
      $role->add_cap('edit_others_' . DZSAP_REGISTER_POST_TYPE_NAME);

    }
  }

  register_post_type(DZSAP_REGISTER_POST_TYPE_NAME, $args);
}


function dzsap_term_meta_fields($term) {
  // this will add the custom meta field to the add new term page

  $t_id = $term->term_id;

  // retrieve the existing value(s) for this meta field. This returns an array
  $term_meta = get_option("taxonomy_$t_id");

  $tem = array(
    'name' => 'feed_xml',
    'no_preview' => 'default',
    'title' => esc_html__('XML Feed'),
  );

  ?>
  <tr class="form-field">
    <th scope="row" valign="top"><label
        for="term_meta[<?php echo $tem['name']; ?>]"><?php echo $tem['title']; ?></label></th>
    <td class="<?php
    if (isset($tem['type']) && $tem['type'] == 'media-upload') {
      echo 'setting-upload';
    }
    ?>">


      <?php


      if (isset($tem['type']) && $tem['type'] == 'media-upload') {
        if ($tem['no_preview'] != 'on') {
          echo '<span class="uploader-preview"></span>';
        }

      }
      ?>



      <?php
      $lab = 'term_meta[' . $tem['name'] . ']';

      $val = '';

      if (isset($term_meta[$tem['name']])) {

        $val = esc_attr($term_meta[$tem['name']]) ? esc_attr($term_meta[$tem['name']]) : '';
        $val = stripslashes($val);
      }

      $class = 'setting-field medium';


      if (isset($tem['type']) && $tem['type'] == 'media-upload') {
        $class .= ' uploader-target';
      }


      echo DZSHelpers::generate_input_textarea($lab, array(
        'class' => $class,
        'seekval' => $val,
        'extraattr' => ' style="width: 100%; " rows="5"',
        'id' => $lab,
      ));


      ?>
      <?php

      ?>
      <p class="description"><?php echo esc_html__('Enter a value for this field', 'pippin'); ?></p>
    </td>
  </tr>
  <?php
}


function dzsap_save_taxonomy_custom_meta($term_id) {
  if (isset($_POST['term_meta'])) {
    $t_id = $term_id;
    $term_meta = get_option("taxonomy_$t_id");
    $cat_keys = array_keys($_POST['term_meta']);
    foreach ($cat_keys as $key) {
      if (isset ($_POST['term_meta'][$key])) {
        $term_meta[$key] = $_POST['term_meta'][$key];
      }
    }
    // Save the option array.
    update_option("taxonomy_$t_id", $term_meta);
  }
}

function dzsap_sliders_save_taxonomy_custom_meta($term_id) {


  if (isset($_POST['term_meta'])) {
    $t_id = $term_id;
    $term_meta = get_option("taxonomy_$t_id");
    $cat_keys = array_keys($_POST['term_meta']);
    foreach ($cat_keys as $key) {
      if (isset ($_POST['term_meta'][$key])) {
        $term_meta[$key] = $_POST['term_meta'][$key];
      }
    }
    // Save the option array.
    update_option("taxonomy_$t_id", $term_meta);
  }
}


function dzsap_enqueue_fontawesome() {

  global $dzsap;

  $url = DZSAP_URL_FONTAWESOME_EXTERNAL;

  if ($dzsap->mainoptions['fontawesome_load_local'] == 'on') {
    $url = DZSAP_BASE_URL . 'libs/fontawesome/font-awesome.min.css';
  }


  wp_enqueue_style('fontawesome', $url);
}


function dzsap_misc_input_text($argname, $pargs = array()) {
  $fout = '';

  $margs = array('type' => 'text', 'class' => '', 'seekval' => '', 'extra_attr' => '',);


  $margs = array_merge($margs, $pargs);

  $type = 'text';
  if (isset($margs['type'])) {
    $type = $margs['type'];
  }
  $fout .= '<input type="' . $type . '"';
  if (isset($margs['class'])) {
    $fout .= ' class="' . $margs['class'] . '"';
  }
  $fout .= ' name="' . $argname . '"';
  if (isset($margs['seekval'])) {
    $fout .= ' value="' . $margs['seekval'] . '"';
  }

  $fout .= $margs['extra_attr'];

  $fout .= '/>';
  return $fout;
}

function dzsap_misc_input_textarea($argname, $otherargs = array()) {
  $fout = '';
  $fout .= '<textarea';
  $fout .= ' name="' . $argname . '"';

  $margs = array(
    'class' => '',
    'val' => '', // === default value
    'seekval' => '', // ===the value to be seeked
    'type' => '',
  );
  $margs = array_merge($margs, $otherargs);


  if ($margs['class'] != '') {
    $fout .= ' class="' . $margs['class'] . '"';
  }
  $fout .= '>';
  if (isset($margs['seekval']) && $margs['seekval'] != '') {
    $fout .= '' . $margs['seekval'] . '';
  } else {
    $fout .= '' . $margs['val'] . '';
  }
  $fout .= '</textarea>';

  return $fout;
}

function dzsap_misc_generate_upload_btn($pargs = array()) {

  global $dzsap;
  $margs = array(
    'label' => 'Upload'
  );

  if ($pargs == '' || is_array($pargs) == false) {
    $pargs = array();
  }

  $margs = array_merge($margs, $pargs);

  $uploadbtnstring = '<button class="button-secondary action upload_file ">' . $margs['label'] . '</button>';


  return $uploadbtnstring;
}


function dzsap_misc_get_ip() {

  if (isset($_SERVER['HTTP_CLIENT_IP']) && !empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ip = $_SERVER['HTTP_CLIENT_IP'];
  } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && !empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
  } else {
    $ip = (isset($_SERVER['REMOTE_ADDR'])) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0';
  }

  $ip = filter_var($ip, FILTER_VALIDATE_IP);
  $ip = ($ip === false) ? '0.0.0.0' : $ip;


  return $ip;
}

function dzsap_create_user($user_name, $user_email) {
  $user_id = 0;
  $user_id = username_exists($user_name);
  if (!$user_id and email_exists($user_email) == false) {
    $random_password = 'test';
    $user_id = wp_create_user($user_name, $random_password, $user_email);
    update_option('dzsapp_portal_user', $user_id);
  } else {
    $random_password = esc_html__('User already exists.  Password inherited.');
  }

  return $user_id;
}


function dzsap_powerpress_shortcode_player() {


  global $powerpress_feed, $dzsap, $post;
  // -- PowerPress settings:
  $GeneralSettings = get_option('powerpress_general');
  $feed_slug = 'podcast';

  $EpisodeData = null;
  if (function_exists('powerpress_get_enclosure_data')) {
    $EpisodeData = powerpress_get_enclosure_data($post->ID, $feed_slug);
  }


  if ($EpisodeData && isset($EpisodeData['url'])) {


    $dzsap->sliders__player_index++;


    $dzsap->front_scripts();

    $margs = dzsap_powerpress_generate_margs();


    $args = array();

    $margs['called_from'] = 'pooowerpress';
    $aux = $dzsap->classView->shortcode_player($margs);


    return $aux;


  }

}


function dzsap_powerpress_filter_content($fout) {

  global $post, $powerpress_feed;

  global $dzsap;


// PowerPress settings:
  $GeneralSettings = get_option('powerpress_general');


  $feed_slug = 'podcast';


  $EpisodeData = null;
  if (function_exists('powerpress_get_enclosure_data')) {

    $EpisodeData = powerpress_get_enclosure_data($post->ID, $feed_slug);
  }


  if ($EpisodeData && isset($EpisodeData['url'])) {


    $dzsap->sliders__player_index++;


    $src = get_post_meta($post->ID, DZSAP_META_WOOCOMMERCE_ZOOMSOUNDS_MP3, true);


    $dzsap->front_scripts();

    $margs = dzsap_powerpress_generate_margs();


    $args = array();

    $margs['autoplay'] = 'off';
    $aux = $dzsap->classView->shortcode_player($margs);


    return $aux . $fout;


  }

  return $fout;
}

function dzsap_powerpress_get_enclosure_data($feed_slug) {
  global $post, $dzsap;

  $EpisodeData = powerpress_get_enclosure_data($post->ID, $feed_slug);


  if ($EpisodeData && isset($EpisodeData['url'])) {


    $dzsap->sliders__player_index++;


    $src = get_post_meta($post->ID, DZSAP_META_WOOCOMMERCE_ZOOMSOUNDS_MP3, true);


    $dzsap->front_scripts();

    $margs = dzsap_powerpress_generate_margs();


    $enc_margs = json_encode($margs);
    $enc_margs = base64_encode(json_encode($margs));


    $embed_url = site_url() . '?action=embed_zoomsounds&type=player&margs=' . urlencode($enc_margs);
    $embed_code = '<iframe src=\'' . $embed_url . '\' style="overflow:hidden; transition: height 0.3s ease-out;" width="100%" height="180" scrolling="no" frameborder="0"></iframe>';


    ?>
    <meta name="twitter:card" content="player">
    <meta name="twitter:site" content="@youtube">
    <meta name="twitter:url" content="<?php echo get_permalink($post->ID); ?>">
    <meta name="twitter:title" content="<?php echo get_permalink($post->post_title); ?>">
    <meta name="twitter:description" content="<?php echo get_permalink($post->post_content); ?>">
    <meta name="twitter:image" content="">
    <meta name="twitter:app:name:iphone" content="<?php echo get_permalink($post->ID); ?>">
    <meta name="twitter:app:name:googleplay" content="<?php echo get_permalink($post->post_title); ?>">
    <meta name="twitter:player" content="<?php echo $embed_url; ?>">
    <meta name="twitter:player:width" content="1280">
    <meta name="twitter:player:height" content="300"><?php


  }
}

/**
 * generate extra html for multisharer in wp_footer
 */
function dzsap_generateHtmlMultisharer() {

  global $dzsap;


  $socialSection = ($dzsap->mainoptions['multisharer_social_share_section']);

  if ($dzsap->mainoptions['multisharer_social_share_section'] === 'default') {
    $socialSection = '<h6 class="social-heading">' . addslashes(esc_html__("Social Networks", DZSAP_ID)) . '</h6> <a rel="nofollow" class="social-icon" href="#" onclick="window.dzs_open_social_link(&quot;https://www.facebook.com/sharer.php?u={{shareurl}}&amp;title=test&quot;); return false;"><i class="fa fa-facebook-square"></i><span class="the-tooltip">' . addslashes(esc_html__("SHARE ON", DZSAP_ID)) . ' FACEBOOK</span></a> <a rel="nofollow" class="social-icon" href="#" onclick="window.dzs_open_social_link(&quot;https://twitter.com/share?url={{shareurl}}&amp;text=Check this out!&amp;via=ZoomPortal&amp;related=yarrcat&quot;); return false;"><i class="fa fa-twitter"></i><span class="the-tooltip">' . addslashes(esc_html__("SHARE ON", DZSAP_ID)) . ' TWITTER</span></a> <a rel="nofollow" class="social-icon" href="#" onclick="window.dzs_open_social_link(&quot;https://plus.google.com/share?url={{shareurl}}&quot;); return false; "><i class="fa fa-google-plus-square"></i><span class="the-tooltip">' . addslashes(esc_html__("SHARE ON", DZSAP_ID)) . 'GOOGLE PLUS</span></a>  <a rel="nofollow" class="social-icon" href="#" onclick="window.dzs_open_social_link(&quot;https://www.linkedin.com/shareArticle?mini=true&url={{shareurl}}&title=LinkedIn%20Developer%20Network&summary={{shareurl}}%20program&source={{shareurl}}&quot; return false; "><i class="fa fa-linkedin"></i><span class="the-tooltip">' . addslashes(esc_html__("SHARE ON", DZSAP_ID)) . ' LINKEDIN</span></a>  <a rel="nofollow" class="social-icon" href="#" onclick="window.dzs_open_social_link(&quot;https://pinterest.com/pin/create/button/?url={{shareurl}}&amp;text=Check this out!&amp;via=ZoomPortal&amp;related=yarrcat&quot;); return false;"><i class="fa fa-pinterest"></i><span class="the-tooltip">' . addslashes(esc_html__("SHARE ON", DZSAP_ID)) . ' PINTEREST</span></a>';
  }

  $shareLink = ($dzsap->mainoptions['multisharer_shareLink_section']);


  if ($dzsap->mainoptions['multisharer_shareLink_section'] === 'default') {
    $shareLink = '<h6 class="social-heading">' . addslashes(esc_html__("Share Link", DZSAP_ID)) . '</h6> <div class="field-for-view field-for-view-link-code">{{replacewithcurrurl}}</div>';
  }

  $embedSection = ($dzsap->mainoptions['multisharer_embed_section']);


  if ($dzsap->mainoptions['multisharer_embed_section'] === 'default') {
    $embedSection = ' <h6 class="social-heading">' . addslashes(esc_html__("Embed Code", DZSAP_ID)) . '</h6> <div class="field-for-view field-for-view-embed-code">{{replacewithembedcode}}</div>';
  }


  ?>
  <div hidden class="dzsap-feed--social-networks"><?php echo $socialSection ?></div>
  <div hidden class="dzsap-feed--share-link"><?php echo $shareLink ?></div>
  <div hidden class="dzsap-feed--embed-link"><?php echo $embedSection ?></div><?php
  DZSZoomSoundsHelper::enqueueUltibox();
  wp_enqueue_style('fontawesome', DZSAP_URL_FONTAWESOME_EXTERNAL);
  wp_enqueue_style('dzs-multisharer', DZSAP_URL_AUDIOPLAYER . 'parts/dzs-shared/multisharer.css');
  wp_enqueue_script('dzs-multisharer', DZSAP_URL_AUDIOPLAYER . 'parts/dzs-shared/multisharer.js');

}

function dzsap_generateHtmlWoocommerceOverlayPlayer() {
  global $dzsap;


  $singlePlayerPosition = '';
  $loopPlayerOverWriteClass = '';
  if ($dzsap->mainoptions['wc_single_player_position'] == 'overlay') {
    $singlePlayerPosition = $dzsap->mainoptions['wc_single_player_position'];
  }


  if ($dzsap->mainoptions['wc_loop_player_position__overlay__wrapper_selector']) {
    $loopPlayerOverWriteClass = $dzsap->mainoptions['wc_loop_player_position__overlay__wrapper_selector'];
  }
  ?>
  <div hidden class="dzsap-feed-singlePlayerPosition"><?php echo $singlePlayerPosition ?></div>
  <div hidden class="dzsap-feed-loopPlayerOverWriteClass"><?php echo $loopPlayerOverWriteClass ?></div><?php

}


function dzsap_powerpress_generateHtmlEnclosureData() {


  wp_enqueue_style('dzsap-powerpress', DZSAP_BASE_URL . 'inc/style/shortcodes/powerpress-compatibility.css');


  global $post;

  global $powerpress_feed;
  $GeneralSettings = get_option('powerpress_general');


  $feed_slug = 'podcast';


  if (function_exists('powerpress_get_enclosure_data') && $post && $post->post_type == 'post') {


    dzsap_powerpress_get_enclosure_data($feed_slug);


  }
}

function dzsap_powerpress_generate_margs() {

  global $post, $dzsap;

  global $powerpress_feed;
  $GeneralSettings = get_option('powerpress_general');


  $feed_slug = 'podcast';

  $margs = array();

  $EpisodeData = powerpress_get_enclosure_data($post->ID, $feed_slug);


  if ($EpisodeData && isset($EpisodeData['url'])) {


    $dzsap->sliders__player_index++;


    $src = get_post_meta($post->ID, 'dzsap_woo_product_track', true);


    $dzsap->front_scripts();

    $margs = array('config' => 'powerpress_player',);

    $margs['source'] = $EpisodeData['url'];
    $margs['called_from'] = 'powerpress';
    $margs['playerid'] = $post->ID;
    $margs['config'] = 'powerpress_player';
    $margs['artistname'] = $post->post_title;

    if (get_the_post_thumbnail_url($post)) {

      $margs['thumb'] = get_the_post_thumbnail_url($post);
    }


    $categories = get_the_terms($post->ID, 'category');

    if (!$categories || is_wp_error($categories))
      $categories = array();

    $categories = array_values($categories);


    if (count($categories)) {


    }
    foreach ($categories as $key => $val) {


      $category_link = get_category_link($val->term_id);


      $lasttime = get_option('dzsap_last_read_category');


      $myXMLData = '';

      if (get_option('taxonomy_' . $val->term_id)) {
        $aux = get_option('taxonomy_' . $val->term_id);


        if (isset($aux['feed_xml'])) {
          $myXMLData = $aux['feed_xml'];
        }


        $myXMLData = stripslashes($myXMLData);
      }


      if ($myXMLData == '' && $dzsap->mainoptions['powerpress_read_category_xml'] == 'on' && ($lasttime == false || $lasttime < time() - 15)) {


        if ($dzsap->debug) {

          print_rr($category_link . 'feed');
        }
        update_option('dzsap_last_read_category', time());
        $myXMLData = @file_get_contents($category_link . 'feed');

        if ($dzsap->debug) {
          echo '<pre class="hmm">';
          print_r($myXMLData);
          echo '</pre>';
        }


      }

      if ($myXMLData) {


        if (strpos($myXMLData, '<?xml') !== false && strpos($myXMLData, '<?xml') < 30) {


          try {


            preg_match_all("/<itunes:image href=\"(.*?)\"/", $myXMLData, $output_array);;

            if (count($output_array[1])) {
              $margs['thumb'] = $output_array[1][0];

            }

            preg_match_all("/\<title\>(.*?)<\/title>/", $myXMLData, $output_array);

            if (count($output_array[1])) {
              $margs['songname'] = $output_array[1][0];

            }


          } catch (Exception $e) {
            echo 'xml error';
            error_log(print_rrr($e));
          }

        }

        $margs['cat_feed_data'] = $myXMLData;


      }

    }

  }


  return $margs;


}

function dzsap_admin_meta_download_waveforms() {

  global $post, $dzsap;

  $po_id = $post->ID;

  $aux = '';
  $uploadbtnstring = '<button class="button-secondary action upload_file ">' . esc_html__('Upload', DZSAP_ID) . '</button>';


  echo $aux;
}


function dzsap_sanitize_to_extra_html($extra_html, $po = null) {


  global $dzsap;
  $playerid = 0;


  if ($po) {
    if (isset($po->ID)) {

      $playerid = $po->ID;
    }
    if (isset($po['playerid'])) {
      $playerid = $po['playerid'];
    }
  }


  $extra_html = str_replace('{{theid}}', $playerid, $extra_html);


  $icon_wishlist = 'fa-heart-o';
  if (strpos($extra_html, '{{audio_love_toggler_icon}}') !== false) {


    global $dzsap;


    if (dzsap_check_if_user_liked_track($playerid)) {
      $icon_wishlist = str_replace('fa-heart-o', 'fa-heart', $icon_wishlist);
    }


    $extra_html = str_replace('{{audio_love_toggler_icon}}', $icon_wishlist, $extra_html);
  }


  $extra_html = str_replace('{{hearts_svg}}', $dzsap->general_assets['hearts_svg'], $extra_html);
  $extra_html = str_replace('{{site_url}}', site_url(), $extra_html);

  $permalink = '';
  if (isset($po) && $po && isset($po->ID) && $po->ID) {
    $permalink = get_permalink($po->ID);
  }
  $extra_html = str_replace('{{itempermalink}}', $permalink, $extra_html);

  return $extra_html;
}

function dzsap_get_songname_from_attachment($che) {


  $songname = '';
  $attachment_id = '';


  if (isset($che[DZSAP_META_OPTION_PREFIX . 'source_attachment_id']) && $che[DZSAP_META_OPTION_PREFIX . 'source_attachment_id']) {

    $attachment_id = $che[DZSAP_META_OPTION_PREFIX . 'source_attachment_id'];

  } else {
    if ($che && isset($che['ID']) && $che['ID']) {
      $attachment_id = get_post_meta($che['ID'], DZSAP_META_OPTION_PREFIX . 'source_attachment_id', true);
    }
  }

  if ($attachment_id) {


    $att = get_post($attachment_id);


    if (isset($att->post_title) && $att->post_title) {
      $songname = $att->post_title;
    }
  }


  return $songname;
}


/**
 * @param object $che
 * @param int $playerId
 * @return int
 */
function dzsap_utils_getProductId($che, $playerId){

  $productId = '';
  if (isset($che['productid']) && $che['productid']) {
    $productId = $che['productid'];
  } else {
    $allmeta = get_post_meta($playerId);

    $lab = DZSAP_META_OPTION_PREFIX . 'productid';
    if (isset($allmeta[$lab]) && $allmeta[$lab] && isset($allmeta[$lab][0]) && $allmeta[$lab][0]) {

      $productId = $allmeta[$lab][0];
    }
  }

  if (!$productId) {
    global $post;
    if ($post && $post->post_type == 'product') {
      $productId = $post->ID;
    }
  }
  if (!$productId) {
    // -- if we have not attached productid, then productid is just playerid
    $productId = $playerId;
  }

  return $productId;
}

/**
 * @param $str
 * @param string $argPlayerId
 * @param null $che
 * @return string|string[]
 */
function dzsap_sanitize_from_extra_html_props($str, $argPlayerId = '', $che = null) {

  global $dzsap;

  $fout = $str;
  $download_link = '';
  $permalink = '';
  $playerId = '';
  $productId = '';


  if (!$argPlayerId) {
    if ($che) {
      if ($che['playerid']) {
        $playerId = $che['playerid'];
      }
    }
  } else {
    $playerId = $argPlayerId;
  }
  if ($playerId) {
    $download_link = dzsap_get_download_link($che, $playerId);
  }


  if (get_permalink($playerId)) {
    $permalink = get_permalink($playerId);
  }



  if (strpos($fout, 'replacewithproductid') != false || strpos($fout, 'addtocart') != false) {

    $productId = dzsap_utils_getProductId($che, $playerId);
    $fout = str_replace('{{replacewithproductid}}', $productId, $fout);
    $fout = str_replace('{{addtocart}}', add_query_arg(array(
      'add-to-cart' => $productId
    ), dzs_curr_url()), $fout);
  }


  $fout = str_replace('{{downloadlink}}', $download_link, $fout);

  // -- replace with the postid
  $fout = str_replace('{{replacewithpostid}}', $playerId, $fout);
  $fout = str_replace('{{replacewithproductid}}', $playerId, $fout);


  $fout = str_replace('{{posturl}}', $permalink, $fout);
  $fout = str_replace('{{quotsingle}}', '\'', $fout);
  $fout = str_replace('{{quots}}', '`', $fout);


  return $fout;
}


function dzsap_sanitize_from_setting($arg) {

  $arg = stripslashes($arg);
  $arg = str_replace('{{quots}}', '\'', $arg);
  $arg = str_replace(array("\r", "\r\n", "\n"), '', $arg);

  return $arg;
}

function dzsap_get_download_link($che, $playerid) {

  global $dzsap;

  $returnDownloadLink = '';

  $isLinkFromPo = false; // -- check if the link is leading to a real post
  if ($playerid) {
    $po = get_post($playerid);

    if (!$po) {
      // -- if number is random then attribute source
      $playerid = '';
      $isLinkFromPo = false;
    } else {

      $isLinkFromPo = true;
    }
  }


  if (isset($che) && is_object($che)) {
    $che = (array)$che;
  }

  // todo: side effect - why do we need this ?
  if (isset($che['source']) == false) {
    if (isset($che['post_type'])) {
      if ($che['post_type'] == 'product') {
        $che['source'] = get_post_meta($che['ID'], 'dzsap_woo_product_track', true);
      }
    }
  }
  // todo: why do we need this ? $dzsap->mainoptions['download_link_links_directly_to_file']=='on' &&


  $downloadId = '';
  $downloadFile = '';
  $downloadSongName = '';

  if ((isset($che) && isset($che['download_custom_link']) && $che['download_custom_link'] && $che['download_custom_link'] != 'off')) {
    // -- it gets replaced by whole download custom_link
    $downloadFile = $che['download_custom_link'];
  } else {
    if ($isLinkFromPo) {
      // -- if playerid is valid
      $downloadId = $playerid;
    }
    if (isset($che) && isset($che['songname'])) {
      $downloadSongName = $che['songname'];
    }
    if (!$downloadId && isset($che) && isset($che['source'])) {
      $downloadFile = $che['source'];
    }
  }


  if ($dzsap->mainoptions['download_link_links_directly_to_file'] == 'on' && $downloadFile) {
    $returnDownloadLink = $che['source'];
  } else {
    if ($downloadId) {
      $returnDownloadLink = site_url() . '?action=' . DZSAP_GET_KEY_DOWNLOAD . '&id=' . $downloadId;
    } else {
      if ($downloadFile) {
        // -- set link for download link
        $returnDownloadLink = site_url() . '?action=' . DZSAP_GET_KEY_DOWNLOAD . '&link=' . urlencode($downloadFile);
      }
    }
    if ($downloadSongName) {
      $returnDownloadLink .= '&songname=' . urlencode($downloadSongName);
    }
  }


  return $returnDownloadLink;
}


function dzsap_sanitize_to_css_perc($arg) {


  $fout = $arg;

  if (strpos($arg, '%') === false) {
    $fout .= '%';
  }


  $fout = str_replace('https://', '', $fout);
  $fout = str_replace('https://', '', $fout);

  return $fout;

}


if (function_exists('dzsap_sort_by_likes') == false) {
  function dzsap_sort_by_likes($a, $b) {
    if (isset($a['likes']) && is_numeric($a['likes']) && isset($b['likes']) && is_numeric($b['likes'])) {
      return $b['likes'] - $a['likes'];
    }
  }
}
if (function_exists('dzsap_sort_by_downloads') == false) {
  function dzsap_sort_by_downloads($a, $b) {
    if (isset($a['downloads']) && is_numeric($a['downloads']) && isset($b['downloads']) && is_numeric($b['downloads'])) {
      return $b['downloads'] - $a['downloads'];
    }
  }
}

if (function_exists('dzsap_sort_by_views') == false) {
  function dzsap_sort_by_views($a, $b) {
    if (isset($a['views']) && is_numeric($a['views']) && isset($b['views']) && is_numeric($b['views'])) {
      return $b['views'] - $a['views'];
    }
  }
}

function dzsap_init_arg_submit_contor_60_secs() {
  global $wpdb;
  global $dzsap;


  $date = date('Y-m-d');
  $country = '0';
  $id = $_POST['video_analytics_id'];
  if ($dzsap->mainoptions['analytics_enable_location'] == 'on') {

    if ($_SERVER['REMOTE_ADDR']) {

      $request = wp_remote_get('https://ipinfo.io/' . $_SERVER['REMOTE_ADDR'] . '/json');
      $response = wp_remote_retrieve_body($request);
      $aux_arr = json_decode($response);

      if ($aux_arr) {
        $country = $aux_arr->country;
      }
    }
  }


  $userid = '';
  $userid = get_current_user_id();
  if ($dzsap->mainoptions['analytics_enable_user_track'] == 'on') {

    if ($_POST['curr_user']) {
      $userid = $_POST['curr_user'];
    }
  }


  $playerid = $id;

  $table_name = $wpdb->prefix . 'dzsap_activity';


  $results = $GLOBALS['wpdb']->get_results('SELECT * FROM ' . $table_name . ' WHERE id_user = \'' . $userid . '\' AND date=\'' . $date . '\'  AND type=\'' . 'timewatched' . '\' AND id_video=\'' . $playerid . '\'', OBJECT);


  if (is_array($results) && count($results) > 0) {


    $val = intval($results[0]->val);
    $newval = $val + 60;

    $wpdb->update(
      $table_name,
      array(
        'val' => $val + 60,
      ),
      array('ID' => $results[0]->id),
      array(
        '%s',    // value1
      ),
      array('%d')
    );


  } else {
    $currip = dzsap_misc_get_ip();


    $wpdb->insert(
      $table_name,
      array(
        'ip' => $currip,
        'type' => 'timewatched',
        'id_user' => $userid,
        'id_video' => $playerid,
        'date' => $date,
        'val' => 60,
        'country' => $country,
      )
    );
  }


  // -- global table

  $query = 'SELECT * FROM ' . $table_name . ' WHERE id_user = \'0\' AND date=\'' . $date . '\'  AND type=\'' . 'timewatched' . '\' AND id_video=\'' . (0) . '\'';
  if ($dzsap->mainoptions['analytics_enable_location'] == 'on' && $country) {
    $query .= ' AND country=\'' . $country . '\'';
  }
  $results = $GLOBALS['wpdb']->get_results($query, OBJECT);


  if (is_array($results) && count($results) > 0) {


    $val = intval($results[0]->val);
    $newval = $val + 60;

    $wpdb->update(
      $table_name,
      array(
        'val' => $val + 60,
      ),
      array('ID' => $results[0]->id),
      array(
        '%s',    // value1
      ),
      array('%d')
    );


  } else {

    $wpdb->insert(
      $table_name,
      array(
        'ip' => 0,
        'type' => 'timewatched',
        'id_user' => 0,
        'id_video' => 0,
        'date' => $date,
        'country' => $country,
        'val' => 60,
      )
    );
  }

}


function dzsap_sanitize_to_array_for_parse($its, $margs) {
  global $dzsap;

  foreach ($its as $lab => $it) {
    $its[$lab] = (array)$it;


    $thumb = DZSZoomSoundsHelper::get_post_thumb_src($it->ID);


    $thumb_from_meta = get_post_meta($it->ID, 'dzsrst_meta_item_thumb', true);

    if ($thumb_from_meta) {

      $thumb = $thumb_from_meta;
    }

    if ($thumb) {
      $its[$lab]['thumbnail'] = $thumb;
    }


    $its[$lab]['title_permalink'] = get_permalink($it->ID);

    $its[$lab]['price'] = get_post_meta($it->ID, 'dzsrst_meta_item_price', true);

    if ($margs['post_type'] == 'product') {
      if (get_post_meta($it->ID, '_regular_price', true)) {
        $its[$lab]['price'] = '';
        if (function_exists('get_woocommerce_currency_symbol')) {
          $its[$lab]['price'] .= get_woocommerce_currency_symbol();
        }
        $its[$lab]['price'] .= get_post_meta($it->ID, '_regular_price', true);
      }
    }

    $its[$lab]['bigimage'] = DZSZoomSoundsHelper::getImageSourceFromId(get_post_meta($it->ID, 'dzsrst_meta_item_bigimage', true));


  }

  return $its;
}

function dzsap_object_to_array($data) {
  if (is_array($data) || is_object($data)) {
    $result = array();
    foreach ($data as $key => $value) {
      $result[$key] = dzsap_object_to_array($value);
    }
    return $result;
  }
  return $data;
}


/**
 * @param {string} $name pcm sanitized source or id
 */
function dzsap_delete_waveform($name) {


  global $wpdb;

  $wpdb->query(
    $wpdb->prepare(
      "DELETE FROM $wpdb->options
		 WHERE option_name LIKE %s
		",
      ('dzsap_pcm_data_' . $name)
    )
  );
}
