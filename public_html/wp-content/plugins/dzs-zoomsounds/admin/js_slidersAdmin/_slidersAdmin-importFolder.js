import {parse_response, show_feedback} from '../js_common/_helper_admin';

export function importFolderInit($, selfInstance) {

  var slider_term_id = 0;
  var slider_term_slug = '';
  var $slidersCon = $('.dzsap-sliders-con').eq(0);
  var $sliderItems = $('.dzsap-slider-items').eq(0);

  var queryArg_page = 'slider_single';

  if (get_query_arg(window.location.href, 'taxonomy') == 'dzsap_sliders' && get_query_arg(window.location.href, 'post_type') == 'dzsap_items' && (typeof get_query_arg(window.location.href, 'tag_ID') == 'undefined' || typeof get_query_arg(window.location.href, 'tag_ID') == '')) {
    queryArg_page = 'slider_multiple';
  }

  if (queryArg_page == 'slider_single') {
    slider_term_id = $slidersCon.attr('data-term_id')
    slider_term_slug = $slidersCon.attr('data-term-slug')
  }


  $(document).on('click.sliders_admin', '.slider-item, .slider-item > .divimage, .add-btn-new, .add-btn-existing-media, .delete-btn,.clone-item-btn, .btn-import-folder, #import-options-link-wrap, .button-primary', handle_mouse);


  function handle_mouse() {
    var _t = $(this);
    if (_t.hasClass('btn-import-folder')) {


      var data = {
        action: 'dzsap_import_folder',
        payload: $('*[data-aux-name="folder_location"]').val(),
      }
      _t.parent().addClass('ajax-is-loading');

      setTimeout(function () {
        _t.parent().removeClass('ajax-is-loading');
      }, 1000);

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


          if (response.files) {
            for (var i in response.files) {
              var responseItemFromImport = response.files[i];



              var queue_call = {
                'type': 'create_item',
                'term_id': $slidersCon.attr('data-term_id'),
                'term_slug': $slidersCon.attr('data-term-slug'),
                'dzsap_meta_item_type': 'audio',
                'dzsap_meta_item_path': responseItemFromImport.path,
                'post_title': responseItemFromImport.name,
                'create_source': 'FROM_FOLDER_IMPORT',
                'dzsap_meta_item_source': responseItemFromImport.url,
              }


              // -- let us see if we already have this

              var isContinueImport = true;
              $('.slider-item').each(function () {
                var _t4 = $(this);




                if (_t4.find('input[name="dzsap_meta_item_source"]').val() == responseItemFromImport.url) {
                  isContinueImport = false;
                }
              });

              if (isContinueImport) {

                queue_call['dzsap_meta_order_' + slider_term_id] = 1 + $sliderItems.children().length + 0;
                window.dzs_slidersAdmin_ajaxQueue.push(queue_call);


                selfInstance.prepare_send_queue_calls(10, selfInstance);


                setTimeout(function () {


                  dzstaa_init('.dzs-tabs-meta-item', {
                    init_each: true
                  });


                  dzssel_init('select.dzs-style-me', {init_each: true});

                  $('*[data-aux-name="feed_mode"]').parent().find('.bigoption').eq(0).trigger('click');
                }, 1000);
              }
            }

          }
        },
        error: function (arg) {
            console.warn('Got this from the server / error: ' + arg);
          ;

        }
      });


      return false;
    }

  }
}