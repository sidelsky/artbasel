<?php


class DzsapWooCommerce {

  public $has_generated_product_player = false;
  private $wc_called_loop_from = '';


  /**
   * DzsapWooCommerce constructor. called on init
   * @param DZSAudioPlayer $dzsap
   */
  function __construct($dzsap) {
    $this->dzsap = $dzsap;


    // todo: move this woocommerce stuff
    if ($dzsap->mainoptions['wc_single_product_player'] && $dzsap->mainoptions['wc_single_product_player'] != 'off') {

      if ($dzsap->mainoptions['wc_single_player_position'] == 'top') {
        add_action('woocommerce_before_single_product', array($this, 'handle_woocommerce_single_product_summary'));
      }
      if ($dzsap->mainoptions['wc_single_player_position'] == 'overlay') {
        add_action('woocommerce_single_product_summary', array($this, 'handle_woocommerce_single_product_summary'));
      }
      if ($dzsap->mainoptions['wc_single_player_position'] == 'bellow') {

        add_action('woocommerce_after_single_product_summary', array($this, 'handle_woocommerce_single_product_summary'));
      }


    }


    // -- loooop
    if ($dzsap->mainoptions['wc_loop_product_player'] && $dzsap->mainoptions['wc_loop_product_player'] != 'off') {

      if ($dzsap->mainoptions['wc_loop_player_position'] == 'top') {
        add_action('woocommerce_before_shop_loop_item', array($this, 'handle_woocommerce_before_shop_loop_item'));
      }


      if ($dzsap->mainoptions['wc_loop_player_position'] == 'overlay') {
        add_action('woocommerce_before_shop_loop_item_title', array($this, 'handle_woocommerce_before_shop_loop_item'));

        add_filter('woocommerce_product_get_image', array($this, 'filter_woocommerce_product_get_image'), 10, 5);
      }

      if ($dzsap->mainoptions['wc_loop_player_position'] == 'bellow') {
        add_action('woocommerce_after_shop_loop_item', array($this, 'handle_woocommerce_before_shop_loop_item'));
      }
    }


  }


  /**
   * will break if not returning an image
   * @param $image
   * @param $product
   * @param $size
   * @param $attr
   * @param $placeholder
   * @return string|string[]
   */
  function filter_woocommerce_product_get_image($image, $product, $size, $attr, $placeholder) {


    $image = preg_replace('/<img.*\/>/', '<div class="woocommerce-loop--woocommerce_thumbnail-con dzsap--go-to-thumboverlay--container">$0</div>', $image);


    return $image;
  }


  function handle_woocommerce_before_shop_loop_item() {


    global $post;


    $this->wc_called_loop_from = 'loop';
    if ($post && $post->ID && get_post_meta($post->ID, 'dzsap_woo_product_track', true)) {


      $args = array(

        'call_from' => 'wc_loop',
        'extra_classes' => ' from-wc_generate_player from-wc_loop',
      );


      $this->wc_generate_player($post->ID, $args);

    }


  }


  function handle_woocommerce_single_product_summary() {

    global $post;
    $dzsap = $this->dzsap;

    if ($this->has_generated_product_player) {
      return false;
    }

    $this->wc_called_loop_from = 'single';

    $id = 0;

    if ($post && $post->ID) {
      $id = $post->ID;
    }

    $product = wc_get_product($id);

    if ($product->is_type('grouped')) {
      $children = $product->get_children();


      $ids = '';


      foreach ($children as $poid) {
        if (get_post_meta($poid, DZSAP_META_WOOCOMMERCE_ZOOMSOUNDS_MP3, true)) {
          if ($ids) {
            $ids .= ',';
          }
          $ids .= $poid;
        }
      }


      echo '<div class="wc-dzsap-wrapper for-dzsag ';

      if ($dzsap->mainoptions['wc_single_player_position'] == 'overlay') {
        echo 'go-after-thumboverlay ';
      }

      echo '">';

      if ($ids) {

        echo dzsap_shortcode_showcase(array(

          'feed_from' => 'audio_items',
          'ids' => $ids,
        ));


        $this->has_generated_product_player = true;
      }

      echo '</div>';

    } else {
      $args = array(

        'call_from' => 'handle_woocommerce_single_product_summary',
        'extra_classes' => ' from-wc_generate_player from-wc_single',
      );
      $this->wc_generate_player($id, $args);

    }


  }

