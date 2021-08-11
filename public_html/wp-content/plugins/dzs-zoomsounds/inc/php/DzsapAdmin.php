<?php


class DzsapAdmin {

  private $dzsap;

  public $termMetaExtraOptions = array();

  /**
   * DzsapAdmin constructor.
   * @param DZSAudioPlayer $dzsap
   */
  function __construct($dzsap) {


    $this->termMetaExtraOptions = array(
      array(
        'name' => 'term_image',
        'title' => esc_html__('Image'),
        'description' => esc_html__('image'),
        'type' => 'media-upload',
      ),
    );

    $this->dzsap = $dzsap;


    add_action('add_meta_boxes', array($this, 'handle_add_meta_boxes'));
    add_action('admin_init', array($this, 'handle_admin_init'), 5);

    add_action('admin_head', array($this, 'handle_admin_head'), 5);
    add_action('admin_menu', array($this, 'handle_admin_menu'));
    add_action('add_attachment', array($this, 'handle_add_attachment'));


    include_once(DZSAP_BASE_PATH . 'inc/php/dzs-shared/admin_termMetas.php');
    add_action(DZSAP_REGISTER_POST_TYPE_CATEGORY . '_edit_form_fields', array($this, 'handle_edit_form_fields'), 10, 10);
    add_action('edited_' . DZSAP_REGISTER_POST_TYPE_CATEGORY, 'dzs_admin_termMetas_save_custom_meta', 10, 2);

    if (isset($_GET['taxonomy']) && $_GET['taxonomy'] == DZSAP_TAXONOMY_NAME_SLIDERS) {

      include_once(DZSAP_BASE_PATH . 'inc/sliders-admin/SlidersAdmin.php');
      include_once(DZSAP_BASE_PATH . 'admin/sliders_admin.php');
      add_action('in_admin_footer', 'dzsap_sliders_admin');
    }


    add_action('save_post', array($this, 'admin_meta_save'));
    add_action('admin_footer', array($this, 'handle_admin_footer'));


  }

  /**
   * this will add the custom meta field to the add new term page
   * @param $term WP_Term
   */
  function handle_edit_form_fields($term) {


    dzs_admin_termMetas_generateOptions($this->termMetaExtraOptions, $term, DZSAP_BASE_URL);


  }

  function handle_add_attachment($postId) {
    $thePost = get_post($postId);


  }

  function handle_admin_footer() {


    if (isset($_GET['taxonomy']) && $_GET['taxonomy'] == DZSAP_TAXONOMY_NAME_SLIDERS) {
      wp_enqueue_script(DZSAP_PREFIX_LOWERCASE . '-sliders-admin--highlight-menu-item', DZSAP_BASE_URL . 'admin/js_slidersAdmin/slidersAdmin-highlight-menu-item.js');
    }

    if (isset($_GET['dzs_css'])) {

      if ($_GET['dzs_css'] == 'remove_wp_menu') {
        wp_enqueue_style('dzs.remove_wp_bar', DZSAP_BASE_URL . 'tinymce/remove_wp_bar.css');

      }
    }
  }


  function handle_admin_menu() {
    DZSZoomSoundsHelper::registerDzsapPages();

  }

