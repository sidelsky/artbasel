"use strict";

import {
  ads_view_setupMarkersOnScrub,
  player_setup_skipad,
  player_setupAd
} from "./js_player/_player_setupAd";
import {generatePlayerMarkupAndSource} from "./js_player/_player_setupMedia";
import {init_windowVars} from "./js_dzsvg/_dzsvg_window_vars";

/*
 * Author: Digital Zoom Studio
 * Website: https://digitalzoomstudio.net/
 * Portfolio: https://codecanyon.net/user/ZoomIt/portfolio?ref=ZoomIt
 * This is not free software.
 * Video Gallery
 * Version: 10.76
 */

init_windowVars();
var dzsvp_players_arr = []; // -- an array to hold video players
var dzsvp_yt_iframe_settoload = false;

var helpersSvg = require('./js_dzsvg/_dzsvg_svgs');
var helpersDZSVG = require('./js_dzsvg/_dzsvg_helpers');
var helpersDZS = require('./js_common/_dzs_helpers');
var playerAdFunctions = require('./js_dzsvg/_player_ad_functions');
var videoElementFunctions = require('./js_dzsvg/_video-element-functions');
var _secondCon = require('./js_dzsvg/components/_second_con');



var ConstantsDzsvg = require('./configs/Constants').constants;
setTimeout(function () {

  if (window.dzsvg_settings) {

  }
  if (Object.hasOwnProperty('assign')) {

    window.dzsvg_settings = Object.assign(dzsvg_default_settings, window.dzsvg_settings)
  }
}, 2);


class DzsVideoPlayer {
  constructor(argThis, argOptions, $) {

    this.argThis = argThis;
    this.argOptions = argOptions;
    this.$ = $;

    this.cthis = null;
    this.initOptions = {};
    this.$parentGallery = null; // -- parent dzsvg


    this.id_player = ''; // -- this is set only if the player actually has an id


    this._videoElement = null;
    this._vpInner = null;
    this._fullscreenVideoElement = null; // -- the video element might be different then the video element ( ie. vimeo )
    this._controlsRight = null;
    this._volumeControls = null;
    this._volumeControls_real = null;
    this.$adContainer = null

    // -- dimensions
    this.bufferedWidthOffset = 0;
    this.original_scrubwidth = 0;
    this.volumeWidthOffset = 0;

    this.totalWidth = 0;
    this.totalHeight = 0;
    this.videoWidth = 0;
    this.videoHeight = 0;


    this.currentPlayerId = '';
    this.dataSrc = '';
    this.dataType = ''; // -- type

    this.paused = true;
    this.suspendStateForLoop = false;
    this.wasPlaying = false;
    this.isInitialPlayed = false;
    this.is_muted_for_autoplay = false; // -- is muted only for autoplay purposes
    this.shouldStartMuted = false;
    this.isPartOfAnGallery = false;
    this.isGalleryHasOneVideoPlayerMode = false;
    this.isAd = false;
    this.isHadFirstInteraction = false;
    this.hasCustomSkin = true; // -- has custom skin or the iframe skin

    this.queue_goto_perc = 0; // -- seek to

    this.autoplayVideo = 'off';
    this.youtube_queue_change_quality = 'off';
    this.isLoop = false

    // -- ads
    this.$adSkipCon = null;
    this.ad_status = 'undefined'
    this.ad_link = null;
    this.ad_array = [];
    this.isAdPlaying = false

    this.dash_inter_check_sizes = 0;

    this.vimeo_url = '';

    this.volumeClass = new (require('./js_dzsvg/components/_volume').VolumeControls)(this);
    this.volumeLast = null;
    this.volumeDefault = null;

    this.classMisc = null;
    this.playerOptions = {}; // -- player options

    this.classInit();
  }