  /**
   * echoes wc player for zoomsounds
   * @param $id
   * @param array $pargs
   * @return false
   */
  function wc_generate_player($id, $pargs = array()) {

    $dzsap = $this->dzsap;

    $margs = array(
      'call_from' => 'default',
      'extra_classes' => ' from-wc_generate_player',
    );


    $margs = array_merge($margs, $pargs);


    if ($this->has_generated_product_player) {
      return false;
    }

    $this->has_generated_product_player = false;


    $post = get_post($id);

    $player_position = $dzsap->mainoptions['wc_loop_player_position'];


    if (strpos($margs['extra_classes'], 'from-wc_single') !== false) {
      $player_position = $dzsap->mainoptions['wc_single_player_position'];


    }


    if ($id && get_post_meta($post->ID, DZSAP_META_WOOCOMMERCE_ZOOMSOUNDS_MP3, true)) {


      $dzsap->sliders__player_index++;


      $src = get_post_meta($post->ID, DZSAP_META_WOOCOMMERCE_ZOOMSOUNDS_MP3, true);


      DZSZoomSoundsHelper::enqueueMainScrips();

      $argsForShortcode = array(
        'mp3' => $src,
        'config' => $dzsap->mainoptions['wc_single_product_player'],
      );


      $argsForShortcode['source'] = $argsForShortcode['mp3'];
      $argsForShortcode['product_id'] = $id;
      $argsForShortcode['called_from'] = 'single_product_summary';
      $argsForShortcode['config'] = $dzsap->mainoptions['wc_single_product_player'];
      $argsForShortcode['extra_classes'] = $margs['extra_classes'];


      if ($this->wc_called_loop_from == 'loop') {

        $argsForShortcode['config'] = $dzsap->mainoptions['wc_loop_product_player'];
      }

      if ($dzsap->mainoptions['wc_product_play_in_footer'] == 'on') {
        $argsForShortcode['faketarget'] = '.'.DZSAP_VIEW_STICKY_PLAYER_ID;
      }


      if ($player_position == 'overlay') {

        $argsForShortcode['extra_classes'] = ' prevent-bubble';


      }

      if (strpos($argsForShortcode['source'], 'https://soundcloud.com') !== false) {
        $argsForShortcode['type'] = 'soundcloud';
      }


      $argsForShortcode['songname'] = $post->post_title;
      $argsForShortcode['menu_songname'] = $post->post_title;

      // Get user object
      $recent_author = get_user_by('ID', $post->post_author);
      // Get user display name
      $argsForShortcode['artistname'] = $recent_author->display_name;
      $argsForShortcode['menu_artistname'] = $recent_author->display_name;

      if ($margs['call_from'] == 'wc_loop') {
        $argsForShortcode['songname'] = '<object class="zoomsounds-woocommerce--product-link"><a rel="nofollow" href="' . get_permalink($post->ID) . '">' . $argsForShortcode['songname'] . '</a></object>';
      }


      // -- try to get from reference term
      $tax = DZSAP_TAXONOMY_NAME_SLIDERS;


      $is_playlist = false;

      $playlist_slug = DZSAP_WOOCOMMERCE_PLAYLIST_IN_PRODUCT_PREFIX . $argsForShortcode['product_id'];

      $reference_term = get_term_by('slug', $playlist_slug, $tax);

      if ($reference_term) {
        $is_playlist = true;

      }


      echo '<div class="wc-dzsap-wrapper for-dzsap-player-wc-loop ';


      if ($is_playlist) {
        echo ' is-playlist';
      }

      if ($player_position == 'overlay') {
        echo ' go-to-thumboverlay center-ap-inside ';
      }

      echo '">';


      $argsForShortcode['thumb_for_parent'] = DZSZoomSoundsHelper::view_getComputedDzsapThumbnail($id);


      $it = $post;
      if (get_post_meta($it->ID, 'dzsap_woo_sample_time_start', true)) {
        $argsForShortcode['sample_time_start'] = get_post_meta($it->ID, 'dzsap_woo_sample_time_start', true);
      }
      if (get_post_meta($it->ID, 'dzsap_woo_sample_time_end', true)) {
        $argsForShortcode['sample_time_end'] = get_post_meta($it->ID, 'dzsap_woo_sample_time_end', true);
      }
      if (get_post_meta($it->ID, 'dzsap_woo_sample_time_total', true)) {
        $argsForShortcode['sample_time_total'] = get_post_meta($it->ID, 'dzsap_woo_sample_time_total', true);
      }

      $argsForShortcode['autoplay'] = 'off';


      if ($is_playlist) {

        $argsForShortcode['id'] = $playlist_slug;


        echo $dzsap->classView->shortcode_playlist_main($argsForShortcode, '');


      } else {

        if ($margs['call_from'] == 'handle_woocommerce_single_product_summary' && $dzsap->mainoptions['wc_single_product_player_shortcode']) {
          // -- if we have shortcode


          $aux = $dzsap->mainoptions['wc_single_product_player_shortcode'];
          $aux = DZSZoomSoundsHelper::sanitize_from_shortcode_pattern($aux, $post);

          echo do_shortcode($aux);
        } else {

          echo $dzsap->classView->shortcode_player($argsForShortcode, '');
        }

      }


      echo '</div><!-- end .wc-dzsap-wrapper -->';

    }

    wp_enqueue_script('dzsap-woocommerce-product-player', DZSAP_BASE_URL . 'inc/js/shortcodes/dzsap-woocommerce-product-player.js', array('jquery'), DZSAP_VERSION);
  }
}


