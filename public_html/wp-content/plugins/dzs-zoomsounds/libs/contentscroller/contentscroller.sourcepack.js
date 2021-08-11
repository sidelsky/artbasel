




/*
 * Author: Digital Zoom Studio
 * Website: http://digitalzoomstudio.net/
 * Portfolio: http://codecanyon.net/user/ZoomIt/portfolio?ref=ZoomIt
 * This is not free software.
 * Advanced Scroller v1.44
 */

"use strict";
import {view_calculatePosXandY} from "./js_csc/_view_movementFuncs";
import {view_gotoPage} from "./js_csc/_view_functions";
import {initialSetup} from "./js_csc/_initialSetup";

window.dzscsc_self_options = {};

var helpersDzsCommon = require('./js_common/_helpers');
var helpersCsc = require('./js_csc/_csc_helpers');
var ConstantsCsc = require('./js_csc/_constants').constants;

class DzsContentScroller {


  constructor(argThis, argOptions, $) {

    this.argThis = argThis;
    this.argOptions = argOptions;
    this.$ = $;


    this.slideshowTime = 0;
    this.margin = 0;
    this.design_itemwidth = 0;

    this.cthis = null;

    this.totalComponentWidth = 0;
    this.totalComponentHeight = 0;
    this.totalItemContainerClipWidth = 0;
    this.totalItemContainerClipHeight = 0;
    this.totalItemContainerWidth = 0;
    this.totalItemContainerHeight = 0;

    this.pag_total_pages = 0;
    this.currPage = -1;
    this.currPageX = 0;
    this.currPageY = -1;
    this.numberOfItems;

    this.responsive_per_row = 0;

    this.$thumbsCon;
    this.$thumbsClip;
    this.$bulletsCon;
    this.$outerThumbs;
    this.$currPage = null;

    this.itemsOnCurrPage = []; // -- items on the current page ( will return only the first for mode onlyone )

    this.hasSpaceToScroll = false;

    this.componentId = null;

    this.init();
  }