  classInit() {


    var cthis
    ;
    var natural_videow = 0
      , natural_videoh = 0
      , last_videoWidth = 0
      , last_videoHeight = 0;
    var video;
    var aux = 0;
    var is_fullscreen = 0;
    var inter_videoReadyState // interval to check for time
      , inter_removeFsControls // interval to remove fullscreen controls when no action is detected
      , inter_mousedownscrubbing = 0 // interval to apply mouse down scrubbing on the video
    ;
    var infoPosX;
    var infoPosY;
    var fScreenControls
      , playcontrols
      , info
      , infotext
      , scrubbar
      , _scrubBg
      , _timetext
      , _btnhd
      , _controlsBackground = null
      , _muteControls = null

    ;
    var played = false
      , mouseover = false // -- the mouse is over the vplayer
      , google_analytics_sent_play_event = false
      , volume_mouse_down = false
      , scrub_mouse_down = false
      , controls_are_hovered = false
      , view_sent = false
      , fullscreen_just_pressed = false
      , play_commited = false // -- this will apply play later on
      , isInited = false
      , isInitedReadyVideo = false
      , vimeo_is_ready = false
    ;

    var scrubbar_moving_x = 0
      , scrubbar_moving = false // -- touch
    ;


    var totalDuration = 0;
    var time_curr = 0;
    var dataOriginalSrc = '';
    var data_sourceVp = '';
    var dataVideoDesc = '';
    var dataOriginalType = ''; // -- type

    var video_title = '';

    //responsive vars
    var _rparent
      , currScale = 1
    ;
    var ww
      , wh
    ;
    var qualities_youtubeVideoQualitiesArray = []
      , qualities_youtubeCurrentQuality
      , hasHD = false
    ;

    var is360 = false;

    var arrTags = [];


    var bufferedLength = -1
      , volumeLength = 0
      , scrubbg_width = 0
    ;
    var lastVideoType = ''
      , inter_checkYtIframeReady = 0
      , inter_clear_playpause_mistake = 0
    ;

    var action_video_end = null
      , action_video_play = null
      , action_video_pause = null
      , action_video_view = null
    ;

    var busy_playpause_mistake = false
    ;

    var _controls_fs_canvas;

    var vimeo_data;


    var inter_10_secs_contor = 0
      , inter_5_secs_contor = 0
      , inter_60_secs_contor = 0
    ;


    var Dzsvg360 = require('./js_dzsvg/_dzsvg_360');


    var $ = this.$;
    var o = this.argOptions;
    cthis = $(this.argThis);
    var selfClass = this;

    selfClass.cthis = cthis;
    selfClass.initOptions = o;


    if (cthis.attr('id')) {
      selfClass.id_player = cthis.attr('id');
    } else {
      if (cthis.attr('data-player-id')) {
        selfClass.id_player = cthis.attr('data-player-id');
      }

    }


    if (typeof cthis.attr('id') != 'undefined' && cthis.attr('id')) {
      selfClass.currentPlayerId = cthis.attr('id');
    } else {
      selfClass.currentPlayerId = 'dzsvp' + parseInt(Math.random() * 10000, 10)
    }

    if (cthis.parent().parent().parent().parent().hasClass('videogallery')) {
      selfClass.$parentGallery = cthis.parent().parent().parent().parent();
    }



    // -- determine autoplay
    selfClass.autoplayVideo = o.autoplay;


    if (o.init_on === 'init') {
      init();
    }
    if (o.init_on === 'scroll') {


      $(window).on('scroll.' + selfClass.currentPlayerId, handle_scroll);
      handle_scroll();
    }


    function init() {
      // -- @order - first function

      classMiscInit();
      // -- external function calls
      selfClass.get_responsive_ratio = (pargs = {}) => {
        helpersDZSVG.player_getResponsiveRatio(selfClass, pargs)
      };
      selfClass.player_user_had_first_interaction = player_user_had_first_interaction;
      selfClass.pauseMovie = pauseMovie;
      selfClass.handleResize = handleResize;
      selfClass.setup_skipad = player_setup_skipad(selfClass);
      selfClass.seek_to_perc = seek_to_perc;
      selfClass.playMovie = playMovie;
      selfClass.playMovie_visual = playMovie_visual;
      selfClass.pauseMovie_visual = pauseMovie_visual;
      selfClass.check_if_ad_must_be_played = () => {
        playerAdFunctions.check_if_ad_must_be_played(selfClass);
      };
      selfClass.check_if_hd_available = selfClass.classMisc.check_if_hd_available;
      selfClass.handleVideoEnd = handleVideoEnd;
      selfClass.volume_setInitial = selfClass.volumeClass.volume_setInitial;
      selfClass.volume_mute = volume_playerMute;
      selfClass.fullscreenHandleChange = handleFullscreenChange;
      selfClass.setupVolume = volume_setupVolumePerc;


      if (cthis.hasClass('vplayer-tobe') || !cthis.hasClass('dzsvp-inited')) {

        cthis.removeClass('vplayer-tobe');
        cthis.addClass('vplayer dzsvp-inited');



        if (o.settings_disableVideoArray !== 'on') {
          dzsvp_players_arr.push(cthis);
        }


        isInited = true;

        $(window).off('scroll.' + selfClass.currentPlayerId);


        // -- get from attr
        helpersDZSVG.playerHandleDeprecatedAttrSrc(cthis);
        if (helpersDZSVG.getDataOrAttr(selfClass.cthis, 'data-sourcevp')) {
          selfClass.dataSrc = helpersDZSVG.getDataOrAttr(selfClass.cthis, 'data-sourcevp');
          dataOriginalSrc = selfClass.cthis.attr('data-sourcevp');
        }

        if (cthis.attr('data-type')) {
          selfClass.dataType = cthis.attr('data-type');
          dataOriginalType = cthis.attr('data-type');
        } else {
          if (o.type) {
            dataOriginalType = o.type;
            selfClass.dataType = o.type;
          }
        }
        // -- get from attr END


        if (selfClass.dataType === 'normal' || selfClass.dataType === 'video') {
          selfClass.dataType = 'selfHosted';
        }


        const videoTypeAndSource = helpersDZSVG.detect_video_type_and_source(selfClass.dataSrc, null, selfClass.cthis);

        if (dataOriginalType === '' || dataOriginalType === 'detect') {
          cthis.attr('data-type', videoTypeAndSource.type);
          selfClass.dataType = videoTypeAndSource.type;
          if (o.playfrom === 'default') {
            if (videoTypeAndSource.playFrom) {
              o.playfrom = videoTypeAndSource.playFrom;
            }
          }
        }

        helpersDZSVG.configureAudioPlayerOptionsInitial(cthis, o, selfClass);

        if (o.action_video_end) {
          action_video_end = o.action_video_end;
        }
        if (o.action_video_view) {
          action_video_view = o.action_video_view;
        }

        if (selfClass.hasCustomSkin) {
          selfClass.cthis.addClass('has-custom-controls');
        }


        selfClass.original_scrubwidth = o.design_scrubbarWidth;




        // -- we do not need the attr anymore.. hide it
        cthis.attr('data-sourcevp', '');
        cthis.data('data-sourcevp', selfClass.dataSrc);
        cthis.data('data-sourcevp-original', dataOriginalSrc);


        if (selfClass.dataType === 'vimeo' || selfClass.dataType === 'youtube' || selfClass.dataType === 'dash') {
          selfClass.dataSrc = videoTypeAndSource.source;
        }


        o.type = selfClass.dataType;


        if (cthis.attr('data-is-360') == 'on') {
          is360 = true;
        }

        if (selfClass.dataType == 'audio') {

          // -- on no circumstance audio can play
          if (helpersDZSVG.is_mobile()) {
            selfClass.autoplayVideo = 'off';
            o.autoplay = 'off';
          }

        }

        cthis.addClass('type-' + selfClass.dataType);

        lastVideoType = selfClass.dataType;

        if (selfClass.dataType == 'vimeo') {
          if (o.vimeo_is_chromeless == 'on') {
            cthis.addClass('vimeo-chromeless');
          }
        }

        if (selfClass.dataType !== 'selfHosted' && selfClass.dataType !== 'video') {
          is360 = false;
        }
        if (cthis.attr('data-adlink')) {
          selfClass.ad_link = cthis.attr('data-adlink');
        }
        _rparent = cthis.parent();


        if (selfClass.dataType === 'youtube') {
          if (!window._global_youtubeIframeAPIReady  && dzsvp_yt_iframe_settoload === false) {
            helpersDZSVG.load_outside_script(ConstantsDzsvg.YOUTUBE_IFRAME_API);
            dzsvp_yt_iframe_settoload = true;
          }
        }

        if (selfClass.dataType === 'inline') {

          if (o.htmlContent !== '') {
            cthis.html(o.htmlContent);
          }

          if (cthis.children().length > 0) {
            var _cach = cthis.children().eq(0);
            if (_cach.get(0)) {
              if (_cach.get(0).nodeName == 'IFRAME') {
                _cach.attr('width', '100%');
                _cach.attr('height', '100%');
              }
            }
          }
        }


        if (selfClass.isAd) {

          if (selfClass.dataType == 'youtube' && helpersDZSVG.is_touch_device() && $(window).width() < 700) {
            cthis.addClass('is-touch-device type-youtube');

          }
          o.settings_video_overlay = 'on';

          if (helpersDZSVG.is_mobile()) {

          }
        }


        view_setupBasicStructure();


        if (cthis.get(0)) {
          cthis.get(0).fn_change_color_highlight = selfClass.classMisc.fn_change_color_highlight;

          cthis.get(0).api_handleResize = handleResize;
          cthis.get(0).api_seek_to_perc = seek_to_perc;

          cthis.get(0).api_currVideo_refresh_fsbutton = (arg) => {
            helpersDZSVG.player_controls_drawFullscreenBarsOnCanvas(selfClass, _controls_fs_canvas, arg);
          }
          cthis.get(0).api_reinit_cover_image = selfClass.classMisc.reinit_cover_image;
          cthis.get(0).api_restart_video = restart_video;
          cthis.get(0).api_change_media = change_media;


          cthis.get(0).api_ad_end = () => {
            playerAdFunctions.adEnd(selfClass);
          };
          cthis.get(0).api_action_set_video_end = function (arg) {
            action_video_end = arg;
          };
          cthis.get(0).api_action_set_video_view = function (arg) {
            action_video_view = arg;
          };
          cthis.get(0).api_action_set_video_play = function (arg) {
            action_video_play = arg;
          };
          cthis.get(0).api_action_set_video_pause = function (arg) {
            action_video_pause = arg;
          };

        }


        if (o.settings_big_play_btn === 'on') {
          var auxbpb = '<div class="big-play-btn">';
          auxbpb += helpersSvg.svg_aurora_play_btn;
          auxbpb += '</div>';

          if (cthis.find('.controls').length) {
            cthis.find('.controls').before(auxbpb);
          } else {
            cthis.append(auxbpb);
          }


          cthis.find('.big-play-btn').on('click', handleClickVideoOverlay);

          cthis.addClass('has-big-play-btn');
        } else {

          cthis.addClass('not-has-big-play-btn');
        }


        if (o.cueVideo === 'on' || ((!helpersDZSVG.is_ios() || o.settings_ios_usecustomskin === 'on') && (selfClass.dataType === 'selfHosted' || selfClass.dataType == 'youtube' || selfClass.dataType == 'vimeo'))) {

          if (selfClass.dataType === 'youtube') {
            inter_checkYtIframeReady = setInterval(selfClass.classMisc.youtube_checkIfIframeIsReady, 100);
          } else {
            init_readyControls(null, {
              'called_from': 'init.. cue video'
            });
          }
        } else {

          resizePlayer(selfClass.videoWidth, selfClass.videoHeight);
          cthis.bind('click', init_readyControls);

          cthis.addClass('dzsvp-loaded');

        }

        if (o.cueVideo != 'on') {


          //--------------normal
          if ((!helpersDZSVG.is_ios() || o.settings_ios_usecustomskin == 'on')) {
          }


        } else {

        }


        setInterval(selfClass.classMisc.check_one_sec_for_adsOrTags, 1000);
        selfClass.classMisc.check_one_sec_for_adsOrTags();


        handleResize();
      }

      if (inter_10_secs_contor == 0 && o.action_video_contor_10secs) {
        inter_10_secs_contor = setInterval(selfClass.classMisc.count_10secs, 10000);
      }
      if (inter_60_secs_contor == 0 && o.action_video_contor_60secs) {
        inter_60_secs_contor = setInterval(selfClass.classMisc.count_60secs, 30000);
      }

      if (inter_5_secs_contor == 0 && o.action_video_contor_5secs) {
        inter_5_secs_contor = setInterval(selfClass.classMisc.count_5secs, 3000);
        setTimeout(function () {

          selfClass.classMisc.count_5secs();
        }, 500)
      }


    }

    function view_setupBasicStructure() {

      // -- setup vp-inner
      if (!cthis.children('.vp-inner').length) {
        if (selfClass.dataType !== 'inline') {
          if (selfClass.dataType === 'vimeo') {
            if (o.settings_big_play_btn === 'on') {
              cthis.append('<div class="vp-inner ' + o.design_skin + '"></div>');
            } else {

              cthis.prepend('<div class="vp-inner ' + o.design_skin + '"></div>');
            }
          } else {


            cthis.append('<div class="vp-inner ' + o.design_skin + '"></div>');
          }

          selfClass._vpInner = cthis.children('.vp-inner').eq(0);
        } else {
          selfClass._vpInner = selfClass.cthis;
        }
      }

      if (selfClass.hasCustomSkin) {
        setup_customControls();
      }

      if(selfClass.isAd){

        player_setup_skipad(selfClass)()
      }


      reinit();
    }

    function setup_customControls() {


      var str_scrubbar = '<div class="scrubbar">';
      str_scrubbar += '<div class="scrub-bg"></div><div class="scrub-buffer"></div><div class="scrub">';


      str_scrubbar += '</div><div class="scrubBox"></div><div class="scrubBox-prog"></div>';
      str_scrubbar += '</div>';


      if (o.design_skin === 'skin_pro') {

        if (!(selfClass.dataType === 'vimeo' && o.vimeo_is_chromeless !== 'on')) {

          if (selfClass._vpInner) {
            selfClass._vpInner.append(str_scrubbar);
          }
        }

      }


      if (selfClass.dataType === 'selfHosted' || selfClass.dataType === 'youtube') {
        if (selfClass._vpInner) {
          selfClass._vpInner.prepend('<div class="mute-indicator"><i class="the-icon">' + helpersSvg.svg_mute_icon + '</i> <span class="the-label">' + 'muted' + '</span></div>')
        }
      }

      var str_controls = '<div class="controls"></div>';

      if (cthis.find('.cover-image').length > 0) {
        cthis.find('.cover-image').eq(0).before(str_controls);
      } else {

        if (selfClass._vpInner) {

          selfClass._vpInner.append(str_controls);
        }
      }


      setTimeout(function () {
        cthis.addClass('cover-image-loaded');
      }, 600);

      selfClass._controlsDiv = cthis.find('.controls');






      selfClass.totalWidth = selfClass.videoWidth;
      selfClass.totalHeight = selfClass.videoHeight;




      if (o.design_skin == 'skin_pro' || o.design_skin == 'skin_aurora') {
        selfClass._controlsDiv.append('<div class="controls-right"></div>');
      }

      if (selfClass._controlsDiv.find('.controls-right').length) {
        selfClass._controlsRight = selfClass._controlsDiv.find('.controls-right');
      }

      if ((selfClass.dataType != 'vimeo' || o.vimeo_is_chromeless == 'on') && selfClass.dataType != 'image' && selfClass.dataType != 'inline') {

        var aux34 = '<div class=""></div>';


        var struct_bg = '<div class="background"></div>';
        var struct_playcontrols = '<div class="playcontrols-con"><div class="playcontrols"></div></div>';


        var struct_timetext = '<div class="timetext"><span class="curr-timetext"></span><span class="total-timetext"></span></div>';


        var struct_fscreen = '<div class="fscreencontrols"></div>';

        aux34 += '';
        selfClass._controlsDiv.append(struct_bg);
        selfClass._controlsDiv.append(struct_playcontrols);
        if (o.design_skin !== 'skin_pro') {
          selfClass._controlsDiv.append(str_scrubbar);
        }
        selfClass._controlsDiv.append(struct_timetext);

        if (selfClass._controlsRight) {
          selfClass._controlsRight.append(struct_fscreen);
        } else {
          selfClass._controlsDiv.append(struct_fscreen);
        }
        selfClass.volumeClass.constructVolumeInPlayer();


        if (o.design_skin === 'skin_avanti') {
          selfClass._controlsDiv.append('<div class="mutecontrols-con"><div class="btn-mute">' + helpersSvg.svg_mute_btn + '</div></div>');

          _muteControls = selfClass._controlsDiv.find('.mutecontrols-con').eq(0);
        }
      }


      if (selfClass._controlsRight) {

        selfClass._controlsDiv.append(selfClass._controlsRight);
      }


      _timetext = cthis.find('.timetext').eq(0);


      _controlsBackground = selfClass._controlsDiv.find('.background').eq(0);

      if (selfClass.dataType === 'image') {
        cthis.attr('data-img', selfClass.dataSrc);
      }

      if (cthis.children('.vplayer-logo')) {
        cthis.append(cthis.children('.vplayer-logo'));
      }


      if (cthis.children('.extra-controls')) {
        if (o.design_skin === 'skin_aurora') {
          cthis.children('.extra-controls').children().each(function () {
            var _t = $(this);

            if (_t.html().indexOf('{{')) {
              _t.html(String(_t.html()).replace('{{svg_embed_icon}}', helpersSvg.svg_embed));
            }
            if (_t.get(0).outerHTML.indexOf('dzsvg-multisharer-but') > -1) {
              helpersDZSVG.dzsvg_check_multisharer();
            }

            cthis.find('.timetext').eq(0).after(_t);
          });

        }
      }


      if (cthis.attr('data-img')) {
        selfClass._vpInner.prepend('<div class="cover-image from-type-' + selfClass.dataType + '"><div class="the-div-image" style="background-image:url(' + cthis.attr('data-img') + ');"/></div>');
      }


      if (selfClass.dataType === 'image') {

        cthis.addClass('dzsvp-loaded');


        if (selfClass.ad_link) {
          selfClass.cthis.children().eq(0).css({'cursor': 'pointer'})
          selfClass.cthis.children().eq(0).bind('click', function () {
            if (selfClass.cthis.find('.controls').eq(0).css('pointer-events') !== 'none') {
              window.open(selfClass.ad_link);
              selfClass.ad_link = null;
            }
          })
        }
        return;
      }


      if (selfClass.dataType === 'inline') {
        cthis.find('.cover-image').bind('click', function () {
          $(this).fadeOut('slow');
        });

        cthis.addClass('dzsvp-loaded');

        setTimeout(function () {

          cthis.addClass('dzsvp-really-loaded');


        }, 2000);


        helpersDZSVG.player_getResponsiveRatio(selfClass, {
          'called_from': 'init .. inline'
        });
        handleResize();
        setTimeout(function () {

          handleResize();
        }, 1000);
        $(window).on('resize', handleResize);

        return;
      }


      if (selfClass.dataType === 'youtube') {
        helpersDZSVG.player_getResponsiveRatio(selfClass, {
          'called_from': 'init .. youtube'
        });
      }
      if (selfClass.dataType === 'selfHosted') {

        if (o.settings_disableControls === 'on') {
          // -- for youtube ads we force enable the custom skin because we need to know when the video ended
          o.cueVideo = 'on';
          o.settings_youtube_usecustomskin = 'on';

          if (helpersDZSVG.is_mobile()) {

            selfClass.autoplayVideo = 'off';
          }
        }

      }

      if (selfClass.dataType === 'vimeo') {

      }
      if (selfClass.dataType === 'youtube') {
        if (o.settings_disableControls === 'on') {
          // -- for youtube ads we force enable the custom skin because we need to know when the video ended
          o.cueVideo = 'on';
          o.settings_youtube_usecustomskin = 'on';
          if (helpersDZSVG.is_mobile()) {
            selfClass.autoplayVideo = 'off';
          }
        }

      }
      info = cthis.find('.info');
      infotext = cthis.find('.infoText');

      ////info


      var structPlayControls = '';
      playcontrols = cthis.find('.playcontrols');


      structPlayControls = helpersDZSVG.player_controls_generatePlayCon(o);

      playcontrols.append(structPlayControls);


      scrubbar = cthis.find('.scrubbar');

      _scrubBg = scrubbar.children('.scrub-bg');


      fScreenControls = cthis.find('.fscreencontrols');

      aux = '<div class="full">';


      if (o.design_skin == 'skin_aurora' || o.design_skin == 'skin_default' || o.design_skin == 'skin_white') {
        aux += helpersSvg.svg_full_icon;
      }


      aux += '</div><div class="fullHover"></div>';


      if (o.design_skin == 'skin_reborn') {
        aux += '<div class="full-tooltip">FULLSCREEN</div>';
      }


      fScreenControls.append(aux);


      if (o.design_skin === 'skin_pro' || o.design_skin === 'skin_bigplay') {
        playcontrols.find('.pauseSimple').eq(0).append('<div class="pause-part-1"></div><div class="pause-part-2"></div>');
        fScreenControls.find('.full').eq(0).append('<canvas width="15" height="15" class="fullscreen-button"></canvas>');

        _controls_fs_canvas = fScreenControls.find('.full').eq(0).find('canvas.fullscreen-button').eq(0)[0];
        if (_controls_fs_canvas != undefined) {
          helpersDZSVG.player_controls_drawFullscreenBarsOnCanvas(selfClass, _controls_fs_canvas, o.controls_fscanvas_bg);
          $(_controls_fs_canvas).bind('mouseover', handleMouseover);
          $(_controls_fs_canvas).bind('mouseout', handleMouseout);
        }
      }


      if (selfClass.cthis.children('.videoDescription').length > 0) {
        dataVideoDesc = selfClass.cthis.children('.videoDescription').html();
        selfClass.cthis.children('.videoDescription').remove();
      }


      if (cthis.attr('data-videoTitle')) {
        if (selfClass._vpInner) {
          selfClass._vpInner.append('<div class="video-description"></div>')

        }


        cthis.find('.video-description').eq(0).append('<div class="video-title">' + cthis.attr('data-videoTitle') + '</div>');
        if (o.video_description_style == 'show-description' && dataVideoDesc) {
          cthis.find('.video-description').eq(0).append('<div class="video-subdescription">' + dataVideoDesc + '</div>');
        }
        video_title = cthis.attr('data-videoTitle');
      }


    }


    function init_readyControls(e, pargs) {


      var margs = {
        'reset_responsive_ratio': false
        , 'check_source': true
        , 'called_from': 'default'
      };


      if (pargs) {
        margs = $.extend(margs, pargs);
      }


      var _c = cthis;
      _c.unbind();

      if (_c.attr('data-type') == 'youtube') {

        selfClass.dataSrc = helpersDZSVG.youtube_sanitize_url_to_id(selfClass.dataSrc);
      }


      var argsForVideoSetup = {}



      // -- ios video setup

      if (o.settings_ios_usecustomskin !== 'on' && helpersDZSVG.is_ios()) {

        if (selfClass.dataType == 'selfHosted') {

          argsForVideoSetup.usePlayInline = true;
          argsForVideoSetup.useCrossOrigin = true;
        }
        if (selfClass.dataType == 'vimeo') {
          _c.children().remove();
          var src = selfClass.dataSrc;
          _c.append('<iframe width="100%" height="100%" src="//player.vimeo.com/video/' + src + '" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen allow="autoplay;fullscreen" style=""></iframe>');

        }


        if (o.responsive_ratio === 'default') {
          helpersDZSVG.player_getResponsiveRatio(selfClass, {
            'called_from': 'init_readyControls .. ios'
          });
          ;
          o.responsive_ratio = 0.5625;
        }

        cthis.addClass('dzsvp-loaded');

        handleResize();


        // -- our job on the iphone / ipad has been done, we exit the function.
      }
      // -- end ios setup


      // -- selfHosted
      if ((!helpersDZSVG.is_ios() || o.settings_ios_usecustomskin === 'on')) {

        // -- selfHosted video on modern browsers
        if (o.settings_enableTags === 'on') {
          cthis.find('.dzstag-tobe').each(function () {
            var _tagElement = $(this);
            helpersDZSVG.tagsSetupDom(_tagElement);
          })
          arrTags = cthis.find('.dzstag');
        }
        aux = '';
        if (selfClass.dataType === 'audio') {
          if (selfClass.cthis.attr('data-audioimg') !== undefined) {
            aux = '<div style="background-image:url(' + selfClass.cthis.attr('data-audioimg') + ')" class="div-full-image from-type-audio"/>';
            selfClass._vpInner.prepend(aux);
          }
        }
        if (selfClass.dataType === 'selfHosted') {

          if (o.cueVideo !== 'on') {
            // o.autoplay = 'off';
            selfClass.autoplayVideo = 'off';
            argsForVideoSetup = {
              'preload': 'metadata',
              'called_from': 'init_readyControls .. cueVideo off'
            };
          } else {
            if (o.preload_method) {
              argsForVideoSetup.preload = o.preload_method;
              argsForVideoSetup.called_from = 'init_readyControls .. cueVideo on';
            }
          }
        }




        // --- type youtube
        if (selfClass.dataType === 'youtube') {
          // -- youtube
          argsForVideoSetup.youtube_useDefaultSkin = (o.settings_youtube_usecustomskin !== 'on' || (o.settings_ios_usecustomskin !== 'on' && helpersDZSVG.is_ios()));


          if (!window.YT) {

            // -- we will need to invoke iframe api
            helpersDZSVG.load_outside_script(ConstantsDzsvg.YOUTUBE_IFRAME_API);
            dzsvp_yt_iframe_settoload = true;
            setTimeout(function () {
              init_readyControls(null, {
                'called_from': 'retry.. no youtube api'
              })
            }, 1000)
            return false;
          }
        }


        if (selfClass.dataType === 'dash') {
          helpersDZSVG.dash_setupPlayer();

        }


        if (margs.called_from === 'change_media') {
          argsForVideoSetup.isGoingToChangeMedia = true;
        }

        if (selfClass.dataType === 'youtube' && argsForVideoSetup.youtube_useDefaultSkin === false) {

          cthis.find('#the-media-' + selfClass.currentPlayerId).bind('mousemove', handle_mousemove);
        }

      }
      generatePlayerMarkupAndSource(selfClass, argsForVideoSetup);


      // -- setup remainder

      if (selfClass.dataType === 'vimeo') {
        if (window.addEventListener) {
          window.addEventListener('message', vimeo_windowMessage, false);
        }

      }


      if (selfClass.autoplayVideo === 'on') {
        selfClass.wasPlaying = true;
      }

      helpersDZSVG.player_getResponsiveRatio(selfClass, {
        'called_from': 'init .. readyControls'
      });
      ;


      if (margs.called_from !== 'change_media' && String(margs.called_from).indexOf('retry') === -1) {
        init_final();
      }
      ;

      video = selfClass._videoElement;
    }


    function check_videoReadyState() {
      if (!selfClass._videoElement) {
        return;
      }

      helpersDZSVG.dzsvg_call_video_when_ready(o, selfClass._videoElement, init_readyVideo, vimeo_is_ready, inter_videoReadyState);
      setTimeout(() => {
        if (o.cue === 'on') {
          if (!isInitedReadyVideo) {
            init_readyVideo({
              'called_from': 'timeout .. readyvideo'
            })
          }
        }
      }, 10000);
    }


    /**
     * this function will assign listeners to the player and selfClass.autoplayVideo if the selfClass.autoplayVideo is set to on
     * @param pargs parameters
     */
    function init_readyVideo(pargs) {


      var margs = {

        'called_from': 'default'
      };

      if (pargs) {
        margs = $.extend(margs, pargs);
      }


      isInitedReadyVideo = true;
      clearInterval(inter_videoReadyState);




      selfClass.volumeClass.volume_setInitial();


      if (selfClass.videoWidth == 0) {

        selfClass.videoWidth = cthis.width();
        selfClass.videoHeight = cthis.height();
      }

      cthis.addClass('dzsvp-loaded');

      if (selfClass.dataType == 'youtube') {
        qualities_youtubeCurrentQuality = selfClass._videoElement.getPlaybackQuality();

      }


      if (selfClass.dataType == 'selfHosted' || selfClass.dataType == 'selfHosted') {
        if (o.default_playbackrate && o.default_playbackrate != '1') {

          setTimeout(function () {

          }, 1000);
          if (selfClass._videoElement && selfClass._videoElement.playbackRate) {

            selfClass._videoElement.playbackRate = Number(o.default_playbackrate);
          }
        }
      }


      selfClass.videoWidth = cthis.outerWidth();
      selfClass.videoHeight = cthis.outerHeight();


      resizePlayer(selfClass.videoWidth, selfClass.videoHeight)


      var checkInter = setInterval(handleEnterFrame, 100);



      if (selfClass.autoplayVideo == 'on') {

        if (selfClass.dataType != 'vimeo') {

          playMovie({
            'called_from': 'autoplay - on'
          });
        }
      }

      if (o.playfrom != 'default') {
        if (o.playfrom == 'last' && selfClass.id_player != '') {
          if (typeof Storage != 'undefined') {

            if (typeof localStorage['dzsvp_' + selfClass.id_player + '_lastpos'] != 'undefined') {
              if (selfClass.dataType == 'selfHosted' || selfClass.dataType == 'audio') {
                selfClass._videoElement.currentTime = Number(localStorage['dzsvp_' + selfClass.id_player + '_lastpos']);
              }
              if (selfClass.dataType == 'youtube') {
                selfClass._videoElement.seekTo(Number(localStorage['dzsvp_' + selfClass.id_player + '_lastpos']));
                if (selfClass.wasPlaying == false) {
                  pauseMovie({
                    'called_from': '_init_readyVideo()'
                  });
                }
              }
            }
          }
        }

        if (isNaN(Number(o.playfrom)) === false) {
          if (selfClass.dataType === 'selfHosted' || selfClass.dataType === 'audio') {
            selfClass._videoElement.currentTime = o.playfrom;
          }
        }


      }


      handleEnterFrame({
        skin_play_check: true
      });




      // -- we include this for both ads and selfHosted videos
      cthis.on('mouseleave', handleMouseout);
      cthis.on('mouseover', handleMouseover);


      cthis.on('click', '.mute-indicator', handle_mouse);

      selfClass._vpInner.on('click', '.controls .playcontrols', handleClickPlayPause);

      if (o.settings_disableControls != 'on') {
        // -- only for selfHosted videos

        cthis.find('.controls').eq(0).bind('mouseover', handle_mouse);
        cthis.find('.controls').eq(0).bind('mouseout', handle_mouse);
        cthis.bind('mousemove', handle_mousemove);
        $(document).on('keyup.dzsvgp', handleKeyPress);


        cthis.on('click', '.quality-option', handle_mouse);

        if (fScreenControls) {
          fScreenControls.off('click', fullscreenToggle);
          fScreenControls.on('click', fullscreenToggle);
        }

        if (scrubbar) {

          scrubbar.bind('click', handleScrub);
          scrubbar.bind('mousedown', handle_mouse);
          scrubbar.bind('mousemove', handleScrubMouse);
          scrubbar.bind('mouseout', handleScrubMouse);
        }
        cthis.bind('mouseleave', handleScrubMouse);


        selfClass._vpInner.find('.touch-play-btn').on('click touchstart', handleClickPlayPause);
        selfClass._vpInner.find('.mutecontrols-con').bind('click', volume_handleClickMuteIcon);



        document.addEventListener('fullscreenchange', handleFullscreenChange, false);
        document.addEventListener('webkitfullscreenchange', handleFullscreenChange, false);
        selfClass._videoElement.addEventListener('webkitendfullscreen', handleFullscreenEnd);
        selfClass._videoElement.addEventListener('endfullscreen', handleFullscreenEnd);


        if (helpersDZSVG.is_mobile()) {
          if (selfClass.dataType == 'youtube') {
            if (o.settings_video_overlay == 'on') {

              cthis.find('.controls').eq(0).css('pointer-events', 'none');
              cthis.find('.controls .playcontrols').eq(0).css('pointer-events', 'auto');
            }
          }

        } else {

          if (is360) {
            o.settings_video_overlay = 'off';
          }
        }


      } else {
        // -- if we disableControls ( for ad for example )
        // -- disable controls except volume / probably because its a advertisment


        if (selfClass.isAd && selfClass.autoplayVideo == 'off') {

        }

        if (helpersDZSVG.is_ios() || helpersDZSVG.is_android()) {

          playcontrols.css({'opacity': 0.9});
          playcontrols.on('click', handleClickPlayPause);

          o.settings_hideControls = 'off';

          cthis.removeClass('hide-on-paused');
          cthis.removeClass('hide-on-mouse-out');


          if (selfClass.isAd) {
            // -- if this is an ad

            selfClass.autoplayVideo = 'on';
            o.autoplay = 'on';
            o.cue = 'on';


            cthis.find('.video-overlay').append('<div class="warning-mobile-ad">' + 'You need to click here for the ad for to start' + '</div>')

          }

        }
      }

      $(selfClass._videoElement).bind('play', handleVideoEvent);




      if (helpersDZSVG.is_ios() && o.settings_ios_usecustomskin == 'off') {
        o.settings_video_overlay = 'off';
      }
      if (o.settings_video_overlay == 'on') {


        var str_video_overlay = '<div class="video-overlay"></div>';
        cthis.find('.dzsvg-video-container').eq(0).after(str_video_overlay);

        cthis.on('click', '.video-overlay', handleClickVideoOverlay);
        cthis.on('dblclick', '.video-overlay', fullscreenToggle);
      }

      if (o.video_description_style == 'gradient') {

        var aux3 = '<div class="video-description video-description-style-' + o.video_description_style + '"><div>';

        aux3 += dataVideoDesc;
        aux3 += '</div></div>';

        if (cthis.find('.big-play-btn').length) {

          cthis.find('.big-play-btn').eq(0).before(aux3);
        } else {

          if (cthis.find('.video-overlay').length) {

            cthis.find('.video-overlay').eq(0).after(aux3);
          } else {

            cthis.find('.controls').before(aux3);
          }
        }
      }



      window.dzsvg_handle_mouse = handle_mouse;


      if (selfClass._volumeControls_real) {

        selfClass._volumeControls_real.bind('mousedown', handle_mouse);
        selfClass._volumeControls_real.bind('click', handleMouseOnVolume);
      }

      $(document).on('mouseup.dzsvg', window.dzsvg_handle_mouse);


      if (o.settings_hideControls === 'on') {
        selfClass._controlsDiv.hide();
      }


      if (selfClass.dataType === 'selfHosted' || selfClass.dataType === 'audio') {

        selfClass._videoElement.addEventListener('ended', handleVideoEnd, false);
        if (helpersDZSVG.is_ios() && video && selfClass.isAd) {
          selfClass._videoElement.addEventListener('webkitendfullscreen', function () {
            if (selfClass._videoElement.currentTime > selfClass._videoElement.duration * 0.75) {
              handleVideoEnd();
            }
          }, false);
        }
      }


      if (cthis.children('.subtitles-con-input').length || o.settings_subtitle_file) {
        selfClass.classMisc.setup_subtitle();
      }


      setTimeout(handleResize, 500);


      cthis.get(0).api_destroy_listeners = destroy_listeners;


    }


    function handle_scroll() {
      if (isInited == false) {


        var st = $(window).scrollTop();
        var cthis_ot = cthis.offset().top;

        var wh = window.innerHeight;




        if (cthis_ot < st + wh + 150) {
          init();
        }


        return;
      } else {


      }

    }

    /**
     * should init after setup controls
     */
    function reinit() {

      if (cthis.attr('data-loop') === 'on') {
        selfClass.isLoop = true;
      }

      helpersDZSVG.reinitPlayerOptions(selfClass, o);

      selfClass.classMisc.reinit_cover_image();


      let extraFeedBeforeRightControls = '';
      const $extraFeedBeforeRightControls = selfClass.cthis.find('.dzsvg-feed--extra-html-before-right-controls').eq(0);
      if ($extraFeedBeforeRightControls.length) {
        extraFeedBeforeRightControls = $extraFeedBeforeRightControls.html();
      }

      if (extraFeedBeforeRightControls) {

        extraFeedBeforeRightControls = String(extraFeedBeforeRightControls).replace('{{svg_quality_icon}}', helpersSvg.svg_quality_icon);

        if (selfClass._controlsRight) {

          selfClass._controlsRight.prepend(extraFeedBeforeRightControls);
        } else {

          if (_timetext) {

            _timetext.after(extraFeedBeforeRightControls);
          }
        }
      }

      helpersDZSVG.player_setQualityLevels(selfClass);


    }


    /**
     * change the media of the player
     * @param argmedia
     * @param pargs
     */
    function change_media(argmedia, pargs) {
      // -- @change media


      var margs = {
        'called_from': 'default'
        , 'type': 'selfHosted'
        , 'autoplay': 'off'
      }

      if (pargs) {
        margs = $.extend(margs, pargs);
      }



      lastVideoType = selfClass.dataType;

      // -- update with new types
      selfClass.dataSrc = argmedia;
      selfClass.dataType = margs.type;

      if (margs.autoplay) {
        selfClass.autoplayVideo = margs.autoplay;
      }

      reinit();

      if (lastVideoType === margs.type) {

        // -- same type
        if (lastVideoType == 'selfHosted') {
          $(selfClass._videoElement).attr('src', argmedia);
          $(selfClass._videoElement).children('source').attr('src', argmedia);
        }
        if (lastVideoType === 'youtube') {
          if (selfClass.hasCustomSkin) {
            selfClass._videoElement.loadVideoById(helpersDZSVG.youtube_sanitize_url_to_id(argmedia));
          } else {
            if (selfClass._videoElement.loadVideoById) {
              selfClass._videoElement.loadVideoById(helpersDZSVG.youtube_sanitize_url_to_id(argmedia));
            } else {

              selfClass.dataSrc = helpersDZSVG.youtube_sanitize_url_to_id(argmedia);
              cthis.find('iframe').eq(0).attr('src', '//www.youtube.com/embed/' + selfClass.dataSrc + '?rel=0&showinfo=0')
            }
          }
        }
        if (lastVideoType === 'vimeo') {
          if (selfClass.hasCustomSkin) {
            var argsForVideoSetup = {
              called_from: 'change_media'
            }
            generatePlayerMarkupAndSource(selfClass, argsForVideoSetup);
          } else {

            var str_source = 'https:' + '//player.vimeo.com/video/' + selfClass.dataSrc + '?api=1&color=' + o.vimeo_color + '&title=' + o.vimeo_title + '&byline=' + o.vimeo_byline + '&portrait=' + o.vimeo_portrait + '&badge=' + o.vimeo_badge + '&player_id=vimeoplayer' + selfClass.dataSrc + (selfClass.autoplayVideo == 'on' ? '&autoplay=1' : '');

            selfClass._vpInner.find('.vimeo-iframe').eq(0).attr('src', str_source)
          }

        }
      } else {

        // -- different types..

        // -- update types
        selfClass.dataType = margs.type;

        cthis.find('video').each(function () {
          var _t2 = $(this);
          var errag = null;

          try {
            errag = this.pause();
            ;
          } catch (err) {
            console.log('cannot pause .. ', errag, err);
          }


          _t2.remove();
        });

        cthis.find('.the-video').remove();

        cthis.attr('data-sourcevp', argmedia);
        cthis.attr('data-type', margs.type);
        init_readyControls(null, {
          'called_from': 'change_media'
        });
        selfClass.dataSrc = argmedia;
      }


      lastVideoType = margs.type;

      if (selfClass.hasCustomSkin) {
        if (selfClass._vpInner.find('.controls').length === 0) {
          setup_customControls();
        }
      }


      if (margs.autoplay === 'on') {
        setTimeout(function () {
          playMovie({
            'called_from': 'change_media'
          });
        }, 300);
      }


    }


    function restart_video() {


      if (selfClass.dataType == 'selfHosted') {
        seek_to_perc(0);
      }
      if (selfClass.dataType == 'vimeo') {
        seek_to_perc(0);
      }


      reinit();
    }


    function init_final() {


      if (cthis.get(0)) {
        if (!(cthis.get(0).externalPauseMovie)) {

          cthis.get(0).externalPauseMovie = pauseMovie;
          cthis.get(0).externalPlayMovie = playMovie;
          cthis.get(0).api_pauseMovie = pauseMovie;
          cthis.get(0).api_playMovie = playMovie;
          cthis.get(0).api_get_responsive_ratio = (pargs = {}) => {
            helpersDZSVG.player_getResponsiveRatio(selfClass, pargs);
          };
        }
      }

      cthis.on('click', '.cover-image:not(.from-type-image), .div-full-image.from-type-audio', handleClickCoverImage);

      cthis.addClass('dzsvp-loaded');



      inter_videoReadyState = setInterval(check_videoReadyState, 50);

      if (is360) {




        $(selfClass._videoElement).on('click', function (e) {
          if (selfClass.isInitialPlayed === false) {
            playMovie();
            mouse_is_over();
          }
        })
        Dzsvg360.functionsInit(selfClass);
      }


      var _scrubbar = cthis.find('.scrubbar').eq(0);

      _scrubbar.on('touchstart', function (e) {
        scrubbar_moving = true;
      })

      if (o.ad_show_markers === 'on') {
        ads_view_setupMarkersOnScrub(selfClass);
      }

      $(document).on('touchmove', function (e) {
        if (scrubbar_moving) {
          scrubbar_moving_x = e.originalEvent.touches[0].pageX;


          var aux3 = scrubbar_moving_x - _scrubbar.offset().left;

          if (aux3 < 0) {
            aux3 = 0;
          }
          if (aux3 > _scrubbar.width()) {
            aux3 = _scrubbar.width();
          }

          seek_to_perc(aux3 / _scrubbar.width());



        }
      });

      $(document).on('touchend', function (e) {
        scrubbar_moving = false;
      })


      $(window).on('resize', handleResize);

      o.settings_trigger_resize = parseInt(o.settings_trigger_resize, 10);
      if (o.settings_trigger_resize > 0) {
        setInterval(function () {

          handleResize(null, {
            'called_from': 'recheck_sizes'
          });
        }, o.settings_trigger_resize);
      }
      ;

      if (helpersDZSVG.is_touch_device()) {
        cthis.addClass('is-touch');
      }
    }


    function destroy_listeners() {

      cthis.unbind('mouseout', handleMouseout);
      cthis.unbind('mouseover', handleMouseover);
      cthis.find('.controls').eq(0).unbind('mouseover', handle_mouse);
      cthis.find('.controls').eq(0).unbind('mouseout', handle_mouse);
      cthis.unbind('mousemove', handle_mousemove);
      cthis.unbind('keydown', handleKeyPress);
      fScreenControls.off('click', fullscreenToggle)
      scrubbar.unbind('click', handleScrub);
      scrubbar.unbind('mousedown', handle_mouse);
      scrubbar.unbind('mousemove', handleScrubMouse);
      scrubbar.unbind('mouseout', handleScrubMouse);
      cthis.unbind('mouseleave', handleScrubMouse);
      cthis.off('click');
      cthis.find('.mutecontrols-con').unbind('click', volume_handleClickMuteIcon);
      document.removeEventListener('fullscreenchange', handleFullscreenChange, false);


      if (selfClass.$parentGallery == null) {
        $(window).off('resize', handleResize);
      }

      selfClass._videoElement.removeEventListener('ended', handleVideoEnd, false);

    }


    function handle_mouse(e) {
      var _t = $(this);


      if (e.type === 'mouseover') {

        if (_t.hasClass('controls')) {

          controls_are_hovered = true;
        }
      }
      if (e.type === 'mouseout') {

        if (_t.hasClass('controls')) {

          controls_are_hovered = false;
        }
      }

      if (e.type === 'mousedown') {

        if (_t.hasClass('volumecontrols')) {

          volume_mouse_down = true;
        }
        if (_t.hasClass('scrubbar')) {


          clearTimeout(inter_mousedownscrubbing);
          inter_mousedownscrubbing = setTimeout(() => {
            scrub_mouse_down = true;
          }, 100);

        }
      }
      if (e.type === 'click') {

        player_user_had_first_interaction();

        if (_t.hasClass('mute-indicator')) {
          selfClass.volumeClass.player_volumeUnmute();
        }
        if (_t.hasClass('quality-option')) {


          if (_t.hasClass('active')) {
            return false;
          }


          selfClass.queue_goto_perc = time_curr / totalDuration;
          if (selfClass.dataType === 'youtube') {

            selfClass._videoElement.setPlaybackQuality(_t.attr('data-val'));



            selfClass._videoElement.stopVideo();
            selfClass._videoElement.setPlaybackQuality(_t.attr('data-val'));
            selfClass._videoElement.playVideo();


            setTimeout(function () {

              qualities_youtubeCurrentQuality = selfClass._videoElement.getPlaybackQuality();



            }, 2000)
          }


          if (selfClass.dataType === 'selfHosted') {


            var newsource = selfClass.dataSrc;


            var _c = $(selfClass._videoElement).eq(0);

            cthis.find('.the-video').addClass('transitioning-out');

            _c.after(_c.clone());

            var _c2 = _c.next();

            _c2.removeClass('transitioning-out transitioning-in');
            _c2.addClass('preparing-transitioning-in js-transitioning-in');
            _c2.html('<source src="' + newsource + '">')


            var aux_wasPlaying = selfClass.wasPlaying;
            _c2.on('loadeddata', function () {

              _c2.off('loadeddata');
              selfClass._videoElement = _c2.get(0);

              if (selfClass.queue_goto_perc) {
                seek_to_perc(selfClass.queue_goto_perc);
                selfClass.queue_goto_perc = '';
              }


              if (is360) {
                Dzsvg360.afterQualityChange(selfClass);
              }


              setTimeout(function () {

                pauseMovie();
                if (cthis.find('.transitioning-out').get(0).pause) {
                  cthis.find('.transitioning-out').get(0).pause();
                }
                cthis.find('.transitioning-out').remove();

                cthis.find('.the-video.js-transitioning-in').addClass('transitioning-in');


                if (aux_wasPlaying) {
                  playMovie();
                }
              }, 500)

            })

            setTimeout(function () {

            }, 100);
          }


          _t.parent().children().removeClass('active');
          _t.addClass('active');
        }
      }
      if (e.type == 'mouseup') {

        clearTimeout(inter_mousedownscrubbing);

        volume_mouse_down = false;
        scrub_mouse_down = false;
      }
    }


    function handle_mousemove(e) {
      cthis.removeClass('mouse-is-out');
      mouseover = true;

      if (volume_mouse_down) {
        handleMouseOnVolume(e);
      }
      if (scrub_mouse_down) {


        var argperc = (e.pageX - (scrubbar.offset().left)) / (scrubbar.children().eq(0).width());
        seek_to_perc(argperc);
      }

      if (is_fullscreen) {


        if (o.settings_disable_mouse_out !== 'on' && o.settings_disable_mouse_out_for_fullscreen !== 'on') {
          clearTimeout(inter_removeFsControls);
          inter_removeFsControls = setTimeout(controls_mouse_is_out, o.settings_mouse_out_delay_for_fullscreen);
        }

        if (e.pageX > ww - 10) {
          controls_are_hovered = false;
        }
      }
    }

    function controls_mouse_is_out() {

      if (selfClass.paused == false && (controls_are_hovered == false || helpersDZSVG.is_android())) {

        cthis.removeClass('mouse-is-over');
        cthis.addClass('mouse-is-out');
      }
      mouseover = false;
    }

    function handleVideoEvent(e) {


      if (e.type === 'play') {

        played = true;

        if (helpersDZSVG.is_ios() || helpersDZSVG.is_android()) {
          cthis.find('.controls').eq(0).css('pointer-events', 'auto');
        }
      }
    }


    function handleClickCoverImage(e) {


      if (e) {
        player_user_had_first_interaction();
      }

      if (selfClass.dataType != 'image') {
        if (selfClass.wasPlaying == false) {
          playMovie({
            'called_from': 'click coverImage'
          });
        } else {
          pauseMovie({
            'called_from': 'click coverImage'
          });
        }
      }
    }

    function player_user_had_first_interaction() {

      if (selfClass.cthis.data('userHadFirstInteraction')) {
        return false;
      }

      selfClass.isHadFirstInteraction = true;
      setTimeout(() => {
        // -- eliminate any concurrent events
        selfClass.cthis.addClass('user-had-first-interaction');

        if (selfClass.$parentGallery) {
          selfClass.$parentGallery.addClass('user-had-first-interaction');
        }
      }, 100);

      // -- unmute
      selfClass.volumeClass.player_volumeUnmute();
      selfClass.cthis.removeClass('autoplay-fallback--started-muted');


      selfClass.cthis.removeClass('is-muted');
      selfClass.cthis.data('userHadFirstInteraction', 'on');
      selfClass.is_muted_for_autoplay = false;
    }

    function handleClickVideoOverlay(e) {
      // -- check if user event

      const wasMutedForAutoplayBeforeClick = selfClass.is_muted_for_autoplay;
      if (e) {
        player_user_had_first_interaction();
      }


      if (is360) {
        Dzsvg360.enableControls();
      }

      if (selfClass.isAd) {
        if (!selfClass.cthis.hasClass('user-had-first-interaction')) {
          // -- no previous interaction
          handleClickPlayPause();
          if (cthis.hasClass('mobile-pretime-ad') && cthis.hasClass('first-played') == false) {
            return false;
          }
        } else {
          // -- previous interaction, now open link
          if (selfClass.ad_link) {
            window.open(selfClass.ad_link);
            selfClass.ad_link = null;
            return false;
          } else {
            return false;
          }
        }

        if (selfClass._videoElement && selfClass._videoElement.paused) {
          playMovie({
            'called_from': 'click_videoOverlay'
          });
        }
        if (e) {
          e.stopPropagation();
        }
      } else {

        // -- is not an AD

        if (selfClass.wasPlaying && wasMutedForAutoplayBeforeClick) {
          // -- just unmute
          selfClass.is_muted_for_autoplay = false;
        } else {
          if (selfClass.wasPlaying === false) {
            playMovie({
              'called_from': '_click_videoOverlay()'
            });
          } else {
            pauseMovie({
              'called_from': '_click_videoOverlay()'
            });
          }
        }
      }
    }

    function handleClickHdButton() {
      var _t = $(this);
      if (_t.hasClass('active')) {
        _t.removeClass('active');
        if ($.inArray('large', qualities_youtubeVideoQualitiesArray) > -1) {
          selfClass._videoElement.setPlaybackQuality('large');
        } else {
          if ($.inArray('medium', qualities_youtubeVideoQualitiesArray) > -1) {
            selfClass._videoElement.setPlaybackQuality('medium');
          } else {
            if ($.inArray('small', qualities_youtubeVideoQualitiesArray) > -1) {
              selfClass._videoElement.setPlaybackQuality('small');
            }
          }
        }

      } else {
        _t.addClass('active');
        if ($.inArray('hd1080', qualities_youtubeVideoQualitiesArray) > -1) {
          selfClass._videoElement.setPlaybackQuality('hd1080');
        } else {

          if ($.inArray('hd720', qualities_youtubeVideoQualitiesArray) > -1) {
            selfClass._videoElement.setPlaybackQuality('hd720');
          }
        }
      }
    }

    function mouse_is_over() {

      if (is360) {
        Dzsvg360.enableControls();
      }
      clearTimeout(inter_removeFsControls);
      cthis.removeClass('mouse-is-out');
      cthis.addClass('mouse-is-over');
    }

    function handleMouseover(e) {


      if ($(e.currentTarget).hasClass('vplayer')) {
        if (o.settings_disable_mouse_out != 'on') {
          if (fullscreen_just_pressed == false) {
            mouse_is_over();
          }
        }
      }
      if ($(e.currentTarget).hasClass('fullscreen-button')) {

        helpersDZSVG.player_controls_drawFullscreenBarsOnCanvas(selfClass, _controls_fs_canvas, o.controls_fscanvas_hover_bg);
      }


    }

    function handleMouseout(e) {

      Dzsvg360.enableControls();

      if (selfClass.dataType == 'youtube' && is_fullscreen) {
        fullscreen_just_pressed = true;

        setTimeout(function () {
          fullscreen_just_pressed = false;
        }, 500)
      }
      if ($(e.currentTarget).hasClass('vplayer')) {


        if (o.settings_disable_mouse_out != 'on') {


          clearTimeout(inter_removeFsControls);

          inter_removeFsControls = setTimeout(controls_mouse_is_out, o.settings_mouse_out_delay);
        }
      }
      if ($(e.currentTarget).hasClass('fullscreen-button')) {
        helpersDZSVG.player_controls_drawFullscreenBarsOnCanvas(selfClass, _controls_fs_canvas, o.controls_fscanvas_bg);
      }

    }

    function handleScrubMouse(e) {
      if (!scrubbar) {
        return false;
      }
      var _t = scrubbar;


      if (e.type == 'mousemove') {
        var mouseX = (e.pageX - $(this).offset().left) / currScale;
        var aux = (mouseX / scrubbg_width) * totalDuration;
        _t.children('.scrubBox').html(helpersDZS.formatTime(aux));
        _t.children('.scrubBox').css({'visibility': 'visible', 'left': (mouseX - 16)});
      }
      if (e.type == 'mouseout') {
        _t.children('.scrubBox').css({'visibility': 'hidden'});

      }
      if (e.type == 'mouseleave') {
        _t.children('.scrubBox').css({'visibility': 'hidden'});
      }
    }


    function handleScrub(e) {
      player_user_had_first_interaction();

      var argperc = (e.pageX - (scrubbar.offset().left)) / (scrubbar.children().eq(0).width());
      seek_to_perc(argperc);
    }

    function seek_to_perc(argperc) {


      var argperccheckads = playerAdFunctions.checkForAdAlongTheWay(selfClass, argperc);

      if (argperccheckads) {
        argperc = argperccheckads;
      }

      if (selfClass.dataType == 'selfHosted' || selfClass.dataType == 'audio' || selfClass.dataType == 'dash') {
        totalDuration = selfClass._videoElement.duration;


        if (isNaN(totalDuration)) {
          return false;
        }
        selfClass._videoElement.currentTime = (argperc) * totalDuration;
      }
      if (selfClass.dataType == 'youtube') {


        if (selfClass._videoElement && selfClass._videoElement.getDuration) {

          totalDuration = selfClass._videoElement.getDuration();
        } else {
          console.info('vplayer warning, youtube type - youtube api not ready .. ? ');
          totalDuration = 0;
        }

        // -- no need for seek to perct if video has not started.
        if (isNaN(totalDuration) || (time_curr == 0 && argperc == 0)) {
          return false;
        }

        selfClass._videoElement.seekTo(argperc * totalDuration);


        if (selfClass.wasPlaying == false) {
          pauseMovie({
            'called_from': '_seek_to_perc()'
          });
        }
      }


      if (selfClass.dataType === 'vimeo') {
        if (argperc === 0 && selfClass.isInitialPlayed) {

          vimeo_data = {
            "method": "seekTo"
            , "value": "0"
          };

          if (selfClass.vimeo_url) {
            try {
              selfClass._videoElement.contentWindow.postMessage(JSON.stringify(vimeo_data), selfClass.vimeo_url);

              selfClass.wasPlaying = false;
              selfClass.paused = true;
            } catch (err) {
              if (window.console) {
                console.log(err);
              }
            }
          }
        } else {

          if (o.vimeo_is_chromeless == 'on') {
            vimeo_data = {
              "method": "seekTo"
              , "value": (argperc) * totalDuration
            };

            if (selfClass.vimeo_url) {
              try {
                selfClass._videoElement.contentWindow.postMessage(JSON.stringify(vimeo_data), selfClass.vimeo_url);

              } catch (err) {
                if (window.console) {
                  console.log(err);
                }
              }
            }
          }

        }
      }

    }

    function handleEnterFrame(pargs) {
      // -- enterFrame function

      var margs = {
        skin_play_check: false
      }

      if (pargs) {
        margs = $.extend(margs, pargs);
      }

      if (selfClass.dataType == 'selfHosted' || selfClass.dataType == 'audio' || selfClass.dataType == 'dash') {
        totalDuration = selfClass._videoElement.duration;
        time_curr = selfClass._videoElement.currentTime;


        if (scrubbar && selfClass._videoElement && selfClass._videoElement.buffered && selfClass._videoElement.readyState > 1) {
          bufferedLength = 0;
          try {

            bufferedLength = (selfClass._videoElement.buffered.end(0) / selfClass._videoElement.duration) * (scrubbar.children().eq(0).width() + selfClass.bufferedWidthOffset);
          } catch (err) {
            console.log(err);
          }
        }


      }
      if (selfClass.dataType == 'youtube') {
        if (selfClass._videoElement.getVideoLoadedFraction == undefined || selfClass._videoElement.getVideoLoadedFraction == 0) {
          return false;
        }
        if (selfClass._videoElement.getDuration != undefined) {
          totalDuration = selfClass._videoElement.getDuration();
          time_curr = selfClass._videoElement.getCurrentTime();
        }
        if (_scrubBg) {

          bufferedLength = (selfClass._videoElement.getVideoLoadedFraction()) * (_scrubBg.width() + selfClass.bufferedWidthOffset);
        }

        aux = 0;
        if (scrubbar) {

          scrubbar.children('.scrub-buffer').css('left', aux);
        }


      }
      aux = ((time_curr / totalDuration) * (scrubbg_width));

      if (aux > scrubbg_width) {
        aux = scrubbg_width;
      }
      aux = parseInt(aux, 10);


      if (o.vimeo_is_chromeless == 'on') {
        if (scrubbar) {
          scrubbar.children('.scrub').css({
            'width': aux
          }, {});
        }
      } else {

        if (scrubbar) {
          scrubbar.children('.scrub').animate({
            'width': aux
          }, {
            queue: false
            , duration: ConstantsDzsvg.ANIMATIONS_DURATION
            , easing: "linear"

          });
        }
      }


      if (bufferedLength > -1) {
        if (bufferedLength > scrubbg_width + selfClass.bufferedWidthOffset) {
          bufferedLength = scrubbg_width + selfClass.bufferedWidthOffset;
        }
        if (scrubbar) {
          scrubbar.children('.scrub-buffer').width(bufferedLength)
        }
      }
      if (_timetext && _timetext.css('display') != 'none' && (selfClass.wasPlaying == true || margs.skin_play_check == true) || (selfClass.dataType == 'vimeo' && o.vimeo_is_chromeless == 'on')) {

        var aux35 = helpersDZS.formatTime(totalDuration);


        if (o.design_skin != 'skin_reborn') {

          aux35 = ' / ' + aux35;
        }



        _timetext.children(".curr-timetext").html(helpersDZS.formatTime(time_curr));
        _timetext.children(".total-timetext").html(aux35);

      }
      if (o.design_enableProgScrubBox == 'on') {

        if (scrubbar) {
          scrubbar.children('.scrubBox-prog').html(helpersDZS.formatTime(time_curr));

          scrubbar.children('.scrubBox-prog').animate({
            'left': aux - 16
          }, {
            queue: false
            , duration: ConstantsDzsvg.ANIMATIONS_DURATION
            , easing: "linear"

          })
        }
      }
      if (o.playfrom == 'last') {
        if (typeof Storage != 'undefined') {
          localStorage['dzsvp_' + selfClass.id_player + '_lastpos'] = time_curr;
        }
      }

    }


    function volume_handleClickMuteIcon(e) {
      var _t = $(this);
      _t.toggleClass('active');

      if (_t.hasClass('active')) {
        selfClass.volumeLast = selfClass.volumeClass.volume_getVolume();
        volume_playerMute();
      } else {

        volume_setupVolumePerc(selfClass.volumeLast, {'called_from': 'volume_unmute'});
      }
    }


    function volume_playerMute() {

      videoElementFunctions.video_mute(selfClass);
    }


    function handleMouseOnVolume(e) {


      // -- from user action


      if (selfClass.dataType == 'selfHosted' || selfClass.dataType == 'audio' || selfClass.dataType == 'youtube') {
        // -- we can remove muted on user action
        player_user_had_first_interaction();
      }

      const _volumeReferenceTarget = selfClass._volumeControls.eq(1).length ? selfClass._volumeControls.eq(1) : selfClass._volumeControls.eq(0);
      const mousePositionRelativeToVolumeControls = (e.pageX - (_volumeReferenceTarget.offset().left));

      selfClass._volumeControls = cthis.find('.volumecontrols').children();
      if (mousePositionRelativeToVolumeControls >= 0) {


        aux = (e.pageX - (_volumeReferenceTarget.offset().left)) / currScale;

        selfClass._volumeControls.eq(2).css('visibility', 'visible')
        selfClass._volumeControls.eq(3).css('visibility', 'hidden')

        volume_setupVolumePerc(aux / _volumeReferenceTarget.width(), {'called_from': 'handleMouseOnVolume'});
      } else {

        // -- set volume to 0  when x < 0

        if (selfClass._volumeControls.eq(3).css('visibility') == 'hidden') {
          selfClass.volumeLast = selfClass.volumeClass.volume_getVolume();
          volume_setupVolumePerc(0)


          if (selfClass.dataType == 'vimeo') {
            vimeo_data = {
              "method": "setVolume"
              , "value": "0"
            };

            if (selfClass.vimeo_url) {
              helpersDZSVG.vimeo_do_command(selfClass, vimeo_data, selfClass.vimeo_url);
            }
          }
          selfClass._volumeControls.eq(3).css('visibility', 'visible')
          selfClass._volumeControls.eq(2).css('visibility', 'hidden')
        } else {
          volume_setupVolumePerc(selfClass.volumeLast)


          selfClass._volumeControls.eq(3).css('visibility', 'hidden')
          selfClass._volumeControls.eq(2).css('visibility', 'visible')
        }
      }

    }

    /**
     *
     * @param argumentVolumePerc
     * @param pargs
     */
    function volume_setupVolumePerc(argumentVolumePerc, pargs) {

      // -- @arg is ratio 0 - 1
      var margs = {
        'called_from': 'default'
      }
      if (pargs) {
        margs = $.extend(margs, pargs);
      }
      if (argumentVolumePerc > 1) {
        argumentVolumePerc = 1;
      }
      selfClass.volumeClass.set_volume(argumentVolumePerc);
    }


    function handleVideoEnd() {

      if (selfClass.dataType == 'vimeo') {
        if (o.end_exit_fullscreen == 'on') {
          if (helpersDZSVG.fullscreen_status() == 1) {
            helpersDZSVG.exitFullscreen();
          }
        }

      }


      if (helpersDZSVG.fullscreen_status() == 1) {
        if (o.end_exit_fullscreen == 'on') {
          fullscreenToggle(null, {
            'called_from': 'handleVideoEnd .. forced o.end_exit_fullscreen',
            'force_exit_fullscreen': true,
          }); // -- we exit fullscreen if video has ended on fullscreen
        }
        setTimeout(function () {
          handleResize();
        }, 100);
      }


      selfClass.cthis.addClass('is-video-end-screen');
      if (selfClass.dataType == 'selfHosted' || selfClass.dataType == 'audio' || selfClass.dataType == 'dash') {

        if (selfClass.isLoop) {
          seek_to_perc(0);
          playMovie({
            'called_from': 'play_from_loop'
          });
          selfClass.cthis.removeClass('is-video-end-screen');
          return false;
        }
        if (selfClass._videoElement) {
          if (o.settings_video_end_reset_time == 'on') {
            selfClass._videoElement.currentTime = 0;
            if (selfClass.isLoop) {
              pauseMovie({
                'called_from': 'end_video()'
              });
              cthis.find('.cover-image').fadeIn('slow');
            } else {

            }


          }
        }
      }
      if (selfClass.dataType == 'youtube') {
        if (selfClass.isLoop) {
          seek_to_perc(0);
          setTimeout(function () {

            playMovie({
              'called_from': 'play_from_loop'
            });
          }, 1000);

          selfClass.suspendStateForLoop = true;
          setTimeout(function () {
            selfClass.suspendStateForLoop = false;
          }, 1500);
          selfClass.cthis.removeClass('is-video-end-screen');
          return false;
        }
        if (selfClass._videoElement) {
          if (selfClass._videoElement && selfClass._videoElement.pauseVideo) {
            selfClass.wasPlaying = false;
            if (o.settings_video_end_reset_time == 'on') {

            }
          }
        }
      }

      if (selfClass.$parentGallery) {
        if (typeof (selfClass.$parentGallery.get(0)) != 'undefined') {
          selfClass.$parentGallery.get(0).videoEnd();
        }

      }
      if (o.parent_player) {
        if (o.parent_player.get(0)) {
          o.parent_player.get(0).api_ad_end();
        }

      }

      if (action_video_end) {
        action_video_end(cthis);
      }

    }

    function handleResize(e, pargs) {


      var margs = {
        'force_resize_gallery': false
        , 'called_from': 'default'
      };

      if (pargs) {
        margs = $.extend(margs, pargs);
      }


      selfClass.videoWidth = cthis.width();
      selfClass.videoHeight = cthis.height();


      if (margs.called_from === 'recheck_sizes') {
        if (Math.abs(last_videoHeight - selfClass.videoHeight) < 4 && Math.abs(last_videoWidth - selfClass.videoWidth) < 4) {


          return false;
        }

      }

      last_videoWidth = selfClass.videoWidth;
      last_videoHeight = selfClass.videoHeight;



      if (isNaN(o.responsive_ratio) === true) {

        // -- then we have no responsive ratio
      }
      if (isNaN(o.responsive_ratio) === false && o.responsive_ratio > 0) {

        var auxh = o.responsive_ratio * selfClass.videoWidth;


        if (selfClass.$parentGallery && ((cthis.hasClass('currItem') && !selfClass.isAd) || margs.force_resize_gallery)) {
          if (selfClass.$parentGallery.get(0) && selfClass.$parentGallery.get(0).api_responsive_ratio_resize_h) {
            selfClass.$parentGallery.addClass('responsive-ratio-smooth');
            selfClass.$parentGallery.get(0).api_responsive_ratio_resize_h(auxh, {
              caller: cthis
            });
          }
        } else {

          if (!selfClass.isAd) {

            cthis.height(o.responsive_ratio * cthis.width());
          }
        }
      }
      if (cthis.hasClass('vp-con-laptop')) {

        if (selfClass.$parentGallery.get(0) && selfClass.$parentGallery.get(0).api_responsive_ratio_resize_h) {
          selfClass.$parentGallery.addClass('responsive-ratio-smooth');
          selfClass.$parentGallery.get(0).api_responsive_ratio_resize_h(selfClass.videoWidth * 0.5466, {
            caller: cthis
          });
        }
      }


      if (selfClass.videoWidth < 600) {
        cthis.addClass('under-600');

        if (o.design_skin === 'skin_aurora') {
          o.design_scrubbarWidth = selfClass.original_scrubwidth - 10;
        }


        if (selfClass.videoWidth < 421) {
          cthis.addClass('under-420');

          if (o.design_skin === 'skin_aurora') {
            o.design_scrubbarWidth = selfClass.original_scrubwidth - 10;
          }
        } else {
          cthis.removeClass('under-420');
          if (o.design_skin === 'skin_aurora') {
            o.design_scrubbarWidth = selfClass.original_scrubwidth;
          }
        }
      } else {
        cthis.removeClass('under-600');
        if (o.design_skin === 'skin_aurora') {
          o.design_scrubbarWidth = selfClass.original_scrubwidth;
        }
      }


      if (helpersDZSVG.fullscreen_status() === 1) {
        ww = $(window).width();
        wh = window.innerHeight;
        resizePlayer(ww, wh);


        cthis.css('transform', '');
        currScale = 1;
      } else {

        resizePlayer(selfClass.videoWidth, selfClass.videoHeight);
      }

    }

    function handleKeyPress(e) {
      //-check if space is pressed for pause


      if (mouseover) {
        if (e.charCode === 27 || e.keyCode === 27) {
          setTimeout(function () {
            handleResize(null, {
              'called_from': 'esc_key'
            });
          }, 300);
        }
        if (e.charCode === 32 || e.keyCode === 32) {
          handleClickPlayPause();


          e.stopPropagation();
          e.preventDefault();
          return false;
        }
      }
    }

    function vimeo_windowMessage(e) {
      // -- we receive iframe messages from vimeo here
      var data, method;

      if (e.origin !== 'https://player.vimeo.com' && e.origin !== 'http://player.vimeo.com') {
        return;
      }
      if (!selfClass._videoElement) {
        console.log('[dzsvg][log] video element does not exist for a reason ..', selfClass, e);
        return false;
      }
      selfClass.vimeo_url = '';

      if ($(selfClass._videoElement).attr('src')) {
        selfClass.vimeo_url = $(selfClass._videoElement).attr('src').split('?')[0];
      }
      vimeo_is_ready = true;

      if (String(selfClass.vimeo_url).indexOf('http') !== 0) {
        selfClass.vimeo_url = 'https:' + selfClass.vimeo_url;
      }

      try {
        data = JSON.parse(e.data);


        if (data.data.duration) {
          time_curr = data.data.seconds;
          totalDuration = data.data.duration;
        }
      } catch (err) {
        //fail silently... like a ninja!
      }

      if (e && typeof (e.data) == 'object') {
        data = e.data;
      }


      if (data && data.player_id && selfClass.dataSrc != data.player_id.substr(11)) {
        return;
      }

      if (data) {
        if (data.event == 'pause') {
          pauseMovie_visual();
        }
        if (data.event == 'ready') {
          if (selfClass.autoplayVideo === 'on') {
            // -- we don't force play Movie because we already set autoplay to 1 on the iframe
          }
          vimeo_data = {
            "method": "addEventListener",
            "value": "finish"
          };
          helpersDZSVG.vimeo_do_command(selfClass, vimeo_data, selfClass.vimeo_url);

          vimeo_data = {
            "method": "addEventListener",
            "value": "pause"
          };
          helpersDZSVG.vimeo_do_command(selfClass, vimeo_data, selfClass.vimeo_url);

          vimeo_data = {
            "method": "addEventListener",
            "value": "playProgress"
          };
          helpersDZSVG.vimeo_do_command(selfClass, vimeo_data, selfClass.vimeo_url);



          cthis.addClass('dzsvp-loaded');
          if (selfClass.$parentGallery != null) {
            if (typeof (selfClass.$parentGallery.get(0)) != 'undefined') {
              selfClass.$parentGallery.get(0).api_video_ready();
            }
          }


        }

        if (data.event === 'playProgress') {
          selfClass.isInitialPlayed = true;
          if (selfClass.paused === true) {
            playMovie_visual();
          }

        }
        if (data.event === 'finish' || data.event === 'ended') {
          handleVideoEnd();
        }
      }
    }


    function handleClickPlayPause(e) {

      var _t = $(this);

      if (this && e) {

        if ($(e.currentTarget).hasClass('playcontrols')) {
          if (_t.parent().parent().parent().hasClass('vplayer') || _t.parent().parent().parent().parent().hasClass('vplayer')) {

            // -- check for HD / ad reasons
          } else {
            return false;
          }
        }
      }
      if (busy_playpause_mistake) {
        return false;
      }


      busy_playpause_mistake = true;

      if (inter_clear_playpause_mistake) {
        clearTimeout(inter_clear_playpause_mistake);
      }
      inter_clear_playpause_mistake = setTimeout(function () {
        busy_playpause_mistake = false;
      }, 300);

      if (selfClass.dataType === 'youtube' && selfClass._videoElement.getPlayerState && (selfClass._videoElement.getPlayerState() === 2 || selfClass._videoElement.getPlayerState() === -1)) {
        selfClass.paused = true;
      }

      if (e) {
        player_user_had_first_interaction();
      }

      if (selfClass.cthis.hasClass('is-video-end-screen')) {
        seek_to_perc(0);
        setTimeout(() => {
          playMovie({
            'called_from': 'handleClickPlayPause'
          });
        }, 100);
        return false;
      }

      if (selfClass.paused) {
        playMovie({
          'called_from': 'handleClickPlayPause'
        });
      } else {
        pauseMovie({
          'called_from': 'handleClickPlayPause'
        });
      }


    }


    function handleFullscreenEnd(event) {

    }


    function handleFullscreenChange(e) {

      is_fullscreen = !!(helpersDZSVG.fullscreen_status() == 1);


      if (is_fullscreen) {
        // -- we have something fullscreen
        selfClass.cthis.addClass('is_fullscreen');
        if (selfClass.dataType == 'vimeo') {
          selfClass._vpInner.get(0).addEventListener('click', () => {
          }, false)
        }
      }


      if (o.touch_play_inline == 'on') {

        if (helpersDZSVG.is_ios()) {
          pauseMovie({
            'called_from': '_touch_play_inline_ios()'
          });
        }
      }

      if (!is_fullscreen) {
        fullscreen_offActions();
      }
    }


    function classMiscInit() {
      class classMisc {


        check_one_sec_for_adsOrTags() {

          if (selfClass.isAdPlaying === false && (selfClass.paused === false)) {

            if (typeof selfClass.ad_array == 'object' && selfClass.ad_array.length > 0) {

              for (let i2 in selfClass.ad_array) {


                var cach = selfClass.ad_array[i2];

                var cach_time = 0;


                if (cach.time) {
                  cach_time = cach.time;
                }
                if (cach.source && totalDuration && time_curr >= cach_time * totalDuration) {

                  player_setupAd(selfClass, i2, {'called_from': 'check_one_sec_for_adsOrTags'});

                }
              }
            }

          }

          if (o.settings_enableTags === 'on') {
            selfClass.classMisc.tags_check();
          }

        }

        reinit_cover_image() {

          selfClass.cthis.find('.cover-image').fadeIn('fast');
        }


        setup_subtitle() {
          var subtitle_input = '';
          if (cthis.children('.subtitles-con-input').length > 0) {
            subtitle_input = cthis.children('.subtitles-con-input').eq(0).html();
            this.parse_subtitle(subtitle_input);
          } else {
            if (o.settings_subtitle_file != '') {
              $.ajax({
                url: o.settings_subtitle_file
                , success: function (response) {
                  subtitle_input = response;
                  this.parse_subtitle(subtitle_input);
                }
              });
            }
          }
        }

        parse_subtitle(arg) {
          var regex_subtitle = /([0-9](?:[0-9]|:|,| )*)[–|-]*(?:(?:\&gt;)|>) *([0-9](?:[0-9]|:|,| )*)[\n|\r]([\s\S]*?)[\n|\r]/g;
          var arr_subtitle = [];
          cthis.append('<div class="subtitles-con"></div>')


          while (arr_subtitle = regex_subtitle.exec(arg)) {

            var starttime = '';
            if (arr_subtitle[1]) {
              starttime = helpersDZS.format_to_seconds(arr_subtitle[1]);
            }
            var endtime = '';
            if (arr_subtitle[2]) {
              arr_subtitle[2] = String(arr_subtitle[2]).replace('gt;', '');
              endtime = helpersDZS.format_to_seconds(arr_subtitle[2]);
            }

            var cnt = '';
            if (arr_subtitle[3]) {
              cnt = arr_subtitle[3];
            }


            cthis.children('.subtitles-con').append('<div class="dzstag subtitle-tag" data-starttime="' + starttime + '" data-endtime="' + endtime + '">' + cnt + '</div>');
          }
          arrTags = cthis.find('.dzstag');

        }


        youtube_checkIfIframeIsReady() {

          if ((window.YT && window.YT.Player) || window._global_youtubeIframeAPIReady) {
            init_readyControls(null, {
              'called_from': 'check_if_yt_iframe_ready'
            });
            clearInterval(inter_checkYtIframeReady);
          }
        }

        fn_change_color_highlight(arg) {
          cthis.find('.scrub').eq(0).css({
            'background': arg
          })
          cthis.find('.volume_active').eq(0).css({
            'background': arg
          })
          cthis.find('.hdbutton-hover').eq(0).css({
            'color': arg
          })
        }

        tags_check() {
          var roundTime = Number(time_curr);


          if (arrTags.length == 0) {
            return;
          }

          arrTags.removeClass('active');
          arrTags.each(function () {
            var _t = $(this);
            if (Number(_t.attr('data-starttime')) <= roundTime && Number(_t.attr('data-endtime')) >= roundTime) {
              _t.addClass('active');
            }
          })
        }

        check_if_hd_available() {


          if (qualities_youtubeVideoQualitiesArray.length > 0) {
            return false;
          }


          qualities_youtubeCurrentQuality = selfClass._videoElement.getPlaybackQuality();

          qualities_youtubeVideoQualitiesArray = selfClass._videoElement.getAvailableQualityLevels();

          if ($.inArray('hd720', qualities_youtubeVideoQualitiesArray) > -1) {
            hasHD = true;
          }

          if (qualities_youtubeVideoQualitiesArray.length > 1) {
            cthis.addClass('has-multiple-quality-levels');
          }


          if (selfClass._controlsDiv) {
            var _qualitySelector = selfClass.cthis.find('.quality-selector');
            if (_qualitySelector.length === 0) {

              if (hasHD === true) {

                if (selfClass._controlsDiv.children('.hdbutton-con').length === 0) {
                  if (o.settings_suggestedQuality !== 'default') {
                    if (qualities_youtubeCurrentQuality !== o.settings_suggestedQuality) {
                      selfClass._videoElement.setPlaybackQuality(o.settings_suggestedQuality);
                    }
                  }


                  if (o.design_skin === 'skin_pro') {
                    selfClass._controlsDiv.find('.timetext').after('<div class="hdbutton-con"><div class="hdbutton-normal">HD</div></div>');

                  } else {

                    selfClass._controlsDiv.append('<div class="hdbutton-con"><div class="hdbutton-normal">HD</div></div>');
                  }

                  _btnhd = selfClass._controlsDiv.children('.hdbutton-con');
                  if (qualities_youtubeCurrentQuality === 'hd720' || qualities_youtubeCurrentQuality === 'hd1080') {
                    _btnhd.addClass('active');
                  }
                  _btnhd.bind('click', handleClickHdButton);


                  resizePlayer(selfClass.videoWidth, selfClass.videoHeight);
                }

              }
            } else {

              // no-quality selector

              helpersDZSVG.player_setupQualitySelector(selfClass, qualities_youtubeCurrentQuality, qualities_youtubeVideoQualitiesArray);

            }

          }

        }

        count_10secs() {
          if (o.action_video_contor_10secs && cthis.hasClass('is-playing')) {
            o.action_video_contor_10secs(cthis, video_title);
          }
        }

        count_60secs() {
          if (o.action_video_contor_60secs && cthis.hasClass('is-playing')) {
            o.action_video_contor_60secs(cthis, video_title);
          }
        }

        count_5secs() {
          if (o.action_video_contor_5secs) {
            o.action_video_contor_5secs(cthis, video_title);
          }
        }

      }

      selfClass.classMisc = new classMisc();
    }

    function fullscreenToggle(event, pargs) {
      // -- is_fullscreenscreen trigger event


      var margs = {
        'called_from': 'event',
        force_exit_fullscreen: false
      };

      if (pargs) {
        margs = $.extend(margs, pargs);
      }




      var _currElement = cthis.parent().get(0);



      if (cthis.hasClass('is-ad')) {
        if (event && event.currentTarget) {
          if (event.currentTarget.className.indexOf('video-overlay') > -1) {
            return false;
          }
        }
      }


      selfClass.videoWidth = cthis.outerWidth();
      selfClass.videoHeight = cthis.outerHeight();


      if (helpersDZSVG.is_ios() && o.touch_play_inline === 'off') {
        playMovie({
          'called_from': 'fullscreenToggle ios'
        });
        return false;
      }

      // -- we force fullscreen status to 1 if we are forcing a exit
      var fullscreenStatus = margs.force_exit_fullscreen ? 1 : helpersDZSVG.fullscreen_status();


      // -- this was forced fullscreen so we exit it..
      if (fullscreenStatus === 0 && cthis.hasClass('is_fullscreen')) {
        fullscreen_offActions();
        is_fullscreen = 0;
        return false;
      }

      if (fullscreenStatus === 0) {
        is_fullscreen = 1;

        cthis.addClass('is_fullscreen is-fullscreen');


        if (is360 && helpersDZSVG.is_ios()) {


          setTimeout(function () {

            handleResize(null, {
              'called_from': 'fullscreen 360'
            });
          }, 300)

        } else {

          if (helpersDZSVG.is_ios() && selfClass._videoElement.webkitEnterFullscreen) {
            selfClass._videoElement.webkitEnterFullscreen();
            return false;
          }



          if (helpersDZSVG.requestFullscreen(_currElement) === null) {

            if (selfClass.$parentGallery) {
              selfClass.$parentGallery.find('.the-logo').hide();
              selfClass.$parentGallery.find('.gallery-buttons').hide();
            }
          }

          selfClass.totalWidth = window.screen.width;
          selfClass.totalHeight = window.screen.height;

          resizePlayer(selfClass.totalWidth, selfClass.totalHeight);


          if (o.design_skin == 'skin_reborn') {
            cthis.find('.full-tooltip').eq(0).html('EXIT FULLSCREEN');
          }


          fullscreen_just_pressed = true;

          setTimeout(function () {
            fullscreen_just_pressed = false
          }, 700)

          if (o.settings_disable_mouse_out !== 'on' && o.settings_disable_mouse_out_for_fullscreen !== 'on') {

            clearTimeout(inter_removeFsControls);
            inter_removeFsControls = setTimeout(controls_mouse_is_out, o.settings_mouse_out_delay_for_fullscreen);
          }

        }


      } else {


        is_fullscreen = 0;
        fullscreen_offActions();
        fullscreen_cancel_on_document();
      }


    }

    function fullscreen_offActions() {

      cthis.addClass('remove_fullscreen');
      cthis.removeClass('is_fullscreen is-fullscreen');
      cthis.find('.vplayer.is_fullscreen').removeClass('is_fullscreen is-fullscreen');
      cthis.removeClass('is-fullscreen');

      if (o.design_skin == 'skin_reborn') {
        cthis.find('.full-tooltip').eq(0).html('FULLSCREEN');
      }



      handleResize();
      setTimeout(handleResize, 800);
    }

    function fullscreen_cancel_on_document() {

      var elem = document;

      if (helpersDZSVG.fullscreen_status() === 1) {

        if (elem.cancelFullScreen) {
          elem.cancelFullScreen();
        } else if (elem.exitFullscreen) {
          try {
            elem.exitFullscreen();
          } catch (err) {
            console.info('error at exit fullscreen ', err);
          }
        } else if (elem.mozCancelFullScreen) {
          elem.mozCancelFullScreen();
        } else if (elem.webkitCancelFullScreen) {
          elem.webkitCancelFullScreen();
        } else if (elem.msExitFullscreen) {
          elem.msExitFullscreen();
        }
      }
    }

    function resizePlayer(warg, harg) {


      calculateDims(warg, harg);



      if (_scrubBg) {

        _scrubBg.css({
          'width': (warg + o.design_scrubbarWidth)
        });

        if (o.design_skin == 'skin_aurora' || o.design_skin == 'skin_avanti' || o.design_skin == 'skin_default' || o.design_skin == 'skin_white') {
          _scrubBg.css({
            'width': '100%'
          });
        }

        scrubbg_width = _scrubBg.width();

      }
      if (is360) {

        Dzsvg360.resizePlayer(warg, harg);
      }


      if (selfClass._controlsDiv) {

        infoPosX = parseInt(selfClass._controlsDiv.find('.infoText').css('left'));
        infoPosY = parseInt(selfClass._controlsDiv.find('.infoText').css('top'));
      }
    }


    function calculateDims(warg, harg) {

      if (o.design_skin != 'skin_bigplay') {
      }


      if (selfClass.dataType == 'selfHosted') {

        if (selfClass._videoElement) {
          if (selfClass._videoElement.videoWidth) {

            natural_videow = selfClass._videoElement.videoWidth;
          }
          if (selfClass._videoElement.videoHeight) {

            natural_videoh = selfClass._videoElement.videoHeight;
          }
        } else {
          console.info('video not found ? problem');
        }

      }
      if (cthis.hasClass('pattern-video')) {
        if (selfClass.dataType == 'selfHosted') {

          if (natural_videow) {

            var nr_w = Math.ceil(selfClass.totalWidth / natural_videow);
            var nr_h = Math.ceil(selfClass.videoHeight / natural_videoh);


            for (var i = 0; i < nr_w; i++) {
              for (var j = 0; j < nr_h; j++) {


                if ((i == 0 && j == 0) || (cthis.find('video[data-dzsvgindex="' + i + '' + j + '"]').length)) {
                  continue;
                }
                $(selfClass._videoElement).after($(selfClass._videoElement).clone());
                $(selfClass._videoElement).next().attr('data-dzsvgindex', String(i) + String(j));
                $(selfClass._videoElement).next().get(0).play();
                $(selfClass._videoElement).next().css({
                  'left': (i * natural_videow)
                  , 'top': (j * natural_videoh)
                })

              }
            }


            if (nr_w) {
              for (var i = 0; i < nr_w; i++) {

              }
            }

          }
        }
      }

    }


    function playMovie(pargs) {


      var margs = {
        'called_from': 'default'
      };

      if (pargs) {
        margs = $.extend(margs, pargs);
      }



      if (helpersDZSVG.is_mobile()) {
        var d = new Date();

        if (selfClass.isHadFirstInteraction === false && o.autoplayWithVideoMuted === 'off' && margs.called_from.indexOf('autoplayNext') > -1 && (Number(d) - window.dzsvg_time_started < 1500)) {
          // -- no user action
          return false;
        }
      }
      play_commited = true;
      if (cthis.hasClass('dzsvp-loaded') == false && selfClass.dataType != 'vimeo') {
        setTimeout(function () {
          // -- check if play still commited

          if (play_commited) {

            margs.called_from = margs.called_from + ' recommit';
            playMovie(margs);
          }
        }, 500);
        return false;
      }

      if (margs.called_from == 'play_only_on_desktop') {
        if (helpersDZSVG.is_mobile()) {
          return false;
        }
      }


      cthis.find('.cover-image').fadeOut('fast');



      if (o.settings_disableVideoArray != 'on') {
        for (var i = 0; i < dzsvp_players_arr.length; i++) {
          if (dzsvp_players_arr[i].get(0) && dzsvp_players_arr[i].get(0) != cthis.get(0) && dzsvp_players_arr[i].get(0).externalPauseMovie != undefined) {
            dzsvp_players_arr[i].get(0).externalPauseMovie({
              'called_from': 'external_pauseMovie()'
            });
          }
        }
      }

      if (o.try_to_pause_zoomsounds_players == 'on') {

        helpersDZSVG.pauseDzsapPlayers();
      }


      if (selfClass.dataType == 'selfHosted' || selfClass.dataType == 'vimeo' || selfClass.dataType == 'audio' || selfClass.dataType == 'dash') {
        try {
          videoElementFunctions.video_play(selfClass);
        } catch (err) {
          console.info('vg - ', err);
        }

      }
      if (selfClass.dataType == 'youtube') {
        if (selfClass.paused == false) {
          return false;
        }
        videoElementFunctions.video_play(selfClass);
      }


      selfClass.wasPlaying = true;
      selfClass.paused = false;
      selfClass.isInitialPlayed = true;
      if (!selfClass.isHadFirstInteraction) {
        selfClass.is_muted_for_autoplay = true;
      }

      cthis.trigger('videoPlay');


      playMovie_visual();
      selfClass.classMisc.check_one_sec_for_adsOrTags();


      if (action_video_view) {
        if (view_sent == false) {
          action_video_view(cthis, video_title);
          view_sent = true;
        }
      }


    }

    function playMovie_visual() {



      cthis.addClass('first-played');

      if (selfClass.isAd) {

        o.parent_player.removeClass('pretime-ad-setuped');



        if (o.parent_player.get(0) && o.parent_player.get(0).gallery_object) {
          $(o.parent_player.get(0).gallery_object).removeClass('pretime-ad-setuped')
        }

      }


      if (o.settings_disableControls != 'on') {
      }

      if (o.google_analytics_send_play_event == 'on' && window._gaq && google_analytics_sent_play_event == false) {
        window._gaq.push(['_trackEvent', 'Video Gallery Play', 'Play', 'video gallery play - ' + selfClass.dataSrc]);
        google_analytics_sent_play_event = true;
      }


      if (o.settings_disable_mouse_out !== 'on') {


        if (helpersDZSVG.is_mobile()) {
          clearTimeout(inter_removeFsControls);
          inter_removeFsControls = setTimeout(controls_mouse_is_out, o.settings_mouse_out_delay_for_fullscreen);
        }

      }


      cthis.addClass('is-playing');
      cthis.removeClass('is-video-end-screen');
      if (selfClass.$parentGallery && selfClass.$parentGallery.get(0) && selfClass.$parentGallery.get(0).api_played_video) {
        selfClass.$parentGallery.get(0).api_played_video();
      }

      selfClass.paused = false;
      selfClass.wasPlaying = true;
      selfClass.isInitialPlayed = true;


      if (action_video_play) {
        action_video_play(cthis, video_title);
      }
    }

    function pauseMovie_visual() {



      selfClass.wasPlaying = false;
      selfClass.paused = true;


      cthis.removeClass('is-playing');

      if (action_video_pause) {
        action_video_pause(cthis, video_title);
      }

    }

    function pauseMovie(pargs) {


      var margs = {
        'called_from': 'default'
      };

      if (pargs) {
        margs = $.extend(margs, pargs);
      }


      play_commited = false;
      if (o.try_to_pause_zoomsounds_players === 'on') {
        if (window.dzsap_player_interrupted_by_dzsvg) {

          window.dzsap_player_interrupted_by_dzsvg.api_play_media({
            'audioapi_setlasttime': false
          });
          window.dzsap_player_interrupted_by_dzsvg = null;
        }
      }


      if (selfClass.isInitialPlayed == false) {
        return false;
      }
      selfClass.suspendStateForLoop = true;
      setTimeout(function () {
        selfClass.suspendStateForLoop = false;
      }, 1500);


      pauseMovie_visual();

      if (selfClass.dataType == 'selfHosted' || selfClass.dataType == 'audio' || selfClass.dataType == 'dash') {
        if (selfClass._videoElement != undefined) {
          selfClass._videoElement.pause();
        } else {
          if (window.console != undefined) {
            console.info('warning: video undefined')
          }
          ;
        }
      }
      if (selfClass.dataType == 'youtube') {

        if (selfClass._videoElement && selfClass._videoElement.pauseVideo) {

          try {
            selfClass._videoElement.pauseVideo();
          } catch (err) {
            if (window.console) {
              console.log(err);
            }
          }
        }
      }

      if (selfClass.dataType == 'vimeo') {
        vimeo_data = {
          "method": "pause"
        };

        if (selfClass.vimeo_url) {
          try {
            selfClass._videoElement.contentWindow.postMessage(JSON.stringify(vimeo_data), selfClass.vimeo_url);

            selfClass.wasPlaying = false;
            selfClass.paused = true;
          } catch (err) {
            if (window.console) {
              console.log(err);
            }
          }
        }
        return;
      }


      selfClass.wasPlaying = false;
      selfClass.paused = true;

      mouse_is_over();

      cthis.removeClass('is-playing');
    }


    try {
      cthis.get(0).checkYoutubeState = function () {
        if (selfClass.dataType == 'youtube' && selfClass._videoElement.getPlayerState != undefined) {
          if (selfClass._videoElement.getPlayerState && selfClass._videoElement.getPlayerState() == 0) {
            handleVideoEnd();
          }
        }
      }

    } catch (err) {
      if (window.console)
        console.log(err);
    }

  }
}


