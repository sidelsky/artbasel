'use strict';
var coll_buffer = 0;
var func_output = '';
var fout = '';
jQuery(document).ready(function ($) {

  let shortcodeOutput = '';

  var _sel = $('select[name="dzsap_selectid"]');

  $(document).on('click', '.insert-sample-tracks,.insert-sample-library,.remove-sample-tracks, button.sg-1, button.sg-2, button.sg-3,.lib-item,#insert_tests', handle_mouse);
  $('#insert_single_player').on('click', click_insert_single_player);


  $(document).on('change', 'select[name="dzsap_selectid"]', handle_mouse)


  sg_update_edit_link();

  function sg_update_edit_link() {
    var _opt = _sel.find(':selected');

    if (_opt.attr('data-term_id')) {
      $('#sg_gallery_edit_link').attr('href', dzsap_settings.site_url + '/wp-admin/term.php?taxonomy=dzsap_sliders&tag_ID=' + _opt.attr('data-term_id') + '&post_type=dzsap_items')
      $('#sg_gallery_edit_link').attr('data-source', dzsap_settings.site_url + '/wp-admin/term.php?taxonomy=dzsap_sliders&tag_ID=' + _opt.attr('data-term_id') + '&post_type=dzsap_items')
    }
  }


  function get_query_arg(purl, key) {


    if (purl.indexOf(key + '=') > -1) {

      var regexS = "[?&]" + key + "(.+?)(?=&|$)";
      var regex = new RegExp(regexS);
      var regtest = regex.exec(purl);


      if (regtest != null) {


        if (regtest[1]) {
          var aux = regtest[1].replace(/=/g, '');
          return aux;
        } else {
          return '';
        }


      }

    }
  }


  function handle_mouse(e) {
    var _t = $(this);

    if (e.type == 'change') {
      sg_update_edit_link();
    }
    if (e.type == 'click') {


      if (_t.attr('id') == 'insert_tests') {
        shortcodeOutput = prepare_fout();
        tinymce_add_content(shortcodeOutput);
        return false;
      }
      if (_t.hasClass('insert-sample-library')) {


        window.open_ultibox(null, {


          type: 'inlinecontent'
          , source: '#import-sample-lib'

          , scaling: 'fill' // -- this is the under description
          , suggested_width: '95vw' // -- this is the under description
          , suggested_height: '95vh' // -- this is the under description
          , item: null // -- we can pass the items from here too

        });


      }
      if (_t.hasClass('lib-item')) {


        var post_id = 1;

        if (get_query_arg(top.location.href, 'post')) {
          post_id = get_query_arg(top.location.href, 'post');
        }

        var data = {
          action: 'dzsap_import_item_lib'
          , demo: _t.attr('data-demo')
          , post_id: post_id
        };

        _t.addClass('loading');

        jQuery.ajax({
          type: "POST",

          url: ajaxurl,
          data: data,
          success: function (response) {

            setTimeout(function () {
              "use strict";

              _t.removeClass('loading');
            }, 100);


            if (response.indexOf('"response_type":"error"') > -1) {

              show_notice(response);
            } else {
              var resp = JSON.parse(response);


              tinymce_add_content(resp.settings.final_shortcode);

              close_ultibox();

              setTimeout(function () {
                "use strict";
                top.close_ultibox();
              }, 500);

              if (resp.items) {


                for (var lab in resp.items) {

                  var c = resp.items[lab];


                  if (c.type == 'set_curr_page_footer_player') {


                    if (parent.set_curr_page_footer_player) {

                      parent.set_curr_page_footer_player(c);
                    }

                  }
                }
              }


            }


          },
          error: function (arg) {
            console.log('[error] Got this from the server: ' + arg);
            ;
          }
        });


      }


      if (_t.hasClass('insert-sample-tracks')) {


        var data = {
          action: 'ajax_dzsap_insert_sample_tracks'
        };


        $.ajax({
          type: "POST",
          url: ajaxurl,
          data: data,
          success: function (response) {
            window.location.reload();

          },
          error: function (arg) {
            console.log('[error] Got this from the server: ' + arg, arg);
            ;
          }
        });

        return false;
      }
      if (_t.hasClass('remove-sample-tracks')) {


        var data = {
          action: 'ajax_dzsap_remove_sample_tracks'
        };


        $.ajax({
          type: "POST",
          url: ajaxurl,
          data: data,
          success: function (response) {
            window.location.reload();

          },
          error: function (arg) {
            console.log('[error] Got this from the server: ' + arg, arg);
            ;
          }
        });

        return false;
      }


      if (_t.hasClass('sg-1')) {


        fout = window.sg1_shortcode;

        tinymce_add_content(fout);

      }
      if (_t.hasClass('sg-3')) {


        fout = window.sg3_shortcode;

        tinymce_add_content(fout);

      }


      if (_t.hasClass('sg-2')) {


        fout = window.sg2_shortcode;

        if (parent.dzsap_prepare_footer_player) {
          parent.dzsap_prepare_footer_player();
        }

        tinymce_add_content(fout);

      }
    }
  }

});

