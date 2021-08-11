<?php


function dzsap_admin_page_autoupdater() {

  global $dzsap;
  ?>
  <div class="wrap">


    <?php

    $auxarray = array();


    if (isset($_GET['dzsap_purchase_remove_binded']) && $_GET['dzsap_purchase_remove_binded'] == 'on') {

      $dzsap->mainoptions['dzsap_purchase_code_binded'] = 'off';

      update_option(DZSAP_DBNAME_OPTIONS, $dzsap->mainoptions);

    }

    if (isset($_POST['action']) && $_POST['action'] === 'dzsap_update_request') {


      if (isset($_POST['dzsap_purchase_code'])) {
        $auxarray = array('dzsap_purchase_code' => $_POST['dzsap_purchase_code']);
        $auxarray = array_merge($dzsap->mainoptions, $auxarray);

        $dzsap->mainoptions = $auxarray;


        update_option(DZSAP_DBNAME_OPTIONS, $auxarray);
      }


    }

    $extra_class = '';
    $extra_attr = '';
    $form_method = "POST";
    $form_action = "";
    $disable_button = '';

    $lab = 'dzsap_purchase_code';

    if ($dzsap->mainoptions['dzsap_purchase_code_binded'] == 'on') {
      $extra_attr = ' disabled';
      $disable_button = ' <input type="hidden" name="purchase_code" value="' . $dzsap->mainoptions[$lab] . '"/><input type="hidden" name="site_url" value="' . site_url() . '"/><input type="hidden" name="redirect_url" value="' . esc_url(add_query_arg('dzsap_purchase_remove_binded', 'on', dzs_curr_url())) . '"/><button class="button-secondary" name="action" value="dzsap_purchase_code_disable">' . esc_html__("Disable Key") . '</button>';
      $form_action = ' action="https://zoomthe.me/updater_dzsap/servezip.php"';
    }


    echo '<form' . $form_action . ' class="mainsettings" method="' . $form_method . '">';

    echo '
                <div class="setting">
                    <div class="label">' . esc_html__('Purchase Code', 'dzsap') . '</div>
                    ' . dzsap_misc_input_text($lab, array('val' => '', 'seekval' => $dzsap->mainoptions[$lab], 'class' => $extra_class, 'extra_attr' => $extra_attr)) . $disable_button . '
                    <div class="sidenote">' . dzs_esc__(__("You can %sfind it here%s ", DZSAP_ID), array('<a href="https://lh5.googleusercontent.com/-o4WL83UU4RY/Unpayq3yUvI/AAAAAAAAJ_w/HJmso_FFLNQ/w786-h1179-no/puchase.jpg" target="_blank">', '</a>')) . '</div>
                </div>';


    if ($dzsap->mainoptions['dzsap_purchase_code_binded'] == 'on') {
      echo '</form><form class="mainsettings" method="post">';
    }

    echo '<p><button class="button-primary" name="action" value="dzsap_update_request">' . esc_html__("Update") . '</button></p>';


    ?>
    </form>
  </div>
  <?php


  if (isset($_POST['action']) && $_POST['action'] === 'dzsap_update_request') {

    DZSZoomSoundsHelper::autoupdaterUpdate();
  }

}