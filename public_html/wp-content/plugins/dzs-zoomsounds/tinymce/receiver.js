'use strict';

function dzsap_receiver(arg) {
  var aux = '';
  var bigaux = '';




  if (jQuery('#dzspb-pagebuilder-con').length > 0 && jQuery('#dzspb-pagebuilder-con').eq(0).css('display') == 'block' && typeof top.dzspb_lastfocused != 'undefined') {
    jQuery(top.dzspb_lastfocused).val(arg);
    jQuery(top.dzspb_lastfocused).trigger('change');
  } else {


    var ed = null;

    if (jQuery('#wp-content-wrap').hasClass('tmce-active') && window.tinyMCE) {

      ed = window.tinyMCE.activeEditor;
    } else {
      if (window.tinyMCE && window.tinyMCE.activeEditor) {
        ed = window.tinyMCE.activeEditor;
      }
    }

    if (ed) {
      if (window.mceeditor_sel && window.mceeditor_sel != 'notset') {
        if ( window.tinyMCE ) {


          if ( window.tinyMCE.activeEditor) {

          }


          if ( window.tinyMCE.execInstanceCommand ) {
            window.tinyMCE.execInstanceCommand(ed.id, 'mceInsertContent', false, arg);
          } else {

            if (ed && ed.execCommand) {
              ed.execCommand('mceReplaceContent', false, arg);

              if (window.remember_sel) {


                ed.dom.remove(window.remember_sel);

                window.remember_sel = null;
              }

            } else {

              window.tinyMCE.execCommand('mceReplaceContent', false, arg);
            }
          }
        }


      } else {

        window.tinyMCE.execCommand('mceReplaceContent', false, arg);
      }
    } else {
      aux = jQuery("#content").val();
      bigaux = aux + arg;
      if (window.htmleditor_sel != undefined && window.htmleditor_sel != '') {
        bigaux = aux.replace(window.htmleditor_sel, arg);
      }
      jQuery("#content").val(bigaux);
    }
  }

  close_ultibox();
}


window.dzsap_prepare_footer_player = function () {
  jQuery('*[name=dzsap_footer_featured_media]').val('fake');
  jQuery('*[name=dzsap_footer_vpconfig]').val('footer-player');
  jQuery('*[name=dzsap_footer_type]').val('fake');
  jQuery('*[name=dzsap_footer_vpconfig]').trigger('change');
}


window.set_curr_page_footer_player = function (c) {

  jQuery('*[name=dzsap_footer_enable]').prop('checked', true);

  jQuery('*[name=dzsap_footer_enable]').trigger('change');
  jQuery('*[name=dzsap_footer_feed_type]').val('parent');
  jQuery('*[name=dzsap_footer_feed_type]').trigger('change');
  jQuery('*[name=dzsap_footer_vpconfig]').val(c.src);
  jQuery('*[name=dzsap_footer_vpconfig]').trigger('change');

}