  function admin_page_mainoptions() {
    $dzsap = $this->dzsap;

    if (isset($_POST['dzsap_delete_plugin_data']) && $_POST['dzsap_delete_plugin_data'] == 'on') {


      // -- delete plugin data

      if ($dzsap->dbs && is_array($dzsap->dbs) && count($dzsap->dbs)) {

        foreach ($dzsap->dbs as $db) {

          $aux = DZSAP_DBNAME_MAINITEMS;
          $aux .= '-' . $db;

          delete_option($aux);
        }
      }

      delete_option(DZSAP_DBNAME_LEGACY_DBS);

      delete_option(DZSAP_DBNAME_MAINITEMS);
      delete_option(DZSAP_DBNAME_AUDIO_PLAYERS_CONFIGS);
      delete_option(DZSAP_DBNAME_OPTIONS);
    }


    if (isset($_GET['dzsap_shortcode_builder']) && $_GET['dzsap_shortcode_builder'] == 'on') {
      dzsap_shortcode_builder();
    } elseif (isset($_GET[DZSAP_ADMIN_SHORTCODE_PLAYER_GENERATOR_KEY]) && $_GET[DZSAP_ADMIN_SHORTCODE_PLAYER_GENERATOR_KEY] == 'on') {


      dzsap_shortcode_player_builder();
    } elseif (isset($_GET[DZSAP_ADMIN_PREVIEW_PLAYER_PARAM]) && $_GET[DZSAP_ADMIN_PREVIEW_PLAYER_PARAM] == 'on') {

      include_once DZSAP_BASE_PATH . "class_parts/admin-preview-player.php";


      dzsap_preview_player();
    } elseif (isset($_GET[DZSAP_ADMIN_PAGENAME_MAINOPTIONS_WAVE_GENERATOR]) && $_GET[DZSAP_ADMIN_PAGENAME_MAINOPTIONS_WAVE_GENERATOR] == 'on') {

      include_once DZSAP_BASE_PATH . 'inc/php/admin-pages/page_wave_regenerate-functions.php';


      dzsap_wave_regenerate_admin_page();
    } else {

      include_once DZSAP_BASE_PATH . "class_parts/admin-page-mainoptions.php";
    }

    ?>


    <div class="clear"></div><br/>
    <?php
    DZSZoomSoundsHelper::embedZoomTabsAndAccordions();
    wp_enqueue_script('jquery-ui-slider');
  }


  function wp_dashboard_setup() {

    $dzsap = $this->dzsap;

    if ($dzsap->mainoptions['analytics_enable'] == 'on') {

      wp_add_dashboard_widget('dzsap_dashboard_analytics', // Widget slug.
        'ZoomSounds' . esc_html__('Analytics', DZSAP_ID), // Title.
        'dzsap_analytics_dashboard_content'

      );
    }

  }

  /**
   * deprecated ?
   */
  function dashboard_comments_display() {


    $type = 'attachement';
    $args = array(
      'post_type' => 'attachment',
      'numberposts' => null,
      'posts_per_page' => '-1',
      'post_mime_type' => 'audio',
      'post_status' => null
    );
    $attachments = get_posts($args);

    $arr_attcomms = array();
    foreach ($attachments as $att) {
      $comments_count = wp_count_comments($att->ID);
      $aux = array('id' => $att->ID, 'commnr' => ($comments_count->approved));
      array_push($arr_attcomms, $aux);
    }


    usort($arr_attcomms, array('DZSZoomSoundsHelper', 'sort_commnr'));


    echo '<div id="dzsap_chart_div"></div>';


    ?>
    <div hidden class="dzsap-admin-feed--dashboard-data"><?php
    $i = 0;
    foreach ($arr_attcomms as $att) {
      echo '';

      $auxpo = get_post($att['id']);


      if ($i > 0) {
        echo ',';
      }
      echo '["' . $auxpo->post_title . '", ' . $att['commnr'] . ']';
      $i++;

    }
  ;
    ?>
    </div><?php


  }


  function do_backup() {
    // -- generate backup
    $dzsap = $this->dzsap;

    $timestamp = time();


    $data = get_option(DZSAP_DBNAME_MAINITEMS);

    if (is_array($data)) {
      $data = serialize($data);
    }


    $upload_dir = wp_upload_dir();


    if (file_exists($upload_dir['basedir'] . '/dzsap_backups')) {


    } else {


      mkdir($upload_dir['basedir'] . '/dzsap_backups', 0755);
    }

    file_put_contents($upload_dir['basedir'] . '/dzsap_backups/backup_' . $timestamp . '.txt', $data);


    update_option('dzsap_last_backup', $timestamp);


    if ($dzsap->mainoptions['playlists_mode'] == 'normal') {


      $terms = get_terms(DZSAP_TAXONOMY_NAME_SLIDERS, array(
        'hide_empty' => false,
      ));

      foreach ($terms as $term) {

        $data = $dzsap->classAdmin->playlist_export($term->term_id);

        if (is_array($data)) {
          $data = json_encode($data);
        }

        file_put_contents($upload_dir['basedir'] . '/dzsap_backups/backup_' . $term->slug . '_' . $timestamp . '.txt', $data);
      }
    } else {

      if (is_array($dzsap->dbs)) {
        foreach ($dzsap->dbs as $adb) {
          $data = get_option(DZSAP_DBNAME_MAINITEMS . '-' . $adb);

          if (is_array($data)) {
            $data = serialize($data);
          }

          file_put_contents($upload_dir['basedir'] . '/dzsap_backups/backup_' . $adb . '_' . $timestamp . '.txt', $data);


        }
      }
    }

    $logged_backups = array();
    try {

      $logged_backups = json_decode(get_option('dzsap_backuplog'), true);
    } catch (Exception $err) {

    }
    if (is_array($logged_backups) == false) {
      $logged_backups = array();
    }


    array_push($logged_backups, time());
    if (count($logged_backups) > 5) {
      array_shift($logged_backups);
    }


    update_option('dzsap_backuplog', json_encode($logged_backups));
  }


