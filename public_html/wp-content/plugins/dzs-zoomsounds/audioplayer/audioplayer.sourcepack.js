/*
 * Author: Audio Player with Playlist
 * Website: https://digitalzoomstudio.net/
 * Portfolio: https://bit.ly/nM4R6u
 * Version: 6.41
 * */
"use strict";

import {
  configureAudioPlayerOptionsInitial,
  dzsap_is_mobile,
  dzsap_singleton_ready_calls,
  is_android,
  is_ios,
  is_safari,
  is_android_good,
  player_adjustIdentifiers,
  player_delete,
  player_detect_skinwave_mode,
  player_determineActualPlayer,
  player_determineHtmlAreas,
  player_getPlayFromTime,
  generateFakeArrayForPcm,
  player_identifySource,
  player_identifyTypes,
  player_stopOtherPlayers,
  player_syncPlayers_buildList,
  player_syncPlayers_gotoItem,
  player_viewApplySkinWaveModes,
  playerFunctions,
  player_service_getSourceProtection,
  player_isGoingToSetupMediaNow,
  player_icecastOrShoutcastRefresh,
  player_determineStickToBottomContainer,
  player_stickToBottomContainerDetermineClasses,
  waitForScriptToBeAvailableThenExecute,
  scrubbar_modeWave_setupCanvas,
  player_initSpectrumOnUserAction,
  player_view_addMetaLoaded,
  formatTime,
  sanitizeToIntFromPointTime,
  view_player_globalDetermineSyncPlayersIndex,
  view_player_playMiscEffects,
  player_initSpectrum,
  assignHelperFunctionsToJquery,
  convertPluginOptionsToFinalOptions,
  jQueryAuxBindings, playerRegisterWindowFunctions
} from './jsinc/_dzsap_helpers';
import {getBaseUrl, isInt, loadScriptIfItDoesNotExist} from './js_common/_dzs_helpers';
import {
  buildAudioElementHtml,
  makeMediaPreloadInTheFuture,
  media_tryToPlay,
  repairMediaElement,
  setupMediaElement,
  media_pause,
  setupMediaListeners
} from "./jsinc/media/_media_functions";
import {media_changeMedia} from './jsinc/media/_onePlayer_changeMedia';
import {PlayerTime} from './jsinc/_dzsap_time_model';
import {
  ajax_retract_like,
  ajax_submit_download,
  ajax_submit_like,
  ajax_submit_total_time,
  ajax_submit_views
} from "./jsinc/_dzsap_ajax";

import {ConstantsDzsAp} from "./configs/_constants";
import {_ClassMetaParts} from "./jsinc/helper-classes/_ClassMetaParts";
import {comments_selector_event, comments_setupCommentsInitial} from "./jsinc/components/_comments";
import {pausebtn_svg, playbtn_svg, svg_share_icon} from "./jsinc/_dzsap_svgs";
import {registerToJquery} from "./jsinc/_dzsap_playlist";
import {retrieve_soundcloud_url} from "./jsinc/_dzsap_misc";
import {setup_structure} from "./jsinc/components/_structure";
import {
  draw_canvas,
  scrubModeWave__checkIfWeShouldTryToGetPcm,
  view_drawCanvases
} from "./jsinc/wave-render/_wave-render-functions";
import {defaultPlayerOptions} from "./configs/_settingsPlayer";
import {dzsap_oneTimeSetups} from "./jsinc/player/_player-one-time-setups";
import {dzsap_generate_keyboard_controls} from "./jsinc/player/_player_keyboard";


window.dzsap_list = []; // -- this is for the players

var dzsap_globalidind = 20;


window.loading_multi_sharer = false;

window.dzsap_player_interrupted_by_dzsap = null;
window.dzsap_audio_ctx = null;
window.dzsap__style = null;
window.dzsap_sticktobottom_con = null;

window.dzsap_self_options = {};

window.dzsap_generating_pcm = false;
window.dzsap_box_main_con = null;
window.dzsap_lasto = null;
window.dzsap_syncList_players = []; // -- used for next .. prev .. footer playlist
window.dzsap_syncList_index = 0; // -- used for next .. prev .. footer playlist
window.dzsap_base_url = '';

window.dzsap_player_index = 0; // -- the player index on the page

/**
 * Main player class
 * @class
 * @property {boolean} isAlreadyHasRealPcm
 * @property {boolean} isPcmRequiredToGenerate
 * @property {boolean} isMultiSharer
 * @property {string} identifier_pcm
 * @property {string} urlToAjaxHandler
 * @property {HTMLElement} _sourcePlayer
 * @property {HTMLElement} $mediaNode_
 * @constructor
 * @public
 */
class DzsAudioPlayer {
  constructor(argThis, argOptions, $) {

    this.argThis = argThis;
    this.argOptions = Object.assign({}, argOptions);
    this.$ = $;
    this.cthis = null;

    this.ajax_view_submitted = 'auto';
    this.increment_views = 0;
    this.the_player_id = '';
    this.currIp = '127.0.0.1';
    this.index_extrahtml_toloads = 0;
    this.starrating_alreadyrated = -1;
    this.data_source = '';

    this.urlToAjaxHandler = null;


    this.playFrom = '';


    this._actualPlayer = null;
    this._audioplayerInner = null;
    this._metaArtistCon = null;
    this._conControls = null;
    this._conPlayPauseCon = null;
    this._apControls = null;
    this._apControlsLeft = null;
    this._apControlsRight = null;
    this._commentsHolder = null;
    this.$mediaNode_ = null;
    this._scrubbar = null;
    this._scrubbarbg_canvas = null;
    this._scrubbarprog_canvas = null;
    this.$feed_fakeButton = null;
    this._sourcePlayer = null;
    this.$realVisualPlayer = null; // -- real visual player can be _sourcePlayer if this is a fake feed or this if not
    this.$theMedia = null;
    this.$conPlayPause = null; // -- [selector] .con-playpause
    this.$conControls = null;
    this.$$scrubbProg = null;
    this.$controlsVolume = null;
    this.$currTime = null;
    this.$totalTime = null;
    this.$commentsWritter = null;
    this.$commentsChildren = null;
    this.$commentsSelector = null;
    this.$embedButton = null;
    /** a reflection object for triggering the player from outside */
    this.$reflectionVisualObject = null;


    this.audioType = 'normal';
    this.audioTypeSelfHosted_streamType = '';
    this.skinwave_mode = 'normal';
    this.action_audio_comment = null; // -- set a outer ended function ( for example for tracking your analytics

    this.commentPositionPerc = 0;// --the % at which the comment will be placed

    this.spectrum_audioContext = null;
    this.spectrum_audioContextBufferSource = null;
    this.spectrum_audioContext_buffer = null;
    this.spectrum_mediaElementSource = null;
    this.spectrum_analyser = null;
    this.spectrum_gainNode = null;

    this.isMultiSharer = false;
    this.hasInitialPcmData = false;

    this.lastArray = null;
    this.last_lastArray = null;

    this.player_playing = false;

    this.actualDataTypeOfMedia = 'audio'; // "audio" or

    this.youtube_retryPlayTimeout = 0;
    this.lastTimeInSeconds = 0;

    // -- sticky player

    this.isStickyPlayer = false;
    this.$stickToBottomContainer = null;


    // -- pcm
    this.uniqueId = '';
    this.identifier_pcm = ''; // -- can be either player id or source if player id is not set
    this.isAlreadyHasRealPcm = false;
    this.isPcmTryingToGenerate = false;
    this.isPlayPromised = false // -- we are promising generating on meta load
    this.isCanvasFirstDrawn = false // -- the first draw on canvas
    this.isPlayerLoaded = false;

    this.original_real_mp3 = '' // -- this is the original real mp3 for sainvg and identifying in the database

    // -- theme specific
    this.skin_minimal_canvasplay = null;

    this.classFunctionalityInnerPlaylist = null;
    this.feedEmbedCode = '';

    this.youtube_currentId = 0;
    this.youtube_isInited = false;

    this.extraHtmlAreas = {
      bottom: '',
      afterArtist: '',
      controlsLeft: '',
      controlsRight: '',
    }

    // -- time vars
    this.sample_time_start = 0;
    this.sample_time_end = 0;
    this.sample_time_total = 0;

    this.playlist_inner_currNr = 0

    this.timeCurrent = 0; // -- *deprecated
    this.timeModel = new PlayerTime(this);

    this.isSample = false;
    this.isSafeToChangeTrack = false // -- only after 2 seconds of init is it safe to change track
    this.isMediaEnded = false;
    /** is first setuped media */
    this.isSetupedMedia = false;
    this.isSentCacheTotalTime = false;
    this.isPcmRequiredToGenerate = false;
    this.radio_isGoingToUpdateSongName = false;
    this.radio_isGoingToUpdateArtistName = false;
    this.mediaProtectionStatus = 'unprotected';

    this.classMetaParts = new _ClassMetaParts(this);

    this.inter_isEnded = 0;


    argThis.SelfInstance = this;
    this.classInit();
  }

  set_sourcePlayer($arg) {
    if ($arg) {
      if ($arg.get(0) != this.cthis.get(0)) {
        this._sourcePlayer = $arg;
      }
    } else {
      this._sourcePlayer = $arg;
    }
  }


  reinit_beforeChangeMedia() {
    this.hasInitialPcmData = false;
    this.isPcmRequiredToGenerate = false;
    this.isAlreadyHasRealPcm = false;
    this.cthis.attr('data-pcm', '');
  }

  reinit_resetMetrics() {
    this.isPlayerLoaded = false;
  }


  service_checkIfWeShouldUpdateTotalTime() {
    ajax_submit_total_time(this);
  }

