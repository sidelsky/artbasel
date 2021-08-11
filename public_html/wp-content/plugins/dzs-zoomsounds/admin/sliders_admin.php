<?php


// -- in action init
$dzsap_slidersAdmin_fixHackConfictsForOrder_metaQuery = null;


add_action('dzsap_sliders_edit_form_fields', 'dzsap_sliders_admin_add_feature_group_field', 10, 10);

add_filter('dzsap_sliders_row_actions', 'dzsap_sliders_admin_duplicate_post_link', 10, 2);
add_action('admin_action_dzsap_duplicate_slider_term', 'dzsap_action_dzsap_duplicate_slider_term', 10, 2);


add_action('admin_init', 'dzsap_sliders_admin_init', 1000);

/**
 * rms_ plugin acts up with the order
 * @param $targetQuery
 */
function dzsap_slidersAdmin_fixHackConfictsForOrder($targetQuery) {

  global $dzsap_slidersAdmin_fixHackConfictsForOrder_metaQuery;

  if (isset($targetQuery->query_vars) && $targetQuery->query_vars['post_type'] === DZSAP_REGISTER_POST_TYPE_NAME) {
    $targetQuery->query_vars['meta_query'] = $dzsap_slidersAdmin_fixHackConfictsForOrder_metaQuery;
  }
}

function dzsap_sliders_admin_generate_item($po) {
  global $dzsap;


  $fout = '';
  $thumb = '';
  $thumb_from_meta = '';
  // -- we need real location, not insert-id
  $struct_uploader = '<div class="dzs-wordpress-uploader "><a rel="nofollow" href="#" class="button-secondary">' . esc_html__('Upload', DZSAP_ID) . '</a></div>';


  $po_id = '';
  if ($po && is_int($po->ID)) {

    $thumb = DZSZoomSoundsHelper::get_post_thumb_src($po->ID);

    $po_id = $po->ID;




    $thumb_from_meta = get_post_meta($po->ID, 'dzsap_meta_item_thumb', true);
  }

  if ($thumb_from_meta) {

    $thumb = $thumb_from_meta;
  }

  $thumb_url = '';
  if ($thumb) {
    $thumb_url = DZSZoomSoundsHelper::getImageSourceFromId($thumb);
  }


  if ($po_id) {

    $fout .= '<div class="slider-item dzstooltip-con for-click';

    if ($po && $po->ID == 'placeholder') {
      $fout .= ' slider-item--placeholder';
    }

    $fout .= '" data-id="' . $po->ID . '">';





    $fout .= '<div class="divimage" style="background-image:url(' . $thumb_url . ');"></div>';
    $fout .= '<div class="slider-item--title" >' . $po->post_title . '</div>';

    $fout .= '<div class="delete-btn item-control-btn"><i class="fa fa-times-circle-o"></i></div>
<div class="clone-item-btn item-control-btn"><i class="fa fa-clone"></i></div>
<div class="dzstooltip dzstooltip--sliders-admin   arrow-top talign-start style-rounded color-dark-light ">
<div class="dzstooltip--selector-top"></div>
<div class="dzstooltip--inner">
<div class="dzstooltip--content">';


    $fout .= '<div class="dzs-tabs dzs-tabs-meta-item  skin-default " data-options=\'{ "design_tabsposition" : "top","design_transition": "fade","design_tabswidth": "default","toggle_breakpoint" : "200","settings_appendWholeContent": "true","toggle_type": "accordion"}
\' style=\'padding: 0;\'>
<div class="dzs-tab-tobe">
<div class="tab-menu ">' . esc_html__("General", DZSAP_ID) . '
    </div>
<div class="tab-content tab-content-item-meta-cat-main">
' . dzsap_sliders_admin_generate_item_meta_cat('main', $po) . '
    </div>
    </div>';


    foreach ($dzsap->item_meta_categories_lng as $lab => $val) {
      ob_start();
      ?>
      <div class="dzs-tab-tobe">
      <div class="tab-menu ">
        <?php
        echo($val);
        ?>
      </div>
      <div class="tab-content tab-content-cat-<?php echo $lab; ?>">


        <?php
        echo dzsap_sliders_admin_generate_item_meta_cat($lab, $po);
        ?>


      </div>
      </div><?php
      $fout .= ob_get_clean();
    }

    $fout .= '</div>';// -- end tabs


    $fout .= '</div>';
    $fout .= '</div>';
    $fout .= '</div>';
    $fout .= '</div>';
  }

  return $fout;
}

