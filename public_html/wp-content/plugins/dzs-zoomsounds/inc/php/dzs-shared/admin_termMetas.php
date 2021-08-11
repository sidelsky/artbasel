<?php
if (!function_exists('dzs_admin_termMetas_save_custom_meta')) {
  function dzs_admin_termMetas_save_custom_meta($term_id) {
    if (isset($_POST['term_meta'])) {
      $t_id = $term_id;
      $term_meta = get_option("taxonomy_$t_id");
      $cat_keys = array_keys($_POST['term_meta']);
      foreach ($cat_keys as $key) {
        if (isset ($_POST['term_meta'][$key])) {
          $term_meta[$key] = $_POST['term_meta'][$key];
        }
      }
      // -- Save the option array.
      update_option("taxonomy_$t_id", $term_meta);

    }
  }
}
if (!function_exists('dzs_admin_termMetas_generateOptionInput')) {

  /**
   * @param string $inputFormName
   * @param string $inputType
   * @param string $baseUrl
   * @param array $term_meta optional
   */
  function dzs_admin_termMetas_generateOptionInput($inputFormName = '', $inputType = 'text', $inputValue = '', $baseUrl = '') {

    $struct_uploader = '
    <div class="dzs-wordpress-uploader insert-id">
      <a href="#" class="button-secondary">' . esc_html__('Upload', 'dzs') . '</a>
    </div>';
    if ($inputType == 'media-upload') {
      echo '<span class="uploader-preview"></span>';
    }
    ?>


    <?php
    $lab = 'term_meta[' . $inputFormName . ']';

    $val = $inputValue;


    $inputClass = 'setting-field medium';


    if ($inputType == 'media-upload') {
      $inputClass .= ' uploader-target';

    }

    if ($inputType == 'color') {
      $inputClass .= ' color-with-spectrum';
    }
    if ($inputType == 'media-upload' || $inputType == 'text' || $inputType == 'input') {
    }

    if ($inputType == 'iconselector') {
      wp_enqueue_style('spectrum', $baseUrl . 'libs/spectrum/spectrum.css');
      wp_enqueue_script('spectrum', $baseUrl . 'libs/spectrum/spectrum.js');
      wp_enqueue_script('faiconselector', $baseUrl . 'libs/dzsiconselector/dzsiconselector.js');
      wp_enqueue_style('faiconselector', $baseUrl . 'libs/dzsiconselector/dzsiconselector.css');

      $inputClass .= ' style-iconselector iconselector-waiter';
      ?>
      <div class="iconselector" data-input-acts-as-search="on">
      <p>

      <span class="iconselector-preview"></span>
      <?php
    }


    echo DZSHelpers::generate_input_text($lab, array(
      'class' => $inputClass,
      'seekval' => $val,
      'id' => $lab,
    ));
    if ($inputType == 'iconselector') {
      ?>
      <span class="iconselector-btn"><i class="fa fa-caret-down"></i>
      </p>

      </span>
      <div class="iconselector-clip"><?php

        ?></div>
      </div><?php
    }


    if ($inputType == 'color') {
    }

    if ($inputType == 'media-upload') {
      echo $struct_uploader;
    }
    ?>
    <?php

    ?>
    <p class="description"><?php echo esc_html__('Enter a value for this field', 'dzs'); ?></p><?php
  }
}
if (!function_exists('dzs_admin_termMetas_generateOptions')) {
  /**
   *
   * this will add the custom meta field to the add new term page
   * applied in wp action term_meta_fields
   * @param array $termMetaExtraOptions
   * @param WP_Term $term
   * @param string $baseUrl
   */
  function dzs_admin_termMetas_generateOptions($termMetaExtraOptions, $term, $baseUrl = '') {
    foreach ($termMetaExtraOptions as $termMetaOption) {
      ?>

      <?php


      $termId = $term->term_id;

      // -- retrieve the existing value(s) for this meta field. This returns an array
      $term_meta = get_option("taxonomy_$termId"); ?>
      <tr class="form-field">
        <th scope="row" valign="top"><label
            for="term_meta[<?php echo $termMetaOption['name']; ?>]"><?php echo $termMetaOption['title']; ?></label></th>
        <td class="<?php
        if ($termMetaOption['type'] == 'media-upload') {
          echo 'setting-upload';
        }
        ?>">
          <?php


          $inputFormName = $termMetaOption['name'];
          $inputValue = '';
          if (isset($term_meta[$inputFormName])) {
            $inputValue = esc_attr($term_meta[$inputFormName]) ? esc_attr($term_meta[$inputFormName]) : '';
          }

          dzs_admin_termMetas_generateOptionInput($termMetaOption['name'], $termMetaOption['type'], $inputValue, $baseUrl);
          ?>
        </td>
      </tr>
      <?php
    }

  }
}