  function playlist_export($term_id, $pargs = array()) {


    $margs = array(
      'download_export' => false
    );

    $margs = array_merge($margs, $pargs);

    $term_meta = get_option("taxonomy_$term_id");


    $tax = DZSAP_TAXONOMY_NAME_SLIDERS;

    $reference_term = get_term_by('id', $term_id, $tax);


    $reference_term_name = $reference_term->name;
    $reference_term_slug = $reference_term->slug;
    $selected_term_id = $reference_term->term_id;


    if ($selected_term_id) {

      $args = array(
        'post_type' => 'dzsap_items',
        'numberposts' => -1,
        'posts_per_page' => -1,


        'orderby' => 'meta_value_num',
        'order' => 'ASC',
        'meta_query' => array(
          'relation' => 'OR',
          array(
            'key' => 'dzsap_meta_order_' . $selected_term_id,

            'compare' => 'EXISTS',
          ),
          array(
            'key' => 'dzsap_meta_order_' . $selected_term_id,

            'compare' => 'NOT EXISTS'
          )
        ),
        'tax_query' => array(
          array(
            'taxonomy' => $tax,
            'field' => 'id',
            'terms' => $selected_term_id // Where term_id of Term 1 is "1".
          )
        ),
      );

      $my_query = new WP_Query($args);


      $arr_export = array(
        'original_term_id' => $selected_term_id,
        'original_term_slug' => $reference_term_slug,
        'original_term_name' => $reference_term_name,
        'original_site_url' => site_url(),
        'export_type' => 'meta_term',
        'term_meta' => $term_meta,
        'items' => array(),
      );

      foreach ($my_query->posts as $po) {


        $po_sanitized = DZSZoomSoundsHelper::sanitize_to_gallery_item($po);


        array_push($arr_export['items'], $po_sanitized);


      }


      if ($margs['download_export']) {
        header('Content-Type: text/plain');
        header('Content-Disposition: attachment; filename="' . "dzsap_export_" . $reference_term_slug . ".txt" . '"');
      }

      return $arr_export;
    } else {
      return array();
    }
  }


  function checkInitCalls() {
    global $pagenow;
    $dzsap = $this->dzsap;

    $post_id = '';
    if (isset($_GET['post']) && $_GET['post'] != '') {
      $post_id = $_GET['post'];
    }

    if ($pagenow == 'post.php') {
      $po = get_post($post_id);
    }


    if (isset($_GET['taxonomy']) && $_GET['taxonomy'] == DZSAP_TAXONOMY_NAME_SLIDERS) {
      wp_enqueue_script('jquery-ui-sortable');
      $url = DZSAP_URL_FONTAWESOME_EXTERNAL;

      if ($dzsap->mainoptions['fontawesome_load_local'] == 'on') {
        $url = DZSAP_BASE_URL . 'libs/fontawesome/font-awesome.min.css';
      }


      wp_enqueue_style('fontawesome', $url);
      wp_enqueue_style('dzs.tooltip', DZSAP_BASE_URL . 'libs/dzstooltip/dzstooltip.css');

    }


    if (isset($_GET['page']) && $_GET['page'] == DZSAP_ADMIN_PAGENAME_MAINOPTIONS) {

      DZSZoomSoundsHelper::enqueueScriptsForAdminMainOptions();

    }


    if (current_user_can('manage_options') || current_user_can('edit_posts') || current_user_can('edit_pages') || current_user_can('dzsap_make_shortcode')) {

      wp_enqueue_script('dzsap_htmleditor', DZSAP_BASE_URL . 'shortcodegenerator/add-generators-to-mce.js');
      wp_enqueue_script('dzsap_configreceiver', DZSAP_BASE_URL . 'tinymce/receiver.js');
    }

  }

