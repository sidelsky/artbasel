<?php


global $dzsap;
?>

  <div class="wrap">
    <h2><?php echo esc_html__('ZoomSounds Main Settings', DZSAP_ID); ?></h2>
    <br/>

    <a class="zoombox button-secondary" href="<?php echo DZSAP_BASE_URL; ?>readme/index.html"
       data-bigwidth="1100" data-scaling="fill"
       data-bigheight="700"><?php echo esc_html__("Documentation"); ?></a>

    <a
      href="<?php echo admin_url('admin.php?page=' . DZSAP_ADMIN_PAGENAME_MAINOPTIONS . '&dzsap_shortcode_builder=on'); ?>"
      target="_blank"
      class="button-secondary action"><?php echo esc_html__('Gallery Generator', DZSAP_ID); ?></a>

    <a
      href="<?php echo admin_url('admin.php?page=' . DZSAP_ADMIN_PAGENAME_MAINOPTIONS . '&'.DZSAP_ADMIN_SHORTCODE_PLAYER_GENERATOR_KEY.'=on'); ?>"
      target="_blank"
      class="button-secondary action"><?php echo esc_html__('Player Generator', DZSAP_ID); ?></a>

    <a
      href="<?php echo admin_url('admin.php?page=' . DZSAP_ADMIN_PAGENAME_MAINOPTIONS . '&' . DZSAP_ADMIN_PAGENAME_MAINOPTIONS_WAVE_GENERATOR . '=on'); ?>"
      target="_blank"
      class="button-secondary action"><?php echo esc_html__('Wave Generator', DZSAP_ID); ?></a>


    <?php
    do_action('dzsap_mainoptions_before_tabs');
    ?>


    <div class="dzs--main-setings--search-con">
      <br>
      <div>
        <input class="dzs-big-input" id="dzs--settings-search" type="search"
               placeholder="<?php echo esc_html__('Search...', DZSAP_ID); ?>"/>
        <i class="dzs--settings-search--search-icon">
          <?php

          echo dzs_read_from_file_ob(DZSAP_BASE_PATH . 'assets/svg/search.svg');
          ?>
        </i>
      </div>
    </div>
    <?php
    if (isset($_COOKIE[DZSAP_COOKIENAME_SYSTEM_CHECK_WAVES]) && $_COOKIE[DZSAP_COOKIENAME_SYSTEM_CHECK_WAVES] === 'on') {


      $searchedVal = '';
      if (isset($_GET['track_search']) && $_GET['track_search']) {
        $searchedVal = $_GET['track_search'];
      }
      $temp = '';
      $temp = '<h4 style="margin-bottom: 5px;">' . esc_html__('Search a track for waves check') . '</h4>';
      $temp .= '<form class="dzs-big-search--con" action="' . admin_url('admin.php?page=dzsap-mo&tab=14') . '">';
      $temp .= '<input type="hidden" name="page" value="dzsap-mo"/>';
      $temp .= '<input type="hidden" name="tab" value="14"/>';
      $temp .= '<input class="dzs-big-input" id="' . DZSAP_COOKIENAME_SYSTEM_CHECK_WAVES . '--search" name="track_search" type="search" value="' . $searchedVal . '" placeholder="' . esc_html__('Search tracks', DZSAP_ID) . '"/><button class="dzs--settings-search--search-icon">
          ' . dzs_read_from_file_ob(DZSAP_BASE_PATH . 'assets/svg/search.svg') . '        </button>';
      $temp .= '</form>';
      echo $temp;
    }
    ?>

    <form class="mainsettings">
      <div id="dzs-tabs--main-options" class="dzs-tabs auto-init" data-options="{ 'design_tabsposition' : 'top'
