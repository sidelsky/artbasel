'use strict';
window.waves_fieldtaget = null;
window.waves_filename = null;

window.inter_dzs_check_dependency_settings = 0;
















require("./js_common/_dependency-functionality");
const waveRegenerate = require("./jsinc/_wave_regenerate");
require("./js_common/_query_arg_func");
var dzsapFeedbacker = require('./jsinc/_feedbacker');
var mainoptions = require('./jsinc/_mainoptions');

var dzsap_vpconfigs = require("./jsinc/_vpconfigs");
var dzsap_systemCheck_waves_check  = require("./jsinc/_systemCheck_waves_check");

import * as dzsap_admin_helpers from "./js_common/_helper_admin";
import {nag_intro_tooltip} from './js_common/_nag_intro_tooltip';

jQuery(document).ready(function ($) {


  // Create the media frame.
  const main_settings = window.dzsap_settings;
  $(document).on('change','select.vpconfig-select',  change_vpconfig);
  $('.save-mainoptions').on('click', dzs_admin_mainOptions_saveAll);

  dzsap_admin_helpers.adminPageWaveformChecker_init()

  $(document).on('click', ' .btn-dzsap-create-playlist-for-woo', handle_mouse);

  dzsap_vpconfigs.vpconfigs_init();

  mainoptions.mainoptions_init();
  var _wrap = $('.wrap').eq(0);


  dzsapFeedbacker.feedbacker_init();


  dzsap_admin_helpers.addGutenbergButtons();
  dzsap_admin_helpers.addUploaderButtons();

  waveRegenerate.wave_regenerate_init();

  dzsap_systemCheck_waves_check.systemCheck_waves_check();

  $('.dzs-auto-click-after-1000').each(function () {
    var _t = $(this);
    setTimeout(function () {
      _t.trigger('click');
    }, 1000);
  });




  nag_intro_tooltip({...main_settings, prefix: 'dzsap'});



  setTimeout(dzsap_admin_helpers.reskin_select, 10);
  setTimeout(function () {

    $('select.vpconfig-select').trigger('change');
  }, 1000);


  window.dzs_dependency_on_document_ready();

  if(get_query_arg(window.location.href,'dzsap_preview_player')){
    setTimeout(()=>{

      window.html2canvas(document.querySelector(".wrap-for-player-preview")).then(canvas => {
        document.body.prepend(canvas)
      });
    },3000);
  }

 
  $(document).on('change.dzsap_get_thumb', '*[name="dzsap_meta_source_attachment_id"]', function () {


    var _t = $(this);




    var _con = null;

    if (_t.parent().parent().parent().parent().parent().hasClass('dzstooltip--content')) {
      _con = _t.parent().parent().parent().parent().parent();
    }




    if (_con) {
      var _c = _con.find('*[name="dzsap_meta_item_thumb"]');
      if (_c) {


        if (_c.val() == '') {

          var data = {
            action: 'dzsap_get_thumb_from_meta'
            , postdata: _t.val()
          };


          var _mainThumb = _c;


          jQuery.ajax({
            type: "POST",
            url: window.ajaxurl,
            data: data,
            success: function (response) {


              if (response.indexOf('image data - ') == 0) {


                response = response.replace('image data - ', '');



                if (response) {


                  if (_mainThumb.val() == '' && _mainThumb.val() != 'none') {
                    _mainThumb.val('data:image/jpeg;base64,' + response);
                    _mainThumb.trigger('change');
                  }
                }

              } else {


                if (_mainThumb.val() == '' && _mainThumb.val() != 'none') {
                  _mainThumb.val(response);
                  _mainThumb.trigger('change');
                }
              }

            },
            error: function (arg) {
                console.log('got error: ' + arg);
              ;
            }
          });
        }
      }
    }

  });
  $(document).on('change.dzsap_global', '.edit_form_line input[name=source], .wrap input[name=source],input[name=playerid]', function () {
    var _t = $(this);


    var sw_show_notice = true;


    if (isNaN(Number(_t.val())) && $('input[name=playerid]').eq(0).val() == '') {

    } else {


      sw_show_notice = false;


    }

    var _c = $('*[name="dzsap_meta_source_attachment_id"]').eq(0);
    if (isNaN(Number(_c.val())) && _c.val() == '') {

    } else {


      sw_show_notice = false;


    }


    _c.trigger('change');


    if (sw_show_notice) {

      $('div[data-label="playerid"],*[data-vc-shortcode-param-name="playerid"]').show();
      $('.notice-for-playerid').show();
    } else {

      $('.notice-for-playerid').hide();
    }


  })

  $('input[name=source]').trigger('change');
  setTimeout(function () {

    $('input[name=source]').trigger('change');
  }, 1000);


  $(".with_colorpicker").each(function () {
    var _t = $(this);
    if (_t.hasClass("treated")) {
      return;
    }
    if ($.fn.farbtastic) {

      _t.next().find(".picker").farbtastic(_t);

    }
    ;
    _t.addClass("treated");

    _t.bind("change", function () {

      jQuery("#customstyle_body").html("body{ background-color:" + $("input[name=color_bg]").val() + "} .dzsportfolio, .dzsportfolio a{ color:" + $("input[name=color_main]").val() + "} .dzsportfolio .portitem:hover .the-title, .dzsportfolio .selector-con .categories .a-category.active { color:" + $("input[name=color_high]").val() + " }");
    });
    _t.trigger("change");
    _t.bind("click", function () {
      if (_t.next().hasClass("picker-con")) {
        _t.next().find(".the-icon").eq(0).trigger("click");
      }
    })
  });


  function handle_mouse(e) {

    var _t = ($(this));

    if (e.type === 'click') {




      if (_t.hasClass('btn-dzsap-create-playlist-for-woo')) {



        var term_name = 'zoomsounds-product-playlist-' + _t.attr('data-playerid');
        var data = {
          action: 'dzsap_create_playlist'
          , term_name: term_name
        };

        _t.attr('disabled', true);
        _t.prop('disabled', true);

        _t.addClass('playlist-opened');


        $.ajax({
          type: "POST",
          url: window.ajaxurl,
          data: data,
          success: function (response) {

            if (response) {

              $('input[name="dzsap_woo_product_track"]').val(term_name);


              _t.parent().parent().parent().after('<iframe class="dzsap-woo-playlist-iframe" src="' + window.dzsap_settings.admin_url + ('term.php?taxonomy=dzsap_sliders&tag_ID=' + response + '&post_type=dzsap_items&dzs_css=remove_wp_menu') + '" width="100%" height="400"></iframe>')
            }
          },
          error: function (arg) {
              console.log('got error: ' + arg, arg);
            ;
          }
        });


        return false;


      }

    }
  }


  function change_vpconfig() {
    var _t = $(this);

    var _con = null;


    if (_t.parent().hasClass('vpconfig-wrapper')) {

      _con = _t.parent();
    }
    if (_t.parent().parent().hasClass('vpconfig-wrapper')) {

      _con = _t.parent().parent();
    }





    if (_con) {

      var selopt = _t.children(':selected');

    }

  }





  if (_wrap.hasClass('wrap-for-generator-player')) {
  }
});

dzsap_admin_helpers.reskin_select();



function dzs_admin_mainOptions_saveAll() {
  jQuery('#save-ajax-loading').css('visibility', 'visible');
  var mainarray = jQuery('.mainsettings').serialize();
  var data = {
    action: 'dzsap_ajax_mo',
    postdata: mainarray
  };

  dzsapFeedbacker.feedbacker_show_message('Options saved.');

  jQuery.post(ajaxurl, data, function (response) {
    jQuery('#save-ajax-loading').css('visibility', 'hidden');
  });

  return false;
}