  function permalink_settings() {

    echo wpautop(esc_html__('These settings control the permalinks used for audio items. These settings only apply when <strong>not using "default" permalinks above</strong>.', DZSAP_ID));

    $permalinks = get_option('dzsap_permalinks');
    $dzsap_permalink = '';

    if (isset($permalinks['item_base'])) {

      $dzsap_permalink = $permalinks['item_base'];
    }


    $item_base = _x('audio', 'default-slug', DZSAP_ID);

    $structures = array(0 => '', 1 => '/' . trailingslashit($item_base));
    ?>
    <table class="form-table">
      <tbody>
      <tr>
        <th><label><input name="dzsap_permalink" type="radio" value="<?php echo $structures[0]; ?>"
                          class="dzsaptog" <?php checked($structures[0], $dzsap_permalink); ?> /> <?php echo esc_html__('Default'); ?>
          </label></th>
        <td><code><?php echo home_url(); ?>/?audio=sample-item</code></td>
      </tr>
      <tr>
        <th><label><input name="dzsap_permalink" type="radio" value="<?php echo $structures[1]; ?>"
                          class="dzsaptog" <?php checked($structures[1], $dzsap_permalink); ?> /> <?php echo esc_html__('Product', DZSAP_ID); ?>
          </label></th>
        <td><code><?php echo home_url(); ?>/<?php echo $item_base; ?>/sample-item/</code></td>
      </tr>
      <tr>
        <th><label><input name="dzsap_permalink" id="dzsap_custom_selection" type="radio" value="custom"
                          class="tog" <?php checked(in_array($dzsap_permalink, $structures), false); ?> />
            <?php echo esc_html__('Custom Base', DZSAP_ID); ?></label></th>
        <td>
          <input name="dzsap_permalink_structure" id="dzsap_permalink_structure" type="text"
                 value="<?php echo esc_attr($dzsap_permalink); ?>" class="regular-text code"> <span
            class="description"><?php echo esc_html__('Enter a custom base to use. A base <strong>must</strong> be set or WordPress will use default instead.', DZSAP_ID); ?></span>
        </td>
      </tr>
      </tbody>
    </table>
    <?php
    wp_enqueue_script('dzsap-admin-permalinks', DZSAP_BASE_URL . 'admin/admin-page/permalinks.js');
  }


  function handle_admin_init() {

    $dzsap = $this->dzsap;

    if (isset($_GET[DZSAP_ADMIN_PREVIEW_PLAYER_PARAM]) && $_GET[DZSAP_ADMIN_PREVIEW_PLAYER_PARAM] === 'on') {
      wp_enqueue_style('dzs.remove_wp_bar', DZSAP_BASE_URL . 'tinymce/remove_wp_bar.css');
      wp_enqueue_script('html2canvas', 'https://html2canvas.hertzen.com/dist/html2canvas.min.js');
    }


    if ($dzsap->mainoptions['activate_comments_widget'] == 'on') {
      add_action('wp_dashboard_setup', array($this, 'wp_dashboard_setup'));
    }


    if ($dzsap->mainoptions['analytics_enable'] == 'on') {
      include_once(DZSAP_BASE_PATH . "class_parts/analytics.php");
      add_action('wp_dashboard_setup', array($this, 'wp_dashboard_setup'));
    }


    if ($dzsap->mainoptions['analytics_enable'] == 'on') {
      wp_enqueue_script('google.charts', 'https://www.gstatic.com/charts/loader.js');
      if ($dzsap->mainoptions['analytics_enable_location'] == 'on') {
        wp_enqueue_script('google.maps', 'https://www.google.com/jsapi');
      }
    }

    add_settings_section('dzsap-permalink', esc_html__('Audio Items Permalink Base', DZSAP_ID), array($this, 'permalink_settings'), 'permalink');


    if ($dzsap->mainoptions['analytics_table_created'] == 'off') {
      dzsap_analytics_table_create();
    }


    // -- use javascript
    if (isset($_GET['page']) && $_GET['page'] == DZSAP_ADMIN_PAGENAME_PARENT) {
      if ($dzsap->mainoptions['playlists_mode'] == 'normal') {
        wp_redirect(admin_url('edit-tags.php?taxonomy=dzsap_sliders&post_type=' . DZSAP_REGISTER_POST_TYPE_NAME));
        exit;
      }
    }

  }

