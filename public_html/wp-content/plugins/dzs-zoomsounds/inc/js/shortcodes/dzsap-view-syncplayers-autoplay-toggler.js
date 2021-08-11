'use strict';


jQuery(document).ready(function ($) {

  function handle_mouse_syncPlayers_autoplayToggler() {

    let $t = $(this);

    if (window.dzsap_settings.syncPlayers_autoplayEnabled) {
      window.dzsap_settings.syncPlayers_autoplayEnabled = false;
      $t.removeClass('active-autoplay')
    } else {

      window.dzsap_settings.syncPlayers_autoplayEnabled = true;
      $t.addClass('active-autoplay')
    }
  }

  $(document).on('click.dzsap_syncPlayers_toggler', '.dzsap-syncPlayers-autoplay-toggler', handle_mouse_syncPlayers_autoplayToggler);
})