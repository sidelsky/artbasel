var dzstlt_arr_tooltips = [];

(function ($) {


  $.fn.dzstooltip = function (o) {

    var defaults = {
      settings_slideshowTime: '5' //in seconds
      , settings_autoHeight: 'on'
      , settings_skin: 'skin-default'
      , settings_close_other_tooltips: 'off'
      , settings_disable_include_in_tt_array: 'off'
      , settings_show_active_in_parent: 'off'
    }

    o = $.extend(defaults, o);
    this.each(function () {
      var cthis = $(this)
        , cchildren = cthis.children()
        , cclass = '';
      ;
      var aux
        , auxa
        , auxb
      ;
      var windowWidth = 0 // -- window width
        , windowHeight = 0 // -- window width
        , scrollTop = 0 // -- scrolltop
        , tooltipOffsetTop = 0 // -- offset top
        , spaceInContainerTop = 0 // -- offset top
        , spaceInContainerBottom = 0 // -- offset top
      ;

      var _tooltip = $(this).find('.dzstooltip').eq(0);
      var _tooltip_inner = null;
      var currNr = -1;

      var original_align = '';
      var original_arrow = '';



      if (cthis.hasClass("dzstooltip")) {
        _tooltip = cthis;
      }
      if (_tooltip && _tooltip.children('.dzstooltip--inner').length) {
        _tooltip_inner = _tooltip.children('.dzstooltip--inner').eq(0);
      }
      cclass = _tooltip.attr('class');

      var candebug = false;


      if (o.settings_disable_include_in_tt_array !== 'on') {
        dzstlt_arr_tooltips.push(_tooltip);
      }




      var inter_calculate_dims_light = 0;


      init();

      function init() {


        $('body').addClass('js');


        if (_tooltip.hasClass('inited')) {
          return false;
        }
        _tooltip.addClass('inited');


        if (_tooltip.hasClass('debug-it')) {
          candebug = true;
        }
        var reg_align = new RegExp('talign-(?:\\w*)', "g");
        var reg_arrow = new RegExp('arrow-(?:\\w*)', "g");


        auxa = reg_align.exec(cclass);
        aux = '';



        if (auxa && auxa[0]) {
          aux = auxa[0]
        } else {
          aux = 'talign-start';
        }



        _tooltip.data('original-talign', aux);
        original_align = aux;




        auxa = reg_arrow.exec(cclass);
        aux = '';



        if (auxa && auxa[0]) {
          aux = auxa[0]
        } else {
          aux = 'arrow-left';
        }



        _tooltip.data('original-arrow', aux);

        original_arrow = aux;


        // -- check structure

        if (_tooltip.children('.dzstooltip--inner').length === 0) {
          _tooltip.wrapInner('<span class="dzstooltip--inner"></span>');
        }

        if (cthis.find('.tooltip-indicator').length === 0) {













          $(cthis.children().eq(0).get(0).previousSibling).wrap('<span class="tooltip-indicator"></span>');


        }





        if (candebug) {
          console.info(original_arrow);
          console.info(original_align);
        }

        _tooltip.addClass('original-' + original_align);
        _tooltip.addClass('original-' + original_arrow);





        cthis.get(0).api_handle_resize = handleResize;

        if (cthis.hasClass('for-click')) {
          cthis.on('click', click_cthis);
        } else {

          cthis.on('mouseenter', handle_mouse);
          cthis.on('mouseleave', handle_mouse);
          cthis.on('click', handle_mouse);

        }

        $(window).on('resize', handleResize);

        handleResize();
        handle_scroll();


        setTimeout(handleResize, 2000);
      }

      function handle_scroll(e) {

        scrollTop = $(window).scrollTop();
        tooltipOffsetTop = cthis.offset().top;





      }

      function handle_mouse(e) {

        var _t = $(this);

        if (e.type == 'mouseenter') {
          // -- mouse over


          if (cthis.hasClass('for-hover')) {
            console.info(_tooltip, '_tooltip_inner -', _tooltip_inner);


            _tooltip.addClass('active');
          } else {
            calculate_dims();
          }

          if (_tooltip.find('.dzstooltip--inner').eq(0).find('.audioplayer').length) {
            var _c = _tooltip.find('.dzstooltip--inner').eq(0).find('.audioplayer');

            if (_c.get(0) && _c.get(0).api_play_media) {
              _c.get(0).api_play_media();
              _c.get(0).api_seek_to_perc(0);
            }
          }

          if (o.settings_show_active_in_parent === 'on') {
            console.info(cthis);
            cthis.addClass('tooltip-is-active');
          }
        }
        if (e.type == 'mouseleave') {

          if (cthis.hasClass('for-hover')) {
            _tooltip.removeClass('active');
          }


          if (_tooltip.find('.dzstooltip--inner').eq(0).find('.audioplayer').length) {
            var _c = _tooltip.find('.dzstooltip--inner').eq(0).find('.audioplayer');

            if (_c.get(0) && _c.get(0).api_pause_media) {
              _c.get(0).api_pause_media();
            }
          }


          if (o.settings_show_active_in_parent === 'on') {
            cthis.removeClass('tooltip-is-active');
          }

        }
        if (e.type == 'click') {

          if (cthis.hasClass('for-click')) {
            _tooltip.toggleClass('active');
          }


          var _c = _tooltip.find('.dzstooltip--inner').eq(0).find('.audioplayer');

          if (_c.length) {


            if (_c.hasClass('is-playing')) {

              if (_c.get(0) && _c.get(0).api_pause_media) {
                _c.get(0).api_pause_media();
              }
            } else {

              if (_c.get(0) && _c.get(0).api_play_media) {
                _c.get(0).api_play_media();
              }
            }
          }


          if (o.settings_show_active_in_parent === 'on') {
            cthis.removeClass('tooltip-is-active');
          }

        }
      }


      function calculate_dims(pargs) {


        var margs = {
          call_from: 'default'
        };


        if (pargs) {
          margs = $.extend(margs, pargs);
        }

        windowWidth = window.innerWidth;
        tooltipOffsetTop = cthis.offset().top;

        spaceInContainerTop = cthis.parent().offset().top - tooltipOffsetTop;




        var isfullwidth = false;





        _tooltip.removeClass('arrow-left arrow-right arrow-top arrow-bottom talign-start talign-center talign-end');
        _tooltip.addClass(original_arrow);
        _tooltip.addClass(original_align);


        _tooltip.css('max-width', '');

        if (inter_calculate_dims_light) {
          clearTimeout(inter_calculate_dims_light);
        }

        inter_calculate_dims_light = setTimeout(function () {
        }, 100);
        calculate_dims_light(margs);


        if (candebug) {
          console.info('finished calculate_dims');
        }
      }

      function calculate_dims_light(pargs) {


        var margs = {
          call_from: 'default'
        };


        if (pargs) {
          margs = $.extend(margs, pargs);
        }






        var maxwidth = 0;
        if (_tooltip.hasClass('arrow-top') || _tooltip.hasClass('arrow-bottom')) {


          if (_tooltip.hasClass('talign-start')) {

            if (_tooltip.offset().left + _tooltip.outerWidth() > windowWidth - 10) {


              var aux_mw = _tooltip.parent().offset().left + _tooltip.parent().outerWidth();
              if (aux_mw > maxwidth) {
                maxwidth = aux_mw;
              }

              _tooltip.removeClass('talign-center talign-start talign-end');
              _tooltip.addClass('talign-end');
            }
          }


          if (_tooltip.hasClass('talign-center')) {


            if (_tooltip.offset().left + (_tooltip.outerWidth()) > windowWidth - 10) {


              var aux_mw = _tooltip.parent().offset().left + _tooltip.parent().outerWidth();
              if (aux_mw > maxwidth) {
                maxwidth = aux_mw;
              }


              _tooltip.removeClass('talign-center talign-start talign-end');
              _tooltip.addClass('talign-end');
            }



            if (_tooltip.offset().left < 5) {


              var aux_mw = windowWidth - (_tooltip.parent().offset().left);
              if (aux_mw > maxwidth) {
                maxwidth = aux_mw;
              }


              _tooltip.removeClass('talign-center talign-start talign-end');
              _tooltip.addClass('talign-start');
            }
          }


          if (_tooltip.hasClass('talign-end')) {

            if (_tooltip.offset().left < _tooltip.outerWidth() / 2) {


              var aux_mw = windowWidth - (_tooltip.parent().offset().left);
              if (aux_mw > maxwidth) {
                maxwidth = aux_mw;
              }


              _tooltip.removeClass('talign-center talign-start talign-end');
              _tooltip.addClass('talign-start');
            }
          }

        } else {


          if (_tooltip.hasClass('arrow-left')) {

            if (_tooltip.offset().left + _tooltip.outerWidth() > windowWidth - 10) {


              var aux_mw = (_tooltip.parent().offset().left);
              if (aux_mw > maxwidth) {
                maxwidth = aux_mw;
              }

              _tooltip.removeClass('arrow-left arrow-right');

              console.warn("REMOVE ARROW-LEFT", windowWidth, (_tooltip.parent().offset().left + _tooltip.parent().outerWidth()));
              _tooltip.addClass('arrow-right');
              setTimeout(function () {

              }, 10);
            }
          }


          if (_tooltip.hasClass('arrow-right')) {

            if (_tooltip.offset().left < _tooltip.outerWidth() / 2) {


              var aux_mw = windowWidth - (_tooltip.parent().offset().left + _tooltip.parent().outerWidth());
              if (aux_mw > maxwidth) {
                maxwidth = aux_mw;
              }


              _tooltip.removeClass('arrow-right');
              _tooltip.addClass('arrow-left');
            }
          }

        }

        if (maxwidth) {
          _tooltip.css('max-width', maxwidth);
        }


        if (candebug) {
          console.info('finished calculate_dims_light');
        }

      }

      function handleResize(e, pargs) {
        windowWidth = window.innerWidth;
        windowHeight = $(window).height();
        calculate_dims();
      }

      function click_cthis(e) {

        var _c = cthis.find('.dzstooltip');
        if (_tooltip.hasClass('active')) {
          _tooltip.removeClass('active');


        } else {


          if (o.settings_close_other_tooltips == 'on') {
            for (i3 = 0; i3 < dzstlt_arr_tooltips.length; i3++) {
              if (dzstlt_arr_tooltips[i3]) {
                dzstlt_arr_tooltips[i3].removeClass('active');
              }
            }
          }

          _c.addClass('active');


          if (o.settings_show_active_in_parent === 'on') {
            cthis.addClass('tooltip-is-active');
          }


        }




      }

      return this;
    })
  }


  window.dzstt_init = function (arg, optargs) {
    $(arg).dzstooltip(optargs);
  }
})(jQuery);


if (typeof jQuery != 'undefined') {
  jQuery(document).ready(function ($) {
    var defsettings = {};

    if (window.dzstlt_init_settings) {
      defsettings = window.dzstlt_init_settings;
    }
    dzstt_init('.dzstooltip-con.js', defsettings);
  })
} else {
  alert('dzstooltip.js - this plugin is based on jQuery -> please include jQuery')
}