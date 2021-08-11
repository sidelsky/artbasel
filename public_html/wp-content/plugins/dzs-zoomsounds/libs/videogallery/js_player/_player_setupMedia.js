import * as dzsvgHelpers from '../js_dzsvg/_dzsvg_helpers';
import {constants as ConstantsDzsvg} from '../configs/Constants';

/**
 * setup video player here .. setup_video
 * @param selfClass
 * @param pargsForVideoSetup
 * @returns {boolean}
 */

export function generatePlayerMarkupAndSource(selfClass, pargsForVideoSetup) {
  var argsForVideoSetup = {
    'preload': 'auto',
    'called_from': 'default',
    'is_dash': false,
    'useCrossOrigin': false,
    'usePlayInline': true,
    'useAudioDimensions': true,
    isGoingToChangeMedia: false,
    is360: false,
    Dzsvg360: null, // -- add the 360 class here if need be

  };

  if (pargsForVideoSetup) {
    argsForVideoSetup = Object.assign(argsForVideoSetup, pargsForVideoSetup);
  }


  var o = selfClass.initOptions;




  var struct_video_element = '';

  var posterSourceStr = '';
  var str_crossorigin = ' crossorigin="anonymous"';


  if (selfClass.cthis.attr('data-img')) {
    posterSourceStr = ' poster="' + selfClass.cthis.attr('data-img') + '"';
  }

  var shouldAutoplay = false;


  if (selfClass.autoplayVideo === 'on' && o.settings_mode !== 'videowall') {
    shouldAutoplay = true;
  }


  // -- if we have user action on page it's enough for autoplay with sound
  if (o.old_curr_nr > -1) {
    if (o.autoplayWithVideoMuted === 'on') {
      if (1) {
        if (o.autoplay === 'on') {
          o.autoplayWithVideoMuted = 'off';
        }
      }
    }
  }


  if (argsForVideoSetup.isGoingToChangeMedia) {
    if (selfClass._vpInner) {

      selfClass._vpInner.find('.fullscreen-video-element').remove();
      selfClass._vpInner.find('.video-element').remove();
    }
  }


  if (selfClass.dataType === 'selfHosted') {


    if (o.is_ad === 'on' && dzsvgHelpers.is_mobile()) {
      argsForVideoSetup.preload = 'metadata';
    }


    if (dzsvgHelpers.is_ios() && o.settings_ios_usecustomskin === 'off') {

      argsForVideoSetup.preload = 'auto';
    }

    struct_video_element = '<video class="the-video video-element" preload="' + argsForVideoSetup.preload + '" ';

    if (selfClass.shouldStartMuted) {

      struct_video_element += ' muted';

    }

    if (o.touch_play_inline === 'on' || argsForVideoSetup.usePlayInline) {
      struct_video_element += ' webkit-playsinline playsinline';
    }


    if (!selfClass.hasCustomSkin) {
      struct_video_element += ' controls="true"';
    }

    if (argsForVideoSetup.useCrossOrigin) {
      struct_video_element += str_crossorigin;
    }

    if (selfClass.videoWidth !== 0) {
      struct_video_element += ' width="100%"';
      struct_video_element += ' height="100%"';
    }
    struct_video_element += '></video>';


  }
  if (selfClass.dataType === 'audio') {

    struct_video_element = '<audio class="the-video video-element" preload="' + argsForVideoSetup.preload + '" ';
    if (argsForVideoSetup.useAudioDimensions) {

      struct_video_element += ' width="100%"';
      struct_video_element += ' height="100%"';
    }
    struct_video_element += '>';

    if (selfClass.dataSrc) {
      struct_video_element += '<source src="' + selfClass.dataSrc + '" type="audio/mp3"/>'
    }
    struct_video_element += '</audio>';
  }


  if (selfClass.dataType === 'youtube') {

    var youtubeAPIArgs = {};

    selfClass._vpInner.children('.cmedia-con').remove();


    struct_video_element = '<span class="cmedia-con youtube-player-con"><span class="video-element" id="the-media-' + selfClass.currentPlayerId + '"></span></span>';


    if (o.cueVideo === 'off') {

      selfClass.autoplayVideo = 'off';
    }



    var playfrom = '';


    if (o.playfrom !== 'default') {



      // -- setup last position youtube
      if (o.playfrom === 'last' && selfClass.id_player !== '') {
        if (typeof Storage != 'undefined') {
          if (typeof localStorage['dzsvp_' + selfClass.id_player + '_lastpos'] != 'undefined') {
            playfrom = (Number(localStorage['dzsvp_' + selfClass.id_player + '_lastpos']))

          }
        }
      }

      if (isNaN(Number(o.playfrom)) === false) {
        playfrom = Number(o.playfrom);
      }
    }


    // -- custom controls
    // -- youtube no controls



    var param_autoplay = 0;

    if (shouldAutoplay) {
      param_autoplay = 1
    }

    var playerVars = {
      'autoplay': param_autoplay,
      controls: 0,
      'showinfo': 0,
      'playsinline': 1,
      rel: 0,
      autohide: 1,
      start: playfrom,
      wmode: 'transparent',
      iv_load_policy: 3,
      modestbranding: 1,
      enablejsapi: 1,
      disablekb: 1
    };

    // -- youtube


    if (selfClass.shouldStartMuted) {
      playerVars.mute = 1;
    }



    youtubeAPIArgs = {
      height: '100%',
      width: '100%',

      playerVars: playerVars,
      videoId: selfClass.dataSrc,
      suggestedQuality: o.settings_suggestedQuality,
      events: {
        'onReady': youtube_onPlayerReady
        , 'onStateChange': youtube_onPlayerStateChange
        , 'onPlaybackQualityChange': onPlayerPlaybackQualityChange
      }
    };


    if (argsForVideoSetup.youtube_useDefaultSkin) {
      // -- init ready controls, not custom skin ( default skin )
      selfClass._vpInner.children(':not(.cover-image)').remove();
      youtubeAPIArgs.playerVars.controls = 1;
    }
  }


  /**
   * vimeo
   */
  if (selfClass.dataType === 'vimeo') {


    var src = selfClass.dataSrc;
    var str_autoplay = '';

    if (shouldAutoplay) {
      str_autoplay = '&autoplay=1';
      selfClass.cthis.find('.cover-image').fadeOut('fast');
    }


    if (selfClass._vpInner && (o.vimeo_is_chromeless !== 'on')) {
      selfClass._vpInner.children('.controls').remove();
    }








    var str_allowFullscreen = 'webkitAllowFullScreen mozallowfullscreen allowFullScreen';


    var str_controls_disable_param = '';

    if (selfClass.hasCustomSkin) {
      str_controls_disable_param = '&controls=0';
    }

    var str_source = 'https:' + '//player.vimeo.com/video/' + src + '?api=1&color=' + o.vimeo_color + '&title=' + o.vimeo_title + str_controls_disable_param + '&byline=' + o.vimeo_byline + '&portrait=' + o.vimeo_portrait + '&badge=' + o.vimeo_badge + '&player_id=vimeoplayer' + src + str_autoplay;


    struct_video_element = '<div class="fullscreen-video-element"><iframe allow="autoplay;fullscreen" class="vimeo-iframe video-element from-simple" scrolling="no" src="' + str_source + '" width="100%" height="100%" frameborder="0" ' + str_allowFullscreen + ' style=""></iframe></div>';


  }

  selfClass._fullscreenVideoElement = null;

  selfClass._vpInner.prepend('<div class="dzsvg-video-container">' + struct_video_element + '</div>');



  if (selfClass.dataType === 'inline') {
    selfClass._vpInner.find('.dzsvg-video-container').append(selfClass.cthis.find('.feed-dzsvg--inline-content'));
    selfClass.cthis.find('.feed-dzsvg--inline-content').removeClass('feed-dzsvg');
  }
  if (selfClass.dataType === 'vimeo') {
    selfClass._videoElement = selfClass._vpInner.find('.vimeo-iframe').get(0);
    selfClass._fullscreenVideoElement = selfClass._vpInner.find('.fullscreen-video-element').get(0);
  }
  if (selfClass.dataType === 'youtube') {

    selfClass._videoElement = new YT.Player('the-media-' + selfClass.currentPlayerId, youtubeAPIArgs);
  }
  if (selfClass.dataType === 'selfHosted' || selfClass.dataType === 'audio' || selfClass.dataType === 'dash') {
    selfClass._videoElement = selfClass.cthis.find('.video-element').get(0);
  }

  if (!selfClass._fullscreenVideoElement) {
    selfClass._fullscreenVideoElement = selfClass._videoElement;
  }

  if (argsForVideoSetup.is_dash) {
    return false;
  }


  if (selfClass.dataType === 'audio') {


    if (selfClass.cthis.attr('data-sourceogg')) {
      jQuery(selfClass._videoElement).eq(0).append('<source src="' + selfClass.cthis.attr('data-sourceogg') + '" type="audio/ogg"/>');
    }
    if (selfClass.cthis.attr('data-sourcewav')) {
      jQuery(selfClass._videoElement).eq(0).append('<source src="' + selfClass.cthis.attr('data-sourcewav') + '" type="audio/wav"/>');
    }
  }
  if (selfClass.dataType === 'vimeo') {


    var self2 = this;




    selfClass._videoElement.addEventListener("webkitfullscreenchange", function (e) {
      if (!dzsvgHelpers.fullscreen_status()) {
        jQuery(e.target).parent().parent().removeClass('is_fullscreen is-fullscreen');
      }
      ;
    }, false);
    selfClass._videoElement.addEventListener("fullscreenchange", function (e) {
      if (!dzsvgHelpers.fullscreen_status()) {
        jQuery(e.target).parent().parent().removeClass('is_fullscreen is-fullscreen');
      }
      ;
    }, false);


    if (window.dzsvg_fullscreen_counter) {
      clearInterval(window.dzsvg_fullscreen_counter);
    }

    window.dzsvg_fullscreen_counter = setInterval(function () {


      // -- leave fullscreen event will not trigger in iframe so we need workaround


      if (dzsvgHelpers.fullscreen_status()) {

        if (!jQuery('.is_fullscreen').length) {
          jQuery('.vplayer.currItem').addClass('is_fullscreen');
        }



        if (window.dzsvg_fullscreen_curr_video && window.dzsvg_fullscreen_curr_video !== jQuery('.is_fullscreen').get(0)) {
          console.log(" FOUND DIFFERENCE");



          var elem = jQuery('.is_fullscreen').get(0);

          if (jQuery(elem).hasClass('type-vimeo')) {

            if (document.exitFullscreen) {
              document.exitFullscreen();
            } else if (document.mozCancelFullScreen) {
              document.mozCancelFullScreen();
            } else if (document.webkitExitFullscreen) {
              document.webkitExitFullscreen();
            }

          }

        }

        window.dzsvg_fullscreen_curr_video = jQuery('.is_fullscreen').get(0);
      }
    }, 1000);


    if (selfClass.dataType === 'vimeo') {


      function vimeo_addListeners() {
        if (window.Vimeo) {
          if (window._global_vimeoIframeAPILoading_inter) {

            clearInterval(window._global_vimeoIframeAPILoading_inter);
          }
          _global_vimeoIframeAPIReady = true;


          if (selfClass._videoElement) {


            // -- vimeo events

            var player = new Vimeo.Player(selfClass._videoElement);



            player.on('play', function () {

              selfClass.playMovie_visual()
            });

            player.on('pause', function () {
              selfClass.pauseMovie_visual();
            });



            selfClass._videoElement.addEventListener('fullscreenchange', selfClass.fullscreenHandleChange, false);
            selfClass._videoElement.addEventListener('webkitfullscreenchange', selfClass.fullscreenHandleChange, false);
          }

        }

      }


      if (window.Vimeo) {
        vimeo_addListeners()
      } else {

        dzsvgHelpers.load_outside_script(ConstantsDzsvg.VIMEO_IFRAME_API);




        _global_vimeoIframeAPILoading = true;
        window._global_vimeoIframeAPILoading_inter = setInterval(function () {
          vimeo_addListeners();

        }, 500);
      }
    }

  }
  if (selfClass.dataType === 'selfHosted') {

    if ((selfClass.dataSrc) && argsForVideoSetup.is_dash === false) {
      if (selfClass.dataSrc && (selfClass.dataSrc.indexOf('.ogg') > -1 || selfClass.dataSrc.indexOf('.ogv') > -1)) {
        selfClass.cthis.attr('data-sourceogg', selfClass.dataSrc);
      }
    }


    if ((selfClass.dataSrc) && argsForVideoSetup.is_dash === false) {
      selfClass.cthis.find('.the-video').eq(0).append('<source src="' + selfClass.dataSrc + '"/>');
    }


    if (selfClass.cthis.attr('data-sourceogg') !== undefined) {
      selfClass.cthis.find('.the-video').eq(0).append('<source src="' + selfClass.cthis.attr('data-sourceogg') + '" type="video/ogg"/>');
    }
    if (selfClass.cthis.attr('data-sourcewebm') !== undefined) {
      selfClass.cthis.find('.the-video').eq(0).append('<source src="' + selfClass.cthis.attr('data-sourcewebm') + '" type="video/webm"/>');
    }
  }





  if (argsForVideoSetup.is360) {
    argsForVideoSetup.Dzsvg360.initPlayer(selfClass);
  }


  function youtube_onPlayerReady(e) {

    if (selfClass._videoElement && selfClass._videoElement.getPlaybackQuality) {



      selfClass._videoElement.setPlaybackQuality(o.settings_suggestedQuality);
    }



    if (selfClass.shouldStartMuted) {
      selfClass.volume_mute();
      selfClass.is_muted_for_autoplay = true;
    }




    if (o.playfrom == 'last') {
      // TODO: dunno why we need this

      if (selfClass.autoplayVideo == 'off') {

        setTimeout(function () {

          selfClass.pauseMovie({
            'called_from': 'playfrom last'
          });
        }, 1000)
      }
    }


    return false;

    selfClass._videoElement.unloadModule('cc');
    selfClass._videoElement.unloadModule('captions');
    selfClass._videoElement.unloadModule("captions");
    selfClass._videoElement.setOption("captions", "track", {"languageCode": "en"});


  }


  function youtube_onPlayerStateChange(e) {

    /*
               -1 – unstarted
               0 – ended
               1 – playing
               2 – paused
               3 – buffering
               5 – video cued
               */




    if (e.data === 1) { // -- playing
      // -play







      if (selfClass.queue_goto_perc) {
        selfClass.seek_to_perc(selfClass.queue_goto_perc);
        selfClass.queue_goto_perc = '';
      }


      if (selfClass.ad_status == 'waiting_for_play') {
        selfClass.setup_skipad();
      }

      selfClass.check_if_ad_must_be_played();
      selfClass.check_if_hd_available();

      if (selfClass.youtube_queue_change_quality) {

        selfClass._videoElement.setPlaybackQuality(o.settings_suggestedQuality);
        selfClass.youtube_queue_change_quality = '';
      }

      selfClass.playMovie_visual();
      selfClass.paused = false;
      selfClass.wasPlaying = true;

      selfClass.isInitialPlayed = true;


      if (dzsvgHelpers.is_mobile()) {
        selfClass.cthis.find('.controls').eq(0).css('pointer-events', 'auto');

      }

    }
    if (e.data === 2) {


      if (selfClass.suspendStateForLoop === false) {

        selfClass.pauseMovie({
          'called_from': 'state_2_for_youtube'
        });
      }
      selfClass.paused = true;
      selfClass.wasPlaying = false;
    }

    if (selfClass._videoElement && selfClass._videoElement.getPlaybackQuality) {

    }


    if (e.data === 3) {
      // -- on player play, set the volume again
      selfClass.volumeClass.volume_setInitial();

    }
    if (e.data === 5) {


    }
    if (e.data === 0) {

      // -- handlevideo end
      selfClass.handleVideoEnd();
    }
  }


  function onPlayerPlaybackQualityChange(e) {



  }
}
