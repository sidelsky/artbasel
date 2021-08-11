<?php
include_once(DZSAP_BASE_PATH . 'inc/php/view-functions/view-embed-functions.php');

class DzsapView {

  public $viewVar = 0;
  /** @var DZSAudioPlayer */
  private $dzsap;


  public $footer_style = '';
  /** @var string used if init_javascript_method is script */
  public $footerScript = '';
  public $footer_style_configs = array();

  public $extraFunctionalities = array(

  );

  /**
   * DzsapView constructor.
   * @param DZSAudioPlayer $dzsap
   */
  function __construct($dzsap) {

    $this->dzsap = $dzsap;


    add_action('init', array($this, 'handle_init_begin'), 3);
    add_action('init', array($this, 'handle_init'), 55);
    add_action('init', array($this, 'handle_init_end'), 900);
    add_action('wp_head', array($this, 'handle_wp_head'));
    add_action('wp_head', array($this, 'handle_wp_head_end'), 900);
    add_action('wp_enqueue_scripts', array($this, 'handle_wp_enqueue_scripts'), 900);


    add_action('wp_footer', array($this, 'handle_wp_footer_start'), 5);
    add_action('wp_footer', array($this, 'handle_wp_footer_end'), 500);


    add_shortcode(DZSAP_ZOOMSOUNDS_ACRONYM, array($this, 'shortcode_playlist_main'));
    add_shortcode('dzs_' . DZSAP_ZOOMSOUNDS_ACRONYM, array($this, 'shortcode_playlist_main'));
    add_action('widgets_init', array($this, 'handle_widgets_init'));


  }

  function handle_init_begin() {

    if (function_exists('vc_add_shortcode_param')) {
      vc_add_shortcode_param('dzs_add_media_att', 'vc_dzs_add_media_att');
    }
  }

  function printHeadScripts(){

    $dzsap = $this->dzsap;

    $usrId = get_current_user_id();
    $usrData = null;

    if ($usrId) {
      $usrData = get_user_by('id', $usrId);
    }
    global $post;


    $mainDzsapSettings = array(
      'dzsap_site_url' => site_url().'/',
      'pluginurl' => DZSAP_URL_AUDIOPLAYER,
      'dzsap_curr_user' => $usrId,
      'version' => DZSAP_VERSION,
      'ajax_url' => admin_url('admin-ajax.php').'',
    );



    $lab = 'dzsaap_default_portal_upload_type';
    if ($dzsap->mainoptions[$lab] && $dzsap->mainoptions[$lab] != 'audio') {
      $mainDzsapSettings[$lab] = $dzsap->mainoptions[$lab];
    }
    if ($post && $post->post_type == DZSAP_REGISTER_POST_TYPE_NAME) {
      $mainDzsapSettings['playerid'] = $post->ID;
    }
    if (($usrData)) {
      $mainDzsapSettings['comments_username'] = $usrData->data->display_name;
      $mainDzsapSettings['comments_avatar'] = DZSZoomSoundsHelper::get_avatar_url(get_avatar($usrId, 40));
    }
    if($dzsap->mainoptions['try_to_cache_total_time'] == 'on'){
      $jsName = 'action_received_time_total';
      $value = 'send_total_time';
      $mainDzsapSettings[$jsName] = $value;
    }
    if ($dzsap->mainoptions['construct_player_list_for_sync'] == 'on') {

      $mainDzsapSettings['syncPlayers_buildList'] = 'on';
      $mainDzsapSettings['syncPlayers_autoplayEnabled'] = true;
    }


    echo json_encode($mainDzsapSettings);

  }

  function handle_wp_enqueue_scripts() {
    $dzsap = $this->dzsap;

//    ob_start();
//    $this->printHeadScripts();
//    $scriptString = ob_get_clean();
//
//    wp_register_script(DZSAP_ID . '-inline', false);
//    wp_enqueue_script(DZSAP_ID . '-inline');
//    wp_add_inline_script(DZSAP_ID . '-inline', $scriptString, 'after');



  }


  function handle_init() {
    $dzsap = $this->dzsap;


    if (!is_admin()) {
      if ($this->dzsap->mainoptions['replace_playlist_shortcode'] == 'on') {
        add_shortcode('playlist', array($this, 'shortcode_wpPlaylist'));
      }

      include_once DZSAP_BASE_PATH.'inc/extensions/view/view-global-vol-icon.php';
      add_shortcode('zoomsounds_global_vol_icon',  'shortcode_zoomsounds_global_vol_icon');
    }


    add_shortcode('dzsap_show_curr_plays', array($this, 'show_curr_plays'));
    add_shortcode('zoomsounds_player_comment_field', array($this, 'shortcode_player_comment_field'));

    add_shortcode('zoomsounds_player', array($this, 'shortcode_player'));

    dzsap_view_embed_init_listeners();
  }

  function handle_init_end() {

    $dzsap = $this->dzsap;
    if ($dzsap->mainoptions['replace_audio_shortcode'] && $dzsap->mainoptions['replace_audio_shortcode'] !== 'off') {
      add_shortcode('audio', array($this, 'shortcode_audio'));
    }


    if ($dzsap->mainoptions['extra_css']) {
      wp_register_style('dzsap-hook-head-styles', false);
      wp_enqueue_style('dzsap-hook-head-styles');
      wp_add_inline_style('dzsap-hook-head-styles', $dzsap->mainoptions['extra_css']);
    }

    if(!is_admin()){

      // -- extra
      include_once(DZSAP_BASE_PATH . 'inc/php/view-functions/extra-functionality/syncPlayers-autoplay-toggler.php');
      add_shortcode('dzsap_syncplayers_autoplay_toggler', 'dzsap_shortcode_syncplayers_autoplay_toggler');
    }

  }

  function handle_wp_head() {
    $dzsap = $this->dzsap;


    if (is_tax(DZSAP_TAXONOMY_NAME_SLIDERS) || ($dzsap->mainoptions['single_index_seo_disable'] == 'on' && is_singular('dzsap_items'))) {
      echo '<meta name="robots" content="noindex, follow">';
    }


    if ($dzsap->mainoptions['replace_powerpress_plugin'] == 'on') {
      global $post;
      if ($post) {
        if ($post->ID != '4812' && $post->ID != '23950') {
          $dzsap->mainoptions['replace_powerpress_plugin'] = 'off';
        }
      }
    }

    if (isset($_GET['dzsap_generate_pcm']) && $_GET['dzsap_generate_pcm']) {
      include DZSAP_BASE_PATH . 'class_parts/part-regenerate-waves-player.php';
    }


    if ($dzsap->mainoptions['replace_powerpress_plugin'] == 'on') {
      add_filter('the_content', array($this, 'filter_the_content'));
    }


    if (!is_single() && (is_post_type_archive(DZSAP_REGISTER_POST_TYPE_NAME) || is_tax(DZSAP_REGISTER_POST_TYPE_CATEGORY))) {
      if ($dzsap->mainoptions['excerpt_hide_zoomsounds_data'] == 'on' || $dzsap->mainoptions['exceprt_zoomsounds_posts']) {
        add_filter('get_the_excerpt', array($this, 'filter_the_content_end'), 9999);
      }
    }

    echo '<script id="dzsap-main-settings" type="application/json">';
    $this->printHeadScripts();
    echo '</script>';

    DZSZoomSoundsHelper::echoJavascriptKeyboardControls($dzsap);

  }


  function handle_wp_footer_start() {

    if ($this->dzsap->mainoptions['init_javascript_method'] == 'script') {

      $this->footerScript .= 'jQuery(document).ready(function($){';
      $this->footerScript .= '$(\'.audioplayer-tobe:not(.dzsap-inited)\').addClass(\'auto-init\'); ';
      $this->footerScript .= 'if(window.dzsap_init_allPlayers) { window.dzsap_init_allPlayers($); }';
      $this->footerScript .= '});';


      wp_register_script(DZSAP_ID . '-inline-footer', false);
      wp_enqueue_script(DZSAP_ID . '-inline-footer');
      wp_add_inline_script(DZSAP_ID . '-inline-footer', $this->footerScript, 'before');
    }



    if ($this->dzsap->mainoptions['failsafe_ajax_reinit_players'] == 'on') {
      wp_enqueue_script('dzsap-init-all-players-on-interval', DZSAP_BASE_URL . 'inc/js/shortcodes/init-all-players-on-interval.js', array(), DZSAP_VERSION);
    }

    $this->generateArgsForFooterStickyPlayerFromMeta();

    if (isset($_GET['action'])) {
      if ($_GET['action'] == 'embed_zoomsounds') {
        dzsap_view_embed_generateHtml();
      }
    }
    $this->handle_footer_extraHtml();
  }

  function handle_wp_footer_end() {

  }

  function filter_the_content($fout) {
    $dzsap = $this->dzsap;

    if ($dzsap->mainoptions['replace_powerpress_plugin'] == 'on') {
      return dzsap_powerpress_filter_content($fout);
    }


    return $fout;
  }

  function filter_the_content_end($fout) {
    global $post;
    $dzsap = $this->dzsap;

    $postTitle = '';

    if ($post && $post->post_title) {
      $postTitle = $post->post_title;
    }


    if ($dzsap->mainoptions['exceprt_zoomsounds_posts']) {

      $shortcodePatternStr = DZSZoomSoundsHelper::sanitize_from_shortcode_pattern($dzsap->mainoptions['exceprt_zoomsounds_posts'], $post);
      $fout = do_shortcode($shortcodePatternStr);
    } else {
      if ($dzsap->mainoptions['excerpt_hide_zoomsounds_data'] == 'on') {

        $fout = str_replace($postTitle . $postTitle . $postTitle, '', $fout);
        $fout = str_replace($postTitle . $postTitle, '', $fout);
        $fout = str_replace('Stats Edit Delete', '', $fout);
        $fout = str_replace('Add to cart', '', $fout);

        $fout = preg_replace('/\[zoomsounds.*?]/', ' ', $fout);;;
        $fout = preg_replace('/&lt;iframe.*?&lt;\/iframe&gt;/', ' ', $fout);;
      }

    }


    return $fout;
  }

  public function get_zoomsounds_player_config_settings($config_name) {

    $dzsap = $this->dzsap;

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
    );


    $vpconfig_k = -1;

    $vpsettings = array();
    $vpconfig_id = $config_name;


    if (is_array($config_name)) {


      $vpsettings['settings'] = $config_name;


    } else {

      for ($i = 0; $i < count($dzsap->mainitems_configs); $i++) {
        if ((isset($vpconfig_id)) && ($vpconfig_id == $dzsap->mainitems_configs[$i]['settings']['id'])) {
          $vpconfig_k = $i;
        }
      }


      if ($vpconfig_k > -1) {
        $vpsettings = $dzsap->mainitems_configs[$vpconfig_k];
      } else {
        $vpsettings['settings'] = $vpsettingsdefault;
      }

      if (is_array($vpsettings) == false || is_array($vpsettings['settings']) == false) {
        $vpsettings = array('settings' => $vpsettingsdefault);
      }
    }