  classInit() {

    var $ = this.$;
    var o = this.argOptions;
    var cthis = $(this.argThis);

    var selfClass = this;


    selfClass.cthis = cthis;
    selfClass.initOptions = o;

    var cthisId = 'ap1'
    ;
    var ww, cthisWidth, th // -- controls width
      , scrubbarWidth = 0 // -- scrubbar width
      , scrubbarProgX = 0 // -- scrubbar prog pos
    ;
    var _theThumbCon
      , $scrubBgCanvas = null,
      _scrubBgCanvasCtx = null;
    var isMuted = false,
      isDestroyed = false,
      google_analytics_sent_play_event = false,
      destroyed_for_rebuffer = false
      , media_isLoopActive = false // -- if loop_active the track will loop
      , curr_time_first_set = false
      , isScrubShowingCurrentTime = false
      , isListenersSetup = false
    ;
    var last_time_total = 0
      , currTime_outerWidth = 0
      , player_index_in_gallery = -1 // -- the player index in gallery
    ;


    var volume_lastVolume = 1,
      last_vol_before_mute = 1
    ;
    var inter_checkReady
      , inter_60_secs_contor = 0
      , inter_trigger_resize;
    var data_station_main_url = ''
    ;

    var isResolveThumbHeight = false
      , volume_dragging = false
      , volume_set_by_user = false // -- this shows if the user actioned on the volume
      , is_under_400 = false


    ; // resize thumb height


    var skin_minimal_button_size = 0;

    // -- touch controls
    var scrubbar_moving = false
      , scrubbar_moving_x = 0
      , aux3 = 0
    ;


    var dataSrc = '';
    var canvasWidth = 100;
    var canh = 100;
    var scrubbar_h = 75
      , design_thumbh
    ;


    var stringAudioElementHtml = '';


    var defaultVolume = 1;

    var action_audio_end = null
      , action_audio_play = null
      , action_audio_play2 = null
      , action_audio_pause = null


    var isNotRenderingEnterFrame = true
      , sw_spectrum_fakeit = 'auto'
    ;


    var duration_viy = 20;
    var begin_viy = 0;
    var change_viy = 0;


    var draw_canvas_inter = 0;


    window.dzsap_player_index++;


    selfClass.timeModel.getSampleTimesFromDom();


    init();

    function init() {


      if (cthis.hasClass('dzsap-inited')) {
        return false;
      }

      selfClass.play_media_visual = play_media_visual;
      selfClass.play_media = play_media;
      selfClass.pause_media = pause_media;
      selfClass.pause_media_visual = pause_media_visual;
      selfClass.seek_to = seek_to;
      selfClass.reinit = reinit;

      selfClass.handle_end = media_handleEnd;
      selfClass.init_loaded = init_loaded;
      selfClass.scrubbar_reveal = scrubbar_reveal;
      selfClass.calculate_dims_time = calculate_dims_time;
      selfClass.check_multisharer = check_multisharer;
      selfClass.setup_structure_scrub = setup_structure_scrub;
      selfClass.setup_structure_sanitizers = setup_structure_sanitizers;
      selfClass.destroy_cmedia = destroy_cmedia;
      selfClass.view_drawCurrentTime = view_drawCurrentTime;
      selfClass.setupStructure_thumbnailCon = setupStructure_thumbnailCon;
      selfClass.setup_pcm_random_for_now = view_wave_setupRandomPcm;
      selfClass.handleResize = view_handleResize;
      selfClass.destroy_media = destroy_media;

      cthis.css({'opacity': ''});
      cthis.addClass('dzsap-inited');
      window.dzsap_player_index++;


      selfClass.keyboard_controls = dzsap_generate_keyboard_controls();

      configureAudioPlayerOptionsInitial(cthis, o, selfClass);

      if (o.loop == 'on') {
        media_isLoopActive = true;
      }

      (player_detect_skinwave_mode.bind(selfClass))()


      if (o.design_skin === 'skin-default') {
        if (o.design_thumbh === 'default') {
          design_thumbh = cthis.height() - 40;
          isResolveThumbHeight = true;
        }
      }


      if (dzsap_is_mobile()) {
        $('body').addClass('is-mobile');
        if (o.mobile_delete === 'on') {
          player_delete(selfClass);
        }
        // -- disable fakeplayer on mobile for some reason
        if (o.mobile_disable_fakeplayer === 'on') {
          selfClass.cthis.attr('data-fakeplayer', '');
        }
      }


      player_viewApplySkinWaveModes(selfClass);


      if (o.design_thumbh === 'default') {
        design_thumbh = 200;
      }


      playerFunctions(selfClass, 'detectIds');


      if (cthis.attr('data-fakeplayer')) {
        player_determineActualPlayer(selfClass);
      }

      selfClass.cthis.addClass('scrubbar-type-' + o.scrubbar_type);


      player_determineHtmlAreas(selfClass);


      // -- syncPlayers build
      if (window.dzsap_settings.syncPlayers_buildList === 'on') {
        player_syncPlayers_buildList()
      }


      player_getPlayFromTime(selfClass);


      player_adjustIdentifiers(selfClass);
      player_identifySource(selfClass);
      player_identifyTypes(selfClass);


      if (selfClass.audioType === 'youtube') {
        window.dzsap_get_base_url();
        const scriptUrl = window.dzsap_base_url ? window.dzsap_base_url + '/parts/youtube/dzsap-youtube-functions.js' : '';
        loadScriptIfItDoesNotExist(scriptUrl, window.dzsap_youtube_functions_inited).then((resolveStr) => {
          window.dzsap_youtube_functions_init(selfClass);
        });
      }


      selfClass.audioTypeSelfHosted_streamType = '';


      if (selfClass.audioType === 'selfHosted') {
        if (cthis.attr('data-streamtype') && cthis.attr('data-streamtype') !== 'off') {
          selfClass.audioTypeSelfHosted_streamType = cthis.attr('data-streamtype');
          data_station_main_url = selfClass.data_source;
          cthis.addClass('is-radio-type');
        } else {
          selfClass.audioTypeSelfHosted_streamType = '';
        }
      }

      // -- no shoutcast autoupdate at the moment 2 3 4 5 6 7 8
      if (selfClass.audioTypeSelfHosted_streamType === 'shoutcast') {

        // -- todo: we
      }


      // -- we disable the function if audioplayer inited
      if (cthis.hasClass('audioplayer')) {
        return;
      }

      if (cthis.attr('id') !== undefined) {
        cthisId = cthis.attr('id');
      } else {
        cthisId = 'ap' + dzsap_globalidind++;
      }


      selfClass.youtube_currentId = 'ytplayer_' + cthisId;


      cthis.removeClass('audioplayer-tobe');
      cthis.addClass('audioplayer');

      view_drawScrubProgress();
      setTimeout(function () {
        view_drawScrubProgress()
      }, 1000);


      //===ios does not support volume controls so just let it die
      //====== .. or autoplay FORCE STAFF


      if (o.cueMedia === 'off') {

        // -- cue is forcing autoplay on
        cthis.addClass('cue-off');
        o.autoplay = 'on';
      }


      //====sound cloud INTEGRATION //
      if (selfClass.audioType === 'soundcloud') {
        retrieve_soundcloud_url(selfClass);
      }
      // -- END soundcloud INTEGRATION//


      setup_structure(selfClass); //  -- inside init()

      // --   trying to access the youtube api with ios did not work


      if (o.scrubbar_type === 'wave' && (selfClass.audioType === 'selfHosted' || selfClass.audioType === 'soundcloud' || selfClass.audioType === 'fake') && o.skinwave_comments_enable === 'on') {
        comments_setupCommentsInitial(selfClass);
      }


      if (o.autoplay === 'on' && o.cueMedia === 'on') {
        selfClass.increment_views = 1;
      }


      // -- soundcloud will setupmedia when api done

      if (o.cueMedia === 'on' && selfClass.audioType !== 'soundcloud') {
        if (is_android() || is_ios()) {
          cthis.find('.playbtn').on('click', play_media);
        }


        if (selfClass.data_source && selfClass.data_source.indexOf('{{generatenonce}}') > -1) {


          selfClass.mediaProtectionStatus = 'fetchingProtection';
          player_service_getSourceProtection(selfClass).then((response) => {
            if (response) {
              cthis.attr('data-source', response);
              setup_media({'called_from': 'nonce generated', 'newSource': response});

              selfClass.mediaProtectionStatus = 'protectedMode';
            }

          });
        } else {

          const isGoingToSetupMediaNow = player_isGoingToSetupMediaNow(selfClass);

          if (selfClass.audioType === 'selfHosted') {
            if (selfClass.audioTypeSelfHosted_streamType === 'icecast' || selfClass.audioTypeSelfHosted_streamType === 'shoutcast') {
              // -- if we have icecast we can update currently playing song


              if (selfClass.audioTypeSelfHosted_streamType === 'icecast' || (selfClass.radio_isGoingToUpdateArtistName || selfClass.radio_isGoingToUpdateSongName)) {

                player_icecastOrShoutcastRefresh(selfClass);
              }
              setInterval(function () {
                player_icecastOrShoutcastRefresh(selfClass);
              }, 10000)
            }
          }

          if (isGoingToSetupMediaNow) {
            setup_media({'called_from': 'normal setup media .. --- icecast must wait'});
          }

        }


      } else {


        cthis.find('.playbtn').on('click', handleClickForSetupMedia);
        cthis.find('.scrubbar').on('click', handleClickForSetupMedia);
        view_handleResize(null, {
          called_from: 'init'
        });
      }


      // -- we call the api functions here


      player_determineStickToBottomContainer(selfClass);
      player_stickToBottomContainerDetermineClasses(selfClass);


      selfClass.timeModel.initObjects();

      // -- api calls
      selfClass.setup_media = setup_media;

      cthis.get(0).classInstance = selfClass;

      cthis.get(0).api_init_loaded = init_loaded; // -- force resize event
      cthis.get(0).api_destroy = destroy_it; // -- destroy the player and the listeners

      cthis.get(0).api_play = play_media; // -- play the media

      cthis.get(0).api_set_volume = volume_setVolume; // -- set a volume
      cthis.get(0).api_get_last_vol = volume_getLast; // -- play the media

      cthis.get(0).api_get_source = () => {
        return selfClass.data_source;
      }; // -- play the media

      cthis.get(0).api_click_for_setup_media = handleClickForSetupMedia; // -- play the media

      cthis.get(0).api_handleResize = view_handleResize; // -- force resize event
      cthis.get(0).api_set_playback_speed = set_playback_speed; // -- set the playback speed, only works for local hosted mp3
      cthis.get(0).api_change_media = media_changeMedia(selfClass, $); // -- change the media file from the API
      cthis.get(0).api_seek_to_perc = seek_to_perc; // -- seek to percentage ( for example seek to 0.5 skips to half of the song )
      cthis.get(0).api_seek_to = seek_to; // -- seek to percentage ( for example seek to 0.5 skips to half of the song )
      cthis.get(0).api_seek_to_visual = seek_to_visual; // -- seek to perchange but only visually ( does not actually skip to that ) , good for a fake player
      cthis.get(0).api_visual_set_volume = volume_setOnlyVisual; // -- set a volume
      cthis.get(0).api_destroy_listeners = destroy_listeners; // -- set a volume

      cthis.get(0).api_pause_media = pause_media; // -- pause the media
      cthis.get(0).api_get_media_isLoopActive = () => {
        return media_isLoopActive;
      }; // -- pause the media
      cthis.get(0).api_media_toggleLoop = media_toggleLoop; // -- pause the media
      cthis.get(0).api_pause_media_visual = pause_media_visual; // -- pause the media, but only visually
      cthis.get(0).api_play_media = play_media; // -- play the media
      cthis.get(0).api_play_media_visual = play_media_visual; // -- play the media, but only visually
      cthis.get(0).api_handle_end = media_handleEnd; // -- play the media, but only visually
      cthis.get(0).api_change_visual_target = change_visual_target; // -- play the media, but only visually
      cthis.get(0).api_change_design_color_highlight = view_updateColorHighlight; // -- play the media, but only visually
      cthis.get(0).api_draw_scrub_prog = view_drawScrubProgress; // -- render the scrub progress
      cthis.get(0).api_draw_curr_time = view_drawCurrentTime; // -- render the current time
      cthis.get(0).api_get_times = selfClass.timeModel.refreshTimes; // -- refresh the current time
      cthis.get(0).api_check_time = handleEnterFrame; // -- do actions required in the current frame
      cthis.get(0).api_sync_players_goto_next = syncPlayers_gotoNext; // -- in the sync playlist, go to the next song
      cthis.get(0).api_sync_players_goto_prev = syncPlayers_gotoPrev; // -- in the sync playlist, go to the previous song
      cthis.get(0).api_regenerate_playerlist_inner = function () {
        // -- call with window.dzsap_generate_list_for_sync_players({'force_regenerate': true})
        if (selfClass.classFunctionalityInnerPlaylist) {
          selfClass.classFunctionalityInnerPlaylist.playlistInner_setupStructureInPlayer();
        }

      }; // -- regenerate the playlist innter


      cthis.get(0).api_step_back = function (arg) {
        if (!arg) {
          arg = selfClass.keyboard_controls.step_back_amount;
        }
        seek_to(selfClass.timeCurrent - arg);
      }
      cthis.get(0).api_step_forward = function (arg) {

        if (arg) {

        } else {
          arg = selfClass.keyboard_controls.step_back_amount;
        }
        seek_to(selfClass.timeCurrent + arg);
      } // --
      /**
       *
       * @param {number} argSpeed  - 0 to 1
       */
      cthis.get(0).api_playback_speed = function (argSpeed) {
        if (selfClass.$mediaNode_ && selfClass.$mediaNode_.playbackRate) {
          selfClass.$mediaNode_.playbackRate = argSpeed;
        }
      } // -- slow to 2/3 of the current song


      cthis.get(0).api_set_action_audio_play = function (arg) {
        action_audio_play = arg;
      }; // -- set action on audio play
      cthis.get(0).api_set_action_audio_pause = function (arg) {
        action_audio_pause = arg;
      }; // -- set action on audio pause
      cthis.get(0).api_set_action_audio_end = function (arg) {
        action_audio_end = arg;
        cthis.data('has-action-end', 'on');
      }; // -- set action on audio end
      cthis.get(0).api_set_action_audio_comment = function (arg) {
        selfClass.action_audio_comment = arg;
      }; // -- set the function to call on audio song comment
      cthis.get(0).api_try_to_submit_view = service_submitView; // -- try to submit a new play analytic


      if (o.action_audio_play) {
        action_audio_play = o.action_audio_play;
      }
      ;
      if (o.action_audio_pause) {
        action_audio_pause = o.action_audio_pause;
      }
      ;
      if (o.action_audio_play2) {
        action_audio_play2 = o.action_audio_play2;
      }
      ;

      if (o.action_audio_end) {
        action_audio_end = o.action_audio_end;
        cthis.data('has-action-end', 'on');
      }


      handleEnterFrame({
        'fire_only_once': true
      });


      if (o.design_skin === 'skin-minimal') {
        handleEnterFrame({
          'fire_only_once': true
        });
      }


      cthis.on('click', '.dzsap-repeat-button,.dzsap-loop-button,.btn-zoomsounds-download,.zoomsounds-btn-step-backward,.zoomsounds-btn-step-forward,.zoomsounds-btn-go-beginning,.zoomsounds-btn-slow-playback,.zoomsounds-btn-reset, .tooltip-indicator--btn-footer-playlist', handle_mouse);
      cthis.on('mouseenter', handle_mouse);
      cthis.on('mouseleave', handle_mouse);


      selfClass.$conPlayPause.on('click', handleClick_playPause);

      cthis.on('click', '.skip-15-sec', function () {
        cthis.get(0).api_step_forward(15);
      });


      $(window).on('resize.dzsap', view_handleResize);
      view_handleResize(null, {
        called_from: 'init'
      });

      if (selfClass._scrubbar && selfClass._scrubbar.get(0)) {

        selfClass._scrubbar.get(0).addEventListener('touchstart', function (e) {
          if (selfClass.player_playing) {

            scrubbar_moving = true;
          }
        }, {passive: true})
      }

      $(document).on('touchmove', function (e) {
        if (scrubbar_moving) {
          scrubbar_moving_x = e.originalEvent.touches[0].pageX;


          aux3 = scrubbar_moving_x - selfClass._scrubbar.offset().left;

          if (aux3 < 0) {
            aux3 = 0;
          }
          if (aux3 > selfClass._scrubbar.width()) {
            aux3 = selfClass._scrubbar.width();
          }

          seek_to_perc(aux3 / selfClass._scrubbar.width(), {
            call_from: "touch move"
          });


          return false;

        }
      });

      $(document).on('touchend', function (e) {
        scrubbar_moving = false;
      });

      if (o.skinwave_comments_mode_outer_selector) {
        selfClass.$commentsSelector = $(o.skinwave_comments_mode_outer_selector);

        if (selfClass.$commentsSelector.data) {

          selfClass.$commentsSelector.data('parent', cthis);

          if (window.dzsap_settings.comments_username) {
            selfClass.$commentsSelector.find('.comment_email,*[name=comment_user]').remove();
          }

          selfClass.$commentsSelector.on('click', '.dzstooltip--close,.comments-btn-submit', comments_selector_event);
          selfClass.$commentsSelector.on('focusin', 'input', comments_selector_event);
          selfClass.$commentsSelector.on('focusout', 'input', comments_selector_event);

        } else {
          console.log('%c, data not available .. ', 'color: #990000;', $(o.skinwave_comments_mode_outer_selector));
        }
      }

      cthis.off('click', '.btn-like');
      cthis.on('click', '.btn-like', handleClickLike);


      waitForScriptToBeAvailableThenExecute(window.dzsap_part_starRatings_loaded, function () {
        window.dzsap_init_starRatings_from_dzsap(selfClass);
      })


      setTimeout(function () {

        view_handleResize(null, {
          called_from: 'init_timeout'
        });


        if (o.skinwave_wave_mode === 'canvas') {

          calculate_dims_time();

          setTimeout(function () {
            calculate_dims_time();


          }, 100)
        }

      }, 100)


      cthis.find('.btn-menu-state').eq(0).on('click', handleClickMenuState);

      cthis.on('click', '.prev-btn,.next-btn', handle_mouse);
    }


    function calculate_dims_time() {
      var reflection_size = parseFloat(o.skinwave_wave_mode_canvas_reflection_size);

      reflection_size = 1 - reflection_size;

      var scrubbarh = selfClass._scrubbar.height();
      if (o.design_skin === 'skin-wave') {
        if (selfClass.skinwave_mode === 'small') {
          scrubbarh = 60;
        }

        if (selfClass._commentsHolder) {
          if (reflection_size === 0) {
            selfClass._commentsHolder.css('top', selfClass._scrubbar.offset().top - cthis.offset().top + scrubbarh * reflection_size - selfClass._commentsHolder.height());
          } else {
            selfClass._commentsHolder.css('top', selfClass._scrubbar.offset().top - selfClass._scrubbar.parent().offset().top + scrubbarh * reflection_size);
            selfClass.$commentsWritter.css('top', selfClass._scrubbar.offset().top - selfClass._scrubbar.parent().offset().top + scrubbarh * reflection_size);
          }
        }

        if (selfClass.$currTime) {
          selfClass.$currTime.css('top', scrubbarh * reflection_size - selfClass.$currTime.outerHeight());
        }
        if (selfClass.$totalTime) {
          selfClass.$totalTime.css('top', scrubbarh * reflection_size - selfClass.$totalTime.outerHeight());
        }
      }

      cthis.attr('data-reflection-size', reflection_size);
    }

    function change_visual_target(arg, pargs) {
      // -- change the visual target, the main is the main palyer selfClass.player_playing and the visual target is the player which is a visual representation of this


      var margs = {}

      if (pargs) {
        margs = $.extend(margs, pargs);
      }


      if (selfClass._sourcePlayer && selfClass._sourcePlayer.get(0) && selfClass._sourcePlayer.get(0).api_pause_media_visual) {
        selfClass._sourcePlayer.get(0).api_pause_media_visual({
          'call_from': 'change_visual_target'
        });
      }

      selfClass.set_sourcePlayer(arg);

      var $sourcePlayer_ = selfClass._sourcePlayer.get(0);
      if (selfClass.player_playing) {
        if (selfClass._sourcePlayer && $sourcePlayer_ && $sourcePlayer_.api_play_media_visual) {
          $sourcePlayer_.api_play_media_visual();
        }
      }

      if ($sourcePlayer_ && $sourcePlayer_.api_draw_curr_time) {


        $sourcePlayer_.api_set_timeVisualCurrent(selfClass.timeCurrent);
        $sourcePlayer_.api_get_times({
          'call_from': ' change visual target .. in api '
        });
        $sourcePlayer_.api_check_time({
          'fire_only_once': true
        });
        $sourcePlayer_.api_draw_curr_time();
        $sourcePlayer_.api_draw_scrub_prog();
      }

      setTimeout(function () {

        if ($sourcePlayer_ && $sourcePlayer_.api_draw_curr_time) {
          $sourcePlayer_.api_get_times();
          $sourcePlayer_.api_check_time({
            'fire_only_once': true
          });
          $sourcePlayer_.api_draw_curr_time();
          $sourcePlayer_.api_draw_scrub_prog();
        }
      }, 800);

    }

    function view_updateColorHighlight(arg) {

      o.design_wave_color_progress = arg;
      if (o.skinwave_wave_mode === 'canvas') {
        view_drawCanvases(selfClass, cthis.attr('data-pcm'), 'canvas_change_pcm');

      }

    }

    function reinit() {

      if (selfClass.audioType === 'normal' || selfClass.audioType === 'detect' || selfClass.audioType === 'audio') {
        selfClass.audioType = 'selfHosted';
      }
    }


    function destroy_listeners() {


      if (isDestroyed) {
        return false;
      }


      isNotRenderingEnterFrame = true;

    }

    function handleClickLike() {
      var _t = $(this);
      if (cthis.has(_t).length === 0) {
        return;
      }

      if (_t.hasClass('active')) {
        (ajax_retract_like.bind(selfClass))();
      } else {
        (ajax_submit_like.bind(selfClass))();
      }
    }


    function destroy_it() {


      if (isDestroyed) {
        return false;
      }

      if (selfClass.player_playing) {
        pause_media();
      }


      $(window).off('resize.dzsap');

      cthis.remove();
      cthis = null;

      isDestroyed = true;
    }

    function handleClickForSetupMedia(e, pargs) {

      var margs = {

        'do_not_autoplay': false
      };

      if (pargs) {
        margs = $.extend(margs, pargs);
      }

      cthis.find('.playbtn').unbind('click', handleClickForSetupMedia);
      cthis.find('.scrubbar').unbind('click', handleClickForSetupMedia);

      setup_media(margs);


      if (is_android() || is_ios()) {
        play_media({
          'called_from': 'click_for_setup_media'
        });
      }
    }


    function handleClickMenuState(e) {
      if (o.parentgallery && typeof (o.parentgallery.get(0)) !== "undefined") {
        o.parentgallery.get(0).api_toggle_menu_state();
      }
    }


    function init_checkIfReady(pargs) {
      var margs = {

        'do_not_autoplay': false
      };

      if (selfClass._actualPlayer && is_ios()) {
        return false;
      }


      if (pargs) {
        margs = $.extend(margs, pargs);
      }

      if (selfClass.audioType === 'youtube') {
        init_loaded(margs);
      } else {
        if (typeof (selfClass.$mediaNode_) !== 'undefined' && selfClass.$mediaNode_) {


          if (selfClass.$mediaNode_.nodeName !== "AUDIO" || o.type === 'shoutcast') {
            init_loaded(margs);
          } else {
            if (is_safari()) {

              if (selfClass.$mediaNode_.readyState >= 1) {

                if (selfClass.isPlayerLoaded === false) {
                }

                init_loaded(margs);
                clearInterval(inter_checkReady);

                if (o.action_audio_loaded_metadata) {
                  o.action_audio_loaded_metadata(cthis);
                }
              }
            } else {
              if (selfClass.$mediaNode_.readyState >= 2) {
                if (selfClass.isPlayerLoaded === false) {
                }
                init_loaded(margs);
                clearInterval(inter_checkReady);

                if (o.action_audio_loaded_metadata) {
                  o.action_audio_loaded_metadata(cthis);
                }
              }
            }

          }
        }

      }

    }

    function scrubbar_reveal() {
      setTimeout(function () {
        selfClass.cthis.addClass('scrubbar-loaded');
      }, 1000);
    }


    function setupStructure_thumbnailCon() {
      if (cthis.attr('data-thumb')) {
        cthis.addClass('has-thumb');
        var aux_thumb_con_str = '';

        if (cthis.attr('data-thumb_link')) {
          aux_thumb_con_str += '<a href="' + cthis.attr('data-thumb_link') + '"';
        } else {
          aux_thumb_con_str += '<div';
        }
        aux_thumb_con_str += ' class="the-thumb-con"><div class="the-thumb" style=" background-image:url(' + cthis.attr('data-thumb') + ')"></div>';


        if (cthis.attr('data-thumb_link')) {
          aux_thumb_con_str += '</a>';
        } else {
          aux_thumb_con_str += '</div>';
        }


        if (cthis.children('.the-thumb-con').length) {
          aux_thumb_con_str = cthis.children('.the-thumb-con').eq(0);
        }


        if (o.design_skin !== 'skin-customcontrols') {
          if (o.design_skin === 'skin-wave' && (selfClass.skinwave_mode === 'small' || selfClass.skinwave_mode === 'alternate')) {

            if (selfClass.skinwave_mode === 'alternate') {
              selfClass._audioplayerInner.prepend(aux_thumb_con_str);
            } else {

              selfClass._apControlsLeft.prepend(aux_thumb_con_str);
            }
          } else if (o.design_skin === 'skin-steel') {


            selfClass._apControlsLeft.prepend(aux_thumb_con_str);
          } else {

            selfClass._audioplayerInner.prepend(aux_thumb_con_str);
          }
        }

        _theThumbCon = selfClass._audioplayerInner.children('.the-thumb-con').eq(0);
      } else {

        cthis.removeClass('has-thumb');
      }
    }


    function setup_structure_sanitizers() {

      if (cthis.hasClass('zoomsounds-wrapper-bg-bellow') && cthis.find('.dzsap-wrapper-buts').length === 0) {

        cthis.append('<div class="temp-wrapper"></div>');
        cthis.find('.temp-wrapper').append(selfClass.extraHtmlAreas.controlsRight);
        cthis.find('.temp-wrapper').children('*:not(.dzsap-wrapper-but)').remove();
        cthis.find('.temp-wrapper > .dzsap-wrapper-but').unwrap();
        cthis.find('.dzsap-wrapper-but').each(function () {
          var aux = $(this).html();

          aux = aux.replace('{{heart_svg}}', '\t&hearts;');
          aux = aux.replace('{{svg_share_icon}}', svg_share_icon);


          if ($(this).get(0) && $(this).get(0).outerHTML.indexOf('dzsap-multisharer-but') > -1) {
            selfClass.isMultiSharer = true;

          }

          $(this).html(aux);
        }).wrapAll('<div class="dzsap-wrapper-buts"></div>');
      }

      if (o.design_skin === 'skin-customcontrols') {
        cthis.html(String(cthis.html()).replace('{{svg_play_icon}}', playbtn_svg));
        cthis.html(String(cthis.html()).replace('{{svg_pause_icon}}', pausebtn_svg));
      }
    }


    /**
     * called if we have .dzsap-multisharer-but in html
     */
    function check_multisharer() {

      // -- we setup a box main here as a child of body

      selfClass.cthis.find('.dzsap-multisharer-but').data('cthis', cthis);
      selfClass.cthis.data('embed_code', selfClass.feedEmbedCode);
    }

    function view_wave_setupRandomPcm(pargs) {


      var margs = {
        call_from: 'default'
      }


      if (pargs) {
        margs = $.extend(margs, pargs);
      }

      var default_pcm = [];

      if (!(o.pcm_data_try_to_generate === 'on' && o.pcm_data_try_to_generate_wait_for_real_pcm === 'on')) {
        for (var i3 = 0; i3 < 200; i3++) {
          default_pcm[i3] = Number(Math.random()).toFixed(2);
        }
        default_pcm = JSON.stringify(default_pcm);

        cthis.addClass('rnd-pcm-for-now')
        cthis.attr('data-pcm', default_pcm);
      }


      scrubbar_modeWave_setupCanvas({}, selfClass);
      ;

    }


    /**
     * called from setup_structure
     */
    function setup_structure_scrub() {

      if (o.skinwave_enableSpectrum !== 'on') {
        if (o.skinwave_wave_mode === 'canvas') {

          if (cthis.attr('data-pcm')) {
            scrubbar_modeWave_setupCanvas({}, selfClass);
          } else {
            view_wave_setupRandomPcm();
          }
        }
      } else {

        // -- spectrum ON
        scrubbar_modeWave_setupCanvas({}, selfClass);
        // -- old spectrum code
        $scrubBgCanvas = selfClass.cthis.find('.scrub-bg-img').eq(0);
        _scrubBgCanvasCtx = $scrubBgCanvas.get(0).getContext("2d");
      }

    }
    ;


    /**
     * order -> init, setup_media, init_loaded
     * called from init() if not icecast or soundcloud
     * @param pargs
     * @returns {boolean}
     */
    function setup_media(pargs) {
      var setupMediaAttrs = {

        'do_not_autoplay': false,
        'called_from': 'default',
        'newSource': '',
      };


      if (pargs) {
        setupMediaAttrs = $.extend(setupMediaAttrs, pargs);
      }

      // -- these types should not exist
      if (selfClass.audioType === 'icecast' || selfClass.audioType === 'shoutcast') {
        selfClass.audioType = 'selfHosted';
      }

      if (o.cueMedia === 'off') {
        if (selfClass.ajax_view_submitted === 'auto') {
          // -- why is view submitted ?
          selfClass.increment_views = 1;
          selfClass.ajax_view_submitted = 'off';
        }
      }

      if (selfClass.isPlayerLoaded) {
        return;
      }
      if (cthis.attr('data-original-type') === 'youtube') {
        return;
      }


      if (selfClass.audioType === 'youtube') {
        dzsap_youtube_setupMedia(selfClass, setupMediaAttrs);
      }
      // -- END youtube


      if (setupMediaAttrs.newSource) {
        selfClass.data_source = setupMediaAttrs.newSource;
      }

      if (is_ios()) {
        o.preload_method = 'metadata';
      }

      const stringAudioElement = buildAudioElementHtml(selfClass, selfClass.audioTypeSelfHosted_streamType, 'setup_media');
      stringAudioElementHtml = stringAudioElement.stringAudioElementHtml;
      const stringAudioTagSource = stringAudioElement.stringAudioTagSource;

      if (selfClass.audioType === 'selfHosted' || selfClass.audioType === 'soundcloud') {
        if (setupMediaAttrs.called_from === 'change_media' || setupMediaAttrs.called_from === 'nonce generated') {

          if (is_ios() || is_android()) {

            // -- we append only the source to mobile devices as we need the thing to autoplay without user action

            setupMediaElement(selfClass, stringAudioElementHtml, stringAudioTagSource);

          } else {
            // -- normal desktop
            if (!(setupMediaAttrs.called_from === 'nonce generated' && selfClass._actualPlayer)) {

              setupMediaElement(selfClass, stringAudioElementHtml);
            }
          }
          // -- end change media
        } else {
          setupMediaElement(selfClass, stringAudioElementHtml);

          if (is_ios() || is_android()) {
            if (setupMediaAttrs.called_from === 'retrieve_soundcloud_url') {
              setTimeout(function () {
                pause_media();
              }, 500);
            }
          }
        }


        if (selfClass.$mediaNode_ && selfClass.$mediaNode_.addEventListener && selfClass.cthis.attr('data-source') !== 'fake') {
          setupMediaListeners(selfClass, setupMediaAttrs, init_loaded, volume_lastVolume, volume_setVolume)
        }

      }

      selfClass.cthis.addClass('media-setuped');


      if (setupMediaAttrs.called_from === 'change_media') {
        return false;
      }

      if (selfClass.audioType !== 'youtube') {
        if (selfClass.cthis.attr('data-source') === 'fake') {
          if (is_ios() || is_android()) {
            init_loaded(setupMediaAttrs);
          }
        } else {
          if (is_ios()) {

            setTimeout(function () {
              init_loaded(setupMediaAttrs);
            }, 1000);


          } else {

            // -- check_ready() will fire init_loaded()
            inter_checkReady = setInterval(function () {
              init_checkIfReady(setupMediaAttrs);
            }, 50);
          }

        }


        if (o.preload_method === 'none') {
          makeMediaPreloadInTheFuture(selfClass);
        }


        if (o.design_skin === 'skin-customcontrols' || o.design_skin === 'skin-customhtml') {
          cthis.find('.custom-play-btn,.custom-pause-btn').off('click');
          cthis.find('.custom-play-btn,.custom-pause-btn').on('click', handleClick_playPause);
        }

        if (o.failsafe_repair_media_element) {
          repairMediaElement(selfClass, stringAudioElementHtml);

        }
      }

      if (o.scrubbar_type === 'wave' && o.skinwave_enableSpectrum === 'on') {
        player_initSpectrumOnUserAction(selfClass);

      }

      selfClass.isSetupedMedia = true;
    }

    function destroy_cmedia() {
      // -- destroy cmedia

      $(selfClass.$mediaNode_).remove();
      selfClass.$mediaNode_ = null;
      selfClass.isSetupedMedia = false;
      selfClass.isPlayerLoaded = false;
    }

    function destroy_media() {
      pause_media();


      if (selfClass.$mediaNode_) {

        if (selfClass.$mediaNode_.children) {

        }

        if (o.type === 'audio') {
          selfClass.$mediaNode_.innerHTML = '';
          selfClass.$mediaNode_.load();
        }
      }

      console.log("DESTROY MEDIA");
      if (selfClass.$theMedia) {

        selfClass.$theMedia.children().remove();
        selfClass.$mediaNode_ = null;
        selfClass.isPlayerLoaded = false;
      }

      destroy_cmedia();

    }

    function setup_listeners() {


      if (isListenersSetup) {
        return false;
      }

      isListenersSetup = true;


      // -- adding scrubbar listeners
      selfClass._scrubbar.unbind('mousemove');
      selfClass._scrubbar.unbind('mouseleave');
      selfClass._scrubbar.unbind('click');
      selfClass._scrubbar.on('mousemove', handleMouseOnScrubbar);
      selfClass._scrubbar.on('mouseleave', handleMouseOnScrubbar);
      selfClass._scrubbar.on('click', handleMouseOnScrubbar);

      selfClass.$controlsVolume.on('click', '.volumeicon', volume_handleClickMuteIcon);

      selfClass.$controlsVolume.on('mousemove', volume_handleMouse);
      selfClass.$controlsVolume.on('mousedown', volume_handleMouse);


      $(document).on('mouseup', window, volume_handleMouse);

      if (o.design_skin === 'skin-silver') {
        cthis.on('click', '.volume-holder', volume_handleMouse);
      }

      cthis.find('.playbtn').unbind('click');

      setTimeout(view_handleResize, 300);
      setTimeout(view_handleResize, 2000);

      if (o.settings_trigger_resize > 0) {
        inter_trigger_resize = setInterval(view_handleResize, o.settings_trigger_resize);
      }

      cthis.addClass('listeners-setuped');

      return false;
    }


    function volume_getLast() {
      return volume_lastVolume;
    }

    /**
     * init laoded
     * @param pargs
     */
    function init_loaded(pargs) {
      if (cthis.hasClass('dzsap-loaded')) {
        return;
      }

      var margs = {
        'do_not_autoplay': false
        , 'call_from': 'init'
      };


      if (pargs) {
        margs = $.extend(margs, pargs);
      }

      setTimeout(function () {
        selfClass.isSafeToChangeTrack = true;
      }, 5000);


      if (typeof (selfClass.$mediaNode_) !== "undefined" && selfClass.$mediaNode_) {
        if (selfClass.$mediaNode_.nodeName === 'AUDIO') {
          selfClass.$mediaNode_.addEventListener('ended', media_handleEnd);
        }
      }

      clearInterval(inter_checkReady);
      clearTimeout(inter_checkReady);
      setup_listeners();


      setTimeout(function () {
        if (selfClass.$currTime && selfClass.$currTime.outerWidth() > 0) {
          currTime_outerWidth = selfClass.$currTime.outerWidth();
        }
      }, 1000);


      // -- this comes from cue off, no pcm data


      if (selfClass.isPcmRequiredToGenerate) {
        scrubModeWave__checkIfWeShouldTryToGetPcm(selfClass, {
          called_from: 'init_loaded()'
        });
      }

      if (selfClass.audioType !== 'fake' && margs.call_from !== 'force_reload_change_media') {
        if (o.settings_exclude_from_list !== 'on' && dzsap_list && dzsap_list.indexOf(cthis) === -1) {
          if (selfClass._actualPlayer === null) {
            dzsap_list.push(cthis);
          }
        }
        if (o.type_audio_stop_buffer_on_unfocus === 'on') {
          cthis.data('type_audio_stop_buffer_on_unfocus', 'on');
          cthis.get(0).api_destroy_for_rebuffer = function () {
            if (o.type === 'audio') {
              selfClass.playFrom = selfClass.$mediaNode_.currentTime;
            }
            destroy_media();
            destroyed_for_rebuffer = true;
          }

        }
      }

      if (selfClass.ajax_view_submitted === 'auto') {
        setTimeout(function () {
          if (selfClass.ajax_view_submitted === 'auto') {
            selfClass.ajax_view_submitted = 'off';
          }
        }, 1000);
      }

      selfClass.isPlayerLoaded = true;

      if (selfClass.data_source !== 'fake') {

        cthis.addClass('dzsap-loaded');
      }

      if (o.default_volume === 'default') {
        defaultVolume = 1;
      }

      if (isNaN(Number(o.default_volume)) === false) {
        defaultVolume = Number(o.default_volume);
      } else {
        if (o.default_volume === 'last') {


          if (localStorage !== null && selfClass.the_player_id) {

            if (localStorage.getItem('dzsap_last_volume_' + selfClass.the_player_id)) {

              defaultVolume = localStorage.getItem('dzsap_last_volume_' + selfClass.the_player_id);
            } else {

              defaultVolume = 1;
            }
          } else {

            defaultVolume = 1;
          }
        }
      }

      if (o.volume_from_gallery) {
        defaultVolume = o.volume_from_gallery;
      }

      volume_setVolume(defaultVolume, {
        call_from: "from_init_loaded"
      });


      if (isInt(selfClass.playFrom)) {
        seek_to(selfClass.playFrom, {
          call_from: 'from_playfrom'
        });
      }


      if (selfClass.playFrom === 'last') {
        // -- here we save last position
        if (typeof Storage !== 'undefined') {
          setTimeout(function () {
            selfClass.playFrom_ready = true;
          })


          if (typeof localStorage['dzsap_' + selfClass.the_player_id + '_lastpos'] !== 'undefined') {
            seek_to(localStorage['dzsap_' + selfClass.the_player_id + '_lastpos'], {
              'call_from': 'last_pos'
            });
          }
        }
      }

      if (margs.do_not_autoplay !== true) {
        if (o.autoplay === 'on' && o.cueMedia === 'on') {
          play_media({
            'called_from': 'do not autoplay not true ( init_loaded() ) '
          });
        }
        ;
      }

      if (selfClass.$mediaNode_ && selfClass.$mediaNode_.duration) {
        player_view_addMetaLoaded(selfClass);
      }

      reinit();

      handleEnterFrame({
        'fire_only_once': false
      });

      if (o.autoplay === 'off') {
        isNotRenderingEnterFrame = true;
      }

      cthis.addClass('init-loaded');

      setTimeout(function () {

        selfClass.timeModel.refreshTimes({
          'call_from': 'set timeout 500'
        });
        handleEnterFrame({
          'fire_only_once': true
        });

        cthis.find('.wave-download').on('click', handle_mouse);
      }, 500);

      setTimeout(function () {
        selfClass.timeModel.refreshTimes({
          'call_from': 'set timeout 1000'
        });

        handleEnterFrame({
          'fire_only_once': true
        });


      }, 1000);


      if (inter_60_secs_contor === 0 && o.action_video_contor_60secs) {
        inter_60_secs_contor = setInterval(count_60secs, 30000);
      }
    }


    function count_60secs() {
      if (o.action_video_contor_60secs && cthis.hasClass('is-playing')) {
        o.action_video_contor_60secs(cthis, '');
      }
    }

    /**
     *
     * @param {boolean} isGoingToActivate
     */
    function media_toggleLoop(isGoingToActivate) {
      media_isLoopActive = isGoingToActivate;
    }

    function handle_mouse(e) {
      var $t = $(this);

      if (e.type === 'click') {
        if ($t.hasClass('wave-download')) {
          (ajax_submit_download.bind(selfClass))();
        }
        if ($t.hasClass('prev-btn')) {
          handleClick_prevBtn();
        }
        if ($t.hasClass('next-btn')) {
          handleClick_nextBtn();
        }
        if ($t.hasClass('tooltip-indicator--btn-footer-playlist')) {

          $t.parent().find('.dzstooltip').toggleClass('active');
        }
        if ($t.hasClass('zoomsounds-btn-go-beginning')) {

          var _target = cthis;
          if (selfClass._actualPlayer) {
            _target = selfClass._actualPlayer;
          }

          _target.get(0).api_seek_to_0();
        }
        if ($t.hasClass('zoomsounds-btn-step-backward')) {

          var _target = cthis;
          if (selfClass._actualPlayer) {
            _target = selfClass._actualPlayer;
          }

          _target.get(0).api_step_back();
        }
        if ($t.hasClass('zoomsounds-btn-step-forward')) {

          var _target = cthis;
          if (selfClass._actualPlayer) {
            _target = selfClass._actualPlayer;
          }

          _target.get(0).api_step_forward();
        }
        if ($t.hasClass('zoomsounds-btn-slow-playback')) {
          var _target = cthis;
          if (selfClass._actualPlayer) {
            _target = selfClass._actualPlayer;
          }

          _target.get(0).api_playback_slow();
        }
        if ($t.hasClass('zoomsounds-btn-reset')) {
          var _target = cthis;
          if (selfClass._actualPlayer) {
            _target = selfClass._actualPlayer;
          }

          _target.get(0).api_playback_reset();
        }
        if ($t.hasClass('btn-zoomsounds-download')) {
          (ajax_submit_download.bind(selfClass))();
        }
        if ($t.hasClass('dzsap-repeat-button')) {

          if (selfClass.player_playing) {
          }
          seek_to(0, {
            call_from: "repeat"
          });
        }
        if ($t.hasClass('dzsap-loop-button')) {
          if ($t.hasClass('active')) {
            $t.removeClass('active');
            media_isLoopActive = false;
          } else {

            $t.addClass('active');
            media_isLoopActive = true;

          }


        }
      }
      if (e.type === 'mouseover') {
      }
      if (e.type === 'mouseenter') {

        if (o.preview_on_hover === 'on') {
          seek_to_perc(0);

          play_media({
            'called_from': 'preview_on_hover'
          });
        }

        window.dzsap_mouseover = true;
      }
      if (e.type === 'mouseleave') {

        if (o.preview_on_hover === 'on') {
          seek_to_perc(0);
          pause_media();
        }
        window.dzsap_mouseover = false;
      }
    }


    function view_drawCurrentTime() {

      // -- draw current time -- called onEnterFrame when playing

      let currentTime = selfClass.timeModel.getVisualCurrentTime();
      let totalTime = selfClass.timeModel.getVisualTotalTime();

      if (selfClass.initOptions.scrubbar_type === 'wave') {
        if (selfClass.initOptions.skinwave_enableSpectrum === 'on') {
          // -- spectrum ON
          // -- easing
          if (selfClass.player_playing) {

          } else {
            return false;
          }
          if ($scrubBgCanvas) {

            canvasWidth = $scrubBgCanvas.width();
            canh = $scrubBgCanvas.height();

            $scrubBgCanvas.get(0).width = canvasWidth;
            $scrubBgCanvas.get(0).height = canh;
          }


          var drawMeter = function () {
            if (selfClass.initOptions.type === 'soundcloud' || sw_spectrum_fakeit === 'on') {

              selfClass.lastArray = generateFakeArrayForPcm();

            } else {

              if (selfClass.spectrum_analyser) {
                selfClass.lastArray = new Uint8Array(selfClass.spectrum_analyser.frequencyBinCount);
                selfClass.spectrum_analyser.getByteFrequencyData(selfClass.lastArray);
              }
            }


            if (selfClass.lastArray && selfClass.lastArray.length) {


              //fix when some sounds end the value still not back to zero
              var len = selfClass.lastArray.length;
              for (var i = len - 1; i >= 0; i--) {

                if (i < len / 2) {

                  selfClass.lastArray[i] = selfClass.lastArray[i] / 255 * canh;
                } else {

                  selfClass.lastArray[i] = selfClass.lastArray[len - i] / 255 * canh;
                }
              }
              ;


              if (selfClass.last_lastarray) {
                for (var i3 = 0; i3 < selfClass.last_lastarray.length; i3++) {
                  begin_viy = selfClass.last_lastarray[i3]; // -- last value
                  change_viy = selfClass.lastArray[i3] - begin_viy; // -- target value - last value
                  duration_viy = 3;
                  selfClass.lastArray[i3] = Math.easeIn(1, begin_viy, change_viy, duration_viy);
                }
              }
              // -- easing END

              draw_canvas($scrubBgCanvas.get(0), selfClass.lastArray, '' + selfClass.initOptions.design_wave_color_bg, {
                'call_from': 'spectrum',
                selfClass,
                'skinwave_wave_mode_canvas_waves_number': parseInt(selfClass.initOptions.skinwave_wave_mode_canvas_waves_number),
                'skinwave_wave_mode_canvas_waves_padding': parseInt(selfClass.initOptions.skinwave_wave_mode_canvas_waves_padding)
              })


              if (selfClass.lastArray) {
                selfClass.last_lastarray = selfClass.lastArray.slice();
              }


            }

          }

          drawMeter();


          // -- end spectrum
        }

        if (selfClass.$currTime && selfClass.$currTime.length) {

          if (selfClass.initOptions.skinwave_timer_static !== 'on') {

            if (scrubbarProgX < 0) {
              scrubbarProgX = 0;
            }
            scrubbarProgX = parseInt(scrubbarProgX, 10);


            if (scrubbarProgX < 2 && cthis.data('promise-to-play-footer-player-from')) {
              return false;
            }

            // -- move currTime
            selfClass.$currTime.css({
              'left': scrubbarProgX
            });

            if (scrubbarProgX > scrubbarWidth - currTime_outerWidth) {
              selfClass.$currTime.css({
                'left': scrubbarWidth - currTime_outerWidth
              })
            }
            if (scrubbarProgX > scrubbarWidth - 30 && scrubbarWidth) {
              selfClass.$totalTime.css({
                'opacity': 1 - (((scrubbarProgX - (scrubbarWidth - 30)) / 30))
              });
            } else {
              if (selfClass.$totalTime.css('opacity') !== '1') {
                selfClass.$totalTime.css({
                  'opacity': ''
                });
              }
            }
            ;
          }
          ;
        }
      }

      if (totalTime !== last_time_total) {
        view_updateTotalTime(totalTime)
      }

      if (selfClass.$currTime) {


        if (isScrubShowingCurrentTime === false) {
          selfClass.$currTime.html(formatTime(currentTime));
        }

        if (selfClass.timeModel.getVisualTotalTime() && selfClass.timeModel.getVisualTotalTime() > -1) {
          selfClass.cthis.addClass('time-total-visible');


        }
      }


      if (selfClass.spectrum_audioContext) {
        if (selfClass.$totalTime) {
          selfClass.$totalTime.html(formatTime(totalTime));
        }
      }

    }


    function view_updateTotalTime(totalTime) {

      if (selfClass.$totalTime) {
        selfClass.$totalTime.html(formatTime(totalTime));
        selfClass.$totalTime.fadeIn('fast');
      }
    }

    /**
     * draw the scrub width
     * @returns {string|boolean}
     */
    function view_drawScrubProgress() {
      let currentTime = selfClass.timeModel.getVisualCurrentTime();
      let totalTime = selfClass.timeModel.getVisualTotalTime();

      scrubbarProgX = (currentTime / totalTime) * scrubbarWidth;

      if (isNaN(scrubbarProgX)) {
        scrubbarProgX = 0;
      }
      if (scrubbarProgX > scrubbarWidth) {
        scrubbarProgX = scrubbarWidth;
      }

      if (currentTime < 0) {
        scrubbarProgX = 0;
      }

      if (totalTime === 0 || isNaN(totalTime)) {
        scrubbarProgX = 0;
      }

      if (scrubbarProgX < 2 && cthis.data('promise-to-play-footer-player-from')) {
        return false;
      }

      if (selfClass.spectrum_audioContext_buffer === null) {
        if (selfClass.$$scrubbProg) {
          selfClass.$$scrubbProg.style.width = parseInt(scrubbarProgX, 10) + 'px';
        }
      }

    }

    function handleClick_prevBtn() {
      if (o.parentgallery && (o.parentgallery.get(0))) {
        o.parentgallery.get(0).api_goto_prev();
      } else {

        syncPlayers_gotoPrev();
      }
    }

    function handleClick_nextBtn() {
      if (o.parentgallery && (o.parentgallery.get(0))) {
        o.parentgallery.get(0).api_goto_next();
      } else {

        syncPlayers_gotoNext();
      }
    }


    /**
     * fired on requestAnimationFrame
     * @param pargs
     * @returns {boolean}
     */
    function handleEnterFrame(pargs) {


      // -- enter frame
      var margs = {
        'fire_only_once': false
      }

      if (pargs) {
        margs = $.extend(margs, pargs);
      }

      if (isDestroyed) {
        return false;
      }

      if (margs.fire_only_once === false && isNotRenderingEnterFrame) {
        requestAnimationFrame(handleEnterFrame);
        return false;
      }

      selfClass.timeModel.refreshTimes({
        'call_from': 'checK_time'
      });

      if (selfClass.audioType === 'selfHosted') {

      }


      view_drawScrubProgress();


      selfClass.timeModel.processCurrentFrame();

      // -- skin minimal
      if (o.design_skin === 'skin-minimal') {


        if (selfClass.player_playing || selfClass.isCanvasFirstDrawn === false) {


          var ctx_minimal = selfClass.skin_minimal_canvasplay.getContext('2d');

          var ctx_w = selfClass.skin_minimal_canvasplay.width;
          var ctx_h = selfClass.skin_minimal_canvasplay.height;

          var pw = ctx_w / 100;
          var ph = ctx_h / 100;

          if (selfClass._actualPlayer) {

          }
          scrubbarProgX = Math.PI * 2 * (selfClass.timeModel.getVisualCurrentTime() / selfClass.timeModel.getVisualTotalTime());

          if (isNaN(scrubbarProgX)) {
            scrubbarProgX = 0;
          }
          if (scrubbarProgX > Math.PI * 2) {
            scrubbarProgX = Math.PI * 2;
          }

          ctx_minimal.clearRect(0, 0, ctx_w, ctx_h);


          // -- use design_wave_color_progress for drawing skin-minimal circle


          ctx_minimal.beginPath();
          ctx_minimal.arc((50 * pw), (50 * ph), (40 * pw), 0, Math.PI * 2, false);
          ctx_minimal.fillStyle = "rgba(0,0,0,0.1)";
          ctx_minimal.fill();


          ctx_minimal.beginPath();
          ctx_minimal.arc((50 * pw), (50 * ph), (34 * pw), 0, scrubbarProgX, false);
          ctx_minimal.lineWidth = (10 * pw);
          ctx_minimal.strokeStyle = 'rgba(0,0,0,0.3)';
          ctx_minimal.stroke();


          selfClass.isCanvasFirstDrawn = true;


        }
      }


      // -- enter_frame
      view_drawCurrentTime();


      if (margs.fire_only_once !== true) {
        requestAnimationFrame(handleEnterFrame);
      }
    }

    function handleClick_playPause(e) {

      if (cthis.hasClass('prevent-bubble')) {

        if (e && e.stopPropagation) {
          e.preventDefault();
          e.stopPropagation();
          ;
        }

      }

      var _t = $(this);

      var isToggleCancelled = false;

      if (!cthis.hasClass('listeners-setuped')) {


        $(selfClass.$mediaNode_).attr('preload', 'auto');

        setup_listeners();
        init_loaded();


        var inter_checkTotalTime = setInterval(function () {

          if (selfClass.$mediaNode_ && selfClass.$mediaNode_.duration && isNaN(selfClass.$mediaNode_.duration) === false) {


            clearInterval(inter_checkTotalTime);
          }
        }, 1000);
      }


      if (o.design_skin === 'skin-minimal') {

        var center_x = _t.offset().left + skin_minimal_button_size / 2;
        var center_y = _t.offset().top + skin_minimal_button_size / 2;
        var mouse_x = e.pageX;
        var mouse_y = e.pageY;

        var perc = -(mouse_x - center_x - (skin_minimal_button_size / 2)) * 0.005;
        if (mouse_y < center_y) {
          perc = 0.5 + (0.5 - perc)
        }

        if (Math.abs(mouse_y - center_y) > 20 || Math.abs(mouse_x - center_x) > 20) {

          seek_to_perc(perc, {
            call_from: "skin_minimal_scrub"
          })
          isToggleCancelled = true;

          handleEnterFrame({
            'fire_only_once': true
          });
        }
      }

      if (!isToggleCancelled) {
        if (!selfClass.player_playing) {
          play_media({
            'called_from': 'click_playpause'
          });
        } else {
          pause_media();
        }
      }


      return false;
    }


    /**
     *
     * @param targetIncrement
     */
    function syncPlayers_gotoItem(targetIncrement = 0) {


      var targetIndex = 0;
      if (selfClass.classFunctionalityInnerPlaylist) {
        // -- playlist Inner

        targetIndex = selfClass.playlist_inner_currNr + targetIncrement;
        if (targetIndex >= 0) {
          selfClass.classFunctionalityInnerPlaylist.playlistInner_gotoItem(targetIndex, {
            'call_from': 'api_sync_players_prev'
          });
        }
      } else {
        if (window.dzsap_syncList_players && window.dzsap_syncList_players.length > 0) {
          player_syncPlayers_gotoItem(selfClass, targetIncrement);
        } else {
          console.log('[dzsap] [syncPlayers] no players found')
        }
      }

      if (window.dzsap_syncList_players && window.dzsap_syncList_index >= window.dzsap_syncList_players.length) {
        window.dzsap_syncList_index = 0;
      }
    }

    function syncPlayers_gotoPrev() {


      if (selfClass._actualPlayer) {
        selfClass._actualPlayer.get(0).api_sync_players_goto_prev();

        return false;
      }


      syncPlayers_gotoItem(-1);

    }


    /**
     * go to next inner playlistItem for single player
     * @returns {boolean}
     */
    function syncPlayers_gotoNext() {

      if (selfClass._actualPlayer) {
        selfClass._actualPlayer.get(0).api_sync_players_goto_next();

        return false;
      }
      syncPlayers_gotoItem(1);
    }

    /**
     *
     * @param pargs
     * @returns {boolean|void}
     */
    function media_handleEnd(pargs) {


      var margs = {
        'called_from': 'default'
      }


      if (pargs) {
        margs = $.extend(margs, pargs);
      }
      if (selfClass.isMediaEnded) {
        return false;
      }

      selfClass.isMediaEnded = true;

      selfClass.inter_isEnded = setTimeout(function () {
        selfClass.isMediaEnded = false;
      }, 1000);


      if (selfClass._sourcePlayer) {

        media_isLoopActive = selfClass._sourcePlayer.get(0).api_get_media_isLoopActive();
      }
      if (selfClass._actualPlayer && margs.call_from !== 'fake_player') {
        // -- lets leave fake player handle handle_end
        return false;
      }


      seek_to(0, {
        'call_from': 'handle_end'
      });

      if (media_isLoopActive) {
        play_media({
          'called_from': 'track_end'
        });
        return false;
      } else {
        pause_media();
      }

      if (o.parentgallery) {
        o.parentgallery.get(0).api_gallery_handle_end();
      }


      setTimeout(function () {
        if (selfClass.cthis.hasClass('is-single-player') || (selfClass._sourcePlayer && selfClass._sourcePlayer.hasClass('is-single-player'))) {
          // -- called on handle end
          syncPlayers_gotoNext();
        }
      }, 100);

      setTimeout(function () {

        if (selfClass._sourcePlayer && (selfClass._sourcePlayer.hasClass('is-single-player') || selfClass._sourcePlayer.hasClass('feeded-whole-playlist'))) {
          selfClass._sourcePlayer.get(0).api_handle_end({
            'call_from': 'handle_end() fake_player'
          });
          return false;
        }

        if (action_audio_end) {
          let args = {};
          action_audio_end(cthis, args);
        }
      }, 200);

    }


    function view_handleResize(e, pargs) {


      if (cthis) {

      }

      ww = $(window).width();
      cthisWidth = cthis.width();
      th = cthis.height();


      if ($scrubBgCanvas && typeof ($scrubBgCanvas.width) === 'function') {
        canvasWidth = $scrubBgCanvas.width();
        canh = $scrubBgCanvas.height();

      }


      if (cthisWidth <= 720) {
        cthis.addClass('under-720');
      } else {

        cthis.removeClass('under-720');
      }
      if (cthisWidth <= 500) {
        // -- width under 500


        // -- move
        if (cthis.hasClass('under-500') === false) {
          if (o.design_skin === 'skin-wave' && selfClass.skinwave_mode === 'normal') {
            selfClass._apControls.append(selfClass._metaArtistCon);
          }
        }

        cthis.addClass('under-500');


      } else {
        // -- width under 500


        if (cthis.hasClass('under-500') === false) {
          if (o.design_skin === 'skin-wave' && selfClass.skinwave_mode === 'normal') {
            selfClass._conPlayPauseCon.after(selfClass._metaArtistCon);
          }
        }

        cthis.removeClass('under-500');
      }


      scrubbarWidth = cthisWidth;
      if (o.design_skin === 'skin-default') {
        scrubbarWidth = cthisWidth;
      }
      if (o.design_skin === 'skin-pro') {
        scrubbarWidth = cthisWidth;
      }
      if (o.design_skin === 'skin-silver' || o.design_skin === 'skin-aria') {
        scrubbarWidth = cthisWidth;

        scrubbarWidth = selfClass._scrubbar.width();
      }


      if (o.design_skin === 'skin-justthumbandbutton') {
        cthisWidth = cthis.children('.audioplayer-inner').outerWidth();
        scrubbarWidth = cthisWidth;
      }
      if (o.design_skin === 'skin-redlights' || o.design_skin === 'skin-steel') {
        scrubbarWidth = selfClass._scrubbar.width();
      }

      if (o.design_skin === 'skin-wave') {
        scrubbarWidth = selfClass._scrubbar.outerWidth(false);
        scrubbar_h = selfClass._scrubbar.outerHeight(false);

        if (selfClass._commentsHolder) {

          selfClass._commentsHolder.css({
            'width': scrubbarWidth
          })

          selfClass._commentsHolder.addClass('active');


        }

      }

      // -- *deprecated todo: remove
      if (isResolveThumbHeight === true) {

        if (o.design_skin === 'skin-default') {

          if (cthis.get(0)) {
            // if the height is auto then
            if (cthis.get(0).style.height === 'auto') {
              cthis.height(200);
            }
          }

          var cthis_height = selfClass._audioplayerInner.height();
          if (cthis.attr('data-initheight')) {
            cthis.attr('data-initheight', selfClass._audioplayerInner.height());
          } else {
            cthis_height = Number(cthis.attr('data-initheight'));
          }

          if (o.design_thumbh === 'default') {

            design_thumbh = cthis_height - 44;
          }

        }

        selfClass._audioplayerInner.find('.the-thumb').eq(0).css({})
      }


      //===display none + overflow hidden hack does not work .. yeah

      if (cthis.css('display') !== 'none') {
        selfClass._scrubbar.find('.scrub-bg-img').eq(0).css({
          'width': selfClass._scrubbar.children('.scrub-bg').width()
        });
        selfClass._scrubbar.find('.scrub-prog-img').eq(0).css({
          'width': selfClass._scrubbar.children('.scrub-bg').width()
        });
      }

      cthis.removeClass('under-240 under-400');
      if (cthisWidth <= 240) {
        cthis.addClass('under-240');
      }
      if (cthisWidth <= 500) {
        cthis.addClass('under-400');

        if (is_under_400 === false) {
          is_under_400 = true;
        }
        if (selfClass.$controlsVolume) {
        }

      } else {


        if (is_under_400 === true) {
          is_under_400 = false;
        }
      }


      var aux2 = 50;

      // -- skin-wave
      if (o.design_skin === 'skin-wave') {


        var sh = selfClass._scrubbar.eq(0).height();


        if (selfClass.skinwave_mode === 'small') {
          sh = 5;


        }


        // ---------- calculate dims small
        if (selfClass.skinwave_mode === 'small') {
          scrubbarWidth = selfClass._scrubbar.width();
        }


        if (o.skinwave_wave_mode === 'canvas') {
          if (cthis.attr('data-pcm')) {
            if (selfClass._scrubbarbg_canvas.width() === 100) {
              selfClass._scrubbarbg_canvas.width(selfClass._scrubbar.width());
            }
            if (selfClass.data_source !== 'fake') {
              // -- if inter definied then clear timeout and call
              if (draw_canvas_inter) {
                clearTimeout(draw_canvas_inter);
                draw_canvas_inter = setTimeout(draw_canvas_inter_func, 300);
              } else {
                draw_canvas_inter_func();
                draw_canvas_inter = 1;
              }
            }
          }
        }
      }


      if (o.design_skin === 'skin-minimal') {

        skin_minimal_button_size = selfClass._apControls.width();
        if (selfClass.skin_minimal_canvasplay) {
          selfClass.skin_minimal_canvasplay.style.width = skin_minimal_button_size;
          selfClass.skin_minimal_canvasplay.width = skin_minimal_button_size;
          selfClass.skin_minimal_canvasplay.style.height = skin_minimal_button_size;
          selfClass.skin_minimal_canvasplay.height = skin_minimal_button_size;

          $(selfClass.skin_minimal_canvasplay).css({
            'width': skin_minimal_button_size
            , 'height': skin_minimal_button_size
          });
        }


      }


      if (o.design_skin === 'skin-default') {
        if (selfClass.$currTime) {
          var _metaArtistCon_l = parseInt(selfClass._metaArtistCon.css('left'), 10);
          var _metaArtistCon_w = selfClass._metaArtistCon.outerWidth();

          if (selfClass._metaArtistCon.css('display') === 'none') {
            selfClass._metaArtistCon_w = 0;
          }
          if (isNaN(selfClass._metaArtistCon_l)) {
            selfClass._metaArtistCon_l = 20;
          }
        }

      }

      if (o.design_skin === 'skin-minion') {
        aux2 = parseInt(selfClass.$conControls.find('.con-playpause').eq(0).offset().left, 10) - parseInt(selfClass.$conControls.eq(0).offset().left, 10) - 18;
        selfClass.$conControls.find('.prev-btn').eq(0).css({
          'top': 0,
          'left': aux2
        })
        aux2 += 36;
        selfClass.$conControls.find('.next-btn').eq(0).css({
          'top': 0,
          'left': aux2
        })
      }


      if (o.embedded === 'on') {
        if (window.frameElement) {

          let args = {
            height: cthis.outerHeight()
          };


          if (o.embedded_iframe_id) {

            args.embedded_iframe_id = o.embedded_iframe_id;
          }


          var message = {
            name: "resizeIframe",
            params: args
          };
          window.parent.postMessage(message, '*');
        }

      }


      view_drawScrubProgress();

      if (o.settings_trigger_resize > 0) {

        if (o.parentgallery && $(o.parentgallery).get(0) !== undefined && $(o.parentgallery).get(0).api_handleResize !== undefined) {
          $(o.parentgallery).get(0).api_handleResize();
        }
      }

    }


    function draw_canvas_inter_func() {
      view_drawCanvases(selfClass, cthis.attr('data-pcm'), 'canvas_normal_pcm');

      draw_canvas_inter = 0;
    }

    function volume_handleMouse(e) {
      var _t = $(this);
      /**
       * from 0 to 1
       * @type number
       */
      var mouseXRelativeToVolume = null;

      if (selfClass.$controlsVolume.find('.volume_static').length) {

        mouseXRelativeToVolume = Number((e.pageX - (selfClass.$controlsVolume.find('.volume_static').eq(0).offset().left)) / (selfClass.$controlsVolume.find('.volume_static').eq(0).width()));
      }

      if (!mouseXRelativeToVolume) {
        return false;
      }
      if (e.type === 'mousemove') {
        if (volume_dragging) {

          if (_t.parent().hasClass('volume-holder') || _t.hasClass('volume-holder')) {
          }

          if (o.design_skin === 'skin-redlights') {
            mouseXRelativeToVolume *= 10;
            mouseXRelativeToVolume = Math.round(mouseXRelativeToVolume);
            mouseXRelativeToVolume /= 10;
          }


          volume_setVolume(mouseXRelativeToVolume, {
            call_from: "set_by_mousemove"
          });
          isMuted = false;
        }

      }
      if (e.type === 'mouseleave') {

      }
      if (e.type === 'click') {

        if (_t.parent().hasClass('volume-holder')) {


          mouseXRelativeToVolume = 1 - ((e.pageY - (selfClass.$controlsVolume.find('.volume_static').eq(0).offset().top)) / (selfClass.$controlsVolume.find('.volume_static').eq(0).height()));

        }
        if (_t.hasClass('volume-holder')) {
          mouseXRelativeToVolume = 1 - ((e.pageY - (selfClass.$controlsVolume.find('.volume_static').eq(0).offset().top)) / (selfClass.$controlsVolume.find('.volume_static').eq(0).height()));


        }

        volume_setVolume(mouseXRelativeToVolume, {
          call_from: "set_by_mouseclick"
        });
        isMuted = false;
      }

      if (e.type === 'mousedown') {

        volume_dragging = true;
        cthis.addClass('volume-dragging');


        if (_t.parent().hasClass('volume-holder')) {


          mouseXRelativeToVolume = 1 - ((e.pageY - (selfClass.$controlsVolume.find('.volume_static').eq(0).offset().top)) / (selfClass.$controlsVolume.find('.volume_static').eq(0).height()));

        }

        volume_setVolume(mouseXRelativeToVolume, {
          call_from: "set_by_mousedown"
        });
        isMuted = false;
      }
      if (e.type === 'mouseup') {

        volume_dragging = false;
        cthis.removeClass('volume-dragging');

      }

    }

    function handleMouseOnScrubbar(e) {
      var mousex = e.pageX;


      if ($(e.target).hasClass('sample-block-start') || $(e.target).hasClass('sample-block-end')) {
        return false;
      }

      if (e.type === 'mousemove') {
        selfClass._scrubbar.children('.scrubBox-hover').css({
          'left': (mousex - selfClass._scrubbar.offset().left)
        });


        if (o.scrub_show_scrub_time === 'on') {


          if (selfClass.timeModel.getVisualTotalTime()) {
            var aux = (mousex - selfClass._scrubbar.offset().left) / selfClass._scrubbar.outerWidth() * selfClass.timeModel.getVisualTotalTime();


            if (selfClass.$currTime) {
              selfClass.$currTime.html(formatTime(aux));
              selfClass.$currTime.addClass('scrub-time');

            }

            isScrubShowingCurrentTime = true;
          }
        }

      }
      if (e.type === 'mouseleave') {

        isScrubShowingCurrentTime = false;

        if (selfClass.$currTime) {
          selfClass.$currTime.removeClass('scrub-time');

        }

        view_drawCurrentTime();

      }
      if (e.type === 'click') {


        if (cthis.hasClass('prevent-bubble')) {

          if (e && e.stopPropagation) {
            e.preventDefault();
            e.stopPropagation();
          }
        }


        if (scrubbarWidth === 0) {

          scrubbarWidth = selfClass._scrubbar.width();
        }
        if (scrubbarWidth === 0) {
          scrubbarWidth = 300;
        }
        var targetPositionOnScrub = ((e.pageX - (selfClass._scrubbar.offset().left)) / (scrubbarWidth) * selfClass.timeModel.getVisualTotalTime());


        if (selfClass.sample_time_start > 0) {
          targetPositionOnScrub -= selfClass.sample_time_start;
        }

        if (selfClass._actualPlayer) {
          setTimeout(function () {
            if (selfClass._actualPlayer.get(0) && selfClass._actualPlayer.get(0).api_pause_media) {

              selfClass._actualPlayer.get(0).api_seek_to_perc(targetPositionOnScrub / selfClass.timeModel.getVisualTotalTime(), {
                'call_from': 'from_feeder_to_feed'
              });
            }
          }, 50);
        }


        seek_to(targetPositionOnScrub, {
          'call_from': 'handleMouseOnScrubbar'
        });

        if (o.autoplay_on_scrub_click === 'on') {

          if (selfClass.player_playing === false) {
            play_media({
              'called_from': 'handleMouseOnScrubbar'
            });
          }
        }

        if (cthis.hasClass('from-wc_loop')) {
          return false;
        }
      }

    }

    function seek_to_perc(argperc, pargs) {
      seek_to((argperc * selfClass.timeModel.getVisualTotalTime()), pargs);
    }

    /**
     * seek to seconds
     * @param targetTimeMediaScrub - number of settings
     * @param pargs -- optiona arguments
     * @returns {boolean}
     */
    function seek_to(targetTimeMediaScrub, pargs) {
      //arg = nr seconds

      var margs = {
        'call_from': 'default'
        , 'deeplinking': 'off' // -- default or "auto" or "user action"
        , 'call_from_type': 'default' // -- default or "auto" or "user action"
      };

      if (pargs) {
        margs = $.extend(margs, pargs);
      }

      if (margs.call_from === 'from_feeder_to_feed') {

      }

      if (margs.deeplinking === 'on') {
        var newlink = add_query_arg(window.location.href, 'audio_time', targetTimeMediaScrub);


        var stateObj = {foo: "bar"};
        history.pushState(stateObj, null, newlink);
      }

      targetTimeMediaScrub = sanitizeToIntFromPointTime(targetTimeMediaScrub);

      targetTimeMediaScrub = selfClass.timeModel.getActualTargetTime(targetTimeMediaScrub);

      if (selfClass._actualPlayer) {
        let args = {
          type: selfClass.actualDataTypeOfMedia,
          fakeplayer_is_feeder: 'on'
        }
        if (selfClass._actualPlayer.length && selfClass._actualPlayer.data('feeding-from') !== cthis.get(0)) {
          // -- the actualPlayer is not rendering this feed player
          if (margs.call_from !== 'handle_end' && margs.call_from !== 'from_playfrom' && margs.call_from !== 'last_pos' && margs.call_from !== 'playlist_seek_from_0') {
            // -- if it is not user action, ( handle_end or anything else )
            args.called_from = 'seek_to from player source->' + (cthis.attr('data-source')) + ' < -  ' + 'old call from - ' + margs.call_from;
            if (selfClass._actualPlayer.get(0).api_change_media) {
              selfClass._actualPlayer.get(0).api_change_media(cthis, args);
            } else {
              console.log('not inited ? ', selfClass._actualPlayer);
            }
          } else {
            // -- NORMAL call

            cthis.data('promise-to-play-footer-player-from', targetTimeMediaScrub);
          }
        }


        setTimeout(function () {

          if (selfClass._actualPlayer.get(0) && selfClass._actualPlayer.get(0).api_pause_media) {
            if (margs.call_from !== 'from_playfrom' && margs.call_from !== 'last_pos') {
              selfClass._actualPlayer.get(0).api_seek_to(targetTimeMediaScrub, {
                'call_from': 'from_feeder_to_feed'
              });
            }
          }
        }, 50);

        return false;
      }


      if (selfClass.audioType === 'youtube') {
        try {
          selfClass.$mediaNode_.seekTo(targetTimeMediaScrub);
        } catch (err) {

        }
      }

      handleEnterFrame({
        'fire_only_once': true
      })
      setTimeout(function () {
        handleEnterFrame({
          'fire_only_once': true
        })
      }, 20);


      if (selfClass.audioType === 'selfHosted') {
        if (0) {

          selfClass.lastTimeInSeconds = targetTimeMediaScrub;

          pause_media({
            'audioapi_setlasttime': false
          });
          play_media({
            'called_from': 'audio_buffer ( seek_to() )'
          });
        } else {

          if (selfClass.$mediaNode_ && typeof (selfClass.$mediaNode_.currentTime) !== 'undefined') {

            try {
              selfClass.$mediaNode_.currentTime = targetTimeMediaScrub;
            } catch (e) {


            }

          }

          return false;

        }

      }


    }

    /**
     * seek to ( only visual )
     * @param argperc
     */
    function seek_to_visual(argperc) {


      curr_time_first_set = true;


      handleEnterFrame({
        'fire_only_once': true
      })
      setTimeout(function () {
        handleEnterFrame({
          'fire_only_once': true
        })
      }, 20);
    }

    /**
     * playback speed
     * @param {float} arg 0 - 10
     */
    function set_playback_speed(arg) {

      if (selfClass.audioType === 'youtube') {
        selfClass.$mediaNode_.setPlaybackRate(arg);
      }
      if (selfClass.audioType === 'selfHosted') {
        selfClass.$mediaNode_.playbackRate = arg;

      }

    }

    /**
     * outputs a volume from 0 to 1
     * @param {number} arg 0 <-> 1
     * @param pargs
     * @returns {boolean}
     */
    function volume_setVolume(arg, pargs) {

      var margs = {

        'call_from': 'default'
      };

      if (pargs) {
        margs = $.extend(margs, pargs);
      }

      if (arg > 1) {
        arg = 1;
      }
      if (arg < 0) {
        arg = 0;
      }


      if (margs.call_from === 'from_fake_player_feeder_from_init_loaded') {
        // -- lets prevent call from the init_loaded set_volume if the volume has been changed
        if (selfClass._sourcePlayer) {
          if (o.default_volume !== 'default') {
            volume_set_by_user = true;
          }
          if (volume_set_by_user) {
            return false;
          } else {
            volume_set_by_user = true;
          }
        }
      }

      if (margs.call_from === 'set_by_mouseclick' || margs.call_from === 'set_by_mousemove') {
        volume_set_by_user = true;
      }

      if (selfClass.audioType === 'youtube') {
        if (selfClass.$mediaNode_ && selfClass.$mediaNode_.setVolume) {
          selfClass.$mediaNode_.setVolume(arg * 100);
        }
      }
      if (selfClass.audioType === 'selfHosted') {
        if (selfClass.$mediaNode_) {

          selfClass.$mediaNode_.volume = arg;
        } else {
          if (selfClass.$mediaNode_) {
            $(selfClass.$mediaNode_).attr('preload', 'metadata');
          }
        }
      }


      volume_setOnlyVisual(arg, margs);

      if (selfClass._sourcePlayer) {
        margs.call_from = ('from_fake_player')
        if (selfClass._sourcePlayer.get(0) && selfClass._sourcePlayer.get(0).api_visual_set_volume(arg, margs)) {
          selfClass._sourcePlayer.get(0).api_visual_set_volume(arg, margs);
        }
      }

      if (selfClass._actualPlayer) {
        if (margs.call_from !== ('from_fake_player')) {
          if (margs.call_from === 'from_init_loaded') {

            margs.call_from = ('from_fake_player_feeder_from_init_loaded')
          } else {

            margs.call_from = ('from_fake_player_feeder')
          }
          if (selfClass._actualPlayer && selfClass._actualPlayer.get(0) && selfClass._actualPlayer.get(0).api_set_volume) {
            selfClass._actualPlayer.get(0).api_set_volume(arg, margs);
          }
        }
      }

    }


    function volume_setOnlyVisual(arg, margs) {

      if (selfClass.$controlsVolume.hasClass('controls-volume-vertical')) {
        selfClass.$controlsVolume.find('.volume_active').eq(0).css({
          'height': (selfClass.$controlsVolume.find('.volume_static').eq(0).height() * arg)
        });
      } else {

        if (selfClass.initOptions.design_skin === 'skin-redlights') {

          selfClass.$controlsVolume.find('.volume_active').eq(0).css({
            'clip-path': 'inset(0% ' + (Math.abs(1 - arg) * 100) + '% 0% 0%)'
          });
        } else {

          selfClass.$controlsVolume.find('.volume_active').eq(0).css({
            'width': (selfClass.$controlsVolume.find('.volume_static').eq(0).width() * arg)
          });
        }
      }


      if (o.design_skin === 'skin-wave' && o.skinwave_dynamicwaves === 'on') {
        selfClass._scrubbar.find('.scrub-bg-img').eq(0).css({
          'transform': 'scaleY(' + arg + ')'
        })
        selfClass._scrubbar.find('.scrub-prog-img').eq(0).css({
          'transform': 'scaleY(' + arg + ')'
        })

      }


      if (localStorage !== null && selfClass.the_player_id) {

        localStorage.setItem('dzsap_last_volume_' + selfClass.the_player_id, arg);

      }

      volume_lastVolume = arg;
    }


    function volume_handleClickMuteIcon(e) {

      if (isMuted === false) {
        last_vol_before_mute = volume_lastVolume;
        volume_setVolume(0, {
          call_from: "from_mute"
        });
        isMuted = true;
      } else {
        volume_setVolume(last_vol_before_mute, {
          call_from: "from_unmute"
        });
        isMuted = false;
      }
    }

    function pause_media_visual(pargs) {


      var margs = {
        'call_from': 'default'
      };


      if (pargs) {
        margs = $.extend(margs, pargs);
      }

      selfClass.$conPlayPause.removeClass('playing');
      cthis.removeClass('is-playing');
      selfClass.player_playing = false;


      if (cthis.parent().hasClass('zoomsounds-wrapper-bg-center')) {
        cthis.parent().removeClass('is-playing');
      }
      if (selfClass.$reflectionVisualObject) {
        selfClass.$reflectionVisualObject.removeClass('is-playing');
      }

      if (o.parentgallery) {
        o.parentgallery.removeClass('player-is-playing');
      }


      isNotRenderingEnterFrame = true;


      if (action_audio_pause) {
        action_audio_pause(cthis);
      }
    }

    function pause_media(pargs) {

      var margs = {
        'audioapi_setlasttime': true,
        'donot_change_media': false,
        'call_actual_player': true,
      };

      if (isDestroyed) {
        return false;
      }

      if (pargs) {
        margs = $.extend(margs, pargs);
      }


      pause_media_visual({
        'call_from': 'pause_media'
      });


      if (margs.call_actual_player && margs.donot_change_media !== true) {
        if (selfClass._actualPlayer !== null) {
          let args = {
            type: selfClass.actualDataTypeOfMedia,
            fakeplayer_is_feeder: 'on'
          }
          if (selfClass._actualPlayer && selfClass._actualPlayer.length && selfClass._actualPlayer.data('feeding-from') !== cthis.get(0)) {
            args.called_from = 'pause_media from player ' + cthis.attr('data-source');
            selfClass._actualPlayer.get(0).api_change_media(cthis, args);
          }
          setTimeout(function () {
            if (selfClass._actualPlayer.get(0) && selfClass._actualPlayer.get(0).api_pause_media) {
              selfClass._actualPlayer.get(0).api_pause_media();
            }
          }, 100);

          selfClass.player_playing = false;
          return;
        }
      }


      media_pause(selfClass, () => {
        if (selfClass._sourcePlayer) {
          if (selfClass._sourcePlayer.get(0) && selfClass._sourcePlayer.get(0).api_pause_media_visual) {
            selfClass._sourcePlayer.get(0).api_pause_media_visual({
              'call_from': 'pause_media in child player'
            });
          }
        }
      })


      selfClass.player_playing = false;


    }

    function play_media_visual(margs) {


      selfClass.player_playing = true;
      isNotRenderingEnterFrame = false;

      cthis.addClass('is-playing');
      cthis.addClass('first-played');

      if (selfClass.$reflectionVisualObject) {
        selfClass.$reflectionVisualObject.addClass('is-playing');
      }
      if (o.parentgallery) {
        o.parentgallery.addClass('player-is-playing');
      }

      if (selfClass.classFunctionalityInnerPlaylist) {
        selfClass.classFunctionalityInnerPlaylist.player_determineSyncPlayersIndex(selfClass, selfClass._sourcePlayer);
      }
      view_player_globalDetermineSyncPlayersIndex(selfClass);

      view_player_playMiscEffects(selfClass);


      if (selfClass.isStickyPlayer) {
        selfClass.$stickToBottomContainer.addClass('audioplayer-loaded');


        const  $dzsapStickToBottomPlaceholder = $('.dzsap-sticktobottom-placeholder');


        selfClass.$stickToBottomContainer.addClass('audioplayer-loaded')

        $dzsapStickToBottomPlaceholder.eq(0).addClass('active');
        $dzsapStickToBottomPlaceholder.css({
          height: (selfClass.$stickToBottomContainer.outerHeight())+'px'
        })
      }


      if (action_audio_play) {
        action_audio_play(cthis);
      }
      if (action_audio_play2) {
        action_audio_play2(cthis);
      }


    }

    function play_media(pargs) {

      var margs = {
        'api_report_play_media': true
        , 'called_from': 'default'
        , 'retry_call': 0
      }
      if (pargs) {
        margs = $.extend(margs, pargs)
      }

      if (!selfClass.isSetupedMedia) {
        setup_media({'called_from': margs.called_from + '[play_media .. not setuped]'});
      }

      if (margs.called_from === 'api_sync_players_prev') {

        player_index_in_gallery = cthis.parent().children('.audioplayer,.audioplayer-tobe').index(cthis);

        if (o.parentgallery && o.parentgallery.get(0) && o.parentgallery.get(0).api_goto_item) {
          o.parentgallery.get(0).api_goto_item(player_index_in_gallery);
        }
      }

      if ((is_ios() && selfClass.spectrum_audioContext_buffer === 'waiting') || selfClass.mediaProtectionStatus==='fetchingProtection') {
        setTimeout(function () {
          if(!pargs){
            pargs = {};
          }
          pargs.call_from_aux = 'waiting audioCtx_buffer or ios';
          play_media(pargs);
        }, 500);
        return false;
      }

      if (margs.called_from === 'click_playpause') {
        // -- lets setup the playlist
      }


      if (!cthis.hasClass('media-setuped') && selfClass._actualPlayer === null) {
        console.log('[dzsap][warning] media not setuped, there might be issues', cthis.attr('id'));
      }


      if (margs.called_from.indexOf('feed_to_feeder') > -1) {
        if (cthis.hasClass('dzsap-loaded') === false) {
          init_loaded();
          var delay = 300;
          if (is_android_good()) {
            delay = 0;
          }
          if (margs.call_from_aux !== 'with delay') {
            if (delay) {
              setTimeout(function () {
                margs.call_from_aux = 'with delay';
                play_media(margs);
              }, delay);
            } else {
              play_media(margs);
            }
            return false;
          }

        }
      }

      player_stopOtherPlayers(dzsap_list, selfClass);


      if (destroyed_for_rebuffer) {
        setup_media({
          'called_from': 'play_media() .. destroyed for rebuffer'
        });
        if (isInt(selfClass.playFrom)) {
          seek_to(selfClass.playFrom, {
            'call_from': 'destroyed_for_rebuffer_playfrom'
          });
        }
        destroyed_for_rebuffer = false;
      }

      if (o.google_analytics_send_play_event === 'on' && window._gaq && google_analytics_sent_play_event === false) {
        window._gaq.push(['_trackEvent', 'ZoomSounds Play', 'Play', 'zoomsounds play - ' + dataSrc]);
        google_analytics_sent_play_event = true;
      }

      if (!window.ga) {
        if (window.__gaTracker) {
          window.ga = window.__gaTracker;
        }
      }

      if (o.google_analytics_send_play_event === 'on' && window.ga && google_analytics_sent_play_event === false) {
        google_analytics_sent_play_event = true;
        window.ga('send', {
          hitType: 'event',
          eventCategory: 'zoomsounds play - ' + dataSrc,
          eventAction: 'play',
          eventLabel: 'zoomsounds play - ' + dataSrc
        });
      }

      // -- media functions

      if (selfClass._sourcePlayer) {

        if (selfClass._sourcePlayer.get(0) && selfClass._sourcePlayer.get(0).api_pause_media_visual) {
          selfClass._sourcePlayer.get(0).api_play_media_visual({
            'api_report_play_media': false
          });
        }

      }


      if (selfClass._actualPlayer) {
        // -- the actual player is the footer player

        let args = {
          type: selfClass.actualDataTypeOfMedia,
          fakeplayer_is_feeder: 'on',
          call_from: 'play_media_audioplayer'
        }

        try {
          if (margs.called_from === 'click_playpause') {
            // -- let us reset up the playlist


            if (o.parentgallery) {
              o.parentgallery.get(0).api_regenerate_sync_players_with_this_playlist();
              selfClass._actualPlayer.get(0).api_regenerate_playerlist_inner();
            }

          }

          if (selfClass._actualPlayer && selfClass._actualPlayer.length && selfClass._actualPlayer.data('feeding-from') !== cthis.get(0)) {

            args.called_from = 'play_media from player 22 ' + cthis.attr('data-source') + ' < -  ' + 'old call from - ' + margs.called_from;

            if (selfClass._actualPlayer.get(0).api_change_media) {
              selfClass._actualPlayer.get(0).api_change_media(cthis, args);
            }

            if (cthis.hasClass('first-played') === false) {
              if (cthis.data('promise-to-play-footer-player-from')) {
                seek_to(cthis.data('promise-to-play-footer-player-from'));
                setTimeout(function () {
                  cthis.data('promise-to-play-footer-player-from', '');
                }, 1000);
              }
            }

          }
          setTimeout(function () {
            if (selfClass._actualPlayer.get(0) && selfClass._actualPlayer.get(0).api_play_media) {
              selfClass._actualPlayer.get(0).api_play_media({
                'called_from': '[feed_to_feeder]'
              });
            }
          }, 100);


          if (selfClass.ajax_view_submitted === 'off') {
            (ajax_submit_views.bind(selfClass))();
          }
          return;


        } catch (err) {
          console.log('no fake player..', err);
        }
      }


      if (selfClass.audioType === 'youtube') {
        dzsap_youtube_playMedia(selfClass, margs, selfClass.youtube_currentId);
      }
      if (selfClass.audioType === 'selfHosted') {


      }


      if (selfClass.audioType === 'youtube') {
        play_media_visual(margs);
      }

      media_tryToPlay(selfClass, () => {

        play_media_visual(margs);


        if (o.scrubbar_type === 'wave' && o.skinwave_enableSpectrum === 'on') {
          player_initSpectrum(selfClass);
        }

        if (selfClass._sourcePlayer) {
          window.dzsap_currplayer_focused = selfClass._sourcePlayer.get(0);
          if (selfClass._sourcePlayer.get(0) && selfClass._sourcePlayer.get(0).api_pause_media_visual) {
            selfClass._sourcePlayer.get(0).api_try_to_submit_view();
          }
        } else {
          window.dzsap_currplayer_focused = cthis.get(0);
          service_submitView();
        }


        if (selfClass.keyboard_controls.play_trigger_step_back === 'on') {
          if (dzsap_currplayer_focused) {
            dzsap_currplayer_focused.api_step_back(selfClass.keyboard_controls.step_back_amount);
          }
        }
      }, (err) => {
        console.log('error autoplay playing -  ', err);
        setTimeout(() => {
          pause_media();
          console.log('trying to pause')
        }, 30);
      })


    }


    function service_submitView() {
      if (selfClass.ajax_view_submitted === 'auto') {
        selfClass.ajax_view_submitted = 'off';
      }
      if (selfClass.ajax_view_submitted === 'off') {

        (ajax_submit_views.bind(selfClass))();
      }
    }


  }
}


