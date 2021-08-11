<?php

include_once(DZSAP_BASE_PATH . 'admin/sliders_admin.php');
include_once(DZSAP_BASE_PATH . 'inc/php/ajax/ajax-get-track-source.php');

class AjaxHandler {

  /**
   * AjaxHandler constructor.
   * @param DZSAudioPlayer $dzsap
   */
  function __construct($dzsap) {

    $this->dzsap = $dzsap;

  }


  /**
   * called on init from main class
   */
  function register_actions() {

    $this->check_posts_init();

    add_action('wp_ajax_dzs_get_attachment_src', array($this, 'get_attachment_src'));
    add_action('wp_ajax_dzsap_ajax', array($this, 'saveLegacyitems'));
    add_action('wp_ajax_dzsap_save_configs', array($this, 'save_audioplayer_configs'));
    add_action('wp_ajax_dzsap_ajax_mo', array($this, 'save_mainOptions'));
    add_action('wp_ajax_dzsap_delete_pcm', array($this, 'delete_pcm'));
    add_action('wp_ajax_dzsap_parse_content_to_shortcode', array($this, 'parse_content_to_shortcode'));
    add_action('wp_ajax_dzsap_send_queue_from_sliders_admin', array($this, 'send_queue_from_sliders_admin'));


    // -- getdemo.php
    add_action('wp_ajax_dzsap_create_playlist', 'ZoomSoundsAjaxFunctions::create_playlist_if_it_does_not_exist');
    add_action('wp_ajax_dzsap_import_item_lib', array($this, 'import_item_lib'));

    add_action('wp_ajax_dzsap_front_submitcomment', array($this, 'front_submitcomment'));
    add_action('wp_ajax_dzsap_get_thumb_from_meta', array($this, 'get_thumb_from_meta'));
    add_action('wp_ajax_dzsap_submit_download', array($this, 'submit_download'));

    add_action('wp_ajax_dzsap_submit_views', array($this, 'submit_views'));
    add_action('wp_ajax_nopriv_dzsap_submit_views', array($this, 'submit_views'));

    add_action('wp_ajax_dzsap_submit_like', array($this, 'submit_like'));
    add_action('wp_ajax_dzsap_retract_like', array($this, 'retract_like'));
    add_action('wp_ajax_dzsap_submit_rate', array($this, 'submit_rate'));
    add_action('wp_ajax_dzsap_get_pcm', array($this, 'get_pcm'));
    add_action('wp_ajax_nopriv_dzsap_get_pcm', array($this, 'get_pcm'));
    add_action('wp_ajax_dzsap_add_to_wishlist', array($this, 'add_to_wishlist'));
    add_action('wp_ajax_nopriv_dzsap_add_to_wishlist', array($this, 'add_to_wishlist'));

    add_action('wp_ajax_nopriv_dzsap_front_submitcomment', array($this, 'front_submitcomment'));
    add_action('wp_ajax_nopriv_dzsap_submit_download', array($this, 'submit_download'));
    add_action('wp_ajax_nopriv_dzsap_submit_like', array($this, 'submit_like'));
    add_action('wp_ajax_nopriv_dzsap_retract_like', array($this, 'retract_like'));
    add_action('wp_ajax_nopriv_dzsap_submit_rate', array($this, 'submit_rate'));
    add_action('wp_ajax_dzsap_submit_pcm', array($this, 'submit_pcm'));
    add_action('wp_ajax_nopriv_dzsap_submit_pcm', array($this, 'submit_pcm'));
    add_action('wp_ajax_dzsap_shoutcast_get_streamtitle', array($this, 'shoutcast_get_streamtitle'));
    add_action('wp_ajax_nopriv_dzsap_shoutcast_get_streamtitle', array($this, 'shoutcast_get_streamtitle'));


    add_action('wp_ajax_dzsap_admin_report_connect_zoomsounds', array($this, 'admin_report_connect_zoomsounds'));
    add_action('wp_ajax_dzsap_delete_notice', array($this, 'delete_notice'));
    add_action('wp_ajax_dzsap_activate', array($this, 'activate_license'));
    add_action('wp_ajax_dzsap_hide_intro_nag', array($this, 'hide_intro_nag'));
    add_action('wp_ajax_dzsap_deactivate', array($this, 'deactivate_license'));

    add_action('wp_ajax_dzsap_admin_nag_disable_all', array($this, 'admin_nag_disable_all'));
    add_action('wp_ajax_ajax_dzsap_insert_sample_tracks', array($this, 'submit_sample_tracks'));
    add_action('wp_ajax_nopriv_ajax_dzsap_insert_sample_tracks', array($this, 'submit_sample_tracks'));

    add_action('wp_ajax_ajax_dzsap_remove_sample_tracks', array($this, 'remove_sample_tracks'));
    add_action('wp_ajax_nopriv_ajax_dzsap_remove_sample_tracks', array($this, 'remove_sample_tracks'));
    add_action('wp_ajax_dzsap_import_folder', 'ZoomSoundsAjaxFunctions::ajax_import_folder');
  }

  function admin_nag_disable_all() {

    $this->dzsap->mainoptions['admin_nag_disable_all'] = 'on';
    update_option(DZSAP_DBNAME_MAINOPTIONS, $this->dzsap->mainoptions);
    die();
  }

  function get_metaViews($postId) {

    $aux = get_post_meta($postId, DZSAP_DB_VIEWS_META_NAME, true);
    if ($aux == '') {
      $aux = 0;
    }

    return $aux;
  }

  function admin_report_connect_zoomsounds() {
    if(function_exists('wp_remote_get')){
      $request = wp_remote_get(DZSAP_ADMIN_UPDATE_LATEST_VERSION_URI);
      $cache = wp_remote_retrieve_body($request);
      echo $cache;
    }
    die();
  }


  function check_posts_init() {
    $this->check_init_args();
  }


