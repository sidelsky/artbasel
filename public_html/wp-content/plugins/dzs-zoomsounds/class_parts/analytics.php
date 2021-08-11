<?php


function dzsap_analytics_dashboard_content() {
  global $dzsap;


  dzsap_analytics_get();
  if ($dzsap->analytics_views == false) {
    $dzsap->analytics_views = array();
  }
  if ($dzsap->analytics_minutes == false) {
    $dzsap->analytics_minutes = array();
  }


  $str_views = '';
  $str_minutes = '';


  $added_view = false;


  $videos_views = array();

  // -- sample data


  $locs_array = array();


  if ((isset($_GET['action']) && $_GET['action'] == 'dzsap_show_analytics_for_video') == false) {

    $arr = array(
      'labels' => array(esc_html__('Track'), esc_html__('Views'), esc_html__('Likes')),
      'lastdays' => array(),
    );

    for ($i = 15; $i >= 0; $i--) {


      $day_label = date("d M", time() - 60 * 60 * 24 * $i);


      // -- chart

      $trackid = '0';


      $aux = array(

        $day_label,
        dzsap_mysql_get_track_activity($trackid, array(
          'get_last' => 'day',
          'day_start' => ($i + 1),
          'day_end' => ($i),
          'type' => 'view',
          'get_count' => 'off',
        )),
        dzsap_mysql_get_track_activity($trackid, array(
          'get_last' => 'day',
          'day_start' => ($i + 1),
          'day_end' => ($i),
          'type' => 'like',
          'get_count' => 'off',
        )),
      );

      array_push($arr['lastdays'], $aux);;


    }

    // -- printing data for analytics here
    ?>

    <div class="dzsap-analytics-hidden-data-general" style="display: none;"><?php echo json_encode($arr); ?></div>


    <?php

    $arr = array(
      'labels' => array(esc_html__('Track'), esc_html__('Downloads')),
      'lastdays' => array(),
    );

    for ($i = 15; $i >= 0; $i--) {


      $day_label = date("d M", time() - 60 * 60 * 24 * $i);


      // -- chart

      $trackid = '0';


      $aux = array(

        $day_label,
        dzsap_mysql_get_track_activity($trackid, array(
          'get_last' => 'day',
          'day_start' => ($i + 1),
          'day_end' => ($i),
          'type' => 'download',
          'get_count' => 'off',
        )),

      );

      array_push($arr['lastdays'], $aux);;
    }
    ?>


    <div class="dzsap-analytics-hidden-data-timewatched" style="display: none;"><?php echo json_encode($arr); ?></div>

    <div id="dzsap_chart_div"></div>
    <div id="dzsap_chart_div-timewatched"></div>




    <?php

  }


  if ((isset($_GET['action']) && $_GET['action'] == 'dzsap_show_analytics_for_video') == 'dadada') {


    for ($i = 30; $i >= 0; $i--) {


      $date_aux = date("Y-m-d", time() - 60 * 60 * (24 * $i));


      // -- @views
      $views = 0;


      foreach ($dzsap->analytics_views as $av) {


        if ($date_aux == $av['date']) {

          $views += $av['views'];


          $sw_found = false;
          foreach ($videos_views as $lab => $vv) {
            if ($vv['video_title'] == $av['video_title']) {

              $videos_views[$lab]['views'] += $av['views'];

              $sw_found = true;
              break;
            }
          }

          if (!$sw_found) {
            array_push($videos_views, array(
              'video_title' => $av['video_title'],
              'views' => $av['views'],
              'seconds' => '0',
            ));
          }
        }


        if ($dzsap->mainoptions['analytics_enable_location'] == 'on') {

          if (isset($av['country'])) {
            if (isset($locs_array[$av['country']])) {

              $locs_array[$av['country']] += $av['views'];
            } else {

              $locs_array[$av['country']] = $av['views'];
            }
          }

        }
      }

      if ($views > 0) {
        $str_views .= ',';

        if ($date_aux && $views) {

          $str_views .= '["' . $date_aux . '", ' . $views . ']';
        } else {

          $str_views .= '[\'' . date("Y-n-j") . '\',0]';
        }


        $added_view = true;
      }


      // -- @minutes
      $views = 0;
      foreach ($dzsap->analytics_minutes as $av) {

        if ($date_aux == $av['date']) {

          $views += $av['seconds'];


          $sw_found = false;
          foreach ($videos_views as $lab => $vv) {
            if ($vv['video_title'] == $av['video_title']) {

              $videos_views[$lab]['seconds'] += $av['seconds'];

              $sw_found = true;
              break;
            }
          }

          if (!$sw_found) {
            array_push($videos_views, array(
              'video_title' => $av['video_title'],
              'views' => '0',
              'seconds' => $av['seconds'],
            ));
          }
        }
      }

      if ($str_minutes == '') {
        $str_minutes = 0;
      }

      if ($views > 0) {
        $str_minutes .= ',';

        $str_minutes .= '["' . $date_aux . '", ' . intval($views) . ']';

        $added_view = true;
      } else {

        $str_minutes .= ',';
        $str_minutes .= '["' . $date_aux . '", ' . '0' . ']';
      }


      // -- tbc minutes will go here as well


    }


    $str_locs = '';

    if ($dzsap->mainoptions['analytics_enable_location'] == 'on') {
      foreach ($locs_array as $lab => $val) {

        if ($val > 0) {
          $str_locs .= ',';

          $str_locs .= '["' . $lab . '", ' . $val . ']';

          $added_view = true;
        }
      }
    }

    wp_enqueue_script('dzsap-shortcode-analytics', DZSAP_BASE_URL.'inc/js/shortcodes/analytics.js');

    ?>


    <br>
    <br>


    <?php
  }

}