function register_dzsap_plugin() {
  if (!window.dzsap_settings) {
    window.dzsap_settings = {};
  }
  (function ($) {

    Math.easeIn = function (t, b, c, d) {

      return -c * (t /= d) * (t - 2) + b;

    };


    assignHelperFunctionsToJquery($);


    // -- define player here
    $.fn.audioplayer = function (argOptions) {
      var finalOptions = {};
      var defaultOptions = Object.assign({}, defaultPlayerOptions);
      finalOptions = convertPluginOptionsToFinalOptions(this, defaultOptions, argOptions);


      this.each(function () {
        return new DzsAudioPlayer(this, Object.assign({},finalOptions), $);
      })
    }


    // -- defined gallery here
    // --
    // AUDIO GALLERY
    // --

    const $feed_dzsapConfigs = jQuery('.dzsap-feed--dzsap-configs');
    if ($feed_dzsapConfigs.length) {
      window.dzsap_apconfigs = JSON.parse($feed_dzsapConfigs.last().html());
    }


    registerToJquery($);

  })(jQuery);
}

window.dzsap_singleton_ready_calls_is_called = false;

window.dzsap_get_base_url = function () {

  window.dzsap_base_url = (window.dzsap_settings && window.dzsap_settings.pluginurl) ? window.dzsap_settings.pluginurl : getBaseUrl('dzsap_base_url', 'audioplayer.js');

}