  function check_init_args() {
    global $dzsap;


    if (isset($_POST['action'])) {
      if ($_POST['action'] == 'dzsap_send_total_time_for_track') {


        if (isset($_POST['id_track'])) {
          $po_id = $_POST['id_track'];
          if (update_post_meta($po_id, DZSAP_DBNAME_CACHE_TOTAL_TIME, $_POST['postdata'])) {
            echo json_encode(array('ajax_status' => 'success'));
          } else {
            echo json_encode(array('ajax_status' => 'error', 'ajax_message' => 'Failed - maybe it already existed with the same values'));
          }
        }
        die();
      }
    }


    if (isset($_GET['action'])) {


      if ($_GET['action'] == 'ajax_dzsap_submit_contor_60_secs') {
        dzsap_init_arg_submit_contor_60_secs();
        die();
      }


      if ($_GET['action'] == DZSAP_GET_KEY_DOWNLOAD) {

        include_once DZSAP_BASE_PATH . 'inc/php/ajax/ajax-download.php';
        dzsap_ajax_downloadTrack();
        die();
      }
    }


    if (isset($_GET['dzsap_action']) && $_GET['dzsap_action'] == 'load_charts_html') {
      include(DZSAP_BASE_PATH . "class_parts/ajax_load_charts_html.php");
    }


    if (isset($_REQUEST['dzsap_action'])) {

      if ($_REQUEST['dzsap_action'] == 'dzsap_import_vp_config') {
        $this->import_vpconfig_by_name($_POST['name']);

        die();

      }
      if ($_GET['dzsap_action'] == DZSAP_VIEW_GET_TRACK_SOURCE) {
        dzsap_ajax_getTrackSource();
      }

      if ($_GET['dzsap_action'] == DZSAP_VIEW_NONCE_IDENTIFIER) {
        dzsap_ajax_getTrackSource_generateNonce();
      }


      if ($_REQUEST['dzsap_action'] == 'dzsap_import_playlist') {
        $name = $_REQUEST['name'];


        $rel_path = DZSAP_BASE_PATH . 'sampledata/dzsap-playlist--' . $name . '.txt';
        $file_cont = file_get_contents($rel_path, true);

        $sw_import = ZoomSoundsAjaxFunctions::import_slider($file_cont);

        echo json_encode($sw_import);
        die();

      }
    }
    if (isset($_POST['action'])) {


      if ($_POST['action'] == DZSAP_AJAX_DELETE_CACHE_WAVEFORM_DATA) {


        $nonce = $_REQUEST['nonce'];
        if (!wp_verify_nonce($nonce, 'dzsap_delete_waveforms_nonce')) {
          // -- This nonce is not valid.
          die('Security check');
        }


        global $wpdb;


        $result = $wpdb->query(
          "DELETE FROM $wpdb->options WHERE `option_name` LIKE 'dzsap_pcm_data%' "
        );


      }
      if ($_POST['action'] == DZSAP_AJAX_DELETE_CACHE_TOTAL_TIMES) {


        $nonce = $_REQUEST['nonce'];
        if (!wp_verify_nonce($nonce, DZSAP_AJAX_DELETE_CACHE_TOTAL_TIMES . '_nonce')) {
          die('Security check');
        }


        global $wpdb;


        $result = $wpdb->query(
          "DELETE FROM $wpdb->postmeta WHERE meta_key = '" . DZSAP_DBNAME_CACHE_TOTAL_TIME . "' "
        );


        if ($result) {

          array_push($dzsap->ajaxMessagesHtml, '<div class="warning">' . esc_html__('deleted cache total times', DZSAP_ID) . '</div>');
        } else {

          array_push($dzsap->ajaxMessagesHtml, '<div class="error">' . esc_html__('deleted total times error', DZSAP_ID) . '</div>');
        }
      }
      if ($_POST['action'] == 'dzsap_duplicate_dzsap_configs') {

        if (isset($_POST['slidernr'])) {
          if (isset($_GET['page']) && $_GET['page'] == DZSAP_ADMIN_PAGENAME_LEGACY_SLIDERS_ADMIN_VPCONFIGS) {
            $aux = ($dzsap->mainitems_configs[$_POST['slidernr']]);
            array_push($dzsap->mainitems_configs, $aux);
            $dzsap->mainitems_configs = array_values($dzsap->mainitems_configs);
            $dzsap->currSlider = count($dzsap->mainitems_configs) - 1;
            update_option(DZSAP_DBNAME_AUDIO_PLAYERS_CONFIGS, $dzsap->mainitems_configs);

            wp_redirect(admin_url('admin.php?page=dzsap_configs&currslider=' . $dzsap->currSlider));
            exit;
          }
        }
      }
    }

    if (isset($_POST['action'])) {
      if ($_POST['action'] == 'dzsap_duplicate_dzsap_slider') {

        if (isset($_POST['slidernr'])) {
          if (isset($_GET['page']) && $_GET['page'] == DZSAP_ADMIN_PAGENAME_LEGACY_SLIDERS_ADMIN_SLIDERS) {
            $aux = ($dzsap->mainitems[$_POST['slidernr']]);
            array_push($dzsap->mainitems, $aux);
            $dzsap->mainitems = array_values($dzsap->mainitems);
            $dzsap->currSlider = count($dzsap->mainitems) - 1;
            update_option(DZSAP_DBNAME_MAINITEMS, $dzsap->mainitems);

            wp_redirect(admin_url('admin.php?page=dzsap_menu&currslider=' . $dzsap->currSlider));
            exit;
          }
        }
      }
    }


  }


  function import_vpconfig_by_name($name) {
    if ($name) {


      $rel_path = 'sampledata/dzsap-slider-' . $name . '.txt';
      $this->import_vpconfig_serialized(file_get_contents(DZSAP_BASE_URL . $rel_path));
    }
  }

  function import_vpconfig_serialized($file_data) {


    $dzsap = $this->dzsap;
    try {

      $auxslider = unserialize($file_data);

      if ((is_object($auxslider) || is_array($auxslider)) && $auxslider['settings']) {

        $dzsap->mainitems_configs = get_option(DZSAP_DBNAME_AUDIO_PLAYERS_CONFIGS);
        $dzsap->mainitems_configs[] = $auxslider;

        update_option(DZSAP_DBNAME_AUDIO_PLAYERS_CONFIGS, $dzsap->mainitems_configs);
      }
    } catch (Exception $e) {
    }
  }


  function import_demo_create_term_if_it_does_not_exist($pargs = array()) {

    $dzsap = $this->dzsap;

    $margs = array(
      'term_name' => '',
      'slug' => '',
      'taxonomy' => '',
      'description' => '',
      'parent' => '',
    );

    $margs = array_merge($margs, $pargs);

    $term = get_term_by('slug', $margs['slug'], $margs['taxonomy']);


    if ($term) {

    } else {


      $args = array(
        'description' => $margs['description'],
        'slug' => $margs['slug'],


      );

      if ($margs['parent']) {
        $args['parent'] = $margs['parent'];
      }

      $term = wp_insert_term($margs['term_name'], $margs['taxonomy'], $args);

    }
    return $term;

  }