function dzsap_sliders_admin_generate_item_meta_cat($categoryKey, $po, $pargs = array()) {

  // -- generate options for sliders admin category
  global $dzsap;


  $margs = array(

    'for_shortcode_generator' => false,
    'for_item_meta' => false,
  );

  $margs = array_merge($margs, $pargs);

  $fout = '';
  // -- we need real location, not insert-id
  $struct_uploader = '<div class="dzs-wordpress-uploader ">
<a rel="nofollow" href="#" class="button-secondary">' . esc_html__('Upload', DZSAP_ID) . '</a>
</div>';


  // -- generate item category ( for sliders admin )
  foreach ($dzsap->options_item_meta as $lab => $optionItem) {


    $optionItem = array_merge(array(
      'category' => '',
      'no_preview' => '',
      'it_is_for' => 'item_meta',
      'input_extra_classes' => '',
    ), $optionItem);



    // -- some sanitizing
    if ($optionItem['type'] == 'image') {
      $optionItem['type'] = 'attach';
    }


    if (isset($optionItem['options'])) {
      if (isset($optionItem['choices']) == false) {
        $optionItem['choices'] = $optionItem['options'];
      }
    }

    if (!($optionItem['category'] == $categoryKey)) {
      if ($categoryKey == 'main') {
        if ($optionItem['category'] == '') {
        } else {
          continue;
        }
      } else {
        continue;
      }
    }

    if (dzs_is_option_for_this($optionItem, 'shortcode_generator')) {
      if ($margs['for_shortcode_generator'] == false) {
        continue;
      }
    }

    if (dzs_is_option_for_this($optionItem, 'for_item_meta_only')) {
      if ($margs['for_item_meta'] == false) {
        continue;
      }
    }


    if ($optionItem['type'] == 'dzs_row') {
      $fout .= '<section class="dzs-row">';
      continue;

    }
    if ($optionItem['type'] == 'dzs_col_md_6') {
      $fout .= '<section class="dzs-col-md-6">';
      continue;

    }
    if ($optionItem['type'] == 'dzs_col_md_12') {
      $fout .= '<section class="dzs-col-md-6">';
      continue;

    }

    if ($optionItem['type'] == 'dzs_row_end') {
      $fout .= '</section><!--dzs row end-->';
      continue;

    }
    if ($optionItem['type'] == 'dzs_col_md_6_end') {
      $fout .= '</section><!--dzs dzs_col_md_6_end end-->';
      continue;

    }
    if ($optionItem['type'] == 'dzs_col_md_12_end') {
      $fout .= '</section><!--dzs dzs_col_md_12_end end-->';
      continue;

    }
    if ($optionItem['type'] == 'custom_html') {
      $fout .= $optionItem['custom_html'];
      continue;

    }


    $fout .= '<div class="setting ';
    $option_name = $optionItem['name'];



    if ($optionItem['type'] == 'attach') {
      $fout .= ' setting-upload';
    }

    $fout .= '" ';

    $fout .= ' data-option-name="' . $option_name . '"';

    if (isset($optionItem['dependency']) && $optionItem['dependency']) {
      $fout .= ' data-dependency=\'' . json_encode($optionItem['dependency']) . '\'';
    }


    $fout .= '>';


    if ((strpos($option_name, 'item_source') || $option_name == 'source')) {



      $lab_aux = 'dzsap_meta_source_attachment_id';
      $val_aux = '';
      if ($po) {

        $val_aux = get_post_meta($po->ID, $lab_aux, true);
      }


      $class = 'setting-field shortcode-field';

      if ($margs['for_shortcode_generator']) {
        $class .= ' insert-id';
      }

      // -- source

      $class .= $optionItem['input_extra_classes'];

      $fout .= DZSHelpers::generate_input_text($lab_aux, array(
        'class' => $class,
        'seekval' => $val_aux,
        'input_type' => 'hidden',
      ));
    }

    $fout .= '<h5 class="setting-label setting-label-item-meta-cat">' . $optionItem['title'] . '</h5>';


    if ($optionItem['type'] == 'attach') {


      if ($optionItem['no_preview'] != 'on') {
        $fout .= '<span class="uploader-preview"></span>';
      }
    }


    if ($margs['for_shortcode_generator']) {
      $option_name = str_replace('dzsap_meta_item_', '', $option_name);
      $option_name = str_replace('dzsap_meta_', '', $option_name);

      if ($option_name == 'the_post_title') {
        $option_name = 'songname';
      }
    }

    $extraattr_input = '';

    if (isset($optionItem['extraattr_input']) && $optionItem['extraattr_input']) {
      $extraattr_input .= $optionItem['extraattr_input'];
    }

    $val = '';

    if ($po && is_int($po->ID)) {

      $val = get_post_meta($po->ID, $option_name, true);
    }

    if ($po && $option_name == 'the_post_title') {
      $val = $po->post_title;
    }
    if ($po && $option_name == 'post_content') {
      $val = $po->post_content;
    }

    $class = 'setting-field medium';

    if ($optionItem['type'] == 'attach') {
      $class .= ' uploader-target';
    }

    if ($margs['for_shortcode_generator']) {
      $class .= ' shortcode-field';
    }


    $class .= $optionItem['input_extra_classes'];


    if ($optionItem['type'] == 'attach') {


      if (isset($optionItem['upload_type']) && $optionItem['upload_type']) {
        $class .= ' upload-type-' . $optionItem['upload_type'];
      }
      $class .= 'setting-field shortcode-field';

      if ($option_name == 'source' && $margs['for_shortcode_generator']) {
        $class .= ' insert-id';
      }

      $fout .= DZSHelpers::generate_input_text($option_name, array(
        'class' => $class,
        'seekval' => $val,
        'extraattr' => $extraattr_input,
      ));
    }
    if ($optionItem['type'] == 'text') {
      $fout .= DZSHelpers::generate_input_text($option_name, array(
        'class' => $class,
        'seekval' => $val,
        'extraattr' => $extraattr_input,
      ));
    }
    if ($optionItem['type'] == 'textarea') {
      $fout .= DZSHelpers::generate_input_textarea($option_name, array(
        'class' => $class,
        'seekval' => $val,
        'extraattr' => $extraattr_input,
      ));
    }
    if ($optionItem['type'] == 'select') {


      $class = '';

      if (isset($optionItem['class'])) {
        $class .= $optionItem['class'];
      }
      $class .= ' dzs-style-me skin-beige setting-field';

      if (isset($optionItem['select_type']) && $optionItem['select_type']) {
        $class .= ' ' . $optionItem['select_type'];
      }
      if ($margs['for_shortcode_generator']) {
        $class .= ' shortcode-field';
      }

      $fout .= DZSHelpers::generate_select($option_name, array(
        'class' => $class,
        'seekval' => $val,
        'options' => $optionItem['choices'],
        'extraattr' => $extraattr_input,
      ));

      if (isset($optionItem['select_type']) && $optionItem['select_type'] == 'opener-listbuttons') {

        $fout .= '<ul class="dzs-style-me-feeder">';

        foreach ($optionItem['choices_html'] as $oim_html) {

          $fout .= '<li>';
          $fout .= $oim_html;
          $fout .= '</li>';
        }

        $fout .= '</ul>';
      }


    }

    if ($optionItem['type'] == 'attach') {


      if (current_user_can('upload_files')) {
        $fout .= '<div class="dzs-wordpress-uploader here-uploader ">
 <a rel="nofollow" href="#" class="button-secondary';


        if (isset($optionItem['upload_btn_extra_classes']) && $optionItem['upload_btn_extra_classes']) {
          $fout .= ' ' . $optionItem['upload_btn_extra_classes'];
        }

        $fout .= '">' . esc_html__('Upload', 'dzsvp') . '</a>
</div>';


      }

    }

    if (isset($optionItem['sidenote']) && $optionItem['sidenote']) {
      $fout .= '<div class="sidenote">' . $optionItem['sidenote'] . '</div>';
    }


    if (isset($optionItem['sidenote-2']) && $optionItem['sidenote-2']) {


      $sidenote_2_class = '';

      if (isset($optionItem['sidenote-2-class'])) {
        $sidenote_2_class = $optionItem['sidenote-2-class'];
      }
      $fout .= '<div class="sidenote-2 ' . $sidenote_2_class . '">' . $optionItem['sidenote-2'] . '</div>';
    }


    $fout .= '
                    </div>';


  }


  return $fout;
}


