<?php
// some total cache vars that needs to be like this

function dzsap_shortcode_player_builder() {
  global $dzsap;


  $sample_data_installed = false;
  if ($dzsap->sample_data && is_array($dzsap->sample_data)) {
    $sample_data_installed = true;
  }


  $ids = '';


  if ($dzsap->sample_data && isset($dzsap->sample_data['media'])) {

    for ($i = 0; $i < count($dzsap->sample_data['media']); $i++) {

      if ($i > 0) {
        $ids .= ',';
      }


      $ids .= $dzsap->sample_data['media'][$i];
    }

  }

  if (isset($dzsap->sample_data['media']) && isset($dzsap->sample_data['media'][0])) {
    ?>


    <div class="dzsap-feed--media-0"><?php echo $dzsap->sample_data['media'][0]; ?></div><?php
  }
  ?><?php
  if ($ids) {
    ?>
    <div class="dzsap-feed--media-ids"><?php echo $ids; ?></div><?php

  }
  if (isset($_GET['sel'])) {
    $aux = str_replace(array("\r", "\r\n", "\n"), '', $_GET['sel']);
    $aux = str_replace("'", '\\\'', $aux);
    ?>
    <div hidden class="dzsap-startinit"><?php echo stripslashes($aux); ?></div><?php

  }
  ?>

<div class="wrap wrap-for-generator-player <?php
if ($sample_data_installed) {
  echo 'sample-data-installed';
}
?>">
  <h3>ZoomSounds <?php echo esc_html__(" Shortcode Generator", 'dzsap'); ?></h3>


  <?php


  $options_array = array();
  $ilab = 0;


  foreach ($dzsap->options_array_player as $categoryKey => $opt) {


    if (
      $categoryKey == 'source'
      || $categoryKey == 'type'
      || $categoryKey == 'config'
      || $categoryKey == 'thumb'
      || $categoryKey == 'cover'
      || $categoryKey == 'autoplay'
      || $categoryKey == 'loop'
      || $categoryKey == 'extra_classes'
      || $categoryKey == 'extra_classes_player'
      || $categoryKey == 'songname'
      || $categoryKey == 'artistname'
      || $categoryKey == 'open_in_ultibox'
      || $categoryKey == 'enable_likes'
      || $categoryKey == 'enable_views'
      || $categoryKey == 'enable_downloads_counter'
      || $categoryKey == 'enable_download_button'
      || $categoryKey == 'playerid'
      || $categoryKey == 'itunes_link'
      || $categoryKey == 'wrapper_image'
      || $categoryKey == 'play_target'
      || $categoryKey == 'download_custom_link'
      || $categoryKey == 'download_link_label'
      || $categoryKey == 'type_normal_stream_type'
      || $categoryKey == 'is_amazon_s3'
    ) {
      continue;
    }


    $options_array[$ilab] = array(
      'type' => $opt['type'],
      'param_name' => $categoryKey,
      'heading' => $opt['title'],

    );


    if (isset($opt['type'])) {
      $options_array[$ilab]['type'] = $opt['type'];
      if ($opt['type'] == 'select') {
        $options_array[$ilab]['type'] = 'dropdown';
      }
      if ($opt['type'] == 'text') {
        $options_array[$ilab]['type'] = 'textfield';
      }
      if ($opt['type'] == 'image') {
        $opt['type'] = 'upload';
        $opt['library_type'] = 'image';
      }
      if ($opt['type'] == 'image') {
      }
      if ($opt['type'] == 'upload') {
        $options_array[$ilab]['type'] = 'dzs_add_media_att';
      }
    }
    if (isset($opt['sidenote'])) {
      $options_array[$ilab]['description'] = $opt['sidenote'];
    }
    if (isset($opt['default'])) {
      $options_array[$ilab]['std'] = $opt['default'];
      $options_array[$ilab]['default'] = $opt['default'];
    }
    if (isset($opt['options'])) {
      $options_array[$ilab]['value'] = $opt['options'];
    }

    if (isset($opt['library_type'])) {
      $options_array[$ilab]['library_type'] = $opt['library_type'];
    }

    if (isset($opt['class'])) {
      $options_array[$ilab]['class'] = $opt['class'];
    }


    ?>
  <div class="setting" <?php

  $optionName = '';


  if (isset($opt['name'])) {
    $optionName = $opt['name'];
  } else {

  }

  if (isset($opt['dependency']) && $opt['dependency']) {
    echo 'data-dependency=\'' . json_encode($opt['dependency']) . '\'';
  }


  ?> data-setting-name="<?php echo $optionName; ?>" data-label="<?php echo $categoryKey; ?>">
    <h4 class="setting-label"><?php echo $opt['title']; ?></h4>
    <?php

    $option_name = $categoryKey;


    ?>
  <div class="input-con type-<?php echo $opt['type']; ?>">
    <?php
    if ($opt['type'] == 'text') {
      echo DZSHelpers::generate_input_text($categoryKey, array(
        'class' => 'shortcode-field  dzs-dependency-field',
      ));
    }
    if ($opt['type'] == 'textarea_html') {
      $content = '';
      $editor_id = $categoryKey;

      wp_editor($content, $editor_id);
    }
    if ($opt['type'] == 'upload') {

      $upload_class = 'shortcode-field upload-target-prev upload-type-' . $opt['library_type'] . ' ';

      if (isset($opt['prefer_id']) && $opt['prefer_id'] == 'on') {
        $upload_class .= ' upload-get-id';
      }

      $upload_class .= ' dzs-dependency-field';
      echo DZSHelpers::generate_input_text($categoryKey, array(
        'class' => $upload_class,
      ));

      echo '<a href="#" class="button-secondary dzs-wordpress-uploader">' . esc_html__("Upload", DZSAP_ID) . '</a>';
    }
    if ($opt['type'] == 'select') {
      echo DZSHelpers::generate_select($categoryKey, array(
        'class' => 'shortcode-field dzs-style-me skin-beige dzs-dependency-field',
        'options' => $opt['options'],
      ));

    }
    ?>
    </div><?php
    if (isset($opt['sidenote'])) {

      ?>
      <div class="sidenote"><?php echo $opt['sidenote']; ?></div>
      <?php
    }
    if (isset($opt['sidenote-2']) && $opt['sidenote-2']) {


      $sidenote_2_class = '';

      if (isset($opt['sidenote-2-class'])) {
        $sidenote_2_class = $opt['sidenote-2-class'];
      }
      ?>
      <div class="sidenote-2 <?php echo $sidenote_2_class ?>"><?php echo $opt['sidenote-2']; ?></div>
      <?php
    }
    ?>


    </div><?php

    $ilab++;
  }

  // -- end deprecated ...


  $argsShortcodeGenerator = array(
    'for_shortcode_generator' => true,
  );
  $fout = '';
  $fout .= '<div class="dzs-tabs dzs-tabs-meta-item auto-init skin-default " data-options=\'{ "design_tabsposition" : "top"
,"design_transition": "fade"
,"design_tabswidth": "default"
,"toggle_breakpoint" : "200"
,"settings_appendWholeContent": "true"
,"toggle_type": "accordion"
}
\' style=\'padding: 0;\'><div class="dzs-tab-tobe">
                    <div class="tab-menu ">' . esc_html__("General", 'dzsap') . '
    </div><div class="tab-content tab-content-item-meta-cat-main">