  /**
   * called in handle_init
   */
  public function ajaxCheckPostOptions() {


    $dzsap = $this->dzsap;
    if (isset($_POST['dzsap_exportdb'])) {
      // -- legacy / *deprecated


      $currDb = '';
      if (isset($_POST['currdb']) && $_POST['currdb'] != '') {
        $dzsap->currDb = $_POST['currdb'];
        $currDb = $dzsap->currDb;
      }

      if ($currDb != 'main' && $currDb != '') {
        $dbname_mainitems = DZSAP_DBNAME_MAINITEMS . '-' . $currDb;
        $dzsap->mainitems = get_option($dbname_mainitems);
      }

      header('Content-Type: text/plain');
      header('Content-Disposition: attachment; filename="' . "dzsap_backup.txt" . '"');
      echo serialize($dzsap->mainitems);
      die();
    }

    if (isset($_POST['dzsap_exportslider'])) {
      // -- legacy / *deprecated


      $currDb = '';
      if (isset($_POST['currdb']) && $_POST['currdb'] != '') {
        $dzsap->currDb = $_POST['currdb'];
        $currDb = $dzsap->currDb;
      }


      $dzsap->db_read_mainitems();

      if ($currDb != 'main' && $currDb != '') {
        // -- *deprecated
        $dbname_mainitems = DZSAP_DBNAME_MAINITEMS . '-' . $currDb;
        $dzsap->mainitems = get_option($dbname_mainitems);
      }

      header('Content-Type: text/plain');
      header('Content-Disposition: attachment; filename="' . "dzsap-slider-" . $_POST['slidername'] . ".txt" . '"');

      echo serialize($dzsap->mainitems[$_POST['slidernr']]);
      die();
    }

    if (isset($_POST['dzsap_exportslider_config'])) {


      $currDb = '';


      $dzsap->db_read_mainitems();


      header('Content-Type: text/plain');
      header('Content-Disposition: attachment; filename="' . "dzsap-slider-" . $_POST['slidername'] . ".txt" . '"');

      echo serialize($dzsap->mainitems_configs[$_POST['slidernr']]);
      die();
    }


    if (isset($_POST['dzsap_importdb'])) {
      $file_data = file_get_contents($_FILES['dzsap_importdbupload']['tmp_name']);
      $aux = unserialize($file_data);

      if (is_array($aux)) {

        $dzsap->mainitems = array_merge($dzsap->mainitems, $aux);
        update_option(DZSAP_DBNAME_MAINITEMS, $dzsap->mainitems);
      }
    }

    if (isset($_POST['dzsap_importslider'])) {
      $file_data = file_get_contents($_FILES['importsliderupload']['tmp_name']);
      $auxslider = unserialize($file_data);
      $dzsap->mainitems = get_option(DZSAP_DBNAME_MAINITEMS);
      $dzsap->mainitems[] = $auxslider;

      update_option(DZSAP_DBNAME_MAINITEMS, $dzsap->mainitems);
    }

    if (isset($_POST['dzsap_import_config'])) {
      $file_data = file_get_contents($_FILES['importsliderupload']['tmp_name']);
      $dzsap->ajax_functions->import_vpconfig_serialized($file_data);
    }

    if (isset($_POST['dzsap_saveoptions'])) {
      $dzsap->mainoptions['embed_prettyphoto'] = $_POST['embed_prettyphoto'];
      $dzsap->mainoptions['disable_prettyphoto'] = $_POST['disable_prettyphoto'];


      update_option(DZSAP_DBNAME_OPTIONS, $dzsap->mainoptions);
    }


    if (isset($_POST['deleteslider'])) {
      // -- *deprecated

      if (isset($_GET['page']) && $_GET['page'] == DZSAP_ADMIN_PAGENAME_LEGACY_SLIDERS_ADMIN_SLIDERS) {
        unset($dzsap->mainitems[$_POST['deleteslider']]);
        $dzsap->mainitems = array_values($dzsap->mainitems);
        $dzsap->currSlider = 0;
        update_option(DZSAP_DBNAME_MAINITEMS, $dzsap->mainitems);
      }


    }

    if (isset($_POST['deleteslider'])) {
      // -- configs
      if (isset($_GET['page']) && $_GET['page'] == DZSAP_ADMIN_PAGENAME_LEGACY_SLIDERS_ADMIN_VPCONFIGS) {
        unset($dzsap->mainitems_configs[$_POST['deleteslider']]);
        $dzsap->mainitems_configs = array_values($dzsap->mainitems_configs);
        $dzsap->currSlider = 0;

        update_option(DZSAP_DBNAME_AUDIO_PLAYERS_CONFIGS, $dzsap->mainitems_configs);
      }
    }
  }


  function import_demo_create_attachment($img_url, $port_id, $img_path) {


    $dzsap = $this->dzsap;
    $attachment = array(
      'guid' => $img_url,
      'post_mime_type' => 'image/jpeg',
      'post_title' => preg_replace('/\.[^.]+$/', '', basename($img_url)),
      'post_content' => '',
      'post_status' => 'inherit'
    );

    // -- Insert the attachment.
    $attach_id = wp_insert_attachment($attachment, $img_url, $port_id);


    require_once(ABSPATH . 'wp-admin/includes/image.php');

    // Generate the metadata for the attachment, and update the database record.
    $attach_data = wp_generate_attachment_metadata($attach_id, $img_path);
    wp_update_attachment_metadata($attach_id, $attach_data);

    return $attach_id;
  }


  function import_demo_create_portfolio_item($pargs = array()) {


    $dzsap = $this->dzsap;
    $margs = array(

      'post_title' => '',
      'post_content' => '',
      'post_status' => '',
      'post_type' => 'dzsvcs_port_items',
    );

    $margs = array_merge($margs, $pargs);


    $args = array(
      'post_type' => $margs['post_type'],
      'post_title' => $margs['post_title'],
      'post_content' => $margs['post_content'],
      'post_status' => $margs['post_status'],


      /*other default parameters you want to set*/
    );


    $post_id = wp_insert_post($args);

    return $post_id;


  }

  /**
   * will import anything with dzsap_meta_ in front as meta
   * @param array $pargs
   * @return int|WP_Error
   */
  function import_demo_insert_post_complete($pargs = array()) {

    $dzsap = $this->dzsap;

    $margs = array(

      'post_title' => '',
      'call_from' => 'default',

      'post_content' => '',
      'post_type' => 'dzsap_items',
      'post_status' => 'publish',
      'post_name' => '',
      'img_url' => '',
      'img_path' => '',
      'term' => '',
      'taxonomy' => '',
      'attach_id' => '',
      'dzsvp_thumb' => '',
      'dzsvp_item_type' => 'detect',
      'dzsvp_featured_media' => '',


    );

    $margs = array_merge($margs, $pargs);


    if ($margs['post_name']) {


      $ind = 1;
      $breaker = 100;


      $the_slug = $margs['post_name'];
      $original_slug = $margs['post_name'];
      $args = array(
        'name' => $the_slug,
        'post_type' => $margs['post_type'],
        'post_status' => 'publish',
        'numberposts' => 1
      );
      $my_posts = get_posts($args);
      if ($my_posts) {


        while (1) {

          $the_slug = $margs['post_name'];
          $original_slug = $margs['post_name'];
          $args = array(
            'name' => $the_slug,
            'post_type' => $margs['post_type'],
            'post_status' => 'publish',
            'numberposts' => 1
          );
          $my_posts = get_posts($args);
          if ($my_posts) {

            $ind++;
            $margs['post_name'] = $original_slug . '-' . $ind;
          } else {
            break;
          }

          $breaker--;

          if ($breaker < 0) {
            break;
          }
        }

        $ind++;

        $margs['post_name'] = $original_slug . '-' . $ind;
      } else {

      }


    }

    $args = array(
      'post_type' => $margs['post_type'],
      'post_title' => $margs['post_title'],

      'post_content' => $margs['post_content'],
      'post_status' => $margs['post_status'],


      /* -- other default parameters you want to set*/
    );


    if ($margs['post_name']) {
      $args['post_name'] = $margs['post_name'];
    }


    if ($margs['term']) {

      $term = $margs['term'];
    }
    $taxonomy = $margs['taxonomy'];

    if ($margs['img_url']) {

      $img_url = $margs['img_url'];
    }
    $img_path = $margs['img_path'];


    $port_id = $this->import_demo_create_portfolio_item($args);

    if ($margs['term']) {
      $term = $margs['term'];


      if (is_object($margs['term']) && isset($margs['term']->term_id)) {
        $term = $margs['term']->term_id;
      } else {

        if (is_array($margs['term']) && isset($margs['term']['term_id'])) {
          $term = $margs['term']['term_id'];
        }
      }
      wp_set_post_terms($port_id, $term, $taxonomy);
    }


    foreach ($margs as $lab => $val) {
      if (strpos($lab, 'dzsap_') === 0) {

        update_post_meta($port_id, $lab, $val);
      }
    }


    if ($margs['attach_id']) {

      set_post_thumbnail($port_id, $margs['attach_id']);
    } else {

      if ($margs['img_url']) {
        $attach_id = $this->import_demo_create_attachment($img_url, $port_id, $img_path);
        set_post_thumbnail($port_id, $attach_id);

        $this->import_demo_last_attach_id = $attach_id;
      }

    }


    return $port_id;


  }


