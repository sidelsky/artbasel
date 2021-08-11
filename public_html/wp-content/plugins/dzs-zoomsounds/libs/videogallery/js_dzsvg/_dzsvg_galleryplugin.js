var helpersSvg = require('./_dzsvg_svgs');
var helpersDZSVG = require('./_dzsvg_helpers');
var helpersDZS = require('../js_common/_dzs_helpers');
var ConstantsDzsvg = require('../configs/Constants').constants;
var DzsNavigation = require('./navigation/_navigation');

class DzsVideoGallery {
  constructor(argThis, argOptions, $) {


    this.argThis = argThis;
    this.argOptions = argOptions;
    this.$ = $;

    this._sliderCon = null;
    this.$sliderMain = null;
    this.$navigationAndMainArea = null;
    this._mainNavigation = null;
    this.$feedItemsContainer = null;
    this.navigation_customStructure = '';
    this.$galleryButtons = null;
    this.$navigationItemsContainer = null;

    this.Navigation = null;


    this.classInit();
  }

  classInit() {


    var cgallery = null
      , $containerForItems = null
      , cid = ''
    ;
    var nrChildren = 0;
    var _navMain

      /** videogallery--navigation-container */
      , $searchFieldCon
    ;
    //gallery dimensions
    var videoWidth
      , videoAreaHeight
      , totalWidth
      , totalHeight
      , last_totalWidth = 0 // -- so we can compare to the last values
      , last_totalHeight = 0
      , navWidth = 0 // the _navCon width
      , navHeight = 0
      , ww
      , heightWindow
      , last_height_for_videoheight = 0 // -- last responsive_ratio height known
    ;

    var isMenuMovementLocked = false;

    var inter_start_the_transition = null;

    var nav_arrow_size = 40
    ;


    var isMergeSocialIconsIntoOne = false // -- merge all socials into one


    var backgroundY;
    var used = [];
    var currNr = -1
      , currNr_curr = -1 // current transitioning
      , nextNr = -1
      , prevNr = -1
      , currPage = 0
      , last_arg = 0
    ;
    var $currVideoPlayer;
    var arr_inlinecontents = [];

    var _rparent
      , _con
      , ccon
      , currScale = 1
      , heightInitial = -1
    ;
    var conw = 0;
    var conh = 0;

    var wpos = 0
      , hpos = 0
    ;
    var lastIndex = 99;

    var isBusyTransition = false
      , isTransitionStarted = false
      , isBusyAjax = false
      , isGalleryLoaded = false// -- gallery loaded sw, when dimensions are set, will take a while if wall
    ;
    var firsttime = true; // firsttime changed item
    var embed_opened = false
      , share_opened = false
      , search_added = false
      , first_played = false
      , isMouseOver = false
      , first_transition = false // -- first transition made
    ;


    var i = 0;

    var aux = 0
      , aux1 = 0
    ;


    var down_x = 0
      , up_x = 0;


    var menuitem_width = 0
      , menuitem_height = 0
      , menuitem_space = 0;

    var menu_position = 'right';
    var original_menu_position = 'right';

    var deeplinkGotoItemQueryParam = '';

    var settings_separation_nr_pages = 0;
    var ind_ajaxPage = 0;


    var init_settings = {};


    var action_playlist_end = null;

    var $ = this.$;
    var o = this.argOptions;
    cgallery = $(this.argThis);
    var selfClass = this;

    var feed_socialCode = '';

    selfClass.init = init;
    selfClass.cgallery = cgallery;
    selfClass.initOptions = o;


    init_settings = $.extend({}, o);

    helpersDZSVG.playlist_initSetupInitial(selfClass, o);






    if (isNaN(Number(o.menuitem_space)) === false) {
      menuitem_space = Number(o.menuitem_space);

    }


    menu_position = o.menu_position;
    original_menu_position = menu_position;



    nrChildren = selfClass.$feedItemsContainer.children('.vplayer,.vplayer-tobe').length;


    if (o.init_on === 'init') {
      init({
        'called_from': 'init'
      });
    }
    if (o.init_on === 'scroll') {
      $(window).on('scroll', handleScroll);
      handleScroll();
    }


    function init(pargs) {


      var margs = {
        caller: null
        , 'called_from': 'default'
      }


      if (selfClass.cgallery.hasClass('dzsvg-inited')) {
        return false;
      }

      if (pargs) {
        margs = $.extend(margs, pargs);
      }

      selfClass.handleResize_currVideo = handleResize_currVideo;

      if (cgallery.parent().parent().parent().hasClass('tab-content')) {
        // -- tabs
        helpersDZSVG.playlist_inDzsTabsHandle(selfClass, margs);
      }

      selfClass.cgallery.addClass('dzsvg-inited');

      ccon = cgallery.parent();
      _rparent = cgallery.parent();





      if (_rparent.parent().hasClass('gallery-is-fullscreen')) {
        if (o.videoplayersettings.responsive_ratio === 'detect') {
          o.videoplayersettings.responsive_ratio = 'default';
        }

      }





      // -- separation - PAGES
      var elimi = 0;



      if (o.settings_separation_mode === 'pages') {

        var dzsvg_page = helpersDZSVG.get_query_arg(window.location.href, 'dzsvgpage');


        if (typeof dzsvg_page == "undefined") {
          dzsvg_page = 1;
        }
        dzsvg_page = parseInt(dzsvg_page, 10);


        if (dzsvg_page == 0 || isNaN(dzsvg_page)) {
          dzsvg_page = 1;
        }

        if (dzsvg_page > 0 && o.settings_separation_pages_number < nrChildren) {

          var aux;
          if (o.settings_separation_pages_number * dzsvg_page <= nrChildren) {
            for (elimi = o.settings_separation_pages_number * dzsvg_page - 1; elimi >= o.settings_separation_pages_number * (dzsvg_page - 1); elimi--) {
              cgallery.children().eq(elimi).addClass('from-pagination-do-not-eliminate');
            }
          } else {
            for (elimi = nrChildren - 1; elimi >= nrChildren - o.settings_separation_pages_number; elimi--) {
              cgallery.children().eq(elimi).addClass('from-pagination-do-not-eliminate');
            }
          }

          cgallery.children().each(function () {
            var _t = $(this);
            if (!_t.hasClass('from-pagination-do-not-eliminate')) {
              _t.remove();
            }
          })

          var str_pagination = '<div class="con-dzsvg-pagination">';
          settings_separation_nr_pages = Math.ceil(nrChildren / o.settings_separation_pages_number);

          nrChildren = cgallery.children().length;

          for (i = 0; i < settings_separation_nr_pages; i++) {
            var str_active = '';
            if ((i + 1) == dzsvg_page) {
              str_active = ' active';
            }
            str_pagination += '<a class="pagination-number ' + str_active + '" href="' + helpersDZSVG.add_query_arg(window.location.href, 'dzsvgpage', (i + 1)) + '">' + (i + 1) + '</a>'
          }

          str_pagination += '</div>';
          cgallery.after(str_pagination);

        }
      }


      if (helpersDZSVG.is_touch_device()) {
        cgallery.addClass('is-touch');
      }


      helpersDZSVG.playlist_convertMenuThumbs(cgallery, o);

      if (o.settings_mode == 'wall' || o.settings_mode == 'videowall') {
        o.design_shadow = 'off';
        o.logo = '';
      }





      totalWidth = cgallery.width();
      totalHeight = cgallery.height();




      if (isNaN(totalWidth)) {
        totalWidth = 800;
      }

      if (isNaN(totalHeight)) {
        totalHeight = 400;
      }


      cid = cgallery.attr('id');
      if (typeof cid == 'undefined' || cid == '') {
        var auxnr = 0;
        var temps = 'vgallery' + auxnr;

        while ($('#' + temps).length > 0) {
          auxnr++;
          temps = 'vgallery' + auxnr;
        }

        cid = temps;
        cgallery.attr('id', cid);
      }


      deeplinkGotoItemQueryParam = (window.dzsvg_settings && (window.dzsvg_settings.deeplink_str)) ? String(window.dzsvg_settings.deeplink_str).replace('{{galleryid}}', cid) : 'the-video';






      cgallery.get(0).var_scale = 1;

      backgroundY = o.backgroundY;

      if (helpersDZSVG.is_touch_device()) {
        if (o.nav_type == 'scroller') {
          o.nav_type = 'thumbs';
        }
      }

      cgallery.addClass('mode-' + o.settings_mode);
      cgallery.addClass('nav-' + o.nav_type);

      var mainClass = '';

      if (typeof (cgallery.attr('class')) == 'string') {
        mainClass = cgallery.attr('class');
      } else {
        mainClass = cgallery.get(0).className;
      }
      if (mainClass.indexOf('skin-') == -1) {
        cgallery.addClass(o.design_skin);
      }


      for (i = 0; i < nrChildren; i++) {

        if (o.randomise == 'on') {
          randomise(0, nrChildren);
        } else {
          used[i] = i;
        }
      }

      if (helpersDZSVG.can_translate()) {
        $('html').addClass('supports-translate');
      }


      setup_structure();


      if (o.settings_mode === 'normal' || o.settings_mode === 'slider') {
        reinit();
      }

      if (o.search_field === 'on') {

        $searchFieldCon.bind('keyup', change_search_field);
      }




      if (helpersDZSVG.is_ios() || helpersDZSVG.is_android()) {

      }
      ;

      var hpos = 0;




      if (o.settings_mode === 'wall') {
        // -- wall
        if (cgallery.parent().hasClass('videogallery-con')) {
          cgallery.parent().css({'width': 'auto', 'height': 'auto'})
        }
        cgallery.css({'width': 'auto', 'height': 'auto'});

        selfClass._sliderCon.children().each(function () {
          // -- each item
          var _t = $(this);

          _t.addClass('vgwall-item').addClass('clearfix');


          var cssArgsWallItem = {
            'height': 'auto'
            , 'position': 'relative'
            , 'top': 'auto'
            , 'left': 'auto'
          };

          if (o.menuitem_width !== '200') {
            cssArgsWallItem.width = o.menuitem_width;
          }


          _t.css(cssArgsWallItem);

          _t.attr('data-bigwidth', o.modewall_bigwidth);
          _t.attr('data-bigheight', o.modewall_bigheight);
          _t.attr('data-biggallery', cgallery.attr('id'));


          var desc = _t.find('.menuDescription').html();

          var thumb = _t.attr('data-thumb');

          var thumb_imgblock = null;

          if (_t.find('.imgblock').length) {
            thumb_imgblock = _t.find('.imgblock');
          }

          if (desc) {

            // -- try to replace
            if (desc.indexOf('{ytthumb}') > -1) {
              desc = desc.split("{ytthumb}").join('<div style="background-image:url(//img.youtube.com/vi/' + helpersDZSVG.getDataOrAttr(_t, 'data-sourcevp') + '/0.jpg)" class="imgblock divimage"></div>');
            }
            if (desc.indexOf('{ytthumbimg}') > -1) {
              desc = desc.split("{ytthumbimg}").join('//img.youtube.com/vi/' + helpersDZSVG.getDataOrAttr(_t, 'data-sourcevp') + '/0.jpg');
            }
            _t.find('.menuDescription').html(desc);
          }


          if (thumb) {

          } else {

            if (thumb_imgblock) {
              if (thumb_imgblock.attr('data-imgsrc')) {

              } else {

                if (thumb_imgblock.attr('src')) {

                  thumb = _t.find('.imgblock').attr('src');
                } else {

                  thumb = thumb_imgblock.css('background-image');
                }
              }
            }

          }



          if (thumb) {

            thumb = thumb.replace('url(', '');
            thumb = thumb.replace(')', '');
            thumb = thumb.replace(/"/g, '');
            _t.attr('data-biggallerythumbnail', thumb);
          }


          _t.find('.menuDescription .imgblock').after(_t.children('.videoTitle').clone());


          if (_t.attr('data-videoTitle') !== undefined && _t.attr('data-videoTitle') !== '') {
            _t.prepend('<div class="videoTitle">' + _t.attr('data-videoTitle') + '</div>');
          }

          // -- setup wall
          if (!_t.attr('data-source')) {
            _t.attr('data-source', helpersDZSVG.getDataOrAttr(_t, 'data-sourcevp'));
          }
          if (_t.attr('data-previewimg') !== undefined) {
            var aux2 = _t.attr('data-previewimg');

            if (aux2 !== undefined && aux2.indexOf('{ytthumbimg}') > -1) {

              aux2 = aux2.split("{ytthumbimg}").join('//img.youtube.com/vi/' + helpersDZSVG.sanitize_to_youtube_id(helpersDZSVG.detect_videoTypeAndSourceForElement(_t).source) + '/0.jpg');
            }


            var stringPreviewImg = '';


            // -- *deprecated
            if (String(o.menuitem_height) !== '') {
              stringPreviewImg += '<div class="previewImg divimg" style="background-image:url(' + aux2 + '); width: 100%; ';
              stringPreviewImg += ' height:' + helpersDZS.sanitizeToCssPx(o.menuitem_height) + ';';
              stringPreviewImg += '"></div>';
            } else {
              stringPreviewImg += '<img class="previewImg" src="' + aux2 + '"';
              stringPreviewImg += '/>';
            }


            _t.prepend(stringPreviewImg);

          }




          var args = {};
          if (window.init_zoombox_settings) {
            args = window.init_zoombox_settings;
          }


          if ($.fn.zoomBox) {

            _t.zoomBox(args);
          } else {


          }
        });


        setTimeout(function () {

          setTimeout(handleResize, 1000);
          isGalleryLoaded = true;
        }, 1500);
      }
      // -- wall END


      if (o.settings_mode === 'videowall') {



        if (cgallery.parent().hasClass('videogallery-con')) {
          cgallery.parent().css({'width': 'auto', 'height': 'auto'})
        }
        cgallery.css({'width': 'auto', 'height': 'auto'});


      }


      if (o.settings_mode === 'wall' || o.settings_mode === 'videowall' || o.settings_mode === 'rotator' || o.settings_mode === 'rotator3d') {
        reinit({
          'called_from': 'init'
        });
      }


      if (o.logo) {
        selfClass.$sliderMain.append('<img class="the-logo" src="' + o.logo + '"/>');
        if (o.logoLink) {
          selfClass.$sliderMain.children('.the-logo').css('cursor', 'pointer');
          selfClass.$sliderMain.children('.the-logo').click(function () {
            window.open(o.logoLink);
          });
        }
      }



      if (window.dzsvg_settings && window.dzsvg_settings.merge_social_into_one === 'on') {
        isMergeSocialIconsIntoOne = true;

      }


      if (isMergeSocialIconsIntoOne) {
        if (o.embedCode !== '' || feed_socialCode) {
          helpersDZSVG.dzsvg_check_multisharer();
          if (o.settings_mode === 'wall') {

            if (selfClass.$sliderMain.children('.gallery-buttons').length === 0) {
              selfClass.$sliderMain.prepend('<div class="gallery-buttons"></div>');
              selfClass.$galleryButtons = cgallery.children('.gallery-buttons');

            }
            setTimeout(function () {
              selfClass.$sliderMain.before(selfClass.$galleryButtons);
            }, 500);
          }


          selfClass.$galleryButtons.append('<div class="embed-button open-in-embed-ultibox"><div class="handle">' + helpersSvg.svg_embed + '</div><div class="feed-dzsvg feed-dzsvg--embedcode">' + o.embedCode + '</div></div>');
          selfClass.$galleryButtons.find('.embed-button .handle').click(helpersDZSVG.dzsvg_click_open_embed_ultibox)


        }


      } else {

        if (o.embedCode !== '') {
          selfClass.$galleryButtons.append('<div class="embed-button"><div class="handle">' + helpersSvg.svg_embed + '</div><div class="contentbox" style="display:none;"><textarea class="thetext">' + o.embedCode + '</textarea></div></div>');
          selfClass.$galleryButtons.find('.embed-button .handle').click(click_embedhandle)
          selfClass.$galleryButtons.find('.embed-button .contentbox').css({
            'right': 50
          })
        }
        if (feed_socialCode) {
          selfClass.$galleryButtons.append('<div class="share-button"><div class="handle">' + helpersSvg.svgShareIcon + '</div><div class="contentbox" style="display:none;"><div class="thetext">' + feed_socialCode + '</div></div></div>');
          selfClass.$galleryButtons.find('.share-button .handle').click(click_sharehandle)
          selfClass.$galleryButtons.find('.share-button .contentbox').css({
            'right': 50
          })
        }
      }


      if (o.nav_type === 'outer') {
        selfClass.$navigationItemsContainer.addClass(o.nav_type_outer_grid);
        selfClass.$navigationItemsContainer.children().addClass('dzs-layout-item');


        if (o.menuitem_width) {
          o.menuitem_width = '';
        }


        if (o.nav_type_outer_max_height) {
          var nto_mh = Number(o.nav_type_outer_max_height);


          _navMain.css('max-height', nto_mh + 'px');
          _navMain.addClass('scroller-con skin_apple inner-relative');
          selfClass.$navigationItemsContainer.addClass('inner');

          _navMain.css({
            'height': 'auto'
          })

          try_to_init_scroller();
        }
      }


      calculateDims({


        'called_from': 'init'
      });


      if (o.nav_type === 'scroller') {
        _navMain.addClass('scroller-con skin_apple');
        selfClass.$navigationItemsContainer.addClass('inner');

        if ((menu_position === 'right' || menu_position === 'left') && nrChildren > 1) {

          selfClass.$navigationItemsContainer.css({
            'width': menuitem_width
          })
        }
        if ((menu_position === 'bottom' || menu_position === 'top') && nrChildren > 1) {

          selfClass.$navigationItemsContainer.css({
            'height': (menuitem_height)
          })
        }

        _navMain.css({
          'height': '100%'
        })


        // -- try scroller
        if ($.fn.scroller) {
          helpersDZSVG.navigation_initScroller(_navMain);
        } else {
          setTimeout(() => {
            helpersDZSVG.navigation_initScroller(_navMain);
          }, 2000);
        }

        setTimeout(function () {


          if ($('html').eq(0).attr('dir') === 'rtl') {
            _navMain.get(0).fn_scrollx_to(1);
          }
        }, 100);
      }
      // -- scroller END


      // -- NO FUNCTION HIER



      cgallery.on('click', '.rotator-btn-gotoNext,.rotator-btn-gotoPrev', handle_mouse);
      $(document).on('keyup.dzsvgg', handleKeyPress);


      window.addEventListener("orientationchange", handle_orientationchange);
      $(window).on('resize', handleResize);
      handleResize();

      setTimeout(function () {
        calculateDims({

          'called_from': 'first_timeout'
        })
      }, 3000);
      setTimeout(init_playlistIsReady, 100);


      if (o.settings_trigger_resize > 0) {
        setInterval(function () {


          calculateDims({
            'called_from': 'recheck_sizes'
          });
        }, o.settings_trigger_resize);
      }
      ;

      setup_apiFunctions();

      if (o.startItem === 'default') {
        o.startItem = 0;
        if (o.playorder === 'reverse') {
          o.startItem = nrChildren - 1;
        }
      }

      // --- gotoItem
      if (o.settings_mode !== 'wall' && o.settings_mode !== 'videowall') {

        isGalleryLoaded = true;






        if (helpersDZSVG.get_query_arg(window.location.href, 'dzsvg_startitem_' + cid)) {
          o.startItem = Number(helpersDZSVG.get_query_arg(window.location.href, 'dzsvg_startitem_' + cid));
        }






        var tempStartItem = helpersDZSVG.detect_startItemBasedOnQueryAddress(deeplinkGotoItemQueryParam, cid);
        if (tempStartItem !== null) {
          o.startItem = tempStartItem;
          if (cgallery.parent().parent().parent().hasClass('categories-videogallery')) {
            var _cach = cgallery.parent().parent().parent();

            var ind = _cach.find('.videogallery').index(cgallery);

            if (ind) {
              setTimeout(function () {
                _cach.get(0).api_goto_category(ind, {
                  'called_from': 'deeplink'
                });
              }, 100);
            }
          }
        }

        if (isNaN(o.startItem)) {
          o.startItem = 0;
        }





        // -- first item

        if (selfClass._sliderCon.children().eq(o.startItem).attr('data-type') === 'link') {
          // -- only for link
          gotoItem(o.startItem, {donotopenlink: "on", 'called_from': 'init'});

        } else {
          // -- first item
          // -- normal
          gotoItem(o.startItem, {'called_from': 'init'});
        }
        if (o.nav_type === 'scroller') {
          // todo: import from _navigation.js
        }


        if (o.settings_go_to_next_after_inactivity) {
          setInterval(function () {
            if (first_played === false) {

              gotoNext();
            }
          }, o.settings_go_to_next_after_inactivity * 1000);
        }
      }

      if (o.settings_separation_mode === 'scroll') {
        $(window).bind('scroll', handleScroll);
      }
      if (o.settings_separation_mode === 'button') {
        cgallery.append('<div class="btn_ajax_loadmore">Load More</div>');
        cgallery.on('click', '.btn_ajax_loadmore', click_btn_ajax_loadmore);
        if (o.settings_separation_pages.length === 0) {
          selfClass.cgallery.find('.btn_ajax_loadmore').hide();
        }
      }




      cgallery.on('mouseleave', handleMouseout);
      cgallery.on('mouseover', handleMouseover);


    }

    function setup_structure() {
      if (o.search_field_con) {
        $searchFieldCon = $(o.search_field_con);
        search_added = true;
      }

      let structNavigationAndMainArea = '<div class="navigation-and-main-area"></div>'


      selfClass.cgallery.append(structNavigationAndMainArea);
      selfClass.$navigationAndMainArea = selfClass.cgallery.find('.navigation-and-main-area').eq(0);


      var navOptions = {
        navigationType: (o.nav_type === 'thumbs' ? 'hover' : o.nav_type === 'thumbsandarrows' ? 'thumbsAndArrows' : o.nav_type),
        menuPosition: o.menu_position,
        menuItemWidth: o.menuitem_width,
        menuItemHeight: o.menuitem_height,
      };
      selfClass.Navigation = new DzsNavigation.DzsNavigation(selfClass, navOptions, $);



      if (o.design_shadow === 'on') {
        cgallery.prepend('<div class="shadow"></div>');
      }

      selfClass.$sliderMain = cgallery.find('.sliderMain');
      selfClass._sliderCon = cgallery.find('.sliderCon');

      selfClass._mainNavigation = cgallery.find('.main-navigation');

      selfClass._sliderCon.addClass(o.extra_class_slider_con);

      if (o.settings_mode === 'slider') {
        selfClass.$sliderMain.after(selfClass._mainNavigation);
      }


      if (o.settings_disableVideo === 'on') {
        selfClass.cgallery.addClass('main-area-disabled');
      }


      selfClass.$sliderMain.append('<div class="gallery-buttons"></div>');
      _navMain = selfClass.cgallery.find('.navMain');

      selfClass.$navigationItemsContainer = selfClass.cgallery.find('.videogallery--navigation-container').eq(0);


      if (o.settings_mode === 'slider') {
        _navMain.append('<div class="rotator-btn-gotoNext">' + helpersSvg.svgForwardButton + '</div><div class="rotator-btn-gotoPrev">' + helpersSvg.svgBackButton + '</div>');
      }
      if (o.settings_mode === 'rotator') {
        _navMain.append('<div class="rotator-btn-gotoNext"></div><div class="rotator-btn-gotoPrev"></div>');
        _navMain.append('<div class="descriptionsCon"></div>');
      }


      selfClass.$galleryButtons = selfClass.$sliderMain.children('.gallery-buttons');


      helpersDZSVG.navigation_detectClassesForPosition(menu_position, selfClass._mainNavigation, cgallery);


      // -- setup search field

      var struct_searchFieldString = '';
      if (!search_added && o.search_field === 'on') {
        struct_searchFieldString = '<div class="dzsvg-search-field"><input type="text" placeholder="search..."/>' + helpersSvg.svgSearchIcon + '</div>';
        if (selfClass._mainNavigation.hasClass('menu-moves-vertically')) {
          selfClass._mainNavigation.prepend(struct_searchFieldString);
        } else {
          selfClass.$navigationItemsContainer.prepend(struct_searchFieldString);
        }

        $searchFieldCon = cgallery.find('.dzsvg-search-field > input');
      }
    }


    function setup_apiFunctions() {


      // --- go to video 0 <<<< the start of the gallery
      cgallery.get(0).videoEnd = handleVideoEnd;
      cgallery.get(0).init_settings = init_settings;

      cgallery.get(0).api_play_currVideo = play_currVideo;
      cgallery.get(0).external_handle_stopCurrVideo = video_stopCurrentVideo;
      cgallery.get(0).api_gotoNext = gotoNext;
      cgallery.get(0).api_gotoPrev = gotoPrev;
      cgallery.get(0).api_gotoItem = gotoItem;
      cgallery.get(0).api_responsive_ratio_resize_h = apiResponsiveRationResize;

      // -- ad functions
      cgallery.get(0).api_ad_block_navigation = ad_block_navigation;
      cgallery.get(0).api_ad_unblock_navigation = ad_unblock_navigation;

      cgallery.get(0).api_handleResize = handleResize;
      cgallery.get(0).api_gotoItem = gotoItem;
      cgallery.get(0).api_handleResize_currVideo = handleResize_currVideo;
      cgallery.get(0).api_play_currVideo = play_currVideo;
      cgallery.get(0).api_pause_currVideo = pause_currVideo;
      cgallery.get(0).api_currVideo_refresh_fsbutton = api_currVideo_refresh_fsbutton;
      cgallery.get(0).api_video_ready = galleryTransition;
      cgallery.get(0).api_set_outerNav = function (arg) {
        o.settings_outerNav = arg;
      };
      cgallery.get(0).api_set_secondCon = function (arg) {
        o.settings_secondCon = arg;
      };
      cgallery.get(0).api_set_action_playlist_end = function (arg) {
        action_playlist_end = arg;
      };


      cgallery.get(0).api_played_video = function () {
        first_played = true;
      };
    }


    function handleMouseover(e) {



      isMouseOver = true;

    }


    function handleMouseout(e) {

      isMouseOver = false;

      if (o.nav_type_auto_scroll === 'on') {

        if (o.nav_type === 'thumbs' || o.nav_type === 'scroller') {
          setTimeout(function () {
            if (isMouseOver === false) {


              // todo: import from navigation.js



            } else {
              handleMouseout();
            }
          }, 2000);
        }
      }

    }

    function handleKeyPress(e) {


      if (e.type === 'keyup') {

        if (e.keyCode === 27) {
          $('.is_fullscreen').removeClass('is_fullscreen is-fullscreen');
          setTimeout(function () {
            $('.is_fullscreen').removeClass('is_fullscreen is-fullscreen');

          }, 999);
          cgallery.find('.is_fullscreen').removeClass('is_fullscreen is-fullscreen');
          setTimeout(function () {
            calculateDims();
          }, 100);
        }
      }


    }

    function try_to_init_scroller() {


      if (window.dzsscr_init) {



        window.dzsscr_init(_navMain, {

          'enable_easing': 'on'
          , 'settings_skin': 'skin_apple'
        });


      } else {


        // todo : move this to specialized script
        var baseUrl = '';
        var baseUrl_arr = helpersDZSVG.get_base_url_arr();
        for (var i24 = 0; i24 < baseUrl_arr.length - 2; i24++) {
          baseUrl += baseUrl_arr[i24] + '/';
        }

        var url = baseUrl + 'dzsscroller/scroller.js';

        $('head').append('<link rel="stylesheet" type="text/css" href="' + baseUrl + 'dzsscroller/scroller.css">');
        $.ajax({
          url: url,
          dataType: "script",
          success: function (arg) {


            try_to_init_scroller();


          }
        });
      }
    }


    function ad_block_navigation() {
      cgallery.addClass('ad-blocked-navigation');

    }

    function ad_unblock_navigation() {
      cgallery.removeClass('ad-blocked-navigation');
    }

    function init_playlistIsReady() {


      if (o.settings_mode === 'wall') {
        setTimeout(init_showPlaylist, 1500);
      } else {
        init_showPlaylist();
      }


      if (o.settings_secondCon) {
        // -- moving this to bottom
      }


      if (o.settings_outerNav) {

        // -- we moved setup to bottom
      }


      handleResize();

      selfClass.cgallery.addClass('inited');
    }

    function handle_mouse(e) {

      var _t = $(this);
      if (_t.hasClass('rotator-btn-gotoNext')) {

        gotoNext();
      }
      if (_t.hasClass('rotator-btn-gotoPrev')) {

        gotoPrev();
      }
    }


    function init_showPlaylist() {


      cgallery.parent().children('.preloader').fadeOut('fast');
      cgallery.parent().children('.css-preloader').fadeOut('fast');


      if (o.init_on === 'scroll' && cgallery.hasClass('transition-slidein')) {
        setTimeout(function () {

          cgallery.addClass('dzsvg-loaded');

          if (cgallery.parent().hasClass('videogallery-con')) {
            cgallery.parent().addClass('dzsvg-loaded');
          }
        }, 300);
      } else {

        cgallery.addClass('dzsvg-loaded');
        if (cgallery.parent().hasClass('videogallery-con')) {
          cgallery.parent().addClass('dzsvg-loaded');
        }
      }
    }

    function setup_navigation_items() {

      var len = selfClass.$feedItemsContainer.find('.vplayer-tobe').length;


      if (o.settings_mode === 'normal') {


        for (i = 0; i < len; i++) {
          var $currentItemFeed = selfClass.$feedItemsContainer.find('.vplayer-tobe').eq(used[i]);
          var structureMenuItemContentInner = null;
          var final_structureMenuItemContent = '';

          if ($currentItemFeed.find('.menuDescription').length) {
            structureMenuItemContentInner = $currentItemFeed.find('.menuDescription').html();
          }

          // -- take the video title from menu description .. for some reason
          if (!($currentItemFeed.attr('data-videoTitle'))) {
            if ($currentItemFeed.find('.menuDescription .the-title').html()) {
              if (o.disable_videoTitle !== 'on') {
                $currentItemFeed.attr('data-videoTitle', $currentItemFeed.find('.menuDescription .the-title').html());
              }

            }
          }
          $currentItemFeed.find('.menuDescription').remove();

          if (structureMenuItemContentInner == null) {
            if (o.menu_description_format || selfClass.navigation_customStructure) {

              if (o.menu_description_format) {
                structureMenuItemContentInner = helpersDZSVG.deprecated__playlist_navigationGenerateStructure(o.menu_description_format, $currentItemFeed);
              }
              if (selfClass.navigation_customStructure) {
                structureMenuItemContentInner = helpersDZSVG.playlist_navigationGenerateStructure(selfClass);

              }
            } else {
              continue;
            }
          }
          if (structureMenuItemContentInner === null) {
            structureMenuItemContentInner = '';
          }

          var vpRealSrc = helpersDZSVG.getDataOrAttr($currentItemFeed, 'data-sourcevp');
          var sourceAndType = helpersDZSVG.detect_video_type_and_source(vpRealSrc);
          vpRealSrc = sourceAndType.source;
          $currentItemFeed.data('dzsvg-curatedtype-from-gallery', sourceAndType.type);
          if (sourceAndType.type === 'youtube') {
            if (sourceAndType.source) {
              $currentItemFeed.data('dzsvg-curatedid-from-gallery', sourceAndType.source);
            }
          }
          structureMenuItemContentInner = helpersDZSVG.deprecated__playlist_replace_youtube_thumb_in_desc(structureMenuItemContentInner, $currentItemFeed);


          vpRealSrc = helpersDZSVG.getDataOrAttr($currentItemFeed, 'data-sourcevp');
          sourceAndType = helpersDZSVG.detect_video_type_and_source(vpRealSrc);
          vpRealSrc = sourceAndType.source;
          $currentItemFeed.data('dzsvg-curatedtype-from-gallery', sourceAndType.type);
          $currentItemFeed.data('dzsvg-curatedid-from-gallery', sourceAndType.source);




          // -- this is inside video gallery
          if (($currentItemFeed.data('dzsvg-curatedtype-from-gallery') === 'youtube' || $currentItemFeed.data('dzsvg-curatedtype-from-gallery') === 'vimeo' || $currentItemFeed.data('dzsvg-curatedtype-from-gallery') === 'facebook' || $currentItemFeed.data('dzsvg-curatedtype-from-gallery') === 'inline')
            && o.videoplayersettings.responsive_ratio === 'detect' && !($currentItemFeed.attr('data-responsive_ratio'))) {
            if (!$currentItemFeed.attr('data-responsive_ratio') || $currentItemFeed.attr('data-responsive_ratio') === 'detect') {
              $currentItemFeed.attr('data-responsive_ratio', '0.5625');
            }
            if ($currentItemFeed.data('dzsvg-curatedtype-from-gallery') === 'inline') {
              setTimeout(function () {
                apiResponsiveRationResize(0.5625 * videoWidth);
              }, 3000);
            }
            $currentItemFeed.attr('data-responsive_ratio-not-known-for-sure', 'on');  // -- we set this until we know the responsive ratio for sure , 0.562 is just 16/9 ratio so should fit to most videos

            if (o.php_media_data_retriever) {
              helpersDZSVG.playlist_get_real_responsive_ratio(i, selfClass);
            }
          }

          if ($currentItemFeed.data('dzsvg-curatedtype-from-gallery') === 'link') {
            final_structureMenuItemContent += '<a class=" dzs-navigation--item"';
            if (selfClass.$feedItemsContainer.children('.vplayer-tobe').eq(i).attr('data-source')) {
              final_structureMenuItemContent += ' href="' + $currentItemFeed.attr('data-source') + '"';
            }
            if (selfClass.$feedItemsContainer.children('.vplayer-tobe').eq(i).attr('data-target')) {
              final_structureMenuItemContent += ' target="' + $currentItemFeed.attr('data-target') + '"';
            }
            final_structureMenuItemContent += '>';
          } else {
            final_structureMenuItemContent += '<div class=" dzs-navigation--item">';
          }


          final_structureMenuItemContent += '<div class=" dzs-navigation--item-content">';
          if (o.settings_menu_overlay === 'on') {
            final_structureMenuItemContent += '<div class="menuitem-overlay"></div>';
          }


          if ($containerForItems.hasClass('skin-boxy')) {
            structureMenuItemContentInner = structureMenuItemContentInner.replace(/\<img src=\"(.+?)".*?\/{0,1}>/g, '<div class="big-thumb" style=\'background-image:url("$1");\'></div>');
          }
          final_structureMenuItemContent += structureMenuItemContentInner + '</div>';


          if ($currentItemFeed.data('dzsvg-curatedtype-from-gallery') === 'link') {

            final_structureMenuItemContent += '</a>';
          } else {

            final_structureMenuItemContent += '</div>';
          }
          selfClass.$navigationItemsContainer.append(final_structureMenuItemContent);


          var _cachmenuitem = selfClass.$navigationItemsContainer.children().last();

          if (o.settings_mode === 'normal') {

            if (selfClass.navigation_customStructure) {

              helpersDZSVG.playlist_navigationStructureAssignVars(selfClass, $currentItemFeed, _cachmenuitem);
            }
            if (o.mode_normal_video_mode === 'one') {
              helpersDZSVG.playlist_navigation_mode_one__set_players_data(_cachmenuitem);
            }
          }
          _cachmenuitem.find('.imgblock.divimage').addClass('big-thumb');
        }

        if (selfClass._mainNavigation) {


          selfClass._mainNavigation.find('.imgblock').each(function () {
            var _t3 = $(this);
            if (_t3.attr('data-imgsrc')) {
              if (_t3.get(0).nodeName === "DIV") {
                _t3.css('background-image', 'url(' + _t3.attr('data-imgsrc') + ')')
              }
              if (_t3.get(0).nodeName === "IMG") {
                _t3.attr('src', '' + _t3.attr('data-imgsrc') + '')

              }
              _t3.attr('data-imgsrc', '');
            }
          })
        }

      }
    }

    /**
     * transfer from feed con to slider con
     */
    function setup_transfer_items_to_sliderCon() {


      var len = selfClass.$feedItemsContainer.find('.vplayer-tobe').length;
      for (i = 0; i < len; i++) {
        var _t = selfClass.$feedItemsContainer.children('.vplayer-tobe').eq(0);
        selfClass._sliderCon.append(_t);
      }
    }

    function reinit(pargs) {


      var margs = {
        caller: null
        , 'called_from': 'default'
      }

      if (pargs) {
        margs = $.extend(margs, pargs);
      }


      $containerForItems = cgallery;

      if (selfClass.$feedItemsContainer.children('.vplayer-tobe').length === 0) {
        $containerForItems = selfClass._sliderCon;
      }

      setup_navigation_items();
      setup_transfer_items_to_sliderCon();


      if (o.settings_mode === 'videowall') {

        selfClass._sliderCon.children().each(function () {
          // --each item
          var _t = $(this);

          _t.wrap('<div class="dzs-layout-item"></div>');


          o.videoplayersettings.responsive_ratio = 'detect';
          o.videoplayersettings.autoplay = 'off';
          o.videoplayersettings.preload_method = 'metadata';


          o.init_all_players_at_init = 'on';

        });
      }


      if (o.settings_mode === 'rotator3d') {
        menu_position = 'none';

        selfClass._sliderCon.children().each(function () {
          var _t = $(this);
          _t.addClass('rotator3d-item');
          _t.css({'width': videoWidth, 'height': videoAreaHeight})
          _t.append('<div class="previewImg" style="background-image:url(' + helpersDZSVG.playlist_navigation_getPreviewImg(_t) + ');"></div>');
          _t.children('.previewImg').bind('click', rotator3d_handleClickOnPreviewImg);

        })
      }


      if (o.init_all_players_at_init === 'on') {

        // -- init all players
        selfClass._sliderCon.find('.vplayer-tobe').each(function () {
          // -- each item
          var _t = $(this);

          o.videoplayersettings.autoplay = 'off';
          o.videoplayersettings.preload_method = 'metadata';


          o.videoplayersettings.gallery_object = cgallery;
          _t.vPlayer(o.videoplayersettings);
        });
      }
      if (o.settings_mode === 'wall') {

        helpersDZSVG.playlist_setupModeWall(selfClass, o);
      }

      $containerForItems.find('.imgblock,.divimage').each(function () {
        var _t3 = $(this);

        if (_t3.attr('data-imgsrc')) {
          if (_t3.get(0).nodeName === "DIV") {
            _t3.css('background-image', 'url(' + _t3.attr('data-imgsrc') + ')')
          }
          if (_t3.get(0).nodeName === "IMG") {
            _t3.attr('src', '' + _t3.attr('data-imgsrc') + '')
          }
          _t3.attr('data-imgsrc', '');
        }
      })


      nrChildren = selfClass._sliderCon.children().length;


      if (selfClass.cgallery.find('.feed-dzsvg--socialCode').length) {
        feed_socialCode = selfClass.cgallery.find('.feed-dzsvg--socialCode').html();
      }
    }


    function change_search_field() {
      var _t = $(this);


      if (o.settings_mode === 'wall') {
        selfClass._sliderCon.children().each(function () {
          var _t2 = $(this);


          if (_t.val() === '' || String(String(_t2.find('.menuDescription').eq(0).html()).toLowerCase()).indexOf(_t.val().toLowerCase()) > -1) {

            _t2.show();
          } else {

            _t2.hide();
          }


        });
      }


      if (o.nav_type === 'scroller') {


        if (typeof _navMain.get(0).api_scrolly_to != 'undefined') {
          _navMain.get(0).api_scrolly_to(0);
        }

        setTimeout(function () {

          selfClass.$navigationItemsContainer.css('top', '0')
        }, 100)
      }
      selfClass.$navigationItemsContainer.children().each(function () {
        var _t2 = $(this);


        if (_t.val() === '' || String(String(_t2.find('.dzs-navigation--item-content').eq(0).html()).toLowerCase()).indexOf(_t.val().toLowerCase()) > -1) {

          _t2.show();
        } else {

          if (_t2.hasClass('dzsvg-search-field') === false) {
            _t2.hide();
          }
        }
      });

      handleResize();
    }

    /**
     * called from outside
     * @param resizeHeightDimension
     * @param pargs
     * @returns {boolean}
     */
    function apiResponsiveRationResize(resizeHeightDimension, pargs) {

      // -- gallery
//                return false;
//                videoHeight = arg;


      var margs = {
        caller: null
        , 'called_from': 'default'
      }

      if (pargs) {
        margs = $.extend(margs, pargs);
      }

      if (margs.caller == null || cgallery.parent().hasClass('skin-laptop')) {
        return false;
      }


      if (heightInitial === -1) {
        heightInitial = selfClass.$sliderMain.height();
      }


      $currVideoPlayer.height(resizeHeightDimension);
      selfClass.$sliderMain.css({
        'height':resizeHeightDimension,
        'min-height':resizeHeightDimension
      });



      if (cgallery.hasClass('ultra-responsive') === false && (menu_position === 'left' || menu_position === 'right' || menu_position === 'none')) {
        totalHeight = resizeHeightDimension;
        videoAreaHeight = resizeHeightDimension;


        if (o.settings_mode !== 'slider') {
          selfClass._mainNavigation.height(resizeHeightDimension);
        }
        videoAreaHeight = resizeHeightDimension;

        setTimeout(() => {

          selfClass.Navigation.calculateDims({forceMainAreaHeight: resizeHeightDimension});
        })

      } else {
        // -- responsive ratio
        selfClass.cgallery.css('height', 'auto');


        videoAreaHeight = resizeHeightDimension;

      }


      if (margs.caller) {
        margs.caller.data('height_for_videoheight', resizeHeightDimension);
        calculateDims({
          called_from: 'height_for_videoheight'
        });
      }

      if (o.nav_type === 'scroller') {
        setTimeout(function () {


          if (_navMain.get(0) && _navMain.get(0).api_toggle_resize) {
            _navMain.get(0).api_toggle_resize();
          }
        }, 100)
      }


    }


    /**
     * calculate dimensions
     * @param pargs
     * @returns {boolean}
     */
    function calculateDims(pargs) {

      var margs = {
        'called_from': 'default'
      };
      if (pargs) {
        margs = $.extend(margs, pargs);
      }




      totalWidth = selfClass.cgallery.outerWidth();
      totalHeight = selfClass.cgallery.height();

      if (selfClass.cgallery.height() === 0) {
        if (o.forceVideoHeight) {
          if (menu_position === 'top' || menu_position === 'bottom') {
            totalHeight = o.forceVideoHeight + o.design_menuitem_height;
          } else {
            totalHeight = o.forceVideoHeight;
          }
        }
      }


      if (margs.called_from === 'recheck_sizes') {

        if (Math.abs(last_totalWidth - totalWidth) < 4 && Math.abs(last_totalHeight - totalHeight) < 4) {


          return false;
        }

      }


      last_totalWidth = totalWidth;
      last_totalHeight = totalHeight;


      if (totalWidth < 721) {
        cgallery.addClass('under-720');


      } else {
        cgallery.removeClass('under-720');
      }


      if (totalWidth < 601) {
        cgallery.addClass('under-600');
      } else {
        cgallery.removeClass('under-600');
      }


      if (String(cgallery.get(0).style.height).indexOf('%') > -1) {

        totalHeight = cgallery.height();
      } else {

        if (cgallery.data('init-height')) {

          totalHeight = cgallery.data('init-height');
        } else {

          totalHeight = cgallery.height();

          setTimeout(function () {
          })

        }
      }




      videoWidth = totalWidth;
      videoAreaHeight = totalHeight;



      menuitem_width = o.menuitem_width;
      menuitem_height = o.menuitem_height;


      if ((menu_position === 'right' || menu_position === 'left') && nrChildren > 1) {
        videoWidth -= (menuitem_width + menuitem_space);
      }


      if (o.nav_type !== 'outer' && (menu_position === 'bottom' || menu_position === 'top') && nrChildren > 1 && cgallery.get(0).style && cgallery.get(0).style.height && cgallery.get(0).style.height !== 'auto') {
        videoAreaHeight -= (menuitem_height + menuitem_space);
      } else {
        videoAreaHeight = o.sliderAreaHeight;
      }



      if ($currVideoPlayer && $currVideoPlayer.data('height_for_videoheight')) {

        videoAreaHeight = $currVideoPlayer.data('height_for_videoheight');

        last_height_for_videoheight = videoAreaHeight;
      } else {
        // -- lets try to get the last value known for responsive ratio if the height of the current video is now currently known
        if (o.videoplayersettings.responsive_ratio && o.videoplayersettings.responsive_ratio === 'detect') {
          if (last_height_for_videoheight) {
            videoAreaHeight = last_height_for_videoheight;
          }

        } else {
          if (menu_position === 'left' || menu_position === 'right') {
            videoAreaHeight = o.sliderAreaHeight;
          }
        }
      }


      if (o.forceVideoHeight !== '' && (!o.videoplayersettings || o.videoplayersettings.responsive_ratio !== 'detect')) {
        videoAreaHeight = o.forceVideoHeight;
      }

      if (o.settings_mode === 'rotator3d') {
        videoWidth = totalWidth / 2;
        videoAreaHeight = totalHeight * 0.8;
        menuitem_width = 0;
        menuitem_height = 0;
        menuitem_space = 0;
      }


      cgallery.addClass('transition-' + o.transition_type)


      // === if there is only one video we hide the nav
      if (nrChildren == 1) {
        //totalWidth = videoWidth;
        selfClass._mainNavigation.hide();
      }


      if (typeof ($currVideoPlayer) != 'undefined') {

      }
      ;

      hpos = 0;
      for (i = 0; i < nrChildren; i++) {

        selfClass._sliderCon.children().eq(i).css({})
        hpos += totalHeight;
      }

      if (o.settings_mode !== 'wall' && o.settings_mode !== 'videowall') {


        selfClass.$sliderMain.css({
          'width': videoWidth
        })


        if ((menu_position === 'left' || menu_position === 'right') && nrChildren > 1) {
          selfClass.$sliderMain.css('width', 'auto');

        }

        selfClass.$sliderMain.css({
          'height': videoAreaHeight
        })

      }

      if (o.settings_mode === 'rotator3d') {
        selfClass.$sliderMain.css({
          'width': totalWidth,
          'height': totalHeight
        })
        selfClass._sliderCon.children().css({
          'width': videoWidth,
          'height': videoAreaHeight
        })
      }
      helpersDZSVG.playlist_navigation_set_dimensions(selfClass, menu_position, menuitem_width, menuitem_height, totalWidth, totalHeight);


      // -- END calculate dims for navigation / mode-normal


      if (o.settings_mode === 'normal') {
        hpos = 0;
        wpos = 0;

        selfClass.$navigationItemsContainer.children('.dzs-navigation--item').unbind('click', handleClickOnNavigationContainer);
        selfClass.$navigationItemsContainer.children('.dzs-navigation--item').bind('click', handleClickOnNavigationContainer);


        selfClass.$navigationItemsContainer.find('.dzs-navigation--item').css({
          'width': menuitem_width,
          'height': menuitem_height
        });

        if (menuitem_height === 0) {
          selfClass.$navigationItemsContainer.find('.dzs-navigation--item').css({
            'height': ''
          });
        }

      }

      if (o.nav_type === 'scroller') {

        if (menu_position === 'top' || menu_position === 'bottom') {
          navWidth = 0;
          selfClass.$navigationItemsContainer.children().each(function () {
            var _t = $(this);
            navWidth += _t.outerWidth(true);
          });
          selfClass.$navigationItemsContainer.width(navWidth);
        }
      }


      calculateDims_secondCon(currNr_curr);


      selfClass.Navigation.calculateDims();
      // -- calculateDims() END
    }

    function handle_orientationchange() {
      setTimeout(function () {
        handleResize();
      }, 1000);
    }

    function handleResize(e, pargs) {
      ww = $(window).width();
      heightWindow = $(window).height();

      conw = _rparent.width();


      if (cgallery.hasClass('try-breakout')) {
        cgallery.css('width', ww + 'px');

        cgallery.css('margin-left', '0');


        if (cgallery.offset().left > 0) {
          cgallery.css('margin-left', '-' + cgallery.offset().left + 'px');
        }
      }


      if (cgallery.hasClass('try-height-as-window-minus-offset')) {

        var aux = heightWindow - cgallery.offset().top;
        if (aux < 300) {
          cgallery.css('height', '90vh')
        } else {
          cgallery.css('height', aux + 'px');
        }

      }


      calculateDims();


      if ($currVideoPlayer) {
        handleResize_currVideo();
      }

    }

    function handleResize_currVideo(e, pargs) {


      var margs = {
        'force_resize_gallery': true
        , 'called_from': 'default'
      };

      if (pargs) {
        margs = $.extend(margs, pargs);
      }


      margs.called_from += '_handleResize_currVideo';

      if (($currVideoPlayer) && $currVideoPlayer.get(0) && ($currVideoPlayer.get(0).api_handleResize)) {


        $currVideoPlayer.get(0).api_handleResize(null, margs);
      }
    }

    function pause_currVideo(e, pargs) {


      var margs = {
        'force_resize_gallery': true
        , 'called_from': 'default'
      };

      if (pargs) {
        margs = $.extend(margs, pargs);
      }


      margs.called_from += '_pause_currVideo';

      if (($currVideoPlayer) && ($currVideoPlayer.get(0).api_pauseMovie)) {


        $currVideoPlayer.get(0).api_pauseMovie(margs);
      }
    }


    function api_currVideo_refresh_fsbutton(argcol) {
      if (typeof ($currVideoPlayer) != "undefined" && typeof ($currVideoPlayer.get(0)) != "undefined" && typeof ($currVideoPlayer.get(0).api_currVideo_refresh_fsbutton) != "undefined") {
        $currVideoPlayer.get(0).api_currVideo_refresh_fsbutton(argcol);
      }
    }


    function randomise(arg, max) {
      arg = parseInt(Math.random() * max);
      var sw = 0;
      for (var j = 0; j < used.length; j++) {
        if (arg === used[j])
          sw = 1;
      }
      if (sw === 1) {
        randomise(0, max);
        return;
      } else
        used.push(arg);
      return arg;
    }


    /**
     *
     * @param {Event} e
     * @returns {boolean}
     */
    function handleHadFirstInteraction(e) {

      if (selfClass.cgallery.data('user-had-first-interaction')) {
        return false;
      }


      selfClass.isHadFirstInteraction = true;
      selfClass.cgallery.data('user-had-first-interaction', 'yes');

      selfClass.cgallery.addClass('user-had-first-interaction');


    }

    function handleClickOnNavigationContainer(e) {
      var _t = $(this);

      var cclass = '';

      if (_t.hasClass('dzs-navigation--item')) {
        cclass = '.dzs-navigation--item';
      }

      if (e) {
        handleHadFirstInteraction(e);
      }


      if (_t.get(0) && _t.get(0).nodeName !== "A") {
        gotoItem(selfClass.$navigationItemsContainer.children(cclass).index(_t));


        if (o.nav_type_auto_scroll === 'on') {
          if (o.nav_type === 'thumbs' || o.nav_type === 'scroller') {


            isMenuMovementLocked = true;

            setTimeout(function () {


              // todo: get form _navigation.js
              // animate_to_curr_thumb({
              //   'called_from': 'animate_to_curr_thumb'
              // });

            }, 100);
            setTimeout(function () {


              isMenuMovementLocked = false;

            }, 2000);
          }
        }

      } else {
        if ($currVideoPlayer && $currVideoPlayer.get(0) && typeof ($currVideoPlayer.get(0).api_pauseMovie) != "undefined") {
          $currVideoPlayer.get(0).api_pauseMovie({
            'called_from': 'handleClickOnNavigationContainer()'
          });
        }

      }

    }

    function handleScroll() {
      if (isGalleryLoaded === false) {
        // -- try init


        var st = $(window).scrollTop();
        var cthisOffsetTop = cgallery.offset().top;

        var wh = window.innerHeight;




        if (cthisOffsetTop < st + wh + 50) {
          init();
        }

        return;
      } else {
        // -- try LOAD MORE

        var _t = $(this);//==window
        wh = $(window).height();
        if (isBusyAjax === true || ind_ajaxPage >= o.settings_separation_pages.length) {
          return;
        }


        if ((_t.scrollTop() + wh) > (cgallery.offset().top + cgallery.height() - 10)) {
          ajax_load_nextpage();
        }
      }

    }

    function click_btn_ajax_loadmore(e) {

      if (isBusyAjax === true || ind_ajaxPage >= o.settings_separation_pages.length) {
        return;
      }
      cgallery.find('.btn_ajax_loadmore').addClass('disabled')
      ajax_load_nextpage();
    }

    function ajax_load_nextpage() {

      cgallery.parent().children('.preloader').fadeIn('slow');

      $.ajax({
        url: o.settings_separation_pages[ind_ajaxPage],
        success: function (response) {
          if (window.console !== undefined) {
            console.log('Got this from the server: ' + response);
          }
          setTimeout(function () {

            selfClass.$feedItemsContainer.append(response);
            reinit({
              'called_from': 'ajax_load_nextpage'
            });
            cgallery.find('.btn_ajax_loadmore').removeClass('disabled');
            setTimeout(function () {
              isBusyAjax = false;
              cgallery.parent().children('.preloader').fadeOut('slow');
              ind_ajaxPage++;


              if (ind_ajaxPage >= o.settings_separation_pages.length) {
                cgallery.children('.btn_ajax_loadmore').fadeOut('slow');
              }


            }, 1000);
          }, 1000);
        },
        error: function (xhr, ajaxOptions, thrownError) {
          if (window.console !== undefined) {
            console.error('not found ' + ajaxOptions);
          }
          ind_ajaxPage++;
          cgallery.parent().children('.preloader').fadeOut('slow');

        }
      });

      isBusyAjax = true;
    }

    function gotoItem(arg, pargs) {


      var gotoItemOptions = {

        'ignore_arg_currNr_check': false
        , 'ignore_linking': false // -- does not change the link if set to true
        , donotopenlink: "off"
        , called_from: "default"
      }

      if (pargs) {
        gotoItemOptions = $.extend(gotoItemOptions, pargs);
      }




      if (!(o.settings_mode === 'normal' && o.mode_normal_video_mode === 'one')) {

        if (currNr === arg || isBusyTransition === true) {
          return false;
        }
      }
      var transformed = false; //if the video is already transformed there is no need to wait
      var _currentTargetPlayer = selfClass._sliderCon.children().eq(arg);
      var argsForVideoPlayer = $.extend({}, o.videoplayersettings);


      $currVideoPlayer = _currentTargetPlayer;

      argsForVideoPlayer.gallery_object = cgallery;
      argsForVideoPlayer.gallery_last_curr_nr = currNr;
      if (gotoItemOptions.called_from === 'init') {
        argsForVideoPlayer.first_video_from_gallery = 'on';
      }

      argsForVideoPlayer['gallery_target_index'] = arg;


      var shouldVideoAutoplay = helpersDZSVG.assertVideoFromGalleryAutoplayStatus(currNr, o, cgallery);
      argsForVideoPlayer['autoplay'] = shouldVideoAutoplay ? 'on' : 'off';


      currNr_curr = arg;



      if (o.settings_enable_linking === 'on') {

        if (_currentTargetPlayer.attr('data-type') === 'link' && (gotoItemOptions.donotopenlink !== 'on')) {
          helpersDZSVG.playlistGotoItemHistoryChangeForLinks(ind_ajaxPage, o, cgallery, _currentTargetPlayer, deeplinkGotoItemQueryParam);
          return false;
        }
        if (_currentTargetPlayer.attr('data-type') !== 'link') {
          helpersDZSVG.playlistGotoItemHistoryChangeForNonLinks(gotoItemOptions, o, cid, arg, deeplinkGotoItemQueryParam);
        }
      }

      if (o.settings_mode === 'normal' && o.mode_normal_video_mode === 'one') {
        _currentTargetPlayer = selfClass._sliderCon.children().eq(0);
        _currentTargetPlayer.addClass('playlist-mode-video-one--main-player')
        $currVideoPlayer = _currentTargetPlayer;

        var _targetPlayer = selfClass._sliderCon.children().eq(arg);
        var optionsForChange = helpersDZSVG.detect_videoTypeAndSourceForElement(_targetPlayer);
        // -- one
        if ($currVideoPlayer.hasClass('vplayer')) {

          pause_currVideo();


          $currVideoPlayer.get(0).api_change_media(
            optionsForChange.source, {
              'type': optionsForChange.type,
              autoplay: shouldVideoAutoplay ? 'on' : 'off'
            })

        } else {
          // -- one video_mode .. vplayer-tobe
          // -- first item
          $currVideoPlayer.vPlayer(argsForVideoPlayer);
          $currVideoPlayer.addClass('active');
          $currVideoPlayer.addClass('currItem');
        }
        selfClass.$navigationItemsContainer.children('.dzs-navigation--item').removeClass('active');
        selfClass.$navigationItemsContainer.children('.dzs-navigation--item').eq(arg).addClass('active');
      }


      // -- not one
      if (!(o.settings_mode === 'normal' && o.mode_normal_video_mode === 'one')) {
        if (currNr > -1) {
          var _c2 = selfClass._sliderCon.children().eq(currNr);

          // --- if on iPad or iPhone, we disable the video as it had runed in a iframe and it wont pause otherwise
          _c2.addClass('transitioning-out');
          if (o.settings_mode === 'normal' && (helpersDZSVG.is_ios() || _c2.attr('data-type') === 'inline' || (_c2.attr('data-type') === 'youtube' && o.videoplayersettings['settings_youtube_usecustomskin'] !== 'on'))) {
            setTimeout(function () {
              _c2.find('.video-description').remove();
              _c2.data('original-iframe', _c2.html());

              // -- we will delete inline content here
              _c2.html('');

              _c2.removeClass('vplayer');
              _c2.addClass('vplayer-tobe');

            }, 1000);
          }
          ;
        }
      }


      if (o.autoplay_ad === 'on') {

        _currentTargetPlayer.data('adplayed', 'on');
      } else {
        _currentTargetPlayer.data('adplayed', 'off');
      }


      if (_currentTargetPlayer.hasClass('vplayer')) {
        transformed = true;
      }


      if (!(o.settings_mode == 'normal' && o.mode_normal_video_mode == 'one')) {
        _currentTargetPlayer.addClass('transitioning-in');
      }


      if (_currentTargetPlayer.hasClass('type-inline') && _currentTargetPlayer.data('original-iframe')) {
        if (_currentTargetPlayer.html() == '') {
          _currentTargetPlayer.html(_currentTargetPlayer.data('original-iframe'));
        }
      }

      // -- not one
      if (!(o.settings_mode === 'normal' && o.mode_normal_video_mode === 'one')) {
        if (_currentTargetPlayer.hasClass('vplayer-tobe')) {
          // -- if not inited

          _currentTargetPlayer.addClass('in-vgallery');
          argsForVideoPlayer['videoWidth'] = videoWidth;
          argsForVideoPlayer['videoHeight'] = '';
          argsForVideoPlayer['old_curr_nr'] = currNr;

          // -- we have gallery logo
          if (o.logo) {
            _currentTargetPlayer.children('.vplayer-logo').remove();
          }

          if (currNr == -1 && o.cueFirstVideo == 'off') {
            argsForVideoPlayer.cueVideo = 'off';
          } else {
            argsForVideoPlayer.cueVideo = 'on';
          }

          argsForVideoPlayer['settings_disableControls'] = 'off';


          if (typeof (arr_inlinecontents[arg]) != 'undefined' && arr_inlinecontents[arg]) {
            argsForVideoPlayer.htmlContent = arr_inlinecontents[arg];
          } else {
            argsForVideoPlayer.htmlContent = '';
          }

          argsForVideoPlayer.gallery_object = cgallery;

          if (argsForVideoPlayer.end_exit_fullscreen == 'off') {
            // -- exit fullscreen on video end

            if (cgallery.find('.vplayer.currItem').hasClass('type-vimeo')) {
              cgallery.find('.vplayer.currItem').removeClass('is_fullscreen is-fullscreen')
            }

            // -- next video has fullscreen status
            if (helpersDZSVG.fullscreen_status() == '1') {
              argsForVideoPlayer.extra_classes = argsForVideoPlayer.extra_classes ? argsForVideoPlayer.extra_classes + ' is_fullscreen is-fullscreen' : ' is_fullscreen is-fullscreen';
            }

            setTimeout(function () {

            }, 500);
          }

          if (o.settings_disableVideo == 'on') {
          } else {
            // -- NOT ONE MODE o.mode_normal_video_mode
            _currentTargetPlayer.vPlayer(argsForVideoPlayer);

          }


        } else {

          // -- NOT (ONE) if already setuped


          if (!(o.init_all_players_at_init == 'on' && currNr === -1)) {
            if (shouldVideoAutoplay) {
              if (typeof _currentTargetPlayer.get(0) != 'undefined' && typeof _currentTargetPlayer.get(0).externalPlayMovie != 'undefined') {
                _currentTargetPlayer.get(0).externalPlayMovie({
                  'called_from': 'autoplayNext'
                });
              }
            }
          }

          if (o.videoplayersettings.end_exit_fullscreen === 'off') {



            if (helpersDZSVG.fullscreen_status() == '1') {
              _currentTargetPlayer.addClass('is_fullscreen is-fullscreen');
            }
          }

          // -- we force a resize on the player just in case it has an responsive ratio


          setTimeout(function () {
            if (typeof _currentTargetPlayer.get(0) != 'undefined' && _currentTargetPlayer.get(0).api_handleResize) {

              _currentTargetPlayer.get(0).api_handleResize(null, {
                force_resize_gallery: true
              })
            }
          }, 250);



        }

      }


      prevNr = arg - 1;
      if (prevNr < 0) {
        prevNr = selfClass._sliderCon.children().length - 1;
      }
      nextNr = arg + 1;
      if (nextNr > selfClass._sliderCon.children().length - 1) {
        nextNr = 0;
      }


      if (o.settings_mode == 'normal') {
        _currentTargetPlayer.css('display', '');
      }
      if (o.settings_mode == 'rotator3d') {
        selfClass._sliderCon.children().removeClass('nextItem currItem hide-preview-img').removeClass('prevItem');
        selfClass._sliderCon.children().eq(nextNr).addClass('nextItem');
        selfClass._sliderCon.children().eq(prevNr).addClass('prevItem');
      }
      if (o.settings_mode == 'rotator') {

        if (currNr > -1) {

        }
        var _descCon = _navMain.children('.descriptionsCon');
        _descCon.children('.currDesc').removeClass('currDesc').addClass('pastDesc');
        _descCon.append('<div class="desc">' + _currentTargetPlayer.find('.menuDescription').html() + '</div>');
        setTimeout(function () {
          _descCon.children('.desc').addClass('currDesc');
        }, 20)

      }



      last_arg = arg;


      if (!(o.settings_mode == 'normal' && o.mode_normal_video_mode == 'one')) {

        if (currNr == -1 || transformed) {
          galleryTransition();
          if (o.settings_mode == 'rotator3d') {
            selfClass._sliderCon.children().eq(arg).addClass('hide-preview-img');
          }
        } else {
          cgallery.parent().children('.preloader').fadeIn('fast');

          var delay = 500;

          if (o.settings_mode == 'rotator3d') {
            delay = 10;
            selfClass._sliderCon.children().eq(arg).addClass('currItem');
            setTimeout(function () {

              selfClass._sliderCon.children().eq(arg).addClass('hide-preview-img');
            }, 300);
          }

          inter_start_the_transition = setTimeout(galleryTransition, delay, arg);

        }
      } else {
        isBusyAjax = false;
        isBusyTransition = false;



        currNr = arg;
      }

      calculateDims_secondCon(arg);

      if (o.settings_outerNav) {

        var _c_outerNav = $(o.settings_outerNav);
        _c_outerNav.find('.videogallery--navigation-outer--block ').removeClass('active');
        _c_outerNav.find('.videogallery--navigation-outer--block ').eq(arg).addClass('active');

        _c_outerNav.find('*[data-global-responsive-ratio]').each(function () {
          var _t4 = $(this);

          var rat = Number(_t4.attr('data-global-responsive-ratio'));

          _t4.height(rat * _t4.width());
        })
      }

      if (cgallery.hasClass('responsive-ratio-smooth')) {
        if (!_currentTargetPlayer.attr('data-responsive_ratio')) {
          apiResponsiveRationResize(heightInitial);
        } else {
          $(window).trigger('resize');
        }

      }




      cgallery.removeClass('hide-players-not-visible-on-screen');
      setTimeout(function () {

        cgallery.addClass('hide-players-not-visible-on-screen');
        selfClass._sliderCon.find('.transitioning-in').removeClass('transitioning-in');
        selfClass._sliderCon.find('.transitioning-out').removeClass('transitioning-out');



        var _extraBtns = null;


        if (cgallery.parent().parent().next().hasClass('extra-btns-con')) {
          _extraBtns = cgallery.parent().parent().next();
        }
        if (cgallery.parent().parent().next().next().hasClass('extra-btns-con')) {
          _extraBtns = cgallery.parent().parent().next().next();
        }
        if (_extraBtns) {
          _extraBtns.find('.stats-btn').attr('data-playerid', $currVideoPlayer.attr('data-player-id'));

        }
      }, 400);
      firsttime = false;
      isBusyTransition = true;


      if (o.settings_mode == 'normal' && o.mode_normal_video_mode == 'one') {
        return false;
      }


      return true;
    }


    function galleryTransition() {
      if (isTransitionStarted) {
        return;
      }

      var arg = last_arg;


      var _c = selfClass._sliderCon.children().eq(arg);

      isTransitionStarted = true;
      clearTimeout(inter_start_the_transition);
      cgallery.parent().children('.preloader').fadeOut('fast');


      selfClass._sliderCon.children().removeClass('currItem');

      if (currNr === -1) {
        _c.addClass('currItem');
        _c.addClass('no-transition');
        setTimeout(function () {
          selfClass._sliderCon.children().eq(currNr).removeClass('no-transition')
        })
      } else {

        if (currNr !== arg) {

          selfClass._sliderCon.children().eq(currNr).addClass('transition-slideup-gotoTop')
        } else {

          selfClass._sliderCon.children().eq(currNr).addClass('currItem');
        }


      }

      setTimeout(setCurrVideoClass, 100);
      selfClass.$navigationItemsContainer.children('.dzs-navigation--item').removeClass('active');
      selfClass.$navigationItemsContainer.children('.dzs-navigation--item').eq(arg).addClass('active');

      if (o.nav_type === 'thumbs' || o.nav_type === 'scroller' || o.nav_type === 'thumbsandarrows') {

        selfClass.$navigationItemsContainer.children('.dzs-navigation--item').removeClass('active');
        selfClass.$navigationItemsContainer.children('.dzs-navigation--item').eq(arg).addClass('active');
      }



      setTimeout(function () {
        $('window').trigger('resize');
        selfClass._sliderCon.children().removeClass('transition-slideup-gotoTop');
      }, 1000);

      if (helpersDZSVG.is_ios() && currNr > -1) {
        if (selfClass._sliderCon.children().eq(currNr).children().eq(0).length > 0 && selfClass._sliderCon.children().eq(currNr).children().eq(0)[0] !== undefined) {
          if (selfClass._sliderCon.children().eq(currNr).children().eq(0)[0].tagName === 'VIDEO') {
            selfClass._sliderCon.children().eq(currNr).children().eq(0).get(0).pause();
          }
        }
      }

      if (first_transition) {

        video_stopCurrentVideo({
          'called_from': 'the_transition'
        });
      }

      if (currNr > -1) {


        first_transition = true;


      }
      currNr = arg;

      setTimeout(function () {

        isBusyTransition = false;
        isTransitionStarted = false;
        hideAllVideosButCurrentVideo();
      }, 400);
    }
    // -- end the_transition()


    function calculateDims_secondCon(arg) {


      if (o.settings_secondCon) {

        var _c = $(o.settings_secondCon);


        _c.find('.item').removeClass('active');
        _c.find('.item').eq(arg).addClass('active');
        _c.find('.dzsas-second-con--clip').css({
            'height': _c.find('.item').eq(arg).outerHeight(false)
            , 'left': -(arg * 100) + '%'
          }
        );


      }
    }

    function hideAllVideosButCurrentVideo() {
      if (o.settings_mode === 'normal') {

        selfClass._sliderCon.children().each(function () {
          var _t = $(this);

          if (_t.hasClass('currItem') == false) {
            _t.hide();
          }
        })
      }
    }


    function setCurrVideoClass() {

      if ($currVideoPlayer) {

        $currVideoPlayer.addClass('currItem');
      }
    }


    function play_currVideo() {

      if (selfClass._sliderCon.children().eq(currNr).get(0) && selfClass._sliderCon.children().eq(currNr).get(0).externalPauseMovie) {
        selfClass._sliderCon.children().eq(currNr).get(0).api_playMovie({
          'called_from': 'api_playMovie'
        });
      }
    }

    function video_stopCurrentVideo(pargs) {

      var margs = {
        'called_from': 'default'
      }

      if (pargs) {
        margs = $.extend(margs, pargs);
      }

      if (!(helpersDZSVG.is_ios()) && currNr > -1 && o.mode_normal_video_mode !== 'one') {
        if (selfClass._sliderCon.children().eq(currNr).get(0) && selfClass._sliderCon.children().eq(currNr).get(0).externalPauseMovie) {
          selfClass._sliderCon.children().eq(currNr).get(0).externalPauseMovie({
            'called_from': 'external_handle_stopCurrVideo() - ' + margs.called_from
          });
        }
      }
    }


    function click_embedhandle() {
      if (embed_opened === false) {
        selfClass.$galleryButtons.find('.embed-button .contentbox').animate({
          'right': 60
        }, {queue: false, duration: ConstantsDzsvg.ANIMATIONS_DURATION});

        selfClass.$galleryButtons.find('.embed-button .contentbox').fadeIn('fast');
        embed_opened = true;
      } else {
        selfClass.$galleryButtons.find('.embed-button .contentbox').animate({
          'right': 50
        }, {queue: false, duration: ConstantsDzsvg.ANIMATIONS_DURATION});

        selfClass.$galleryButtons.find('.embed-button .contentbox').fadeOut('fast');
        embed_opened = false;
      }
    }

    function click_sharehandle() {
      if (share_opened === false) {
        selfClass.$galleryButtons.find('.share-button .contentbox').animate({
          'right': 60
        }, {queue: false, duration: ConstantsDzsvg.ANIMATIONS_DURATION});

        selfClass.$galleryButtons.find('.share-button .contentbox').fadeIn('fast');
        share_opened = true;
      } else {
        selfClass.$galleryButtons.find('.share-button .contentbox').animate({
          'right': 50
        }, {queue: false, duration: ConstantsDzsvg.ANIMATIONS_DURATION});

        selfClass.$galleryButtons.find('.share-button .contentbox').fadeOut('fast');
        share_opened = false;
      }
    }

    function gotoPrev() {

      if (o.playorder === 'reverse') {
        gotoNext();
        return;
      }

      var tempNr = currNr - 1;
      if (tempNr < 0) {
        tempNr = selfClass._sliderCon.children().length - 1;
      }
      gotoItem(tempNr);


    }

    function gotoNext() {

      if (o.playorder === 'reverse') {
        gotoPrev();
        return;
      }

      var goforwardwithnext = true;
      var tempNr = currNr + 1;
      if (tempNr >= selfClass._sliderCon.children().length) {
        tempNr = 0;


        if (o.loop_playlist !== 'on') {
          goforwardwithnext = false;
        }

        if (action_playlist_end) {
          action_playlist_end(cgallery);
        }
      }


      if (goforwardwithnext) {

        // -- we will go forward with next movie
        gotoItem(tempNr);
      }


      if (o.nav_type_auto_scroll === 'on') {
        if (o.nav_type === 'thumbs' || o.nav_type === 'scroller') {


          setTimeout(function () {


            selfClass.Navigation.animate_to_curr_thumb();

          }, 20);
        }
      }
    }

    function handleVideoEnd() {
      // -- cgallery


      if (o.autoplayNext === 'on') {

        gotoNext();
      }


    }


    function rotator3d_handleClickOnPreviewImg(e) {
      var _t = $(this);
      var selectedIndex = _t.parent().parent().children().index(_t.parent());

      if (e) {
        handleHadFirstInteraction(e);
      }

      gotoItem(selectedIndex);
    }

    $.fn.turnNormalscreen = function () {
      $(this).css({
        'position': 'relative'
      })
      selfClass.$sliderMain.css({
        'position': 'relative'
      })
      for (i = 0; i < nrChildren; i++) {
        selfClass._sliderCon.children().eq(i).css({
          'position': 'absolute'
        })
      }
    }
  }
}


function apply_videogallery_plugin($) {
  $.fn.vGallery = function (argOptions) {

    var finalOptions = {};
    var defaultOptions = Object.assign({}, require('../configs/_playlistSettings').default_opts);
    finalOptions = helpersDZSVG.convertPluginOptionsToFinalOptions(this, defaultOptions, argOptions);
    this.each(function () {

      var _vg = new DzsVideoGallery(this, finalOptions, $);
      return this;
    }); // end each

  }


  window.dzsvg_init = function (selector, settings) {
    if (typeof (settings) != "undefined" && typeof (settings.init_each) != "undefined" && settings.init_each === true) {
      if (Object.keys(settings).length === 1) {
        settings = undefined;
      }
      $(selector).each(function () {
        var _t = $(this);
        _t.vGallery(settings)
      });
    } else {
      $(selector).vGallery(settings);
    }
  };
  // -- deprecated
  window.zsvg_init = function (selector, settings) {
    $(selector).vGallery(settings);
  };
}

exports.apply_videogallery_plugin = apply_videogallery_plugin;