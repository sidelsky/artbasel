<?php

/**
 * @param $dzsap
 * @param $playerAttributes
 * @param $playerid
 * @return string
 */
function dzsap_parse_items__button_download_generate($dzsap, $playerAttributes, $playerid) {

  $download_link = '';


  $stringExtraHtml_leftContent = '';
  $download_link = dzsap_get_download_link($playerAttributes, $playerid);

  $download_str = ' <a rel="nofollow" target="_blank" href="' . $download_link . '" download class="btn-zoomsounds btn-zoomsounds-download"';


  if ($dzsap->mainoptions['register_to_download_opens_in_new_link'] == 'on') {
    $download_str .= ' target="_blank"';
  }

  $download_str .= '><span class="the-icon"><i class="fa fa-get-pocket"></i></span><span class="the-label">' . $dzsap->mainoptions['i18n_free_download'] . '</span></a>';


  $isAllowedDownload = true;


  if ($dzsap->mainoptions['allow_download_only_for_registered_users'] == 'on') {


    global $current_user;




    if ($current_user->ID) {

      if ($dzsap->mainoptions['allow_download_only_for_registered_users_capability'] && $dzsap->mainoptions['allow_download_only_for_registered_users_capability'] != 'read') {
        if (current_user_can($dzsap->mainoptions['allow_download_only_for_registered_users_capability']) == false) {
          $isAllowedDownload = false;
        }
      }

    } else {

      $isAllowedDownload = false;
    }


  }
  if (current_user_can('manage_options')) {
    $isAllowedDownload = true;

  }

  if ($isAllowedDownload == false) {

    $download_str = '<span href="' . $download_link . '" class="btn-zoomsounds btn-zoomsounds-download  dzstooltip-con "><span class="tooltip-indicator"><span class="the-icon"><i class="fa fa-get-pocket"></i></span><span class="the-label" style="opacity:0.5">' . $dzsap->mainoptions['i18n_free_download'] . '</span></span> <span class="dzstooltip arrow-from-start transition-slidein arrow-bottom talign-start style-rounded color-dark-light align-right" style="width: auto; white-space: nowrap;"><span class="dzstooltip--inner">' . $dzsap->mainoptions['i18n_register_to_download'] . '</span></span> </span>';
  }



  $stringExtraHtml_leftContent .= $download_str;
  return $stringExtraHtml_leftContent;
}