  function send_queue_from_sliders_admin() {

    global $dzsap;


    $response = array(
      'report' => 'success',
      'items' => array(),
    );

    $queue_calls = json_decode(stripslashes($_POST['postdata']), true);


    foreach ($queue_calls as $queueCall) {

      if ($queueCall['type'] == 'set_meta_order') {
        foreach ($queueCall['items'] as $it) {

          update_post_meta($it['id'], 'dzsap_meta_order_' . $queueCall['term_id'], $it['order']);
        }
      }

      if ($queueCall['type'] == 'set_meta') {

        if ($queueCall['lab'] == 'the_post_title' || $queueCall['lab'] == 'post_content') {


          $aferent_lab = $queueCall['lab'];

          if ($queueCall['lab'] == 'the_post_title') {
            $aferent_lab = 'post_title';
          }
          if ($queueCall['lab'] == 'post_content') {
            $aferent_lab = 'post_content';
          }

          $my_post = array(
            'ID' => $queueCall['item_id'],
            $aferent_lab => $queueCall['val'],

          );


// Update the post into the database
          wp_update_post($my_post);
        } else {

          update_post_meta($queueCall['item_id'], $queueCall['lab'], $queueCall['val']);
        }

      }
      if ($queueCall['type'] == 'delete_item') {


        $post_id = $queueCall['id'];


        $term_list = wp_get_post_terms($post_id, DZSAP_TAXONOMY_NAME_SLIDERS, array("fields" => "all"));


        $response['report_type'] = 'delete_item';
        $response['report_message'] = esc_html__("Item deleted", 'dzsap');


        if (is_array($term_list) && count($term_list) == 1) {

          wp_delete_post($post_id);
        } else {
          wp_remove_object_terms($post_id, $queueCall['term_slug'], DZSAP_TAXONOMY_NAME_SLIDERS);
        }

      }
      if ($queueCall['type'] == 'create_item') {


        $taxonomy = DZSAP_TAXONOMY_NAME_SLIDERS;


        $current_user = wp_get_current_user();
        $new_post_author_id = $current_user->ID;


        $createItemArgs = array(
          'post_title' => esc_html__("Insert Name", DZSAP_ID),
          'post_content' => 'content here',
          'post_status' => 'publish',
          'post_author' => $new_post_author_id,
          'post_type' => 'dzsap_items',
        );


        if (isset($queueCall['term_slug']) && $queueCall['term_slug']) {

          $title = substr($queueCall['term_slug'], 0, 4);


          if (isset($queueCall['dzsap_meta_order_' . $queueCall['term_id']])) {
            $title .= $queueCall['dzsap_meta_order_' . $queueCall['term_id']];
          }

          $createItemArgs['post_title'] = $title;

        }
        if (isset($queueCall['create_source']) && $queueCall['create_source']) {
          $createItemArgs['dzsap_create_source'] = $queueCall['create_source'];
        }


        if ($dzsap->mainoptions['try_to_get_id3_thumb_in_frontend'] == 'on') {

          $title = '';
          $createItemArgs['post_title'] = $title;
        }


        // -- search for default


        $the_slug = 'default_zoomsounds_item_settings';
        $the_slug_term = '';

        // -- if we find it we get the settings
        if (isset($queueCall['term_slug']) && $queueCall['term_slug']) {
          $the_slug_term .= $the_slug . '_' . $queueCall['term_slug'];
        }


        $args4 = array(
          'name' => $the_slug,
          'post_type' => 'dzsap_items',
          'post_status' => 'any',
          'numberposts' => 1
        );
        $my_posts = get_posts($args4);
        if ($my_posts) {
          $createItemArgs = array_merge($createItemArgs, DZSZoomSoundsHelper::sanitize_to_gallery_item($my_posts[0]));

        }


        if ($the_slug_term) {

          $args4 = array(
            'name' => $the_slug_term,
            'post_type' => 'dzsap_items',
            'post_status' => 'any',
            'numberposts' => 1
          );
          $my_posts = get_posts($args4);
          if ($my_posts) {
            $createItemArgs = array_merge($createItemArgs, DZSZoomSoundsHelper::sanitize_to_gallery_item($my_posts[0]));

          }

        }

        // -- end search for default


        if (isset($queueCall['post_title']) && $queueCall['post_title']) {
          $createItemArgs['post_title'] = $queueCall['post_title'];

        }
        $createItemArgs['post_status'] = 'publish';


        $createItemArgs['call_from'] = 'send queue from sliders admin';
        $new_created_item = $this->import_demo_insert_post_complete($createItemArgs);

        if (isset($queueCall['term_slug']) && $queueCall['term_slug']) {
          wp_set_post_terms($new_created_item, dzs_sanitize_for_post_terms($queueCall['term_slug']), $taxonomy);

        }


        foreach ($queueCall as $lab => $val) {
          if (strpos($lab, 'dzsap_meta') === 0) {
            update_post_meta($new_created_item, $lab, $val);
          }
        }


        array_push($response['items'], array(
          'type' => 'create_item',
          'str' => dzsap_sliders_admin_generate_item(get_post($new_created_item)),
        ));
      }
      // -- end create_item


      if ($queueCall['type'] == 'duplicate_item') {


        $duplicatedItemId = ($queueCall['id']);

        $duplicatedItem = $this->duplicate_post($duplicatedItemId);


        array_push($response['items'], array(
          'type' => 'create_item',
          'original_request' => 'duplicate_item',
          'original_post_id' => $duplicatedItemId,
          'str' => dzsap_sliders_admin_generate_item(get_post($duplicatedItem)),
        ));
      }
    }

    echo json_encode($response);
    die();
  }


