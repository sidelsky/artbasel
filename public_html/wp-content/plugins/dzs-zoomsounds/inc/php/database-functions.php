<?php

function dzsap_mysql_get_track_activity($track_id, $pargs = array()) {


  // -- get last ON for interval training

  $margs = array(
    'get_last' => 'off',
    'call_from' => 'default',
    'interval' => '24',
    'type' => 'view',
    'table' => 'detect',
    'day_start' => '3',
    'day_end' => '2',
    'get_count' => 'off',
  );

  if ($pargs) {
    $margs = array_merge($margs, $pargs);
  }


  global $wpdb;
  $table_name = $wpdb->prefix . 'dzsap_activity';


  $format_track_id = 'id_video';


  $margs['table'] = $table_name;

  $query = "SELECT ";


  if ($margs['get_count'] == 'on') {

    $query .= 'COUNT(*)';
  } else {

    $query .= '*';
  }

  $query .= " FROM `" . $margs['table'] . "` WHERE `" . $format_track_id . "` = '" . $track_id;


  if (strpos($margs['type'], '%') !== false) {

    $query .= "' AND type LIKE '" . $margs['type'] . "'";
  } else {

    $query .= "' AND type='" . $margs['type'] . "'";
  }


  if ($margs['get_last'] == 'on') {
    $query .= ' AND date > DATE_SUB(NOW(), INTERVAL ' . $margs['interval'] . ' HOUR)';
  }

  if ($margs['get_last'] == 'day') {
    $query .= ' AND date BETWEEN DATE_SUB(NOW(), INTERVAL ' . $margs['day_start'] . ' DAY)
    AND DATE_SUB(NOW(), INTERVAL  ' . $margs['day_end'] . ' DAY)';


  }

  // -- interval start / end


  if (isset($margs['id_user'])) {
    $query .= ' AND id_user=\'' . $margs['id_user'] . '\'';
  }


  $results = $GLOBALS['wpdb']->get_results($query, OBJECT);


  $finalval = 0;
  if (is_array($results) && count($results) > 0) {


    if ($margs['get_count'] == 'on') {


      if (isset($results[0])) {
        $results[0] = (array)$results[0];


        return $results[0]['COUNT(*)'];

      }
    } else {

      if ($margs['call_from'] == 'debug') {

      }
      foreach ($results as $lab => $aux2) {
        $results[$lab] = (array)$results[$lab];

        $finalval += $results[$lab]['val'];
      }
    }


  }


  return $finalval;


}


function dzsap_mysql_check_if_user_did_activity($id_user, $track_id, $type = 'view') {

  global $dzsap;
  if ($dzsap->mainoptions['wpdb_enable'] == 'on') {
    global $wpdb;


    $currip = dzsap_misc_get_ip();
    $date = date('Y-m-d H:i:s');
    $table_name = $wpdb->prefix . 'dzsap_activity';

    $user_id = 0;

    if (get_option('dzsap_table_activity_created')) {

      $table_name = $wpdb->prefix . 'dzsap_activity';
      $query = "SELECT * FROM $table_name WHERE `id_user` = '$id_user' AND `id_video`='$track_id' AND `type`='$type'";


      $mylink = $wpdb->get_row($query);


      if ($mylink && isset($mylink->id)) {
        return true;
      }
    }


    return false;
  }


}

function dzsap_mysql_delete_activity($pargs = array()) {
  global $wpdb;
  global $dzsap;

  if ($dzsap->mainoptions['wpdb_enable'] == 'on') {


    $margs = array(
      'type' => 'download',
      'id_user' => '',
      'id_video' => '',
    );

    if ($pargs == '' || is_array($pargs) == false) {
      $pargs = array();
    }

    $margs = array_merge($margs, $pargs);

    $currip = dzsap_misc_get_ip();
    $date = date('Y-m-d H:i:s');


    if (get_option('dzsap_table_activity_created')) {
      $table_name = $wpdb->prefix . 'dzsap_activity';

      $user_id = 0;
      $current_user = wp_get_current_user();

      if ($current_user) {
        if ($current_user->ID) {
          $user_id = $current_user->ID;
        }
      }


      $args = array(
        'ip' => $currip,
        'type' => $margs['type'],
        'id_user' => $user_id,
        'id_video' => $margs['id_video'],
        'date' => $date,
      );


      $prepareArgs = array($margs['type']);

      $sql = 'DELETE FROM $table_name
		 WHERE type = %s';

      if ($user_id) {
        $sql .= "AND id_user=%s";
        array_push($prepareArgs, $user_id);
      }
      if ($margs['id_video']) {
        $sql .= "AND id_video=%s";
        array_push($prepareArgs, $margs['id_video']);

      }

      $wpdb->prepare(
        $sql,
        $prepareArgs
      );


    } else {

      $dzsap->ajax_functions->create_activity_table();
    }
  }

}


function dzsap_mysql_insert_activity($pargs = array()) {


  global $dzsap;
  if ($dzsap->mainoptions['wpdb_enable'] == 'on') {
    global $wpdb;


    $margs = array(
      'type' => 'download',
      'id_user' => '',
      'id_video' => '',
    );

    if ($pargs == '' || is_array($pargs) == false) {
      $pargs = array();
    }

    $margs = array_merge($margs, $pargs);

    $currip = dzsap_misc_get_ip();
    $date = date('Y-m-d H:i:s');


    if (get_option('dzsap_table_activity_created')) {
      $table_name = $wpdb->prefix . 'dzsap_activity';

      $user_id = 0;
      $current_user = wp_get_current_user();

      if ($current_user) {
        if ($current_user->ID) {
          $user_id = $current_user->ID;
        }
      }


      $args = array(
        'ip' => $currip,
        'type' => $margs['type'],
        'id_user' => $user_id,
        'id_video' => $margs['id_video'],
        'date' => $date,
      );


      if ($margs['type'] == 'like' || $margs['type'] == 'download') {
        $args['val'] = 1;
      }


      $wpdb->insert($table_name, $args);
    } else {

      $dzsap->ajax_functions->create_activity_table();
    }
  }

}


function dzsap_check_if_user_played_track($track_id) {

  global $current_user;


  if ($current_user && isset($current_user->data) && $current_user->data && isset($current_user->data->ID) && $current_user->data->ID) {
    //--- if user logged in


    return dzsap_mysql_check_if_user_did_activity($current_user->data->ID, $track_id, 'view');
  } else {
    if (isset($_COOKIE['viewsubmitted-' . $track_id])) {
      return true;
    }
    return false;
  }
}


function dzsap_check_if_user_liked_track($track_id, $id_user = 0) {

  global $current_user;


  if ($id_user == 0 && $current_user && isset($current_user->data) && $current_user->data && isset($current_user->data->ID) && $current_user->data->ID) {
    $id_user = $current_user->data->ID;
  }

  if ($id_user) {


    return dzsap_mysql_check_if_user_did_activity($id_user, $track_id, 'like');
  } else {

    if (isset($_COOKIE['likesubmitted-' . $track_id])) {
      return true;
    }
    if (isset($_COOKIE['dzsap_likesubmitted-' . $track_id])) {
      return true;
    }
    return false;
  }
}