  init() {
    var selfClass = this;

    var $ = selfClass.$;
    var o = selfClass.argOptions;

    var cthis = $(selfClass.argThis)
    ;
    selfClass.cthis = cthis;

    var currNr = -1;
    var i = 0
      , startIndex = 0
    ;
    var ww
      , tw_last // total width of the container and h
      , page_w_calculated = 0 // -- one item calculated width
    ;
    var
      items_per_page = 0
    ;
    var
      _items


      , _thumbsConItems
      , _arrowsCon
    ;



    var
      pag_total_thumbsizes = 0
      , pag_total_thumbnr = 0 // -- total number of thumbnails
      , pag_last_total_pages = 0 //  -- the last total pages
      , pag_excess_thumbnr = 0 // -- the excess thumbs which go

    ;
    var
       tempPage = 0
      , lastArg = 0 // -- the last current transitioning item
    ;

    var slideshowInter
      , slideshowCount = 0
      , inter_wait_loaded = null
    ;


    var loadedArray = []
      , lazyLoadingArray = []
      , itemsToBeArray = []
    ;


    var action_call_on_gotoItem = null;

    var inter_calculate_hard = 0
      , inter_check_if_loaded = 0
    ;
    var is_over = false;
    var misc_has_height_same_as_width_elements = false;


    var duration_viy = 10
      , target_viy = 0
      , target_vix = 0
      , begin_vix = 0
      , finish_vix = 0
      , change_vix = 0
      , cthis_offset_x = 0
      , _t_offset_x_parent = 0
      , _t_offset_x = 0
      , _t_offset_rel_x = 0
    ;


    var assets = require('./js_common/_assets');

    var viewIndexX = 0;





    init();


    function init() {
      if (selfClass.cthis.hasClass('inited')) {
        return;
      }

      selfClass.gotoPrevPage = gotoPrevPage;
      selfClass.gotoNextPage = gotoNextPage;


      helpersCsc.init_sanitizeInitialOptions(selfClass, o);



      initialSetup(selfClass, o);

      if (o.design_arrowsize === 'default') {
        o.design_arrowsize = 40;
      }
      if (o.design_bulletspos === 'default') {
        o.design_bulletspos = 'bottom';
      }

      if (o.design_disableArrows === 'default') {
        o.design_disableArrows = 'off';
      }
      if (o.settings_onlyone === 'on') {
        selfClass.cthis.addClass('is-onlyone');
      }


      setup_structure();

      selfClass.numberOfItems = _items.children().length;

      if (selfClass.cthis.find('.js-height-same-as-width')) {
        misc_has_height_same_as_width_elements = true;
      }





      reinit();





      selfClass.cthis.addClass('inited');



      selfClass.cthis.get(0).api_set_action_call_on_gotoItem = function (arg) {

        action_call_on_gotoItem = arg;
      };





      if (o.nav_type === 'slide') {

        if (helpersCsc.is_android() === false) {

          handle_frame();


          selfClass.cthis.on('mousemove', handle_mouse);
        }
      }

      if (selfClass.$outerThumbs) {
        selfClass.$outerThumbs.on('click', '.csc-item', function () {
          var _t = $(this);

          var ind = _t.parent().children().index(_t);

          _t.parent().children().removeClass('active');
          _t.addClass('active');

          gotoPage(ind, {call_from: 'click cscitem'});
        });

        selfClass.$outerThumbs.find('.csc-item').eq(0).addClass('active');
      }



      if (selfClass.cthis.find('.csc-item.needs-loading:not(.loaded)').length > 0 && o.settings_force_immediate_load === 'off') {

        checkWhenLoaded();
      } else {
        if (o.settings_force_immediate_load === 'on') {
          checkWhenLoaded();
        }
        init_setup();
      }

    }


    function setup_structure() {


      if (o.design_bulletspos === 'top') {

      }
      selfClass.cthis.append('<div class="thumbsClip"><div class="thumbsCon"></div></div>');
      if (o.design_bulletspos === 'bottom') {
        selfClass.cthis.append('<div class="bulletsCon"></div>');
      }


      if (o.design_disableArrows !== 'on') {

        if (selfClass.cthis.children('.arrowsCon').length === 0) {

          selfClass.cthis.append('<div class="arrowsCon"></div>');
        }
      }


      if (o.settings_autoHeight === 'off') {
        selfClass.cthis.addClass('autoheight-off');
      }

      if (o.outer_thumbs) {

        selfClass.$outerThumbs = $(o.outer_thumbs);

        if (selfClass.$outerThumbs.length) {

        } else {
          selfClass.$outerThumbs = null;
        }
      }

      selfClass.cthis.addClass('direction-' + o.settings_direction);

      _items = selfClass.cthis.children('.items').eq(0);
      selfClass.$bulletsCon = selfClass.cthis.children('.bulletsCon').eq(0);
      selfClass.$thumbsCon = selfClass.cthis.find('.thumbsCon').eq(0);
      selfClass.$thumbsClip = selfClass.cthis.find('.thumbsClip').eq(0);
      _arrowsCon = selfClass.cthis.find('.arrowsCon').eq(0);


      if (_arrowsCon.children('.arrow-left').length === 0) {

        _arrowsCon.append('<div class="cs-arrow cs-arrow-left">' + assets.svg_arrow_left + '</div>');
        _arrowsCon.append('<div class="cs-arrow cs-arrow-right">' + assets.svg_arrow_right + '</div>');
      }
    }


    function handle_frame() {

      // -start handleframe


      if (isNaN(target_viy)) {
        target_viy = 0;
      }

      if (duration_viy === 0) {
        window.requestAnimationFrame(handle_frame);
        return false;
      }



      if (o.settings_direction === 'horizontal' && selfClass.hasSpaceToScroll) {
        begin_vix = target_vix;
        change_vix = finish_vix - begin_vix;



        target_vix = Number(Math.easeIn(1, begin_vix, change_vix, duration_viy).toFixed(4));

        selfClass.$thumbsCon.css({
          'transform': 'translate3d(' + target_vix + 'px,0,0)'
        })


      }


      if (o.settings_direction === 'vertical' && selfClass.hasSpaceToScroll) {
        begin_vix = target_vix;
        change_vix = finish_vix - begin_vix;



        target_vix = Number(Math.easeIn(1, begin_vix, change_vix, duration_viy).toFixed(4));

        selfClass.$thumbsCon.css({
          'transform': 'translate3d(0,' + target_vix + 'px,0)'
        })


      }



      window.requestAnimationFrame(handle_frame);
    }


    function reinit() {


      var ind = 0;

      itemsToBeArray = _items.children('.csc-item');


      _items.children('.csc-item').each(function () {
        var _t = $(this);
        var ind2 = _t.parent().children().index(_t);


        // -- each csc item
        var _c2 = _t.find('.divimage,img');
        if (_c2.length && _c2.eq(0).attr('data-src')) {
          _t.addClass('needs-loading');

          if (_c2.attr('data-src')) {
            _c2.css('background-image', 'url(' + _c2.attr('data-src') + ')')
          }
        } else {

        }

        _t.wrapInner('<div class="csc-item--inner"></div>');



        var itemWidth = selfClass.design_itemwidth;


        if (itemWidth !== 'auto' && itemWidth !== '' && selfClass.cthis.hasClass('is-onlyoneitem') === false) {
          _t.css({
            'width': itemWidth
          });
        }
        selfClass.$thumbsCon.append(_t);

        if (o.settings_lazyLoading === 'on') {
          if (_t.find('.imagediv').length === 0 && _t.find('img.imageimg').length === 0) {
            lazyLoadingArray[ind] = 'tobeloaded';
          } else {
            lazyLoadingArray[ind] = 'loaded';
          }
        }

        loadedArray[ind] = 1;
        ind++;


      });

      if (o.settings_lazyLoading === 'on') {


        prepareForLoad(startIndex);
        if (selfClass.$thumbsCon.children().eq(startIndex).hasClass('type-inline') === false) {
          selfClass.$thumbsCon.children().eq(startIndex).addClass('needs-loading');
        }
      } else {
        for (i = 0; i < lazyLoadingArray.length; i++) {
          loadItem(lazyLoadingArray[i]);
        }
      }

      _thumbsConItems = selfClass.$thumbsCon.children();
    }

    function setup_sizes() {


      // -- autoheight OFF
      if (o.settings_autoHeight === 'off' && o.settings_onlyone === 'on') {
        selfClass.cthis.find('.csc-item').each(function () {
          var _t = $(this);
          // -- assign the thumbs clip height to each item
          _t.css('height', selfClass.$thumbsClip.outerHeight() + 'px');
        })

      }
    }

    /**
     * setup load handlers
     */
    function checkWhenLoaded() {


      selfClass.cthis.find('.csc-item').each(function () {
        var _t = $(this);
        var ind = _t.parent().children().index(_t);





        if (_t.html() === '') {
          loadedArray[ind] = 1;
          return;
        }

        if (_t.find('.divimage').length > 0) {


          var _cach = _t.find('.divimage').eq(0);
          var toload = _cach.get(0);


          var aux = '';
          if (_cach.attr('data-src')) {

            aux = _cach.attr('data-src');
          } else {

            aux = _t.find('.divimage').eq(0).css('background-image');
          }


          var img = new Image();
          aux = helpersDzsCommon.sanitize_background_url(aux);


          img.onload = function (e) {

            var args = {
              dzscsc_index: ind
              , target: e.target.realparent
              , call_from: 'img onload 561'
            };

            loadedImage(args);
          };


          toload.dzscsc_index = ind;
          toload.realimg = img;
          img.realparent = toload;


          if (aux) {
            loadedArray[ind] = 0;
            img.src = aux;
          } else {

            loadedArray[ind] = 1;
          }


        } else {
          toload = _t.find('img.imageimg').eq(0).get(0);
        }

        let delay = 500;
        if (typeof (toload) === "undefined") {

          var args = {


            call_from: 'toload undefined'
          };

          if (_t.find('.vplayer').length > 0) {
            toload = _t.find('.vplayer').eq(0);
          } else {
            args._con = _t;
            delay = 0;
          }

          loadedArray[ind] = 1;
          args.dzscsc_index = ind;
          args.target = toload;

          setTimeout(loadedImage, delay, args);
        } else {


          loadedArray[ind] = 0;

          if (toload && $(toload).attr('data-src')) {

            var args = {
              dzscsc_index: ind
              , target: toload
              , call_from: 'hmm'
            };

            toload.dzscsc_index = ind;
          } else {

            var args = {


              call_from: 'toload undefined'
            };
            args.dzscsc_index = ind;
            args.target = toload;
            loadedArray[ind] = 1;
            setTimeout(loadedImage, delay, args);
          }





          if (toload.complete === true && toload.naturalWidth !== 0) {

            setTimeout(loadedImage, 500, args);
          } else {
            $(toload).on('load', loadedImage);
          }
        }
      });
    }

    function loadedImage(pargs) {
      var ind = 0;
      var _t = $(this);
      var _cscItem = null;

      var margs = {
        dzscsc_index: null
        , target: null
        , _cscItem: null
        , call_from: 'default'
      };

      if (pargs) {
        margs = $.extend(margs, pargs);
        if (pargs.currentTarget) {
          margs.target = pargs.currentTarget;
          if (margs.target && margs.target.dzscsc_index) {
            margs.dzscsc_index = margs.target.dzscsc_index;
          }
        }
      }




      if (margs.dzscsc_index) {
        ind = margs.dzscsc_index;
      }
      if (margs.target) {
        _t = $(margs.target);
      }
      if (margs._cscItem) {
        _cscItem = $(margs._cscItem);
      }







      if (_t) {
        _t.addClass('image-is-loaded');

      }



      if (_t.hasClass('divimage')) {

        if (_t.get(0).style.height === '' || _t.get(0).style.height === 'auto' || _t.hasClass('fullheight') === false) {
          if (_t.hasClass('full-square')) {

            _t.css('height', '0');
          } else {
            if (o.settings_autoHeight === 'on') {
              // -- do we really need this ?
              _t.css({
                'padding-top': Number(Number(_t.get(0).realimg.naturalHeight) / Number(_t.get(0).realimg.naturalWidth) * 100).toFixed(2) + '%'
              })
            }
          }
        }
        _t.data('natural_w', _t.get(0).realimg.naturalWidth);
        _t.data('natural_h', _t.get(0).realimg.naturalHeight);
        _t.data('ratio_wh', (_t.get(0).realimg.naturalWidth / _t.get(0).realimg.naturalHeight));
      }
      loadedArray[ind] = 1;






      _cscItem = helpersCsc.determineConInLoadedImage(_cscItem, _t);





      if (_cscItem) {
        var _img = _t.get(0);



        if (_t.get(0).realimg) {
          _img = _t.get(0).realimg;
        }

        var isImage = !!(_img && _img.naturalWidth && _img.naturalHeight);

        if (isImage) {
          _cscItem.data('naturalWidth', _img.naturalWidth);
        }
        if (isImage) {
          _cscItem.data('naturalHeight', _img.naturalHeight);
        }



        if (!(isImage)) {
          helpersCsc.treatCscItemVplayer(_cscItem, _t);
        }

        _cscItem.addClass('image-loaded');

        $(_cscItem).data('cscItemParameters', {
          'realImg': _img,
          'isImage': isImage
        })
      }


      var sw = false;

      var tempNr = currNr;

      if (tempNr === -1) {
        tempNr = 0;
      }

      if (loadedArray[tempNr] !== 1) {
        sw = true;
      }

      // -- if something is not loaded we wait some more


      if (sw === false) {
        var args = {
          from_check_loaded: true
        };
        init_setup(args);
      }
    }

    function init_setup(pargs) {
      // -- this is where we will setup


      var margs = {
        from_check_loaded: false
      };

      if (pargs) {
        margs = $.extend(margs, pargs);
      }


      if (o.settings_force_immediate_load === 'on' && margs.from_check_loaded) {

        handleResize();
      }


      if (selfClass.cthis.hasClass('init-setuped')) {
        return;
      }
      selfClass.cthis.addClass('init-setuped');



      setup_sizes();

      pag_total_thumbnr = selfClass.$thumbsCon.children().length;
      selfClass.$thumbsCon.children().each(function () {
        var _t = $(this);
        var ind = _t.parent().children().index(_t);

        if (ind === 0) {

        }
        if (ind === selfClass.$thumbsCon.children().length - 1) {

        }


        if (o.design_forceitemwidth > 0) {

        }





        // -- no margin for PERCENTAGE allowed
        pag_total_thumbsizes += _t.outerWidth(true);
      });
      selfClass.totalComponentWidth = selfClass.cthis.outerWidth(false);
      selfClass.totalComponentHeight = o.design_itemheight;







      _arrowsCon.children().on('click', click_arrow);

      selfClass.cthis.get(0).api_gotoNextPage = gotoNextPage;
      selfClass.cthis.get(0).api_gotoPrevPage = gotoPrevPage;
      selfClass.cthis.get(0).api_destroy_listeners = destroy_listeners;


      helpersCsc.conditionalAddSwiping(o);


      $(window).on('resize', handleResize);

      selfClass.cthis.get(0).api_force_resize = handleResize;
      selfClass.cthis.get(0).api_handleResize = handleResize;


      calculate_dims({'donotcallgotopage': 'on', 'called_from': 'init_setup()'});

      setTimeout(function () {

        calculate_dims({'donotcallgotopage': 'on', 'called_from': 'init_setup() timeout'});
      }, 1000);

      if (selfClass.slideshowTime > 0) {
        slideshowInter = setInterval(handleHeartBeat, 1000);
      }

      selfClass.cthis.unbind('mouseenter');
      selfClass.cthis.on('mouseenter', handle_mouseenter);
      selfClass.cthis.unbind('mouseleave');
      selfClass.cthis.on('mouseleave', handle_mouseleave);


      selfClass.cthis.on('click', '.bulletsCon > .bullet,.cs-arrow', handle_mouse);

      var tempPage = 0;




      var isNeedsLoading = false;
      if (o.settings_onlyone === 'on') {
        if (_thumbsConItems.eq(tempPage).children().length === 1) {
          if (_thumbsConItems.eq(tempPage).children().get(0).nodeName === 'IMG') {
            isNeedsLoading = true;
          }
        }
      }

      if (isNeedsLoading) {
        inter_check_if_loaded = setInterval(checkIfImageLoaded, 100);
      } else {
        init_allLoaded();
      }
      // -- end init_setup()
    }

    function checkIfImageLoaded() {

      if (_thumbsConItems.eq(0).children().get(0).nodeName === 'IMG') {
        if (_thumbsConItems.eq(0).children().get(0).naturalHeight) {
          init_allLoaded();
        }
      }
    }


    function init_allLoaded() {

      // -- handleLoaded aka
      clearInterval(inter_check_if_loaded);

      selfClass.cthis.addClass('inited-allloaded');

      selfClass.cthis.children('.preloader, .preloader-semicircles').fadeOut('slow');





      gotoPage(tempPage, {call_from: 'init_allloaded'});
      selfClass.cthis.get(0).api_goto_page = gotoPage;

    }


    function handle_mouse(e) {
      var _t = $(this);

      if (e.type === 'click') {
        if (_t.hasClass('cs-arrow')) {
          if (_t.hasClass('cs-arrow-left')) {
            gotoPrevPage();
          }
          if (_t.hasClass('cs-arrow-right')) {
            gotoNextPage();
          }

        }
        if (_t.hasClass('bullet')) {

          var ind = _t.parent().children().index(_t);
          if (selfClass.cthis.find(_t).length < 1) {
            return;
          }

          gotoPage(ind, {called_from: 'bullet'});
        }
      }
      if (e.type === 'mousemove') {

        var mx = e.clientX - selfClass.$thumbsClip.offset().left;
        var aux_rat = mx / selfClass.totalComponentWidth;


        if (o.settings_direction === 'vertical') {
          mx = e.clientY - selfClass.$thumbsClip.offset().top;
          aux_rat = mx / selfClass.totalComponentHeight;
          // -- normalize 25px
          aux_rat += ((aux_rat - 0.5) * 50) / selfClass.totalComponentHeight;
        }
        viewIndexX = aux_rat * (selfClass.totalItemContainerWidth - selfClass.totalItemContainerClipWidth);


        if (o.settings_direction === 'vertical') {
          viewIndexX = aux_rat * (selfClass.totalItemContainerHeight - selfClass.totalItemContainerClipHeight);

          // -- normalize
          if (viewIndexX < 0) {
            viewIndexX = 0;
          }
          if (viewIndexX > Math.abs(selfClass.totalItemContainerHeight - selfClass.totalItemContainerClipHeight)) {
            viewIndexX = Math.abs(selfClass.totalItemContainerHeight - selfClass.totalItemContainerClipHeight);
          }
        }

        viewIndexX = -viewIndexX;
        finish_vix = viewIndexX;


      }
      if (e.type === 'mouseover') {
        if (_t.hasClass('bullet')) {

          var ind = _t.parent().children().index(_t);
          if (selfClass.cthis.find(_t).length < 1) {
            return;
          }

          gotoPage(ind, {called_from: 'mouseover'});
        }
      }
    }


    function destroy_listeners() {

      selfClass.cthis.unbind('mouseenter');
      selfClass.cthis.unbind('mouseleave');
      _arrowsCon.children().unbind('click', click_arrow);

      $(document).undelegate('.bullet', 'click', click_bullet);
      $(window).off('resize', handleResize);
    }

    function handle_mouseenter() {
      is_over = true;
    }

    function handle_mouseleave() {
      is_over = false;
    }

    function calculateDimsForCurrPage(pargs) {


      var margs = {
        donotcallgotopage: 'off'
        , calculate_auto_height: true
        , calculate_auto_height_proportional: true
        , calculate_auto_height_default_h: 0
        , calculate_thumbsClip_height: true
      };

      if (pargs) {
        margs = $.extend(margs, pargs);
      }



      // -- autoheight calculationseb

      if (o.settings_autoHeight === 'on' && selfClass.$currPage) {
        // -- why horizontal ?

        var currPageHeight = 0;
        if (o.settings_onlyone === 'on') {
          // -- ONLYONE
          currPageHeight = selfClass.$currPage.find('.csc-item--inner').eq(0).outerHeight();


          if (selfClass.$currPage.children('.vplayer').length > 0) {
            currPageHeight = selfClass.$currPage.width() * 0.562;
          }
        } else {
          // -- lets see if each item is within page


          selfClass.$thumbsCon.children().each(function () {


            var _t = $(this);
            var isWithinCurrPage = !!(_t_offset_rel_x + -(_t_offset_x_parent) >= -selfClass.currPageX - (selfClass.margin / 2) && _t_offset_rel_x + -(_t_offset_x_parent) < -selfClass.currPageX + page_w_calculated);


            _t_offset_x = _t.offset().left;
            _t_offset_rel_x = _t_offset_x - cthis_offset_x;
            _t_offset_x_parent = parseInt(_t.parent().css('left'), 10);

            if (isWithinCurrPage) {
              if (_t.outerHeight() > currPageHeight) {
                currPageHeight = _t.outerHeight();
              }
            }




          })
        }
        // -- finished calculating some vars


        if (o.settings_direction === 'horizontal') {

          if (o.settings_autoHeight_proportional === 'on') {
            if (selfClass.$currPage.find('.divimage').eq(0).data('natural_w')) {

              var nw = Number(selfClass.$currPage.find('.divimage').eq(0).data('natural_w'));
              var nh = Number(selfClass.$currPage.find('.divimage').eq(0).data('natural_h'));

              currPageHeight = selfClass.totalComponentWidth * nh / nw;
              if (currPageHeight > o.settings_autoHeight_proportional_max_height) {
                currPageHeight = o.settings_autoHeight_proportional_max_height;
              }
              currPageHeight += 'px';
            }
          }
        }


        // -- if we have force width
        if (margs.force_width && margs.force_width > 0) {
          selfClass.$currPage.find('img').eq(0).width(margs.force_width);
          selfClass.$currPage.find('img').eq(0).addClass('width-already-set');

          selfClass.$thumbsClip.width(margs.force_width);
          selfClass.$thumbsClip.addClass('width-already-set');
        }

        if (margs.force_height && margs.force_height > 0) {

          currPageHeight = margs.force_height;
        }

        // console.info('margs.force_height - ',margs.force_height);


        // console.info('currPageHeight calculating height -5 ', currPageHeight);

        selfClass.$thumbsClip.css({
          //'height' : currPageHeight
        });

        // console.log..('currPageHeight - ', currPageHeight);

        if (currPageHeight === 0) {
          setTimeout(calculateDimsForCurrPage, 300)
        }
        selfClass.$thumbsCon.css({
          'height': currPageHeight
        });
        selfClass.cthis.css({
          'height': 'auto'
        });
      }
    }

    function calculate_dims(pargs) {


      var margs = {
        donotcallgotopage: 'off',
        called_from: 'default'
        , calculate_auto_height: true
        , calculate_auto_height_proportional: true
        , calculate_auto_height_default_h: 0
        , calculate_thumbsClip_height: true
      };

      if (pargs) {
        margs = $.extend(margs, pargs);
      }


      ww = window.innerWidth;
      // ww = document.body.clientWidth;
      selfClass.totalComponentWidth = selfClass.cthis.width();
      selfClass.totalComponentHeight = selfClass.cthis.height();

      selfClass.totalItemContainerClipWidth = selfClass.totalComponentWidth;
      selfClass.totalItemContainerClipHeight = selfClass.totalComponentHeight;


      selfClass.totalItemContainerWidth = selfClass.$thumbsCon.outerWidth(); // --- swiper total width
      selfClass.totalItemContainerClipWidth = selfClass.$thumbsClip.width() // --- swiper image width ( visible )



      if (selfClass.totalComponentWidth < 720) {
        selfClass.cthis.addClass('under-720');

        if (o.responsive_720_design_itemwidth) {
          selfClass.design_itemwidth = o.responsive_720_design_itemwidth;
        }
      } else {

        selfClass.cthis.removeClass('under-720');


        if (o.responsive_720_design_itemwidth) {
          selfClass.design_itemwidth = o.design_itemwidth;
        }

      }




      selfClass.totalComponentHeight = selfClass.cthis.outerHeight(false);


      page_w_calculated = selfClass.$thumbsClip.outerWidth();
      cthis_offset_x = selfClass.cthis.offset().left;





      selfClass.responsive_per_row = o.per_row;
      if (ww < 600) {
        if (o.per_row === 4 || o.per_row === 5 || o.per_row === 6) {
          selfClass.responsive_per_row = 2;
        }
      }


      if (o.settings_onlyone === 'off') {
        helpersCsc.calculateDimensionsForEachItem(selfClass, selfClass.$thumbsCon, selfClass.responsive_per_row, o);
      }


      calculateDimsForCurrPage(margs);
      selfClass.totalItemContainerWidth = selfClass.$thumbsCon.width(); // -- total width con width
      selfClass.totalItemContainerHeight = selfClass.$thumbsCon.height();


      setTimeout(function () {
        selfClass.totalItemContainerWidth = selfClass.$thumbsCon.width();
      }, 1000);


      if (selfClass.currPage === -1) {
        selfClass.currPage = 0;
      }





      helpersCsc.determineIfHasSpaceToScroll(selfClass, selfClass.$thumbsCon, selfClass.$thumbsClip, o);
      helpersCsc.determineTotalNumberOfPages(selfClass, selfClass.numberOfItems, o);





      if (margs && margs.donotcallgotopage === 'on') {

      } else {
      }


      if (pag_last_total_pages !== selfClass.pag_total_pages) {

        selfClass.$bulletsCon.html('');
        for (i = 0; i < selfClass.pag_total_pages; i++) {
          selfClass.$bulletsCon.append('<span class="bullet"></span>')
        }
      }



      pag_last_total_pages = selfClass.pag_total_pages;



      if (margs.calculate_thumbsClip_height) {

        // -- auto-height means that height will adjust via content and not via javascript


        if (selfClass.cthis.hasClass('auto-height') === false) {
          if (o.settings_direction === 'vertical') {



          }




          if (o.settings_onlyone === 'on') {


          }
        }
      }

      if (selfClass.$outerThumbs && o.outer_thumbs_keep_same_height === 'on') {
        selfClass.$outerThumbs.css('height', selfClass.totalComponentHeight + 'px');
      }

    }


    function calculate_dims_hard() {



      setup_sizes();

      if (tw_last && tw_last !== selfClass.totalComponentWidth) {
        gotoPage(selfClass.currPage, {'called_from': 'tw_last!=tw' + ' tw - ' + selfClass.totalComponentWidth + 'th - ' + selfClass.totalComponentHeight});
      }
      tw_last = selfClass.totalComponentWidth;

    }


    function handleHeartBeat() {
      slideshowCount++;
      if (o.settings_slideshowDontChangeOnHover === 'on') {
        if (is_over === true) {
          return;
        }
      }

      if (slideshowCount >= selfClass.slideshowTime) {
        gotoNextPage();
        slideshowCount = 0;
      }
    }


    function handleResize(e, pargs) {


      var margs = {

        calculate_auto_height: true
        , calculate_auto_height_default_h: 0
      };

      if (pargs) {
        margs = $.extend(margs, pargs);
      }


      if (selfClass.currPage > -1) {

        margs.called_from = 'handleResize()';
        calculate_dims(margs);
      }


      clearTimeout(inter_calculate_hard);
      inter_calculate_hard = setTimeout(calculate_dims_hard, 100);

    }

    function click_arrow() {
      var _t = $(this);
      if (_t.hasClass('arrow-left')) {
        gotoPrevPage();
      }
      if (_t.hasClass('arrow-right')) {
        gotoNextPage();
      }
    }

    function click_bullet() {
      var _t = $(this);
      var ind = _t.parent().children().index(_t);
      if (selfClass.cthis.find(_t).length < 1) {
        return;
      }

      gotoPage(ind);
    }

    function prepareForLoad(arg) {
      var tempNextNr = arg + 1;
      var tempPrevNr = arg - 1;

      if (tempPrevNr <= -1) {
        tempPrevNr = selfClass.numberOfItems - 1;
      }
      if (tempNextNr >= selfClass.numberOfItems) {
        tempNextNr = 0;
      }




      loadItem(tempPrevNr);
      loadItem(arg);
      loadItem(tempNextNr);
    }

    function loadItem(arg) {



      if (lazyLoadingArray[arg] === 'tobeloaded') {
        var _t = selfClass.$thumbsCon.children().eq(arg);



        if (_t.attr('data-source')) {

          _t.append('<img class="fullwidth" src="' + _t.attr('data-source') + '"/>');
        }
        if (_t.attr('data-divimage_source')) {

          _t.append('<div class="divimage" style="background-image: url(' + _t.attr('data-divimage_source') + ');" ></div>');
        }

        lazyLoadingArray[arg] = 'loading';
      }


      checkWhenLoaded();

    }

    function gotoNextPage() {
      tempPage = selfClass.currPage + 1;
      if (tempPage > selfClass.pag_total_pages - 1) {
        tempPage = 0;
      }
      gotoPage(tempPage);
    }

    function gotoPrevPage() {
      tempPage = selfClass.currPage - 1;
      if (tempPage < 0) {
        tempPage = selfClass.pag_total_pages - 1;
      }
      gotoPage(tempPage);
    }

    function gotoPage(targetIndex, pargs) {

      var margs = {

        'called_from': 'default',
        'called_from_resize': false

      };

      if (pargs) {
        margs = $.extend(margs, pargs);
      }





      if (targetIndex > selfClass.pag_total_pages - 1) {
        targetIndex = selfClass.pag_total_pages - 1;
      }


      lastArg = targetIndex;

      if (o.settings_mode === 'onlyoneitem' && o.settings_lazyLoading === 'on') {
        prepareForLoad(targetIndex);
      }


      if (o.settings_transition_only_when_loaded === 'on' && selfClass.$thumbsCon.children().eq(targetIndex).hasClass('needs-loading') && selfClass.$thumbsCon.children().eq(targetIndex).hasClass('loaded') === false) {



        inter_wait_loaded = setTimeout(function () {
          gotoPage(targetIndex, margs)
        }, 500);

        return false;
      } else {

        clearTimeout(inter_wait_loaded);
      }



      view_calculatePosXandY(selfClass,targetIndex, items_per_page, pag_excess_thumbnr);






      var animation_time = 500;


      view_gotoPage(selfClass,targetIndex);





      if (margs.called_from_resize === false && action_call_on_gotoItem) {
        selfClass.cthis.get(0).api_do_transition = do_transition;
        action_call_on_gotoItem(selfClass.cthis, selfClass.$thumbsCon.children().eq(targetIndex), {arg: targetIndex});

      }


      do_transition();


      calculate_dims({'donotcallgotopage': 'on', 'called_from': 'gotoPage'});



    }


    function do_transition(pargs) {



      var margs = {

          'force_width': 0
          , 'force_height': 0
          , 'arg': 0
          , 'called_from_resize': false
        }
      ;

      if (pargs) {
        margs = $.extend(margs, pargs);
      }

      var arg = lastArg;

      if (margs.arg) {
        arg = margs.arg;
      }


      if (o.settings_onlyone === 'on') {

        //------- only one item
        var _c = selfClass.$thumbsCon.children().eq(arg);

        selfClass.$thumbsCon.children().removeClass("currItem");




        if (o.settings_transition === 'fade' || o.settings_transition === 'slide' || o.settings_transition === 'wipeoutandfade' || o.settings_transition === 'flyout') {

          _c.addClass('currItem');



          if (_c.children().eq(0).hasClass('is-video')) {



          }
        }

        if (margs.called_from_resize !== true) {

          _c.addClass('transitioning-in');
        }



        if (o.settings_transition === 'slide') {
          if (!selfClass.cthis.hasClass('no-need-for-nav')) {
            if (o.settings_direction === 'vertical') {
              selfClass.$thumbsCon.css({
                'top': selfClass.currPageY
              });
            } else {

              if (selfClass.currPageX > 0) {
                selfClass.currPageX = 0;
              }
              selfClass.$thumbsCon.css({
                'left': selfClass.currPageX
              });
            }
          }

        }


        if (o.settings_transition === 'fade') {

        }


      } else {

        if (!selfClass.cthis.hasClass('no-need-for-nav')) {
          if (o.settings_direction === 'vertical') {
            selfClass.$thumbsCon.css({
              'top': selfClass.currPageY
            });
          } else {
            if (selfClass.currPageX > 0) {
              selfClass.currPageX = 0;
            }
            selfClass.$thumbsCon.css({
              'left': selfClass.currPageX
            });
          }
        }


      }


      selfClass.currPage = arg;
      slideshowCount = 0;


      if (selfClass.$outerThumbs) {
        selfClass.$outerThumbs.find('.csc-item').removeClass('active');
        selfClass.$outerThumbs.find('.csc-item').eq(selfClass.currPage).addClass('active');
      }


      if (o.settings_transition === 'fade') {

        setTimeout(function () {
          do_transition_end();
        }, 300)
      }



    }

    function do_transition_end() {



      selfClass.$thumbsCon.children().removeClass('transitioning-in transitioning-out')


    }

  }
}