  public function duplicate_post($reference_po_id, $pargs = array()) {


    $margs = array(
      'new_term_slug' => '',
      'call_from' => 'default',
      'new_tax' => DZSAP_TAXONOMY_NAME_SLIDERS,
    );

    $margs = array_merge($margs, $pargs);

    $reference_po = get_post($reference_po_id);


    $current_user = wp_get_current_user();
    $new_post_author_id = $current_user->ID;

    $args = array(
      'post_title' => $reference_po->post_title,
      'post_content' => $reference_po->post_content,
      'post_status' => 'publish',
      'post_author' => $new_post_author_id,
      'post_type' => $reference_po->post_type,
    );


    $duplicatedItemId = wp_insert_post($args);


    update_post_meta($duplicatedItemId, 'duplicated_post_reference_id', $reference_po_id);
    /*
		 * get all current post terms ad set them to the new post draft
		 */
    $taxonomies = get_object_taxonomies($reference_po->post_type); // returns array of taxonomy names for post type, ex array("category", "post_tag");
    foreach ($taxonomies as $taxonomy) {
      if ($margs['new_term_slug']) {
        if ($taxonomy == DZSAP_TAXONOMY_NAME_SLIDERS) {
          continue;
        }
      }
      $post_terms = wp_get_object_terms($reference_po_id, $taxonomy, array('fields' => 'slugs'));
      wp_set_object_terms($duplicatedItemId, $post_terms, $taxonomy, false);
    }


    // -- for duplicate term
    if ($margs['new_term_slug']) {

      wp_set_object_terms($duplicatedItemId, $margs['new_term_slug'], $margs['new_tax'], false);
    } else {

    }


    /*
		 * duplicate all post meta just in two SQL queries
		 */
    global $wpdb;
    $sql_query_sel = array();
    $post_meta_infos = $wpdb->get_results("SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id=$reference_po_id");
    if (count($post_meta_infos) != 0) {
      $sql_query = "INSERT INTO $wpdb->postmeta (post_id, meta_key, meta_value) ";
      foreach ($post_meta_infos as $meta_info) {
        $meta_key = $meta_info->meta_key;
        if ($meta_key == '_wp_old_slug' || $meta_key == DZSAP_DB_LIKES_META_NAME || $meta_key == DZSAP_DB_VIEWS_META_NAME) {
          continue;
        }
        $meta_value = addslashes($meta_info->meta_value);
        $sql_query_sel[] = $wpdb->prepare("SELECT %d, %s, %s", $duplicatedItemId, $meta_key, $meta_value);

      }
      $sql_query .= implode(" UNION ALL ", $sql_query_sel);
      $wpdb->query($sql_query);
    }

    return $duplicatedItemId;
  }


  function submit_download() {
    global $dzsap;

    $aux_likes = 0;
    $playerid = '';

    if (isset($_POST['playerid'])) {
      $playerid = $_POST['playerid'];
      $playerid = str_replace('ap', '', $playerid);
    }

    if (is_numeric($playerid) && get_post_meta($playerid, DZSAP_DB_DOWNLOADS_META_NAME, true) != '') {
      $aux_likes = intval(get_post_meta($playerid, DZSAP_DB_DOWNLOADS_META_NAME, true));
    }

    if (isset($_COOKIE['downloadsubmitted-' . $playerid])) {

    } else {

    }

    $aux_likes = $aux_likes + 1;


    dzsap_mysql_insert_activity(array(
      'id_video' => $playerid,
      'type' => 'download',
    ));


    if (is_numeric($playerid)) {

      update_post_meta($playerid, DZSAP_DB_DOWNLOADS_META_NAME, $aux_likes);
    }


    setcookie("downloadsubmitted-" . $playerid, '1', time() + (intval($dzsap->mainoptions['play_remember_time']) * 60), COOKIEPATH);

    echo 'success';
    die();
  }


  function submit_views() {
    // -- here we record the views
    global $dzsap;

    $intViews = 0;
    $playerid = '';

    if (isset($_POST['playerid'])) {
      $playerid = $_POST['playerid'];
      $playerid = str_replace('ap', '', $playerid);
    }

    if (get_post_meta($playerid, DZSAP_DB_VIEWS_META_NAME, true) != '') {
      $intViews = intval(get_post_meta($playerid, DZSAP_DB_VIEWS_META_NAME, true));
    }


    dzsap_analytics_submit_into_table(array(
      'type' => 'view',
    ));


    if (isset($_COOKIE['viewsubmitted-' . $playerid])) {

    } else {
      $intViews = $intViews + 1;


      dzsap_mysql_insert_activity(array(
        'id_video' => $playerid,
        'type' => 'view',
      ));

    }


    update_post_meta($playerid, DZSAP_DB_VIEWS_META_NAME, $intViews);


    echo json_encode(array(
      'response_type' => 'success',
      'number' => $intViews,
    ));

    setcookie("viewsubmitted-" . $playerid, '1', time() + (intval($dzsap->mainoptions['play_remember_time']) * 60), COOKIEPATH);

    die();
  }


  function submit_rate() {


    $rate_index = 0;
    $rate_nr = 0;
    $playerid = '';

    if (isset($_POST['playerid'])) {
      $playerid = $_POST['playerid'];
      $playerid = str_replace('ap', '', $playerid);
    }

    if (get_post_meta($playerid, '_dzsap_rate_nr', true) != '') {
      $rate_nr = intval(get_post_meta($playerid, '_dzsap_rate_nr', true));
    }
    if (get_post_meta($playerid, '_dzsap_rate_index', true) != '') {
      $rate_index = intval(get_post_meta($playerid, '_dzsap_rate_index', true));
    }


    if (!isset($_COOKIE['dzsap_ratesubmitted-' . $playerid])) {
      $rate_nr++;
    }

    if ($rate_nr <= 0) {
      $rate_nr = 1;
    }


    $rate_index = ($rate_index * ($rate_nr - 1) + intval($_POST['postdata'])) / ($rate_nr);


    setcookie("dzsap_ratesubmitted-" . $playerid, $_POST['postdata'], time() + 36000, COOKIEPATH);


    update_post_meta($playerid, '_dzsap_rate_index', $rate_index);
    update_post_meta($playerid, '_dzsap_rate_nr', $rate_nr);

    echo json_encode(array(
      'index' => $rate_index,
      'number' => $rate_nr,
    ));
    die();
  }


  function shoutcast_get_streamtitle() {


    echo ZoomSoundsAjaxFunctions::shoutcast_get_now_playing($_GET['source']);

    die();
  }


