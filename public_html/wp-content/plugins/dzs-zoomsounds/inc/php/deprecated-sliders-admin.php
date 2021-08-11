<div class="wrap">
  <div class="import-export-db-con">
    <div class="the-toggle"></div>
    <div class="the-content-mask" style="">

      <div class="the-content">
        <h2><?php echo esc_html__("Whole Database"); ?></h2>
        <form enctype="multipart/form-data" action="" method="POST">

          <div class="">
            <h3><?php echo esc_html__("Import Whole Database"); ?></h3>
            <input name="dzsap_importdbupload" type="file" size="10"/><br/>
          </div>
          <div class="">
            <input class="button-secondary" type="submit" name="dzsap_importdb" value="Import"/>
          </div>
          <div class="clear"></div>
        </form>


        <div class="">
          <h3><?php echo esc_html__("Export Whole Database"); ?></h3>
        </div>
        <div class="">
          <form action="" method="POST"><input class="button-secondary" type="submit" name="dzsap_exportdb"
                                               value="Export"/></form>
        </div>
        <br>
        <br>
        <h1><?php echo esc_html__("OR"); ?></h1>
        <br>
        <br>


        <h2><?php echo esc_html__("Single Slider"); ?></h2>


        <form enctype="multipart/form-data" action="" method="POST">
          <div class="">
            <h3><?php echo esc_html__("Import a Single Slider"); ?></h3>
            <input name="importsliderupload" type="file" size="10"/><br/>
          </div>
          <div class="">
            <input class="button-secondary" type="submit" name="dzsap_importslider" value="Import"/>
          </div>
          <div class="clear"></div>
        </form>

      </div>
    </div>
  </div>
  <h2>DZS <?php echo esc_html__('ZoomSounds Admin', 'dzsap'); ?>&nbsp; <span
      style="font-size:13px; font-weight: 100;">version <?php echo DZSAP_VERSION; ?></span> <img alt=""
                                                                                                 style="visibility: visible;"
                                                                                                 id="main-ajax-loading"
                                                                                                 src="<?php bloginfo('wpurl'); ?>/wp-admin/images/wpspin_light.gif"/>
  </h2>
  <noscript><?php echo esc_html__('You need javascript for this.', 'dzsap'); ?></noscript>
  <div class="top-buttons">
    <a href="<?php echo DZSAP_BASE_URL; ?>readme/index.html"
       class="button-secondary action"><?php echo esc_html__('Documentation', 'dzsap'); ?></a>
    <div class="super-select db-select dzsap">
      <button class="button-secondary btn-show-dbs">Current Database - <span class="strong currdb"><?php
          if ($dzsap->currDb == '') {
            echo 'main';
          } else {
            echo $dzsap->currDb;
          }
          ?></span></button>
      <select class="main-select hidden"><?php


        if (is_array($dzsap->dbs)) {
          foreach ($dzsap->dbs as $adb) {
            $params = array('dbname' => $adb);
            $newurl = add_query_arg($params, dzs_curr_url());
            echo '<option' . ' data-newurl="' . $newurl . '"' . '>' . $adb . '</option>';
          }
        } else {
          $params = array('dbname' => 'main');
          $newurl = add_query_arg($params, dzs_curr_url());
          echo '<option' . ' data-newurl="' . $newurl . '"' . ' selected="selected"' . '>' . ('unknown') . '</option>';
        }
        ?></select>
      <div class="hidden replaceurlhelper"><?php
        $params = array('dbname' => 'replaceurlhere');
        $newurl = add_query_arg($params, dzs_curr_url());
        echo $newurl;
        ?></div>
    </div>
  </div>
  <table cellspacing="0" class="wp-list-table widefat dzs_admin_table main_sliders">
    <thead>
    <tr>
      <th style="" class="manage-column column-name" id="name" scope="col"><?php echo esc_html__('ID', 'dzsap'); ?></th>
      <th class="column-edit">Edit</th>
      <th class="column-edit">Embed</th>
      <th class="column-edit">Export</th>
      <th class="column-edit">Duplicate</th>
      <?php
      if ($dzsap->mainoptions['is_safebinding'] != 'on') {
        ?>
        <?php
      }
      ?>
      <th class="column-edit">Delete</th>
    </tr>
    </thead>
    <tbody>
    </tbody>
  </table>
  <?php
  $url_add = '';
  $url_add = '';
  $items = $dzsap->mainitems;


  $aux = remove_query_arg('deleteslider', admin_url('admin.php?page=' . DZSAP_ADMIN_PAGENAME_LEGACY_SLIDERS_ADMIN_SLIDERS . '&adder=adder'));

  $nextslidernr = count($items);
  if ($nextslidernr < 1) {

  }
  $params = array('currslider' => $nextslidernr);
  $url_add = add_query_arg($params, $aux);
  ?>
  <a class="button-secondary add-slider"
     href="<?php echo $url_add; ?>"><?php echo esc_html__('Add Playlist', 'dzsap'); ?></a>
  <form class="master-settings">
  </form>
  <div class="saveconfirmer"><?php echo esc_html__('Loading...', 'dzsap'); ?></div>
  <a href="#" class="button-primary master-save"></a> <img alt=""
                                                           style="position:fixed; bottom:18px; right:125px; visibility: hidden;"
                                                           id="save-ajax-loading"
                                                           src="<?php bloginfo('wpurl'); ?>/wp-admin/images/wpspin_light.gif"/>

  <a href="#" class="button-primary master-save"><?php echo esc_html__('Save All Galleries', 'dzsap'); ?></a>
  <a href="#" class="button-primary slider-save"><?php echo esc_html__('Save Gallery', 'dzsap'); ?></a>
