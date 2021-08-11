<?php


/**
 * init from ?dzsap_wave_regenerate=on
 */
function dzsap_wave_regenerate_admin_page() {
  ?>
  <div class="wrap">


    <?php

    $trackUrl = '';
    $trackId = '';
    $trackUrlId = '';

    $isShowTrackInfo = !(isset($_GET['disable_isShowTrackInfo']) && $_GET['disable_isShowTrackInfo']==='on');




    if (isset($_REQUEST['track_url'])) {
      $trackUrl = $_REQUEST['track_url'];
    }
    if (isset($_REQUEST['track_id'])) {
      $trackId = $_REQUEST['track_id'];
    }
    if (isset($_REQUEST['track_url_id'])) {
      $trackUrlId = $_REQUEST['track_url_id'];
    }

    $trackData = DZSZoomSoundsHelper::media_getUrlIdAndSourceId($trackUrl, $trackId, $trackUrlId);


    $trackUrl = $trackData['trackUrl'];
    $trackId = $trackData['trackId'];
    $trackUrlId = $trackData['trackUrlId'];







    ?>
    <div class="con-maindemo wavegenerator-con" id="a-demo">
      <?php
      if($isShowTrackInfo){
      ?>
      <div class="container">
        <h4><?php echo esc_html__('Track info', DZSAP_ID) ?></h4>
        <form name="track-info" action="<?php echo admin_url('admin.php?page=dzsap-mo&'.DZSAP_ADMIN_PAGENAME_MAINOPTIONS_WAVE_GENERATOR.'=on') ?>"
              class="<?php echo $trackUrl ? 'disabled-inputs' : '' ?>" method="POST">
          <?php
          if (!$trackId && !$trackUrl) {
            ?>
            <p class="sidenote"
               style="font-style: italic;"><?php echo esc_html__('you can input a track source or id', DZSAP_ID) ?></p>
            <?php
          }
          ?>

          <div class="dzs-row">
            <div class="dzs-col-md-4">
              <h6><?php echo esc_html__('track url', DZSAP_ID) ?></h6>
              <input name="track_url" value="<?php echo htmlspecialchars($trackUrl, ENT_QUOTES) ?>"/>
            </div>

            <div class="dzs-col-md-4">
              <h6><?php echo esc_html__('id', DZSAP_ID) ?></h6>
              <input name="track_id" value="<?php echo $trackId ?>"/>
            </div>

            <div class="dzs-col-md-4">
              <h6><?php echo esc_html__('url id', DZSAP_ID) ?></h6>
              <input name="track_url_id" value="<?php echo $trackUrlId ?>"/>
            </div>
          </div>
          <br>

          <div class="dzs-row">
            <div class="dzs-col-md-12">
              <button class="button-secondary"><?php echo esc_html__('Get wavedata', DZSAP_ID) ?></button>
            </div>
          </div>
        </form>
      </div>
      <?php


      }

      if ($trackUrl) {

        ?>

        <form class="track-waveform-meta" method="POST">

          <h4><?php echo esc_html__('Track meta', DZSAP_ID) ?></h4>


          <input name="wavedata_track_url" type="hidden"/>
          <input name="wavedata_track_id" type="hidden"/>
          <input name="wavedata_track_url_id" type="hidden"/>
          <br>
          <br>
          <div class="dzsap-wave-generator auto-init"
               data-options='{"source":"<?php echo $trackUrl ?>", "selectorWaveData":"textarea[name=wavedata_pcm]"}'>
            <div class="dzsap-wave-generator--status"><?php echo esc_html__('waiting init', DZSAP_ID) ?></div>
            <div class="dzsap-wave-generator--wave"></div>
          </div>
          <br>
          <h6><?php echo esc_html__('pcm data', DZSAP_ID) ?></h6>
          <textarea name="wavedata_pcm" style="display: block; width: 100%; height: 50px;"></textarea>
          <br>
          <button class="button-primary dzsap-btn--submit-pcm"><?php echo esc_html__('submit', DZSAP_ID) ?></button>
        </form>
        <?php

      }

      ?>


    </div>
  </div>



  <?php
  wp_enqueue_script('wavesurfer', DZSAP_BASE_URL . 'audioplayer/wavesurfer.js');
  wp_enqueue_style('dzs.remove_wp_bar', DZSAP_BASE_URL . 'admin/remove-wp-style.css');
  $js_url = DZSAP_BASE_URL . 'audioplayer/dzsap-wave-generator.js';
  if (defined('DZSAP_DEBUG_LOCAL_SCRIPTS') && DZSAP_DEBUG_LOCAL_SCRIPTS === true) {
    $js_url = 'http://devsite/zoomsounds/source/audioplayer/dzsap-wave-generator.js';
  }
  wp_enqueue_script('dzsap-regenerate-waveform', $js_url);

}