  function submit_pcm() {


    $dbPcmDataKey = '';
    $pcmData = '';
    $forceUrlTrackId = '';


    $responseArray = array(
      'response_type' => 'success',
    );


    if (isset($_POST['postdata'])) {
      $pcmData = $_POST['postdata'];
    }
    if (isset($_POST['forceUrlTrackId'])) {
      $forceUrlTrackId = $_POST['forceUrlTrackId'];
    }

    if ($_POST['playerid']) {
      $dbPcmDataKey = 'dzsap_pcm_data_' . (DZSZoomSoundsHelper::sanitize_toKey($_POST['playerid']));
    }


    if ((isset($_POST['call_from']) && $_POST['call_from'] = 'manual_wave_overwrite') || strpos($pcmData, ',') !== false) {


      $isValidPcm = false;

      try {
        $pcmDataArr = json_decode($pcmData, true);

        foreach ($pcmDataArr as $key => $pcmDataArrItem) {
          if ($pcmDataArrItem !== null && $pcmDataArrItem !== 0) {
            $isValidPcm = true;
          }
        }
      } catch (Exception $exception) {

      }

      if (!$isValidPcm) {
        $responseArray['response_type'] = 'error';
        $responseArray['error_message'] = esc_html__('pcm data not valid', DZSAP_ID);
        echo json_encode($responseArray);
        die();
      }

      $pcmData = stripslashes($pcmData);
      update_option($dbPcmDataKey, $pcmData);

      if (isset($_POST['source'])) {

        $pcmSourceUrlId = DZSZoomSoundsHelper::sanitize_toKey($_POST['source']);

        $dbPcmDataKey = 'dzsap_pcm_data_' . $pcmSourceUrlId;
        $pcmData = stripslashes($pcmData);
        update_option($dbPcmDataKey, $pcmData);

        $arr_pcm_to_id_links = array();
        if (get_option(DZSAP_DBNAME_PCM_LINKS)) {
          $arr_pcm_to_id_links = get_option('dzsap_pcm_to_id_links');
        }

        if ($forceUrlTrackId) {
          $pcmSourceUrlId = $forceUrlTrackId;
        }

        if ($pcmSourceUrlId) {

          $arr_pcm_to_id_links[$_POST['playerid']] = $pcmSourceUrlId;
          update_option(DZSAP_DBNAME_PCM_LINKS, $arr_pcm_to_id_links);
        }


        // -- if we have source then just link to id
      }


      echo json_encode($responseArray);
    }

    die();
  }


  function submit_sample_tracks() {
    global $dzsap;


    $dzsap->sample_data = array(
      'media' => array(),
    );


    include(DZSAP_BASE_PATH . "class_parts/sample_submit_tracks.php");


    echo 'success';

    die();
  }


  function remove_sample_tracks() {
    global $dzsap;


    foreach ($dzsap->sample_data['media'] as $pid) {
      wp_delete_post($pid);
    };

    $dzsap->sample_data = false;
    update_option(DZSAP_DBNAME_SAMPLEDATA, $dzsap->sample_data);


    echo 'success';

    die();
  }


  function submit_like() {


    global $dzsap;
    global $current_user;

    $aux_likes = 0;
    $playerid = '';


    $user_id = -1;
    if ($current_user->ID) {
      $user_id = $current_user->ID;
    }


    if (isset($_POST['playerid'])) {
      $playerid = $_POST['playerid'];
      $playerid = str_replace('ap', '', $playerid);
    }


    if (get_post_meta($playerid, DZSAP_DB_LIKES_META_NAME, true) != '') {
      $aux_likes = intval(get_post_meta($_POST['playerid'], DZSAP_DB_LIKES_META_NAME, true));
    }


    $aux_likes = $aux_likes + 1;

    update_post_meta($playerid, DZSAP_DB_LIKES_META_NAME, $aux_likes);


    setcookie("dzsap_likesubmitted-" . $playerid, '1', time() + 36000, COOKIEPATH);


    if ($user_id > 0) {
      $aux_likes_arr = array();
      $aux_likes_arr_test = get_user_meta($user_id, DZSAP_DB_LIKES_META_NAME);
      if (is_array($aux_likes_arr_test)) {
        $aux_likes_arr = $aux_likes_arr_test;
      };

      if (!in_array($playerid, $aux_likes_arr)) {
        array_push($aux_likes_arr, $playerid);

        update_user_meta($user_id, DZSAP_DB_LIKES_META_NAME, $aux_likes_arr);
      }
    };

    dzsap_mysql_insert_activity(array(
      'id_video' => $playerid,
      'type' => 'like',
    ));

    echo json_encode(array(
      'status' => 'success',
      'nr_likes' => $aux_likes,
    ));
    die();
  }


  function retract_like() {
    global $dzsap;


    $aux_likes = 1;
    $playerid = '';

    if (isset($_POST['playerid'])) {
      $playerid = $_POST['playerid'];
      $playerid = str_replace('ap', '', $playerid);
    }


    if (get_post_meta($playerid, DZSAP_DB_LIKES_META_NAME, true) != '') {
      $aux_likes = intval(get_post_meta($_POST['playerid'], DZSAP_DB_LIKES_META_NAME, true));
    }

    $aux_likes = $aux_likes - 1;

    update_post_meta($playerid, DZSAP_DB_LIKES_META_NAME, $aux_likes);

    setcookie("dzsap_likesubmitted-" . $playerid, '', time() - 36000, COOKIEPATH);


    $user_id = 0;
    $current_user = wp_get_current_user();

    if ($current_user) {
      if ($current_user->ID) {
        $user_id = $current_user->ID;
      }
    }


    dzsap_mysql_delete_activity(array(
      'id_video' => $playerid,
      'id_user' => $user_id,
      'type' => 'like',
    ));

    echo json_encode(array(
      'status' => 'success',
      'nr_likes' => $aux_likes,
    ));
    die();
  }


  function import_item_lib() {
    global $dzsap;


    $cont = '';

    $dzsap->db_read_mainitems();

    if ($_POST['demo'] == 'sample_vimeo_channel33') {

    } else {

      $url = 'https://zoomthe.me/updater_dzsap/getdemo.php?demo=' . $_POST['demo'] . '&purchase_code=' . $dzsap->mainoptions['dzsap_purchase_code'] . '&site_url=' . urlencode(site_url());
      $cont = file_get_contents($url);
    }


    $resp = json_decode($cont, true);


    if ($resp['response_type'] == 'success') {


      foreach ($resp['items'] as $lab => $it) {



        if ($it['type'] == 'slider_import') {

          $sw_import = true;
          $slider = unserialize($it['src']);


          foreach ($dzsap->mainitems as $mainitem) {


            if ($slider['settings']['id'] === $mainitem['settings']['id']) {


              $sw_import = false;
            }
          }


          if ($sw_import) {


            array_push($dzsap->mainitems, $slider);


            update_option(DZSAP_DBNAME_MAINITEMS, $dzsap->mainitems);
          }
        }


        if ($it['type'] == 'set_curr_page_footer_player') {


          if (isset($_POST['post_id']) && $_POST['post_id']) {
            $id = $_POST['post_id'];

            update_post_meta($id, DZSAP_META_NAME_FOOTER_ENABLE, 'on');
            update_post_meta($id, DZSAP_META_NAME_FOOTER_FEED_TYPE, 'parent');
            update_post_meta($id, DZSAP_META_NAME_FOOTER_VPCONFIG, $it['src']);
          }
        }
        if ($it['type'] == 'apconfig_import') {

          $sw_import = true;
          $slider = unserialize($it['src']);


          foreach ($dzsap->mainitems_configs as $mainitem) {


            if ($slider['settings']['id'] === $mainitem['settings']['id']) {


              $sw_import = false;
            }
          }


          if ($sw_import) {


            array_push($dzsap->mainitems_configs, $slider);


            update_option(DZSAP_DBNAME_AUDIO_PLAYERS_CONFIGS, $dzsap->mainitems_configs);
          }
        }


        if ($it['type'] == DZSAP_REGISTER_POST_TYPE_CATEGORY) {


          $args = $it;


          $args['taxonomy'] = DZSAP_REGISTER_POST_TYPE_CATEGORY;
          $dzsap->import_demo_create_term_if_it_does_not_exist($args);


        }
        if ($it['type'] == 'product_cat') {


          $args = $it;


          $args['taxonomy'] = 'product_cat';
          $dzsap->import_demo_create_term_if_it_does_not_exist($args);


        }
        if ($it['type'] == DZSAP_REGISTER_POST_TYPE_NAME) {


          $args = $it;


          $taxonomy = DZSAP_REGISTER_POST_TYPE_CATEGORY;

          if (isset($args['post_type']) && $args['post_type'] == 'product') {


            $taxonomy = 'product_cat';
          }
          if ($args['term_slug']) {


            $term = get_term_by('slug', $args['term_slug'], $taxonomy);


            if ($term) {


              $args['term'] = $term;


            }


            $args['taxonomy'] = $taxonomy;

          }


          $args['call_from'] = 'import item lib';

          $this->import_demo_insert_post_complete($args);


        }
      }
    }


    echo json_encode($resp);
    die();
  }