</div>
<?php
ob_start();
  $aux = str_replace(array("\r", "\r\n", "\n"), '', $dzsap->sliderstructure);
  $aux = str_replace(array("'"), '&quot;', $aux);
  echo "var sliderstructure = '" . $aux . "';
    ";
  $aux = str_replace(array("\r", "\r\n", "\n"), '', $dzsap->itemstructure);
  $aux = str_replace(array("'"), '&quot;', $aux);
  echo "var itemstructure = '" . $aux . "';
    ";

  ?>
jQuery(document).ready(function ($) {
sliders_ready($);
if (jQuery.fn.multiUploader) {
jQuery('.dzs-multi-upload').multiUploader();
}
<?php
$items = $dzsap->mainitems;
for ($i = 0; $i < count($items); $i++) {

  $aux = '';
  if (isset($items[$i]) && isset($items[$i]['settings']) && isset($items[$i]['settings']['id'])) {


    $items[$i]['settings']['id'] = str_replace('"', '', $items[$i]['settings']['id']);
    $aux = '{ name: "' . $items[$i]['settings']['id'] . '"}';
  }

  echo "dzs_legacy_slidersAddSlider(" . $aux . ");";
}
if (count($items) > 0)
  echo 'dzs_legacy_slidersShowSlider(0);';
for ($i = 0; $i < count($items); $i++) {

  if (($dzsap->mainoptions['is_safebinding'] != 'on' || $i == $dzsap->currSlider) && is_array($items[$i])) {

    // -- jsi is the javascript I, if safebinding is on then the jsi is always 0 ( only one gallery )
    $jsi = $i;
    if ($dzsap->mainoptions['is_safebinding'] == 'on') {
      $jsi = 0;
    }

    for ($j = 0; $j < count($items[$i]) - 1; $j++) {
      echo "dzs_legacy_slidersAddItem(" . $jsi . ");";
    }

    foreach ($items[$i] as $label => $value) {
      if ($label === 'settings') {
        if (is_array($items[$i][$label])) {
          foreach ($items[$i][$label] as $sublabel => $subvalue) {
            $subvalue = (string)$subvalue;
            $subvalue = stripslashes($subvalue);
            $subvalue = str_replace(array("\r", "\r\n", "\n", '\\', "\\"), '', $subvalue);
            $subvalue = str_replace(array("'"), '"', $subvalue);
            $subvalue = str_replace(array("</script>"), '{{scriptend}}', $subvalue);

            echo 'dzs_legacy_slidersChange(' . $jsi . ', "settings", "' . $sublabel . '", ' . "'" . $subvalue . "'" . ');';
          }
        }
      } else {

        if (is_array($items[$i][$label])) {
          foreach ($items[$i][$label] as $sublabel => $subvalue) {
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
jQuery('.master-save').remove();
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