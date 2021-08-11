<?php


function dzsap_admin_page_vpc() {
  global $dzsap;


  $id_for_preview_player = 'newconfig';

  /** @var number $targetSliderId */
  $targetSliderId = null;
  $targetSliderId = $dzsap->currSlider;


  // -- sliders admin video config
  ?>
  <div class="wrap <?php

  if (isset($_GET[DZSAP_LEGACY_SLIDERS__GET_CURRSLIDER_FINDER])) {
    echo 'legacySliders--found-by-slug';
  }

  ?>">
    <div class="import-export-db-con">
      <div class="the-toggle"></div>
      <div class="the-content-mask" style="">

        <div class="the-content">


          <form enctype="multipart/form-data" action="" method="POST">
            <div class="one_half">
              <h3><?php echo esc_html__('Import config', 'dzsap'); ?></h3>
              <input name="importsliderupload" type="file" size="10"/><br/>
            </div>
            <div class="one_half last alignright">
              <input class="button-secondary" type="submit" name="dzsap_import_config" value="Import"/>
            </div>
            <div class="clear"></div>
          </form>


          <div class="clear"></div>

        </div>
      </div>
    </div>
    <h2>DZS <?php echo esc_html__('ZoomSounds Admin', 'dzsap'); ?> <img alt="" style="visibility: visible;"
                                                                        id="main-ajax-loading"
                                                                        src="<?php bloginfo('wpurl'); ?>/wp-admin/images/wpspin_light.gif"/>
    </h2>
    <noscript><?php echo esc_html__('You need javascript for this.', 'dzsap'); ?></noscript>
    <div class="top-buttons">
      <a rel="nofollow" href="<?php echo DZSAP_BASE_URL; ?>readme/index.html"
         class="button-secondary action"><?php echo esc_html__('Documentation', 'dzsap'); ?></a>

    </div>
    <table cellspacing="0" class="wp-list-table widefat dzs_admin_table main_sliders">
      <thead>
      <tr>
        <th style="" class="manage-column column-name" id="name"
            scope="col"><?php echo esc_html__('ID', 'dzsap'); ?></th>
        <th class="column-edit"><?php echo esc_html__("Edit", 'dzsap'); ?></th>
        <th class="column-edit"><?php echo esc_html__("Embed", 'dzsap'); ?></th>
        <th class="column-edit"><?php echo esc_html__("Export", 'dzsap'); ?></th>
        <th class="column-edit"><?php echo esc_html__("Duplicate", 'dzsap'); ?></th>

        <th class="column-edit">Delete</th>
      </tr>
      </thead>
      <tbody>
      </tbody>
    </table>
    <?php
    $url_add = '';
    $url_add = '';
    $vpConfigs = $dzsap->mainitems_configs;


    $aux = remove_query_arg('deleteslider', dzs_curr_url());
    $aux = admin_url('admin.php?page=' . DZSAP_ADMIN_PAGENAME_LEGACY_SLIDERS_ADMIN_VPCONFIGS . '&adder=adder');
    $params = array('currslider' => count($vpConfigs));
    $url_add = add_query_arg($params, $aux);


    // -- if NOT then it is
    if (isset($vpConfigs[$targetSliderId]['settings']['id'])) {
      $id_for_preview_player = ($vpConfigs[$targetSliderId]['settings']['id']);
    }
    ?>
    <a rel="nofollow" class="button-secondary add-slider"
       href="<?php echo $url_add; ?>"><?php echo esc_html__('Add Configuration', 'dzsap'); ?></a>
    <form class="master-settings only-settings-con mode_vpconfigs">
    </form>
    <div class="saveconfirmer"><?php echo esc_html__('Loading...', 'dzsap'); ?></div>
    <a rel="nofollow" href="#" class="button-primary master-save-vpc"></a> <img alt=""
                                                                                style="position:fixed; bottom:18px; right:125px; visibility: hidden;"
                                                                                id="save-ajax-loading"
                                                                                src="<?php bloginfo('wpurl'); ?>/wp-admin/images/wpspin_light.gif"/>

    <a rel="nofollow" href="#"
       class="button-primary master-save-vpc"><?php echo esc_html__('Save All Configs', 'dzsap'); ?></a>
    <a rel="nofollow" href="#"
       class="button-primary slider-save-vpc"><?php echo esc_html__('Save Config', 'dzsap'); ?></a>


  </div>
  <?php

  ob_start();


  $aux = str_replace(array("\r", "\r\n", "\n"), '', $dzsap->videoplayerconfig);
  $aux = str_replace(array("'"), '&quot;', $aux);
  echo "var videoplayerconfig = '" . $aux . "';
    ";
  ?>
  'use strict';
  jQuery(document).ready(function ($) {
  sliders_ready($);
  if ($.fn.multiUploader) {
  $('.dzs-multi-upload').multiUploader();
  }
  <?php
  $vpConfigs = $dzsap->mainitems_configs;

  for ($i = 0; $i < count($vpConfigs); $i++) {


    $aux = '';
    if (isset($vpConfigs[$i]) && isset($vpConfigs[$i]['settings']) && isset($vpConfigs[$i]['settings']['id'])) {

      if ($vpConfigs[$i]['settings']['id'] == 'called_from_vpconfig_admin_preview') {
        continue;
      }
      $vpConfigs[$i]['settings']['id'] = str_replace('"', '', $vpConfigs[$i]['settings']['id']);
      $aux = '{ name: "' . $vpConfigs[$i]['settings']['id'] . '"}';
    }
    echo "dzs_legacy_slidersAddSlider(" . $aux . ");";
  }
  if (count($vpConfigs) > 0)
    echo 'dzs_legacy_slidersShowSlider(0);';
  for ($i = 0; $i < count($vpConfigs); $i++) {
    if (($dzsap->mainoptions['is_safebinding'] != 'on' || $i == $targetSliderId) && isset($vpConfigs[$i]) && is_array($vpConfigs[$i])) {

      //==== jsi is the javascript I, if safebinding is on then the jsi is always 0 ( only one gallery )
      $jsi = $i;
      if ($dzsap->mainoptions['is_safebinding'] == 'on') {
        $jsi = 0;
      }

      for ($j = 0; $j < count($vpConfigs[$i]) - 1; $j++) {
        echo "dzs_legacy_slidersAddItem(" . $jsi . ");";
      }


      foreach ($vpConfigs[$i] as $label => $value) {
        if ($label === 'settings') {
          if (is_array($vpConfigs[$i][$label])) {
            foreach ($vpConfigs[$i][$label] as $sublabel => $subvalue) {
              $subvalue = (string)$subvalue;
              $subvalue = stripslashes($subvalue);
              $subvalue = str_replace(array("\r", "\r\n", "\n", '\\', "\\"), '', $subvalue);
              $subvalue = str_replace(array("'"), '"', $subvalue);
              $subvalue = str_replace(array("</script>"), '{{scriptend}}', $subvalue);
              // -- only settings
              echo 'dzs_legacy_slidersChange(' . $jsi . ', "settings", "' . $sublabel . '", ' . "'" . $subvalue . "'" . ');';
            }
          }
        } else {

          if (is_array($vpConfigs[$i][$label])) {
            foreach ($vpConfigs[$i][$label] as $sublabel => $subvalue) {
              $subvalue = (string)$subvalue;
              $subvalue = stripslashes($subvalue);
              $subvalue = str_replace(array("\r", "\r\n", "\n", '\\', "\\"), '', $subvalue);
              $subvalue = str_replace(array("'"), '"', $subvalue);
              $subvalue = str_replace(array("</script>"), '{{scriptend}}', $subvalue);
              if ($label == '') {
                $label = '0';
              }


              echo 'dzs_legacy_slidersChange(' . $jsi . ', ' . $label . ', "' . $sublabel . '", ' . "'" . $subvalue . "'" . ');';
            }
          }
        }
      }
      if ($dzsap->mainoptions['is_safebinding'] == 'on') {
        break;
      }
    }
  }
  ?>
  jQuery('#main-ajax-loading').css('visibility', 'hidden');
  if (dzsap_settings.is_safebinding == "on") {
  jQuery('.master-save-vpc').remove();
  if (dzsap_settings.addslider == "on") {

  dzs_legacy_slidersAddSlider();
  window.currSlider_nr = -1
  dzs_legacy_slidersShowSlider(0);
  }
  jQuery('.slider-in-table').each(function () {

  });
  }
  dzs_legacy_slidersViewWarningTooMany();
  sliders_allready();
  });

  <?php
  $scriptString = ob_get_clean();


  wp_register_script('dzsap-script-hook', '');
  wp_enqueue_script('dzsap-script-hook');
  wp_add_inline_script('dzsap-script-hook', $scriptString);
}