,design_transition: 'fade'
,design_tabswidth: 'default'
,toggle_breakpoint : '400'
,toggle_type: 'accordion'
,toggle_type: 'accordion'
,settings_enable_linking : 'on'
,settings_appendWholeContent: true
,refresh_tab_height: '1000'
}">

        <div class="dzs-tab-tobe">
          <div class="tab-menu with-tooltip">
            <i class="fa fa-tachometer"></i> <?php echo esc_html__("Settings"); ?>
          </div>
          <div class="tab-content">
            <br>


            <!-- general settings tab content -->


            <div class="setting">
              <h4 class="setting-label"><?php echo esc_html__('Enable Global Footer Player', DZSAP_ID); ?></h4>
              <?php


              $lab = 'enable_global_footer_player';

              $vpconfigs_arr = array(
                array('lab' => esc_html__("Off"), 'val' => 'off')
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

              echo DZSHelpers::generate_select($lab, array('class' => 'vpconfig-select styleme', 'options' => $vpconfigs_arr, 'seekval' => $dzsap->mainoptions[$lab])); ?>

              <div class="edit-link-con" style="margin-top: 10px;"></div>

              <div
                class="sidenote"><?php echo esc_html__("this will output the footer player on the whole site."); ?></div>
            </div>


            <?php

            $dependency = array(

              array(
                'element' => 'skinwave_wave_mode',
                'value' => array('canvas'),
              ),
            );
            ?>
            <div class="setting" data-dependency='<?php echo json_encode($dependency); ?>'>
              <h4 class="setting-label"><?php echo esc_html__('Normalize ', DZSAP_ID); ?></h4>
              <?php


              $lab = 'skinwave_wave_mode_canvas_normalize';

              $opts = array(
                array(
                  'lab' => esc_html__("Normalize Waves"),
                  'val' => 'on'
                ),
                array(
                  'lab' => esc_html__("Do not normalize"),
                  'val' => 'off'
                ),

              );


              echo DZSHelpers::generate_select($lab, array('class' => ' styleme', 'options' => $opts, 'seekval' => $dzsap->mainoptions[$lab])); ?>


              <div
                class="sidenote"><?php echo esc_html__("normalize the waves to look like they have continuity , or disable normalizing to make the waveforms follow the real sound") . ''; ?></div>
            </div>


            <div class="setting">
              <h4
                class="setting-label"><?php echo esc_html__('Allow Download Only for Registered Users ', DZSAP_ID); ?></h4>
              <?php


              $lab = 'allow_download_only_for_registered_users';

              $opts = array(
                array(
                  'lab' => esc_html__("Off"),
                  'val' => 'off'
                ),
                array(
                  'lab' => esc_html__("On"),
                  'val' => 'on'
                ),

              );


              echo DZSHelpers::generate_select($lab, array(
                'class' => ' styleme dzs-dependency-field',
                'options' => $opts,
                'seekval' => $dzsap->mainoptions[$lab]));
              ?>


              <div
                class="sidenote"><?php echo esc_html__("allow the download tab only for registered users") . ''; ?></div>
            </div>


            <?php
            $lab = 'exclude_from_search';
            $val = 'off';
            if (isset($dzsap->mainoptions[$lab]) && $dzsap->mainoptions[$lab]) {
              $val = $dzsap->mainoptions[$lab];
            }

            include_once DZSAP_BASE_PATH . 'inc/php/admin/admin-echo-registeredUsersCapabilityMainOption.php';
            dzsap_admin_echo_registeredUsersCapabilityMainOption($dzsap);
            ?>
            <div class="setting">
              <h4 class="setting-label"><?php echo esc_html__('Exclude audio items from Search', DZSAP_ID); ?></h4>
              <?php


              $opts = array(
                array(
                  'lab' => esc_html__("Include"),
                  'val' => 'off'
                ),
                array(
                  'lab' => esc_html__("Exclude"),
                  'val' => 'on'
                ),
              );


              echo DZSHelpers::generate_select($lab, array(
                'class' => ' styleme ',
                'options' => $opts,
                'seekval' => $val
              ));

              ?>


              <div class="sidenote"><?php echo esc_html__("select a class to restrict downloads too") . ''; ?></div>
            </div>


            <div class="setting">
              <h4 class="setting-label"><?php echo esc_html__('SoundCloud API Key', DZSAP_ID); ?></h4>
              <?php
              $val = '';
              if ($dzsap->mainoptions['soundcloud_api_key']) {
                $val = $dzsap->mainoptions['soundcloud_api_key'];
              }
              echo DZSHelpers::generate_input_text('soundcloud_api_key', array('val' => '', 'seekval' => $val, 'type' => '', 'class' => ''));
              ?>
              <div
                class="sidenote"><?php echo esc_html__('You can get one by going to <a href="https://soundcloud.com/you/apps/new">here</a> and registering a new app. The api key wil lbe the client ID you get at the end.', DZSAP_ID); ?></div>
            </div>

            <div class="setting">
              <h4 class="setting-label"><?php echo esc_html__('Play Remember Time', DZSAP_ID); ?></h4>
              <?php
              $lab = 'play_remember_time';
              $val = '';
              if ($dzsap->mainoptions[$lab]) {
                $val = $dzsap->mainoptions[$lab];
              }
              echo DZSHelpers::generate_input_text($lab, array('val' => '', 'seekval' => $val, 'type' => '', 'class' => ''));
              ?>
              <div
                class="sidenote"><?php echo esc_html__('plays are regitered by ip - you can specify a time ( in minutes ) at which plays are remembers. after this time - a new play can be registered for the same ip', DZSAP_ID); ?></div>
            </div>
            <div class="setting">
              <h4 class="setting-label"><?php echo esc_html__('Like Markup Part 1', DZSAP_ID); ?></h4>
              <?php
              $val = '';
              $lab = 'str_likes_part1';
              if ($dzsap->mainoptions[$lab]) {
                $val = stripslashes($dzsap->mainoptions[$lab]);
              }
              echo dzsap_misc_input_textarea($lab, array('val' => '', 'seekval' => $val, 'type' => '', 'class' => ''));
              ?>
              <div class="sidenote"><?php echo esc_html__('Replace with any html. Default is - ', DZSAP_ID); ?>
                <pre><?php echo esc_html('<span class="btn-zoomsounds btn-like"><span class="the-icon">{{heart_svg}}</span><span class="the-label hide-on-active">Like</span><span class="the-label show-on-active">Liked</span></span>') ?></pre>
              </div>
            </div>
            <div class="setting">
              <h4 class="setting-label"><?php echo esc_html__('Plays Markup', DZSAP_ID); ?></h4>
              <?php
              $val = '';
              $lab = 'str_views';
              if ($dzsap->mainoptions[$lab]) {
                $val = stripslashes($dzsap->mainoptions[$lab]);
              }
              echo dzsap_misc_input_textarea($lab, array('val' => '', 'seekval' => $val, 'type' => '', 'class' => ''));
              ?>
              <div class="sidenote"><?php echo esc_html__('Replace with any html. Default is - ', DZSAP_ID); ?>
                <pre><?php echo esc_html('<div class="counter-hits"><i class="fa fa-play"></i><span class="the-number">{{get_plays}}</span></div>') ?></pre>
              </div>
            </div>
            <div class="setting">
              <h4 class="setting-label"><?php echo esc_html__('Like Markup Part 2', DZSAP_ID); ?></h4>
              <?php
              $val = '';
              $lab = 'str_likes_part2';
              if ($dzsap->mainoptions[$lab]) {
                $val = stripslashes($dzsap->mainoptions[$lab]);
              }
              echo dzsap_misc_input_textarea($lab, array('val' => '', 'seekval' => $val, 'type' => '', 'class' => ''));
              ?>
              <div class="sidenote"><?php echo esc_html__('Replace with any html. Default is - ', DZSAP_ID); ?>
                <pre><?php echo esc_html('<div class="counter-likes"><i class="fa fa-heart"></i><span class="the-number">{{get_likes}}</span></div>') ?></pre>
              </div>
            </div>
            <div class="setting">
              <h4 class="setting-label"><?php echo esc_html__('Rates Markup', DZSAP_ID); ?></h4>
              <?php
              $val = '';
              $lab = 'str_rates';
              if ($dzsap->mainoptions[$lab]) {
                $val = stripslashes($dzsap->mainoptions[$lab]);
              }
              echo dzsap_misc_input_textarea($lab, array('val' => '', 'seekval' => $val, 'type' => '', 'class' => ''));
              ?>
              <div class="sidenote"><?php echo esc_html__('Replace with any html. Default is - ', DZSAP_ID); ?>
                <pre><?php echo esc_html('<div class="counter-rates"><span class="the-number">{{get_rates}}</span> rates</div>') ?></pre>
              </div>
            </div>


            <?php

            $config_main_options = include(DZSAP_BASE_PATH . 'configs/config-main-options.php');

            echo DZSZoomSoundsHelper::generateOptionsFromConfigForMainOptions($config_main_options, 'main_settings', $dzsap);

            ?>


            <!-- end general settings -->


            <div class="setting">
              <h4
                class="setting-label"><?php echo esc_html__('Replace default wordpress audio shortcode', DZSAP_ID); ?></h4>
              <?php


              $lab = 'replace_audio_shortcode';

              $vpconfigs_arr = array(
                array('lab' => esc_html__("Off", DZSAP_ID), 'val' => 'off')
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

              echo DZSHelpers::generate_select($lab, array('class' => 'vpconfig-select styleme', 'options' => $vpconfigs_arr, 'seekval' => $dzsap->mainoptions[$lab])); ?>

              <div class="edit-link-con" style="margin-top: 10px;"></div>

              <div
                class="sidenote"><?php echo esc_html__("select a audio player configuration with which to replace the default wordpress player"); ?></div>
            </div>


            <?php

            $lab = 'replace_audio_shortcode_extra_args';


            //htmlentities
            ?>
            <div class="setting">


              <h4
                class="setting-label"><?php echo esc_html__('Extra arguments for default audio shortcode', DZSAP_ID); ?></h4>
              <?php echo dzsap_misc_input_textarea($lab, array('val' => '', 'seekval' => stripslashes($dzsap->mainoptions[$lab]))); ?>

              <div class="sidenote"><?php echo esc_html__("in json format", DZSAP_ID); ?>
                ; <?php echo esc_html__("for example enter: ", DZSAP_ID); ?>
                <code>{"enable_likes":"on"}</code><?php echo esc_html__("for showing likes, or enter: ", DZSAP_ID); ?>
                <code>{"enable_likes":"on","enable_views":"on"}</code><?php echo esc_html__("for enabling likes AND plays", DZSAP_ID); ?>
              </div>
            </div>


            <div class="setting">
              <h4
                class="setting-label"><?php echo esc_html__('Play default shortcode in footer player', DZSAP_ID); ?></h4>
              <?php


              $lab = 'replace_audio_shortcode_play_in_footer';


              echo DZSHelpers::generate_input_text($lab, array('id' => $lab, 'class' => 'fake-input', 'val' => 'off', 'input_type' => 'hidden'));


              echo '<div class="dzscheckbox skin-nova">
                                        ' . DZSHelpers::generate_input_checkbox($lab, array('id' => $lab, 'class' => 'mainsetting', 'val' => 'on', 'seekval' => $dzsap->mainoptions[$lab])) . '
                                        <label for="' . $lab . '"></label>
                                    </div>';
              ?>


              <div
                class="sidenote"><?php echo esc_html__("only if a player configuration is selected for the default player, then this will play in the footer player"); ?></div>
            </div>
          </div>
        </div>

        <div class="dzs-tab-tobe tab-disabled">
          <div class="tab-menu ">
            &nbsp;&nbsp;
          </div>
          <div class="tab-content">

          </div>
        </div>


        <div class="dzs-tab-tobe">
          <div class="tab-menu with-tooltip">
            <i class="fa fa-shopping-cart"></i> <?php echo esc_html__("WooCommerce ", DZSAP_ID) ?>
          </div>
          <div class="tab-content">
            <br>

            <h3><?php echo esc_html__('Single product', DZSAP_ID); ?></h3>

            <div class="setting">
              <h4 class="setting-label"><?php echo esc_html__('Single Product ZoomSounds Preview', DZSAP_ID); ?></h4>
              <?php


              $lab = 'wc_single_product_player';


              echo DZSHelpers::generate_select($lab, array('class' => 'vpconfig-select styleme', 'options' => $vpconfigs_arr, 'seekval' => $dzsap->mainoptions[$lab])); ?>

              <div class="edit-link-con" style="margin-top: 10px;"></div>

              <div
                class="sidenote"><?php echo esc_html__("this will output a preview player in the woocommerce product page if a track is set in the zoomsounds settings of the product."); ?></div>
            </div>


            <div class="setting">
              <h4 class="setting-label"><?php echo esc_html__('Product Player Position', DZSAP_ID); ?></h4>
              <?php


              $lab = 'wc_single_player_position';


              echo DZSHelpers::generate_select($lab, array('class' => 'vpconfig-select styleme', 'options' => array(
                array(
                  'label' => esc_html__("Top of product"),
                  'value' => 'top',
                ),
                array(
                  'label' => esc_html__("Overlay product image"),
                  'value' => 'overlay',
                ),
                array(
                  'label' => esc_html__("Below product"),
                  'value' => 'bellow',
                ),
              ), 'seekval' => $dzsap->mainoptions[$lab])); ?>

              <div class="edit-link-con" style="margin-top: 10px;"></div>

              <div
                class="sidenote"><?php echo esc_html__("this will output a preview player in the woocommerce single product page if a track is set in the zoomsounds settings of the product."); ?></div>
            </div>

            <h3><?php echo esc_html__('Loop product', DZSAP_ID); ?></h3>

            <div class="setting">
              <h4 class="setting-label"><?php echo esc_html__('Loop Product ZoomSounds Preview', DZSAP_ID); ?></h4>
              <?php


              $lab = 'wc_loop_product_player';


              echo DZSHelpers::generate_select($lab, array('class' => ' styleme', 'options' => $vpconfigs_arr, 'seekval' => $dzsap->mainoptions[$lab])); ?>

              <div class="edit-link-con" style="margin-top: 10px;"></div>

              <div
                class="sidenote"><?php echo esc_html__("this will output a preview player in the woocommerce shop page if a track is set in the zoomsounds settings of the product."); ?></div>
            </div>

            <div class="setting">
              <h4 class="setting-label"><?php echo esc_html__('Product Loop Position', DZSAP_ID); ?></h4>
              <?php


              $lab = 'wc_loop_player_position';


              echo DZSHelpers::generate_select($lab, array('class' => 'dzs-dependency-field vpconfig-select styleme', 'options' => array(
                array(
                  'label' => esc_html__("Top of product"),
                  'value' => 'top',
                ),
                array(
                  'label' => esc_html__("Overlay product image"),
                  'value' => 'overlay',
                ),
                array(
                  'label' => esc_html__("Below product"),
                  'value' => 'bellow',
                ),
              ), 'seekval' => $dzsap->mainoptions[$lab])); ?>

              <div class="edit-link-con" style="margin-top: 10px;"></div>

              <div
                class="sidenote"><?php echo esc_html__("this will output a preview player in the woocommerce shop page if a track is set in the zoomsounds settings of the product."); ?></div>
            </div>

            <?php
            $dependency = array(

              array(
                'element' => 'wc_loop_player_position',
                'value' => array('overlay'),
              ),
            );
            ?>
            <div class="setting" data-dependency='<?php echo json_encode($dependency); ?>'>
              <h4 class="setting-label"><?php echo esc_html__('Custom Wrapper Selector', DZSAP_ID); ?></h4>
              <?php
              $lab = 'wc_loop_player_position__overlay__wrapper_selector';
              $val = '';
              if ($dzsap->mainoptions[$lab]) {
                $val = $dzsap->mainoptions[$lab];
              }
              echo DZSHelpers::generate_input_text($lab, array('val' => '', 'seekval' => $val, 'type' => '', 'class' => ''));
              ?>
              <div
                class="sidenote"><?php echo esc_html__('plays are regitered by ip - you can specify a time ( in minutes ) at which plays are remembers. after this time - a new play can be registered for the same ip', DZSAP_ID); ?></div>
            </div>

            <h3><?php echo esc_html__('General WooCommerce Players', DZSAP_ID); ?></h3>

            <div class="setting">
              <h4 class="setting-label"><?php echo esc_html__('Play in Sticky Player ? ', DZSAP_ID); ?></h4>
              <?php


              $lab = 'wc_product_play_in_footer';


              echo DZSHelpers::generate_select($lab, array('class' => ' styleme', 'options' => array(
                array(
                  'label' => esc_html__("Off"),
                  'value' => 'off',
                ),
                array(
                  'label' => esc_html__("On"),
                  'value' => 'on',
                ),
              ), 'seekval' => $dzsap->mainoptions[$lab])); ?>

              <div class="edit-link-con" style="margin-top: 10px;"></div>

              <div
                class="sidenote"><?php echo esc_html__("this will output a preview player in the woocommerce shop page if a track is set in the zoomsounds settings of the product."); ?></div>
            </div>


            <div class="setting">
              <h4 class="setting-label"><?php echo esc_html__('Try to hide real url', DZSAP_ID); ?> (beta)</h4>
              <?php


              $lab = 'try_to_hide_url';


              echo DZSHelpers::generate_select($lab, array('class' => 'styleme', 'options' => array(
                array(
                  'label' => esc_html__("Off"),
                  'value' => 'off',
                ),
                array(
                  'label' => esc_html__("On"),
                  'value' => 'on',
                ),
              ), 'seekval' => $dzsap->mainoptions[$lab])); ?>


              <div
                class="sidenote"><?php echo esc_html__("( beta ) try to hide real url and deny access for direct download - will DISABLE seeking the mp3 progress"); ?></div>
            </div>

            <div class="setting">
              <h4
                class="label"><?php echo esc_html__('Single Product ZoomSounds Preview - Optional shortcode', DZSAP_ID); ?></h4>
              <?php


              $lab = 'wc_single_product_player_shortcode';


              echo DZSHelpers::generate_input_textarea($lab, array('class' => ' ', 'seekval' => $dzsap->mainoptions[$lab])); ?>


              <div
                class="sidenote"><?php echo esc_html__("you can input here shortcode to replace the main player in woocommerce product ie -", DZSAP_ID); ?>
                <br>
                <pre style="white-space: pre-line;">[zoomsounds_player type="detect" dzsap_meta_source_attachment_id="{{postid}}" source="{{source}}" thumb="{{thumb}}" config="sample--skin-wave--with-comments" autoplay="off" loop="off" open_in_ultibox="off" enable_likes="off" enable_views="on" play_in_footer_player="on" enable_download_button="off" download_custom_link_enable="off"]</pre>
              </div>
            </div>


            <div class="setting">
              <h4 class="setting-label"><?php echo esc_html__('Samples Times Reflect', DZSAP_ID); ?></h4>
              <?php


              $lab = 'sample_time_pseudo';


              echo DZSHelpers::generate_select($lab, array('class' => 'styleme', 'options' => array(
                array(
                  'label' => esc_html__("Part of Real Track"),
                  'value' => '',
                ),
                array(
                  'label' => esc_html__("Part of Preview Track"),
                  'value' => 'pseudo',
                ),
              ), 'seekval' => $dzsap->mainoptions[$lab])); ?>


              <div
                class="sidenote"><?php echo esc_html__("this controls wheter the sample time start / end reflect the part of the real track or the preview track"); ?>
                <a
                  href="https://zoomthe.me/knowledge-base/zoomsounds-audio-player/article/how-to-use-woocommerce-sample-preview-times/"></a>
              </div>
            </div>


          </div>

        </div>


        <div class="dzs-tab-tobe tab-disabled">
          <div class="tab-menu ">
            &nbsp;&nbsp;
          </div>
          <div class="tab-content">

          </div>
        </div>

        <div class="dzs-tab-tobe">
          <div class="tab-menu with-tooltip">
            <i class="fa fa-flag"></i> <?php echo esc_html__("Translate") ?>
          </div>
          <div class="tab-content">
            <br>


            <div
              class="sidenote"><?php echo esc_html__("Note that integral translation of the plugin can be done by installing the WPML plugin. Or by using PO Edit and modifying the core wordpress language. We provide next only a few strings to be translated, for convenience:"); ?></div>

            <?php
            $lab = 'i18n_buy';

            echo '
                <div class="setting">
                    <h4 class="setting-label">' . esc_html__('Translate "Buy"', DZSAP_ID) . '</div>
                    ' . DZSHelpers::generate_input_text($lab, array('seekval' => $dzsap->mainoptions[$lab])) . '
                </h4>';

            $lab = 'i18n_title';

            echo '
                <div class="setting">
                    <h4 class="setting-label">' . esc_html__('Translate "Title"', DZSAP_ID) . '</div>
                    ' . DZSHelpers::generate_input_text($lab, array('seekval' => $dzsap->mainoptions[$lab])) . '
                </h4>';


            $lab = 'i18n_play';

            echo '
                <div class="setting">
                    <h4 class="setting-label">' . esc_html__('Translate "Play"', DZSAP_ID) . '</div>
                    ' . DZSHelpers::generate_input_text($lab, array('seekval' => $dzsap->mainoptions[$lab])) . '
                </h4>';
            $lab = 'i18n_free_download';

            echo '
                <div class="setting">
                    <h4 class="setting-label">' . esc_html__('Translate "Free Download"', DZSAP_ID) . '</div>
                    ' . DZSHelpers::generate_input_text($lab, array('seekval' => $dzsap->mainoptions[$lab])) . '
                </h4>';


            $lab = 'i18n_register_to_download';

            echo '
                <div class="setting">
                    <h4 class="setting-label">' . esc_html__('Translate "Register to download"', DZSAP_ID) . '</div>
                    ' . DZSHelpers::generate_input_text($lab, array('seekval' => $dzsap->mainoptions[$lab])) . '
                </h4>';


            ?>


            <div class="setting">
              <h4
                class="setting-label"><?php echo esc_html__('Register to download - opens in new window', DZSAP_ID); ?></h4>
              <?php


              $lab = 'register_to_download_opens_in_new_link';


              echo DZSHelpers::generate_input_text($lab, array('id' => $lab, 'class' => 'fake-input', 'val' => 'off', 'input_type' => 'hidden'));


              echo '<div class="dzscheckbox skin-nova">
                                        ' . DZSHelpers::generate_input_checkbox($lab, array('id' => $lab, 'class' => 'mainsetting', 'val' => 'on', 'seekval' => $dzsap->mainoptions[$lab])) . '
                                        <label for="' . $lab . '"></label>
                                    </div>';
              ?>


            </div>

          </div>
        </div>


        <div class="dzs-tab-tobe tab-disabled">
          <div class="tab-menu ">
            &nbsp;&nbsp;
          </div>
          <div class="tab-content">

          </div>
        </div>

        <div class="dzs-tab-tobe">
          <div class="tab-menu with-tooltip">
            <i class="fa fa-keyboard-o"></i> <?php echo esc_html__("Keyboard") ?>
          </div>
          <div class="tab-content">
            <br>


            <div class="sidenote"><?php echo dzs_esc__(esc_html__("keyboard controls setup: %s %s escape key - %s space key - %s 
                        left key - %s
                        right key - %s
                        up key - %s
                        down key - %s
                        you can input something like this %s also
                        ", DZSAP_ID), array('<br>', '<br>', '<strong>27</strong><br>'
              , '<strong>32</strong><br>'
              , '<strong>37</strong><br>'
              , '<strong>39</strong><br>'
              , '<strong>38</strong><br>'
              , '<strong>40</strong><br>'
              , '<strong>ctrl+39</strong>'
              )); ?></div>

            <?php

            $lab = 'keyboard_pause_play';

            echo '
                <div class="setting">
                    <h4 class="setting-label">' . esc_html__('Play / pause code', DZSAP_ID) . '</div>
                    ' . DZSHelpers::generate_input_text($lab, array('seekval' => $dzsap->mainoptions[$lab])) . '
                </h4>';


            $lab = 'keyboard_step_forward';

            echo '
                <div class="setting">
                    <h4 class="setting-label">' . esc_html__('Step forward key code', DZSAP_ID) . '</div>
                    ' . DZSHelpers::generate_input_text($lab, array('seekval' => $dzsap->mainoptions[$lab])) . '
                </h4>';
            $lab = 'keyboard_step_back';

            echo '
                <div class="setting">
                    <h4 class="setting-label">' . esc_html__('Step back key code', DZSAP_ID) . '</div>
                    ' . DZSHelpers::generate_input_text($lab, array('seekval' => $dzsap->mainoptions[$lab])) . '
                </h4>';


            $lab = 'keyboard_step_back_amount';

            echo '
                <div class="setting">
                    <h4 class="setting-label">' . esc_html__('Back amount in seconds', DZSAP_ID) . '</div>
                    ' . DZSHelpers::generate_input_text($lab, array('seekval' => $dzsap->mainoptions[$lab])) . '
                </h4>';


            $lab = 'keyboard_sync_players_goto_prev';

            echo '
                <div class="setting">
                    <h4 class="setting-label">' . esc_html__('Previous track', DZSAP_ID) . '</div>
                    ' . DZSHelpers::generate_input_text($lab, array('seekval' => $dzsap->mainoptions[$lab])) . '
                    <div class="sidenote">' . esc_html__('either enable Play Single Players One After Another on the Page in Developer Settings, or enable the sticky player playlist in Player configurations - for this to work', DZSAP_ID) . '</div>
                </h4>';
            $lab = 'keyboard_sync_players_goto_next';

            echo '
                <div class="setting">
                    <h4 class="setting-label">' . esc_html__('Next track', DZSAP_ID) . '</div>
                    ' . DZSHelpers::generate_input_text($lab, array('seekval' => $dzsap->mainoptions[$lab])) . '
                </h4>';


            ?>


            <div class="setting">
              <h4 class="setting-label"><?php echo esc_html__('Show tooltips', DZSAP_ID); ?></h4>
              <?php

              $lab = 'keyboard_show_tooltips';


              echo DZSHelpers::generate_input_text($lab, array('id' => $lab, 'class' => 'fake-input', 'val' => 'off', 'input_type' => 'hidden'));


              echo '<div class="dzscheckbox skin-nova">
                                        ' . DZSHelpers::generate_input_checkbox($lab, array('id' => $lab, 'class' => 'mainsetting', 'val' => 'on', 'seekval' => $dzsap->mainoptions[$lab])) . '
                                        <label for="' . $lab . '"></label>
                                    </div>';
              ?>

            </div>


            <div class="setting">
              <h4 class="setting-label"><?php echo esc_html__('Play triggers step back', DZSAP_ID); ?></h4>
              <?php

              $lab = 'keyboard_play_trigger_step_back';


              echo DZSHelpers::generate_input_text($lab, array('id' => $lab, 'class' => 'fake-input', 'val' => 'off', 'input_type' => 'hidden'));


              echo '<div class="dzscheckbox skin-nova">
                                        ' . DZSHelpers::generate_input_checkbox($lab, array('id' => $lab, 'class' => 'mainsetting', 'val' => 'on', 'seekval' => $dzsap->mainoptions[$lab])) . '
                                        <label for="' . $lab . '"></label>
                                    </div>';
              ?>

            </div>

          </div>
        </div>


        <div class="dzs-tab-tobe tab-disabled">
          <div class="tab-menu ">
            &nbsp;&nbsp;
          </div>
          <div class="tab-content">

          </div>
        </div>

        <div class="dzs-tab-tobe">
          <div class="tab-menu with-tooltip">
            <i class="fa fa-paint-brush"></i> <?php echo esc_html__("Appearance", DZSAP_ID) ?>
          </div>
          <div class="tab-content">
            <br>


            <?php


            echo DZSZoomSoundsHelper::generateOptionsFromConfigForMainOptions($config_main_options, 'settings_appearance', $dzsap);

            $val = '444444';

            $lab = 'design_wave_color_bg';
            if (isset($dzsap->mainoptions[$lab]) && $dzsap->mainoptions[$lab]) {
              $val = $dzsap->mainoptions[$lab];
            }
            echo '<h3>' . esc_html__("Wave Form Options") . '</h3>
                <div class="setting">
                    <h4 class="setting-label">' . esc_html__('Waveform BG Color', DZSAP_ID) . '</div>
                    ' . DZSHelpers::generate_input_text($lab, array('val' => 'ffffff', 'seekval' => $val, 'type' => 'colorpicker', 'class' => 'colorpicker-nohash')) . '
                    <div class="sidenote">' . sprintf(esc_html__("you can input a gradient by inputing %s with your colors", DZSAP_ID), '<strong>000000,ffffff</strong>') . '</div>
                </h4>';

            $val = 'ef6b13';


            $lab = 'design_wave_color_progress';
            if (isset($dzsap->mainoptions[$lab]) && $dzsap->mainoptions[$lab]) {
              $val = $dzsap->mainoptions[$lab];
            }

            echo '<div class="setting">
                    <h4 class="setting-label">' . esc_html__('Waveform Progress Color', DZSAP_ID) . '</div>
                    ' . DZSHelpers::generate_input_text($lab, array('seekval' => $val, 'type' => 'colorpicker', 'class' => 'colorpicker-nohash')) . '
                </h4>';
            ?>




            <?php

            $dependency = array(

              array(
                'element' => 'skinwave_wave_mode',
                'value' => array('image'),
              ),
            );
            ?>
            <div class="setting" data-dependency='<?php echo json_encode($dependency); ?>'>
              <h4 class="setting-label"><?php echo esc_html__('Multiplier', DZSAP_ID); ?></h4>
              <?php
              $val = 'ffffff';
              $lab = 'waveformgenerator_multiplier';
              if ($dzsap->mainoptions[$lab]) {
                $val = $dzsap->mainoptions[$lab];
              }
              echo DZSHelpers::generate_input_text($lab, array('val' => '1', 'seekval' => $val, 'type' => '', 'class' => ''));
              ?>
              <div
                class="sidenote"><?php echo esc_html__('If your waveformes come out a little flat and need some amplifying, you can increase this value .', DZSAP_ID); ?></div>
            </div>


            <div class="setting" data-dependency='<?php echo json_encode($dependency); ?>'>
              <h4 class="setting-label"><?php echo esc_html__('Waveform Style', DZSAP_ID); ?></h4>
              <?php echo DZSHelpers::generate_select('settings_wavestyle', array('options' => array('reflect', 'normal'), 'seekval' => $dzsap->mainoptions['settings_wavestyle'])); ?>

            </div>


            <div class="setting">
              <h4 class="setting-label"><?php echo esc_html__('Extra CSS', DZSAP_ID); ?></h4>
              <?php
              echo DZSHelpers::generate_input_textarea('extra_css', array(
                'val' => '',
                'extraattr' => ' rows="5" style="width: 100%;"',
                'seekval' => $dzsap->mainoptions['extra_css'],
              ));
              ?>

            </div>


          </div>
        </div>

        <div class="dzs-tab-tobe tab-disabled">
          <div class="tab-menu ">
            &nbsp;&nbsp;
          </div>
          <div class="tab-content">

          </div>
        </div>


        <div class="dzs-tab-tobe">
          <div class="tab-menu with-tooltip">
            <i class="fa fa-bar-chart"></i> <?php echo esc_html__("Social", DZSAP_ID) ?>
          </div>
          <div class="tab-content">
            <br>


            <?php


            echo DZSZoomSoundsHelper::generateOptionsFromConfigForMainOptions($config_main_options, 'settings_social', $dzsap);
            ?>

            <div class="dzs-container">
              <div class="full">
                <div class="setting">

                  <?php
                  $lab = 'analytics_enable';
                  echo DZSHelpers::generate_input_text($lab, array('id' => $lab, 'val' => 'off', 'input_type' => 'hidden'));
                  ?>
                  <h4 class="setting-label"><?php echo esc_html__('Enable Analytics', DZSAP_ID); ?></h4>
                  <div class="dzscheckbox skin-nova">
                    <?php
                    echo DZSHelpers::generate_input_checkbox($lab, array('id' => $lab, 'val' => 'on', 'seekval' => $dzsap->mainoptions[$lab])); ?>
                    <label for="<?php echo $lab; ?>"></label>
                  </div>
                  <div
                    class="sidenote"><?php echo esc_html__('activate analytics for the galleries', DZSAP_ID); ?></div>
                </div>


                <div class="setting">

                  <?php
                  $lab = 'analytics_enable_location';
                  echo DZSHelpers::generate_input_text($lab, array('id' => $lab, 'val' => 'off', 'input_type' => 'hidden'));
                  ?>
                  <h4 class="setting-label"><?php echo esc_html__('Track Users Country?', DZSAP_ID); ?></h4>
                  <div class="dzscheckbox skin-nova">
                    <?php
                    echo DZSHelpers::generate_input_checkbox($lab, array('id' => $lab, 'val' => 'on', 'seekval' => $dzsap->mainoptions[$lab])); ?>
                    <label for="<?php echo $lab; ?>"></label>
                  </div>
                  <div
                    class="sidenote"><?php echo esc_html__('use geolocation to track users country', DZSAP_ID); ?></div>
                </div>

                <div class="setting">

                  <?php
                  $lab = 'analytics_enable_user_track';
                  echo DZSHelpers::generate_input_text($lab, array('id' => $lab, 'val' => 'off', 'input_type' => 'hidden'));
                  ?>
                  <h4 class="setting-label"><?php echo esc_html__('Track Statistic by User?', DZSAP_ID); ?></h4>
                  <div class="dzscheckbox skin-nova">
                    <?php
                    echo DZSHelpers::generate_input_checkbox($lab, array('id' => $lab, 'val' => 'on', 'seekval' => $dzsap->mainoptions[$lab])); ?>
                    <label for="<?php echo $lab; ?>"></label>
                  </div>
                  <div
                    class="sidenote"><?php echo esc_html__('track views and minutes watched of each user', DZSAP_ID); ?></div>
                </div>


              </div>


            </div>


          </div>
        </div>


        <!-- END system check -->
        <div class="dzs-tab-tobe tab-disabled">
          <div class="tab-menu ">
            &nbsp;&nbsp;
          </div>
          <div class="tab-content">

          </div>
        </div>

        <div class="dzs-tab-tobe">
          <div class="tab-menu with-tooltip">
            <i class="fa fa-bookmark"></i> <?php echo esc_html__("Meta"); ?>
          </div>

          <div class="tab-content">
            <br>


            <div class="setting">
              <h4 class="setting-label"><?php echo esc_html__('Enable Meta Options for ... ', DZSAP_ID); ?></h4>
              <?php
              $mainPostTypesKey = 'dzsap_meta_post_types';


              $args = array(
                'public' => true,
                '_builtin' => false
              );

              $output = 'names'; // names or objects, note names is the default
              $operator = 'and'; // 'and' or 'or'

              $post_types = get_post_types($args, $output, $operator);


              echo DZSHelpers::generate_input_text($mainPostTypesKey . '[]', array('class' => 'styleme', 'input_type' => 'hidden', 'seekval' => '', 'val' => ''));
              echo '<label>';
              echo DZSHelpers::generate_input_checkbox($mainPostTypesKey . '[]', array('class' => 'styleme', 'def_value' => '', 'seekval' => $dzsap->mainoptions[$mainPostTypesKey], 'val' => 'post'));
              echo esc_html__(' post', DZSAP_ID);
              echo '</label>';
              echo '<br/>';
              echo '<label>';
              echo DZSHelpers::generate_input_checkbox($mainPostTypesKey . '[]', array('class' => 'styleme', 'def_value' => '', 'seekval' => $dzsap->mainoptions[$mainPostTypesKey], 'val' => 'page'));
              echo esc_html__(' page', DZSAP_ID);
              echo '</label>';
              echo '<br/>';
              foreach ($post_types as $key => $post_type) {

                $val = '';

                if (isset($dzsap->mainoptions[$mainPostTypesKey])) {
                  $val = $dzsap->mainoptions[$mainPostTypesKey];
                }
                echo '<label>';
                echo DZSHelpers::generate_input_checkbox($mainPostTypesKey . '[]', array('class' => 'styleme', 'def_value' => '', 'seekval' => $val, 'val' => $post_type));
                echo esc_html__(' ' . $post_type, DZSAP_ID);
                echo '</label>';
                echo '<br/>';
              }
              ?>
              <div class="clear"></div>
              <div
                class='sidenote'><?php echo sprintf(esc_html__('allows for %s meta options for these post types', DZSAP_ID), 'ZoomSounds'); ?></div>
              <div class="clear"></div>
            </div>


            <?php

            $nr = 1;
            $lab = 'extra_meta_label_' . $nr;

            $val = '';

            if (isset($dzsap->mainoptions[$lab])) {
              $val = $dzsap->mainoptions[$lab];
            }


            ?>
            <div class="setting">


              <h4
                class="setting-label"><?php echo sprintf(esc_html__('Optional Meta Box %s Label', DZSAP_ID), '<strong>' . $nr . '</strong>'); ?></h4>
              <?php

              echo DZSHelpers::generate_input_text($lab, array('class' => ' ', 'seekval' => $val));

              ?>

              <div
                class="sidenote"><?php echo esc_html__("place a optional meta box label - that can be replaced with in the zoomsounds extra html"); ?></div>
            </div>


            <?php

            $nr = 2;
            $lab = 'extra_meta_label_' . $nr;

            $val = '';

            if (isset($dzsap->mainoptions[$lab])) {
              $val = $dzsap->mainoptions[$lab];
            }


            ?>
            <div class="setting">


              <h4
                class="setting-label"><?php echo sprintf(esc_html__('Optional Meta Box %s Label', DZSAP_ID), '<strong>' . $nr . '</strong>'); ?></h4>
              <?php

              echo DZSHelpers::generate_input_text($lab, array('class' => ' ', 'seekval' => $val));

              ?>

              <div
                class="sidenote"><?php echo esc_html__("place a optional meta box label - that can be replaced with in the zoomsounds extra html"); ?></div>
            </div>


            <?php

            $nr = 3;
            $lab = 'extra_meta_label_' . $nr;

            $val = '';

            if (isset($dzsap->mainoptions[$lab])) {
              $val = $dzsap->mainoptions[$lab];
            }


            ?>
            <div class="setting">


              <h4
                class="setting-label"><?php echo sprintf(esc_html__('Optional Meta Box %s Label', DZSAP_ID), '<strong>' . $nr . '</strong>'); ?></h4>
              <?php

              echo DZSHelpers::generate_input_text($lab, array('class' => ' ', 'seekval' => $val));

              ?>

              <div
                class="sidenote"><?php echo esc_html__("place a optional meta box label - that can be replaced with in the zoomsounds extra html"); ?></div>
            </div>

          </div>
        </div>


        <!-- system check -->
        <div class="dzs-tab-tobe tab-disabled">
          <div class="tab-menu ">
            &nbsp;&nbsp;
          </div>
          <div class="tab-content">

          </div>
        </div>

        <div class="dzs-tab-tobe">
          <div class="tab-menu with-tooltip">
            <i class="fa fa-gear"></i> <?php echo esc_html__("System Check"); ?>
          </div>
          <div class="tab-content">

            <div class="setting">
              <h4 class="setting-label"><?php echo esc_html__("Audit Waveforms"); ?></h4>
              <?php

              $valIsWavesCheck = isset($_COOKIE[DZSAP_COOKIENAME_SYSTEM_CHECK_WAVES]) && $_COOKIE[DZSAP_COOKIENAME_SYSTEM_CHECK_WAVES] ? 'on' : 'off';


              $lab = DZSAP_COOKIENAME_SYSTEM_CHECK_WAVES;
              echo '<div class="dzscheckbox skin-nova">
' . DZSHelpers::generate_input_checkbox($valIsWavesCheck, array('id' => $lab, 'class' => ' ', 'val' => 'on', 'seekval' => $valIsWavesCheck)) . '
<label for="' . $lab . '"></label>
</div>';

              if ($valIsWavesCheck === 'on') {
                include_once(DZSAP_BASE_PATH . 'inc/php/admin/admin-systemCheck-wave-check-for-every-file.php');
                dzsap_admin_systemCheck_wavesCheckEacHFileInit();
              }
              ?>
            </div>


            <div class="setting">
              <h4 class="setting-label"><strong>GetText</strong> <?php echo esc_html__("Support"); ?></h4>
              <?php
              DZSZoomSoundsHelper::adminSystemCheckSupportedOrNotEcho(function_exists("gettext"));
              ?>
              <div class="sidenote"><?php echo esc_html__('translation support'); ?></div>
            </div>


            <div class="setting">
              <h4 class="setting-label">ZipArchive <?php echo esc_html__("Support"); ?></h4>
              <?php
              DZSZoomSoundsHelper::adminSystemCheckSupportedOrNotEcho(class_exists("ZipArchive"));
              ?>
              <div class="sidenote"><?php echo esc_html__('zip making for album download support'); ?></div>
            </div>

            <div class="setting">
              <h4 class="setting-label">Curl <?php echo esc_html__("Support"); ?></h4>
              <?php
              DZSZoomSoundsHelper::adminSystemCheckSupportedOrNotEcho(function_exists('curl_version'));
              ?>
              <div class="sidenote"><?php echo esc_html__('for making youtube / vimeo api calls'); ?></div>
            </div>

            <div class="setting">
              <h4 class="setting-label">allow_url_fopen <?php echo esc_html__("Support"); ?></h4>
              <?php
              DZSZoomSoundsHelper::adminSystemCheckSupportedOrNotEcho(ini_get('allow_url_fopen'));
              ?>

              <div class="sidenote"><?php echo esc_html__('for making youtube / vimeo api calls'); ?></div>
            </div>


            <div class="setting">

              <h4 class="setting-label"><?php echo esc_html__("PHP Version"); ?></h4>

              <div class="setting-text-ok">
                <?php
                echo phpversion();
                ?>
              </div>

              <div
                class="sidenote"><?php echo esc_html__('the install php version, 5.4 or greater required for facebook api'); ?></div>
            </div>


            <div class="setting">
              <h4 class="setting-label"><?php echo esc_html__("Server Information", DZSAP_ID); ?></h4>

              <table>
                <tbody>
                <tr>
                  <td><?php echo esc_html__("Server Information", DZSAP_ID) ?></td>
                  <td><?php echo print_r($_SERVER['SERVER_ADDR']) ?></td>
                </tr>
                <tr>
                  <td><?php echo esc_html__("getenv(\"HOME\")", DZSAP_ID) ?></td>
                  <td><?php echo print_r(getenv("HOME", true)) ?></td>
                </tr>
                <tr>
                  <td><?php echo esc_html__("ABSPATH", DZSAP_ID) ?></td>
                  <td><?php echo print_r(ABSPATH, true) ?></td>
                </tr>
                <tr>
                  <td><?php echo esc_html__("site_url()", DZSAP_ID) ?></td>
                  <td><?php echo print_r(site_url(), true) ?></td>
                </tr>
                </tbody>
              </table>

              <div class="sidenote"><?php echo esc_html__('server info'); ?></div>
            </div>


            <div class="setting">
              <h4 class="setting-label"><?php echo esc_html__("Permissions check"); ?></h4>


              <?php
              $role = get_role('administrator');
              $role->add_cap(DZSAP_TAXONOMY_NAME_SLIDERS . '_manage_categories');
              $role->add_cap('dzsap_manage_vpconfigs');
              ?>

              <?php
              $cap = DZSAP_TAXONOMY_NAME_SLIDERS . '_manage_categories';
              ?>
              <div class="permission-check-div">
                <strong class="permission"><?php
                  echo $cap;
                  ?>
                </strong> -
                <span class="label">

                            <?php
                            if (current_user_can($cap) || current_user_can('manage_options')) {
                              echo '<span class="setting-text-ok"><i class="fa fa-check"></i> ' . '' . esc_html__("allowed", DZSAP_ID) . '</span>';
                            } else {

                              echo '<span class="setting-text-notok"><i class="fa fa-times"></i> ' . '' . esc_html__("not allowed", DZSAP_ID) . '</span>';
                            }
                            ?>
                                </span>
              </div>

              <?php
              $cap = 'dzsap_manage_options';
              ?>
              <div class="permission-check-div">
                <strong class="permission"><?php
                  echo $cap;
                  ?>
                </strong> -
                <span class="label">

                            <?php
                            if (current_user_can('manage_options')) {
                              $role->add_cap($cap);
                            }
                            if (current_user_can($cap) || current_user_can('manage_options')) {
                              echo '<span class="setting-text-ok"><i class="fa fa-check"></i> ' . '' . esc_html__("allowed", DZSAP_ID) . '</span>';
                            } else {

                              echo '<span class="setting-text-notok"><i class="fa fa-times"></i> ' . '' . esc_html__("not allowed", DZSAP_ID) . '</span>';
                            }
                            ?>
                                </span>
              </div>

              <?php
              $cap = 'dzsap_make_shortcode';
              ?>
              <div class="permission-check-div">
                <strong class="permission"><?php
                  echo $cap;
                  ?>
                </strong> -
                <span class="label">

                            <?php
                            if (current_user_can('manage_options')) {
                              $role->add_cap($cap);
                            }
                            if (current_user_can($cap) || current_user_can('manage_options')) {
                              echo '<span class="setting-text-ok"><i class="fa fa-check"></i> ' . '' . esc_html__("allowed", DZSAP_ID) . '</span>';
                            } else {

                              echo '<span class="setting-text-notok"><i class="fa fa-times"></i> ' . '' . esc_html__("not allowed", DZSAP_ID) . '</span>';
                            }
                            ?>
                                </span>
              </div>

              <?php
              $cap = 'dzsap_manage_vpconfigs';
              ?>
              <div class="permission-check-div">
                <strong class="permission"><?php
                  echo $cap;
                  ?>
                </strong> -
                <span class="label">

                            <?php
                            if (current_user_can($cap) || current_user_can('manage_options')) {
                              echo '<span class="setting-text-ok"><i class="fa fa-check"></i> ' . '' . esc_html__("allowed", DZSAP_ID) . '</span>';
                            } else {

                              echo '<span class="setting-text-notok"><i class="fa fa-times"></i> ' . '' . esc_html__("not allowed", DZSAP_ID) . '</span>';
                            }
                            ?>
                                </span>
              </div>


            </div>


            <div class="setting">

              <h4 class="setting-label"><?php echo esc_html__("Analytics table status"); ?></h4>
              <?php
              global $wpdb;

              $table_name = $wpdb->prefix . 'dzsap_activity';

              $var = $wpdb->get_var("SHOW TABLES LIKE '$table_name'");


              if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {

                echo '<div class="setting-text-notok error">' . '' . esc_html__("table not installed") . '</div>';
              } else {
                echo '<div class="setting-text-ok"><i class="fa fa-check"></i> ' . '' . esc_html__("table ok") . '</div>';


                echo '<p class=""><a class="button-secondary repair-table" href="' . admin_url('admin.php?page=dzsap-mo&tab=17&analytics_table_repair=on') . '">' . esc_html__("repair table") . '</a></p>';


                echo '<p class=""><a class="button-secondary" href="' . admin_url('admin.php?page=dzsap-mo&tab=17&show_analytics_table_last_10_rows=on') . '">' . esc_html__("check last 10 rows") . '</a></p>';


                if (isset($_GET['show_analytics_table_last_10_rows']) && $_GET['show_analytics_table_last_10_rows'] == 'on') {

                  $query = 'SELECT * FROM ' . $table_name . ' ORDER BY id DESC LIMIT 10';
                  $results = $GLOBALS['wpdb']->get_results($query, OBJECT);

                  print_rr($results);
                }
                if (isset($_GET['analytics_table_repair']) && $_GET['analytics_table_repair'] == 'on') {


                  $query = 'SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA=' . DB_NAME . ' AND TABLE_NAME=' . $table_name . ' AND column_name=%s';


                  $val = $wpdb->query($wpdb->prepare($query, 'country'));


                  $sw = false;
                  if ($val !== FALSE) {


                    if ($val->num_rows > 0) {


                    } else {

                      $query = 'ALTER TABLE ' . $table_name . ' ADD `country` mediumtext NULL ;';


                      $val = $wpdb->query($query);


                      $sw = true;


                    }

                  }

                  $query = 'SELECT * FROM INFORMATION_SCHEMA.COLUMNS
           WHERE TABLE_SCHEMA=\'' . DB_NAME . '\' AND TABLE_NAME=\'' . $table_name . '\' AND column_name=\'val\'';


                  $val = $wpdb->query($query);


                  if ($val !== FALSE) {


                    if ($val->num_rows > 0) {


                    } else {

                      $query = 'ALTER TABLE `' . $table_name . '` ADD `val` int(255) NULL ;';


                      $val = $wpdb->query($query);


                      $sw = true;


                    }

                  }

                  if ($sw) {

                    echo 'table repaired!';
                  } else {

                    echo 'table was already okay';


                  }


                }

              }
              ?>

              <?php
              if (ini_get('allow_url_fopen')) {
              } else {

              }
              ?>

              <div class="sidenote"><?php echo esc_html__('check if the analytics table exists'); ?></div>
            </div>


            <div class="setting">

              <h4 class="setting-label"><?php echo esc_html__("Backup log"); ?></h4>

              <pre><?php
                $logged_backups = array();
                try {

                  $logged_backups = json_decode(get_option('dzsap_backuplog'), true);
                } catch (Exception $err) {

                }

                if (is_array($logged_backups) == false) {
                  $logged_backups = array();
                }


                foreach ($logged_backups as $lb) {
                  echo date("F j, Y, g:i a", $lb) . '<br>';
                }
                ?></pre>
            </div>


          </div>
        </div>
        <!-- system check END -->


        <div class="dzs-tab-tobe tab-disabled">
          <div class="tab-menu ">
            &nbsp;&nbsp;
          </div>
          <div class="tab-content">

          </div>
        </div>

        <div class="dzs-tab-tobe">
          <div class="tab-menu with-tooltip">
            <i class="fa fa-gears"></i> <?php echo esc_html__("Amazon s3") ?>
          </div>
          <div class="tab-content">


            <div class="setting">

              <h3 class="setting-label"><?php echo esc_html__("AWS Support", DZSAP_ID); ?></h3>

              <?php


              if (isset($_GET['install_aws']) && $_GET['install_aws'] == 'on') {


                $aux = 'https://s3.eu-west-3.amazonaws.com/zoomitflash-test-bucket/aws.zip';
                $res = DZSHelpers::get_contents($aux);


                if ($res === false) {
                  echo 'server offline';
                } else {
                  if (strpos($res, '<div class="error">') === 0) {
                    echo $res;


                    if (strpos($res, '<div class="error">error: in progress') === 0) {

                      $dzsap->mainoptions['dzsap_purchase_code_binded'] = 'on';
                      update_option(DZSAP_DBNAME_OPTIONS, $dzsap->mainoptions);
                    }
                  } else {

                    file_put_contents(dirname(__FILE__) . '/aws.zip', $res);
                    if (class_exists('ZipArchive')) {
                      $zip = new ZipArchive;
                      $res = $zip->open(dirname(__FILE__) . '/aws.zip');

                      if ($res === TRUE) {

                        $zip->extractTo(dirname(__FILE__));
                        $zip->close();


                      } else {
                        echo 'failed, code:' . $res;
                      }
                      echo esc_html__('Update installed.');
                    } else {

                      echo esc_html__('ZipArchive class not found.');
                    }

                  }
                }

              }


              ?>
            </div>

            <?php
            if (file_exists(dirname(__FILE__) . '/aws/aws-autoloader.php')) {
              ?>



              <div class="setting">
              <h5 class="label"><?php echo esc_html__('Enable AWS Support', DZSAP_ID); ?></h5>
              <?php
              $lab = 'aws_enable_support';
              echo DZSHelpers::generate_input_text($lab, array('id' => $lab, 'class' => 'fake-input', 'val' => 'off', 'input_type' => 'hidden'));
              echo '<div class="dzscheckbox skin-nova">
                                        ' . DZSHelpers::generate_input_checkbox($lab, array('id' => $lab, 'class' => 'mainsetting', 'val' => 'on', 'seekval' => $dzsap->mainoptions[$lab])) . '
                                        <label for="' . $lab . '"></label>
                                    </div>';
              ?>
              <div
                class="sidenote"><?php echo sprintf(esc_html__('enable aws support', DZSAP_ID), '/', 'wp-content/dzsap_backups'); ?></div>
              </div><?php


              $lab = 'aws_key';
              ?>
              <div class="setting">


                <h5 class="label">Amazon S3 <?php echo esc_html__('Key', DZSAP_ID); ?></h5>
                <?php

                echo DZSHelpers::generate_input_text($lab, array('class' => ' ', 'seekval' => $dzsap->mainoptions[$lab]));

                ?>
                <div class="sidenote"><?php echo dzs_esc__(esc_html__("tutorial %shere%s", DZSAP_ID),

                    array('<a target="_blank" href="https://zoomthe.me/knowledge-base/zoomsounds-audio-player/article/how-to-enable-amazon-s3-support-for-reading-files-from-bucket/">',
                      '</a>')
                  ); ?></div>


              </div>
              <?php


              $lab = 'aws_key_secret';
              ?>
              <div class="setting">


                <h5 class="label">Amazon S3 <?php echo esc_html__('Secret', DZSAP_ID); ?></h5>
                <?php

                echo DZSHelpers::generate_input_text($lab, array('class' => ' ', 'seekval' => $dzsap->mainoptions[$lab]));

                ?>


              </div>
              <?php


              $lab = 'aws_region';
              ?>
              <div class="setting">


                <h5 class="label">Amazon S3 <?php echo esc_html__('Region code', DZSAP_ID); ?></h5>
                <?php

                echo DZSHelpers::generate_input_text($lab, array('class' => ' ', 'seekval' => $dzsap->mainoptions[$lab]));

                ?>
                <div
                  class="sidenote"><?php echo dzs_esc__(esc_html__("region code ( ie. %s ) - full list %shere%s", DZSAP_ID), array(
                    '<strong>eu-west</strong>',
                    '<a target="_blank" href="https://docs.aws.amazon.com/general/latest/gr/rande.html">',
                    '</a>'
                  )); ?></div>


              </div>
              <?php


              $lab = 'aws_bucket';
              ?>
              <div class="setting">


                <h5 class="label">Amazon S3 <?php echo esc_html__('Bucket', DZSAP_ID); ?></h5>
                <?php

                echo DZSHelpers::generate_input_text($lab, array('class' => ' ', 'seekval' => $dzsap->mainoptions[$lab]));

                ?>


              </div>
              <?php

            } else {

              echo '<p class=""><a class="button-secondary repair-table" href="' . admin_url('admin.php?page=dzsap-mo&tab=17&install_aws=on') . '">' . esc_html__("install aws", DZSAP_ID) . '</a></p>';
            } ?>

          </div>
        </div>


        <div class="dzs-tab-tobe tab-disabled">
          <div class="tab-menu ">
            &nbsp;&nbsp;
          </div>
          <div class="tab-content">

          </div>
        </div>

        <div class="dzs-tab-tobe">
          <div class="tab-menu with-tooltip">
            <i class="fa fa-gears"></i> <?php echo esc_html__("Developer") ?>
          </div>
          <div class="tab-content">
            <br>


            <!-- developer tab content -->

            <?php

            echo DZSZoomSoundsHelper::generateOptionsFromConfigForMainOptions($config_main_options, 'developer_settings', $dzsap);
            ?>



            <?php
            $lab = 'pcm_notice';
            ?>
            <div class="setting">
              <?php
              echo DZSHelpers::generate_input_text($lab, array('id' => $lab, 'input_type' => 'hidden', 'class' => 'mainsetting', 'val' => 'off'))
              ?>
              <h4 class="setting-label"><?php echo esc_html__('Wave Generating Notice', DZSAP_ID); ?></h4>
              <?php
              echo '<div class="dzscheckbox skin-nova">
                                        ' . DZSHelpers::generate_input_checkbox($lab, array('id' => $lab, 'class' => 'mainsetting', 'val' => 'on', 'seekval' => $dzsap->mainoptions[$lab])) . '
                                        <label for="' . $lab . '"></label>
                                    </div>';
              ?>
              <div
                class="sidenote"><?php echo esc_html__('display the wave generating notice - or else the notice will not show but the wave forms will still generate', DZSAP_ID); ?></div>
            </div>


            <div class="setting">
              <h4 class="setting-label"><?php echo esc_html__('Safe Binding?', DZSAP_ID); ?></h4>

              <?php
              $lab = 'is_safebinding';
              echo '<div class="dzscheckbox skin-nova">
' . DZSHelpers::generate_input_checkbox($lab, array('id' => $lab, 'class' => 'mainsetting', 'val' => 'on', 'seekval' => $dzsap->mainoptions[$lab])) . '
 <label for="' . $lab . '"></label>
</div>';
              ?>
              <div
                class="sidenote"><?php echo esc_html__('the galleries admin can use a complex ajax backend to ensure fast editing, but this can cause limitation issues on php servers. Turn this to on if you want a faster editing experience ( and if you have less then 20 videos accross galleries ) ', DZSAP_ID); ?></div>
            </div>
            <div class="setting">
              <h4 class="setting-label"><?php echo esc_html__('Do Not Use Caching', DZSAP_ID); ?></h4>
              <?php
              $lab = 'use_api_caching';
              echo '<div class="dzscheckbox skin-nova">
                                        ' . DZSHelpers::generate_input_checkbox($lab, array('id' => $lab, 'class' => 'mainsetting', 'val' => 'off', 'seekval' => $dzsap->mainoptions[$lab])) . '
    <label for="' . $lab . '"></label>
</div>';
              ?>
              <div
                class="sidenote"><?php echo esc_html__('use caching for vimeo / youtube api ( recommended - on )', DZSAP_ID); ?></div>
            </div>


            <div class="setting">
              <h4 class="setting-label"><?php echo esc_html__('Force File Get Contents', DZSAP_ID); ?></h4>
              <?php
              $lab = 'force_file_get_contents';
              echo '<div class="dzscheckbox skin-nova">
                                        ' . DZSHelpers::generate_input_checkbox($lab, array('id' => $lab, 'class' => 'mainsetting', 'val' => 'on', 'seekval' => $dzsap->mainoptions[$lab])) . '
                                        <label for="' . $lab . '"></label>
                                    </div>';
              ?>
              <div
                class="sidenote"><?php echo esc_html__('sometimes curl will not work for retrieving youtube user name / playlist - try enabling this option if so...', DZSAP_ID); ?></div>
            </div>


            <div class="setting">
              <h4 class="setting-label"><?php echo esc_html__('Hide Audio Items from menu', DZSAP_ID); ?></h4>
              <?php
              $lab = 'dzsap_items_hide';


              echo DZSHelpers::generate_input_text($lab, array('id' => $lab, 'input_type' => 'hidden', 'class' => 'mainsetting', 'val' => 'off'));

              echo '<div class="dzscheckbox skin-nova">
                                        ' . DZSHelpers::generate_input_checkbox($lab, array('id' => $lab, 'class' => 'mainsetting', 'val' => 'on', 'seekval' => $dzsap->mainoptions[$lab])) . '
                                        <label for="' . $lab . '"></label>
                                    </div>';
              ?>
              <div
                class="sidenote"><?php echo esc_html__('hide the items', DZSAP_ID); ?></div>
            </div>


            <div class="setting">
              <h4 class="setting-label"><?php echo esc_html__('Force Refresh Size Every 1000ms', DZSAP_ID); ?></h4>
              <?php
              $lab = 'settings_trigger_resize';
              echo '<div class="dzscheckbox skin-nova">
                                        ' . DZSHelpers::generate_input_checkbox($lab, array('id' => $lab, 'class' => 'mainsetting', 'val' => 'on', 'seekval' => $dzsap->mainoptions[$lab])) . '
                                        <label for="' . $lab . '"></label>
                                    </div>';
              ?>
              <div
                class="sidenote"><?php echo esc_html__('sometimes sizes need to be recalculated ( for example if you use the gallery in tabs )', DZSAP_ID); ?></div>
            </div>


            <div class="setting">
              <h4 class="setting-label"><?php echo esc_html__('Enable Powerpress Support', DZSAP_ID); ?></h4>
              <?php $lab = 'replace_powerpress_plugin';
              echo '<div class="dzscheckbox skin-nova">
                                        ' . DZSHelpers::generate_input_checkbox($lab, array('id' => $lab, 'class' => 'mainsetting', 'val' => 'on', 'seekval' => $dzsap->mainoptions[$lab])) . '
                                        <label for="' . $lab . '"></label>
                                    </div>';
              ?>
              <div
                class="sidenote"><?php echo esc_html__('replace the current powerpress player with zoomsounds ', DZSAP_ID); ?></div>
            </div>


            <div class="setting">
              <h4 class="setting-label"><?php echo 'Powerpress - ';
                echo esc_html__(' try to read category data ', DZSAP_ID);
                echo 'xml'; ?></h4>
              <?php $lab = 'powerpress_read_category_xml';
              echo '<div class="dzscheckbox skin-nova">
                                        ' . DZSHelpers::generate_input_checkbox($lab, array('id' => $lab, 'class' => 'mainsetting', 'val' => 'on', 'seekval' => $dzsap->mainoptions[$lab])) . '
                                        <label for="' . $lab . '"></label>
                                    </div>';
              ?>
              <div
                class="sidenote"><?php echo esc_html__('replace the current powerpress player with zoomsounds ', DZSAP_ID); ?></div>
            </div>


            <?php
            $lab = 'js_init_timeout';
            ?>
            <div class="setting">


              <h4 class="setting-label"><?php echo esc_html__('Javascript Init Timeout', DZSAP_ID); ?></h4>
              <?php

              echo DZSHelpers::generate_input_text($lab, array('class' => ' ', 'seekval' => $dzsap->mainoptions[$lab]));

              ?>

              <div
                class="sidenote"><?php echo esc_html__("place a timeout for initializing the player ( in ms ) "); ?></div>
            </div>


            <?php

            $lab = 'wavesurfer_pcm_length';


            ?>
            <div class="setting">


              <h4 class="setting-label"><?php echo esc_html__('Precision', DZSAP_ID); ?></h4>
              <?php

              echo DZSHelpers::generate_input_text($lab, array('class' => ' ', 'seekval' => $dzsap->mainoptions[$lab]));

              ?>

              <div
                class="sidenote"><?php echo esc_html__("higher is more precise, but occupies more storage space", DZSAP_ID); ?></div>
            </div>


            <?php

            $lab = 'extra_js';


            ?>
            <div class="setting">


              <h4 class="setting-label"><?php echo esc_html__('Extra Javascript', DZSAP_ID); ?></h4>
              <?php echo dzsap_misc_input_textarea($lab, array('val' => '', 'seekval' => stripslashes($dzsap->mainoptions[$lab]))); ?>

              <div class="sidenote"><?php echo esc_html__("extra javascript on page load"); ?></div>
            </div>


          </div>
        </div>


        <?php
        do_action('dzsap_mainoptions_after_last_tab');
        ?>

      </div>


      <br/>
      <br/>
      <br/>
      <a href='#'
         class="button-primary save-btn dzsap-save-main-options save-mainoptions"><?php echo esc_html__('Save Options', DZSAP_ID); ?></a>
    </form>
    <br/><br/>


    <div class="dzstoggle toggle1<?php

    $lab = 'track_id';
    if (isset($_GET[$lab])) {
      echo ' active';
    }

    ?>" rel="">
      <div class="toggle-title" style=""><?php echo esc_html__('Analyze track data', DZSAP_ID); ?></div>
      <div class="toggle-content" style="<?php

      $lab = 'track_id';
      if (isset($_GET[$lab])) {
        echo 'height: auto;';
      }

      ?>">


        <div
          class="sidenote"><?php echo esc_html__("Analyze wave data or generate wave data for a single track."); ?></div>

        <form action="admin.php?page=dzsap-mo" method="get">
          <div class="setting">

            <h4 class="setting-label"><?php echo esc_html__("Track"); ?><?php echo esc_html__("Id"); ?></h4>

            <?php


            $lab = 'page';
            echo DZSHelpers::generate_input_text($lab, array(
              'seekval' => 'dzsap-mo',
              'input_type' => 'hidden',
            ));

            $lab = 'track_id';
            $val = '';

            if (isset($_GET[$lab])) {
              $val = $_GET[$lab];
            }
            echo DZSHelpers::generate_input_text($lab, array('seekval' => $val));
            ?>
            <div class="sidenote"><?php echo esc_html__("get track by id or source"); ?></div>
          </div>
          <div class="setting">

            <h4 class="setting-label"><?php echo esc_html__("Get pcm from url"); ?></h4>

            <?php


            $lab = 'track_source';
            $val = '';

            if (isset($_GET[$lab])) {
              $val = $_GET[$lab];
            }
            echo DZSHelpers::generate_input_text($lab, array('seekval' => $val));
            ?>
            <div
              class="sidenote"><?php echo esc_html__("( donor mp3 ) ( optional ) get pcm data from another mp3 url"); ?></div>
          </div>
          <button class="button-secondary" name="dzsap_action"
                  value="generate_wave"><?php echo esc_html__("Get Track Data"); ?></button>

        </form>

        <?php


        if (isset($_GET[$lab])){
        ?>

        <div class="setting">

          <h4 class="setting-label"><?php echo esc_html__("Flash"); ?><?php echo esc_html__("Tool"); ?></h4>


          <?php


          global $dzsap;


          $lab = 'track_id';
          $val = '';

          if (isset($_GET[$lab])) {
            $val = $_GET[$lab];
          }


          $id = $val;
          $id = DZSZoomSoundsHelper::sanitize_toKey($id);
          $media_po = get_post($id);

          $flash_src = '';

          $src = wp_get_attachment_url($id);


          if (isset($_GET['track_source'])) {
            $flash_src = $_GET['track_source'];
          } else {
            $flash_src = $src;
          }

          $aux = '';

          $urlIframe = admin_url() . 'admin.php';

          $urlIframe = add_query_arg('page', DZSAP_ADMIN_PAGENAME_MAINOPTIONS, $urlIframe);
          $urlIframe = add_query_arg(DZSAP_ADMIN_PAGENAME_MAINOPTIONS_WAVE_GENERATOR, 'on', $urlIframe);
          $urlIframe = add_query_arg('track_id', $id, $urlIframe);

          $aux .= '<iframe class="regenerate-waveform-iframe" src="
' . $urlIframe . '" width="100%" height="530"></iframe>';


          echo $aux;

          ?>
        </div>


        <div
          class="sidenote"><?php echo esc_html__("copy the text from above in the box below to overwrite pcm data"); ?>
          - <?php echo sprintf(esc_html__("in order to save the pcm data fro mthe flash tool, click the text above and press %s ( %s ) "), "ctrl + a", esc_html__("Select All")); ?></div>

        <div class="setting">

          <h4 class="setting-label"><?php echo esc_html__("PCM"); ?><?php echo esc_html__("Data"); ?></h4>
          <?php

          $lab = 'dzsap_pcm_data';


          $val = get_option($lab . '_' . $id);
          echo DZSHelpers::generate_input_textarea($lab, array(
            'seekval' => $val,
            'extraattr' => ' data-id="' . $id . '" style="width: 100%;" rows="5" ',
          ));
          ?>

          <button name="dzsap_save_pcm" value="on"
                  class="button-secondary"><?php echo esc_html__('Save PCM Data From Textarea', DZSAP_ID); ?></button>
        </div>
      </div><!-- end toggle content -->
      <?php
      // -- end analyzing
      }

      ?>


    </div>
    <!-- end analyze track data -->


    <div class="dzstoggle toggle1">
      <div class="toggle-title" style=""><?php echo esc_html__('Delete Plugin Data', DZSAP_ID); ?></div>
      <div class="toggle-content" style="">
        <br>
        <form class="mainsettings" method="POST">
          <button name="dzsap_delete_plugin_data" value="on"
                  class="button-secondary"><?php echo esc_html__('Delete plugin data', DZSAP_ID); ?></button>
        </form>
        <br>
        <form class="mainsettings" method="POST">
          <?php
          $nonce = wp_create_nonce('dzsap_delete_waveforms_nonce');
          ?>
          <input type="hidden" name="action" value="<?php echo DZSAP_AJAX_DELETE_CACHE_WAVEFORM_DATA ?>"/>
          <input type="hidden" name="nonce" value="<?php echo $nonce; ?>"/>
          <button
            class="button-secondary btn-delete-waveform-data"><?php echo esc_html__('Delete waveform data', DZSAP_ID); ?></button>
        </form>
        <br>
        <form class="mainsettings" method="POST">
          <?php
          $nonce = wp_create_nonce(DZSAP_AJAX_DELETE_CACHE_TOTAL_TIMES . '_nonce');
          ?>
          <input type="hidden" name="action" value="<?php echo DZSAP_AJAX_DELETE_CACHE_TOTAL_TIMES ?>"/>
          <input type="hidden" name="nonce" value="<?php echo $nonce; ?>"/>
          <button
            class="button-secondary btn-delete-cache-times"><?php echo esc_html__('Delete total times', DZSAP_ID); ?></button>
        </form>
        <br>

      </div>

    </div>


    <div class="feedbacker" style=""><img alt="" style="" id="save-ajax-loading2"
                                          src="<?php echo site_url(); ?>/wp-admin/images/wpspin_light.gif"/>
    </div>
  </div>
  <div class="clear"></div><br/>
<?php
