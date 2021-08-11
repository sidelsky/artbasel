<?php
// -- in this file we generate item meta ( for EDIT AUDIO )
global $post, $wp_version;

$dzsap = $this->dzsap;

// -- we need real location, not insert-id


$struct_uploader = '';

if (current_user_can('upload_files')) {

  $struct_uploader = '<div class="dzs-wordpress-uploader ">
    <a href="#" class="button-secondary">' . esc_html__('Upload', DZSAP_ID) . '</a>
</div>';
}
?>
  <div class="select-hidden-con">
    <?php
    $lab_nonce = 'dzsap_meta_nonce';
    echo '<input type="hidden" name="' . $lab_nonce . '" value="' . wp_create_nonce($lab_nonce) . '"/>';
    ?>


  </div>


<?php

foreach ($dzsap->options_item_meta as $lab => $optionForMeta) {


  if (isset($optionForMeta['only_for'])) {

    if (in_array('item_meta_below', $optionForMeta['only_for'])) {

    } else {
      continue;
    }
  }

  if (isset($optionForMeta['choices'])) {

  } else {

    if (isset($optionForMeta['options'])) {
      $optionForMeta['choices'] = $optionForMeta['options'];
    }
  }
  if (isset($optionForMeta['name'])) {

  } else {
    $optionForMeta['name'] = $lab;
  }
  if (isset($optionForMeta['title'])) {

  } else {

    // -- only the col_end
    $optionForMeta['title'] = '';

  }


  if (strpos($optionForMeta['name'], DZSAP_META_OPTION_PREFIX) === false) {
    $optionForMeta['name'] = DZSAP_META_OPTION_PREFIX . $optionForMeta['name'];
  }

  ?>
  <div class="setting <?php

  $option_name = $optionForMeta['name'];


  if ($optionForMeta['type'] == 'attach') {
    ?>setting-upload<?php
  }

  ?>"><?php

    if (strpos($option_name, 'item_source')) {

      $lab_aux = 'dzsap_meta_source_attachment_id';
      $val_aux = '';
      $val_aux = get_post_meta($post->ID, $lab_aux, true);
      echo DZSHelpers::generate_input_text($lab_aux, array(
        'class' => $class,
        'seekval' => $val_aux,
        'input_type' => 'hidden',
      ));
    }
    ?>
    <h5 class="setting-label setting-label--item-label"><?php echo $optionForMeta['title']; ?></h5>


    <?php

    if ($optionForMeta['type'] == 'attach') {
      ?><span class="uploader-preview"></span><?php
    }

    ?>

    <?php

    $val = get_post_meta($post->ID, $option_name, true);

    $class = 'setting-field medium';

    if ($optionForMeta['type'] == 'attach') {
      $class .= ' uploader-target';
    }


    if ($optionForMeta['type'] == 'attach') {
      echo DZSHelpers::generate_input_text($option_name, array(
        'class' => $class,
        'seekval' => $val,
      ));
    }
    if ($optionForMeta['type'] == 'text') {
      echo DZSHelpers::generate_input_text($option_name, array(
        'class' => $class,
        'seekval' => $val,
      ));
    }
    if ($optionForMeta['type'] == 'textarea') {
      echo DZSHelpers::generate_input_textarea($option_name, array(
        'class' => $class,
        'seekval' => $val,
      ));
    }
    if ($optionForMeta['type'] == 'custom_html') {
      echo $optionForMeta['custom_html'];


    }
    if ($optionForMeta['type'] == 'select') {


      $class = 'dzs-style-me skin-beige';

      if (isset($optionForMeta['select_type']) && $optionForMeta['select_type']) {
        $class .= ' ' . $optionForMeta['select_type'];
      }

      echo DZSHelpers::generate_select($option_name, array(
        'class' => $class,
        'seekval' => $val,
        'options' => $optionForMeta['choices'],
      ));

      if (isset($optionForMeta['select_type']) && $optionForMeta['select_type'] == 'opener-listbuttons') {

        echo '<ul class="dzs-style-me-feeder">';

        foreach ($optionForMeta['choices_html'] as $optionForMeta_html) {

          echo '<li>';
          echo $optionForMeta_html;
          echo '</li>';
        }

        echo '</ul>';
      }


    }

    if ($optionForMeta['type'] == 'attach') {
      echo $struct_uploader;
    }

    if (isset($optionForMeta['extra_html_after_input']) && $optionForMeta['extra_html_after_input']) {
      echo $optionForMeta['extra_html_after_input'];
    }

    if (isset($optionForMeta['sidenote']) && $optionForMeta['sidenote']) {
      echo '<div class="sidenote">' . $optionForMeta['sidenote'] . '</div>';
    }

    ?>

  </div>

  <?php


}
?>

<?php

$i = 1;

for ($i = 1; $i < 4; $i++) {

  $lab = 'extra_meta_label_' . $i;

  $val = '';

  if (isset($dzsap->mainoptions[$lab])) {
    $val = $dzsap->mainoptions[$lab];
  }

  if ($val) {
    ?>
    <div class="setting setting--item-meta">
    <h5 class="setting-label"><?php echo $val; ?></h5>
    <?php
    $lab = 'dzsap_meta_' . $lab;

    $val = get_post_meta($post->ID, $lab, true);

    echo DZSHelpers::generate_input_text($lab, array(
      'class' => 'setting-field ',
      'seekval' => $val,
    ));

    ?>
    <div class="sidenote"><?php echo esc_html__("optional meta set in settings", DZSAP_ID); ?></div>
    </div><?php
  }
}