function tinymce_add_content(arg) {


  if (top == window) {

    jQuery('.shortcode-output').text(arg);
  } else {


    if (top.dzsap_widget_shortcode) {
      top.dzsap_widget_shortcode.val(arg);

      top.dzsap_widget_shortcode = null;

      if (top.close_zoombox2) {
        top.close_zoombox2();
      }
    } else {


      if (typeof (top.dzsap_receiver) == 'function') {
        top.dzsap_receiver(arg);
      }

    }

  }
}

function click_insert_tests() {
}

function prepare_fout() {
  let fout = '';
  fout += '[zoomsounds';
  var _c,
    _c2
  ;

  _c = jQuery('select[name=dzsap_selectid]');
  if (_c.val() != '') {
    fout += ' id="' + _c.val() + '"';
  }
  _c = jQuery('*[name=width]');
  if (_c.val() != '') {
    fout += ' width="' + _c.val() + '"';
  }
  _c = jQuery('*[name=height]');
  if (_c.val() != '') {
    fout += ' height="' + _c.val() + '"';
  }


  fout += ']';

  return fout;
}


function change_select() {
  var selval = (jQuery(this).find(':selected').text());
  jQuery(this).parent().children('span').text(selval);
}

function prepare_fout_single() {
  fout = '';


  fout += '[zoomsounds_player';


  var _targetSettinger = jQuery('.con-only-single').eq(0);
  jQuery('.item-con').each(function () {
    var _t = jQuery(this);
    if (_t.hasClass('active')) {
      _targetSettinger = _t;
    }
  })


  var lab = '';
  var _c;

  lab = 'source';
  _c = _targetSettinger.find('*[data-label="' + lab + '"]');
  fout += ' source="' + _c.val() + '"';

  lab = 'vpconfig';
  _c = jQuery('.con-only-single').eq(0).find('*[data-label="' + lab + '"]');
  fout += ' config="' + _c.val() + '"';

  lab = 'linktomediafile';
  _c = _targetSettinger.find('*[data-label="' + lab + '"]');
  fout += ' playerid="' + _c.val() + '"';

  lab = 'waveformbg';
  _c = _targetSettinger.find('*[data-label="' + lab + '"]');
  fout += ' waveformbg="' + _c.val() + '"';

  lab = 'waveformprog';
  _c = _targetSettinger.find('*[data-label="' + lab + '"]');
  fout += ' waveformprog="' + _c.val() + '"';

  lab = 'thumb';
  _c = _targetSettinger.find('*[data-label="' + lab + '"]');
  fout += ' thumb="' + _c.val() + '"';

  fout += ' autoplay="on" cue="on" enable_likes="off" enable_views="off" enable_rates="off"';


  lab = 'playfrom';
  _c = _targetSettinger.find('*[data-label="' + lab + '"]');
  fout += ' playfrom="' + _c.val() + '"';


  fout += ']';
}

function click_insert_single_player() {

  prepare_fout_single();
  tinymce_add_content(fout);
  return false;
}