  function handle_admin_head_start() {


  }

  function handle_admin_head() {
    $dzsap = $this->dzsap;


    DZSZoomSoundsHelper::admin_legacySliders_determineCurrSlider($dzsap);

    if (isset($_GET[DZSAP_ADMIN_PREVIEW_PLAYER_PARAM]) && $_GET[DZSAP_ADMIN_PREVIEW_PLAYER_PARAM] === 'on') {

      include_once(DZSAP_BASE_PATH . 'class_parts/admin-preview-player.php');
      dzsap_preview_player__headScripts();
    }


    if ($dzsap->mainoptions['enable_auto_backup'] == 'on') {

      $last_backup = get_option('dzsap_last_backup');


      if ($last_backup) {

        $timestamp = time();
        if (abs($timestamp - $last_backup) > (3600 * 24 * 1)) {

          $this->do_backup();
        }

      } else {
        $this->do_backup();
      }
    }


    if (isset($_GET['page']) && $_GET['page'] == DZSAP_ADMIN_PAGENAME_ABOUT) {

      wp_enqueue_style('dzsvg', DZSAP_BASE_URL . 'libs/videogallery/vplayer.css');
      wp_enqueue_script('dzsvg', DZSAP_BASE_URL . "libs/videogallery/vplayer.js");
    }


    $this->dzsap->options_item_meta = include(DZSAP_BASE_PATH . "configs/options-item-meta.php");
    DZSZoomSoundsHelper::enqueueScriptsForAdminGeneral();

    // -- admin_head


  }

  function admin_page() {

    $dzsap = $this->dzsap;
    // -- old Sliders Admin
    include_once DZSAP_BASE_PATH . "inc/php/deprecated-sliders-admin.php";
  }

  function admin_page_vpc() {

    include_once DZSAP_BASE_PATH . "class_parts/admin-page-audioPlayerConfigs.php";

    dzsap_admin_page_vpc();
  }

  function handle_add_meta_boxes() {

    $dzsap = $this->dzsap;

    add_meta_box('dzsap_footer_player_options', esc_html__('Footer Player Settings', DZSAP_ID), array($this, 'admin_meta_options'), 'page', 'normal', 'high');
    add_meta_box('dzsap_footer_player_options', esc_html__('Footer Player Settings', DZSAP_ID), array($this, 'admin_meta_options'), 'post', 'normal', 'high');
    add_meta_box('dzsap_footer_player_options', esc_html__('Footer Player Settings', DZSAP_ID), array($this, 'admin_meta_options'), 'product', 'normal', 'high');
    add_meta_box('dzsap_footer_player_options', esc_html__('Footer Player Settings', DZSAP_ID), array($this, 'admin_meta_options'), DZSAP_REGISTER_POST_TYPE_NAME, 'normal', 'high');


    // -- deprecated!
    add_meta_box('dzsap_waveform_generation', esc_html__('ZoomSounds Waveforms'), 'dzsap_admin_meta_download_waveforms', 'download', 'normal', 'high');


    add_meta_box('dzsap_meta_options', esc_html__('Audio Item Settings', DZSAP_ID), array($this, 'dzsap_admin_meta_options'), DZSAP_REGISTER_POST_TYPE_NAME, 'normal', 'high');


    $meta_post_array = $dzsap->mainoptions['dzsap_meta_post_types'];


    if ($meta_post_array && is_array($meta_post_array) && count($meta_post_array)) {


      foreach ($meta_post_array as $post_type) {
        if ($post_type == DZSAP_REGISTER_POST_TYPE_NAME) {
          continue;
        }


        if ($post_type) {
          add_meta_box('dzsap_meta_options', esc_html__('Audio Item Settings', DZSAP_ID), array($this, 'dzsap_admin_meta_options'), $post_type, 'normal');
        }

      }
    }


  }