  function delete_notice() {


    update_option($_POST['postdata'], 'seen');
    die();
  }


  function deactivate_license() {
    global $dzsap;

    $dzsap->mainoptions['dzsap_purchase_code'] = '';
    $dzsap->mainoptions['dzsap_purchase_code_binded'] = 'off';
    update_option(DZSAP_DBNAME_OPTIONS, $dzsap->mainoptions);

    die();
  }


  function activate_license() {
    global $dzsap;


    $dzsap->mainoptions['dzsap_purchase_code'] = $_POST['postdata'];
    $dzsap->mainoptions['dzsap_purchase_code_binded'] = 'on';
    update_option(DZSAP_DBNAME_OPTIONS, $dzsap->mainoptions);

    die();

  }


  function hide_intro_nag() {
    global $dzsap;


    $dzsap->mainoptions['acknowledged_intro_data'] = $_POST['postdata'];
    update_option(DZSAP_DBNAME_OPTIONS, $dzsap->mainoptions);

    die();

  }


  function parse_content_to_shortcode() {


    echo do_shortcode(stripslashes($_POST['postdata']));

    die();
  }


  function delete_pcm() {


    $playerId = $_POST['playerid'];
    $trackSource = '';

    if (isset($_POST['track_src'])) {
      $trackSource = $_POST['track_src'];
    }

    $dbPcmKey = 'dzsap_pcm_data_' . (DZSZoomSoundsHelper::sanitize_toKey($playerId));


    delete_option($dbPcmKey);

    if ($trackSource) {

      $dbPcmKey = 'dzsap_pcm_data_' . (DZSZoomSoundsHelper::sanitize_toKey($trackSource));


      delete_option($dbPcmKey);
    }


    $arr_pcm_to_id_links = array();
    if (get_option(DZSAP_DBNAME_PCM_LINKS)) {
      $arr_pcm_to_id_links = get_option('dzsap_pcm_to_id_links');


      unset($arr_pcm_to_id_links[$playerId]);


      foreach ($arr_pcm_to_id_links as $key => $pcmLink) {

        if (DZSZoomSoundsHelper::sanitize_toKey($pcmLink) === DZSZoomSoundsHelper::sanitize_toKey($trackSource)) {
          unset($arr_pcm_to_id_links[$key]);
          break;
        }
      }
      update_option(DZSAP_DBNAME_PCM_LINKS, $arr_pcm_to_id_links);
    }


    echo 'success deleted - ' . $dbPcmKey;
    die();
  }


  function get_attachment_src() {

    $fout = wp_get_attachment_image_src($_POST['id'], 'full');


    if (isset($fout[0])) {

      echo $fout[0];
    }
    die();
  }

  function saveLegacyitems() {
    global $dzsap;
    //---this is the main save function which saves item
    $auxarray = array();
    $mainarray = array();


    //parsing post data
    parse_str($_POST['postdata'], $auxarray);


    if (isset($_POST['currdb'])) {
      $dzsap->currDb = $_POST['currdb'];
    }


    $dbname_mainitems = DZSAP_DBNAME_MAINITEMS;
    if ($dzsap->currDb != 'main' && $dzsap->currDb != '') {
      $dbname_mainitems .= '-' . $dzsap->currDb;
    }

    if (isset($_POST['sliderid'])) {

      $mainarray = get_option($dbname_mainitems);
      foreach ($auxarray as $label => $value) {
        $aux = explode('-', $label);
        $tempmainarray[$aux[1]][$aux[2]] = $auxarray[$label];
      }
      $mainarray[$_POST['sliderid']] = $tempmainarray;
    } else {
      foreach ($auxarray as $label => $value) {

        $aux = explode('-', $label);
        $mainarray[$aux[0]][$aux[1]][$aux[2]] = $auxarray[$label];
      }
    }
    echo $dbname_mainitems;
    echo isset($_POST['currdb']);
    update_option($dbname_mainitems, $mainarray);
    echo 'success';
    die();
  }


  function save_audioplayer_configs() {
    // -- this is the main save function which saves configs
    global $dzsap;

    $dataArrayFromPost = array();
    $mainarray = array();

    // -- parsing post data
    parse_str($_POST['postdata'], $dataArrayFromPost);



    if (isset($_POST['currdb'])) {
      $dzsap->currDb = $_POST['currdb'];
    }


    if (isset($_POST['sliderid'])) {

      $mainarray = get_option(DZSAP_DBNAME_AUDIO_PLAYERS_CONFIGS);
      foreach ($dataArrayFromPost as $label => $value) {
        $aux = explode('-', $label);
        $tempmainarray[$aux[1]][$aux[2]] = $dataArrayFromPost[$label];
      }

      $mainarray[$_POST['sliderid']] = $tempmainarray;
    } else {


      if (isset($_POST['slider_name'])) {


        if ($_POST['slider_name'] == 'called_from_vpconfig_admin_preview') {

        }
        $dataArrayFromPost['0-settings-id'] = $_POST['slider_name'];


        $vpconfig_k = count($dzsap->mainitems_configs);
        $vpconfig_id = $_POST['slider_name'];
        for ($i = 0; $i < count($dzsap->mainitems_configs); $i++) {
          if ((isset($vpconfig_id)) && ($vpconfig_id == $dzsap->mainitems_configs[$i]['settings']['id'])) {
            $vpconfig_k = $i;
          }
        }


        $mainarray = get_option(DZSAP_DBNAME_AUDIO_PLAYERS_CONFIGS);
        foreach ($dataArrayFromPost as $label => $value) {
          $aux = explode('-', $label);
          $tempmainarray[$aux[1]][$aux[2]] = $dataArrayFromPost[$label];
        }


        if ($_POST['slider_name'] == 'called_from_vpconfig_admin_preview') {

          update_option('dzsap_temp_vpconfig', $tempmainarray);
        } else {

          $mainarray[$vpconfig_k] = $tempmainarray;
        }



      } else {

        foreach ($dataArrayFromPost as $label => $value) {

          $aux = explode('-', $label);
          $mainarray[$aux[0]][$aux[1]][$aux[2]] = $dataArrayFromPost[$label];
        }
      }

    }

    foreach ($mainarray as $key => $val) {
      if (!$val) {
        unset($mainarray[$key]);
      }
    }

    update_option(DZSAP_DBNAME_AUDIO_PLAYERS_CONFIGS, $mainarray);


    $response = array(
      'ajax_status' => 'success',
    );
    echo json_encode($response);

    die();
  }

