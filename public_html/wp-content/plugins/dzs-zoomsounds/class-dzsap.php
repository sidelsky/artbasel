<?php
include_once(DZSAP_BASE_PATH . "configs/constants.php");
include_once(DZSAP_BASE_PATH . "class_parts/class-ajax-functions.php");
include_once(DZSAP_BASE_PATH . "inc/php/database-functions.php");
include_once(DZSAP_BASE_PATH . "inc/php/DZSZoomSoundsHelper.php");
include_once(DZSAP_BASE_PATH . "inc/php/DzsapView.php");
include_once(DZSAP_BASE_PATH . "inc/php/DzsapShowcase.php");
include_once(DZSAP_BASE_PATH . "inc/php/DzsapAdmin.php");
include_once(DZSAP_BASE_PATH . "inc/php/AjaxHandler.php");
include_once(DZSAP_BASE_PATH . "inc/portal/showcase-functions.php");
include_once(DZSAP_BASE_PATH . "inc/php/analytics.php");
include_once(DZSAP_BASE_PATH . "inc/php/deprecated/legacy-sliders.php");
include_once(DZSAP_BASE_PATH . "inc/php/deprecated.php");
include_once(DZSAP_BASE_PATH . "inc/php/view-functions.php");
include_once(DZSAP_BASE_PATH . "inc/php/gutenberg-functions.php");
include_once(DZSAP_BASE_PATH . "inc/php/shortcodes.php");
include_once(DZSAP_BASE_PATH . "inc/php/view-parseItems/parse-items-functions.php");


/**
 * @property  $audioPlayerConfigs
 * Class DZSAudioPlayer
 */
class DZSAudioPlayer {


  public $admin_capability = 'manage_options';

  public $mainitems;
  public $mainitems_configs;
  public $mainoptions;
  public $sliders_index = 0;
  public $sliders__player_index = 0;
  public $cats_index = 0;
  public $dbs = array();
  public $currDb = '';
  public $vpconfigsstr = '';
  public $currSlider = '';
  public $current_user_id = 0;
  public $videoplayerconfig = '';

  public $alwaysembed = "on";

  public $general_assets = array();
  public $sample_data = array();


  public $options_item_meta = array();
  public $options_item_meta_sanitized = array(); // -- removing dzsap_meta_
  public $og_data = array();


  public $db_has_read_mainitems = false;

  public $sliderstructure = ''; // -- deprecated

  public $options_array_player = array();
  public $options_slider = array();
  public $options_slider_categories_lng = array();
  public $item_meta_categories_lng = array();


  public $isEnableMultisharer = false;
  public $debug = false;


  public $extraCssConsumedConfigurations = array();

  public $ajaxMessagesHtml = array();

  public $svg_star = '';

  public $classView;
  public $classAdmin;
  public $classShowcase;
  public $classWoo;
  public $ajax_functions = null;

  /** @var array used for playlist audio player configs */
  public $audioPlayerConfigs = array();

  function __construct() {




    $this->general_assets = DZSZoomSoundsHelper::get_assets();


    $this->svg_star = $this->general_assets['svg_star'];

    // -- clear database


    // -- classes init
    $this->classView = new DzsapView($this);
    $this->classShowcase = new DzsapShowcase($this);
    $this->ajax_functions = new AjaxHandler($this);

    if(is_admin()){

      $this->classAdmin = new DzsapAdmin($this);
    }

    add_action('init', array($this, 'handle_init'), 2);


    add_action('edited_' . DZSAP_TAXONOMY_NAME_SLIDERS, 'dzsap_sliders_save_taxonomy_custom_meta');

    add_action( 'plugins_loaded', array($this, 'handle_plugins_loaded' ));
  }


  function db_read_default_opts() {
    DZSZoomSoundsHelper::dbReadOptions($this);
  }

  function handle_plugins_loaded(){

    if(defined('ELEMENTOR_VERSION')){

      include_once(DZSAP_BASE_PATH.'inc/php/compatibilities/DZSAP_Elementor.php');
      $this->classElementor = new DZSAP_Elementor($this);
    }
  }