function dzsap_sliders_admin_init() {

  global $dzsap, $pagenow;
  $taxonomyName = DZSAP_TAXONOMY_NAME_SLIDERS;

  if (isset($_GET['taxonomy']) && $_GET['taxonomy'] === DZSAP_TAXONOMY_NAME_SLIDERS && isset($_GET['post_type']) && $_GET['post_type'] === DZSAP_REGISTER_POST_TYPE_NAME) {

    add_action('pre_get_posts', 'dzsap_slidersAdmin_fixHackConfictsForOrder', 1000);
  }


  if ((isset($_REQUEST['action']) && 'dzsap_duplicate_slider_term' == $_REQUEST['action'])) {

    if (!(isset($_GET['term_id']) || isset($_POST['term_id']))) {
      wp_die("no term_id set");
    }


    /*
     * get the original post id
     */
    $term_id = (isset($_GET['term_id']) ? absint($_GET['term_id']) : absint($_POST['term_id']));

    $term_meta = get_option("taxonomy_$term_id");

    /*
    $current_user = wp_get_current_user();
    $new_post_author = $current_user->ID;



     * Nonce verification
     */
    if (isset($_GET['duplicate-nonce-for-term-id-' . $term_id]) && wp_verify_nonce($_GET['duplicate-nonce-for-term-id-' . $term_id], 'duplicate-nonce-for-term-id-' . $term_id)) {


      $argsForImport = array(
        'post_type' => 'dzsap_items',
        'tax_query' => array(
          array(
            'taxonomy' => DZSAP_TAXONOMY_NAME_SLIDERS,
            'field' => 'id',
            'terms' => $term_id
          )
        ),
      );
      $query = new WP_Query($argsForImport);


      $reference_term = get_term($term_id, $taxonomyName);


      $reference_term_name = $reference_term->name;
      $reference_term_slug = $reference_term->slug;






      $new_term_name = $reference_term_name . ' ' . esc_html("Copy", DZSAP_ID);
      $new_term_slug = $reference_term_slug . '-copy';
      $original_slug_name = $reference_term_slug . '-copy';


      $ind = 1;
      $breaker = 100;
      while (1) {

        $term = term_exists($new_term_slug, $taxonomyName);
        if ($term !== 0 && $term !== null) {

          $ind++;
          $new_term_slug = $original_slug_name . '-' . $ind;
        } else {
          break;
        }

        $breaker--;

        if ($breaker < 0) {
          break;
        }
      }


      $new_term = wp_insert_term(
        $new_term_name, // the term
        $taxonomyName, // the taxonomy
        array(

          'slug' => $new_term_slug,
        )
      );


      foreach ($query->posts as $po) {


        $dzsap->ajax_functions->duplicate_post($po->ID, array(
          'new_term_slug' => $new_term_slug,
          'call_from' => 'default',
          'new_tax' => $taxonomyName,
        ));


      }




      $new_term_id = $new_term['term_id'];


      update_option("taxonomy_$new_term_id", $term_meta);
      wp_redirect(admin_url('term.php?taxonomy=' . $taxonomyName . '&tag_ID=' . $new_term_id . '&post_type=dzsap_items'));

      exit;



    } else {
      $aux = ('invalid nonce for term_id' . $term_id . 'duplicate-nonce-for-term-id-' . $term_id);

      $aux .= print_rr($_SESSION);

      $aux .= ' searched nonce - ' . $_GET['duplicate-nonce-for-term-id-' . $term_id];
      $aux .= ' searched nonce verify - ' . wp_verify_nonce($_GET['duplicate-nonce-for-term-id-' . $term_id], 'duplicate-nonce-for-term-id-' . $term_id);


      wp_die($aux);
    }
  }


  // -- export
  if ((isset($_REQUEST['action']) && 'dzsap_export_slider_term' == $_REQUEST['action'])) {


    /*
     * get the original post id
     */
    $term_id = (isset($_GET['term_id']) ? absint($_GET['term_id']) : absint($_POST['term_id']));


    $arr_export = $dzsap->classAdmin->playlist_export($term_id, array(
      'download_export' => true
    ));
    echo json_encode($arr_export);
    die();


    exit;

  }


  // -- import

  if (isset($_POST['action']) && $_POST['action'] == 'dzsap_import_slider') {

    if (isset($_FILES['dzsap_import_slider_file'])) {

      $file_arr = $_FILES['dzsap_import_slider_file'];

      $file_cont = file_get_contents($file_arr['tmp_name'], true);


      $type = 'none';


      try {
        $sliderObj = json_decode($file_cont, true);

        error_log('content - ' . print_rr($sliderObj, true));

        if ($sliderObj && is_array($sliderObj)) {

          $type = 'json';
        } else {
          $sliderObj = unserialize($file_cont);


          error_log('content - ' . print_rr($sliderObj, true));
          $type = 'serial';
        }

        if (is_array($sliderObj)) {
          if ($type == 'json') {


            $reference_term_name = $sliderObj['original_term_name'];
            $reference_term_slug = $sliderObj['original_term_slug'];




            $original_name = $reference_term_name;
            $original_slug = $reference_term_slug;


            $new_term_slug = $reference_term_slug;
            $new_term_name = $reference_term_name;


            $ind = 1;
            $breaker = 100;


            $term = term_exists($new_term_name, $taxonomyName);
            if ($term !== 0 && $term !== null) {


              $new_term_name = $original_name . '-' . $ind;
              $new_term_slug = $original_slug . '-' . $ind;
              $ind++;


              while (1) {

                $term = term_exists($new_term_name, $taxonomyName);
                if ($term !== 0 && $term !== null) {

                  $new_term_name = $original_name . '-' . $ind;
                  $new_term_slug = $original_slug . '-' . $ind;
                  $ind++;
                } else {
                  break;
                }

                $breaker--;

                if ($breaker < 0) {
                  break;
                }
              }

            } else {


            }


            $new_term = wp_insert_term(
              $new_term_name, // the term
              $taxonomyName, // the taxonomy
              array(

                'slug' => $new_term_slug,
              )
            );


            $new_term_id = '';
            if (is_array($new_term)) {

              $new_term_id = $new_term['term_id'];
            } else {
              error_log(' .. the name is ' . $new_term_name);
              error_log(print_r($new_term, true));
            }


            $term_meta = array_merge(array(), $sliderObj['term_meta']);

            unset($term_meta['items']);

            update_option("taxonomy_$new_term_id", $term_meta);


            foreach ($sliderObj['items'] as $po) {

              $argsForImport = array_merge(array(), $po);

              $argsForImport['term'] = $new_term_slug;
              $argsForImport['taxonomy'] = $taxonomyName;

              $argsForImport['call_from'] = 'sliders_admin import slider_file';
              $dzsap->ajax_functions->import_demo_insert_post_complete($argsForImport);


            }






          }


          // -- legacy
          if ($type == 'serial') {


            $new_term_id = '';
            $new_term = null;
            $original_slug = '';


            if (isset($sliderObj['settings'])) {

              $sliderSettings = $sliderObj['settings'];
              $reference_term_name = $sliderSettings['id'];
              $reference_term_slug = $sliderSettings['id'];

              $original_name = $reference_term_name;
              $original_slug = $reference_term_slug;


              $new_term_slug = $reference_term_slug;
              $new_term_name = $reference_term_name;


              $ind = 1;
              $breaker = 100;


              $term = term_exists($new_term_slug, $taxonomyName);
              if ($term !== 0 && $term !== null) {
                while (1) {

                  $term = term_exists($new_term_slug, $taxonomyName);
                  if ($term !== 0 && $term !== null) {

                    $ind++;
                    $new_term_slug = $original_slug . '-' . $ind;
                  } else {
                    break;
                  }

                  $breaker--;

                  if ($breaker < 0) {
                    break;
                  }
                }

                $ind++;
                $new_term_name = $original_name . '-' . $ind;
                $new_term_slug = $original_slug . '-' . $ind;
              }


              $new_term = wp_insert_term(
                $new_term_name, // the term
                $taxonomyName, // the taxonomy
                array(
                  'slug' => $new_term_slug,
                )
              );


              if (is_array($new_term)) {

                $new_term_id = $new_term['term_id'];
              } else {
                error_log(' .. the name is ' . $new_term_name);
                error_log(print_r($new_term, true));
              }


              $new_term_id = null;

              if (isset($new_term['term_id'])) {
                $new_term_id = $new_term['term_id'];
              }


              if($new_term_id){

                $term_meta = array_merge(array(), $sliderSettings);

                unset($term_meta['items']);


                update_option("taxonomy_$new_term_id", $term_meta);

                foreach ($sliderObj as $lab => $songItem) {
                  if ($lab === 'settings') {
                    continue;
                  } else {
                    // -- item

                    $argsForImport = array_merge(array(), $songItem);



                    $argsForImport['term'] = $new_term_slug;
                    $argsForImport['taxonomy'] = $taxonomyName;

                    $source = $songItem['source'];
                    $artistName = '';
                    $songTitle = $original_slug . '-' . $lab;
                    if(isset($songItem['menu_songname'])){
                      $songTitle = $songItem['menu_songname'];
                    }
                    if(isset($songItem['menu_artistname'])){
                      $artistName = $songItem['menu_artistname'];
                    }
                    if(!$source){
                      if(isset($songItem['linktomediafile']) && $songItem['linktomediafile']){
                        $source = $songItem['linktomediafile'];
                      }
                    }

                    if(isset($songItem['thumb']) && $songItem['thumb']){
                      $argsForImport['dzsap_meta_item_thumb'] = $songItem['thumb'];
                    }

                    $argsForImport['dzsap_meta_item_source'] = $source;
                    $argsForImport['post_name'] = $songTitle;
                    $argsForImport['post_title'] = $songTitle;
                    $argsForImport['dzsap_meta_artistname'] = $artistName;






                    foreach ($dzsap->options_item_meta as $oim) {
                      if (isset($oim['name'])) {
                        $long_name = $oim['name'];
                        $short_name = str_replace('dzsap_meta_', '', $oim['name']);
                        if (isset($argsForImport[$short_name])) {
                          $argsForImport[$long_name] = $argsForImport[$short_name];
                        }
                      }
                    }


                    $dzsap->ajax_functions->import_demo_insert_post_complete($argsForImport);

                  }


                }
              }

            }


          }
        }
      } catch
      (Exception $err) {
        print_rr($err);
      }
    }
  }
}


