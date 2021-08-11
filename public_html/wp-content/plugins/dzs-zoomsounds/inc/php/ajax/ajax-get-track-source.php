<?php

function dzsap_ajax_getTrackSource_generateNonce() {


  if (session_status() == PHP_SESSION_NONE) {
    session_start();
  }

  $id = $_GET['id'];


  $lab = 'dzsap_nonce_for_' . $id . '_ip_' . $_SERVER['REMOTE_ADDR'];
  $lab = DZSZoomSoundsHelper::sanitize_toKey($lab);


  $nonce = rand(0, 10000);


  $_SESSION[$lab] = $nonce;
  if (isset($_GET['play_in_footer_player']) && $_GET['play_in_footer_player'] === 'on') {

  }

  $src = site_url() . '/index.php?dzsap_action=' . DZSAP_VIEW_GET_TRACK_SOURCE . '&id=' . $id . '&' . $lab . '=' . $nonce;

  echo $src;
  die();
}


function dzsap_ajax_getTrackSource() {
  $id = $_GET['id'];
  $audioPost = (get_post($id));
  $src_url = '';

  if (!$audioPost) {
    die();
  }
  if (session_status() == PHP_SESSION_NONE) {
    session_start();
  }

  $src_url = get_post_meta($audioPost->ID, DZSAP_META_WOOCOMMERCE_ZOOMSOUNDS_MP3, true);


  $playerid = '';
  $args = array();
  if ($src_url == '') {

    $src_url = DZSZoomSoundsHelper::media_getTrackSource($audioPost->ID, $playerid, $args);
  }

  $isAllowedPlay = false;

  if ($id && $src_url) {


    $fout = '';


    $lab = 'dzsap_nonce_for_' . $id . '_ip_' . $_SERVER['REMOTE_ADDR'];
    $lab = DZSZoomSoundsHelper::sanitize_toKey($lab);


    if (isset($_SESSION[$lab]) && $_SESSION[$lab] == $_GET[$lab]) {


      $extension = "mp3";
      $mime_type = "audio/mpeg, audio/x-mpeg, audio/x-mpeg-3, audio/mpeg3, audio/mp3";


      $_SESSION[$lab] = 'invalidate_nonce';


      // -- invalidate session

      $isAllowedPlay = true;


    } else {


    }


    if (isset($_SERVER['HTTP_RANGE']) || isset($_SERVER['HTTP_REFERER'])) {
      $isAllowedPlay = true;
    }


    if ($isAllowedPlay) {


      $isExternalUrl = false;

      if (strpos($src_url, site_url()) === false) {
        $isExternalUrl = true;
      }


      if ($isExternalUrl) {

        $filePath = str_replace(site_url(), (dirname(dirname(dirname(DZSAP_BASE_PATH)))), $src_url);
        $fileSize = filesize($filePath);


        $fileName = $filePath;

        $aux = explode(DIRECTORY_SEPARATOR, $filePath);
        if ($aux) {

          $fileName = $aux[count($aux) - 1];
        }

        header('Content-Type: ' . $mime_type);
        header('Content-Length: ' . $fileSize);
        header('Content-Range: bytes ' . '0' . '-' . $fileSize); // This tells the player what byte we're starting with.
        header('Content-Disposition:  filename="' . $fileName);
        header('X-Pad: avoid browser bug');
        header('Cache-Control: no-cache');


        readfile($filePath, true);
        die();
      } else {


        $file = '';
        if (strpos($src_url, site_url()) !== false) {
          $file = str_replace(site_url(), (dirname(dirname(dirname(DZSAP_BASE_PATH)))), $src_url);
        } else {


          if (ini_get('allow_url_fopen')) {
            echo file_get_contents($src_url);
          } else {


            $ch = curl_init($src_url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $cache = curl_exec($ch);
            curl_close($ch);

            echo $cache;
          }
          die();
        }

        $content_type = 'application/octet-stream';


        @error_reporting(0);


        if (!file_exists($file)) {
          header("HTTP/1.1 404 Not Found");
          exit;
        }

        // -- Get file size
        $filesize = sprintf("%u", filesize($file));

        // -- Handle 'Range' header
        if (isset($_SERVER['HTTP_RANGE'])) {
          $range = $_SERVER['HTTP_RANGE'];
        } elseif ($apache = apache_request_headers()) {
          $headers = array();
          foreach ($apache as $header => $val) {
            $headers[strtolower($header)] = $val;
          }
          if (isset($headers['range'])) {
            $range = $headers['range'];
          } else $range = FALSE;
        } else $range = FALSE;

        //Is range
        if ($range) {
          $partial = true;
          list($param, $range) = explode('=', $range);
          // Bad request - range unit is not 'bytes'
          if (strtolower(trim($param)) != 'bytes') {
            header("HTTP/1.1 400 Invalid Request");
            exit;
          }
          // Get range values
          $range = explode(',', $range);
          $range = explode('-', $range[0]);
          // Deal with range values
          if ($range[0] === '') {
            $end = $filesize - 1;
            $start = $end - intval($range[0]);
          } else if ($range[1] === '') {
            $start = intval($range[0]);
            $end = $filesize - 1;
          } else {
            // Both numbers present, return specific range
            $start = intval($range[0]);
            $end = intval($range[1]);
            if ($end >= $filesize || (!$start && (!$end || $end == ($filesize - 1)))) $partial = false; // Invalid range/whole file specified, return whole file
          }
          $length = $end - $start + 1;
        } // No range requested
        else $partial = false;

        // Send standard headers
        header("Content-Type: $content_type");
        header("Content-Length: $filesize");
        header('Accept-Ranges: bytes');

        // send extra headers for range handling...
        if ($partial) {
          header('HTTP/1.1 206 Partial Content');
          header("Content-Range: bytes $start-$end/$filesize");
          if (!$fp = fopen($file, 'rb')) {
            header("HTTP/1.1 500 Internal Server Error");
            exit;
          }
          if ($start) fseek($fp, $start);
          while ($length) {
            set_time_limit(0);
            $read = ($length > 8192) ? 8192 : $length;
            $length -= $read;
            print(fread($fp, $read));
          }
          fclose($fp);
        } //just send the whole file
        else readfile($file);
        exit;

      }


    }
  }


  die();

}