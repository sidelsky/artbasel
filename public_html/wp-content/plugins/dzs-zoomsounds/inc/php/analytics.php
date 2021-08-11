<?php


function dzsap_analytics_get() {
  global $dzsap;
  $dzsap->analytics_views = get_option('dzsap_analytics_views');
  $dzsap->analytics_minutes = get_option('dzsap_analytics_minutes');


  if ($dzsap->mainoptions['analytics_enable_user_track'] == 'on') {
    $dzsap->analytics_users = get_option('dzsap_analytics_users');


    if ($dzsap->analytics_users == false) {
      $dzsap->analytics_users = array();
    }
  }


}


function dzsap_analytics_submit_into_table($pargs) {

  global $dzsap;

  $margs = array(
    'call_from' => 'default',
    'called_from_ajax' => false,
    'type' => 'view',
  );


  $margs = array_merge($margs, $pargs);


  $date = date('Y-m-d H:i:s');



  $id = '';

  if (isset($_POST['video_analytics_id']) && $_POST['video_analytics_id']) {

    $id = $_POST['video_analytics_id'];
  }
  if (isset($_POST['playerid']) && $_POST['playerid']) {

    $id = $_POST['playerid'];
  }

  $id = str_replace('ap', '', $id);

  $country = '0';

  if ($dzsap->mainoptions['analytics_enable_location'] == 'on') {

    if ($_SERVER['REMOTE_ADDR']) {
      $request = wp_remote_get('https://ipinfo.io/' . $_SERVER['REMOTE_ADDR'] . '/json');
      $response = wp_remote_retrieve_body($request);
      $aux_arr = json_decode($response);

      if ($aux_arr) {
        if(isset($aux_arr->country)){

          $country = $aux_arr->country;
        }
      }
    }
  }


  $userid = '';
  $userid = get_current_user_id();
  if ($dzsap->mainoptions['analytics_enable_user_track'] == 'on') {

    if (isset($_POST['dzsap_curr_user']) && $_POST['dzsap_curr_user']) {
      $userid = $_POST['dzsap_curr_user'];
    }
  }


  $playerid = $id;



  if (isset($_COOKIE["dzsap_" . $margs['type'] . "submitted-" . $playerid]) && $_COOKIE["dzsap_" . $margs['type'] . "submitted-" . $playerid] == '1') {

  } else {


    if ($margs['type'] == 'view') {

      $integerLikes = get_post_meta($id, DZSAP_DB_VIEWS_META_NAME, true);

      $integerLikes = intval($integerLikes);


      update_post_meta($id, DZSAP_DB_VIEWS_META_NAME, ++$integerLikes);
    }


    if ($margs['type'] == 'like') {

      $integerLikes = get_post_meta($id, DZSAP_DB_LIKES_META_NAME, true);
      $integerLikes = intval($integerLikes);


      update_post_meta($id, DZSAP_DB_LIKES_META_NAME, ++$integerLikes);
    }





    $currip = dzsap_misc_get_ip();


    if ($margs['type'] == 'view') {
    }

    setcookie("dzsap_" . $margs['type'] . "submitted-" . $playerid, 1, time() + 36000, COOKIEPATH);


    global $wpdb;


    $table_name = $wpdb->prefix . 'dzsap_activity';


    if ($dzsap->mainoptions['analytics_enable_user_track'] == 'on') {

      // -- date precise
      $date = date('Y-m-d H:i:s');
      $wpdb->insert(
        $table_name,
        array(
          'ip' => $currip,
          'country' => $country,
          'type' => $margs['type'],
          'val' => 1,
          'id_user' => $userid,
          'id_video' => $playerid,
          'date' => $date,
        )
      );
    } else {


      // -- date more generic for select matches
      $date = date('Y-m-d');


      // -- submit to total plays for today

      $query = 'SELECT * FROM ' . $table_name . ' WHERE id_user = \'0\' AND date=\'' . $date . '\'  AND type=\'' . $margs['type'] . '\' AND id_video=\'' . ($playerid) . '\'';
      if ($dzsap->mainoptions['analytics_enable_location'] == 'on' && $country) {
        $query .= ' AND country=\'' . $country . '\'';
      }
      $results = $wpdb->get_results($query, OBJECT);


      if (is_array($results) && count($results) > 0) {


        $val = intval($results[0]->val);
        $newval = $val + 1;

        $wpdb->update(
          $table_name,
          array(
            'val' => $val + 1,
          ),
          array('ID' => $results[0]->id),
          array(
            '%s',    // value1
          ),
          array('%d')
        );


      } else {

        $wpdb->insert(
          $table_name,
          array(
            'ip' => 0,
            'type' => $margs['type'],
            'id_user' => 0,
            'id_video' => $playerid,
            'date' => $date,
            'val' => 1,
            'country' => $country,
          )
        );
      }

    }





    $query = 'SELECT * FROM ' . $table_name . ' WHERE id_user = \'0\' AND date=\'' . $date . '\'  AND type=\'' . 'view' . '\' AND id_video=\'' . (0) . '\'';
    if ($dzsap->mainoptions['analytics_enable_location'] == 'on' && $country) {
      $query .= ' AND country=\'' . $country . '\'';
    }
    $results = $wpdb->get_results($query, OBJECT);


    if (is_array($results) && count($results) > 0) {


      $val = intval($results[0]->val);
      $newval = $val + 1;

      $wpdb->update(
        $table_name,
        array(
          'val' => $val + 1,
        ),
        array('ID' => $results[0]->id),
        array(
          '%s',    // value1
        ),
        array('%d')
      );


    } else {

      $wpdb->insert(
        $table_name,
        array(
          'ip' => 0,
          'type' => 'view',
          'id_user' => 0,
          'id_video' => 0,
          'date' => $date,
          'val' => 1,
          'country' => $country,
        )
      );
    }


    if($margs['called_from_ajax']){
      die();

    }
  }


}


function dzsap_analytics_table_create() {

  global $wpdb, $dzsap;

  $table_name = $wpdb->prefix . 'dzsap_activity';
  if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
    //table not in database. Create new table
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
          id mediumint(9) NOT NULL AUTO_INCREMENT,
          type varchar(100) NOT NULL,
          country varchar(100) NULL,
          id_user int(10) NOT NULL,
          val int(255) NOT NULL,
          ip varchar(255) NOT NULL,
          id_video int(10) NOT NULL,
          date datetime NOT NULL,
          UNIQUE KEY id (id)
     ) $charset_collate;";
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);


    $dzsap->mainoptions['analytics_table_created'] = 'on';;
    update_option(DZSAP_DBNAME_OPTIONS, $dzsap->mainoptions);

  } else {
  }

}

