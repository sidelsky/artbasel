<?php

function dzsap_ajax_downloadTrack() {
  global $dzsap;

  function canUserDownload() {
    global $dzsap;
    if ($dzsap->mainoptions['allow_download_only_for_registered_users'] == 'on') {
      // -- registered download
      global $current_user;

      if ($current_user->ID) {


        if ($dzsap->mainoptions['allow_download_only_for_registered_users_capability'] && $dzsap->mainoptions['allow_download_only_for_registered_users_capability'] != 'read') {


          if (!(current_user_can(DZSAP_PERMISSION_ULTIMATE) || DZSZoomSoundsHelper::user_has_role_cap($current_user, $dzsap->mainoptions['allow_download_only_for_registered_users_capability']))) {
            return array(
              'error' => true,
              'message' => esc_html__("You do not have permission", DZSAP_ID),
            );
          }
        }
      } else {
        return array(
          'error' => true,
          'message' => esc_html__("You need to register", DZSAP_ID),
        );
      }
    }

    if (isset($_GET['id']) && $_GET['id']) {

    } else {

      if (isset($_GET['link']) && $_GET['link']) {

      } else {

        return array(
          'error' => true,
          'message' => esc_html__("You need to set media id", DZSAP_ID),
        );
      }
    }

    return array(
      'error' => false,
      'message' => '',
    );
  }

  function setHeaders($content_type, $fileName_in_header) {

    header("Pragma: public");
    header("Expires: 0");

    header("Content-Type: '.$content_type.'");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: public");
    header("Content-Description: File Transfer");
    header("Content-type: application/octet-stream");
    header("Content-Disposition: attachment; filename=\"" . $fileName_in_header . "\"");
    header("Content-Transfer-Encoding: binary");
  }

  // -- download here

  $canUserDownload = canUserDownload();
  if ($canUserDownload['error']) {
    die($canUserDownload['message']);
  }

  $filenameFromTitle = '';
  $fileUrl = '';
  $audioFilePath = '';
  $filePathForDownload = '';
  $fileName_in_header = '';

  if (isset($_GET['id']) && $_GET['id']) {
    // -- try to download based on path
    $po = get_post($_GET['id']);
    $pid = $_GET['id'];

    if ($po && $po->post_title) {
      $filenameFromTitle = $po->post_title;
    }


    $mockPostId = 0;
    $mockMargs = array();

    if ($po->post_type == 'product') {
      $fileUrl = $dzsap->get_track_source($po->ID, $mockPostId, $mockMargs);
    }
    if ($po->post_type == 'attachment') {
      $fileUrl = wp_get_attachment_url($po->ID);
      $audioFilePath = get_attached_file($po->ID);
    }

    if ($po->post_type == DZSAP_REGISTER_POST_TYPE_NAME) {
      $fileUrl = $dzsap->get_track_source($po->ID, $mockPostId, $mockMargs);
      $audioFilePath = '';
    }
    if (strpos($fileUrl, site_url()) !== false) {
      $audioFilePath = str_replace(site_url() . '/', ABSPATH, $fileUrl);
    }

    if ($fileUrl == '') {
      if (isset($_GET['source'])) {
        $fileUrl = $_GET['source'];
      }

      if ($fileUrl == '') {
        if (function_exists('get_field')) {
          $arr = get_field('scratch_preview', $po->ID);
          if ($arr) {
            $fileUrl = wp_get_attachment_url($arr);
          }
        }
      }
    }

    // -- force it
    if (isset($_GET['songname']) && $_GET['songname']) {
      $filenameFromTitle = $_GET['songname'];
    }


    // -- still in download

    $fileName_in_header = $fileUrl;


    $fileUrl_exploder = explode('/', $fileName_in_header);
    $fileName_in_header = $fileUrl_exploder[count($fileUrl_exploder) - 1];

    if ($filenameFromTitle) {
      $fileName_in_header = $filenameFromTitle;
    }


    if ($audioFilePath && file_exists($audioFilePath)) {
      $filePathForDownload = $audioFilePath;
    } else {
      $filePathForDownload = $fileUrl;
    }


    dzsap_mysql_insert_activity(array(
      'id_video' => $po->ID,
      'type' => 'download',
    ));


    // --end id


  } else {
    // -- where does link come from ?
    if (isset($_GET['link']) && $_GET['link']) {

      $fileUriParts = explode('/', $_GET['link']);
      $fileUrl = $_GET['link'];
      $filenameFromTitle = $fileUriParts[count($fileUriParts) - 1];

      $filenameFromTitle = html_entity_decode($filenameFromTitle);


      $fileName_in_header = ($filenameFromTitle);


      $filePathForDownload = $fileUrl;
      if (strpos($fileUrl, site_url()) !== false) {
        $filePathForDownload = str_replace(site_url() . '/', ABSPATH, $fileUrl);
        $audioFilePath = $filePathForDownload;
      }

    }

  }

  $headerContentLength = '';
  $extension = 'mp3';
  $content_type = 'application/octet-stream';

  // -- dzs ap download
  if (strpos($fileUrl, '.m4a') !== false) {
    $extension = 'm4a';
    $content_type = 'audio/mp4';

  }

  if (strpos($fileUrl, '.wav') !== false) {
    $extension = 'wav';
    $content_type = 'audio/wav';
  }
  // -- dzsap download from link

  if (strpos($fileUrl, '.m4a') !== false) {
    $extension = 'm4a';
    $content_type = 'audio/mp4';
  }

  if (!($audioFilePath && file_exists($audioFilePath))) {
    if (strpos($audioFilePath, DIRECTORY_SEPARATOR) === 0) {
      $audioFilePath = ABSPATH . $audioFilePath;
    }
  }
  if (strpos($fileName_in_header, '.') === false) {
    $fileName_in_header .= '.' . strtolower($extension);
  }


  if ($audioFilePath && file_exists($audioFilePath)) {
    setHeaders($content_type, $fileName_in_header);
    header('Content-Length: ' . filesize($audioFilePath));
    readfile($filePathForDownload);
  } else {

    if (ini_get('allow_url_fopen')) {

      $fileGetContentsStatus = @file_get_contents($filePathForDownload);

      if ($fileGetContentsStatus === false) {
        $fileGetContentsStatus = @file_get_contents($fileUrl);
      }


      if ($fileGetContentsStatus) {
        setHeaders($content_type, $fileName_in_header);
        echo $fileGetContentsStatus;
      } else {


        echo 'failed $fileUrl - ' . print_r($fileUrl, true);
        echo 'failed - ' . print_r(error_get_last(), true);
      }
    }else{

      setHeaders($content_type, $fileName_in_header);
      $ch = curl_init($fileUrl);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_HEADER, 0);
      curl_setopt($ch, CURLOPT_TIMEOUT, 10);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      $cache = curl_exec($ch);
      curl_close($ch);
      echo $cache;
    }
  }


}