  /**
   * called at priority 2
   */
  function handle_init() {
    global $current_user;

    if ($current_user->ID) {
      $this->current_user_id = $current_user->ID;
    }



    if (class_exists('Cornerstone_Plugin')) {
      include_once(DZSAP_BASE_PATH . 'inc/php/cornerstone/cornerstone-functions.php');
      add_action('wp_enqueue_scripts', 'dzsap_cs_enqueue');
      add_action('cornerstone_register_elements', 'dzsap_cs_register_elements');
      add_filter('cornerstone_icon_map', 'dzsap_cs_icon_map');
      add_action('cornerstone_before_wp_editor', 'dzsap_cs_home_before');
      add_action('cornerstone_load_builder', 'dzsap_cs_home_before');
    }

    if (defined('DZSAP_DEBUG_LOCAL_SCRIPTS') && DZSAP_DEBUG_LOCAL_SCRIPTS === true) {
      wp_deregister_script('heartbeat');
    }

    $this->item_meta_categories_lng = array(
      'misc' => esc_html__("Miscellaneous", DZSAP_ID),
      'extra_html' => esc_html__("Extra HTML", DZSAP_ID),
    );

    $this->db_read_default_opts();

    $this->ajax_functions->ajaxCheckPostOptions();

    if (function_exists('WC')) {


      include(DZSAP_BASE_PATH . '/inc/php/DzsapWooCommerce.php');
      $this->classWoo = new DzsapWooCommerce($this);
      dzsap_woo_woocommerce_init();
    }


    require_once(DZSAP_BASE_PATH . "class_parts/options_array_player.php");


    if ($this->mainoptions['playlists_mode'] == 'legacy') {
      dzsap_deprecated_init_slider_structure();
    }


    include DZSAP_BASE_PATH . "class_parts/vpconfig.php";


    dzsap_gutenberg_init();


    // --- check posts
    if (isset($_GET['dzsap_shortcode_builder']) && $_GET['dzsap_shortcode_builder'] == 'on') {


      include_once(dirname(__FILE__) . '/tinymce/popupiframe.php');
      define('DONOTCACHEPAGE', true);
      define('DONOTMINIFY', true);

    }


    if (isset($_GET[DZSAP_ADMIN_SHORTCODE_PLAYER_GENERATOR_KEY]) && $_GET[DZSAP_ADMIN_SHORTCODE_PLAYER_GENERATOR_KEY] == 'on') {

      include_once(DZSAP_BASE_PATH . '/shortcodegenerator/generator_player.php');
      if (!defined("DONOTCACHEPAGE")) {
        define('DONOTCACHEPAGE', true);
      }
      if (!defined("DONOTMINIFY")) {
        define('DONOTMINIFY', true);
      }
    }





    // -- ajax actions
    dzsap_shortcode_init_listeners();

    if ($this->ajax_functions) {
      $this->ajax_functions->register_actions();
    }





    if ($this->mainoptions['try_to_hide_url'] == 'on') {

      if (!session_id()) {
        session_start();
      }
    }


    if (isset($_GET['dzsap_action']) && $_GET['dzsap_action']) {
      include DZSAP_BASE_PATH . 'class_parts/part-ajax-functions.php';
    }


    wp_enqueue_script('jquery');
    if (is_admin()) {

      $this->classAdmin->checkInitCalls();

    } else {
      if (isset($this->mainoptions['always_embed']) && $this->mainoptions['always_embed'] == 'on') {
        DZSZoomSoundsHelper::enqueueMainScrips();
        DZSZoomSoundsHelper::enqueueUltibox();
      }
    }


    dzsap_register_links();
    dzsap_gutenberg_register_scripts();



    if (function_exists('vc_map')) {
      include_once(DZSAP_BASE_PATH . 'inc/php/vc/part-vcintegration.php');
    }
  }

  // --- end handle_init END
  // -----------------------


  function handle_init_end() {


    if ($this->mainoptions['replace_powerpress_plugin'] == 'on') {
      add_shortcode('powerpress', 'dzsap_powerpress_shortcode_player');
    }


    if (!(get_option('dzsap_sample_data_installed'))) {
      $tax = DZSAP_TAXONOMY_NAME_SLIDERS;
      $reference_term = get_term_by('slug', 'gallery-1-copy', $tax);

      if (!$reference_term) {
        $file_cont = file_get_contents('sampledata/dzsap_export_gallery.txt', true);

        ZoomSoundsAjaxFunctions::import_slider($file_cont);
      }
      update_option('dzsap_sample_data_installed', 'on');

    }

  }


  /**
   * get main items in $dzsap->mainitems
   * used in various options for normal mode
   * used for slidersAdmin in legacy mode
   */
  function db_read_mainitems() {
    DZSZoomSoundsHelper::dbReadMainItems($this);
  }


  /**
   * @param $sourceid
   * @param $playerid
   * @param $margs
   * @return bool|mixed|string
   */
  function get_track_source($sourceid, &$playerid, &$margs) {
    return DZSZoomSoundsHelper::media_getTrackSource($sourceid, $playerid, $margs);
  }
  function parse_items($its, $pargs = array(), $argPlaylistOptions = array()) {
    return $this->classView->parse_items($its, $pargs, $argPlaylistOptions);
  }
  function front_scripts() {
    DZSZoomSoundsHelper::enqueueMainScrips();
  }
  /** deprecated 5.89
   * @param $pargs
   */
  function shortcode_player($pargs) {
    return $this->classView->shortcode_player($pargs);
  }
}



