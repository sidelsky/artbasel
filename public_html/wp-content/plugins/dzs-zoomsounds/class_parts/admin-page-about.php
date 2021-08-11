<?php


DZSZoomSoundsHelper::enqueueUltibox();


$role = get_role('administrator');


if ($role) {
  $role->add_cap('dzsap_make_shortcode');
  $role->add_cap('dzsap_manage_options');
  $role->add_cap(DZSAP_TAXONOMY_NAME_SLIDERS . '_manage_categories');
  $role->add_cap('dzsap_manage_vpconfigs');
  $role->add_cap('read_' . DZSAP_REGISTER_POST_TYPE_NAME);
  $role->add_cap('edit_dzsap_items');
  $role->add_cap('edit_others_' . DZSAP_REGISTER_POST_TYPE_NAME);
}
?>


  <div id="loading-activation" class="feed-ultibox show-in-ultibox">

    <div class="dzs-center-flex">

      <i class="fa-spin fa fa-circle-o-notch" style="font-size: 30px;"></i>
    </div>

  </div>


  <div class="wrap wrap-dzsap-about" style="max-width: 1200px;">
    <h1><?php echo esc_html__("Welcome to");
      echo ' ZoomSounds ';
      echo DZSAP_VERSION; ?></h1>


    <div class="about-text"><?php echo esc_html__("
            Congratulations! You are about to use the most powerful audio player."); ?>  </div>
    <p class="useful-links">
      <a href="<?php echo admin_url('admin.php?page=dzsap_menu'); ?>" target=""
         class="button-primary action"><?php echo esc_html__('Gallery Admin', DZSAP_ID); ?></a>
      <a href="https://bit.ly/wpdzsap_kb_from_about"
         class="button-secondary action"><?php echo esc_html__('Knowledge base', DZSAP_ID); ?></a>
      <a href="<?php echo DZSAP_BASE_URL; ?>readme/index.html"
         class="button-secondary action"><?php echo esc_html__('Documentation', DZSAP_ID); ?></a>


    </p>


    <div class="dzs-tabs auto-init dzs-tabs-dzsvp-page skin-box" data-options="{ 'design_tabsposition' : 'top'
,design_transition: 'fade'
,design_tabswidth: 'default'
,toggle_breakpoint : '400'
,settings_appendWholeContent : true
,toggle_type: 'accordion'
}">


      <div class="dzs-tab-tobe">
        <div class="tab-menu"><i class="fa fa-video-camera"></i><?php echo esc_html__('Intro', 'dzsvp'); ?></div>
        <div class="tab-content">

          <div class="dzs-row">
            <div class="dzs-col-md-6">

              <img class="fullwidth" src="https://s3.envato.com/files/198770381/preview-wordpress-audio-player.jpg"
                   style="border: 2px solid #aaa;"/>


            </div>
            <div class="dzs-col-md-6">

              <p>
                <?php
                echo sprintf(esc_html__("Want a nifty, cutting-edge, retina-ready, responsive html5 audio player for your site ? ZoomSounds is the perfect candidate. With nine skins to fit every brand, multiple layout for the wave skin, only one format required to function, ZoomSounds is the perfect choice for an audio player."));
                ?>
              </p>
              <p>
                <?php
                echo sprintf(esc_html__("Supports self hosted mp3 / m4a / wav, youtube and mp3 shoutcast radio stations. You can also embed SoundCloud songs just by inputing their link in the shortcode generator – that easy. You just need a soundcloud account and api key ( you can request that on their site ) ."));
                ?>
              </p>
            </div>
          </div>
        </div>

      </div>


      <div class="dzs-tab-tobe">
        <div class="tab-menu"><i class="fa fa-video-camera"></i><?php echo esc_html__('Create Galleries', 'dzsvp'); ?>
        </div>
        <div class="tab-content">

          <div class="dzs-row">
            <div class="dzs-col-md-6">


              <div class="vplayer-tobe  tobe-inited skin_noskin" data-videoTitle="How to setup gallery quick demo"
                   data-type="youtube" data-src="https://www.youtube.com/watch?v=DF8xQKMs_qY" data-loop="on"
                   data-responsive_ratio="0.562" data-options='{
            autoplay: "off"
            ,autoplay_on_mobile_too_with_video_muted: "on"
            ,settings_suggestedQuality: "hd1080"
}'></div>


            </div>
            <div class="dzs-col-md-6">

              <p>
                <?php
                echo sprintf(esc_html__("This is how easy it is to create new galleries. Just add your items, choose your settings, and embed the gallery into any page. "));
                ?>
              </p>
              <p>
                <?php
                echo sprintf(esc_html__("Multiple options like menu position, display mode, video description style, video items sorting can be chosen for each gallery. And options like looping, autoplay, cover images can be chosen for each individual item"));
                ?>
              </p>
            </div>
          </div>
        </div>

      </div>

      <div class="dzs-tab-tobe">
        <div class="tab-menu"><i class="fa fa-flask"></i><?php echo esc_html__('Audio Showcase', 'dzsvp'); ?></div>
        <div class="tab-content">

          <div class="dzs-row">
            <div class="dzs-col-md-6">

              <div class="vplayer-tobe tobe-inited skin_noskin" data-videoTitle="How to setup video items"
                   data-type="youtube" data-src="https://www.youtube.com/watch?v=Er2WgT99xDM" data-loop="on"
                   data-responsive_ratio="detect" data-options='{
            autoplay: "off"
            ,autoplay_on_mobile_too_with_video_muted: "on"
}'></div>
            </div>
            <div class="dzs-col-md-6">

              <p>
                <?php
                echo sprintf(esc_html__("For creating video items with their own page and comments, you can use the custom video items page. These items have multiple layouts and can be added to any page via the awesome Video Showcase shortcode generator"));
                ?></p>
              <p>
                <?php
                echo sprintf(esc_html__("You can even allow your visitors to upload videos from youtube or self hosted if the %sVideo Portal%s addon is installed"), '<strong>', '</strong>');
                ?></p>

            </div>
          </div>
        </div>

      </div>

      <div class="dzs-tab-tobe">
        <div class="tab-menu"><i class="fa fa-cog"></i><?php echo esc_html__('Shortcode Generator', 'dzsvp'); ?></div>
        <div class="tab-content">

          <div class="dzs-row">
            <div class="dzs-col-md-6">

              <img src="https://i.imgur.com/6FgtoMo.png" style="box-shadow: 0 0 3px 0 rgba(0,0,0,0.3);"
                   class="fullwidth"/>
            </div>
            <div class="dzs-col-md-6">

              <p>

                <?php
                echo sprintf(esc_html__("ZoomSounds is very easy to use - it is based on shortcodes but you do not need to remember any shortcodes - the included shortcode generator makes life easy by getting a visual interface for selecting / customizing settings of the gallery."));
                ?>
              </p>

            </div>
          </div>
        </div>

      </div>

    </div>


    <br>
    <br>
    <div class="dzs-row">

      <?php
      if (current_user_can('manage_options')) {
        ?>
        <div class="dzs-col-md-4">

          <div class="white-bg">

            <h4><?php echo esc_html__("Activate"); ?> ZoomSounds</h4>

            <?php

            $auxarray = array();


            if (isset($_GET['dzsap_purchase_remove_binded']) && $_GET['dzsap_purchase_remove_binded'] == 'on') {

              $dzsap->mainoptions['dzsap_purchase_code_binded'] = 'off';

              update_option($dzsap->dboptionsname, $dzsap->mainoptions);

            }

            if (isset($_POST['action'])) {


              if ($_POST['action'] === 'dzsap_update_request' || $_POST['action'] === 'dzsap_register_request') {

                if (isset($_POST['dzsap_purchase_code'])) {
                  $auxarray = array('dzsap_purchase_code' => $_POST['dzsap_purchase_code']);
                  $auxarray = array_merge($dzsap->mainoptions, $auxarray);

                  $dzsap->mainoptions = $auxarray;


                  update_option($dzsap->dboptionsname, $auxarray);
                }
              }


            }

            $extra_class = '';
            $extra_attr = '';
            $form_method = "POST";
            $form_action = "";
            $disable_button = '';

            $purchaseCodeDbName = 'dzsap_purchase_code';
            $purchaseCodeDbVal = $dzsap->mainoptions[$purchaseCodeDbName];

            if ($dzsap->mainoptions['dzsap_purchase_code_binded'] == 'on') {
              $extra_attr = ' disabled';
              $disable_button = ' <input type="hidden" name="purchase_code" value="' . $dzsap->mainoptions[$purchaseCodeDbName] . '"/><input type="hidden" name="site_url" value="' . site_url() . '"/><input type="hidden" name="redirect_url" value="' . esc_url(add_query_arg('dzsap_purchase_remove_binded', 'on', dzs_curr_url())) . '"/><button class="button-secondary btn-disable-activation" name="action" value="dzsap_purchase_code_disable">' . esc_html__("Disable Key", DZSAP_ID) . '</button>';
              $form_action = ' action="https://zoomthe.me/updater_dzsap/servezip.php"';
            } else {
              $purchaseCodeDbVal = '';
            }


            ?>
            <form action="https://zoomthe.me/updater_dzsap/check_activation.php" class="mainsettings activate-form"
                  method="POST">
              <?php
              ?>
              <div class="sidenote"><?php echo esc_html__("Unlock ");
                echo " ZoomSounds";
                echo esc_html__(" for premium benefits like one click sample galleries install and autoupdate.") ?></div><?php
              echo '
            
                <div class="setting">
                    <div class="label">' . esc_html__("Purchase Code", DZSAP_ID) . '</div>
                    ' . dzsap_misc_input_text($purchaseCodeDbName, array('val' => '', 'seekval' => $dzsap->mainoptions[$purchaseCodeDbName], 'class' => $extra_class, 'extra_attr' => $extra_attr)) . $disable_button . '
                    <div class="sidenote">' . dzs_esc__(__("You can %sfind it here%s ", DZSAP_ID), array('<a href="https://lh5.googleusercontent.com/-o4WL83UU4RY/Unpayq3yUvI/AAAAAAAAJ_w/HJmso_FFLNQ/w786-h1179-no/puchase.jpg" target="_blank">', '</a>')) . '</div>
                </div>';


              echo '<p><button class="button-primary" name="action" value="dzsap_register_request">' . esc_html__("Activate", DZSAP_ID) . '</button></p>';


              if ($dzsap->mainoptions['dzsap_purchase_code_binded'] == 'on') {
                echo '';
              }
              ?></form>
            <br>

            <?php


            ?>
            <form class="mainsettings update-form" method="post"><?php

              ?>
              <strong><?php echo esc_html__("Current Version"); ?></strong>
              <p><span class="version-number"
                       style="font-size:13px; font-weight: 100;"><span
                    class="now-version"><?php echo DZSAP_VERSION; ?></span></span></p>
              <strong><?php echo esc_html__("Latest Version"); ?></strong>
              <p><span class="version-number"
                       style="font-size:13px; font-weight: 100; min-height: 17px;"><span
                    class="latest-version" style=" min-height: 21px; display: inline-block"> <i
                      class="fa-spin fa fa-circle-o-notch"></i> </span></span></p>

              <?php

              $str_disabled = ' disabled';

              if ($dzsap->mainoptions['dzsap_purchase_code_binded'] == 'on') {
                $str_disabled = '';
              }


              echo '<p><button class="button-primary" name="action" value="dzsap_update_request" ' . $str_disabled . '>' . esc_html__("Update", DZSAP_ID) . '</button></p>';


              ?>
            </form><?php


            if (isset($_POST['action']) && $_POST['action'] === 'dzsap_update_request') {

              DZSZoomSoundsHelper::autoupdaterUpdate();
            }


            ?>

          </div>
        </div>
        <?php
      }
      ?>

      <div class="dzs-col-md-4">

        <div class="white-bg">

          <h4><?php echo esc_html__("One click sample data"); ?></h4>

          <img src="https://i.imgur.com/5PrrWkl.jpg" class="fullwidth"/>

          <p><?php
            echo sprintf(esc_html__("Want to import some sample content from the zoomsounds demo ? Shortcode generator comes to your help with sample data. The sample data tab allows for quick one click import of some demos for both playlists and players."));
            ?></p>

        </div>
      </div>
      <div class="dzs-col-md-4 system-check">


        <div class="white-bg">

          <h4><?php echo esc_html__("System Check"); ?></h4>

          <div class="setting">

            <h4 class="setting-label">GetText <?php echo esc_html__("Support"); ?></h4>


            <?php
            if (function_exists("gettext")) {
              echo '<div class="setting-text-ok"><i class="fa fa-thumbs-up"></i> ' . '' . esc_html__("supported") . '</div>';
            } else {

              echo '<div class="setting-text-notok">' . '' . esc_html__("not supported") . '</div>';
            }
            ?>
          </div>


          <div class="setting">

            <h4 class="setting-label">ZipArchive <?php echo esc_html__("Support"); ?></h4>


            <?php
            if (class_exists("ZipArchive")) {
              echo '<div class="setting-text-ok"><i class="fa fa-thumbs-up"></i> ' . '' . esc_html__("supported") . '</div>';
            } else {

              echo '<div class="setting-text-notok">' . '' . esc_html__("not supported") . '</div>';
            }
            ?>
          </div>
          <div class="setting">

            <h4 class="setting-label">Curl <?php echo esc_html__("Support"); ?></h4>


            <?php
            if (function_exists('curl_version')) {
              echo '<div class="setting-text-ok"><i class="fa fa-thumbs-up"></i> ' . '' . esc_html__("supported") . '</div>';
            } else {

              echo '<div class="setting-text-notok">' . '' . esc_html__("not supported") . '</div>';
            }
            ?>

          </div>
          <div class="setting">

            <h4 class="setting-label">allow_url_fopen <?php echo esc_html__("Support"); ?></h4>


            <?php
            if (ini_get('allow_url_fopen')) {
              echo '<div class="setting-text-ok"><i class="fa fa-thumbs-up"></i> ' . '' . esc_html__("supported") . '</div>';
            } else {

              echo '<div class="setting-text-notok">' . '' . esc_html__("not supported") . '</div>';
            }
            ?>


          </div>


          <div class="setting">

            <h4 class="setting-label">ZoomIt <?php echo esc_html__("Server Communication", DZSAP_ID); ?></h4>
            <i class="fa-spin fa fa-circle-o-notch replace-with-server-communication-feedback"></i>
          </div>


          <div class="setting">

            <h4 class="setting-label"><?php echo esc_html__("PHP Version"); ?></h4>

            <div class="setting-text-ok">
              <?php
              echo phpversion();
              ?>
            </div>


          </div>

        </div>
      </div>


    </div>

    <br>
    <a href="<?php echo admin_url('admin.php?page=dzsap_menu&donotshowaboutagain=on'); ?>" target=""
       class="button-primary action"><i class="fa fa-check"></i> <?php echo esc_html__('Got it! Lets go.', DZSAP_ID); ?></a>
  </div>
<?php