(function ($) {


  Math.easeIn = function (t, b, c, d) {

    return -c * (t /= d) * (t - 2) + b;

  };


  $.fn.contentscroller = function (argOptions) {

    var finalOptions = {};
    var cscSettings = require('./configs/_settings');
    var defaults = Object.assign({}, cscSettings.default_opts);





    if (typeof argOptions === 'undefined') {
      if (typeof $(this).attr('data-options') !== 'undefined') {
        var aux = $(this).attr('data-options');
        try {
          var aux_json = JSON.parse(aux);
          argOptions = $.extend({}, aux_json);

        } catch (err) {
          console.error(err);

          var aux = $(this).attr('data-options');
          aux = 'window.dzscsc_self_options  = ' + aux;
          try {
            eval(aux);
          } catch (err) {
            console.warn('eval error', err);
          }

          argOptions = $.extend({}, window.dzscsc_self_options);
          window.window.dzscsc_self_options = $.extend({}, {});
        }




      }
    }
    finalOptions = $.extend(defaults, argOptions);


    this.each(function () {

      var _ag = new DzsContentScroller(this, finalOptions, $);
      return this;

      return this;
    })
  };


  window.dzscsc_init = function (selector, settings) {
    if (typeof (settings) !== "undefined" && typeof (settings.init_each) !== "undefined" && settings.init_each === true) {
      var element_count = 0;
      for (var e in settings) {
        element_count++;
      }
      if (element_count === 1) {
        settings = undefined;
      }

      $(selector).each(function () {
        var _t = $(this);
        _t.contentscroller(settings)
      });
    } else {
      $(selector).contentscroller(settings);
    }

  };
})(jQuery);


jQuery(document).ready(function ($) {
  dzscsc_init('.contentscroller.auto-init', {init_each: true})
});
