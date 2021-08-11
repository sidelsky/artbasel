'use strict';
var sliderIndex = 0;
var itemIndex = [0];
var currSlider_nr = -1;
var currSlider;
var targetInput;
var global_items = 0;

window.DZSAP_LEGACY_SLIDERS_MODE = ''; // -- "sliders" or "vpconfigs"

jQuery(document).ready(function ($) {

  window.DZSAP_LEGACY_SLIDERS_MODE = $('body').hasClass('zoomsounds_page_dzsap_configs') ? 'vpconfigs' : 'sliders';

  dzsap_settings.currSlider = parseInt(dzsap_settings.currSlider, 10);


  setTimeout(function () {


    dzstaa_init('.dzs-tabs.auto-init-from-vpconfig', {
      'design_tabsposition': 'top'
      , design_transition: 'fade'
      , design_tabswidth: 'default'
      , toggle_breakpoint: '200'
      , toggle_type: 'accordion'
      , settings_enable_linking: 'on'
      , settings_appendWholeContent: true
      , refresh_tab_height: '1000'
    });


  }, 1000);

  setTimeout(function () {

    dzssel_init('select.dzs-style-me', {init_each: true});
  }, 1500);


  $('.saveconfirmer').fadeOut('slow');
  $('.add-slider').on('click', sliders_click_addslider);

  $(document).on("click", ".item-preview", item_open);

  $('.master-save').on('click', sliders_saveall);
  $('.slider-save').on('click', sliders_saveslider);
  $('.master-save-vpc').on('click', dzs_sliders_saveAllVpcs);
  $('.slider-save-vpc').on('click', sliders_saveslider_vpc);


  $(document).on('change', '.dzs-dependency-field, .main-media-file', handle_submit);

  $(document).on("change", ".main-id", sliders_change_mainid);
  $(document).on("change", ".main-thumb", sliders_change_mainthumb);
  $(document).on("click",".slider-edit", sliders_click_slideredit);
  $(document).on("click",".slider-duplicate", sliders_click_sliderduplicate);
  $(document).on("click",".slider-delete", sliders_click_sliderdelete);
  $(document).on("click",".slider-sliderexport",  sliders_click_sliderexport);
  $(document).on("click", ".slider-embed", sliders_click_sliderembed);

  $(document).on("click",".item-delete",  sliders_click_itemdelete);
  $(document).on("click",".item-duplicate",  sliders_click_itemduplicate);

  $(document).on("click", ".upload_file", sliders_wpupload);
  $(document).on("change", ".item-type", sliders_itemchangetype);

  $('.item-type').trigger('change');
  $('.main-thumb').trigger('change');


  $(document).on('change', '*[name="0-settings-vpconfig"]', handle_change);
  $(document).on('click', '.quick-edit-vp,button[name=dzsap_save_pcm]', handle_mouse);



  function sliders_click_sliderembed() {
    var _t = jQuery(this);
    var par = _t.parent().parent().parent();
    var ind = par.parent().children().index(par);
    var sname = par.children('td').eq(0).html()


    var htmlForEmbed = DZSAP_LEGACY_SLIDERS_MODE === 'vpconfigs' ? `use this shortcode for embedding: [zoomsounds_player config="${sname}" source="${'https://digitalzoomstudio.net/links/sample-mp3.php'}"]` : 'use this shortcode for embedding: [zoomsounds id="' + sname + '"]';

    jQuery('.saveconfirmer').html(htmlForEmbed);
    jQuery('.saveconfirmer').stop().fadeIn('fast').delay(4000).fadeOut('fast');

    return false;
  }

  function sliders_click_itemdelete() {
    var index = currSlider.find('.item-delete').index(jQuery(this));

    var arg = index;
    sliders_delete_item(arg);
    return false;
  }

  function sliders_click_itemduplicate() {
    var index = currSlider.find('.item-duplicate').index(jQuery(this));
    var _cache = currSlider.find('.items-con').eq(0);
    _cache.append(jQuery(this).parent().clone());
    for (let i = 0; i < _cache.children().last().find('.textinput').length; i++) {
      dzs_legacy_slidersRename(_cache.children().last().find('.textinput').eq(i), currSlider_nr, itemIndex[currSlider_nr]);
    }
    for (let i = 0; i < _cache.children().last().find('textarea').length; i++) {
      var _c = _cache.children().last().find('textarea').eq(i);
      _c.val(_cache.children().eq(index).find('textarea').eq(i).val());
    }
    itemIndex[currSlider_nr]++;

    return false;

  }
  function sliders_itemchangetype() {
    var _t = jQuery(this);
    var selval = _t.find(':selected').val();

    var target = _t.parent().parent().parent().find('.main-source');

    if (selval == 'inline') {
      target.css({
        'height': 80,
        'resize': 'vertical'
      });
    } else {
      target.css({
        'height': 23,
        'resize': 'none'
      });
    }

  }
  function sliders_deleteslider(arg) {
    jQuery('.main_sliders').children('tbody').children().eq(arg).remove();
    jQuery('.slider-con').eq(arg).remove();
    if (arg < sliderIndex - 1) {
      for (let i = arg; i < sliderIndex - 1; i++) {
        _cache = jQuery('.slider-con').eq(i);
        for (let j = 0; j < _cache.find('.textinput').length; j++) {
          var _c2 = _cache.find('.textinput').eq(j);
          dzs_legacy_slidersRename(_c2, i, 'same')
        }
      }
    }

    sliderIndex--;
    if (arg == currSlider_nr) {
      currSlider_nr = -1;
      dzs_legacy_slidersShowSlider(arg);
    }
  }

  function sliders_click_addslider() {

    if (dzsap_settings.is_safebinding == 'on') {

    } else {
      dzs_legacy_slidersAddSlider();
      return false;
    }
  }


  function sliders_saveslider() {
    jQuery('#save-ajax-loading').css('visibility', 'visible');
    var mainarray = currSlider.serializeAnything();


    var auxslidernr = currSlider_nr;

    if (dzsap_settings.is_safebinding == 'on') {
      auxslidernr = dzsap_settings.currSlider;
    }

    var data = {
      action: 'dzsap_ajax'
      , postdata: mainarray
      , sliderid: auxslidernr
      , currdb: dzsap_settings.currdb
    };
    jQuery.post(ajaxurl, data, function (response) {
      jQuery('#save-ajax-loading').css('visibility', 'hidden');
      if (response.indexOf('success') > -1) {
        jQuery('.saveconfirmer').html('Options saved.');
      } else {
        jQuery('.saveconfirmer').html('There seemed to be a problem ? Please check if options were actually saved.');
      }
      jQuery('.saveconfirmer').fadeIn('fast').delay(2000).fadeOut('fast');
    });
    return false;
  }


  function item_open() {
    var _t = jQuery(this);
    var _itemcon = _t.parent();
    if (dzsap_settings.admin_close_otheritems == 'on') {
      jQuery('.item-con').each(function () {
        var _t2 = jQuery(this);
        if (_t2[0] != _itemcon[0] && _t2.hasClass('active')) {
          _t2.removeClass('active');
        }
      });
    }

    if (_itemcon.hasClass('active')) {
      _itemcon.removeClass('active');
    } else {
      _itemcon.addClass('active');
    }
  }



  function sliders_saveall() {
    jQuery('#save-ajax-loading').css('visibility', 'visible');
    var mainarray = jQuery('.master-settings').serialize();
    var data = {
      action: 'dzsap_ajax'
      , postdata: mainarray
      , currdb: dzsap_settings.currdb
    };
    jQuery('.saveconfirmer').html('Options saved.');
    jQuery('.saveconfirmer').fadeIn('fast').delay(2000).fadeOut('fast');
    jQuery.post(window.ajaxurl, data, function (response) {
      jQuery('#save-ajax-loading').css('visibility', 'hidden');
    });

    return false;
  }


  function sliders_saveslider_vpc() {
    jQuery('#save-ajax-loading').css('visibility', 'visible');
    var mainarray = currSlider.serializeAnything();

    // -- json encode form -> currSlider.parent().serializeArray()

    var auxslidernr = currSlider_nr;

    if (dzsap_settings.is_safebinding == 'on') {
      auxslidernr = dzsap_settings.currSlider;
    }

    var data = {
      action: 'dzsap_save_configs'
      , postdata: mainarray
      , sliderid: auxslidernr
      , currdb: dzsap_settings.currdb
    };
    jQuery.post(ajaxurl, data, function (response) {
      jQuery('#save-ajax-loading').css('visibility', 'hidden');
      if (response.indexOf('success') > -1) {
        jQuery('.saveconfirmer').html('Options saved.');
      } else {
        jQuery('.saveconfirmer').html('There seemed to be a problem ? Please check if options were actually saved.');
      }
      jQuery('.saveconfirmer').fadeIn('fast').delay(2000).fadeOut('fast');
    });
    return false;
  }


  function sliders_change_mainid() {
    var _t = jQuery(this);
    var index = jQuery('.main-id').index(_t);
    if (dzsap_settings.is_safebinding != 'on') {
      jQuery('.main_sliders tbody').children().eq(index).children().eq(0).text(_t.val());
    }
  }

  function sliders_change_mainthumb() {
    var _t = jQuery(this);
    var _con = _t.parent().parent().parent();

    if (_con.hasClass('item-con') === false) {
      return;
    }

    _con.find('.item-preview').css('background-image', "url(" + _t.val() + ")");
  }

  function sliders_click_slideredit() {

    if (dzsap_settings.is_safebinding == 'on') {

    } else {
      var index = jQuery('.slider-edit').index(jQuery(this));
      dzs_legacy_slidersShowSlider(index);
      return false;
    }
  }

  function sliders_click_sliderduplicate() {
    var index = jQuery('.slider-duplicate').index(jQuery(this));

    // -- duplicate
    jQuery('.main_sliders').children('tbody').append('<tr class="slider-in-table"><td>' + jQuery('.slider-con').eq(index).find('.main-id').eq(0).val() + '</td><td class="button_view"><strong><a href="#" class="slider-action slider-edit">Edit</a></strong></td><td class="button_view"><strong><a href="#" class="slider-action slider-embed">Embed</a></strong></td><td class="button_view"><strong><a href="#" class="slider-action slider-sliderexport">Export</a></strong></td><td class="button_view"><strong><a href="#" class="slider-action slider-duplicate">Duplicate</a></strong></td><td class="button_view"><strong><a href="#" class="slider-action slider-delete">Delete</a></strong></td></tr>')
    jQuery('.master-settings').append(jQuery('.slider-con').eq(index).clone());
    for (let i = 0; i < jQuery('.slider-con').eq(sliderIndex).find('.textinput').length; i++) {
      var _cache = jQuery('.slider-con').eq(sliderIndex).find('.textinput').eq(i);
      dzs_legacy_slidersRename(_cache, sliderIndex, 'same')
    }


    for (let i = 0; i < jQuery('.slider-con').eq(index).find('textarea').length; i++) {
      var _c = jQuery('.slider-con').last().find('textarea').eq(i);
      _c.val(jQuery('.slider-con').eq(index).find('textarea').eq(i).val());
    }

    dzs_legacy_slidersAddListeners();
    itemIndex[sliderIndex] = 0;
    ++sliderIndex;


    return false;
  }


  function sliders_click_sliderexport() {
    var _t = jQuery(this);
    var par = _t.parent().parent().parent();
    var ind = par.parent().children().index(par);
    var sname = par.children('td').eq(0).html()


    var url = dzsap_settings.thepath + 'admin/sliderexport.php?KeepThis=true&width=400&height=200&slidernr=' + ind + '&slidername=' + sname + '&currdb=' + window.dzsap_settings.currdb + '&TB_iframe=true';


    if (String(window.location.href).indexOf('dzsap_configs') > -1) {

      url = dzsap_settings.thepath + 'admin/sliderexport_config.php?KeepThis=true&width=400&height=200&slidernr=' + ind + '&slidername=' + sname + '&currdb=' + window.dzsap_settings.currdb + '&TB_iframe=true';
    }


    tb_show('Slide Editor', url);
    return false;
  }



  function sliders_click_sliderdelete() {

    var r = confirm("are you sure you want to delete ?");
    if (r == true) {
    } else {
      return false;
    }

    if (dzsap_settings.is_safebinding == 'on') {

    } else {
      var index = jQuery('.slider-delete').index(jQuery(this));
      sliders_deleteslider(index);
      return false;
    }

  }

  function handle_mouse(e) {


    var _t = $(this);

    if (_t.attr('name') == 'dzsap_save_pcm') {


      var _c = $('*[name=dzsap_pcm_data]').eq(0);


      var data = {
        action: 'dzsap_submit_pcm',
        postdata: _c.val(),
        call_from: 'manual_wave_overwrite',
        playerid: _c.attr('data-id')
      };


      window.dzsap_generating_pcm = false;


      if (ajaxurl) {


        $.ajax({
          type: "POST",
          url: ajaxurl,
          data: data,
          success: function (response) {

          }
        });
      }


      return false;
    }
    if (_t.hasClass('quick-edit-vp')) {

      window.open_ultibox(null, {

        type: 'iframe'
        , source: _t.attr('href')
        , scaling: 'fill' // -- this is the under description
        , suggested_width: '400' // -- this is the under description
        , suggested_height: '95vh' // -- this is the under description
        , item: null // -- we can pass the items from here too

      });

      return false;
    }

  }

  function handle_change(e) {


    var _t = $(this);
    if (_t.hasClass('dzs-dependency-field')) {

      check_dependency_settings();
    }


    if (_t.attr('name') == '0-settings-vpconfig') {


      var ind = 0;

      _t.children().each(function () {
        var _t2 = $(this);


        if (_t2.prop('selected')) {
          ind = _t2.parent().children().index(_t2) - 1;
          return false;
        }
      });

      $('#quick-edit').attr('href', add_query_arg($('#quick-edit').attr('href'), 'currslider', ind));


    }

  }


  $('.import-export-db-con .the-toggle').click(function () {
    var _t = $(this);
    var _cont = _t.parent().children('.the-content-mask');

    var cont_h = _cont.children('.the-content').height() + 50 + 19;
    if (_cont.css('height') == '0px')
      _cont.stop().animate({
        'height': cont_h
      }, 200);
    else
      _cont.stop().animate({
        'height': 0
      }, 200);


  });
  dzsap_setupDbSelect();
  setTimeout(dzs_legacy_slidersAddListeners, 1000);
  $('.import-export-db-con .the-content-mask').css({
    'height': 0
  })


  function handle_submit(e) {
    var _t = $(this);


    if (e.type == 'change') {
      if (_t.hasClass('dzs-dependency-field')) {

      }


      if (_t.hasClass('main-media-file')) {


        var _mainThumb = _t.parent().parent().find('.main-thumb');


        setTimeout(function () {
          if (_mainThumb.val() == '' && _mainThumb.val() != 'none') {


            var data = {
              action: 'dzsap_get_thumb_from_meta'
              , postdata: _t.val()
            };
            $.post(ajaxurl, data, function (response) {

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

            });
          }

        }, 1000);
      }
    }
  }


  function check_dependency_settings() {
    $('*[data-dependency]').each(function () {
      var _t = $(this);


      var aux = _t.attr('data-dependency');

      if (aux.indexOf('"') == 0) {
        aux = aux.replace(/"/g, '');
      }
      aux = aux.replace(/{quotquot}/g, '"');

      var dep_arr = JSON.parse(aux);


      if (dep_arr[0]) {
        var _c = $('*[name="' + dep_arr[0].element + '"]').eq(0);


        var sw_show = false;

        for (var i3 in dep_arr[0].value) {
          if (_c.val() == dep_arr[0].value[i3]) {
            sw_show = true;
            break;

          }
        }

        if (sw_show) {
          _t.show();
        } else {
          _t.hide();
        }


      }
    })
  }
});

function sliders_ready($) {


};


function sliders_allready() {

  jQuery('table.main_sliders').find('.slider-in-table').eq(dzsap_settings.currSlider).addClass('active');
}

function dzsap_setupDbSelect() {
  var _c = jQuery('.db-select.dzsap');

  _c.append('<div class="db-select-nicecon"><div id="db-select-scroller-con" class="scroller-con easing" style="width: 180px; height: 80px;"><div class="inner"></div></div></div>');
  _c.find('.main-select').children().each(function () {
    var _t = jQuery(this);
    _c.find('.inner').append('<div class="a-db-option">select database <span class="strong">' + _t.text() + '</span><a href="' + _t.attr('data-newurl') + '" class="todb">&raquo;</a></div>');
  })
  _c.find('.inner').append('<div class="a-db-option">create database <input class="newdb"/><a href=" " class="todb createdb">&raquo;</a></div>');

  if (jQuery.fn.scroller) {

    jQuery('#db-select-scroller-con').scroller({
      settings_skin: 'skin_slider'
    });
  }
  _c.find('.todb.createdb').eq(0).on('click', function () {
    var _t = jQuery(this);

    if (_t.prev().val() == '') {
      _t.prev().addClass('attention');
      setTimeout(function () {
        _t.prev().removeClass('attention');

      }, 1000)
      return false;

    } else {
      var aux = _c.find('.replaceurlhelper').eq(0).text();
      aux = aux.replace('replaceurlhere', _t.prev().val());
      _t.attr('href', aux);
    }
  })
  jQuery('.dzsap .btn-show-dbs').on('click', function () {

    var _t = jQuery(this).parent();
    if (_t.find('.db-select-nicecon').eq(0).hasClass('active')) {
      _t.find('.db-select-nicecon').eq(0).removeClass('active')
    } else {
      _t.find('.db-select-nicecon').eq(0).addClass('active')
    }
  })
}





function sliders_reinit() {
  jQuery('.with_colorpicker').each(function () {
    var _t = jQuery(this);
    if (_t.hasClass('treated')) {
      return;
    }
    if (jQuery.fn.farbtastic) {
      _t.next().find('.picker').farbtastic(_t);

    }
    _t.addClass('treated');
  });
}


var uploader_frame;

function sliders_wpupload() {
  var _t = jQuery(this);
  targetInput = _t.prev();

  var searched_type = '';

  if (targetInput.hasClass('upload-type-audio')) {
    searched_type = 'audio';
  }
  if (targetInput.hasClass('upload-type-image')) {
    searched_type = 'image';
  }


  if (typeof wp != 'undefined' && typeof wp.media != 'undefined') {
    uploader_frame = wp.media.frames.dzsap_addplayer = wp.media({

      title: "Insert Media Modal",
      multiple: true,
      // Tell the modal to show only images.
      library: {
        type: searched_type
      },

      // Customize the submit button.
      button: {
        // Set the text of the button.
        text: "Insert Media",
        // Tell the button not to close the modal, since we're
        // going to refresh the page when the image is selected.
        close: false
      }
    });

    // When an image is selected, run a callback.
    uploader_frame.on('select', function () {

      var attachment = uploader_frame.state().get('selection').first();


      if (targetInput.hasClass('upload-prop-id')) {
        targetInput.val(attachment.attributes.id);
      } else {
        targetInput.val(attachment.attributes.url);

      }


      targetInput.trigger('change');
      uploader_frame.close();
    });

    // Finally, open the modal.
    uploader_frame.open();
  } else {
    tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true&amp;post_id=1&amp;width=640&amp;height=105');
    var backup_send_to_editor = window.send_to_editor;
    var intval = window.setInterval(function () {
      if (jQuery('#TB_iframeContent').attr('src') != undefined && jQuery('#TB_iframeContent').attr('src').indexOf("&field_id=") !== -1) {
        jQuery('#TB_iframeContent').contents().find('#tab-type_url').hide();
      }
      jQuery('#TB_iframeContent').contents().find('.savesend .button').val("Upload to Video Gallery");
    }, 50);
    window.send_to_editor = function (arg) {
      var fullpath = arg
        , fullpathArray = fullpath.split('>');


      var aux3 = jQuery(fullpath).attr('href');


      targetInput.val(aux3);
      targetInput.trigger('change');
      tb_remove();
      window.clearInterval(intval);
      window.send_to_editor = backup_send_to_editor;
    };
  }


  return false;
}


function sliders_delete_item(arg) {
  currSlider.find('.item-con').eq(arg).remove();

  if (arg < itemIndex[currSlider_nr] - 1) {
    for (let i = arg; i < itemIndex[currSlider_nr] - 1; i++) {
      var _c = currSlider.find('.item-con').eq(i);
      for (let j = 0; j < _c.find('.textinput').length; j++) {
        dzs_legacy_slidersRename(_c.find('.textinput').eq(j), currSlider_nr, i);
      }
    }
  }
  itemIndex[currSlider_nr]--;
  return false;
}





function dzs_legacy_slidersAddListeners() {
  jQuery('.add-item').unbind();
  jQuery('.add-item').on('click', click_additem);
  if (typeof jQuery.fn.sortable != 'undefined') {
    jQuery('.items-con').sortable({
      placeholder: "ui-state-highlight"
      , handle: '.item-preview'
      , update: item_onsorted
    });
  } else {
  }
  if (jQuery.fn.singleUploader) {
    jQuery('.dzs-upload').singleUploader();
  }
  if (window.dzstoggle_initalltoggles != undefined) {
    dzstoggle_initalltoggles();
  } else {
    if (window.console) {
      console.info('toggles not defined');
    }
    ;
  }



  function item_onsorted() {
    for (let i = 0; i < currSlider.find('.item-con').length; i++) {
      var _cache = currSlider.find('.item-con').eq(i);
      for (let j = 0; j < _cache.find('.textinput').length; j++) {
        var _cache2 = _cache.find('.textinput').eq(j);
        dzs_legacy_slidersRename(_cache2, undefined, i);
      }
    }
  }



  function extra_skin_hiddenselect() {
    for (let i = 0; i < jQuery('.select-hidden-metastyle').length; i++) {
      var _t = jQuery('.select-hidden-metastyle').eq(i);
      if (_t.hasClass('inited')) {
        continue;
      }

      _t.addClass('inited');
      _t.children('select').eq(0).on('change', change_selecthidden);
      change_selecthidden(null, _t.children('select').eq(0));
      _t.find('.an-option').on('click', click_anoption);
    }

    function change_selecthidden(e, arg) {
      var _c = jQuery(this);
      if (arg != undefined) {
        _c = arg;
      }
      var _con = _c.parent();
      var selind = _c.children().index(_c.children(':selected'));
      var _slidercon = _con.parent().parent();

      _con.find('.an-option').removeClass('active');
      _con.find('.an-option').eq(selind).addClass('active');

      do_changemainsliderclass(_slidercon, selind);
    }

    function click_anoption(e) {
      var _c = jQuery(this);
      var ind = _c.parent().children().index(_c);
      var _con = _c.parent().parent();
      var _slidercon = _con.parent().parent();
      _c.parent().children().removeClass('active');
      _c.addClass('active');
      _con.children('select').eq(0).children().removeAttr('selected');
      _con.children('select').eq(0).children().eq(ind).attr('selected', 'selected');

      do_changemainsliderclass(_slidercon, ind);

    }

    function do_changemainsliderclass(arg, argval) {


      if (arg.hasClass('slider-con')) {
        arg.removeClass('mode_normal');
        arg.removeClass('mode_ytuser');
        arg.removeClass('mode_ytplaylist');
        arg.removeClass('mode_vimeouser');
        if (argval == 0) {
          arg.addClass('mode_normal')
        }
        if (argval == 1) {
          arg.addClass('mode_ytuser')
        }
        if (argval == 2) {
          arg.addClass('mode_ytplaylist')
        }
        if (argval == 3) {
          arg.addClass('mode_vimeouser')
        }

      }
      if (arg.hasClass('item-settings-con')) {


        arg.removeClass('type_audio type_soundcloud type_shoutcast type_youtube type_mediafile type_inline');

        if (argval == 0) {
          arg.addClass('type_mediafile')
        }
        if (argval == 1) {
          arg.addClass('type_soundcloud')
        }
        if (argval == 2) {
          arg.addClass('type_shoutcast')
        }
        if (argval == 3) {
          arg.addClass('type_youtube')
        }
        if (argval == 4) {
          arg.addClass('type_audio')
        }
        if (argval == 5) {
          arg.addClass('type_inline')
        }
      }
    }

  }

  function click_additem() {


    dzs_legacy_slidersAddItem(currSlider_nr)
    dzs_legacy_slidersAddListeners();

    return false;
  }


  extra_skin_hiddenselect();
}


function dzs_legacy_slidersAddSlider(args) {
  var sliderslen = jQuery('.main_sliders').children('tbody').children().length;
  var auxurl = (dzsap_settings.urlcurrslider).replace('_currslider_', sliderslen);
  var auxdelurl = (dzsap_settings.urldelslider).replace('_currslider_', sliderslen);
  var auxname = 'default';


  if (sliderslen == 0) {
  }


  if (args != undefined && args.name != undefined) {
    auxname = args.name;
  }

  if (auxname == 'default-settings-for-zoomsounds') {
    auxname = '<span class="legacy-sliders--default-settings-name">General Settings for Zoomsounds <span class="italic">( default settings )</span></span>';
  }


  var auxs = '<tr class="slider-in-table"><td>' + auxname + '</td><td class="button_view"><strong><a href="' + auxurl + '" class="slider-action slider-edit">Edit</a></strong></td><td class="button_view"><strong><a href="#" class="slider-action slider-embed">Embed</a></strong></td><td class="button_view"><strong><a href="#" class="slider-action slider-sliderexport">Export</a></strong></td>';


  if (dzsap_settings.is_safebinding == 'on') {
    auxs += '<td class="button_view"><form method="POST" class="slider-duplicate-form"><input type="hidden" name="action" value="';

    if (pagenow == 'zoomsounds_page_dzsap_configs') {

      auxs += 'dzsap_duplicate_dzsap_configs';
    }
    if (pagenow == 'toplevel_page_dzsap_menu') {

      auxs += 'dzsap_duplicate_dzsap_slider';
    }

    auxs += '"/><input type="hidden" name="slidernr" value="' + sliderslen + '"/><input class="button-secondary" type="submit" value="Duplicate"/></form></td>';
  } else {
    auxs += '<td class="button_view"><strong><a href="#" class="slider-action slider-duplicate">Duplicate</a></strong></td>';
  }

  auxs += '<td class="button_view"><form method="POST" class="slider-delete"><input type="hidden" name="deleteslider" value="' + sliderslen + '"/><input class="button-secondary" type="submit" value="Delete"/></form></td></tr>';


  jQuery('.main_sliders').children('tbody').append(auxs);


  if (dzsap_settings.is_safebinding == 'on') {
    if (dzsap_settings.currSlider == sliderslen) {
      if (jQuery('.master-settings').hasClass("mode_vpconfigs")) {
        jQuery('.master-settings').append(videoplayerconfig);
      } else {
        jQuery('.master-settings').append(sliderstructure);
      }
    }
  } else {
    if (jQuery('.master-settings').hasClass("mode_vpconfigs")) {
      jQuery('.master-settings').append(videoplayerconfig);
    } else {
      jQuery('.master-settings').append(sliderstructure);
    }
  }
  for (let i = 0; i < jQuery('.slider-con').eq(sliderIndex).find('.textinput').length; i++) {
    var _cache = jQuery('.slider-con').eq(sliderIndex).find('.textinput').eq(i);
    dzs_legacy_slidersRename(_cache, sliderIndex, 'settings')
  }
  dzs_legacy_slidersAddListeners();
  itemIndex[sliderIndex] = 0;
  ++sliderIndex;

  sliders_reinit();
  return false;

}

/**
 *
 * @param arg1 - slider index
 * @param arg2
 * @param arg3
 * @returns {boolean}
 */
function dzs_legacy_slidersAddItem(arg1, arg2, arg3) {
  var j = 0;
  var _cache = jQuery('.items-con').eq(arg1);

  // -- we do not have itemstructure

  if (window.itemstructure) {

    _cache.append(window.itemstructure);
  }
  for (let i = 0; i < _cache.children().last().find('.textinput').length; i++) {
    dzs_legacy_slidersRename(_cache.children().last().find('.textinput').eq(i), arg1, itemIndex[arg1]);
  }
  if (arg2 != undefined) {
    _cache.children().last().find('.textinput').eq(0).val(arg2)
    _cache.children().last().find('.textinput').eq(0).trigger('change');
  }
  if (arg3 != undefined) {
    if (arg3.title != undefined) {
      _cache.children().last().find('.textinput').eq(3).val(arg3.title)
      _cache.children().last().find('.textinput').eq(3).trigger('change');
    }
    if (arg3.thumb != undefined) {
      _cache.children().last().find('.textinput').eq(1).val(arg3.thumb)
      _cache.children().last().find('.textinput').eq(1).trigger('change');
    }
    if (arg3.type != undefined) {
      var _c = _cache.children().last().find('.textinput').eq(2);
      _c.find(':selected').attr('selected', '');

      for (j = 0; j < _c.children().length; j++) {
        if (_c.children().eq(j).text() == arg3.type)
          _c.children().eq(j).attr('selected', 'selected');
      }
      _c.trigger('change');
    }
  }

  itemIndex[arg1]++;
  global_items++;

  return false;
}

function dzs_legacy_slidersViewWarningTooMany() {
  var limit = 15;

  if (dzsap_settings.is_safebinding == 'on') {
    limit = 75;
  }
  if (global_items > limit) {
    jQuery('.notes').append('<div class="warning"><strong>Warning</strong> - you have many items in this database. max_input_vars is defaulted to 1000. What this means is if you have more then ' + limit + ' items across all the galleries in this database, saving via the <strong>Save All Sliders</strong> option might not work. and there are three possible solutions to this:</p>        <ol>  <li>( recommended ) distribute your galleries accross multiple databases - <a href="https://digitalzoomstudio.net/docs/wpvideogallery/#explaination_dbs">see how</a>           <li>OR increase max_input_vars via php.ini or .htaccess file       <li>OR use the <strong>save slider</strong> ( single ) option - you can only save the current slider you are editing with this                </ol>        <p>Also remember to backup regularly via the Export option from the Gear menu</p></div>')
  }
}

function dzs_legacy_slidersShowSlider(arg1) {
  if (arg1 == currSlider_nr) {
    return;
  }
  jQuery('.slider-con').eq(currSlider_nr).fadeOut('fast');
  jQuery('.slider-con').eq(arg1).fadeIn('fast');
  currSlider_nr = arg1;
  currSlider = jQuery('.slider-con').eq(currSlider_nr);
  jQuery('.slider-con').removeClass('currSlider');
  currSlider.addClass('currSlider');
}




function dzs_legacy_slidersChange(arg1, argType, argLabel, arg4, pargs) {
  // -- called from the page html
  // -- select the main slider
  // -- @arg1 only helps in dynamic mode

  var margs = {
    'call_from': 'default'
  }

  if (pargs && typeof pargs != 'undefined') {
    margs = jQuery.extend(margs, pargs);
  }
  var _cache = jQuery('.slider-con').eq(arg1);


  if (argType == "settings") {
    // -- mainsettings
    for (let i = 0; i < _cache.find('.mainsetting').length; i++) {

      var _c2 = _cache.find('.mainsetting').eq(i);
      var searchedLabelName = arg1 + "-" + argType + "-" + argLabel;


      if (_c2.attr('name') == searchedLabelName) {


        _c2.val(arg4);
        if (_c2[0].nodeName == 'SELECT') {
          for (let j = 0; j < _c2.children().length; j++) {
            var auxval = _c2.children().eq(j).text();
            if (_c2.children().eq(j).attr('value') != '' && _c2.children().eq(j).attr('value') != undefined) {
              auxval = _c2.children().eq(j).attr('value');
            }
            if (auxval == arg4)
              _c2.children().eq(j).attr('selected', 'selected');
          }
        }
        if (_c2[0].nodeName == 'INPUT' && _c2.attr('type') == 'checkbox') {
          if (arg4 == 'on') {
            _c2.attr('checked', 'checked');
          }
        }
        _c2.change();
      }
    }
  } else {
    var _c2 = _cache.find('.item-con').eq(argType);
    for (var i = 0; i < _c2.find('.textinput').length; i++) {
      var _c3 = _c2.find('.textinput').eq(i);


      var aux = arg1 + "-" + argType + "-" + argLabel;
      if (_c3.attr('name') == aux) {
        _c3.val(arg4);
        if (_c3[0].nodeName == 'SELECT') {
          for (let j = 0; j < _c3.children().length; j++) {
            if (_c3.children().eq(j).text() == arg4)
              _c3.children().eq(j).attr('selected', 'selected');
          }
        }
        _c3.change();

      }
    }

  }
}

function dzs_legacy_slidersRename(arg1, arg2, arg3, arg4) {
  var name = arg1.attr('name');
  var aname = name.split('-');

  if (arg2 != 'same') {
    if (arg2 == undefined) {
      aname[0] = currSlider_nr;
    } else {
      aname[0] = arg2;
    }
  }
  if (arg3 != 'same') {
    if (arg3 == undefined) {
      aname[1] = itemIndex[currSlider_nr];
    } else {
      aname[1] = arg3;
    }
  }
  var str = aname[0] + '-' + aname[1] + '-' + aname[2];
  arg1.attr('name', str);

}




function dzs_sliders_saveAllVpcs() {
  jQuery('#save-ajax-loading').css('visibility', 'visible');
  var mainarray = jQuery('.master-settings').serialize();
  var data = {
    action: 'dzsap_save_vpc'
    , postdata: mainarray
    , currdb: dzsap_settings.currdb
  };
  jQuery('.saveconfirmer').html('Options saved.');
  jQuery('.saveconfirmer').fadeIn('fast').delay(2000).fadeOut('fast');
  jQuery.post(ajaxurl, data, function (response) {
    jQuery('#save-ajax-loading').css('visibility', 'hidden');
  });

  return false;
}



/* @projectDescription jQuery Serialize Anything - Serialize anything (and not just forms!)
 * @author Bramus! (Bram Van Damme)
 * @version 1.0
 * @website: https://www.bram.us/
 * @license : BSD
 */

(function ($) {

  $.fn.serializeAnything = function () {

    var toReturn = [];
    var els = $(this).find(':input').get();

    $.each(els, function () {
      if (this.name && !this.disabled && (this.checked || /select|textarea/i.test(this.nodeName) || /text|hidden|password/i.test(this.type))) {
        var val = $(this).val();
        toReturn.push(encodeURIComponent(this.name) + "=" + encodeURIComponent(val));
      }
    });

    return toReturn.join("&").replace(/%20/g, "+");

  }

})(jQuery);