$dzsap_woo_tab_data = false;


function dzsap_woo_woocommerce_init() {


  add_action('woocommerce_product_write_panel_tabs', 'dzsap_woo_product_write_panel_tab');
  add_action('woocommerce_product_data_panels', 'dzsap_woo_product_write_panel');
  add_action('woocommerce_process_product_meta', 'dzsap_woo_product_save_data', 10, 2);

  // frontend stuff
  add_filter('woocommerce_product_tabs', 'dzsap_woo_add_custom_product_tabs');

  add_filter('woocommerce_custom_product_tabs_lite_content', 'dzsap_woo_do_shortcode');

}


function dzsap_woo_add_custom_product_tabs($tabs) {
  global $product, $dzsap_woo_tab_data;

  return $tabs;
}


function dzsap_woo_custom_product_tabs_panel_content($key, $tab) {

  // allow shortcodes to function
  $content = apply_filters('the_content', $tab['content']);
  $content = str_replace(']]>', ']]&gt;', $content);

  echo apply_filters('woocommerce_custom_product_tabs_lite_heading', '<h2>' . $tab['title'] . '</h2>', $tab);
  echo apply_filters('woocommerce_custom_product_tabs_lite_content', $content, $tab);
}


function dzsap_woo_product_write_panel_tab() {
  echo "<li class=\"product_tabs_lite_tab\"><a href=\"#woocommerce_dzsap_tab\"><span>" . esc_html__('ZoomSounds') . "</span></a></li>";
}


/**
 * Adds the panel to the Product Data postbox in the product interface
 */
