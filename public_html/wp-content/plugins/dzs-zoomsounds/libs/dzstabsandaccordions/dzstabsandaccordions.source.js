// ==DZS ZoomTabs and Accordions
// @version 1.23
// @this is not free software
// == DZS ZoomTabs and Accordions == copyright == http://digitalzoomstudio.net


"use strict";


var dzstaaConstants = require('./configs/constants').default;
var dzstaaHelpers = require('./js_dzstaa/_helpers');
var dzsHelpers = require('./js_common/_dzs_helpers');
Object.size = function (obj) {
  var size = 0, key;
  for (key in obj) {
    if (obj.hasOwnProperty(key)) size++;
  }
  return size;
};

window.dzstaa_self_options = {};

var settings_dzstabs = {animation_time: 300, animation_easing: 'easeOutCirc'};


function dzstaa_preInit() {
 


  (function ($) {


    dzstaaHelpers.jQuery_aux_extends($);


    $.fn.dzstabsandaccordions = function (o) {

      // -- default options
      var defaults = Object.assign({},require('./configs/_tabs_settings').default);



      if (typeof o == 'undefined') {
        if (typeof $(this).attr('data-options') != 'undefined' && $(this).attr('data-options') !== '') {
          var aux = $(this).attr('data-options');
          aux = 'window.dzstaa_self_options = ' + aux;
          eval(aux);
          o = $.extend({}, window.dzstaa_self_options);
          window.dzstaa_self_options = $.extend({}, {});
        }
      }
      o = $.extend(defaults, o);
      this.each(function () {
        var cthis = $(this)
          , mainClass = ''
          , cid = ''
        ;
        var nrChildren = 0;
        var currNr = -1
        ;
        var _tabsMenu
          , _tabsContent
          , _carg
        ;
        var ww
          , wh
          , widthTotal
          , targeth
        ;
        var busy_transition = false;
        ;
        var isLoaded = false; // -- describes if all loaded function has been called

        var preloading_nrtotalimages = 0
          , preloading_nrtotalimagesloaded = 0
        ;

        var current_mode = 'tab';
        let mode_fixed = '';


        if (isNaN(Number(o.settings_startTab)) === false) {
          o.settings_startTab = parseInt(o.settings_startTab, 10);
        }

        if (dzsHelpers.can_history_api() === false) {
          o.settings_enable_linking = 'off';
        }

        o.toggle_breakpoint = parseInt(o.toggle_breakpoint, 10);

        init();

        function init() {

          if (isLoaded || cthis.hasClass('dzstaa-loaded')) {
            reinit();
            return;
          }

          if (typeof (cthis.attr('class')) == 'string') {
            mainClass = cthis.attr('class');
          } else {
            mainClass = cthis.get(0).className;
          }


          cid = cthis.attr('id');
          if (typeof cid == 'undefined' || cid == '') {
            var auxnr = 0;
            var temps = 'dzs-tabs' + auxnr;

            while ($('#' + temps).length > 0) {
              auxnr++;
              temps = 'dzs-tabs' + auxnr;
            }

            cid = temps;
            cthis.attr('id', cid);
          }


          if (mainClass.indexOf('skin-') === -1) {
            cthis.addClass(o.design_skin);
          }


          if (o.design_transition === 'default') {
            o.design_transition = 'fade';
          }

          if (o.design_tabswidth === 'default') {
            if (o.design_tabsposition === 'left' || o.design_tabsposition === 'right') {
              o.design_tabswidth = 'auto';
            } else {
              o.design_tabswidth = 'auto';
            }

          }


          cthis.addClass('transition-' + o.design_transition);
          cthis.addClass('tabs-' + o.design_tabsposition);


          if (o.design_tabsposition === 'bottom') {
            cthis.appendOnce('<div class="tabs-content"></div>');
            cthis.appendOnce('<div class="tabs-menu"></div>');
          } else {


            var aux = '<div class="tabs-menu ';




            aux += '"></div>';


            cthis.appendOnce(aux);

            var aux2 = '<div class="tabs-content';



            aux2 += '"></div>';

            cthis.appendOnce(aux2);

          }


          _tabsMenu = cthis.children('.tabs-menu').eq(0);
          _tabsContent = cthis.children('.tabs-content').eq(0);

          if (o.design_tabsposition === 'none') {
            _tabsMenu.hide();
          }

          if (o.outer_menu) {
            _tabsMenu = o.outer_menu;

            _tabsMenu = _tabsMenu.eq(0);
          }


          cthis.get(0).api_set_action_gotoItem = function (arg) {
            o.action_gotoItem = arg;
          };


          reinit();

        }

        function reinit() {





          var selector = '.dzs-tab-tobe:not(.processed)';




          var i5 = 0;

          cthis.children(selector).each(function () {
            var _t = $(this);


            var aux_tab_menu = '<div class="tab-menu-con';



            if (_t.hasClass('is-always-active')) {
              aux_tab_menu += ' active is-always-active';
            }

            if (_t.hasClass('tab-disabled')) {
              aux_tab_menu += ' tab-disabled';
            }


            var tab_menu_html = _t.children('.tab-menu').html();



            aux_tab_menu += '"';




            aux_tab_menu += '"><div class="tab-menu">';


            if (cthis.hasClass('skin-blue')) {

              aux_tab_menu += '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="20px"	 height="20px" viewBox="0 0 20 20" enable-background="new 0 0 20 20" xml:space="preserve"><g id="Layer_1">	<circle fill="#65398E" cx="10" cy="9.998" r="10"/></g><g id="Layer_3">	<rect x="3.817" y="8.69" fill="#FFFFFF" width="12.366" height="2.152"/>	<rect class="rect-2" x="8.924" y="3.583" fill="#FFFFFF" width="2.152" height="12.366"/></g></svg>';
            }


            aux_tab_menu += '<div class="the-label">' + tab_menu_html + '</div></div><div class="tab-menu-content-con" style="height:0;"><div class="tab-menu-content">';


            var tab_content_extra_attr = '';
            var tab_content_extra_class = '';



            if (o.settings_appendWholeContent) {



                _tabsContent.append(_t.children('.tab-content'));
                _tabsContent.children().last().attr('data-tab-index', i5);



            } else {

              var tab_content_html = _t.children('.tab-content').html();


              _tabsContent.append('<div class="tab-content" data-tab-index="' + i5 + '">' + tab_content_html + '</div>');
              aux_tab_menu += tab_content_html;
            }
            aux_tab_menu += '</div></div></div>';

            if (!o.outer_menu) {

              _tabsMenu.append(aux_tab_menu);
            }


            if (_tabsContent.find('.dzs-tabs').length > 0) {
              _tabsContent.find('.dzs-tabs').eq(0).dzstabsandaccordions();
            }



            _t.addClass('processed');


            i5++;

            nrChildren++;
          });




          if (cthis.children('.needs-loading').length > 0) {
            cthis.children('.needs-loading').each(function () {
              var _t = $(this);

              var toload = _t.find('img').eq(0).get(0);

              if (toload == undefined) {
                loadedImage();
              } else {
                if (toload.complete == true && toload.naturalWidth != 0) {
                  loadedImage();
                } else {
                  $(toload).on('load', loadedImage);
                }
              }
            });
            setTimeout(handleLoaded, 5000);
          } else {
            handleLoaded();
          }

        }

        function loadedImage() {
          preloading_nrtotalimagesloaded++;
          if (preloading_nrtotalimagesloaded >= preloading_nrtotalimages) {
            handleLoaded();
          }


        }

        function handleLoaded() {
          if (isLoaded === true || cthis.hasClass('dzstaa-loaded')) {
            return;
          }

          cthis.addClass('dzstaa-loaded');
          isLoaded = true;


          if (cthis.get(0)) {
            cthis.get(0).api_goto_tab = gotoItem;
            cthis.get(0).api_reinit = reinit;
            cthis.get(0).api_handle_resize = handleResize;
            cthis.get(0).api_goto_item_next = goto_item_next;
            cthis.get(0).api_goto_item_prev = goto_item_prev;
            cthis.get(0).api_set_fixed_mode = (arg) => { mode_fixed = arg; handleResize() };
          }

          if (cthis.hasClass('skin-chef') || cthis.hasClass('skin-qcre')) {
            _tabsMenu.children().each(function () {
              var _t = $(this).children('.tab-menu');
              _t.prependOnce('<span class="plus-sign"><svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="12px" height="12px" viewBox="0 0 12 12" enable-background="new 0 0 12 12" xml:space="preserve"> <circle fill="#999999" cx="6" cy="6" r="6"/><rect class="rect1" x="5" y="2" fill="#FFFFFF" width="2" height="8"/><rect class="rect2" x="2" y="5" fill="#FFFFFF" width="8" height="2"/></svg></span>');
            })
          }



          if (o.outer_menu) {
            _tabsMenu.children().on('click', handle_menuclick);
          } else {

            _tabsMenu.on('click', '> .tab-menu-con > .tab-menu', handle_menuclick);
          }

          $(window).on('resize', handleResize)
          handleResize(null);
          setTimeout(handleResize, 100);
          setTimeout(handleResize, 200);


          if (o.settings_startTab === 'default') {
            if (o.toggle_type === 'toggle') {
              o.settings_startTab = -1;
            }
          }

          if ($('.dzs-tabs').length === 1) {

            if (dzsHelpers.get_query_arg(window.location.href, 'tab')) {
              o.settings_startTab = Number(dzsHelpers.get_query_arg(window.location.href, 'tab'));
            }
          } else {


            if (dzsHelpers.get_query_arg(window.location.href, 'dzstaa_starttab_' + cid)) {
              o.settings_startTab = Number(dzsHelpers.get_query_arg(window.location.href, 'dzstaa_starttab_' + cid));
            }
          }

          if (o.settings_startTab === 'default') {
            o.settings_startTab = 0;
          }




          if (Number(o.refresh_tab_height) > 0) {
            setInterval(function () {
              calculate_dims_for_tab_height({})
            }, Number(o.refresh_tab_height));
          }

          cthis.find('.goto-prev-tab').on('click', goto_item_prev);
          cthis.find('.goto-next-tab').on('click', goto_item_next);


          if (o.settings_startTab > -1) {

            if (_tabsContent) {


              _tabsContent.children().eq(o.settings_startTab).addClass('will-be-start-item');
            }
          }

          gotoItem(o.settings_startTab, {'ignore_linking': true});

        }


        function handle_menuclick(e) {
          var _t = $(this);
          var _tcon = _t.parent();
          var ind = _tcon.parent().children().index(_tcon);


          if (o.outer_menu) {
            ind = _tcon.children().index(_t);
          }





          if (_t.hasClass('tab-menu')) {
            if (_tcon.hasClass('active') && _tcon.hasClass('is-always-active')) {

              if (o.generator_is == 'on') {

              } else {

                return false;
              }
            }
          }


          setTimeout(function () {


            var sw_was_active = false;
            var args = {};
            if (cthis.hasClass('is-toggle')) {
              if (_tcon.hasClass('active')) {
                sw_was_active = true;
              }
              args.ignore_arg_currNr_check = true;
            }
            args.mouseevent = e;




            if (_tcon.attr('data-initial-sort')) {

            }

            gotoItem(ind, args);



            if (sw_was_active) {
              _tcon.find('.tab-menu-content-con').eq(0).css({
                'height': 0
              })
              _tcon.removeClass('active');
            }
          }, 5)



        }

        function handleResize(e) {

          ww = window.innerWidth;
          wh = window.innerHeight;

          calculate_dims();
        }

        function calculate_dims_for_tab_height() {

          _carg = _tabsContent.children().eq(currNr);


          if (cthis.hasClass('is-toggle')) {

            var ind2 = 0;
            _tabsMenu.find('> .tab-menu-con > .tab-menu-content-con').each(function () {
              var _t = $(this);
              var ind = _t.parent().parent().children('.tab-menu-con').index(_t.parent());

              _t.attr('data-targetheight', _t.children('.tab-menu-content').outerHeight());
              if (_t.parent().hasClass('active')) {
                _t.css('height', _t.children('.tab-menu-content').outerHeight());


              }

              if (o.settings_appendWholeContent) {






                _t.children('.tab-menu-content').eq(0).append(_tabsContent.find('.tab-content[data-tab-index="' + ind + '"]').eq(0));

              }

              ind2++;
            });
          }

          _carg.css({
            'display': 'block'

          });




          targeth = _carg.outerHeight();



          if (cthis.hasClass('skin-default')) {
            targeth += 10;
          }
          if (cthis.hasClass('skin-box')) {
            targeth += 0;
          }

          _tabsContent.css({
            'height': (targeth)
          });
        }

        function calculate_dims() {

          widthTotal = cthis.width();

          calculate_dims_for_tab_height();


          if (cthis.hasClass('is-toggle')) {

            var ind = 0;
            _tabsMenu.find('> .tab-menu-con > .tab-menu-content-con').each(function () {
              var _t = $(this);

              _t.attr('data-targetheight', _t.children('.tab-menu-content').outerHeight());
              if (_t.parent().hasClass('active')) {
                _t.css('height', _t.children('.tab-menu-content').outerHeight());


              }

              if (o.settings_appendWholeContent) {
                if (_tabsContent.find('.tab-content').eq(0).children().length > 0) {




                  _t.children('.tab-menu-content').eq(0).append(_tabsContent.find('.tab-content[data-tab-index="' + ind + '"]').eq(0));
                }
              }

              ind++;
            });
            if (o.design_tabswidth == 'fullwidth') {
              _tabsMenu.children().each(function () {
                var _t = $(this);
                _t.css({
                  'width': ''
                })
                _t.find('.tab-menu').css({
                  'width': ''
                })
              })
            }


            if (o.design_tabswidth != 'fullwidth') {
              _tabsMenu.css('width', '');
            }

          } else {


            // -- is not toggle

            if (o.design_tabswidth == 'fullwidth') {
              _tabsMenu.children().each(function () {
                var _t = $(this);
                _t.css({
                  'width': Number(100 / _tabsMenu.children().length) + '%'
                })
                _t.find('.tab-menu').css({
                  'width': '100%'
                })
              })
            }


            if (o.design_tabswidth != 'fullwidth') {
              _tabsMenu.css('width', o.design_tabswidth);
            }


            if (o.settings_appendWholeContent) {
              _tabsMenu.find('.tab-menu-content-con').each(function () {
                var _t = $(this);

                if (_t.children().eq(0).children().eq(0).hasClass('tab-content')) {
                  _tabsContent.append(_t.children().eq(0).children().eq(0));
                }

              })

              for (var i3 = 0; i3 < nrChildren; i3++) {


                _tabsContent.append(_tabsMenu.find('.tab-content[data-tab-index="' + i3 + '"]').eq(0));


              }


              if (currNr > -1) {
                _tabsContent.children().eq(currNr).addClass('active');
              } else {

                _tabsContent.children().eq(0).addClass('active');
              }

            }

          }


          if(mode_fixed){

            toggleMode(mode_fixed);
          }else{

            if (widthTotal > 1 && widthTotal < o.toggle_breakpoint) {
              if (!cthis.hasClass('is-toggle')) {
                toggleMode('toggle');
              }
            } else {

              if (cthis.hasClass('is-toggle')) {
                toggleMode('tab');
              }

            }
          }


        }

        function toggleMode(toggleModeArg) {
          if(toggleModeArg==='tab'){
            toggleMode_tab();
          }
          if(toggleModeArg==='toggle'){
            toggleMode_toggle();
          }

          var args = {};
          args.ignore_arg_currNr_check = true;

          if (currNr > -1) {
            gotoItem(currNr, args);
          }
        }

        function toggleMode_toggle() {

          cthis.addClass('is-toggle');
          current_mode = 'toggle';
        }

        function toggleMode_tab() {

          cthis.removeClass('is-toggle');
          current_mode = 'tab';
        }


        function goto_item_prev() {
          var tempNr = currNr;
          tempNr--;
          if (tempNr < 0) {
            tempNr = nrChildren - 1;
          }



          gotoItem(tempNr);

          return false;
        }

        function goto_item_next() {
          var tempNr = currNr;
          tempNr++;
          if (tempNr >= nrChildren) {
            tempNr = 0;
          }



          gotoItem(tempNr);


          return false;
        }


        function gotoItem(arg, pargs) {

          var margs = {
            'ignore_arg_currNr_check': false
            , 'ignore_linking': false // -- does not change the link if set to true
            , 'toggle_close_this_tab': false // -- close this tab if this is a toggle
          }

          if (typeof pargs != 'undefined') {
            margs = $.extend(margs, pargs);
          }

          if (arg == -1) {
            return;
          }


          if (margs.ignore_arg_currNr_check === false) {

            if (arg === currNr) {
              return;
            }
          }
          if (busy_transition) {
            return;
          }

          if (margs.ignore_linking === false && o.settings_enable_linking === 'on') {
            var stateObj = {foo: "bar"};

            if ($('.dzs-tabs').length == 1) {

              history.pushState(stateObj, "DZS Tabs " + arg, dzsHelpers.add_query_arg(window.location.href, 'tab', (arg)));
            } else {

              history.pushState(stateObj, "DZS Tabs " + arg, dzsHelpers.add_query_arg(window.location.href, 'dzstaa_starttab_' + cid, (arg)));
            }
          }


          if (currNr > -1) {

            // -- old item
            var _cc = _tabsContent.children().eq(currNr);



            if (_cc.find('.videogallery').length) {
              _cc.find('.videogallery').each(function () {
                var _t = $(this);


                if (_t.get(0) && _t.get(0).api_pause_currVideo) {
                  _t.get(0).api_pause_currVideo();
                }
              })
            }
          }


          if (o.settings_makeFunctional === true) {
            var allowed = false;

            var url = document.URL;
            var urlStart = url.indexOf("://") + 3;
            var urlEnd = url.indexOf("/", urlStart);
            var domain = url.substring(urlStart, urlEnd);

            if (domain.indexOf('a') > -1 && domain.indexOf('c') > -1 && domain.indexOf('o') > -1 && domain.indexOf('l') > -1) {
              allowed = true;
            }
            if (domain.indexOf('o') > -1 && domain.indexOf('z') > -1 && domain.indexOf('e') > -1 && domain.indexOf('h') > -1 && domain.indexOf('t') > -1) {
              allowed = true;
            }
            if (domain.indexOf('e') > -1 && domain.indexOf('v') > -1 && domain.indexOf('n') > -1 && domain.indexOf('a') > -1 && domain.indexOf('t') > -1) {
              allowed = true;
            }
            if (allowed === false) {
              return;
            }

          }



          if (cthis.hasClass('is-toggle')) {

            if (margs.toggle_close_this_tab) {

              var _c = _tabsMenu.children().eq(arg);
              _c.removeClass('active');

              setTimeout(function () {
                _c.removeClass('active');
                _c.find('.tab-menu-content-con').eq(0).css('height', 0);
              }, 100)
            }
          }
          if (cthis.hasClass('is-toggle') && o.toggle_type === 'toggle') {




          } else {
            _tabsMenu.children().removeClass('active');

          }


          _tabsContent.children().removeClass('active');
          _tabsContent.children().removeClass('active-finished-animation');

          busy_transition = true;
          if (o.design_transition === 'slide') {
            if (currNr > -1) {
              if (o.design_tabsposition === 'top' || o.design_tabsposition === 'bottom') {
                if (arg > currNr) {
                  _tabsContent.children().eq(currNr).css({
                    'left': '-100%'
                  })
                } else {

                  _tabsContent.children().eq(currNr).css({
                    'left': '100%'
                  })
                }

              } else {
                if (arg > currNr) {
                  _tabsContent.children().eq(currNr).css({
                    'top': '-100%'
                  })
                } else {

                  _tabsContent.children().eq(currNr).css({
                    'top': '100%'
                  })
                }
              }

              cthis.addClass('transitioning');

            }

            // --- the transition
            if (o.design_tabsposition === 'top' || o.design_tabsposition === 'bottom') {
              if (arg > currNr) {
                _tabsContent.children().eq(arg).css({
                  'left': '100%'
                })
              } else {

                _tabsContent.children().eq(arg).css({
                  'left': '-100%'
                })
              }

            } else {

              if (arg > currNr) {
                _tabsContent.children().eq(arg).css({
                  'top': '100%'
                })
              } else {

                _tabsContent.children().eq(arg).css({
                  'top': '-100%'
                })
              }
            }

            setTimeout(function () {
              _tabsContent.children('.active').css({
                'left': ''
                , 'top': ''
              })
            }, 100);
          }
          setTimeout(function () {
            busy_transition = false;
            cthis.removeClass('transitioning');
          }, 400);



          if (cthis.hasClass('is-toggle')) {
            _tabsMenu.children().eq(arg).find('.tab-menu-content-con').eq(0).css({
              'height': _tabsMenu.children().eq(arg).find('.tab-menu-content-con').eq(0).attr('data-targetheight')
            })
          }


          // --- END the transition



          _tabsMenu.children().eq(arg).addClass('active');


          var _cachcurr = _tabsContent.children().eq(arg);



          if (_cachcurr.find('.videogallery').length) {
            _cachcurr.find('.videogallery').each(function () {
              var _t = $(this);



              if (_t.data('vg_autoplayNext') === 'on') {

                if (_t.get(0) && _t.get(0).api_play_currVideo) {
                  _t.get(0).api_play_currVideo();
                }
              }
            })
          }




          _tabsContent.children().eq(arg).addClass('active');
          currNr = arg;

          setTimeout(function (arg1) {
            _tabsContent.children().eq(arg1).addClass('active-finished-animation');

          }, settings_dzstabs.animation_time, arg)

          //------- currNr zone


          if (currNr > -1) {

            if (cthis.hasClass('is-toggle') && o.toggle_type === 'accordion') {
              _tabsMenu.children(":not(.active)").each(function () {
                var _t = $(this);
                _t.find('.tab-menu-content-con').eq(0).css('height', 0);
              });
            }

          }

          if (o.settings_scroll_to_start === 'on') {
            if (typeof margs != 'undefined' && margs.mouseevent && margs.mouseevent.type === 'click') {
              $(' body').animate({
                scrollTop: _tabsContent.children().eq(currNr).offset().top
              }, 300);
            }

          }


          calculate_dims();

          if (o.action_gotoItem) {
            margs.cthis = cthis;
            o.action_gotoItem(arg, margs);
          }
        }

        return this;
      })
    }
    window.dzstaa_init = function (selector, settings) {
      if (typeof (settings) != "undefined" && typeof (settings.init_each) != "undefined" && settings.init_each == true) {
        var element_count = 0;
        for (var e in settings) {
          element_count++;
        }
        if (element_count == 1) {
          settings = undefined;
        }

        $(selector).each(function () {
          var _t = $(this);
          _t.dzstabsandaccordions(settings)
        });
      } else {
        $(selector).dzstabsandaccordions(settings);
      }

    };
  })(jQuery);



  jQuery(document).ready(function($){


    dzstaa_init('.dzs-tabs.auto-init', {init_each: true});

    jQuery('a[id*="read-more-action"]').on('click', function () {
      var _t = $(this);
      var tid = _t.attr('id');
      if (tid != '' && typeof tid != "undefined") {
        jQuery('#' + tid + '-target').slideDown({duration: 300});
        setTimeout(function () {
          $(window).trigger('resize');
        }, 350);
      }
      return false;
    });
  })
}

 function dzstaa_jQueryInit() {

  return new Promise((resolve, reject) => {

    if (window.jQuery) {
      resolve('jQuery loaded');
    } else {
      var script = document.createElement('script');
      script.onload = function () {

        if (window.jQuery) {
          resolve('jQuery loaded');
        } else {
          reject('error loading');
        }
      };
      script.src = dzstaaConstants.URL_JQUERY;

      document.head.appendChild(script);
    }

    setTimeout(() => {
      reject('error loading');
    }, 15000);
  })
}

dzstaa_jQueryInit().then(() => {

  dzstaa_preInit()
}).catch((err) => {
  console.log(err);
})