function dzsap_action_dzsap_duplicate_slider_term() {


}

function dzsap_sliders_admin_duplicate_post_link($actions, $term) {


  if (current_user_can('edit_posts')) {


    // Create an nonce, and add it as a query var in a link to perform an action.
    $nonce = wp_create_nonce('duplicate-nonce-for-term-id-' . $term->term_id);

    $actions['duplicate'] = '<a href="' . admin_url('edit-tags.php?taxonomy=dzsap_sliders&post_type=dzsap_items&action=dzsap_duplicate_slider_term&term_id=' . $term->term_id) . '&duplicate-nonce-for-term-id-' . ($term->term_id) . '=' . $nonce . '" title="Duplicate this item" rel="permalink">' . esc_html("Duplicate", DZSAP_ID) . '</a>';
  }


  $actions['export'] = '<a href="' . admin_url('edit-tags.php?taxonomy=dzsap_sliders&post_type=dzsap_items&action=dzsap_export_slider_term&term_id=' . $term->term_id) . '" title="Duplicate this item" rel="permalink">' . esc_html("Export", DZSAP_ID) . '</a>';


  return $actions;
}


function dzsap_sliders_admin() {

  $dzsapSlidersAdmin = new SlidersAdmin(array(
    'target_taxonomy' => DZSAP_TAXONOMY_NAME_SLIDERS,
    'target_post_type' => DZSAP_REGISTER_POST_TYPE_NAME,
  ));
  $dzsapSlidersAdmin->render_sliders();
}