function dzsap_woo_product_write_panel() {
  global $post, $dzsap;
  // the product


  echo '<div id="woocommerce_dzsap_tab" class="panel wc-metaboxes-wrapper woocommerce_options_panel">';


  $lab = DZSAP_META_WOOCOMMERCE_ZOOMSOUNDS_MP3;
  echo '<div class="upload-for-target-con">';
  woocommerce_wp_text_input(array('id' => $lab, 'class' => 'upload-target-prev', 'label' => esc_html__('Preview Track'), 'description' => ('<button class="button-secondary action dzs-wordpress-uploader ">Upload</button>'), 'value' => get_post_meta($post->ID, $lab, true)));


  echo '</div>';



  $lab = 'dzsap_woo_use_preview_track_for_download';

  $val = 'no';

  woocommerce_wp_hidden_input(
    array(
      'id'    => $lab,
      'value' => $val
    )
  );

  if (get_post_meta($post->ID, $lab, true)) {
    $val = get_post_meta($post->ID, $lab, true);
  }

  echo '<div class="woocommerce_dzsap_tab-setting">';
  woocommerce_wp_checkbox(array('id' => $lab, 'class' => ' ', 'label' => esc_html__('Use for download'), 'value' => $val));


  echo '</div>';


  if ($dzsap->mainoptions['skinwave_wave_mode'] != 'canvas') {


  } else {


    ?>

    <p class="form-field dzsap_woo_product_track_field "><label
        for="dzsap_woo_product_track"><?php echo esc_html__("Waveform", 'dzsap'); ?></label>
      <span class="regenerate-waveform-con">
            <button class="button-secondary regenerate-waveform regenerate-waveform--from-woo "
                    data-playerid="<?php echo $_GET['post']; ?>"><?php echo esc_html__("Regenerate Waveform", 'dzsap'); ?></button>
        </span></p>


    <?php

  }


  $lab = 'dzsap_woo_sample_time_start';

  $val = 0;

  if (get_post_meta($post->ID, $lab, true)) {
    $val = get_post_meta($post->ID, $lab, true);
  }

  echo '<div class="woocommerce_dzsap_tab-setting">';
  woocommerce_wp_text_input(array('id' => $lab, 'class' => 'sample-time-start-feeder', 'label' => esc_html__('Sample Time Start'), 'description' => esc_html__('If this is a sample ( you are not showing the full track, you can input a start time here ), leave 0 if not', 'dzsap'), 'value' => $val));


  echo '</div>';


  $lab = 'dzsap_woo_sample_time_end';

  $val = 0;

  if (get_post_meta($post->ID, $lab, true)) {
    $val = get_post_meta($post->ID, $lab, true);
  }


  echo '<div class="woocommerce_dzsap_tab-setting">';
  woocommerce_wp_text_input(array('id' => $lab, 'class' => 'sample-time-end-feeder', 'label' => esc_html__('Sample Time End'), 'description' => esc_html__('If this is a sample ( you are not showing the full track, you can input a end time here ), leave 0 if not'), 'value' => $val));


  echo '</div>';


  $lab = 'dzsap_woo_sample_time_total';

  $val = 0;

  if (get_post_meta($post->ID, $lab, true)) {
    $val = get_post_meta($post->ID, $lab, true);
  }


  echo '<div class="woocommerce_dzsap_tab-setting">';
  woocommerce_wp_text_input(array('id' => $lab, 'class' => 'sample-time-total-feeder', 'label' => esc_html__('Sample Time Total'), 'description' => esc_html__('The total track duration  ( in seconds ) '), 'value' => $val));


  echo '</div>';


  $lab = 'dzsap_woo_subtitle';
  $val = '';

  if (get_post_meta($post->ID, $lab, true)) {
    $val = get_post_meta($post->ID, $lab, true);
  }

  echo '<div class="woocommerce_dzsap_tab-setting">';
  woocommerce_wp_text_input(array('id' => $lab, 'class' => '', 'label' => esc_html__('Subtitle'), 'description' => esc_html__('The subtitle for some grid styles'), 'value' => $val));


  echo '</div>';


  $lab = 'dzsap_woo_custom_link';
  $val = '';

  if (get_post_meta($post->ID, $lab, true)) {
    $val = get_post_meta($post->ID, $lab, true);
  }

  echo '<div class="woocommerce_dzsap_tab-setting">';
  woocommerce_wp_text_input(array('id' => $lab, 'class' => '', 'label' => esc_html__('Custom Link on Buy'), 'description' => esc_html__('Custom link on Buy button click'), 'value' => $val));


  echo '</div>';


  $lab = 'dzsap_meta_replace_artistname';
  $val = '';

  if (get_post_meta($post->ID, $lab, true)) {
    $val = get_post_meta($post->ID, $lab, true);
  }
  echo '<div class="woocommerce_dzsap_tab-setting">';
  woocommerce_wp_text_input(array('id' => $lab, 'class' => '', 'label' => esc_html__('Artist name'), 'description' =>
    dzs_esc__(
      esc_html__('default will be the author name input %s for no author name', 'dzsap')
      , array('<strong>none</strong>')
    ),
    'value' => $val));


  echo '</div>';


  ?>
  <p class="form-field dzsap_woo_product_track_field "><label
    for="<?php echo DZSAP_META_WOOCOMMERCE_ZOOMSOUNDS_MP3; ?>"><?php echo esc_html__("Playlist", 'dzsap'); ?></label>

  <button class="button-secondary  btn-dzsap-create-playlist-for-woo <?php
  if (DZSZoomSoundsHelper::check_playlist_exists('zoomsounds-product-playlist-' . $_GET['post'])) {
    echo ' dzs-auto-click-after-1000';
  }
  ?>"
          data-playerid="<?php echo $_GET['post']; ?>"><?php echo esc_html__("Generate playlist preview", 'dzsap'); ?></button>
  </p><?php


  echo '</div>';

}


function dzsap_woo_product_save_data($post_id, $post) {

  /* Check autosave */
  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
    return $post_id;
  }
  if (is_array($_POST)) {
    $auxa = $_POST;
    foreach ($auxa as $label => $value) {


      if (strpos($label, 'dzsap_woo_') !== false) {
        dzs_savemeta($post_id, $label, $value);
      }
    }
  }

}


function dzsap_woo_woocommerce_wp_textarea_input($field) {
  global $thepostid, $post;

  if (!$thepostid) $thepostid = $post->ID;
  if (!isset($field['placeholder'])) $field['placeholder'] = '';
  if (!isset($field['class'])) $field['class'] = 'short';
  if (!isset($field['value'])) $field['value'] = get_post_meta($thepostid, $field['id'], true);

  echo '<p class="form-field ' . $field['id'] . '_field"><label style="display:block;" for="' . $field['id'] . '">' . $field['label'] . '</label><textarea class="' . $field['class'] . '" name="' . $field['id'] . '" id="' . $field['id'] . '" placeholder="' . $field['placeholder'] . '" rows="2" cols="20"' . (isset($field['style']) ? ' style="' . $field['style'] . '"' : '') . '>' . esc_textarea($field['value']) . '</textarea> ';

  if (isset($field['description']) && $field['description']) {
    echo '<span class="description">' . $field['description'] . '</span>';
  }

  echo '</p>';
}