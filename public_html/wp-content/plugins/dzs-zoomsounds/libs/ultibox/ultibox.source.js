// -- DZS Ultibox
// @version 1.23
// @this is not free software
// -- DZS Ultibox == copyright == http://digitalzoomstudio.net


"use strict";

const ultiboxHelpers = require('./jsinc/_ultibox_helpers');

Object.size = function (obj) {
  var size = 0, key;
  for (key in obj) {
    if (obj.hasOwnProperty(key)) size++;
  }
  return size;
};
if (window.jQuery == undefined) {
  console.log("jquery not detected ? ");
  setTimeout(()=>{
    ultiboxHelpers.init_jquery_helpers();
  },500);
}else{
  ultiboxHelpers.init_jquery_helpers();
}

window.dzstaa_self_options = {};


window.dzsulb_inited = false;


Math.easeIn = function (t, b, c, d) {

  return -c * (t /= d) * (t - 2) + b;

};



(function ($) {


  var svg_close_btn = ultiboxHelpers.svg_close_btn;


  var svg_right_arrow = ultiboxHelpers.svg_right_arrow;



  var _maincon = null
    , _boxMainsCon = null
    , _galleryClipCon = null
    , _galleryItemsCon = null
    , _boxMain = null
    , _boxMainMediaCon = null
    , _boxMainMedia = null
    , _boxMainRealMedia = null // -- temp, the real media
    , _boxMainUnder = null
  ;


  var id_main = '';

  var media_ratio_w_h = 0
    , media_w = 0
    , media_h = 0
    , media_finalw = 0
    , media_finalh = 0
    , media_has_under_description = false

    , opts_max_width = 0


    , currNr_gal = -1

    , bmc_w = 0 // -- box-mains-con width
    , bmc_h = 0 // -- box-mains-con height

    , scaling = 'proportional' // -- proportional or fill

    , ww = 0
    , wh = 0

    , gallery_setup = '' // -- the gallery curently setup

    , $ultibox_items_arr = []
    , theurl = window.location.href
  ;


  var lastargs = null
    , lastlastargs = null
  ;

  var padding_hor = 30
    , padding_ver = 30
    , offset_v = 30
  ;



  var inter_calculate_dims_light = 0;


  // Starting time and duration.



  var _inline_content_orig_parent = null
    , _inline_content_orig_prev = null
    , _inline_content_orig_parent_last = null
    , _inline_content_orig_prev_last = null
  ;


  var func_callback = null
  ;


  var ultibox_curr_margs = {};
  var ultibox_options = {

    'transition': 'slideup'
    , 'transition_out': 'same-as-in'
    , 'skin': 'skin-default'
    , settings_deeplinking: "on"
    , nav_mode: "thumbs" // -- thumbs or none
    , settings_enable_arrows: "auto"
    , extra_classes: ""
    , gallery_type: "skin-default"
    , videoplayer_settings: {
      'autoplay': 'off'
      , 'design_skin': 'skin_reborn'
      , settings_youtube_usecustomskin: 'on'
      , 'cue': 'on'
    }
    , audioplayer_settings: {}


  };

  var svg_play = '<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="13.75px" height="12.982px" viewBox="0 0 13.75 12.982" enable-background="new 0 0 13.75 12.982" xml:space="preserve"> <path d="M11.889,5.71L3.491,0.108C3.389,0.041,3.284,0,3.163,0C2.834,0,2.565,0.304,2.565,0.676H2.562v11.63h0.003 c0,0.372,0.269,0.676,0.597,0.676c0.124,0,0.227-0.047,0.338-0.115l8.389-5.595c0.199-0.186,0.326-0.467,0.326-0.781 S12.088,5.899,11.889,5.71z"/> </svg>';

  var _body = $('body').eq(0);
  var _html = $('html').eq(0);

  window.dzsulb_main_init = dzsulb_main_init;

  function dzsulb_main_init() {


    if (_maincon) {
      return false;
    }

    _body = $('body').eq(0);
    _html = $('html').eq(0);


    if (window.ultibox_options_init) {
      ultibox_options = $.extend(ultibox_options, window.ultibox_options_init);
    }












    offset_v = ultibox_options.offset_v;





    var aux = '<div class="dzsulb-main-con ' + ultibox_options.skin + ' ' + ultibox_options.extra_classes + ' gallery-' + ultibox_options.gallery_type + '">';


    if (ultibox_options.skin == 'skin-default') {

      if (ultibox_options.settings_enable_arrows == 'auto') {

      }
    }

    aux += '<div class="overlay-background"></div>';

    aux += '<div class="dzsulb-preloader preloader-fountain" > <div id="fountainG_1" class="fountainG"></div> <div id="fountainG_2" class="fountainG"></div> <div id="fountainG_3" class="fountainG"></div> <div id="fountainG_4" class="fountainG"></div> </div>';

    aux += '<div class="box-mains-con">';

    aux += '</div><!-- end .box-mains-con-->';


    if (ultibox_options.nav_mode == 'thumbs') {

      aux += '<div class="gallery-clip-con"><div class="gallery-items-con">';

      aux += '</div></div><!-- end .gallery-clip-con-->';
    }


    aux += '</div>';


    _body.append(aux);



    _maincon = _body.children('.dzsulb-main-con').eq(0);
    _boxMainsCon = _maincon.find('.box-mains-con').eq(0);
    _galleryClipCon = _maincon.find('.gallery-clip-con').eq(0);
    _galleryItemsCon = _maincon.find('.gallery-items-con').eq(0);

    if (ultibox_options.transition == 'default') {
      ultibox_options.transition = 'fade';
    }
    if (ultibox_options.transition_out == 'same-as-in') {
      ultibox_options.transition_out = ultibox_options.transition;
    }


    _maincon.addClass('transition-' + ultibox_options.transition);


    _maincon.on('click', '>.overlay-background, .close-btn-con, .ultibox-close-btn, .gallery-items-con > .gallery-thumb, .ultibox-gallery-arrow,.ultibox-prev-btn,.ultibox-next-btn', handle_mouse);
    _maincon.on('wheel', '.box-main.scroll-mode,.gallery-items-con.scroll-mode', handle_scroll);


    check_deeplink();



    window.close_ultibox = close_ultibox;


    window.api_ultibox_set_callback_func = function (argo) {
      func_callback = argo;
    };


    $(window).on('resize', handle_resize)
    handle_resize();

  }

  function check_deeplink() {
    if (theurl.indexOf('ultibox=') > -1) {

      if (get_query_arg(theurl, 'ultibox')) {
        var tempNr = parseInt(get_query_arg(theurl, 'ultibox'), 10);


        if (String(tempNr) != 'NaN') {
          // -- if it is a number
          if (tempNr > -1) {

            // -- lets see if we have it .. if we don t


            if ($('.ultibox-item,.ultibox-item-delegated').eq(tempNr).length) {

              open_ultibox($('.ultibox-item,.ultibox-item-delegated').eq(tempNr), null, {
                from_deeplink: tempNr
                , call_from: 'check_deeplink'
              });
            } else {

              setTimeout(function () {
                open_ultibox($('.ultibox-item,.ultibox-item-delegated').eq(tempNr), null, {
                  from_deeplink: tempNr
                  , call_from: 'check_deeplink'
                });

              }, 1500);
            }
            // -- let us try again

          }
        } else {

          var auxobj = $('#' + get_query_arg(theurl, 'ultibox'));


          open_ultibox(auxobj, null, {
            from_deeplink: '#' + get_query_arg(theurl, 'ultibox')
            , call_from: 'check_deeplink'
          });
        }
      }

    }
  }

  function handle_scroll(e) {


    var _t = $(this);





    // -- this is where the scrolling happens
    if (_t.hasClass('box-main')) {

      var ch = wh;
      var th = _boxMain.children('.box-main-media-con').eq(0).outerHeight();

      var auxY = parseInt(_boxMain.css('top')) + Number(e.originalEvent.wheelDelta) * 10;


      if (auxY > offset_v) {
        auxY = offset_v;
      }
      if (auxY < ch - th - offset_v - (wh - _boxMain.parent().outerHeight())) {
        auxY = ch - th - offset_v - (wh - _boxMain.parent().outerHeight());
      }


      _boxMain.css({

      })
    }
    if (_t.hasClass('gallery-items-con')) {

      var cw = ww;
      var tw = _galleryItemsCon.outerWidth();

      var auxX = parseInt(_galleryItemsCon.css('left')) + Number(e.originalEvent.wheelDelta) * 10;


      if (auxX > 30) {
        auxX = 30;
      }
      if (auxX < cw - tw - 30) {
        auxX = cw - tw - 30;
      }


      _galleryItemsCon.css({
        'left': auxX
      })
    }


  }

  function handle_mouse(e) {


    var _t = $(this);

    if (e.type === 'click') {



      if (_t.hasClass('overlay-background')) {

        close_ultibox();

      }
      if (_t.hasClass('close-btn-con') || _t.hasClass('ultibox-close-btn')) {

        close_ultibox();

      }

      if (_t.hasClass('gallery-thumb')) {

        var ind = _t.parent().children().index(_t);



        goto_gallery_item(ind);

      }

      if (_t.hasClass('ultibox-gallery-arrow--left')) {

        goto_gallery_item_prev();

      }

      if (_t.hasClass('ultibox-gallery-arrow--right')) {

        goto_gallery_item_next();

      }


      // -- loaded-item next, .zoomed next
    }

  }

  function handle_mouse_item(e) {


    var _t = $(this);

    if (e.type === 'click') {



      if (_t.hasClass('')) {

      }

      open_ultibox(_t, null);


      // -- loaded-item next, .zoomed next
    }

  }


  function goto_gallery_item_prev() {
    var tempNr = currNr_gal;
    tempNr--;


    var gal_nr_items = 0;

    if (_galleryItemsCon.length) {
      gal_nr_items = _galleryItemsCon.children().length;
    } else {
      gal_nr_items = $('*[data-biggallery="' + ultibox_curr_margs.biggallery + '"]').length;
    }

    if (tempNr < 0) {
      tempNr = gal_nr_items - 1;
    }



    goto_gallery_item(tempNr);

    return false;
  }

  function goto_gallery_item_next() {
    var tempNr = currNr_gal;
    tempNr++;


    var gal_nr_items = 0;

    if (_galleryItemsCon.length) {
      gal_nr_items = _galleryItemsCon.children().length;
    } else {
      gal_nr_items = $('*[data-biggallery="' + ultibox_curr_margs.biggallery + '"]').length;
    }


    if (tempNr >= gal_nr_items) {
      tempNr = 0;
    }



    goto_gallery_item(tempNr);


    return false;
  }


  function goto_gallery_item(arg) {

    var _c = null;
    var gallery_selection_mode = 'gallery-items';


    if (_galleryItemsCon.length && _galleryItemsCon.children().length) {


      _c = _galleryItemsCon.children().eq(arg);

      gallery_selection_mode = 'gallery-items';
    } else {





      _c = $('*[data-biggallery="' + ultibox_curr_margs.biggallery + '"]').eq(arg);

      gallery_selection_mode = 'this is the item';
    }




    if (_c) {

    }


    if (currNr_gal > -1) {
      if (arg < currNr_gal) {

        _maincon.addClass('gallery-direction-reverse');
      }

      if (arg === currNr_gal) {
        return false;
      }
    }


    // -- if we have _c parent-item property
    if (_c) {

      window.ultibox_countdown = false;

      if (gallery_selection_mode === 'gallery-items') {

        if (_c.data('parent-item')) {
          open_ultibox(_c.data('parent-item'), {
            'call_from': 'gallery_item'
          })
        }


      }

      if (gallery_selection_mode === 'this is the item') {


        open_ultibox(_c, {
          'call_from': 'gallery_item'
        })


      }

      currNr_gal = arg;


      restore_target_div();


      if (_galleryItemsCon) {

        _galleryItemsCon.children().removeClass('active');
        setTimeout(function () {
          _galleryItemsCon.children().eq(currNr_gal).addClass('active');
        }, 100)
      }

    }

  }


  window.ultibox_reset_cooldown = function () {

  }
  /**
   * main open ultibox
   * @param _arg
   * @param pargs
   * @returns {boolean}
   */
  window.open_ultibox = function (_arg, pargs) {


    var margs = {

      type: 'detect'
      , video_type: 'detect'
      , audio_type: 'detect'
      , audio_thumb: ''
      , source: ''
      , max_width: 'default' // -- this is useful for under description feed and is mandatory actually
      , under_description: '' // -- this is the under description
      , right_description: '' // -- this is the under description
      , scaling: 'proportional' // -- this is the under description
      , inline_content_move: 'off'
      , suggested_width: ''
      , suggested_height: ''
      , box_bg: ''
      , biggallery: ''
      , call_from: 'default'
      , forcenodeeplink: 'off'
      , _targetDiv: null
      , item: null // -- we can pass the items from here too

    };


    if (pargs) {
      margs = $.extend(margs, pargs);
    }




    if (window.ultibox_countdown) {
      return false;

    }
    window.ultibox_countdown = true;
    setTimeout(function () {
      window.ultibox_reset_cooldown();
    }, 100);



    let suggestedMediaType = 'image';
    if (_arg) {


      if (_arg.attr('data-source')) {
        margs.source = _arg.attr('data-source');
      } else {
        if (_arg.attr('data-src')) {
          margs.source = _arg.attr('data-src');
        }else{
          if (_arg.attr('data-sourcevp')) {
            // -- if it's video we might be looking for data-sourcevp
            margs.source = _arg.attr('data-sourcevp');
            suggestedMediaType = 'video';
          }else{
            if(_arg.attr('href')){
              margs.source = _arg.attr('href');
              suggestedMediaType = 'iframe';
            }
          }
        }
      }
      if (_arg.attr('data-type')) {
        margs.type = _arg.attr('data-type');


        if (margs.type === 'vimeo') {
          margs.type = 'video';
          margs.video_type = 'vimeo';
        }
        if (margs.type === 'youtube') {
          margs.type = 'video';
          margs.video_type = 'youtube';
        }
      } else {
        margs.type = ultiboxHelpers.detect_ultibox_media_type(margs.source, suggestedMediaType);
      }

      if (_arg.attr('data-scaling')) {
        margs.scaling = _arg.attr('data-scaling');
      }
      if (_arg.attr('data-box-bg')) {
        margs.box_bg = _arg.attr('data-box-bg');
      }
      if (_arg.attr('data-audio-thumb')) {
        margs.audio_thumb = _arg.attr('data-audio-thumb');
      }
      if (_arg.attr('data-inline-move')) {
        margs.inline_content_move = _arg.attr('data-inline-move');
      }

      if (_arg.next().hasClass('feed-ultibox-desc') || _arg.children().hasClass('feed-ultibox-desc')) {

        var _c = null;
        if (_arg.next().hasClass('feed-ultibox-desc')) {
          _c = _arg.next();
        }
        if (_arg.children('.feed-ultibox-desc').length) {
          _c = _arg.children('.feed-ultibox-desc').eq(0);
        }

        margs.under_description = _c.html();
      }


      if (_arg.attr('data-suggested-width')) {
        margs.suggested_width = (_arg.attr('data-suggested-width'));
      }
      if (_arg.attr('data-force-nodeeplink')) {
        margs.forcenodeeplink = (_arg.attr('data-force-nodeeplink'));
      }
      if (_arg.attr('data-suggested-height')) {
        margs.suggested_height = (_arg.attr('data-suggested-height'));
      }

      if (typeof _arg != 'string') {
        margs.item = _arg;
      }


      if (_arg.attr('data-biggallery')) {
        margs.biggallery = _arg.attr('data-biggallery');
      }
    }




    if (margs.type === 'video') {
      if (margs.video_type === 'detect') {


        if (margs.item && margs.item.attr('data-video-type')) {
          margs.video_type = margs.item.attr('data-video-type');
        }
      }

      if (margs.video_type === 'detect') {
        if (margs.source.indexOf('youtube.com/') > -1) {


          margs.video_type = 'youtube';

          margs.source = get_query_arg(margs.source, 'v');


        }
      }
      if (margs.video_type === 'detect') {

        margs.video_type = 'video';
      }

    }

    if (margs.type === 'audio') {

      if (margs.audio_type === 'detect') {

        margs.audio_type = 'audio';
      }

    }

    if (margs.type === 'inlinecontent') {
      margs._targetDiv = $(margs.source).eq(0);

    }




    if (margs.under_description) {
      if (margs.max_width === 'default') {
        margs.max_width = 400;
      }
    }


    if (margs.biggallery) {
    }


    ultibox_curr_margs = $.extend({}, margs);


    _maincon.removeClass('disabled');
    _html.addClass('ultibox-opened');


    setTimeout(function () {

      _maincon.addClass('loading-item');


      if (margs.type === 'image') {




        var newImg = new Image;
        newImg.onload = function () {




          media_w = this.naturalWidth;
          media_h = this.naturalHeight;


          setup_media(margs);


        };
        newImg.src = margs.source;
      }

      if (margs.type === 'video') {



        media_w = 800;
        media_h = 454;

        if (margs.video_type === 'video' || margs.video_type === 'youtube' || margs.video_type === 'vimeo') {
          if ($.fn.vPlayer) {

            setup_media(margs);
          } else {

            console.warn("You need videogallery embedded");
            close_ultibox();
          }
        }

      }

      if (margs.type === 'audio') {



        media_w = 800;
        media_h = 'auto';

        if (margs.audio_type === 'audio') {
          if ($.fn.audioplayer) {

            setup_media(margs);
          } else {

            console.warn("You need zoomsounds embedded");
            close_ultibox();
          }
        }

      }

      if (margs.type === 'iframe') {



        media_w = 800;
        media_h = 600;

        setup_media(margs);
      }

      if (margs.type === 'inlinecontent') {



        media_w = 800;
        media_h = 'auto';

        setup_media(margs);
      }
    }, 100);


    if (ultibox_options.settings_deeplinking === 'on' && can_history_api() === true && margs.forcenodeeplink !== 'on') {


      $ultibox_items_arr = $('.ultibox-item,.ultibox-item-delegated')
      if (margs.item && margs.item.attr('data-ultibox-sort')) {

      }

      var ind = $ultibox_items_arr.index(margs.item);
      if (typeof ($(margs.item).attr('id')) != 'undefined') {




        var aux = encodeURIComponent($(margs.item).attr('id'));
        aux = aux.replace(/%/g, "8767");
        ind = aux;
      }


      theurl = window.location.href;
      var newurl = add_query_arg(theurl, 'ultibox', ind);
      if (newurl.indexOf(' ') > -1) {
        newurl = newurl.replace(' ', '%20');
      }
      theurl = newurl;
      //console.info(theurl);
      history.pushState({}, "", newurl);
    }
  }


  function setup_media(margs) {
    // -- appends the item to the DOM but does not necesarrly append the loaded event , that is appended only when the media is ( allegedly )

    // console.info('setup_media()', margs);


    if (margs.suggested_width) {
      if (isNaN(Number(margs.suggested_width)) === false) {

        media_w = Number(margs.suggested_width);
      } else {
        media_w = margs.suggested_width;
      }
    }
    if (margs.suggested_height) {
      if (isNaN(Number(margs.suggested_height)) === false) {

        media_h = Number(margs.suggested_height);
      } else {
        media_h = margs.suggested_height;
      }
    }


    if (isNaN(Number(margs.suggested_height)) === false) {
      media_ratio_w_h = media_w / media_h;
    } else {
      media_ratio_w_h = 1;
    }
    scaling = margs.scaling;


    var structureBoxMain = '';


    if (margs.call_from == 'gallery_item') {
      _boxMain.addClass('gallery-transitioning-out');
    } else {
      if (_boxMain) {

        _boxMain.addClass('transitioning-out');
      }
    }

    structureBoxMain += '<div class="box-main type-' + margs.type;

    if (margs.call_from == 'gallery_item') {
      structureBoxMain += ' gallery-preparing-transitioning-in';

      setTimeout(function () {
        _boxMain.addClass('gallery-transitioning-in')
      }, 10)
      setTimeout(function () {
        _maincon.find('.box-main.gallery-transitioning-out').remove();
        _boxMain.removeClass('gallery-transitioning-in')
        _boxMain.removeClass('gallery-preparing-transitioning-in')
        _maincon.removeClass('gallery-direction-reverse')
      }, 500)


    }


    structureBoxMain += '">';


    structureBoxMain += '<div class="box-main-media-con transition-target">';


    structureBoxMain += '<div class="close-btn-con"> ' + svg_close_btn + '</div>';

    structureBoxMain += '<div class="box-main-media type-' + margs.type + '" style="';

    if (margs.box_bg) {
      structureBoxMain += 'background-color: ' + margs.box_bg + ';';
    }

    structureBoxMain += '"></div><div class="box-main-under"></div>';


    // debugger;
    if (ultibox_options.settings_enable_arrows === 'on') {
      structureBoxMain += '<div class="ultibox-gallery-arrow ultibox-gallery-arrow--left">' + svg_right_arrow + '</div>';
      structureBoxMain += '<div class="ultibox-gallery-arrow ultibox-gallery-arrow--right">' + svg_right_arrow + '</div>';


    }


    structureBoxMain += '</div></div>';

    _boxMainsCon.append(structureBoxMain);


    _boxMain = _maincon.find('.box-main:not(.gallery-transitioning-out)').eq(0);
    _boxMainMediaCon = _boxMain.find('.box-main-media-con').eq(0);
    _boxMainMedia = _boxMain.find('.box-main-media').eq(0);
    _boxMainUnder = _boxMain.find('.box-main-under').eq(0);


    // console.info('_boxMain - ',_boxMain);
    // console.info('_boxMainMediaCon - ',_boxMainMediaCon);
    // console.info('_boxMainMedia - ',_boxMainMedia);
    // console.info('_boxMainUnder - ',_boxMainUnder);

    if (margs.type === 'image') {
      _boxMainMedia.append('<div class="imagediv real-media" style="background-image: url(' + margs.source + ') "></div>');
      setTimeout(function () {

        media_ready(margs);
      }, 50);

    }

    if (margs.type === 'video') {


      // console.info('( type is video ) margs.video_type - ', margs.video_type);
      if (ultibox_options.videoplayer_settings.settings_youtube_usecustomskin === 'off' && (margs.video_type === 'youtube' || margs.video_type === 'vimeo')) {


        if (margs.video_type === 'youtube') {
          _boxMainMedia.append('<iframe class="real-media" width="100%" height="100%" src="https://www.youtube.com/embed/' + margs.source + '" frameborder="0" allowfullscreen allow="fullscreen"></iframe>');
        }
        if (margs.video_type === 'vimeo') {
          _boxMainMedia.append('<iframe src="https://player.vimeo.com/video/' + margs.source + '" width="100%" height="100%" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen allow="fullscreen"></iframe>');
        }


      } else {
        if (margs.video_type === 'video' || margs.video_type === 'youtube' || margs.video_type === 'vimeo') {
          if ($.fn.vPlayer) {


            var video_title = '';

            if (margs.item && margs.item.attr('data-videotitle')) {
              video_title = margs.item.attr('data-videotitle');
            }


            console.info('margs.source - ', margs.source, ' margs.item -> ', margs.item);

            var aux_str_videoplayer = '<div class="vplayer-tobe auto-init   real-media " data-videoTitle="' + video_title + '"  data-src="' + margs.source + '"';


            aux_str_videoplayer += ' data-type="' + margs.video_type + '"'

            aux_str_videoplayer += '></div>';

            _boxMainMedia.append(aux_str_videoplayer);


            var args = {
              'autoplay': 'off'
              , 'cue': 'on'
            };

            if (ultibox_options.videoplayer_settings) {
              args = $.extend(args, ultibox_options.videoplayer_settings);
            }

            var autoplay_it = args.autoplay;

            // args.autoplay = 'off';
            // _boxMainMedia.find('.real-media').eq(0).vPlayer(args);


            if (margs.item && margs.item.attr('data-player-id')) {
              _boxMainMedia.find('.real-media').eq(0).attr('data-player-id', margs.item.attr('data-player-id'));
            }

            console.info('%c videoplayer_settings - ', ' color: #da2133;', args, ultibox_options.videoplayer_settings);

            window.dzsvp_init(_boxMainMedia.find('.real-media').eq(0), args);


            // if(autoplay_it=='on'){
            //
            //     setTimeout(function(){
            //         _boxMainRealMedia.get(0).api_playMovie();
            //     },300)
            // }
          }
        }

      }


      // _boxMainMedia.append('<div class="imagediv real-media" style="background-image: url('+margs.source+') "></div>');
      setTimeout(function () {

        _maincon.addClass('loaded-item');
      }, 50);


    }


    if (margs.type === 'audio') {

      if (margs.audio_type === 'youtube') {
        _boxMainMedia.append('<iframe class="real-media" width="100%" height="100%" src="https://www.youtube.com/embed/' + margs.source + '" frameborder="0" allowfullscreen></iframe>');
      }


      if (margs.audio_type === 'audio') {
        if ($.fn.audioplayer) {

          var structAudioPlayer = '<div class="audioplayer-tobe skin-wave real-media  button-aspect-noir button-aspect-noir--filled "   data-source="' + margs.source + '" ';


          if (margs.audio_thumb) {
            structAudioPlayer += ' data-thumb="' + margs.audio_thumb + '"'
          }

          structAudioPlayer += '></div>';


          _boxMainMedia.append(structAudioPlayer);




          var argsApSettings = $.extend({
            'autoplay': 'off'
            , 'cue': 'on'
            , skinwave_mode: 'small'
          }, ultibox_options.audioplayer_settings);

          _boxMainMedia.find('.real-media').eq(0).audioplayer(argsApSettings);

          setTimeout(function () {
            _boxMainRealMedia.get(0).api_play_media();
          }, 300)
        }
      }

      // _boxMainMedia.append('<div class="imagediv real-media" style="background-image: url('+margs.source+') "></div>');
      setTimeout(function () {

        media_ready(margs);
      }, 50);


    }
    if (margs.type === 'iframe') {
      _boxMainMedia.append('<div class=" real-media" style=""><iframe class="ultibox--real-media--iframe" src="' + margs.source + '" style="" width="100%" height="100%"></iframe></div>');

      setTimeout(function () {
        media_ready(margs);

      }, 1500);

      // -- we leave 1500 ms time to load any iframe

    }
    if (margs.type === 'inlinecontent') {


      // console.info('_boxMainMedia - ',_boxMainMedia);
      _boxMainMedia.append('<div class=" real-media" style=""></div>');


      _inline_content_orig_prev = null;
      _inline_content_orig_parent = null;

      //console.warn('margs._targetDiv.prev() - ',margs._targetDiv.prev());

      if (margs.inline_content_move === 'on') {

        if (margs._targetDiv.prev().length > 0) {
          _inline_content_orig_prev = margs._targetDiv.prev();
        } else {

          _inline_content_orig_parent = margs._targetDiv.parent();
        }
      }


      //console.warn('margs._targetDiv - ',margs._targetDiv);
      // console.warn('_inline_content_orig_prev - ',_inline_content_orig_prev);
      // console.warn('margs.inline_content_move - ',margs.inline_content_move);


      if (margs.inline_content_move === 'on') {
        _boxMainMedia.find('.real-media').append(margs._targetDiv);
      } else {
        _boxMainMedia.find('.real-media').append(margs._targetDiv.clone());
      }


      if (margs._targetDiv.hasClass('cancel-inlinecontent-padding')) {
        _boxMainMedia.addClass('cancel-inlinecontent-padding');
      } else {
        _boxMainMedia.removeClass('cancel-inlinecontent-padding');
      }


      if (_boxMainMedia.find('.auto-init-from-ultibox').length) {
        console.info(' 1 2 3');


        if (window.dzsvg_init) {
          _boxMainMedia.find('.videogallery.auto-init-from-ultibox:not(.inited)').each(function () {
            var _t2 = $(this);

            dzsvg_init(_t2, {
              init_each: true,
            });
          })
        }
      }


      _boxMainMedia.find('.toexecute, .to-execute--from-ultibox').each(function () {
        var _t2 = $(this);
        if (_t2.hasClass('executed') === false) {
          eval(_t2.text());
          _t2.addClass('executed');
        }
      });

      setTimeout(function () {

        media_ready(margs);
      }, 200);

      // -- we leave 1500 ms time to load any iframe

    }
    _boxMainRealMedia = _boxMainMedia.find('.real-media').eq(0);


    if (margs.under_description) {


      _boxMainUnder.append(margs.under_description);
      _boxMainMedia.width('100%');
      media_has_under_description = true;
      _boxMain.addClass('with-description');
    } else {

      media_has_under_description = false;

    }


    if (margs.biggallery) {

      if (margs.biggallery !== gallery_setup) {
        // console.info('margs.biggallery - ',margs.biggallery);


        if (ultibox_options.nav_mode !== 'none') {

          _maincon.addClass('has-gallery');
        }


        var i5 = 0;
        $('*[data-biggallery="' + margs.biggallery + '"]').each(function () {
          var _t = $(this);


          // -- we check if
          if (margs.item && margs.item.get && margs.item.get(0)) {
            if (margs.item.get(0) === _t.get(0)) {

              currNr_gal = i5;
            }
          }

          // console.info('currNr_gal from biggallery - ',currNr_gal, '_t - ',_t);
          var thumb_src = '';


          if (_t.attr('data-thumb-for-gallery')) {

          } else {
            if (_t.attr('data-source')) {
              thumb_src = _t.attr('data-source');
            }
            if (_t.get(0) && _t.get(0).nodeName === "IMG") {

              thumb_src = _t.attr('src');
            }
          }

          // console.info('thumb_src - ',thumb_src);


          if (thumb_src) {

            var aux = '<div class="gallery-thumb"><div class="gallery-thumb--image" style="background-image: url(' + thumb_src + ');"></div><div class="gallery-thumb--icon">';


            if (_t.attr('data-type') === 'video' || _t.attr('data-type') === 'audio') {

              aux += svg_play;
            }

            aux += '</div></div>';


            _galleryItemsCon.append(aux);

            //console.info(_t.attr('data-type'));
            _galleryItemsCon.children().last().data('parent-item', _t);
          }

          i5++;

        });

        // -- end for

        gallery_setup = margs.biggallery;


        setTimeout(function () {
          _galleryClipCon.addClass('gallery-loaded');
          //console.log('_galleryItemsCon - ',_galleryItemsCon);
          _galleryItemsCon.children().eq(currNr_gal).addClass('active');
        }, 100)

      } else {

        _galleryClipCon.addClass('gallery-loaded');
      }
    } else {

      _maincon.removeClass('has-gallery');
      _galleryClipCon.removeClass('gallery-loaded');
      gallery_setup = '';
    }


    if (margs.max_width) {
      opts_max_width = margs.max_width;
    } else {
      opts_max_width = 0;
    }

    handle_resize(null, {
      call_calculate_dims_light: false
    })
    calculate_dims_light({
      'call_from': "setup_media"
      , 'calculate_main_con': true

    })

    lastargs = margs;


    //console.info(func_callback);
    if (func_callback) {
      func_callback(margs);
    }


    // -- just want to cancel the default click behaviour on links
    //if (e != undefined && e != null) {
    //    e.preventDefault();
    //}

  }

  function media_ready(margs) {

    _maincon.addClass('loaded-item');

    // console.info('media_ready() - ',margs);

    if (margs.type === 'inlinecontent') {

      // console.info(_boxMainMedia.find('.contentscroller'));
      if (_boxMainMedia.find('.contentscroller').length) {
        _boxMainMedia.find('.contentscroller').each(function () {
          var $t2 = $(this);

          // console.info(_t2);

          if($t2.get(0).api_handleResize){

            $t2.get(0).api_handleResize();
          }
        })
      }


      if (_boxMainMedia.find('.videogallery').length) {
        _boxMainMedia.find('.videogallery').each(function () {
          var $t2 = $(this);

          // console.info(_t2);

          if($t2.get(0).api_handleResize) {
            $t2.get(0).api_handleResize(null, {
              force_resize_gallery: true
            })
          }
        })
      }

      if (_boxMainMedia.find('.ultibox-close-btn').length) {
        _boxMain.find('.close-btn-con').fadeOut('fast');
      } else {

        _boxMain.find('.close-btn-con').fadeIn('fast');
      }


      setTimeout(function () {

        calculate_dims_light();
      }, 500);
    }
  }


  function restore_target_div() {


    //console.info('restore_target_div()',lastargs)


    if (lastargs && lastargs.inline_content_move === 'on') {

      _inline_content_orig_prev_last = _inline_content_orig_prev;
      _inline_content_orig_parent_last = _inline_content_orig_parent;

      //console.info('lastargs._targetDiv - ',lastargs._targetDiv);
      //console.info('_inline_content_orig_prev_last - ',_inline_content_orig_prev_last);
      //console.info('_inline_content_orig_parent_last - ',_inline_content_orig_parent_last);

      lastlastargs = $.extend({}, lastargs);


      setTimeout(function () {


        if (_inline_content_orig_prev_last) {

          _inline_content_orig_prev_last.after(lastlastargs._targetDiv);

        }
        if (_inline_content_orig_parent_last) {

          _inline_content_orig_parent_last.prepend(lastlastargs._targetDiv);

        }


        // -- TODO: maybe resize content scroller
        // lastlastargs._targetDiv.find('.contentscroller').each(function(){
        //     var _t3 = $(this);
        //
        //
        //
        //     _t3.get(0).api_handleResize();
        // })


      }, 300)
    }

  }


  function close_ultibox() {

    // _maincon.removeClass('disabled');


    if(_maincon.find('.show-only-in-ultibox').length === 0) {
      _maincon.removeClass('loading-item');
    }

    _maincon.removeClass('loaded-item');
    _html.removeClass('ultibox-opened');
    _galleryClipCon.removeClass('gallery-loaded');


    if(_maincon.find('.show-only-in-ultibox').length){
      setTimeout(function(){
        _maincon.removeClass('loading-item');
      },500);
    }
    _maincon.addClass('closing-ultibox');


    // -- leave it a bit so we can make sure that the hiding for show-only-in-ultibox is not instant
    setTimeout(function(){
      _maincon.removeClass('loading-item');
      _maincon.removeClass('closing-ultibox');
    },500);

    restore_target_div();


    if (ultibox_options.settings_deeplinking == 'on' && can_history_api() == true) {
      var newurl = add_query_arg(theurl, 'ultibox', "NaN");
      theurl = newurl;
      history.pushState({}, "", newurl);
    }

    setTimeout(function () {

      _maincon.addClass('disabled');

      if (_boxMainRealMedia) {

        _boxMainRealMedia.remove();
      }

      if (_boxMainUnder) {

        _boxMainUnder.html('');
      }

      _boxMainsCon.html('');


      window.ultibox_countdown = false;
    }, 300);
  }

  function handle_resize(e, pargs) {


    var margs = {
      'call_from': 'default'
      , 'call_calculate_dims_light': true
    };

    if (pargs) {
      margs = $.extend(margs, pargs);
    }


    ww = $(window).width();
    wh = window.innerHeight;

    bmc_w = _boxMainsCon.width();
    bmc_h = _boxMainsCon.height();


    // console.info(_boxMainsCon, 'bmc_h - ', bmc_h);

    if (margs.call_calculate_dims_light) {

      if (inter_calculate_dims_light) {
        clearTimeout(inter_calculate_dims_light);
      }
      inter_calculate_dims_light = setTimeout(calculate_dims_light, 100);
    }

    if (_boxMainMedia && _boxMainMedia.hasClass('type-inlinecontent')) {
      // console.info('ceva has type-inlinecontent');


    }

  }


  function calculate_dims_light(pargs) {


    var margs = {
      'call_from': 'default'
      , 'calculate_main_con': true
    };

    if (pargs) {
      margs = $.extend(margs, pargs);
    }


    if (margs.calculate_main_con) {


      // console.info('calculate_dims_light()', media_w, media_h, scaling);

      media_finalw = media_w;
      media_finalh = media_h;

      if (opts_max_width) {
        if (media_finalw > opts_max_width) {
          media_finalw = opts_max_width;

          if (scaling != 'fill') {

            media_finalh = media_finalw / media_ratio_w_h;
          }

          // console.info('media_finalh - ',media_finalh);
        }


      }


      if (media_finalw > bmc_w - padding_hor) {
        media_finalw = bmc_w - padding_hor;
        if (scaling != 'fill') {
          media_finalh = media_finalw / media_ratio_w_h;
        }
      }
      if (media_finalh > bmc_h - padding_ver) {
        //console.warn('media_finalh over limit', media_finalh, media_finalw, media_ratio_w_h);
        media_finalh = bmc_h - padding_ver;
        if (scaling != 'fill') {
          media_finalw = media_finalh * media_ratio_w_h;
        }

      }

      // console.info('calculate_dims_light()', media_finalw, media_finalh, bmc_h - padding_ver);


      if (opts_max_width) {
        if (media_has_under_description) {
          _boxMainMediaCon.width(media_finalw);
        }
      }

      if (_boxMainMedia) {

        if (media_has_under_description) {
          _boxMainMedia.width('100%');
        } else {

          _boxMainMedia.width(media_finalw);
        }

        setTimeout(function () {


          // _boxMainMediaCon.width(200);
        }, 5000);

        _boxMainMedia.height(media_finalh);


        // console.info(_boxMain, _boxMain.outerHeight(), wh);
        _boxMain.css({
          'max-height': 'none'
          , 'height': 'auto'
        })
        if (_boxMain) {

          // console.error(_boxMain, wh);


          setTimeout(function () {

            if (_boxMain.outerHeight() > _boxMain.parent().outerHeight()) { // 0 = padding


              _boxMain.addClass('scroll-mode');

              if (offset_v != 30) {
                _boxMain.css('top', offset_v);
              }

            } else {

              _boxMain.removeClass('scroll-mode');
              _boxMain.css({
                'top': ''
              })
            }
            _boxMain.css({
              'max-height': ''
              , 'height': ''
            })
          }, 100)
        }
        if (_galleryItemsCon) {

          // console.error(_boxMain, wh);

          if (_galleryItemsCon.outerWidth() > ww - 0) { // 0 = padding


            _galleryItemsCon.addClass('scroll-mode');

          } else {

            _galleryItemsCon.removeClass('scroll-mode');
            _galleryItemsCon.css({
              'left': ''
            })
          }
          _galleryItemsCon.css({
            'max-height': ''
            , 'height': ''
          })
        }
      }


    }


  }





  // -- item


  $.fn.dzsulb = function (o) {

    //==default options
    var defaults = {
      settings_slideshowTime: '5' //in seconds
      , settings_enable_linking: 'off' // enable deeplinking on tabs
      , settings_contentHeight: '0'//set the fixed tab height
      , settings_scroll_to_start: 'off'//scroll to start when a tab menu is clicked
      , settings_startTab: 'default'// -- the start tab, default or a fixed number
      , design_skin: 'skin-default' // -- skin-default, skin-boxed, skin-melbourne or skin-blue
      , design_transition: 'default' // default, fade or slide
      , design_tabsposition: 'top' // -- set top, right, bottom or left
      , design_tabswidth: 'default' // -- set the tabs width for position left or right, if tabs position top or bottom and this is set to fullwidth, then the tabs will cover all the width
      , design_maxwidth: '4000'
      , settings_makeFunctional: false
      , settings_appendWholeContent: false // -- take the whole tab content and append it into the dzs tabs, this makes complex scripts like sliders still work inside of tabs
      , toggle_breakpoint: '320' //  -- a number at which bellow the tabs will trasform to toggles
      , toggle_type: 'accordion' // -- normally, the  toggles act like accordions, but they can act like traditional toggles if this is set to toggle
      , refresh_tab_height: '0' // -- normally, the  toggles act like accordions, but they can act like traditional toggles if this is set to toggle
      , outer_menu: null // -- normally, the  toggles act like accordions, but they can act like traditional toggles if this is set to toggle
      , action_gotoItem: null // -- set a external javascript action that happens when a item is selected
      , vc_editable: false // -- add some extra classes for the visual composer frontend edit

    };

//        console.info(this, o);

    if (typeof o == 'undefined') {
      if (typeof $(this).attr('data-options') != 'undefined' && $(this).attr('data-options') != '') {
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

      ;

      




      if (isNaN(Number(o.settings_startTab)) === false) {
        o.settings_startTab = parseInt(o.settings_startTab, 10);
      }

      if (can_history_api() === false) {
        o.settings_enable_linking = 'off';
      }

      o.toggle_breakpoint = parseInt(o.toggle_breakpoint, 10);


      if (window.dzsulb_inited === false) {
        dzsulb_init();
      }


      // -- item


      init();

      // -- init item !
      function init() {


        // console.warn('init() - ', cthis);


        if (cthis.attr('data-source')) {

        } else {
          if (cthis.attr('href')) {
            cthis.attr('data-source', cthis.attr('href'));
          }
        }

        var src = cthis.attr('data-source');
        if (!(cthis.attr('data-type')) || cthis.attr('data-type') == 'detect') {


          //console.info(src,src.indexOf('.mp4'),src.length);
          cthis.attr('data-type', ultiboxHelpers.detect_ultibox_media_type(src));

        } else {

        }


        //console.info('type - ',cthis.attr('data-type'))


        // cthis.off('click');
        cthis.off('click', handle_mouse_item);
        cthis.on('click', handle_mouse_item);

      }








      return this;
    })
  }
  window.dzsulb_init = function (selector, settings) {
    if (typeof (settings) != "undefined" && typeof (settings.init_each) != "undefined" && settings.init_each === true) {
      var element_count = 0;
      for (var e in settings) {
        element_count++;
      }
      if (element_count == 1) {
        settings = undefined;
      }

      $(selector).each(function () {
        var _t = $(this);
        _t.dzsulb(settings)
      });
    } else {
      $(selector).dzsulb(settings);
    }


    // console.info('get_query_arg(window.location.href,\'ultibox\') - ',get_query_arg(window.location.href,'ultibox'));

    if (get_query_arg(window.location.href, 'ultibox') || get_query_arg(window.location.href, 'ultibox') === '0') {

      // console.info("SCROLL TO TOP");
      $(window).scrollTop(0);
    }


  };
})(jQuery);


function can_history_api() {
  return !!(window.history && history.pushState);
}




jQuery(document).ready(function ($) {

  // console.info($('.rst-menu-main-con.auto-init'));

  //console.warn($('.ultibox-item'));
  dzsulb_init('.ultibox-item', {init_each: true});

  window.dzsulb_main_init();


  $(document).off('click', '.ultibox-item-delegated');
  $(document).on('click', '.ultibox-item-delegated', function (e) {
    console.log('opening ultibox item delegated', $(this));
    window.open_ultibox($(this));

    e.stopPropagation();
    e.preventDefault();
    return false;
  });


  // dzsulb_init('.ultibox-item', {init_each: true});


});