  function save_mainOptions() {
    $mainOptionsFromPost = array();
    // -- parsing post data


    parse_str($_POST['postdata'], $mainOptionsFromPost);

    $mainOptionsFromPost_default = array();

    $mainOptions = get_option(DZSAP_DBNAME_OPTIONS);

    $mainOptionsFromPost = array_merge($mainOptionsFromPost_default, $mainOptionsFromPost);
    $mainOptionsFromPost = array_merge($mainOptions, $mainOptionsFromPost);

    if (isset($mainOptionsFromPost['dzsaap_enable_unregistered_submit']) && $mainOptionsFromPost['dzsaap_enable_unregistered_submit'] == 'on') {


      $user_name = 'portal_user';
      $user_email = 'portal_user@gmail.com';
      $user_id = dzsap_create_user($user_name, $user_email);


    }
    if (isset($mainOptionsFromPost['dzsaap_enable_allow_users_to_edit_own_tracks']) && $mainOptionsFromPost['dzsaap_enable_allow_users_to_edit_own_tracks'] == 'on') {

      $role = get_role('subscriber');

      $role->remove_cap('edit_posts');
      $role->remove_cap('dzsap_items');
      $role->add_cap('read_' . DZSAP_REGISTER_POST_TYPE_NAME);
      $role->add_cap('edit_' . DZSAP_REGISTER_POST_TYPE_NAME);
    }


    if (isset($mainOptionsFromPost['track_downloads']) && $mainOptionsFromPost['track_downloads'] == 'on' || isset($mainOptionsFromPost['analytics_enable']) && $mainOptionsFromPost['analytics_enable'] == 'on') {


      $this->create_activity_table();

      $mainOptionsFromPost['wpdb_enable'] = 'on';

    }

    if (isset($mainOptionsFromPost['analytics_enable']) && $mainOptionsFromPost['analytics_enable'] == 'off') {

      $mainOptionsFromPost['wpdb_enable'] = 'off';
    }


    update_option(DZSAP_DBNAME_OPTIONS, $mainOptionsFromPost);
    wp_send_json_success(array(
      'response_message' => esc_html__('Options saved', DZSAP_ID)
    ), 200);
  }


  function add_to_wishlist() {

    global $dzsap;

    $arr_wishlist = $dzsap->classView->get_wishlist();


    if ($_POST['wishlist_action'] == 'add') {
      array_push($arr_wishlist, $_POST['playerid']);

    } else {

      foreach ($arr_wishlist as $lab => $val) {
        if ($val == $_POST['playerid']) {
          unset($arr_wishlist[$lab]);
        }
      }
    }



    update_user_meta(get_current_user_id(), 'dzsap_wishlist', json_encode($arr_wishlist));


    die();
  }

  function get_pcm() {


    global $dzsap;

    $playerId = '';
    $source = '';

    if (isset($_POST['playerid'])) {
      $playerId = $_POST['playerid'];
    }
    if (isset($_POST['source'])) {
      $source = $_POST['source'];
    }


    echo $dzsap->classView->generate_pcm(array(), array(
      'generate_only_pcm' => true,
      'identifierId' => $playerId,
      'identifierSource' => $source,
    ));

    die();
  }

  function get_thumb_from_meta() {


    $pid = $_POST['postdata'];

    if (get_post_meta($pid, '_dzsap-thumb', true)) {

      echo get_post_meta($pid, '_dzsap-thumb', true);
    } else {


      $upload_dir = wp_upload_dir();
      $upload_dir_url = $upload_dir['url'] . '/';
      $upload_dir_path = $upload_dir['path'] . '/';

      $file = get_attached_file($pid);
      $metadata = wp_read_audio_metadata($file);
      if (isset($metadata['image']) && $metadata['image']['data']) {
        file_put_contents($upload_dir_path . 'audio_image_' . $pid . '.jpg', $metadata['image']['data']);
        echo $upload_dir_url . 'audio_image_' . $pid . '.jpg';
      }
    }


    die();
  }

  function front_submitcomment() {


    $time = current_time('mysql');

    $playerid = $_POST['playerid'];
    $playerid = str_replace('ap', '', $playerid);

    $email = '';
    $comm_author = $_POST['skinwave_comments_account'];


    $user_id = get_current_user_id();
    $user_data = get_userdata($user_id);


    if (isset($user_data->data)) {

      if (isset($user_data->data->ID)) {
        $email = $user_data->data->user_email;
        $comm_author = $user_data->data->user_login;
      }
    }


    $data = array(
      'comment_post_ID' => $playerid,
      'comment_author' => $comm_author,
      'comment_author_email' => $email,
      'comment_author_url' => $_POST['comm_position'],
      'comment_content' => $_POST['postdata'],
      'comment_type' => '',
      'comment_parent' => 0,
      'user_id' => 1,
      'comment_author_IP' => '127.0.0.1',
      'comment_agent' => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.0.10) Gecko/2009042316 Firefox/3.0.10 (.NET CLR 3.5.30729)',
      'comment_date' => $time,
      'comment_approved' => 1,
    );

    wp_insert_comment($data);


    setcookie("commentsubmitted-" . $playerid, '1', time() + 36000, COOKIEPATH);

    print_r($data);

    echo 'success';
    die();
  }


  function create_activity_table() {

    global $wpdb;


    $auxarray['wpdb_enable'] = 'on';

    $table_name = $wpdb->prefix . 'dzsap_activity';
    if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
      // -- table not in database. Create new table
      $charset_collate = $wpdb->get_charset_collate();

      $sql = "CREATE TABLE $table_name (
          id mediumint(9) NOT NULL AUTO_INCREMENT,
          type varchar(100) NOT NULL,
          id_user int(10) NOT NULL,
          ip varchar(255) NOT NULL,
          id_video varchar(255) NOT NULL,
          date datetime NOT NULL,
          UNIQUE KEY id (id)
     ) $charset_collate;";
      require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
      dbDelta($sql);

    } else {
    }

    update_option('dzsap_table_activity_created', 'on');
  }


}