' . dzsap_sliders_admin_generate_item_meta_cat('main', null, $argsShortcodeGenerator) . '
    </div>
    </div>';


  foreach ($dzsap->item_meta_categories_lng as $categoryKey => $val) {


    ob_start();
    ?>
    <div class="dzs-tab-tobe">
    <div class="tab-menu ">
      <?php
      echo($val);
      ?>
    </div>
    <div class="tab-content tab-content-cat-<?php echo $categoryKey; ?>">


      <?php
      echo dzsap_sliders_admin_generate_item_meta_cat($categoryKey, null, $argsShortcodeGenerator);
      ?>


    </div></div><?php

    $fout .= ob_get_clean();


  }

  // -- duplicated as gutenberg/configs/config-samples.js
  $arr_examples = array(
    array(
      'img' => 'assets/sampledata_img/sample--skin-wave-simple.jpg',
      'name' => 'sample--skin-wave-simple',
      'label' => 'Skin Wave Simple',
    ),
    array(

      'img' => 'assets/sampledata_img/sample--boxed-inside.jpg',
      'name' => 'sample--boxed-inside',
      'label' => 'Wave Boxed Inside',
    ),
    array(

      'img' => 'assets/sampledata_img/sample--skin-pro-simple.jpg',
      'name' => 'sample--skin-pro-simple',
      'label' => 'Skin Pro Simple',
    ),
    array(

      'img' => 'assets/sampledata_img/sample--skin-justthumbandbutton.jpg',
      'name' => 'sample--skin-justthumbandbutton',
      'label' => 'Skin Just Thumb and Button',
    ),
  );


  $fout .= '<div class="dzs-tab-tobe"><div class="tab-menu ">' . esc_html__("Examples", 'dzsap') . '</div><div class="tab-content ">';

  $fout .= '<div class="dzs-player-examples-con">';
  foreach ($arr_examples as $example) {
    $fout .= '<div class="dzs-player-example" onClick=\'() => { console.log("dada"); }\' data-the-name="' . $example['name'] . '">
                      <img class="the-img" src="' . DZSAP_BASE_URL . $example['img'] . '"/>
                      <h6 class="the-label">' . $example['label'] . '</h6>
                    </div>';
  }

  $fout .= '</div><!-- end .dzs-player-examples-con -->';
  $fout .= '</div>';

  $fout .= '</div>';// -- end tabs

  echo $fout;

  echo '<br>';
  echo '<button class="button-primary submit-shortcode">' . esc_html__("Submit Shortcode") . '</button>';


  ?>
  <div class="shortcode-output"></div>

  </div><?php

}