function dzsap_sliders_admin_add_feature_group_field($term) {




  global $dzsap;





  $dzsap->options_slider = include DZSAP_BASE_PATH . 'configs/playlist-options.php';


  $dzsap->options_slider_categories_lng = array(
    'appearence' => esc_html__("Appearance", DZSAP_ID),
    'menu' => esc_html__("Menu", DZSAP_ID),
    'autoplay' => esc_html__("Play Options", DZSAP_ID),
    'counters' => esc_html__("Counters", DZSAP_ID),
  );





  $i23 = 0;
  foreach ($dzsap->mainitems_configs as $vpconfig) {



    $aux = array(
      'label' => $vpconfig['settings']['id'],
      'value' => $vpconfig['settings']['id'],
    );




    foreach ($dzsap->options_slider as $lab => $so) {

      if ($so['name'] == 'vpconfig') {




        array_push($dzsap->options_slider[$lab]['options'], $aux);

        break;
      }
    }


    $i23++;
  }


  dzsap_sliders_admin_parse_options($term, 'main');


}

function dzsap_sliders_admin_parse_options($term, $cat = 'main') {

  global $dzsap;
  $indtem = 0;


  $t_id = $term->term_id;

  // retrieve the existing value(s) for this meta field. This returns an array
  $term_meta = get_option("taxonomy_$t_id");




  // -- we need real location, not insert-id

  $struct_uploader = '<div class="dzs-wordpress-uploader ">
<a href="#" class="button-secondary">' . esc_html__('Upload', 'dzsvp') . '</a>
</div>';

  foreach ($dzsap->options_slider as $sliderOption) {






    if ($cat == 'main') {

      if (isset($sliderOption['category']) == false || (isset($sliderOption['category']) && $sliderOption['category'] == 'main')) {

      } else {
        continue;
      }
    } else {

      if ((isset($sliderOption['category']) && $sliderOption['category'] == $cat)) {

      } else {
        continue;
      }
    }
    if (!isset($sliderOption['title'])) {
      continue;
    }
    if ($indtem % 2 === 0) {



    }


    if (isset($sliderOption['choices'])) {
      $sliderOption['options'] = $sliderOption['choices'];
    }

    if (isset($sliderOption['sidenote'])) {
      $sliderOption['description'] = $sliderOption['sidenote'];
    }
    ?>
    <tr class="form-field" <?php


    if (isset($sliderOption['dependency'])) {

      echo ' data-dependency=\'' . json_encode($sliderOption['dependency']) . '\'';
    }

    ?>>
      <th scope="row" valign="top"><label
          for="term_meta[<?php echo $sliderOption['name']; ?>]"><?php echo $sliderOption['title']; ?></label></th>
      <td class="<?php
      if ($sliderOption['type'] == 'media-upload') {
        echo 'setting-upload';
      }
      ?>">


        <?php
        // -- main options

        if ($sliderOption['type'] == 'media-upload' || $sliderOption['type'] == 'color') {
          echo '<div class="uploader-three-floats">';
        }

        if ($sliderOption['type'] == 'media-upload') {
          echo '<span class="uploader-preview"></span>';
        }
        ?>



        <?php
        $lab = 'term_meta[' . $sliderOption['name'] . ']';

        $val = '';

        if (isset($term_meta[$sliderOption['name']])) {

          $val = esc_attr($term_meta[$sliderOption['name']]) ? esc_attr($term_meta[$sliderOption['name']]) : '';
        }

        $class = 'setting-field medium';


        if ($sliderOption['type'] == 'media-upload') {
          $class .= ' uploader-target';
        }

        if ($sliderOption['type'] == 'color') {
          $class .= ' wp-color-picker-init';
        }
        if ($sliderOption['type'] == 'media-upload' || $sliderOption['type'] == 'text' || $sliderOption['type'] == 'input' || $sliderOption['type'] == 'color') {


          if ($sliderOption['type'] == 'color') {
            $class .= ' with_colorpicker';
          }

          echo DZSHelpers::generate_input_text($lab, array(
            'class' => $class,
            'seekval' => $val,
            'id' => $lab,
          ));

        }


        if ($sliderOption['type'] == 'select') {




          $class .= ' dzs-style-me skin-beige';

          if (isset($sliderOption['select_type'])) {
            $class .= ' ' . $sliderOption['select_type'];
          }
          if (isset($sliderOption['extra_classes'])) {
            $class .= ' ' . $sliderOption['extra_classes'];
          }
          $class .= ' dzs-dependency-field';
          echo DZSHelpers::generate_select($lab, array(
            'class' => $class,
            'options' => $sliderOption['options'],
            'seekval' => $val,
            'id' => $lab,
          ));


          if (isset($sliderOption['select_type']) && $sliderOption['select_type'] == 'opener-listbuttons') {

            echo '<ul class="dzs-style-me-feeder">';

            foreach ($sliderOption['choices_html'] as $oim_html) {

              echo '<li>';
              echo $oim_html;
              echo '</li>';
            }

            echo '</ul>';


          }
        }

        if ($sliderOption['type'] == 'color') {



          echo '<div class="picker-con"><div class="the-icon"></div><div class="picker"></div></div>';
        }


        // -- media upload
        if ($sliderOption['type'] == 'media-upload') {

          echo '<div class="dzs-wordpress-uploader here-uploader ">
<a href="#" class="button-secondary';


          if (isset($sliderOption['upload_btn_extra_classes']) && $sliderOption['upload_btn_extra_classes']) {
            echo ' ' . $sliderOption['upload_btn_extra_classes'];
          }


          echo '">' . esc_html__('Upload', 'dzsvp') . '</a>
</div>';

        }
        ?>
        <?php


        if ($sliderOption['type'] == 'media-upload' || $sliderOption['type'] == 'color') {
          echo '</div><!-- end uploader three floats -->';
        }

        $description = '';
        if (isset($sliderOption['description'])) {
          $description = $sliderOption['description'];
        }

        if ($description) {
          ?>
          <p class="description"><?php echo $description; ?></p>
          <?php


        }
        ?>
      </td>
    </tr>
    <?php

    $indtem++;
  }

}