    return $vpsettings;
  }


  function handle_footer_extraHtml() {

    $dzsap = $this->dzsap;

    if ($this->footer_style) {
      wp_register_style('dzsap-footer-style', false);
      wp_enqueue_style('dzsap-footer-style');
      wp_add_inline_style('dzsap-footer-style', $this->footer_style);
    }


    if ($dzsap->og_data && count($dzsap->og_data)) {
      $ogThumbnailSrc = '';

      if (isset($dzsap->og_data['image'])) {
        $ogThumbnailSrc = $dzsap->og_data['image'];
      }
      echo '<meta property="og:title" content="' . $dzsap->og_data['title'] . '" />';
      echo '<meta property="og:description" content="' . strip_tags($dzsap->og_data['description']) . '" />';

      if ($ogThumbnailSrc) {
        echo '<meta property="og:image" content="' . $ogThumbnailSrc . '" />';
      }
    }


    if ($dzsap->isEnableMultisharer) {
      dzsap_generateHtmlMultisharer();
    }


    if (count($dzsap->audioPlayerConfigs) > 0) {


      ?>
      <div hidden class="dzsap-feed--dzsap-configs"><?php echo json_encode($dzsap->audioPlayerConfigs); ?></div><?php
    }

    if (($dzsap->mainoptions['wc_loop_product_player'] && $dzsap->mainoptions['wc_loop_product_player'] != 'off') || ($dzsap->mainoptions['wc_single_product_player'] && $dzsap->mainoptions['wc_single_product_player'] != 'off')) {


      if ($dzsap->mainoptions['wc_loop_player_position'] == 'overlay') {
        dzsap_generateHtmlWoocommerceOverlayPlayer();
      }
    }


    if (isset($dzsap->mainoptions['replace_powerpress_plugin']) && $dzsap->mainoptions['replace_powerpress_plugin'] == 'on') {
      dzsap_powerpress_generateHtmlEnclosureData();
    }


  }

  /**
   *  generate the footer player args from the meta info
   */
  function generateArgsForFooterStickyPlayerFromMeta() {
    global $wp_query;

    $dzsap = $this->dzsap;

    $isFooterPlayerEnabled = false;
    $footer_player_source = 'fake';
    $footer_player_config = 'fake';
    $footer_player_type = 'fake';
    $footer_player_songName = '';


    if ($dzsap->mainoptions['enable_global_footer_player'] != 'off') {

      $isFooterPlayerEnabled = true;
      $footer_player_source = 'fake';
      $footer_player_type = 'fake';
      $footer_player_config = $dzsap->mainoptions['enable_global_footer_player'];
    }

    if ($wp_query && $wp_query->post) {
      if ((get_post_meta($wp_query->post->ID, DZSAP_META_NAME_FOOTER_FEATURED_MEDIA, true)
        || get_post_meta($wp_query->post->ID, DZSAP_META_NAME_FOOTER_ENABLE, true) == 'on')
      ) {

        $isFooterPlayerEnabled = true;


        $footer_player_config = get_post_meta($wp_query->post->ID, DZSAP_META_NAME_FOOTER_VPCONFIG, true);
        if (get_post_meta($wp_query->post->ID, DZSAP_META_NAME_FOOTER_FEED_TYPE, true) == 'custom') {
          $footer_player_source = get_post_meta($wp_query->post->ID, DZSAP_META_NAME_FOOTER_FEATURED_MEDIA, true);
          $footer_player_type = get_post_meta($wp_query->post->ID, DZSAP_META_NAME_FOOTER_TYPE, true);

        }
        if (get_post_meta($wp_query->post->ID, DZSAP_META_NAME_FOOTER_SONG_NAME, true)) {
          $footer_player_songName = get_post_meta($wp_query->post->ID, DZSAP_META_NAME_FOOTER_SONG_NAME, true);

        }
      }
    }


    if ($isFooterPlayerEnabled) {
      if ($footer_player_source) {
        $this->view_generateFooterPlayer($footer_player_type, $footer_player_source, $footer_player_config, $footer_player_songName);
      }
    }
  }

  /**
   * generate the output
   * @param $footer_player_type
   * @param $footer_player_source
   * @param $footer_player_config
   * @param $footer_player_songName
   */
  function view_generateFooterPlayer($footer_player_type, $footer_player_source, $footer_player_config, $footer_player_songName) {

    $dzsap = $this->dzsap;


    $dzsap->front_scripts();

    $cueMedia = 'on';
    if ($footer_player_type === 'fake') {
      $cueMedia = 'off';
    }

    $playerArgs = array(
      'player_id' => DZSAP_VIEW_STICKY_PLAYER_ID,

      'source' => $footer_player_source,
      'cueMedia' => $cueMedia,
      'config' => $footer_player_config,
      'autoplay' => 'off',
      'songname' => $footer_player_songName,
      'type' => $footer_player_type,
    );


    $vpsettings = DZSZoomSoundsHelper::getVpConfigFromConfigsDatabase($footer_player_config);


    echo '<div class="dzsap-sticktobottom-placeholder dzsap-sticktobottom-placeholder-for-' . $vpsettings['settings']['skin_ap'] . '"></div>
<section class="dzsap-sticktobottom ';


    if ((isset($vpsettings['settings']['skin_ap']) == false ||
        $vpsettings['settings']['skin_ap'] == 'skin-wave') &&
      (isset($vpsettings['settings']['skinwave_mode']) && $vpsettings['settings']['skinwave_mode'] == 'small'
      )
    ) {
      echo ' dzsap-sticktobottom-for-skin-wave';
    }


    if (isset($vpsettings['settings']['skin_ap']) == false || ($vpsettings['settings']['skin_ap'] == 'skin-silver')) {
      echo ' dzsap-sticktobottom-for-skin-silver';
    }


    echo '">';

    echo '<div class="dzs-container">';


    if (isset($vpsettings['settings']['enable_footer_close_button']) == false || ($vpsettings['settings']['enable_footer_close_button'] == 'on')) {
      echo '<div class="sticktobottom-close-con">' . $dzsap->general_assets['svg_stick_to_bottom_close_hide'] . $dzsap->general_assets['svg_stick_to_bottom_close_show'] . ' </div>';

      wp_enqueue_script('footer-player-close-btn', DZSAP_URL_AUDIOPLAYER . 'parts/footer-player/footer-player-icon-hide.js');
    }


    $aux = array('called_from' => 'footer_player');

    $playerArgs = array_merge($playerArgs, $aux);


    echo $dzsap->classView->shortcode_player($playerArgs);


    echo '</div>';
    echo '</section>';


  }

  function handle_widgets_init() {


    include_once DZSAP_BASE_PATH . "widget.php";
    $dzsap_widget = new DZSAP_Tags_Widget();
    $dzsap_widget::register_this_widget();

    add_action('widgets_init', array($dzsap_widget, 'register_this_widget'));
  }

  /**
   * @param $singleItemInstance
   * @param array $pargs
   * @return string pcm data as string
   */
  function generate_pcm($singleItemInstance, $pargs = array()) {

    $dzsap = $this->dzsap;

    $margs = array(
      'generate_only_pcm' => false, // -- generate only the pcm not the markup
      'identifierSource' => '',
      'identifierId' => '',
    );

    if (is_array($pargs) == false) {
      $pargs = array();
    }

    $margs = array_merge($margs, $pargs);

    $fout = '';


    $pcmIdentifierId = $margs['identifierId'];
    $pcmIdentifierSource = $margs['identifierSource'];


    // -- if it's a post... stdObject
    if (isset($singleItemInstance->post_title)) {
      $args = array();
      $pcmIdentifierSource = $dzsap->get_track_source($singleItemInstance->ID, $singleItemInstance->ID, $args);
      $singleItemInstance = (array)$singleItemInstance;

      $singleItemInstance['playerid'] = $singleItemInstance['id'];
    }


    if (isset($singleItemInstance['source']) && $singleItemInstance['source']) {
      $pcmIdentifierSource = $singleItemInstance['source'];
    }
    if (isset($singleItemInstance['playerid']) && $singleItemInstance['playerid']) {
      $pcmIdentifierId = $singleItemInstance['playerid'];
    }
    if (isset($singleItemInstance['wpPlayerPostId']) && $singleItemInstance['wpPlayerPostId']) {
      $pcmIdentifierId = $singleItemInstance['wpPlayerPostId'];
    }

    if ($pcmIdentifierSource == 'fake') {
      return '';
    }


    $lab_option_pcm = '';

    if ($pcmIdentifierId) {
      $lab_option_pcm = 'dzsap_pcm_data_' . DZSZoomSoundsHelper::sanitize_toKey($pcmIdentifierId);
    }
    $stringPcm = get_option($lab_option_pcm);


    if ($this->isPcmInvalid($stringPcm)) {
      $lab_option_pcm = 'dzsap_pcm_data_' . DZSZoomSoundsHelper::sanitize_toKey($pcmIdentifierSource);

      if (DZSZoomSoundsHelper::sanitize_toKey($pcmIdentifierSource)) {
        $stringPcm = get_option($lab_option_pcm);
      }

    }



    if ($this->isPcmInvalid($stringPcm)) {
      if (isset($singleItemInstance['linktomediafile'])) {
        if ($singleItemInstance['linktomediafile']) {
          $lab_option_pcm = 'dzsap_pcm_data_' . $singleItemInstance['linktomediafile'];
          $stringPcm = get_option($lab_option_pcm);
        }
      }
    }


    if (!$this->isPcmInvalid($stringPcm)) {
      $fout .= ' data-pcm=\'' . stripslashes($stringPcm) . '\'';
    }

    if ($margs['generate_only_pcm'] && !$this->isPcmInvalid($stringPcm)) {
      $fout = stripslashes($stringPcm);
    }


    return $fout;
  }

  function isPcmInvalid($pcm) {
    return ($pcm == '' || $pcm == '[]' || strpos($pcm, ',') === false || strpos($pcm, 'null') !== false);
  }


  function handle_wp_head_end() {

    $dzsap = $this->dzsap;

    if ($dzsap->mainoptions['script_use_async'] === 'on' || $dzsap->mainoptions['script_use_defer'] === 'on') {

      add_filter('script_loader_tag', array($this, 'script_use_async'), 10, 3);
    }


  }

  function script_use_async($tag, $handle) {

    $dzsap = $this->dzsap;

    if ($dzsap->mainoptions['script_use_async'] === 'on' && $dzsap->mainoptions['init_javascript_method'] != 'script') {
      if (strpos($handle, DZSAP_ID) !== false) {
        $tag = str_replace('<script', '<script async', $tag);
      }
    }

    if ($dzsap->mainoptions['script_use_defer'] === 'on' && $dzsap->mainoptions['init_javascript_method'] != 'script') {
      if (strpos($handle, DZSAP_ID) !== false) {
        $tag = str_replace('<script', '<script defer', $tag);
      }
    }

    return $tag;
  }

  /**
   * @param $its
   * @param array $argSinglePlayerOptions - playerShortcode and Settings
   * @param array $argPlaylistOptions
   * @return string
   */
  function parse_items($its, $argSinglePlayerOptions = array(), $argPlaylistOptions = array()) {
    // -- returns only the html5 gallery items

    global $post;

    $dzsap = $this->dzsap;

    $fout = '';
    $start_nr = 0; // -- the i start nr
    $end_nr = 0; // --  the i start nr

    $singlePlayerOptions = array(
      'menu_facebook_share' => 'auto',
      'menu_like_button' => 'auto',
      'gallery_skin' => 'skin-wave',
      'called_from' => 'skin-wave',
      'skinwave_mode' => 'normal',
      'is_single' => 'off',
      'auto_init_player' => 'off',
      'auto_init_player_options' => '',
      'wrapper_image' => '',
      'extraattr' => '',
      'extra_classes' => '',
      'wrapper_image_type' => '', // zoomsounds-wrapper-bg-bellow or zoomsounds-wrapper-bg-center ( set in item options )
    );

    $playlistOptions = null;

    if ($argPlaylistOptions && is_array($argPlaylistOptions) && count($argPlaylistOptions)) {
      $playlistOptions = $argPlaylistOptions;
    }

    $singlePlayerOptions = array_merge($singlePlayerOptions, $argSinglePlayerOptions);
    $vpConfig = $its['playerConfigSettings'];


    // -- count
    foreach ($its as $key => $val) {
      if (is_numeric($key)) {
        $end_nr++;
      }
    }


    dzsap_view_parseItemsInitialSettingsSetup($its, $singlePlayerOptions);

    if ($singlePlayerOptions['called_from'] == 'gallery') {
    }


    for ($i = $start_nr; $i < $end_nr; $i++) {


      $i_fout = '';
      $singleItemInstance = array(
        'menu_artistname' => 'default',
        'menu_songname' => 'default',
        'menu_extrahtml' => '',
        'extra_html' => '',
        'called_from' => '',
        'songname' => '',
        'artistname' => '',
        'show_tags' => 'off',
        'playerid' => '', // -- playerid for database *deprecated .. transition to wpPlayerPostId
        'wpPlayerPostId' => '', // --  database id
      );

      /** might be fake @var number | string $computedPlayerId */
      $computedPlayerId = '';
      $isPlayerIdFake = false; // -- if we assign a random number here , then it is fake

      if (is_array($its[$i]) == false) {
        $its[$i] = array();
      }

      $singleItemInstance = array_merge($singleItemInstance, $its[$i]);


      DZSZoomSoundsHelper::player_parseItems_generateSinglePlayerIds($isPlayerIdFake, $singleItemInstance, $singlePlayerOptions);


      $singleItemInstance = DZSZoomSoundsHelper::sanitize_item_for_parse_items($i, $singleItemInstance, $its);

      if ($singleItemInstance['show_tags'] == 'on') {
        $i_fout .= DZSZoomSoundsHelper::player_parseItems_generateTags($singleItemInstance['playerId_computed']);
      }


      $type = 'audio';

      if (isset($singleItemInstance['type']) && $singleItemInstance['type'] != '') {
        $type = $singleItemInstance['type'];
      }

      if ($type == 'inline') {
        continue;
      }


      if ($singleItemInstance['source'] == '' || $singleItemInstance['source'] == ' ') {
        continue;
      }


      if (isset($_GET['fromsharer']) && $_GET['fromsharer'] == 'on') {
        if (isset($_GET['audiogallery_startitem_ag1']) && $_GET['audiogallery_startitem_ag1']) {
          if ($i == $_GET['audiogallery_startitem_ag1']) {
            $dzsap->og_data = array(
              'title' => $singleItemInstance['menu_songname'],
              'image' => $singleItemInstance['thumb'],
              'description' => esc_html__("by", DZSAP_ID) . ' ' . $singleItemInstance['menu_artistname'],
            );
          }
        }
      }

      if (strpos($singleItemInstance['source'], 'soundcloud.com') !== false) {
        if (isset($singleItemInstance['soundcloud_track_id']) && isset($singleItemInstance['soundcloud_secret_token']) && $singleItemInstance['soundcloud_track_id'] && $singleItemInstance['soundcloud_secret_token']) {
          $singleItemInstance['source'] = DZSZoomSoundsHelper::get_soundcloud_track_source($singleItemInstance);
          if ($type == 'soundcloud') {
            $type = 'audio';
          }
        }
      }


      if (isset($its['playerConfigSettings'])) {
        $singleItemInstance['extra_html'] = DZSZoomSoundsHelper::parseItemDetermineExtraHtml($singleItemInstance['extra_html'], $its['playerConfigSettings']);
      }


      $singleItemInstance['extra_html'] = do_shortcode(dzsap_sanitize_from_extra_html_props($singleItemInstance['extra_html'], '', $singleItemInstance));


      if ($singleItemInstance['playerId_computed']) {
        if (isset($singleItemInstance['itunes_link']) && $singleItemInstance['itunes_link']) {

        } else {
          if ($singleItemInstance['wpPlayerPostId']) {
            if (get_post_meta($singleItemInstance['wpPlayerPostId'], 'dzsap_meta_itunes_link', true)) {
              $singleItemInstance['itunes_link'] = get_post_meta($singleItemInstance['wpPlayerPostId'], 'dzsap_meta_itunes_link', true);

            }

          }
        }
      }


      $extraHtmlInBottomControls = '';


      if ((isset($singleItemInstance['extra_html_in_bottom_controls']) && $singleItemInstance['extra_html_in_bottom_controls'])) {
        $extraHtmlInBottomControls = ($singleItemInstance['extra_html_in_bottom_controls']);
      }
      if ((isset($singleItemInstance['extrahtml_in_bottom_controls_from_player']) && $singleItemInstance['extrahtml_in_bottom_controls_from_player'])) {
        $extraHtmlInBottomControls .= ($singleItemInstance['extrahtml_in_bottom_controls_from_player']);
      }


      if ($extraHtmlInBottomControls) {


        $singleItemInstance['extra_html'] .= dzs_esc__(do_shortcode(dzsap_sanitize_from_extra_html_props($extraHtmlInBottomControls, '', $singleItemInstance)));
      }


      // -- we are going to now show non public tracks
      if ($dzsap->mainoptions['show_only_published'] == 'on') {
        if (isset($singleItemInstance['ID']) && $singleItemInstance['ID']) {
          if (($singleItemInstance['post_type'] != 'dzsap_items') && get_post_status($singleItemInstance['ID']) !== 'publish') {
            continue;
          }
        }
      }


      // -- player


      $vpConfigId = DZSAP_DEFAULT_ZOOMSOUNDS_CONFIG;


      if (isset($its['playerConfigSettings']) && isset($its['playerConfigSettings']['id'])) {
        $vpConfigId = $its['playerConfigSettings']['id'];
      }


      dzsap_view_parseItemsAddFooterExtraStyling($this, $vpConfigId, $vpConfig, $its);
      $str_tw = '';


      if (isset($singlePlayerOptions['single']) && $singlePlayerOptions['single'] == 'on') {
        if (isset($singlePlayerOptions['width']) && isset($singlePlayerOptions['height'])) {

          // -- some sanitizing
          $tw = $singlePlayerOptions['width'];

          if ($tw != '') {
            if (strpos($tw, "%") === false && $tw != 'auto') {
              $str_tw = ' width: ' . $tw . 'px;';
            } else {
              $str_tw = ' width: ' . $tw . ';';
            }
          }


        }
      }


      $thumb_link_attr = '';
      $fakeplayer_attr = '';
      $thumb_for_parent_attr = '';

      $pcmString = '';

      // -- we get data-pmc here
      if ($dzsap->mainoptions['skinwave_wave_mode'] == 'canvas') {
        $pcmString = $this->generate_pcm($singleItemInstance);
      }


      $audioplayerClasses = dzsap_view_parseItemsInitialClassSetup($its, $i, $post, $singleItemInstance, $singlePlayerOptions);


      // -- parse the item
      $i_fout .= '<div class="' . $audioplayerClasses;
      $i_fout .= '" ';
      // -- end class


      if ($singlePlayerOptions['auto_init_player_options']) {


        $i_fout .= ' data-options=\'' . $singlePlayerOptions['auto_init_player_options'] . '\'';
      }

      $i_fout .= ' style="';
      if ($singlePlayerOptions['called_from'] == 'player') {
        $i_fout .= ' opacity: 0; ';
      }
      $i_fout .= '' . $str_tw . '';
      $i_fout .= '"';


      $post_type = '';

      if ($singleItemInstance['wpPlayerPostId']) {
        $po = get_post($singleItemInstance['wpPlayerPostId']);

        if ($po) {
          if ($po->post_type) {
            $post_type = $po->post_type;
          }
        }

        if ($post_type) {

          $i_fout .= ' data-posttype="' . $post_type . '"';

          $singleItemInstance['post_type'] = $post_type;
        }
      }

      if (isset($singleItemInstance['product_id']) && $singleItemInstance['product_id']) {

        $i_fout .= ' data-product_id="' . $singleItemInstance['product_id'] . '"';
      }

      if (isset($singleItemInstance['type_normal_stream_type']) && $singleItemInstance['type_normal_stream_type']) {
        $i_fout .= ' data-streamtype="' . DZSZoomsoundsHelper::sanitizeForShortcodeAttr($singleItemInstance['type_normal_stream_type']) . '"';
      }


      if (dzsap_check_if_user_played_track($singleItemInstance['playerId_computed']) === true) {
        $i_fout .= ' data-viewsubmitted="on"';
      }


      if ($singleItemInstance['playerId_computed'] != '') {


        if ($singlePlayerOptions['called_from'] == 'footer_player') {

          $i_fout .= ' id="'.DZSAP_VIEW_STICKY_PLAYER_ID.'"';
        } else {
          $i_fout .= ' id="ap' . $singleItemInstance['playerId_computed'] . '"';
        }

        $i_fout .= ' data-playerid="' . $singleItemInstance['playerId_computed'] . '"';
        $i_fout .= ' data-computed-playerid="' . $singleItemInstance['playerId_computed'] . '"';

        if (isset($singleItemInstance['wpPlayerPostId']) && $singleItemInstance['wpPlayerPostId'] != '') {

          $i_fout .= ' data-real-playerid="' . $singleItemInstance['wpPlayerPostId'] . '"';
        }
      };

      $i_fout .= ' data-sanitized_source="' . DZSZoomSoundsHelper::sanitize_toKey($singleItemInstance['source']) . '"';


      $i_fout .= $singlePlayerOptions['extraattr'];


      if (isset($singleItemInstance['dzsap_meta_source_attachment_id']) && $singleItemInstance['dzsap_meta_source_attachment_id']) {

      } else {
        // -- try to get dzsap_meta_source_attachment_id if it's a dzsap_item
        if ($singleItemInstance['wpPlayerPostId']) {


          if (get_post_meta($singleItemInstance['wpPlayerPostId'], 'dzsap_meta_source_attachment_id', true)) {
            $singleItemInstance['dzsap_meta_source_attachment_id'] = get_post_meta($singleItemInstance['wpPlayerPostId'], 'dzsap_meta_source_attachment_id', true);
          }


        }
      }


      if ($dzsap->mainoptions['try_to_get_id3_thumb_in_frontend'] == 'on') {


        if (isset($singleItemInstance['dzsap_meta_source_attachment_id']) && $singleItemInstance['dzsap_meta_source_attachment_id']) {

          if (!(isset($singleItemInstance['thumb']) && $singleItemInstance['thumb'])) {

            // -- get base64 data in frontend
            $file = get_attached_file($singleItemInstance['dzsap_meta_source_attachment_id']);

            include_once(ABSPATH . 'wp-admin/includes/media.php');
            $metadata = wp_read_audio_metadata($file);

            if ($metadata && isset($metadata['image']) && isset($metadata['image']['data'])) {
              $singleItemInstance['thumb'] = 'data:image/jpeg;base64,' . base64_encode($metadata['image']['data']);
            }

          }

          if (!(isset($singleItemInstance['artistname']) && $singleItemInstance['artistname'])) {
            $file = get_attached_file($singleItemInstance['dzsap_meta_source_attachment_id']);
            include_once(ABSPATH . 'wp-admin/includes/media.php');
            $metadata = wp_read_audio_metadata($file);
          }
        }
      }


      $singleItemInstance['thumb'] = DZSZoomSoundsHelper::getThumbnailFromItemInstance($singleItemInstance);

      if (isset($singleItemInstance['thumb']) && $singleItemInstance['thumb']) {
        $i_fout .= ' data-thumb="' . $singleItemInstance['thumb'] . '"';
      };
      if (isset($singleItemInstance['thumb_for_parent']) && $singleItemInstance['thumb_for_parent']) {

        $thumb_for_parent_attr .= ' data-thumb_for_parent="' . $singleItemInstance['thumb_for_parent'] . '"';
      };
      $i_fout .= $thumb_for_parent_attr;

      if (isset($singleItemInstance['thumb_link']) && $singleItemInstance['thumb_link']) {
        $thumb_link_attr .= ' data-thumb_link="' . $singleItemInstance['thumb_link'] . '"';
      };

      $i_fout .= $thumb_link_attr;
      if (isset($singleItemInstance['wrapper_image']) && $singleItemInstance['wrapper_image']) {
        $i_fout .= ' data-wrapper-image="' . DZSZoomSoundsHelper::getImageSourceFromId($singleItemInstance['wrapper_image']) . '" ';
      }

      if (isset($singleItemInstance['publisher']) && $singleItemInstance['publisher']) {
        $i_fout .= ' data-publisher="' . $singleItemInstance['publisher'] . '"';
      };


      if (isset($singleItemInstance['sample_time_start']) && $singleItemInstance['sample_time_start']) {
        // -- not pseudo
        $i_fout .= ' data-sample_time_start="' . $singleItemInstance['sample_time_start'] . '"';
      }

      if (isset($singleItemInstance['sample_time_end']) && $singleItemInstance['sample_time_end']) {
        // -- not pseudo
        $i_fout .= ' data-sample_time_end="' . $singleItemInstance['sample_time_end'] . '"';
      }

      if (isset($singleItemInstance['sample_time_total']) && $singleItemInstance['sample_time_total']) {
        $i_fout .= ' data-sample_time_total="' . $singleItemInstance['sample_time_total'] . '"';
      } else {

        // -- try to set from cache total time

        $isSourceFake = isset($singlePlayerOptions['source']) && $singlePlayerOptions['source'] == 'fake';

        if ($dzsap->mainoptions['try_to_cache_total_time'] == 'on' && !$isSourceFake && get_post_meta($singleItemInstance['playerId_computed'], DZSAP_DBNAME_CACHE_TOTAL_TIME, true) && intval(get_post_meta($singleItemInstance['playerId_computed'], DZSAP_DBNAME_CACHE_TOTAL_TIME, true)) > 0) {
          $i_fout .= ' data-sample_time_total="' . intval(get_post_meta($singleItemInstance['playerId_computed'], DZSAP_DBNAME_CACHE_TOTAL_TIME, true)) . '"';
        }
      }


      if (isset($singleItemInstance['play_in_footer_player']) && ($singleItemInstance['play_in_footer_player'] == 'default' || $singleItemInstance['play_in_footer_player'] === '')) {
        $singleItemInstance['play_in_footer_player'] = 'off';

      }
      if (isset($its['settings']['gallery_play_in_footer_player']) && $its['settings']['gallery_play_in_footer_player'] == 'on') {
        $singleItemInstance['play_in_footer_player'] = $its['settings']['gallery_play_in_footer_player'];
      }


      if (isset($singleItemInstance['play_in_footer_player']) && $singleItemInstance['play_in_footer_player'] == 'on') {

        $fakeplayer_attr = ' data-fakeplayer=".'.DZSAP_VIEW_STICKY_PLAYER_ID.'"';
      };


      if ($dzsap->mainoptions['skinwave_wave_mode'] == 'canvas') {


        $i_fout .= $pcmString;
      } else {
        if (isset($singleItemInstance['waveformbg']) && $singleItemInstance['waveformbg'] != '') {
          $i_fout .= ' data-scrubbg="' . $singleItemInstance['waveformbg'] . '"';
        };
        if (isset($singleItemInstance['waveformprog']) && $singleItemInstance['waveformprog'] != '') {
          $i_fout .= ' data-scrubprog="' . $singleItemInstance['waveformprog'] . '"';
        };
      }

      if ($type != '') {

        if ($type == 'detect') {
          if ($singleItemInstance['source']) {

            if ($singleItemInstance['source'] != sanitize_youtube_url_to_id($singleItemInstance['source'])) {
              $type = 'youtube';
              $singleItemInstance['source'] = sanitize_youtube_url_to_id($singleItemInstance['source']);
            }
          }
        }
        $i_fout .= ' data-type="' . $type . '"';
      };


      if (($dzsap->mainoptions['developer_check_for_bots_and_dont_reveal_source'] == 'on' && DZSZoomSoundsHelper::isBotScraping() == false) || $dzsap->mainoptions['developer_check_for_bots_and_dont_reveal_source'] != 'on') {

        if (isset($singleItemInstance['source']) && $singleItemInstance['source'] != '') {
          $i_fout .= ' data-source="' . DZSZoomsoundsHelper::sanitizeForShortcodeAttr($singleItemInstance['source']) . '"';
        };
        if (isset($singleItemInstance['sourceogg']) && $singleItemInstance['sourceogg'] != '') {
          $i_fout .= ' data-sourceogg="' . $singleItemInstance['sourceogg'] . '"';
        };
      }

      if (isset($singleItemInstance['bgimage']) && $singleItemInstance['bgimage'] != '') {
        $i_fout .= ' data-bgimage="' . $singleItemInstance['bgimage'] . '"';
        $i_fout .= ' data-wrapper-image="' . $singleItemInstance['bgimage'] . '"';
      };


      if ($singleItemInstance['playfrom']) {
        $i_fout .= ' data-playfrom="' . $singleItemInstance['playfrom'] . '"';
      };

      if (isset($singlePlayerOptions['faketarget']) && $singlePlayerOptions['faketarget']) {
        $fakeplayer_attr = ' data-fakeplayer="' . $singlePlayerOptions['faketarget'] . '"';
      }

      $i_fout .= $fakeplayer_attr;

      $i_fout .= '>';


      if (isset($singleItemInstance['replace_songname']) && $singleItemInstance['replace_songname']) {
        $singleItemInstance['songname'] = $singleItemInstance['replace_songname'];
      }

      // -- try to compute songname
      if ($singleItemInstance['songname'] == 'default' || $singleItemInstance['songname'] == '{{id3}}') {
        $compute_songName = DZSZoomSoundsHelper::view_getSongNameFromComputed($singleItemInstance['source'], $singleItemInstance);
        if ($compute_songName) {
          $singleItemInstance['songname'] = $compute_songName;
        }
      }

      if ($singleItemInstance['songname'] == 'default') {
        $singleItemInstance['songname'] = '';
      }


      if ($singleItemInstance['artistname'] == 'none') {
        $singleItemInstance['artistname'] = '';
      }


      if ($singleItemInstance['songname'] == 'none') {
        $singleItemInstance['songname'] = '';
      }


      if ($singleItemInstance['artistname'] == 'default') {
        $singleItemInstance['artistname'] = '';
      }


      if ($singleItemInstance['songname'] == 'default' || $singleItemInstance['songname'] == '{{id3}}') {
        $singleItemInstance['songname'] = '';
      }


      if (isset($singleItemInstance['player_id']) && $singleItemInstance['player_id'] == DZSAP_VIEW_STICKY_PLAYER_ID) {
        $singleItemInstance['menu_artistname'] = ' ';
        $singleItemInstance['menu_songname'] = ' ';
      }

      $meta_artist_html = '';


      $has_artist_name = false;

      if ((isset($singleItemInstance['artistname']) && $singleItemInstance['artistname']) || (isset($singleItemInstance['songname']) && $singleItemInstance['songname']) || $singlePlayerOptions['called_from'] == 'footer_player') {
        $meta_artist_html .= '<div class="meta-artist track-meta-for-dzsap">';
        $meta_artist_html .= '<span class="the-artist first-line">';


        if ($singleItemInstance['artistname']) {
          $has_artist_name = true;


          $meta_artist_html .= '<span class="first-line-label">' . $singleItemInstance['artistname'] . '</span>';
        }


        if (isset($vpConfig['settings_extrahtml_after_artist'])) {
          $meta_artist_html .= dzs_esc__(do_shortcode($vpConfig['settings_extrahtml_after_artist']));
        }


        $meta_artist_html .= '</span>';
        if ($singleItemInstance['songname'] != '' || $singleItemInstance['called_from'] == 'footer_player') {

          if ($has_artist_name) {


          }

          $meta_artist_html .= '<span class="the-name the-songname second-line">' . $singleItemInstance['songname'] . '</span>';
        }

        $meta_artist_html .= '</div>';
      }


      if ($singleItemInstance['artistname']) {

        $i_fout .= '<div hidden class="feed-dzsap feed-artist-name">' . $singleItemInstance['artistname'] . '</div>';
      }
      if ($singleItemInstance['songname']) {

        $i_fout .= '<div hidden class="feed-dzsap feed-song-name">' . $singleItemInstance['songname'] . '</div>';
      }

      $i_fout .= $meta_artist_html;


      if (isset($singleItemInstance['wrapper_image_type']) && $singleItemInstance['wrapper_image_type']) {


        if ($singleItemInstance['wrapper_image_type'] == 'zoomsounds-wrapper-bg-bellow') {

          $dzsap->isEnableMultisharer = true;
          $i_fout .= '<div href="#" class=" dzsap-wrapper-but dzsap-multisharer-but "><span class="the-icon">{{svg_share_icon}}</span> </div>';

          $i_fout .= '<div href="#" class=" dzsap-wrapper-but btn-like "><span class="the-icon">{{heart_svg}}</span> </div>';
        }

      }


      if ($singleItemInstance['menu_artistname'] != '' || $singleItemInstance['menu_songname'] != '' || (isset($singleItemInstance['thumb']) && $singleItemInstance['thumb'] != '')) {
        $i_fout .= '<div class="menu-description">';
        if (isset($singleItemInstance['thumb']) && $singleItemInstance['thumb']) {
          $i_fout .= '<div class="menu-item-thumb-con"><div class="menu-item-thumb" style="background-image: url(' . $singleItemInstance['thumb'] . ')"></div></div>';
        }


        if ($singlePlayerOptions['gallery_skin'] == 'skin-aura') {
          $i_fout .= '<div class="menu-artist-info">';
        }


        $i_fout .= '<span class="the-artist">' . $singleItemInstance['menu_artistname'] . '</span>';
        $i_fout .= '<span class="the-name">' . $singleItemInstance['menu_songname'] . '</span>';


        if ($singlePlayerOptions['gallery_skin'] == 'skin-aura') {
          $i_fout .= '</div>';
        }

        if (isset($_COOKIE['dzsap_ratesubmitted-' . $singleItemInstance['playerId_computed']])) {
          $singleItemInstance['menu_extrahtml'] = str_replace('download-after-rate', 'download-after-rate active', $singleItemInstance['menu_extrahtml']);
        } else {
          if (isset($_COOKIE['commentsubmitted-' . $singleItemInstance['playerId_computed']])) {
            $singleItemInstance['menu_extrahtml'] = str_replace('download-after-rate', 'download-after-rate active', $singleItemInstance['menu_extrahtml']);
          };
        }


        if ($singlePlayerOptions['gallery_skin'] == 'skin-aura') {
          $i_fout .= '<div class="menu-item-views"><svg class="svg-icon" version="1.1" id="Layer_2" xmlns="https://www.w3.org/2000/svg" xmlns:xlink="https://www.w3.org/1999/xlink" x="0px" y="0px" width="11.161px" height="12.817px" viewBox="0 0 11.161 12.817" enable-background="new 0 0 11.161 12.817" xml:space="preserve"> <g> <g> <g> <path fill="#D2D6DB" d="M8.233,4.589c1.401,0.871,2.662,1.77,2.801,1.998c0.139,0.228-1.456,1.371-2.896,2.177l-4.408,2.465 c-1.44,0.805-2.835,1.474-3.101,1.484c-0.266,0.012-0.483-1.938-0.483-3.588V3.666c0-1.65,0.095-3.19,0.212-3.422 c0.116-0.232,1.875,0.613,3.276,1.484L8.233,4.589z"/> </g> </g> </g> </svg> <span class="the-count">' . get_post_meta($singleItemInstance['playerId_computed'], DZSAP_DB_VIEWS_META_NAME, true) . '</span></div>';


          if ($singlePlayerOptions['menu_facebook_share'] == 'auto' || $singlePlayerOptions['menu_facebook_share'] == 'on' || $singlePlayerOptions['menu_like_button'] == 'auto' || $singlePlayerOptions['menu_like_button'] == 'on') {

            $i_fout .= '<div class="float-right">';
            if ($singlePlayerOptions['menu_facebook_share'] == 'auto' || $singlePlayerOptions['menu_facebook_share'] == 'on') {

              $i_fout .= ' <a rel="nofollow" class="btn-zoomsounds-menu menu-facebook-share"  onclick=\'window.dzs_open_social_link("https://www.facebook.com/sharer.php?u={{shareurl}}",this); return false;\'><i class="fa fa-share" aria-hidden="true"></i></a>';
            }
            if ($singlePlayerOptions['menu_like_button'] == 'auto' || $singlePlayerOptions['menu_like_button'] == 'on') {

              $i_fout .= ' <a rel="nofollow" class="btn-zoomsounds-menu menu-btn-like "><i class="fa fa-thumbs-up" aria-hidden="true"></i></a>';

            }

            $i_fout .= '</div>';
          }
        }


        $i_fout .= stripslashes($singleItemInstance['menu_extrahtml']);
        $i_fout .= '</div>';
      }


      if (isset($its['settings']['skinwave_comments_enable']) && $its['settings']['skinwave_comments_enable'] == 'on') {

        if ($singleItemInstance['playerId_computed'] != '') {

          $i_fout .= '<div class="the-comments">';
          $comms = get_comments(array('post_id' => $singleItemInstance['playerId_computed']));

          foreach ($comms as $comm) {
            $i_fout .= '<div class="dzstooltip-con dzsap--comment" style="left:' . dzsap_sanitize_to_css_perc($comm->comment_author_url) . '"><span class="dzstooltip arrow-from-start transition-slidein  arrow-bottom talign-start style-rounded color-dark-light  " style="width: 250px;"><span class="dzstooltip--inner"><span class="the-comment-author">@' . $comm->comment_author . '</span> says:<br>' . $comm->comment_content . '</span></span><fig class="the-avatar" style="background-image: url(https://secure.gravatar.com/avatar/' . md5($comm->comment_author_email) . '?s=20)"></fig></div>';


          }
          $i_fout .= '</div>';


          wp_enqueue_style('dzs.tooltip', DZSAP_BASE_URL . 'libs/dzstooltip/dzstooltip.css');
        }
      }

      if (isset($vpConfig) && $vpConfig['skin_ap'] && ($vpConfig['skin_ap'] == 'skin-customcontrols' || $vpConfig['skin_ap'] == 'skin-customhtml')) {

        $customContent = '';

        if ($singlePlayerOptions['the_content']) {
          $customContent = do_shortcode($singlePlayerOptions['the_content']);
        } else {
          if (isset($singlePlayerOptions['settings_extrahtml_in_player']) && $singlePlayerOptions['settings_extrahtml_in_player']) {
            $customContent = DZSZoomSoundsHelper::sanitize_from_meta_textarea($singlePlayerOptions['settings_extrahtml_in_player']);;
          }
        }
        $i_fout .= '<div hidden aria-hidden="true" class="feed-dzsap feed-dzsap--custom-controls">' . $customContent . '</div>';
      }
      // --- extra html meta


      $che_post = null;
      $singleItemInstance_post = null;
      if ($singleItemInstance['playerId_computed'] && $isPlayerIdFake === false) {
        $che_post = get_post($singleItemInstance['playerId_computed']);
      }


      if ($singlePlayerOptions['called_from'] == 'single_product_summary') {

        if (isset($singlePlayerOptions['product_id'])) {

          if ($dzsap->mainoptions['wc_product_play_in_footer'] == 'on') {


            $vpset = $dzsap->classView->get_zoomsounds_player_config_settings($dzsap->mainoptions['enable_global_footer_player']);


            $price = '';

            if (get_post_meta($singlePlayerOptions['product_id'], '_regular_price', true)) {
              if (function_exists('get_woocommerce_currency_symbol')) {
                $price .= get_woocommerce_currency_symbol();
              }
              if (get_post_meta($singlePlayerOptions['product_id'], '_sale_price', true)) {

                $price .= get_post_meta($singlePlayerOptions['product_id'], '_sale_price', true);
              } else {

                $price .= get_post_meta($singlePlayerOptions['product_id'], '_regular_price', true);
              }
            }


            if (isset($vpset['settings']['extra_classes_player']) && strpos($vpset['settings']['extra_classes_player'], 'skinvariation-wave-righter') !== false) {
              $i_fout .= '<div hidden class="feed-dzsap-for-extra-html-right"><form method="post" style="margin: 0!important; "><button class="zoomsounds-add-tocart-btn" name="add-to-cart" value="' . $singlePlayerOptions['product_id'] . '"><i class="fa fa-shopping-cart"></i>&nbsp;&nbsp;&nbsp;<span class="the-price">' . $price . '</span></button></form></div>';
            }


          }
        }

      }


      if (
      DZSZoomSoundsHelper::isPlayerHasExtrahtml($singleItemInstance, $its['settings'], $its['settings'], $its['playerConfigSettings'])
      ) {


        $extraHtmlAreas = $this->parseItems_determineExtraHtml($singleItemInstance, $its['settings'], $singlePlayerOptions, $its['playerConfigSettings'], $singleItemInstance['playerId_computed'], $che_post);


        $i_fout .= $this->generatePlayerExtraHtml($extraHtmlAreas, $singleItemInstance);
      }



      if (isset($singleItemInstance['inner_html']) && $singleItemInstance['inner_html']) {
        $i_fout .= $singleItemInstance['inner_html'];
      }


      if (isset($singlePlayerOptions['feed_embed_code']) && $singlePlayerOptions['feed_embed_code']) {
        $i_fout .= '<div aria-hidden="true" hidden class="feed-dzsap feed-dzsap--embed-code" >' . $singlePlayerOptions['feed_embed_code'] . '</div>';
      }

      $i_fout .= '</div>';// -- <!-- end .audioplayer-->

      if (isset($singleItemInstance['apply_script'])) {
      }


      if (isset($its['settings']) && $its['settings']['skin_ap'] && ($its['settings']['skin_ap'] == 'skin-customhtml')) {


        $i_fout = view_player_generateCustomHtml($singlePlayerOptions, $meta_artist_html, $pcmString, $fakeplayer_attr, $thumb_for_parent_attr, $thumb_link_attr);


      }

      $fout .= $i_fout;


    }


    return $fout;
  }


  /**
   * replace output
   * @param $singlePlayerOptions
   * @param $meta_artist_html
   * @param $pcmString
   * @param $fakeplayer_attr
   * @param $thumb_for_parent_attr
   * @param $thumb_link_attr
   * @return string|string[]
   */
  function view_player_generateCustomHtml($singlePlayerOptions, $meta_artist_html, $pcmString, $fakeplayer_attr, $thumb_for_parent_attr, $thumb_link_attr) {

    $i_fout = DZSZoomSoundsHelper::sanitize_from_meta_textarea($singlePlayerOptions['settings_extrahtml_in_player']);

    $i_fout = str_replace('{{artist_complete_html}}', $meta_artist_html, $i_fout);


    $lab = 'source';

    if (isset($singleItemInstance[$lab])) {
      $i_fout = str_replace('{{' . $lab . '}}', $singleItemInstance[$lab], $i_fout);
    } else {

      $i_fout = str_replace('{{' . $lab . '}}', '', $i_fout);
    }
    $lab = 'type';

    if (isset($singleItemInstance[$lab])) {
      $i_fout = str_replace('{{' . $lab . '}}', $singleItemInstance[$lab], $i_fout);
    } else {

      $i_fout = str_replace('{{' . $lab . '}}', '', $i_fout);
    }

    $lab = 'thumb';

    if (isset($singleItemInstance[$lab])) {
      $i_fout = str_replace('{{' . $lab . '}}', $singleItemInstance[$lab], $i_fout);
    } else {

      $i_fout = str_replace('{{' . $lab . '}}', '', $i_fout);
    }
    $lab = 'pcm';

    $i_fout = str_replace('{{' . $lab . '}}', $pcmString, $i_fout);

    $lab = 'fakeplayer_attr';
    $i_fout = str_replace('{{' . $lab . '}}', $fakeplayer_attr, $i_fout);

    $lab = 'thumb_for_parent_attr';
    $i_fout = str_replace('{{' . $lab . '}}', $thumb_for_parent_attr, $i_fout);

    $lab = 'thumb_link';
    $i_fout = str_replace('{{' . $lab . '}}', $thumb_link_attr, $i_fout);

    return $i_fout;
  }


  /**
   * [zoomsounds_player source="pathto.mp3" artistname="" songname=""]
   * @param array $argsShortcodePlayer
   * @param string $content
   * @return string
   */
  function shortcode_player($argsShortcodePlayer = array(), $content = '') {

    global $post;


    $dzsap = $this->dzsap;
    $fout = '';

    $dzsap->sliders__player_index++;
    $player_idx = $dzsap->sliders__player_index;

    $dzsap->front_scripts();

    $shortcodePlayerAtts = array_merge(DZSAP_VIEW_DEFAULT_SHORTCODE_PLAYER_ATTS, array(
      'player_index' => $player_idx,
    ));

    $default_margs = array_merge(array(), $shortcodePlayerAtts);

    if (isset($argsShortcodePlayer) && is_array($argsShortcodePlayer)) {
      $shortcodePlayerAtts = array_merge($shortcodePlayerAtts, $argsShortcodePlayer);
    }



    if ($content) {
      $shortcodePlayerAtts['content_inner'] = $content;
    }


    $shortcodePlayerAtts['source'] = DZSZoomSoundsHelper::player_parseItems_getSource($shortcodePlayerAtts['source'], $shortcodePlayerAtts);

    if (isset($shortcodePlayerAtts['the_post_title']) && $shortcodePlayerAtts['the_post_title'] && (!($shortcodePlayerAtts['songname']))) {
      $shortcodePlayerAtts['songname'] = $shortcodePlayerAtts['the_post_title'];
    }


    $original_player_margs = array_merge($shortcodePlayerAtts, array());

    $original_source = $shortcodePlayerAtts['source'];


    $embed_margs = array();


    // -- embed margs
    foreach ($shortcodePlayerAtts as $lab => $arg) {
      if (isset($shortcodePlayerAtts[$lab])) {
        if (isset($default_margs[$lab]) == false || $shortcodePlayerAtts[$lab] !== $default_margs[$lab]) {
          $embed_margs[$lab] = $shortcodePlayerAtts[$lab];
        }
      }
    }
    if (isset($embed_margs['cat_feed_data'])) {
      unset($embed_margs['cat_feed_data']);
    }


    $playerid = '';


    $player_post = null;


    if ($shortcodePlayerAtts['play_target'] == 'footer') {
      if (isset($shortcodePlayerAtts['faketarget']) && $shortcodePlayerAtts['faketarget']) {

      } else {
        $shortcodePlayerAtts['faketarget'] = '.'.DZSAP_VIEW_STICKY_PLAYER_ID;
      }
    }


    $po = null;


    if (is_int(intval($shortcodePlayerAtts['source']))) {
      $po = get_post($shortcodePlayerAtts['source']);

      if ($po) {
        if ($po->post_type == DZSAP_REGISTER_POST_TYPE_NAME) {
          $shortcodePlayerAtts['post_content'] = $po->post_content;

        }
      }

    }


    if ($shortcodePlayerAtts['source']) {
      if ($dzsap->get_track_source($shortcodePlayerAtts['source'], $playerid, $shortcodePlayerAtts) != $shortcodePlayerAtts['source']) {

        if (is_numeric($shortcodePlayerAtts['source'])) {
          if (isset($shortcodePlayerAtts['playerid']) == false || $shortcodePlayerAtts['playerid'] == '') {
            $shortcodePlayerAtts['playerid'] = $shortcodePlayerAtts['source'];
          }
        }
        $shortcodePlayerAtts['source'] = $dzsap->get_track_source($shortcodePlayerAtts['source'], $playerid, $shortcodePlayerAtts);
      }
    }


    $vpsettings = DZSZoomSoundsHelper::getVpSettings($shortcodePlayerAtts['config'], $shortcodePlayerAtts);


    if (isset($shortcodePlayerAtts['embedded']) && $shortcodePlayerAtts['embedded'] == 'on') {

      $vpsettings['enable_embed_button'] = 'off';


      $vpsettings['menu_right_enable_multishare'] = 'off';
    }
    if (isset($shortcodePlayerAtts['playerid']) && $shortcodePlayerAtts['playerid']) {

    } else {


      if (is_numeric($shortcodePlayerAtts['source'])) {
        $shortcodePlayerAtts['playerid'] = $shortcodePlayerAtts['source'];
      } else {


        $shortcodePlayerAtts['playerid'] = DZSZoomSoundsHelper::encode_to_number($shortcodePlayerAtts['source']);
      }


      if ($shortcodePlayerAtts['dzsap_meta_source_attachment_id'] && is_numeric($shortcodePlayerAtts['dzsap_meta_source_attachment_id'])) {


        $shortcodePlayerAtts['playerid'] = DZSZoomsoundsHelper::sanitizeForShortcodeAttr($shortcodePlayerAtts['dzsap_meta_source_attachment_id']);
      }

    }


    if ($vpsettings['settings']['skin_ap'] == 'null') {
      $vpsettings['settings']['skin_ap'] = 'skin-wave';
    }


    $its = array(0 => $shortcodePlayerAtts, 'settings' => array());

    $its['settings'] = array_merge($its['settings'], $vpsettings['settings']);
    $its['playerConfigSettings'] = $vpsettings['settings'];


    if ($shortcodePlayerAtts['enable_views'] == 'on') {
      $its['settings']['enable_views'] = 'on';
    }


    $settingsForParsePlayer = array_merge($vpsettings['settings'], $shortcodePlayerAtts);


    // -- lets overwrite some settings that we forced from shortcode args


    if (isset($argsShortcodePlayer['enable_embed_button']) && $argsShortcodePlayer['enable_embed_button']) {

      $settingsForParsePlayer['enable_embed_button'] = $argsShortcodePlayer['enable_embed_button'];
    }


    if (isset($settingsForParsePlayer['cat_feed_data'])) {

      include_once "../../class_parts/powerpress_cat_feed_data.php";
    }


    $settingsForParsePlayer['extra_html'] = DZSZoomsoundsHelper::sanitizeForShortcodeAttr($settingsForParsePlayer['extra_html'], $settingsForParsePlayer);

    $encodedMargs = base64_encode(json_encode($embed_margs));


    $embed_code = DZSZoomSoundsHelper::generate_embed_code(array(
      'call_from' => 'shortcode_player',
      'enc_margs' => $encodedMargs,
    ));


    $settingsForParsePlayer['embed_code'] = $embed_code;


    if ($settingsForParsePlayer['itunes_link']) {

      if (isset($its[0]['extra_html']) == false) {
        $its[0]['extra_html'] = '';
      }

      $its[0]['extra_html'] .= '  <a rel="nofollow" href="' . $settingsForParsePlayer['itunes_link'] . '" target="_blank" class=" btn-zoomsounds btn-itunes "><span class="the-icon"><i class="fa fa-apple"></i></span><span class="the-label ">iTunes</span></a>';
    }


    $settingsForParsePlayer['the_content'] = $content;

    if ($settingsForParsePlayer['songname'] && $settingsForParsePlayer['songname'] != 'default') {

      if (isset($its[0]['menu_songname']) == false || !($its[0]['menu_songname'] && $its[0]['menu_songname'] != 'default')) {

        $its[0]['menu_songname'] = $settingsForParsePlayer['songname'];
      }
    }
    if ($settingsForParsePlayer['artistname'] && $settingsForParsePlayer['artistname'] != 'default') {

      if (isset($its[0]['menu_artistname']) == false || !($its[0]['menu_artistname'] && $its[0]['menu_artistname'] != 'default')) {

        $its[0]['menu_artistname'] = $settingsForParsePlayer['artistname'];
      }
    }


    $lab = 'title_is_permalink';
    if (isset($settingsForParsePlayer[$lab]) && $settingsForParsePlayer[$lab]) {
      $its[0][$lab] = $settingsForParsePlayer[$lab];
    }
    if (isset($settingsForParsePlayer['product_id']) && $settingsForParsePlayer['product_id']) {

      $pid = $settingsForParsePlayer['product_id'];

      if (get_post_meta($pid, 'dzsap_meta_replace_artistname', true)) {

        $its[0]['artistname'] = get_post_meta($pid, 'dzsap_meta_replace_artistname', true);
      }
    }


    $dzsapSettingsArrayString = dzsap_generate_audioplayer_settings(array(
      'call_from' => 'shortcode_player',
      'enc_margs' => $encodedMargs,
      'extra_init_settings' => $settingsForParsePlayer['extra_init_settings'],
    ), $vpsettings, $its, $settingsForParsePlayer);

    if ($settingsForParsePlayer['openinzoombox'] != 'on') {


      if ($settingsForParsePlayer['init_player'] == 'on') {
        if ($dzsap->mainoptions['init_javascript_method'] != 'script') {
          $settingsForParsePlayer['auto_init_player'] = 'on';
        }
        $settingsForParsePlayer['auto_init_player_options'] = $dzsapSettingsArrayString;
      }


      if ($encodedMargs) {

        $embed_code = DZSZoomSoundsHelper::generate_embed_code(array(
          'call_from' => 'shortcode_player',
          'enc_margs' => $encodedMargs,
        ));
        $settingsForParsePlayer['feed_embed_code'] = $embed_code;
      }

      // -- player
      $fout .= $this->parse_items($its, $settingsForParsePlayer, array());

    }

    $player_id = $settingsForParsePlayer['playerid'];

    // -- normal mode
    if ($shortcodePlayerAtts['init_player'] == 'on') {
      DZSZoomSoundsHelper::enqueueMainScrips();
    }


    $extra_buttons_html = '';

    if ($dzsap->mainoptions['analytics_enable'] == 'on') {
      if (current_user_can('manage_options')) {
        if ($shortcodePlayerAtts['called_from'] != 'footer_player') {

          // -- the stats

          $extra_buttons_html .= '<span class="btn-zoomsounds stats-btn" data-playerid="' . $shortcodePlayerAtts['playerid'] . '"  data-sanitized_source="' . DZSZoomSoundsHelper::sanitize_toKey($shortcodePlayerAtts['source']) . '" data-url="' . dzs_curr_url() . '" ><span class="the-icon"><i class="fa fa-tachometer" aria-hidden="true"></i></span><span class="btn-label">' . esc_html__('Stats', DZSAP_ID) . '</span></span>';


          // -- some portal delete button : todo: complete


        }


        DZSZoomSoundsHelper::enqueueAudioPlayerShowcase();
        wp_enqueue_style('fontawesome', DZSAP_URL_FONTAWESOME_EXTERNAL);

      }


    }
    if ($shortcodePlayerAtts['called_from'] != 'footer_player') {

      if (DZSZoomSoundsHelper::isTheTrackHasFromCurrentUser($shortcodePlayerAtts['playerid'])) {

        $extra_buttons_html .= DZSZoomSoundsHelper::generateExtraButtonsForPlayerDeleteEdit($shortcodePlayerAtts['playerid']);

      }
    }

    if ($extra_buttons_html && $shortcodePlayerAtts['called_from'] != 'playlist_showcase') {
      if ($dzsap->mainoptions['enable_aux_buttons'] === 'on') {
        $fout .= '<div class="extra-btns-con">';
        $fout .= $extra_buttons_html;
        $fout .= '</div>';
      }
    }


    // -- this fixes some & being converted to &#038;
    remove_filter('the_content', 'wptexturize');

    DZSZoomSoundsHelper::enqueueMainScrips();
    return $fout;
  }

  /**
   * called in parse_items()
   * @param $playerAttributes
   * @param $argPlaylistOptions
   * @param $argPlayerOptions
   * @param $argPlayerConfig
   * @param $playerid
   * @param $che_post
   * @return string[]
   */
  public function parseItems_determineExtraHtml($playerAttributes, $argPlaylistOptions, $argPlayerOptions, $argPlayerConfig, $playerid, $che_post) {

    $extraHtmlAreas = array(
      'bottom' => '',
      'bottom_left' => '',
      'afterArtist' => '',
      'controlsLeft' => '',
      'controlsRight' => '',
      'afterPlayPause' => '',
      'afterConControls' => '',
    );
    $i_fout = '';


    $playlistOptions = array(
      'enable_downloads_counter' => 'off'
    );
    $playerOptions = array(
      'is_single' => 'off',
      'embedded' => 'off',
    );
    /** @var $playlistAndPlayerOptions array  common attributes */
    $playlistAndPlayerOptions = array(
      'enable_rates' => 'off',
      'enable_views' => 'off',
      'enable_likes' => 'off',
      'enable_download_button' => 'off',
      'menu_right_enable_info_btn' => 'off',
      'js_settings_extrahtml_in_float_right_from_config' => '',
      'js_settings_extrahtml_in_bottom_controls_from_config' => '',
    );
    $playerConfig = array(
      'enable_config_button' => 'off',
      'enable_embed_button' => 'off',
    );

    if (count($playerConfig)) {
      $playerConfig = array_merge($playerConfig, $argPlayerConfig);
    }
    if (count($playerOptions)) {
      $playerOptions = array_merge($playerOptions, $argPlayerOptions);
    }

    if (count($argPlaylistOptions)) {
      $playlistOptions = array_merge($playlistOptions, $argPlaylistOptions);
    }
    $playlistAndPlayerOptions = array_merge($playlistAndPlayerOptions, $playlistOptions);
    $playlistAndPlayerOptions = array_merge($playlistAndPlayerOptions, $playerConfig);
    $playlistAndPlayerOptions = array_merge($playlistAndPlayerOptions, $playerOptions);


    $dzsap = $this->dzsap;


    include_once DZSAP_BASE_PATH . 'inc/php/view-functions/view-determine-html-areas.php';

    $extraHtmlAreas['bottom'] = dzsap_view_determineHtmlAreas_bottom($dzsap, $playerAttributes, $playlistAndPlayerOptions, $playerid);

    $extraHtmlAreas['bottom_left'] = dzsap_view_determineHtmlAreas_bottomLeft($dzsap, $playerAttributes, $playerOptions, $playlistAndPlayerOptions, $playerConfig, $playerid);

    $extraHtmlAreas['controlsLeft'] = dzsap_view_determineHtmlAreas_controlsLeft($playerAttributes);
    $extraHtmlAreas['controlsRight'] = dzsap_view_determineHtmlAreas_controlsRight($dzsap, $playerAttributes, $playerConfig, $che_post, $playlistAndPlayerOptions);
    $extraHtmlAreas['afterPlayPause'] = dzsap_view_determineHtmlAreas_controlsAfterPlayPause($playerConfig);
    $extraHtmlAreas['afterConControls'] = dzsap_view_determineHtmlAreas_controlsAfterConControls($playerConfig, $playerOptions);


    return $extraHtmlAreas;
  }

  /**
   * called in parse_items()
   * @param $singleItemInstance
   * @param $argPlaylistOptions
   * @param $argPlayerOptions
   * @param $argPlayerConfig
   * @param $playerid
   * @param $che_post
   * @return string
   */
  public function generatePlayerExtraHtml($extraHtmlAreas, $singleItemInstance) {

    $i_fout = '';

    foreach ($extraHtmlAreas as $key => $extraHtmlArea) {

      $extraHtmlAreas[$key] = DZSZoomSoundsHelper::sanitize_for_extraHtml($extraHtmlAreas[$key], $singleItemInstance);;
    }
    if ($extraHtmlAreas['controlsLeft']) {
      $i_fout .= $extraHtmlAreas['controlsLeft'];
    }
    if (isset($extraHtmlAreas['controlsRight']) && $extraHtmlAreas['controlsRight']) {
      $i_fout .= $extraHtmlAreas['controlsRight'];
    }
    if (isset($extraHtmlAreas['afterPlayPause']) && $extraHtmlAreas['afterPlayPause']) {
      $i_fout .= $extraHtmlAreas['afterPlayPause'];
    }
    if (isset($extraHtmlAreas['afterConControls']) && $extraHtmlAreas['afterConControls']) {
      $i_fout .= $extraHtmlAreas['afterConControls'];
    }
    if ($extraHtmlAreas['bottom_left']) {

      $i_fout .= '<div hidden class="feed-dzsap feed-dzsap--extra-html ">';
      $i_fout .= $extraHtmlAreas['bottom_left'];
      $i_fout .= '</div><!-- end .extra-html--left-->';
    }
    if ($extraHtmlAreas['bottom']) {

      $i_fout .= '<div hidden class="feed-dzsap feed-dzsap--extra-html" data-playerid="' . $singleItemInstance['playerid'] . '" style="opacity:0;">' . ($extraHtmlAreas['bottom']) . '</div>';
    }

    return $i_fout;
  }

  /**
   * @return array|mixed
   */
  function get_wishlist() {


    $arr_wishlist = array();

    if (get_user_meta(get_current_user_id(), 'dzsap_wishlist', true) && get_user_meta(get_current_user_id(), 'dzsap_wishlist', true) != 'null') {
      try {

        $arr_wishlist = json_decode(get_user_meta(get_current_user_id(), 'dzsap_wishlist', true), true);
      } catch (Exception $e) {

      }
    }

    return $arr_wishlist;
  }


  function shortcode_player_comment_field() {

    $fout = '';

    global $current_user;


    if ($current_user->ID) {
      $fout .= '<div class="zoomsounds-comment-wrapper">
                <div class="zoomsounds-comment-wrapper--avatar divimage" style="background-image: url(https://www.gravatar.com/avatar/?d=identicon);"></div>
                <div class="zoomsounds-comment-wrapper--input-wrap">
                    <input type="text" class="comment_text" placeholder="' . esc_html__("Write a comment") . '"/>
                    <input type="text" class="comment_email" placeholder="' . esc_html__("Your email") . '"/>
                    <!--<input type="text" class="comment_user" placeholder="' . esc_html__("Your display name") . '"/>-->
                </div>

                <div class="zoomsounds-comment-wrapper--buttons">
                    <span class="dzs-button-dzsap comments-btn-cancel">' . esc_html__("Cancel") . '</span>
                    <span class="dzs-button-dzsap comments-btn-submit">' . esc_html__("Submit") . '</span>
                </div>
            </div>';
    } else {
      $fout .= esc_html__("You need to be logged in to comment");
    }


    return $fout;


  }


  function show_curr_plays($pargs = array(), $content = '') {
    global $post;

    $fout = '';


    $str_views = $this->dzsap->mainoptions['str_views'];


    if (isset($pargs['id'])) {
      $post = get_post($pargs['id']);
    }


    if ($post) {
      $str_views = $this->dzsap->ajax_functions->get_metaViews($post->ID);
      $fout = str_replace('{{get_plays}}', $aux, $str_views);
    }
    return $fout;
  }

  /**
   * default wordpress audio [zoomsounds_player source="pathto.mp3"]
   * @param array $atts
   * @param null $content
   * @return string
   */
  function shortcode_audio($atts = array(), $content = null) {


    // --


    $dzsap = $this->dzsap;
    $dzsap->sliders__player_index++;

    $fout = '';


    $dzsap->front_scripts();

    $margs = array(
      'mp3' => '',
      'wav' => '',
      'm4a' => '',
      'config' => 'default',
    );

    if (!is_array($atts)) {
      $atts = array();
    }

    $margs = array_merge($margs, $atts);

    if ($margs['mp3']) {
      $margs['source'] = $margs['mp3'];
    } else {
      if ($margs['wav']) {
        $margs['source'] = $margs['wav'];
      } else {
        if ($margs['m4a']) {
          $margs['source'] = $margs['m4a'];
        }
      }
    }
    $margs['config'] = $dzsap->mainoptions['replace_audio_shortcode'];
    $margs['called_from'] = 'audio_shortcode';


    $audio_attachments = get_posts(array(
      'post_type' => 'attachment',
      'post_mime_type' => 'audio'
    ));


    $pid = 0;
    foreach ($audio_attachments as $lab => $val) {


      if ($val->guid == $margs['source']) {
        $pid = $val->ID;
        break;
      }
    }

    if ($pid) {


      $margs['source'] = $pid;
    }


    if ($dzsap->mainoptions['replace_audio_shortcode_extra_args']) {
      try {
        $arr = json_decode($dzsap->mainoptions['replace_audio_shortcode_extra_args'], true);
        $margs = array_merge($margs, $arr);
      } catch (Exception $e) {
      }
    }

    if ($dzsap->mainoptions['replace_audio_shortcode_play_in_footer'] == 'on') {
      $margs['play_target'] = 'footer';
    }

    $playerid = '';

    $fout .= $dzsap->classView->shortcode_player($margs, $content);


    return $fout;
  }


  /**
   * [playlist ids="2,3,4"]
   * @param $atts
   * @return string
   */
  function shortcode_wpPlaylist($atts) {

    //
    $dzsap = $this->dzsap;

    global $current_user;
    $fout = '';
    $iout = ''; //items parse

    $defaultPlaylistOptions = array(
      'ids' => '1'
    , 'embedded_in_zoombox' => 'off'
    , 'embedded' => 'off'
    , 'db' => 'main'
    );

    if ($atts == '') {
      $atts = array();
    }

    $defaultPlaylistOptions = array_merge($defaultPlaylistOptions, $atts);


    $po_array = explode(",", $defaultPlaylistOptions['ids']);

    $fout .= '[zoomsounds id="playlist_gallery" embedded="' . $defaultPlaylistOptions['embedded'] . '" for_embed_ids="' . $defaultPlaylistOptions['ids'] . '"]';


    // -- setting up the db ( deprecated )
    $currDb = '';
    if (isset($defaultPlaylistOptions['db']) && $defaultPlaylistOptions['db'] != '') {
      $dzsap->currDb = $defaultPlaylistOptions['db'];
      $currDb = $dzsap->currDb;
    }
    $dzsap->dbs = get_option(DZSAP_DBNAME_LEGACY_DBS);

    $dbname_mainitems = DZSAP_DBNAME_MAINITEMS;
    if ($currDb != 'main' && $currDb != '') {
      $dbname_mainitems .= '-' . $currDb;
      $dzsap->mainitems = get_option($dbname_mainitems);
    }
    // -- setting up the db END


    $dzsap->front_scripts();


    $dzsap->sliders_index++;


    $i = 0;
    $k = 0;
    $id = DZSAP_VIEW_SHOWCASE_PLAYLIST_ID;
    if (isset($defaultPlaylistOptions['id'])) {
      $id = $defaultPlaylistOptions['id'];
    }


    $term_meta = array();
    $its = array(
      'settings' => array(),
    );
    $selected_term_id = '';


    $args = array(
      'id' => $id,
      'force_ids' => $defaultPlaylistOptions['ids'],
      'called_from' => 'shortcode_playlist',
    );
    $this->get_its_items($its, $args);

    if ($dzsap->mainoptions['playlists_mode'] == 'normal') {
      $tax = DZSAP_TAXONOMY_NAME_SLIDERS;
      $reference_term = get_term_by('slug', $id, $tax);
      if ($reference_term) {

        $selected_term_id = $reference_term->term_id;
        $term_meta = get_option("taxonomy_$selected_term_id");
      }
    }


    $this->get_its_settings($its, $defaultPlaylistOptions, $term_meta, $selected_term_id);


    $enable_likes = 'off';
    $enable_views = 'off';
    $enable_downloads_counter = 'off';

    if ($its) {
      $lab = 'enable_views';
      if (isset($its['settings'][$lab]) && $its['settings'][$lab]) {
        $enable_views = $its['settings'][$lab];
      }
      $lab = 'enable_likes';
      if (isset($its['settings'][$lab]) && $its['settings'][$lab]) {
        $enable_likes = $its['settings'][$lab];
      }
      $lab = 'enable_downloads_counter';
      if (isset($its['settings'][$lab]) && $its['settings'][$lab]) {
        $enable_downloads_counter = $its['settings'][$lab];
      }
    }


    foreach ($po_array as $po_id) {


      if (is_numeric($po_id)) {

        $po = get_post($po_id);
        $title = $po->post_title;
        $title = str_replace(array('"', '[', ']'), '&quot;', $title);
        $desc = $po->post_content;
        $desc = str_replace(array('"', '[', ']'), '&quot;', $desc);
        $fout .= '[zoomsounds_player source="' . $po->guid . '" config="playlist_player" playerid="' . $po_id . '" thumb="" autoplay="on" cueMedia="on" enable_likes="' . $enable_likes . '" enable_views="' . $enable_views . '"  enable_downloads_counter="' . $enable_downloads_counter . '" songname="' . $title . '" artistname="' . $desc . '" init_player="off"]';
      } else {

        $fout .= '[zoomsounds_player source="' . $po_id . '" config="playlist_player" playerid="' . $po_id . '" thumb="" autoplay="off" cueMedia="on" enable_likes="' . $enable_likes . '" enable_views="' . $enable_views . '"  enable_downloads_counter="' . $enable_downloads_counter . '"  init_player="off"]';
      }

    }
    $fout .= '[/zoomsounds]';


    $fout = do_shortcode($fout);


    return $fout;
  }

  function playlist_initialSetup(&$its) {


    // -- embed

    if (isset($its['settings']['gallery_embed_type'])) {
      if ($its['settings']['gallery_embed_type'] === 'on-no-embed') {

      }
      if ($its['settings']['gallery_embed_type'] === 'on-with-embed') {


        $its['playerConfigSettings']['enable_embed_button'] = 'in_lightbox';
      }
    }

  }


  /** [zoomsounds id="theid"]
   * @param array $atts
   * @param null $content
   * @return string|void
   */
  public function shortcode_playlist_main($atts = array(), $content = null) {

    global $current_user;
    $fout = '';
    $iout = ''; //items parse

    $shortcodeOptions = array(
      'playlist_id' => 'default'
    , 'db' => ''
    , 'category' => ''
    , 'extra_classes' => ''
    , 'fullscreen' => 'off'
    , 'settings_separation_mode' => 'normal'  // === normal ( no pagination ) or pages or scroll or button
    , 'settings_separation_pages_number' => '5'//=== the number of items per 'page'
    , 'settings_separation_paged' => '0'//=== the page number
    , 'return_onlyitems' => 'off' // ==return only the items ( used by pagination )
    , 'embedded' => 'off'
    , 'divinsteadofscript' => 'off'
    , 'width' => '-1'
    , 'height' => '-1'
    , 'embedded_in_zoombox' => 'off'
    , 'for_embed_ids' => ''
    , 'is_single' => 'off'
    , 'overwrite_only_its' => ''
    , 'called_from' => 'default'
    , 'play_target' => 'default'
    );

    if ($atts == '') {
      $atts = array();
    }

    $shortcodeOptions = array_merge($shortcodeOptions, $atts);

    if ((!$shortcodeOptions['playlist_id'] || $shortcodeOptions['playlist_id'] == 'default') && isset($shortcodeOptions['id']) && $shortcodeOptions['id']) {
      $shortcodeOptions['playlist_id'] = $shortcodeOptions['id'];
    }

    // -- the id will get replaced so we can store the original id / slug
    $shortcodeOptions['original_id'] = $shortcodeOptions['playlist_id'];


    $dzsap = $this->dzsap;
    // -- setting up the db
    $currDb = '';
    if (isset($shortcodeOptions['db']) && $shortcodeOptions['db'] != '') {
      $dzsap->currDb = $shortcodeOptions['db'];
      $currDb = $dzsap->currDb;
    }

    $dzsap->dbs = get_option(DZSAP_DBNAME_LEGACY_DBS);
    $dzsap->db_read_mainitems();


    // -- setting up the db END


    $dzsap->front_scripts();


    $dzsap->sliders_index++;


    $its = array(
      'settings' => array(),
    );
    $selected_term_id = '';

    $term_meta = array();


    if ($shortcodeOptions['for_embed_ids']) {
      $shortcodeOptions['force_ids'] = $shortcodeOptions['for_embed_ids'];
    }
    $this->get_its_items($its, $shortcodeOptions);

    if ($dzsap->mainoptions['playlists_mode'] == 'normal') {
      $tax = DZSAP_TAXONOMY_NAME_SLIDERS;


      $reference_term = get_term_by('slug', $shortcodeOptions['playlist_id'], $tax);

      if ($reference_term) {

      } else {
        // -- reference term does not exist..

        $directores = get_terms(DZSAP_TAXONOMY_NAME_SLIDERS);

        $playerOptionsArgs = $shortcodeOptions;
        $playerOptionsArgs['playlist_id'] = $directores[0]->slug;
        if ($shortcodeOptions['called_from'] != 'redo') {
          $playerOptionsArgs['called_from'] = 'redo';
          return $this->shortcode_playlist_main($playerOptionsArgs);
        }
        return '';
      }


      $selected_term_id = $reference_term->term_id;

      $term_meta = get_option("taxonomy_$selected_term_id");
    }


    if ($shortcodeOptions['overwrite_only_its'] && is_array($shortcodeOptions['overwrite_only_its'])) {


      $new_its = array_merge(array(), $its);
      foreach ($its as $lab => $val) {
        if ($lab !== 'settings') {
          unset($new_its[$lab]);
        }
      }
      $new_its = array_merge($new_its, $shortcodeOptions['overwrite_only_its']);

      $its = $new_its;
    }


    $this->get_its_settings($its, $shortcodeOptions, $term_meta, $selected_term_id);
    // -- after settings


    $i = 0;

    $vpsettings = DZSZoomSoundsHelper::getVpSettings($its['settings']['vpconfig']);
    $sanitizedApConfigId = DZSZoomSoundsHelper::sanitizeToValidObjectLabel($vpsettings['settings']['id']);


    unset($vpsettings['settings']['id']);
    $its['settings'] = array_merge($its['settings'], $vpsettings['settings']);
    $its['playerConfigSettings'] = ($vpsettings['settings']);
    $its['playerConfigSettings']['id'] = $sanitizedApConfigId;


    $this->playlist_initialSetup($its);


    // -- some sanitizing
    $tw = $its['settings']['width'];
    $th = $its['settings']['height'];

    if ($shortcodeOptions['width'] != '-1') {
      $tw = $shortcodeOptions['width'];
    }
    if ($shortcodeOptions['height'] != '-1') {
      $th = $shortcodeOptions['height'];
    }
    $str_tw = '';
    $str_th = '';


    if ($tw != '') {
      $str_tw .= 'width: ';
      $str_tw .= DZSZoomSoundsHelper::sanitizeToPx($tw);
      $str_tw .= ';';
    }


    if ($th != '') {
      $str_th .= 'height: ';
      $str_tw .= DZSZoomSoundsHelper::sanitizeToPx($th);
      $str_th .= ';';
    }


    $skinGallery = 'skin-wave';

    if (isset($its['settings']['galleryskin'])) {
      $skinGallery = $its['settings']['galleryskin'];
    }


    $sanitizedApConfigId = DZSZoomSoundsHelper::sanitizeToValidObjectLabel($its['playerConfigSettings']['id']);

    $newSettings = array();
    if (isset($its['settings']['autoplaynext'])) {
      $newSettings['autoplay_next'] = $its['settings']['autoplaynext'];
    }
    $newSettings['embedded'] = $shortcodeOptions['embedded'];
    $newSettings['settings_ap'] = $sanitizedApConfigId;


    $videoPlaylistSettingsMerged = array_merge($its['settings'], $newSettings);


    if (isset($videoPlaylistSettingsMerged['settings_mode_showall_show_number'])) {
      if ($videoPlaylistSettingsMerged['settings_mode_showall_show_number'] && $videoPlaylistSettingsMerged['settings_mode_showall_show_number'] == 'on') {
        wp_enqueue_script('isotope', DZSAP_BASE_URL . 'libs/isotope/isotope.js');
      }
    }


    if (isset($its['settings']['settings_enable_linking'])) {
      if (isset($videoPlaylistSettingsMerged) === false || $videoPlaylistSettingsMerged === '') {
        $videoPlaylistSettingsMerged['enable_linking'] = $its['settings']['settings_enable_linking'];
      }
    }

    if (isset($_GET['fromsharer']) && $_GET['fromsharer'] == 'on') {
      if (isset($_GET['audiogallery_startitem_ag1']) && $_GET['audiogallery_startitem_ag1'] !== '') {
        $videoPlaylistSettingsMerged['design_menu_state'] = 'closed';
      }
    }


    // -- playlist
    if (isset($its['playerConfigSettings']['colorhighlight']) && $its['playerConfigSettings']['colorhighlight']) {

      $audioGalleryCustomColorsCss = DZSZoomSoundsHelper::generateCssPlayerCustomColors(array(
        'skin_ap' => $its['playerConfigSettings']['skin_ap'],
        'selector' => '.audiogallery#ag' . $dzsap->sliders_index . ' .audioplayer',
        'colorhighlight' => $its['playerConfigSettings']['colorhighlight'],
      ));
      wp_register_style('dzsap-hook-gallery-custom-styles', false);
      wp_enqueue_style('dzsap-hook-gallery-custom-styles');
      wp_add_inline_style('dzsap-hook-gallery-custom-styles', $audioGalleryCustomColorsCss);
    }


    if (isset($its['settings']['enable_bg_wrapper']) && $its['settings']['enable_bg_wrapper'] == 'on') {
      $fout .= '<div class="ap-wrapper">
<div class="the-bg"></div>';
    }

    // -- main gallery div
    $fout .= '<div   id="ag' . $dzsap->sliders_index . '" class="audiogallery ag_slug_' . $shortcodeOptions['original_id'] . ' auto-init ' . $skinGallery . ' id_' . $its['settings']['id'] . ' ';


    if ($shortcodeOptions['extra_classes']) {
      $fout .= ' ' . $shortcodeOptions['extra_classes'];
    }


    $fout .= '" style="background-color:' . $its['settings']['bgcolor'] . ';' . $str_tw . '' . $str_th . '" data-options=\'' . json_encode(dzsap_generate_javascript_setting_for_playlist($videoPlaylistSettingsMerged)['foutArr']) . '\'>';


    if ($content) {
      $iout .= do_shortcode($content);
    } else {

      $playerOptionsArgs = array(
        'called_from' => 'gallery',
        'gallery_skin' => $skinGallery,
      );
      $playerOptionsArgs = array_merge($vpsettings['settings'], $playerOptionsArgs);
      $playerOptionsArgs = array_merge($playerOptionsArgs, $shortcodeOptions);


      $playerOptionsArgs['called_from'] = 'gallery';


      if ($its['playerConfigSettings']['enable_embed_button'] === 'in_lightbox' || $its['playerConfigSettings']['enable_embed_button'] === 'in_extra_html') {


        $embed_code = DZSZoomSoundsHelper::generate_embed_code(array(
          'call_from' => 'shortcode_player',
          'playlistId' => $shortcodeOptions['playlist_id'],
        ), false);
        $playerOptionsArgs['feed_embed_code'] = $embed_code;
      }


      $iout .= $dzsap->parse_items($its, $playerOptionsArgs, $shortcodeOptions);

    }

    $fout .= '<div class="items">';
    $fout .= $iout;


    $fout .= '</div>';
    $fout .= '</div>'; // -- end .audiogallery


    if (isset($its['settings']['enable_bg_wrapper']) && $its['settings']['enable_bg_wrapper'] == 'on') {
      $fout .= '</div>';
    }


    $playerSettingsFromGallery = array();


    if (isset($its['playerConfigSettings']['enable_embed_button']) && ($its['playerConfigSettings']['enable_embed_button'] != 'off')) {

      $deprecatedStringDb = '';
      if ($dzsap->currDb != '') {
        $deprecatedStringDb = '&db=' . $dzsap->currDb . '';
      }
      if ($shortcodeOptions['playlist_id'] == DZSAP_VIEW_SHOWCASE_PLAYLIST_ID) {
        $str = '<iframe src="' . site_url() . '?action=zoomsounds-embedtype=playlist&ids=' . $shortcodeOptions['for_embed_ids'] . '' . $deprecatedStringDb . '" width="100%" height="' . $its['settings']['height'] . '" style="overflow:hidden; transition: height 0.5s ease-out;" scrolling="no" frameborder="0"></iframe>';
      } else {
        $str = '<iframe src="' . site_url() . '?action=zoomsounds-embed&type=gallery&id=' . $its['settings']['id'] . '' . $deprecatedStringDb . '" width="100%" height="' . $its['settings']['height'] . '" style="overflow:hidden; transition: height 0.5s ease-out;" scrolling="no" frameborder="0"></iframe>';
      }


      $str = str_replace('"', "'", $str);
      $playerSettingsFromGallery['embed_code'] = htmlentities($str, ENT_QUOTES);
    }


    if (isset($its['settings']['enable_embed_button']) && ($its['settings']['enable_embed_button'] == 'on' || $vpsettings['settings']['enable_embed_button'] == 'in_player_controls')) {
      $playerSettingsFromGallery['enable_embed_button'] = 'on';
    }


    $dzsap->mainoptions['color_waveformbg'] = str_replace('#', '', $dzsap->mainoptions['color_waveformbg']);
    if ($dzsap->mainoptions['skinwave_wave_mode'] == 'canvas') {

      $playerSettingsFromGallery['pcm_data_try_to_generate'] = $dzsap->mainoptions['pcm_data_try_to_generate'];
      $playerSettingsFromGallery['pcm_notice'] = $dzsap->mainoptions['pcm_notice'];
      $playerSettingsFromGallery['notice_no_media'] = $dzsap->mainoptions['notice_no_media'];

    }


    $audioplayerSettingsMerged = array_merge($its['playerConfigSettings'], $playerSettingsFromGallery);


    $dzsap->audioPlayerConfigs[$sanitizedApConfigId] = dzsap_generate_javascript_setting_for_player($audioplayerSettingsMerged)['foutArr'];


    $url = DZSAP_URL_FONTAWESOME_EXTERNAL;
    if ($dzsap->mainoptions['fontawesome_load_local'] == 'on') {
      $url = DZSAP_BASE_URL . 'libs/fontawesome/font-awesome.min.css';
    }


    wp_enqueue_style('fontawesome', $url);


    // -- this fixes some & being converted to &#038;
    remove_filter('the_content', 'wptexturize');

    if ($shortcodeOptions['return_onlyitems'] != 'on') {
      return $fout;
    } else {
      return $iout;
    }


  }


  function get_its_items(&$its, $shortcodeOptions) {
    global $dzsap;
    // -- from @margs we need id

    if ($dzsap->mainoptions['playlists_mode'] == 'normal') {

      // -- try to get from reference term
      $tax = DZSAP_TAXONOMY_NAME_SLIDERS;


      $reference_term = get_term_by('slug', $shortcodeOptions['playlist_id'], $tax);


      if ($reference_term) {


      } else {
        // -- reference term does not exist..

        $directores = get_terms(DZSAP_TAXONOMY_NAME_SLIDERS);

        $args = $shortcodeOptions;
        $args['id'] = $directores[0]->slug;
        if ($shortcodeOptions['called_from'] != 'redo') {
          $args['called_from'] = 'redo';
          return $this->shortcode_playlist_main($args);
        }
        return '';
      }

      $selected_term_id = $reference_term->term_id;

      $term_meta = get_option("taxonomy_$selected_term_id");


      // -- main order
      if ($selected_term_id) {

        $args = array(
          'post_type' => 'dzsap_items',
          'numberposts' => -1,
          'posts_per_page' => -1,

          'orderby' => 'meta_value_num',
          'order' => 'ASC',

          'tax_query' => array(
            array(
              'taxonomy' => $tax,
              'field' => 'id',
              'terms' => $selected_term_id // Where term_id of Term 1 is "1".
            )
          ),
        );


        if (isset($term_meta['orderby'])) {
          if ($term_meta['orderby'] == 'rand') {
            $args['orderby'] = $term_meta['orderby'];
          }
          if ($term_meta['orderby'] == 'custom') {
            $args['meta_query'] = array(
              'relation' => 'OR',
              array(
                'key' => 'dzsap_meta_order_' . $selected_term_id,
                'compare' => 'EXISTS',
              ),
              array(
                'key' => 'dzsap_meta_order_' . $selected_term_id,
                'compare' => 'NOT EXISTS'
              )
            );
          }
          if ($term_meta['orderby'] == 'ratings_score') {
            $args['orderby'] = 'meta_value_num';

            $key = '_dzsap_rate_index';
            $args['meta_query'] = array(
              'relation' => 'OR',
              array(
                'key' => $key,
                'compare' => 'EXISTS',
              ),
              array(
                'key' => $key,
                'compare' => 'NOT EXISTS'
              )
            );
            $args['meta_type'] = 'NUMERIC';
            $args['order'] = 'DESC';

          }
          if ($term_meta['orderby'] == 'ratings_number') {
            $args['orderby'] = 'meta_value_num';

            $key = '_dzsap_rate_nr';
            $args['meta_query'] = array(
              'relation' => 'OR',
              array(
                'key' => $key,
                'compare' => 'EXISTS',
              ),
              array(
                'key' => $key,
                'compare' => 'NOT EXISTS'
              )
            );
            $args['meta_type'] = 'NUMERIC';
            $args['order'] = 'DESC';
          }
          if ($term_meta['orderby'] == 'alphabetical_ASC') {
            $args['orderby'] = 'title';
            $args['order'] = 'ASC';
          }
          if ($term_meta['orderby'] == 'alphabetical_DESC') {
            $args['orderby'] = 'title';
            $args['order'] = 'DESC';
          }
        }

        if (isset($shortcodeOptions['force_ids']) && $shortcodeOptions['force_ids']) {

          $args['post_type'] = 'any';
          $args['post_status'] = 'any';
          $args['post__in'] = explode(',', $shortcodeOptions['force_ids']);
          unset($args['tax_query']);
          unset($args['meta_query']);
        }
        $my_query = new WP_Query($args);


        foreach ($my_query->posts as $po) {


          $por = DZSZoomSoundsHelper::sanitize_to_gallery_item($po);

          array_push($its, $por);

        }
      }
    } else {
      // -- legacy mode

      if (isset($shortcodeOptions['playlist_id'])) {
        $id = $shortcodeOptions['playlist_id'];
      }

      for ($i = 0; $i < count($dzsap->mainitems); $i++) {

        if (isset($dzsap->mainitems[$i]) && isset($dzsap->mainitems[$i]['settings'])) {

          if ((isset($id)) && ($id == $dzsap->mainitems[$i]['settings']['id'])) {
            $k = $i;
          }
        }
      }
      $its = $dzsap->mainitems[$k];
    }


  }


  function get_its_settings(&$its, $margs, $term_meta, $selected_term_id) {
    global $dzsap;

    $its_settings_default = array(
      'galleryskin' => 'skin-wave',
      'vpconfig' => 'default',
      'bgcolor' => 'transparent',
      'width' => '',
      'height' => '',
      'autoplay' => '',
      'autoplaynext' => 'on',
      'autoplay_next' => '',
      'menuposition' => 'bottom',
    );
    if ($dzsap->mainoptions['playlists_mode'] == 'normal') {
      $its_settings_default['id'] = $selected_term_id;
    }

    if (isset($its['settings']) == false) {
      $its['settings'] = array();
    }

    $its['settings'] = array_merge($its_settings_default, $its['settings']);


    if ($dzsap->mainoptions['playlists_mode'] == 'normal') {
      if (is_array($term_meta)) {

        foreach ($term_meta as $lab => $val) {
          if ($lab == 'autoplay_next') {

            $lab = 'autoplaynext';
          }
          $its['settings'][$lab] = $val;

        }
      }
    }
  }

}