  function admin_meta_save($post_id) {
    global $post;
    if (!$post) {
      return;
    }
    /* Check autosave */
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
      return $post_id;
    }
    if (isset($_REQUEST['dzs_nonce'])) {
      $nonce = $_REQUEST['dzs_nonce'];
      if (!wp_verify_nonce($nonce, 'dzs_nonce'))
        wp_die('Security check');
    }
    if (is_array($_POST)) {
      $postData = $_POST;


      if ($post->post_type === DZSAP_REGISTER_POST_TYPE_NAME || $post->post_type === 'product') {
        $this->saveAllPostMetaFields($post, $postData);
      } else {

        if (get_post_meta($post_id, DZSAP_META_NAME_FOOTER_ENABLE, true) || isset($postData[DZSAP_META_NAME_FOOTER_ENABLE]) && $postData[DZSAP_META_NAME_FOOTER_ENABLE] == 'on') {
          $this->saveAllPostMetaFields($post, $postData);
        }
      }
    }
  }

  function saveAllPostMetaFields($post, $postData) {
    foreach ($postData as $label => $value) {
      if (strpos($label, 'dzsap_') !== false) {
        dzs_savemeta($post->ID, $label, $value);
      }
    }

  }

  function dzsap_admin_meta_options() {


    include_once(DZSAP_BASE_PATH . 'class_parts/item-meta.php');

    DZSZoomSoundsHelper::enqueueSelector();
  }


  function admin_page_about() {
    $dzsap = $this->dzsap;

    include_once(DZSAP_BASE_PATH . 'class_parts/admin-page-about.php');


    DZSZoomSoundsHelper::embedZoomTabsAndAccordions();

    $url = DZSAP_URL_FONTAWESOME_EXTERNAL;

    if ($dzsap->mainoptions['fontawesome_load_local'] == 'on') {
      $url = DZSAP_BASE_URL . 'libs/fontawesome/font-awesome.min.css';
    }


    wp_enqueue_style('fontawesome', $url);
    wp_enqueue_script('admin-page--about', DZSAP_BASE_URL . 'admin/admin-page/about.js');

  }


  function admin_page_autoupdater() {

    include_once DZSAP_BASE_PATH . 'class_parts/admin-page-autoupdater.php';
    dzsap_admin_page_autoupdater();
  }

  function admin_meta_options() {
    global $post;


    $dzsap = $this->dzsap;

    $struct_uploader = '
<a rel="nofollow" href="#" class="button-secondary dzs-wordpress-uploader">' . esc_html__('Upload', DZSAP_ID) . '</a>
';


    $vpconfigs_arr = array(
      array('lab' => 'default', 'val' => 'default')
    );

    $i23 = 0;
    foreach ($dzsap->mainitems_configs as $vpconfig) {


      $auxa = array(
        'lab' => $vpconfig['settings']['id'],
        'val' => $vpconfig['settings']['id'],
        'extraattr' => 'data-sliderlink="' . $i23 . '"',
      );

      array_push($vpconfigs_arr, $auxa);

      $i23++;
    }


    ?>
    <div class="dzsap-meta-bigcon">
      <input type="hidden" name="dzs_nonce" value="<?php echo wp_create_nonce('dzs_nonce'); ?>"/>


      <?php
      ?>


      <div class="dzs-setting">
        <?php
        $lab = DZSAP_META_NAME_FOOTER_ENABLE;

        echo DZSHelpers::generate_input_text($lab, array(
          'class' => 'fake-input',
          'def_value' => '',
          'seekval' => 'off',
          'input_type' => 'hidden',
        ));
        ?>
        <h4><?php echo esc_html__('Enable Sticky Player', DZSAP_ID); ?></h4>
        <?php

        echo '<div class="dzscheckbox skin-nova">
                                        ' . DZSHelpers::generate_input_checkbox($lab, array('id' => $lab, 'class' => 'mainsetting dzs-dependency-field', 'val' => 'on', 'seekval' => get_post_meta($post->ID, $lab, true))) . '
                                        <label for="' . $lab . '"></label>
                                    </div>';


        // -- for future we can do a logical set like "(" .. ")" .. "AND" .. "OR"


        ?>

      </div>


      <?php
      $dependency = array(

        array(
          'lab' => DZSAP_META_NAME_FOOTER_ENABLE,
          'val' => array('on'),
        ),

      );
      ?>
      <div data-dependency='<?php echo json_encode($dependency); ?>'>

        <?php


        $feed_type = array(
          array(
            'lab' => 'parent',
            'val' => 'parent',
            'visual_option_image_src' => DZSAP_BASE_URL . "tinymce/img/footer_type_parent.png",
            'visual_option_label' => esc_html__("Parent Player", DZSAP_ID),
          ),
          array(
            'lab' => 'custom',
            'val' => 'custom',
            'visual_option_image_src' => DZSAP_BASE_URL . "tinymce/img/footer_type_media.png",
            'visual_option_label' => esc_html__("Custom Media", DZSAP_ID),
          ),
        );
        ?>


        <div class="dzs-setting ">
          <h4><?php echo esc_html__('Feed Type', DZSAP_ID); ?></h4>

          <?php
          $lab = DZSAP_META_NAME_FOOTER_FEED_TYPE;
          DZSZoomSoundsHelper::admin_generate_selectVisual($lab, $feed_type, get_post_meta($post->ID, $lab, true));

          ?>


          <div class="sidenote">
            <?php echo esc_html__('Select parent player for the sticky player to await being played from the outside ( a track on the page or select custom media to set a custom mp3 to play directly in the sticky player.', DZSAP_ID); ?>
          </div>

        </div>


        <div class="dzs-setting vpconfig-wrapper">
          <h4><?php echo esc_html__('Player configuration', DZSAP_ID); ?></h4>
          <?php
          $lab = DZSAP_META_NAME_FOOTER_VPCONFIG;
          echo DZSHelpers::generate_select($lab, array('class' => 'vpconfig-select styleme', 'options' => $vpconfigs_arr, 'seekval' => get_post_meta($post->ID, $lab, true))); ?>

          <div class="edit-link-con" style="margin-top: 10px;"></div>

        </div>


        <?php


        // -- for future we can do a logical set like "(" .. ")" .. "AND" .. "OR"
        $dependency = array(

          array(
            'lab' => DZSAP_META_NAME_FOOTER_FEED_TYPE,
            'val' => array('custom'),
          ),

        );


        ?>

        <div class="dzs-setting" data-dependency='<?php echo json_encode($dependency); ?>'>
          <h4><?php echo esc_html__('Featured Media', DZSAP_ID); ?></h4>
          <?php
          $lab = DZSAP_META_NAME_FOOTER_FEATURED_MEDIA;
          echo DZSHelpers::generate_input_text($lab, array('class' => 'input-big-image upload-target-prev', 'def_value' => '', 'seekval' => get_post_meta($post->ID, $lab, true))); ?>
          <?php echo $struct_uploader; ?>

        </div>

        <div class="dzs-setting" data-dependency='<?php echo json_encode($dependency); ?>'>
          <h4><?php echo esc_html__('Song name', DZSAP_ID); ?></h4>
          <?php
          $lab = DZSAP_META_NAME_FOOTER_SONG_NAME;
          echo DZSHelpers::generate_input_text($lab, array('class' => 'input-big-image  ', 'def_value' => '', 'seekval' => get_post_meta($post->ID, $lab, true))); ?>

        </div>

        <?php


        ?>


        <div class="dzs-setting " data-dependency='<?php echo json_encode($dependency); ?>'>
          <h4><?php echo esc_html__('Media Type', DZSAP_ID); ?></h4>
          <?php
          $types_arr = array(
            array('lab' => 'audio', 'val' => 'audio'),
            array('lab' => 'shoutcast', 'val' => 'shoutcast'),
            array('lab' => 'soundcloud', 'val' => 'soundcloud'),
            array('lab' => 'youtube', 'val' => 'youtube'),
            array('lab' => 'fake', 'val' => 'fake'),
          );
          $lab = DZSAP_META_NAME_FOOTER_TYPE;
          echo DZSHelpers::generate_select($lab, array('class' => ' styleme', 'options' => $types_arr, 'seekval' => get_post_meta($post->ID, $lab, true))); ?>

          <div class="edit-link-con"></div>
        </div>


      </div>


    </div>


    <?php
  }


}