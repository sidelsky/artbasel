export const dzsap_oneTimeSetups = ()=>{


  window.dzsap_generate_list_for_sync_players = function (pargs) {
    var $ = jQuery;

    var margs = {
      'force_regenerate': false

    };

    if (pargs) {
      margs = $.extend(margs, pargs);
    }
    window.dzsap_syncList_players = [];


    if ((window.dzsap_settings.syncPlayers_buildList === 'on') || margs.force_regenerate) {

      jQuery('.audioplayer,.audioplayer-tobe').each(function () {
        var _t2 = jQuery(this);
        if (_t2.attr('data-do-not-include-in-list') !== 'on') {
          if (_t2.attr('data-type') !== 'fake' || _t2.attr('data-fakeplayer')) {
            window.dzsap_syncList_players.push(_t2);
          }
        }
      })


    }
  }


  jQuery(document).off('click.dzsap_global');
  jQuery(document).on('click.dzsap_global', '.dzsap-btn-info', function () {

    var _t = jQuery(this);
    if (_t.hasClass('dzsap-btn-info')) {

      _t.find('.dzstooltip').toggleClass('active');
      return;
    }

  })
  jQuery(document).on('mouseover.dzsap_global', '.dzsap-btn-info', function () {

    var _t = jQuery(this);
    if (_t.hasClass('dzsap-btn-info')) {

      if (window.innerWidth < 500) {

        if (_t.offset().left < (window.innerWidth / 2)) {
          _t.find('.dzstooltip').removeClass('talign-end').addClass('talign-start');
        }
      } else {
        _t.find('.dzstooltip').addClass('talign-end').removeClass('talign-start');
      }
    }

  });
}