function register_dzsap_callScriptsOnReady() {
  jQuery(document).ready(function ($) {


    // -- main call

    if (!window.dzsap_singleton_ready_calls_is_called) {
      dzsap_singleton_ready_calls();
    }


    dzsag_init('.audiogallery.auto-init');


    jQueryAuxBindings($);


  });


}


window.dzsap_currplayer_focused = null;
window.dzsap_currplayer_from_share = null;
window.dzsap_mouseover = false;


window.dzsap_init_allPlayers = function ($) {

  $('.audioplayer.auto-init,.audioplayer-tobe.auto-init').each(function () {
    var _t2 = $(this);
    if (_t2.hasClass('audioplayer-tobe')) {
      if (window.dzsap_init) {
        dzsap_init(_t2);
      }
    }
  })
}


async function dzsap_jQueryInit(callback, reject) {

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
      script.src = ConstantsDzsAp.URL_JQUERY;

      document.head.appendChild(script);
    }

    setTimeout(() => {
      reject('error loading');
    }, 15000);
  })
}

dzsap_jQueryInit().then(() => {
  register_dzsap_plugin();
  register_dzsap_callScriptsOnReady();
  jQuery(document).ready(function ($) {
    window.dzsap_init_allPlayers($)
  });


  if(!window.dzsap_player_isOneTimeSetuped){
    dzsap_oneTimeSetups();
    window.dzsap_player_isOneTimeSetuped = true;
  }
}).catch((err) => {
  console.log(err);
})


window.dzsap_init = function (selector, settings) {

  jQuery(selector).audioplayer(Object.assign({}, settings));

  window.dzsap_lasto = settings;


};
playerRegisterWindowFunctions();
