'use strict';
jQuery(document).ready(function($){

  $.ajax({

    type: "POST",
    url: window.ajaxurl,
    data: {
      'action':'dzsap_admin_report_connect_zoomsounds'
    },
    success: function (response) {

      if(response){
        $('.replace-with-server-communication-feedback').get(0).outerHTML = ('<div class="setting-text-ok"><i class="fa fa-thumbs-up"></i> communication ok</div>');
      }
    },
    error: function (error) {

      $('.replace-with-server-communication-feedback').get(0).outerHTML = ('<div class="setting-text-ok"><i class="fa fa-thumbs-down"></i> communication issue</div>');
    },
  })



  $(document).on('mouseover', '.vplayer', function () {
    if ($(this).get(0) && $(this).get(0).api_playMovie) {

      $(this).get(0).api_playMovie();
    }
  })
  $(document).on('click', '.tab-menu', function () {


    dzsvp_init('.vplayer-tobe.tobe-inited', {init_each: true});
    dzsvg_init('.videogallery.auto-init', {init_each: true});
  })
  $(document).on('click', '.btn-disable-activation', function () {


    var _t = $(this);


    open_ultibox(null, {

      type: 'inlinecontent'
      , source: '#loading-activation'

    });


    $.get("https://zoomthe.me/updater_dzsap/check_activation.php?purchase_code=" + $('*[name=dzsap_purchase_code]').val() + '&site_url=' + encodeURIComponent(dzsap_settings.site_url) + '&action=dzsap_purchase_code_disable', function (data) {





      $('.dzs-center-flex').eq(0).html(data);

      if (data.indexOf('success') > -1) {



        setTimeout(function () {

          var data2 = {
            action: 'dzsap_deactivate'
            , postdata: $('*[name=dzsap_purchase_code]').eq(0).val()
          };

          $.post(ajaxurl, data2, function (response) {

            if (window.console != undefined) {

            }

            setTimeout(function () {

              location.reload();
            }, 1000)




          });
        }, 10)

      }


    });


    return false;
  })


  $(document).on('submit', '.activate-form', function () {
    var _t = $(this);


    open_ultibox(null, {

      type: 'inlinecontent'
      , source: '#loading-activation'

    });






    $.get("https://zoomthe.me/updater_dzsap/check_activation.php?purchase_code=" + $('*[name=dzsap_purchase_code]').val() + '&site_url=' + encodeURIComponent(dzsap_settings.site_url), function (data) {





      $('.dzs-center-flex').html(data);

      if (data.indexOf('success') > -1) {


        setTimeout(function () {

          var data2 = {
            action: 'dzsap_activate'
            , postdata: $('*[name=dzsap_purchase_code]').eq(0).val()
          };

          $.post(ajaxurl, data2, function (response) {

            if (window.console != undefined) {

            }

            location.reload();



          });
        }, 10)

      }


    });


    return false;
  })

  setTimeout(function () {
    jQuery.get("https://zoomthe.me/cronjobs/cache/dzsap_get_version.static.html", function (data) {


      var newvrs = Number(data);

      $('.latest-version').animate({
        'opacity': '0'
      }, 300);

      setTimeout(function () {

        $('.latest-version').html(newvrs);
        $('.latest-version').animate({
          'opacity': '1'
        }, 300);
      }, 300);


    });


  }, 1000);
  setTimeout(function () {


    $.get("https://zoomthe.me/updater_dzsap/getdemo.php?demo=1", function (data) {




    })
  }, 2000);
})