window.setup_videogalleryCategories = helpersDZSVG.setup_videogalleryCategories;


//-------VIDEO PLAYER
(function ($) {
  $.fn.vPlayer = function (argOptions) {

    var finalOptions = {};
    var defaultOptions = Object.assign({}, require('./configs/_playerSettings').default_opts);
    finalOptions = helpersDZSVG.convertPluginOptionsToFinalOptions(this, defaultOptions, argOptions);
    this.each(function () {

      var _vg = new DzsVideoPlayer(this, finalOptions, $);
      return this;
    }); // end each

  }


  window.dzsvp_init = function (selector, settings) {


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
        _t.vPlayer(settings)
      });
    } else {
      $(selector).vPlayer(settings);
    }


  };


  if (!window.dzsvg_settings) {
    if (window.dzsvg_default_settings) {
      window.dzsvg_settings = window.dzsvg_default_settings;
    }
  }

  require('./js_dzsvg/_dzsvg_galleryplugin').apply_videogallery_plugin(jQuery);


})(jQuery);


jQuery(document).ready(function ($) {
  dzsvp_init('.vplayer-tobe.auto-init', {init_each: true});
  dzsvg_init('.videogallery.auto-init', {init_each: true});


  helpersDZSVG.registerAuxjQueryExtends($);

  _secondCon.secondCon_initFunctions();


  helpersDZSVG.init_navigationOuter();
  _secondCon.init_secondCon();


});


helpersDZSVG.dzsvgExtraWindowFunctions();

window.dzsvg_curr_embed_code = '';

