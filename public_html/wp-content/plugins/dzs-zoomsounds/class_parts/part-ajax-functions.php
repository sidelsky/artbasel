<?php



if ($_GET['dzsap_action'] == 'delete_waveforms') {

  if (DZSZoomSoundsHelper::isTheTrackHasFromCurrentUser($_GET['trackid'])) {

    // -- todo: action
    error_log('delete waveforms');


    dzsap_delete_waveform($_GET['trackid']);
    dzsap_delete_waveform($_GET['sanitized_source']);
  }
}


if ($_GET['dzsap_action'] == 'delete_track') {

  if (DZSZoomSoundsHelper::isTheTrackHasFromCurrentUser($_GET['track_id'])) {
    wp_delete_post($_GET['track_id']);
  }
}



