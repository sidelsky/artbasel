"use strict";

import {getSliderItemContainerFromSettingField, prepare_send_queue_calls, init_generateSampleShortcode} from "./js_slidersAdmin/_slidersAdmin-functions";
import {importFolderInit} from "./js_slidersAdmin/_slidersAdmin-importFolder";

import {init_query_arg_globals} from './js_common/_query_arg_func';
import {parse_response, show_feedback} from './js_common/_helper_admin';

window.dzs_slidersAdmin_ajaxQueue = [];

init_query_arg_globals();

class DzsSlidersAdmin{
  constructor($, SA_CONFIG) {

    this.$ = $;

    this.$slidersCon = null;

    this.isSaving = false;
    this.SA_CONFIG = SA_CONFIG;

    this.inter_send_to_ajax = 0;

 
    this.classInit();
  }

  classInit(){

    var selfInstance = this;

    var $ = this.$;

    // -- we ll create queue calls so that we send ajax only once

    var inter_queued_calls = 0;



    selfInstance.prepare_send_queue_calls = prepare_send_queue_calls;
    selfInstance.send_queue_calls = send_queue_calls;


    init_generateSampleShortcode(selfInstance);

    var term_id = 0;

    var _feedbacker = $('.feedbacker').eq(0);

    var $sliderItems = $('.dzsap-slider-items').eq(0);
    selfInstance.$slidersCon = $('.dzsap-sliders-con').eq(0);


    var queryArg_page = 'slider_single';

    if (get_query_arg(window.location.href, 'taxonomy') == selfInstance.SA_CONFIG['taxonomy'] && get_query_arg(window.location.href, 'post_type') == selfInstance.SA_CONFIG['post_type'] &&
      (typeof get_query_arg(window.location.href, 'tag_ID') == 'undefined' || typeof get_query_arg(window.location.href, 'tag_ID') == '')) {
      queryArg_page = 'slider_multiple';
    }


    var slider_term_id = 0;
    var slider_term_slug = '';
    if (queryArg_page == 'slider_single') {
      slider_term_id = selfInstance.$slidersCon.attr('data-term_id')
      slider_term_slug = selfInstance.$slidersCon.attr('data-term-slug')
    }


    _feedbacker.fadeOut('fast');



    setTimeout(function () {

      $('body').addClass('sliders-loaded');
    }, 600);

    if (queryArg_page == 'slider_multiple') {

      $('body').addClass('page-slider-multiple');
      var _colContainer = $('#col-container');
      $('#submit').after($('.dzs--nag-intro-tooltip--sliders-admin'));


      _colContainer.before('<div class="sliders-con"></div>');
      _colContainer.after('<div class="add-slider-con"></div>');

      var $slidersConMultiple = _colContainer.prev();
      var addSliderCon = _colContainer.next();

      $slidersConMultiple.append(_colContainer.find('#col-right').eq(0));

      $('#footer-thankyou').hide();
      selfInstance.$slidersCon.hide();

      $slidersConMultiple.find('.row-actions > .edit > a').css('margin-right', '15px');
      $slidersConMultiple.find('.row-actions > .edit > a').wrapInner('<span class="the-text"></span>');


      $slidersConMultiple.find('.row-actions > .edit > a').addClass('dzs-button btn-style-default skinvariation-border-radius-more btn-padding-medium text-strong color-normal-highlight color-over-dark font-size-small');


      $('#screen-meta-links').prepend('<div id="import-options-link-wrap" class="hide-if-no-js screen-meta-toggle">\n' +
        '\t\t\t<button type="button" id="show-settings-link" class="button show-settings" aria-controls="screen-options-wrap" aria-expanded="false">Import</button>\n' +
        '\t\t\t</div>');

      // -- end slider multiple

      $('#screen-options-wrap').after($('.import-slider-form'));
    }

    if (queryArg_page == 'slider_single') {
      $('body').addClass('page-slider-single');
      $('.dzsap-sliders').before($('#edittag').eq(0));


      $('#edittag').prepend($('#tabs-box').eq(0));
      $('.form-table:not(.custom-form-table)').addClass('sa-category-main');


      var _sa_categoryMain = $('.sa-category-main').eq(0);

      _sa_categoryMain.find('tr').eq(1).after('<div class="clear"></div>');
      _sa_categoryMain.find('.term-description-wrap').eq(0).after('<div class="clear"></div>');


      $('.tab-content-cat-main').append(_sa_categoryMain);

      dzstaa_init('#tabs-box');
      dzstaa_init('.dzs-tabs-meta-item', {
        init_each: true
      });


    }



    setTimeout(function () {
      $('.slider-status').removeClass('empty');
    }, 300);
    setTimeout(function () {
      $('.slider-status').removeClass('loading');
    }, 500);
    setTimeout(function () {
      // -- we place this here so that it won't fire with no reason ;)
      $(document).on('change', 'input.setting-field,select.setting-field,textarea.setting-field', handle_change);
      $(document).on('keyup', 'input.setting-field,select.setting-field,textarea.setting-field', handle_change);
      $('.slider-status').addClass('empty');
    }, 1000);


    $(document).on('change.sliders_admin', '*[name=the_post_title]', handle_change);
    $(document).on('click.sliders_admin', '.slider-item, .slider-item > .divimage, .add-btn-new, .add-btn-existing-media, .delete-btn,.clone-item-btn, #import-options-link-wrap, .button-primary', handle_mouse);

    importFolderInit($, selfInstance);


    window.onbeforeunload = function () {
      if (selfInstance.isSaving) {

        return "Please do not close this windows until the changes are saved.";
      }
    }

    setTimeout(function () {






      if (queryArg_page == 'slider_single' && get_query_arg(window.location.href, 'taxonomy') == selfInstance.SA_CONFIG['taxonomy']) {




        $('.wrap').eq(0).append(selfInstance.$slidersCon);
      }



      $sliderItems.sortable({
        placeholder: "ui-state-highlight"
        , items: ".slider-item"
        , stop: function (event, ui) {

          var arr_order = [];
          var i = 1;
          $sliderItems.children().each(function () {
            var _t = $(this);
            var aux = {
              'id': _t.attr('data-id')
              , 'order': i++
            }

            arr_order.push(aux);


          })


          let queue_call = {
            'type': 'set_meta_order'
            , 'items': arr_order
            , 'term_id': slider_term_id
          }


          window.dzs_slidersAdmin_ajaxQueue.push(queue_call);



          prepare_send_queue_calls(undefined, selfInstance);
        }
      });

      $('#tabs-box').after($('.slidersAdmin--metaArea'));
    }, 500);


    function handle_change(e) {
      var $t = $(this);

      var $sliderItem = null;



      if (e.type == 'change' || e.type == 'keyup') {


        $sliderItem = getSliderItemContainerFromSettingField($t);


        if ($t.attr('name') == 'dzsap_meta_item_source') {
          setTimeout(function () {

            $t.parent().parent().find('*[name=dzsap_meta_source_attachment_id]').trigger('change');
          }, 200);
        }
        if ($t.attr('name') == 'the_post_title') {
          $sliderItem.find('.slider-item--title').html($t.val());
        }


        // -- change the thumbnail
        if (String($t.attr('name')).indexOf('item_thumb') > -1) {

          $sliderItem.find('.divimage').eq(0).css({
            'background-image': 'url(' + $t.val() + ')'
          });

        }

        if ($sliderItem) {
          var id = $sliderItem.attr('data-id');





          let queue_call = {
            'type': 'set_meta'
            , 'item_id': id
            , 'lab': $t.attr('name')
            , 'val': $t.val()
          };


          var sw_found_and_set = false;
          for (var lab in window.dzs_slidersAdmin_ajaxQueue) {
            var val = window.dzs_slidersAdmin_ajaxQueue[lab];



            if (val.type == 'set_meta') {
              if (val.item_id == id) {
                if (val.lab == $t.attr('name')) {
                  window.dzs_slidersAdmin_ajaxQueue[lab].val = $t.val();
                  sw_found_and_set = true;
                }
              }
            }
          }

          if (sw_found_and_set == false) {

            window.dzs_slidersAdmin_ajaxQueue.push(queue_call);
          }



          prepare_send_queue_calls(undefined, selfInstance);
        }
      }
    }


    function handle_mouse(e) {
      var $t = $(this);

      if (e.type == 'click') {


        if ($t.attr('id') == 'import-options-link-wrap') {
          if ($t.hasClass('active') == false) {

            $('.import-slider-form').show();
            $('#screen-meta').slideDown('fast');
            $('#screen-options-link-wrap').fadeOut('fast');




            $t.addClass('active');
          } else {

            $('#screen-meta').slideUp('fast');
            $('.import-slider-form').fadeOut('fast');
            $('#screen-options-link-wrap').fadeIn('fast');




            $t.removeClass('active');
          }
        }


        if ($t.hasClass('button-primary')) {


          if (window.dzs_slidersAdmin_ajaxQueue.length) {
            prepare_send_queue_calls(10, selfInstance);

            setTimeout(function () {

              $('.button-primary').trigger('click');
            }, 1000);
            return false;
          }
        }
        if ($t.hasClass('delete-btn')) {




          let queue_call = {
            'type': 'delete_item'
            , 'id': $t.parent().attr('data-id')
            , 'term_slug': slider_term_slug


          }
          window.dzs_slidersAdmin_ajaxQueue.push(queue_call);


          prepare_send_queue_calls(10, selfInstance);

          $t.parent().remove();


          return false;
        }
        if ($t.hasClass('clone-item-btn')) {




          let queue_call = {
            'type': 'duplicate_item'
            , 'id': $t.parent().attr('data-id')
            , 'term_slug': slider_term_slug
          }
          window.dzs_slidersAdmin_ajaxQueue.push(queue_call);


          prepare_send_queue_calls(10, selfInstance);


          return false;
        }
        if ($t.hasClass('add-btn--icon')) {



          let queue_call = {
            'type': 'create_item'
            , 'term_id': selfInstance.$slidersCon.attr('data-term_id')
            , 'term_name': selfInstance.$slidersCon.attr('data-term-slug')
          }
          queue_call['dzsap_meta_order_' + slider_term_id] = 1 + $sliderItems.children().length + 0;
          window.dzs_slidersAdmin_ajaxQueue.push(queue_call);


          prepare_send_queue_calls(10, selfInstance);


        }
        if ($t.hasClass('add-btn-new')) {



          let queue_call = {
            'type': 'create_item',
            'create_source': 'FROM_MANUAL_ADD',
            'term_id': selfInstance.$slidersCon.attr('data-term_id'),
            'term_slug': selfInstance.$slidersCon.attr('data-term-slug')
          }
          queue_call['dzsap_meta_order_' + slider_term_id] = 1 + $sliderItems.children().length + 0;
          window.dzs_slidersAdmin_ajaxQueue.push(queue_call);


          prepare_send_queue_calls(10, selfInstance);

        }
        if ($t.hasClass('add-btn-existing-media')) {


          var _targetInput = $t.prev();

          var searched_type = '';

          if ($t.hasClass('upload-type-audio') || _targetInput.hasClass('upload-type-audio')) {
            searched_type = 'audio';
          }
          if (_targetInput.hasClass('upload-type-video')) {
            searched_type = 'video';
          }
          if (_targetInput.hasClass('upload-type-image')) {
            searched_type = 'image';
          }


          var frame = wp.media.frames.dzsp_addimage = wp.media({
            title: "Insert Media"
            , multiple: true
            , library: {
              type: searched_type
            },

            // Customize the submit button.
            button: {
              // Set the text of the button.
              text: "Insert Media",
              close: true
            }
          });

          // When an image is selected, run a callback.
          frame.on('select', function (arg1, arg2) {
            // Grab the selected attachment.



            // TODO: add code here

            var selection = frame.state().get('selection');




            var i_sel = 0;
            selection.map(function (attachment) {
              attachment = attachment.toJSON();




              let queue_call = {
                'type': 'create_item'
                , 'term_id': selfInstance.$slidersCon.attr('data-term_id')
                , 'term_slug': selfInstance.$slidersCon.attr('data-term-slug')
                , 'post_title': attachment.title
                , 'dzsap_meta_item_source': attachment.url
              }

              queue_call['dzsap_meta_order_' + slider_term_id] = 1 + $sliderItems.children().length + i_sel;
              window.dzs_slidersAdmin_ajaxQueue.push(queue_call);

              i_sel++;


            });
            prepare_send_queue_calls(10, selfInstance);



          });

          // Finally, open the modal.
          frame.open();

          e.stopPropagation();
          e.preventDefault();
          return false;


        }
        if ($t.hasClass('slider-item')) {


          if ($t.hasClass('tooltip-open')) {

          } else {

            $('.slider-item').removeClass('tooltip-open').find('.dzstooltip').removeClass('active');

            $t.addClass('tooltip-open');
            $t.find('.dzstooltip').addClass('active');
          }

        }

        if ($t.hasClass('divimage')) {


          if ($t.parent().hasClass('slider-item')) {

            var _par = $t.parent();
            if (_par.hasClass('tooltip-open')) {

              _par.removeClass('tooltip-open');
              _par.find('.dzstooltip').removeClass('active');
              return false;
            }
          }

        }
      }
    }



    function send_queue_calls() {


      $('.slider-status').removeClass('empty');

      var arg = JSON.stringify(window.dzs_slidersAdmin_ajaxQueue);
      var data = {
        action: 'dzsap_send_queue_from_sliders_admin'
        , the_term_id: selfInstance.$slidersCon.attr('data-term-id')
        , postdata: arg
      };


      jQuery.ajax({
        type: "POST",
        url: window.ajaxurl,
        data: data,
        success: function (response) {

          response = parse_response(response);


          if (response.report_message) {
            if (window) {

              show_feedback(response.report_message);
            }
          }


          if (response.items) {
            for (var i in response.items) {
              var responseItem = response.items[i];

              if (responseItem.type == 'create_item') {

                if (responseItem.original_request == 'duplicate_item') {
                  $('.slider-item[data-id="' + responseItem.original_post_id + '"]').after(responseItem.str);
                } else {
                  $sliderItems.append(responseItem.str);
                }

                dzstaa_init('.dzs-tabs-meta-item', {
                  init_each: true
                });
                dzssel_init('select.dzs-style-me', {init_each: true});
              }
            }

          }

          $('.slider-status').addClass('empty');
          selfInstance.isSaving = false;
          window.dzs_slidersAdmin_ajaxQueue = [];
        },
        error: function (arg) {
            console.log('Got this from the server / error: ' + arg);
          ;
        }
      });
    }
  }
}


jQuery(document).ready(function ($) {


  new DzsSlidersAdmin($, {

    taxonomy: 'dzsap_sliders',
    post_type: 'dzsap_items',
    shortcode_sample: '[zoomsounds id="{{theslug}}"]',
  });

});