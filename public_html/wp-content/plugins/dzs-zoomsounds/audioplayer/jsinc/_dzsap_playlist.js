import * as dzsapHelpers from './_dzsap_helpers';
import {playbtn_svg} from './_dzsap_svgs';
import {ConstantsDzsAp} from "../configs/_constants";
import {default_opts} from '../configs/_settingsPlaylist';

import * as ZoomSoundsNav from './components/_nav';


class DzsApPlaylist {
  constructor(argThis, argOptions, $) {

    this.argThis = argThis;
    this.argOptions = argOptions;
    this.$ = $;
    this.navClass = null;


    this.init();
  }

  init() {


    var $ = this.$;
    var selfGallery = this;

    var o = selfGallery.argOptions;
    var cgallery = $(selfGallery.argThis);
    var cid = 'ag1';
    var currNr = -1 // -- the current player that is playing
      , lastCurrNr = 0
      , nrChildren = 0
      , tempNr = 0;

    var i = 0;


    var dzsap_currplayer_focused = null;
    var _sliderMain, _sliderClipper, _navMain, _navClipper, _cache;
    var busy = false,
      first = true,
      destroyed = true,
      skin_redlight_give_controls_right_to_all_players = false // -- if the mode is mode-showall and the skin of the player is redlights, then make all players with controls right
    ;


    var trying_to_get_track_data = false;


    var arr_menuitems = [];
    var track_data = []; // -- the whole track data views / likes etc.

    var str_alertBeforeRate = 'You need to comment or rate before downloading.';


    var duration_viy = 20;

    var target_viy = 0;

    var begin_viy = 0;

    var change_viy = 0;

    selfGallery.goto_item = goto_item;
    selfGallery.handleResize = handleResize;


    selfGallery.initOptions = o;
    if (window.dzsap_settings && typeof (window.dzsap_settings.str_alertBeforeRate) != 'undefined') {
      str_alertBeforeRate = window.dzsap_settings.str_alertBeforeRate;
    }

    cgallery.get(0).currNr_2 = -1; // -- we use this as backup currNR for mode-showall ( hack )

    init();

    function init() {
      // -- init gallery here


      if (o.settings_ap === 'default') {
        if (cgallery.attr('data-player-options')) {
          o.settings_ap = dzsapHelpers.convertPluginOptionsToFinalOptions(cgallery.get(0), {}, null, 'data-player-options');
        } else {
          const _firstPlayer = cgallery.find('.audioplayer, .audioplayer-tobe').eq(0);
          if (_firstPlayer) {

            o.settings_ap = dzsapHelpers.convertPluginOptionsToFinalOptions(_firstPlayer.get(0), {}, null);
          }
        }
      } else {
        if (typeof o.settings_ap == 'string' && window.dzsap_apconfigs) {
          if (typeof window.dzsap_apconfigs[o.settings_ap] === 'object') {
            o.settings_ap = {...window.dzsap_apconfigs[o.settings_ap]};
          }
        }
      }


      if (o.settings_ap === 'default' || typeof o.settings_ap === 'string') {
        o.settings_ap = {};
      }


      if (o.design_menu_width === 'default') {
        o.design_menu_width = '100%';
      }

      if (o.design_menu_height === 'default') {
        o.design_menu_height = '200';
      }


      if (cgallery.hasClass('skin-wave')) {
        o.design_skin = 'skin-wave';
      }
      if (cgallery.hasClass('skin-default')) {
        o.design_skin = 'skin-default';
      }
      if (cgallery.hasClass('skin-aura')) {
        o.design_skin = 'skin-aura';
      }


      cgallery.addClass(o.settings_mode);


      cgallery.append('<div class="slider-main"><div class="slider-clipper"></div></div>');

      cgallery.addClass('menu-position-' + o.design_menu_position);

      _sliderMain = cgallery.find('.slider-main').eq(0);


      var lengthAudioPlayersInPlaylist = cgallery.find('.items').children('.audioplayer-tobe').length;

      // --- if there is a single audio player in the gallery - theres no point of a menu


      o.settings_ap.disable_player_navigation = o.disable_player_navigation;
      if (lengthAudioPlayersInPlaylist === 0 || lengthAudioPlayersInPlaylist === 1) {
        o.design_menu_position = 'none';
        o.settings_ap.disable_player_navigation = 'on';
      }


      selfGallery.navClass = new ZoomSoundsNav.ZoomSoundsNav(selfGallery);


      if (o.design_menu_position === 'top') {
        _sliderMain.before(selfGallery.navClass.get_structZoomsoundsNav());
      }
      if (o.design_menu_position === 'bottom') {
        _sliderMain.after(selfGallery.navClass.get_structZoomsoundsNav());
      }


      if (o.settings_php_handler) {

      } else {
        if (o.settings_ap.settings_php_handler) {
          o.settings_php_handler = o.settings_ap.settings_php_handler;
        }
      }


      if (typeof cgallery.attr('id')) {
        cid = cgallery.attr('id');
      } else {

        var ind = 0;
        while ($('ag' + ind).length === 0) {
          ind++;
        }


        cid = 'ag' + ind;

        cgallery.attr('id', cid);
      }


      _sliderClipper = cgallery.find('.slider-clipper').eq(0);
      _navMain = cgallery.find('.nav-main').eq(0);
      _navClipper = cgallery.find('.nav-clipper').eq(0);

      if (cgallery.children('.extra-html').length) {
        cgallery.append(cgallery.children('.extra-html'));
      }

      if (o.settings_mode === 'mode-showall') {
        _sliderClipper.addClass('layout-' + o.mode_showall_layout);
      }

      selfGallery.navClass.set_elements(_navMain, _navClipper, cgallery);


      reinit();


      selfGallery.navClass.init_ready();


      parse_track_data();


      if (dzsapHelpers.can_history_api() === false) {
        o.settings_enable_linking = 'off';
      }

      $(window).on('resize', handleResize);
      handleResize();
      setTimeout(handleResize, 1000);


      cgallery.get(0).api_skin_redlights_give_controls_right_to_all = function () {


        skin_redlight_give_controls_right_to_all_players = true;
      }


      if (dzsapHelpers.get_query_arg(window.location.href, 'audiogallery_startitem_' + cid)) {
        tempNr = Number(dzsapHelpers.get_query_arg(window.location.href, 'audiogallery_startitem_' + cid));

        lastCurrNr = tempNr;
        if (Number(dzsapHelpers.get_query_arg(window.location.href, 'audiogallery_startitem_' + cid)) && Number(dzsapHelpers.get_query_arg(window.location.href, 'audiogallery_startitem_' + cid)) > 0) {


          // -- caution .. coming from share link will trigger autoplay!!!
          if (o.force_autoplay_when_coming_from_share_link == 'on') {
            o.autoplay = 'on';
          }
        }
      }


      if (o.settings_mode == 'mode-normal') {

        goto_item(tempNr, {
          'called_from': 'init'
        });
      }


      if (o.settings_mode === 'mode-showall') {
        // -- mode-showall

        _sliderClipper.children().each(function () {
          var _t = $(this);


          var ind = _t.parent().children('.audioplayer,.audioplayer-tobe').index(_t);

          if (_t.hasClass('audioplayer-tobe')) {


            var player_args = Object.assign({}, o.settings_ap);
            player_args.parentgallery = cgallery;
            player_args.call_from = 'mode show-all';
            player_args.action_audio_play = mode_showall_listen_for_play;

            // -- showall
            _t.audioplayer(player_args);


            ind = String(ind + 1);

            if (ind.length < 2) {
              ind = '0' + ind;
            }

            if (o.mode_showall_layout === 'one-per-row' && o.settings_mode_showall_show_number !== 'off') {

              _t.before('<div class="number-wrapper"><span class="the-number">' + ind + '</span></div>')
              _t.after('<div class="clear for-number-wrapper"></div>')
            }
          }

        })


        if ($.fn.isotope && o.mode_showall_layout !== 'one-per-row') {

          // -- we have isotope
          _sliderClipper.find('.audioplayer,.audioplayer-tobe').addClass('isotope-item');
          setTimeout(function () {

            _sliderClipper.prepend('<div class="grid-sizer"></div>');
            _sliderClipper.isotope({
              // options
              itemSelector: '.isotope-item',
              layoutMode: 'fitRows',
              percentPosition: true,
              masonry: {
                columnWidth: '.grid-sizer'
              }
            });
            _sliderClipper.addClass('isotoped');
            setTimeout(function () {
              _sliderClipper.isotope('layout')
            }, 900);
          }, ConstantsDzsAp.PLAYLIST_TRANSITION_DURATION);


          _sliderClipper.append('<div class="clear"></div>');
        }


        if (skin_redlight_give_controls_right_to_all_players) {

          _sliderClipper.children('.audioplayer').each(function () {

            var _t = $(this);


            if (_t.find('.ap-controls-right').eq(0).prev().hasClass('controls-right') === false) {
              _t.find('.ap-controls-right').eq(0).before('<div class="controls-right"> </div>');
            }
          });
        }

      }


      cgallery.find('.download-after-rate').on('click', click_downloadAfterRate);

      cgallery.get(0).api_regenerate_sync_players_with_this_playlist = regenerate_sync_players_with_this_playlist;
      cgallery.get(0).api_goto_next = goto_next;
      cgallery.get(0).api_goto_prev = goto_prev;
      cgallery.get(0).api_goto_item = goto_item;
      cgallery.get(0).api_gallery_handle_end = gallery_handle_end;
      cgallery.get(0).api_toggle_menu_state = toggle_menu_state;
      cgallery.get(0).api_handleResize = handleResize;
      cgallery.get(0).api_player_commentSubmitted = player_commentSubmitted;
      cgallery.get(0).api_player_rateSubmitted = player_rateSubmitted;
      cgallery.get(0).api_reinit = reinit;
      cgallery.get(0).api_play_curr_media = play_curr_media;
      cgallery.get(0).api_get_nr_children = get_nr_children;
      cgallery.get(0).api_init_player_from_gallery = init_player_from_gallery;
      cgallery.get(0).api_filter = filterPlayersInPlaylist;
      cgallery.get(0).api_destroy = destroy_gallery;


      setInterval(calculate_on_interval, 1000);


      setTimeout(init_loaded, 700);


      if (o.enable_easing == 'on') {

        handle_frame();
      }


      cgallery.addClass('dzsag-inited');

      cgallery.addClass('transition-' + o.playlistTransition);
      cgallery.addClass('playlist-transition-' + o.playlistTransition);


    }


    function destroy_gallery() {


      if (destroyed) {
        return false;
      }


      cgallery.remove();
      cgallery = null;

      destroyed = true;
    }

    function filterPlayersInPlaylist(filterBy, searchedString) {
      if (!(filterBy)) {
        filterBy = 'title';
      }

      /**
       *
       * @param $audioPlayer_
       * @returns {boolean} true if found
       */
      const filterForIsotope = ($audioPlayer_) => {

        var $audioPlayer = $($audioPlayer_);
        var referenceVal = '';

        if (filterBy === 'title') {
          referenceVal = $audioPlayer.find('.the-name').text();
        }


        if (searchedString === '') {
          return true;
        }
        return referenceVal.toLowerCase().indexOf(searchedString.toLowerCase()) > -1;


      }

      _sliderClipper.children().each(function () {
        var isAccordingToSearch = filterForIsotope(this);
        if (isAccordingToSearch) {
          $(this).addClass('is-according-to-search');
        } else {
          $(this).removeClass('is-according-to-search');
        }
        if (_sliderClipper.hasClass('isotoped')) {

          _sliderClipper.isotope({
            filter: '.is-according-to-search'
          });
        } else {
          if (isAccordingToSearch) {
            $(this).fadeIn('fast');
          } else {
            $(this).fadeOut('fast');
          }
        }
      });

    }

    function regenerate_sync_players_with_this_playlist() {

      // -- in case we play from playlist we overwrite whole footer playlist

      window.dzsap_syncList_players = [];

      _sliderClipper.children('.audioplayer,.audioplayer-tobe').each(function () {
        var _t = $(this);
        _t.addClass('feeded-whole-playlist');
        if (_t.attr('data-do-not-include-in-list') != 'on') {
          window.dzsap_syncList_players.push(_t);
        }
      })
    }


    function init_parse_track_data() {

      if (trying_to_get_track_data) {
        return false;
      }

      trying_to_get_track_data = true;

      var data = {
        action: 'dzsap_get_views_all',
        postdata: '1',
      };


      if (o.settings_php_handler) {
        $.ajax({
          type: "POST",
          url: o.settings_php_handler,
          data: data,
          success: function (response) {


            cgallery.attr('data-track-data', response);
            parse_track_data();

          },
          error: function (arg) {

          }
        });
      }


    }

    function parse_track_data() {
      if (cgallery.attr('data-track-data')) {
        try {
          track_data = JSON.parse(cgallery.attr('data-track-data'));
        } catch (err) {
          console.log(err);
        }

        if (track_data && track_data.length) {

          selfGallery.navClass.parseTrackData(track_data);

        }


      }


    }

    function get_nr_children() {
      return nrChildren;
    }

    function find_player_id(arg) {
      if (arg.attr('data-player-id')) {
        return arg.attr('data-player-id');
      } else {
        if (arg.attr('id')) {
          return arg.attr('id');
        } else {
          if (arg.attr('data-source')) {
            return dzsapHelpers.dzs_clean_string(arg.attr('data-source'));
          }
        }
      }
    }

    function generateMenuItemObjects(notInitedPlayers){

      const arr_menuitems = [];
      notInitedPlayers.each(function(){
        var _c = $(this);
        let menuDescriptionHtml = '';

        if (_c.find('.menu-description').length && _c.find('.menu-description').eq(0).html()) {
          menuDescriptionHtml = _c.find('.menu-description').html();
        } else {
          menuDescriptionHtml = '';

          if (_c.find('.feed-artist-name').length || _c.find('.feed-song-name').length) {

            menuDescriptionHtml = ``;
            if (_c.attr('data-thumb')) {
              menuDescriptionHtml += `<div class="menu-item-thumb-con"><div class="menu-item-thumb" style="background-image: url(${_c.attr('data-thumb')})"></div></div>`;
            }
            menuDescriptionHtml += `<div class="menu-artist-info"><span class="the-artist">${_c.find('.feed-artist-name').html()}</span><span class="the-name">${_c.find('.feed-song-name').html()}</span></div>`
          }
        }


        var menuItemObject = {
          'menu_description': menuDescriptionHtml,
          'player_id': find_player_id(_c)
        }

        arr_menuitems.push(menuItemObject)


      })

      return arr_menuitems;
    }

    function reinit() {


      const notInitedPlayers = cgallery.find('.items').eq(0).children('.audioplayer-tobe');
      arr_menuitems = [];


      var player_id = '';

      arr_menuitems = generateMenuItemObjects(notInitedPlayers);
      _sliderClipper.append(notInitedPlayers);


      for (i = 0; i < arr_menuitems.length; i++) {
        var extra_class = '';
        if (arr_menuitems[i].menu_description && arr_menuitems[i].menu_description.indexOf('<div class="menu-item-thumb-con"><div class="menu-item-thumb" style="') == -1) {
          extra_class += ' no-thumb';
        }


        var aux = '<div class="menu-item' + extra_class + '"  data-menu-index="' + i + '" data-gallery-id="' + cid + '" data-playerid="' + arr_menuitems[i].player_id + '">'

        if (cgallery.hasClass('skin-aura')) {
          aux += '<div class="menu-item-number">' + (++nrChildren) + '</div>';
        }

        aux += arr_menuitems[i].menu_description;


        if (cgallery.hasClass('skin-aura') && String(arr_menuitems[i].menu_description).indexOf('menu-item-views') == 1) {

          if (track_data && track_data.length > 0) {

            aux += '<div class="menu-item-views"></div>';
          } else {

            init_parse_track_data();
            aux += '<div class="menu-item-views">' + playbtn_svg + ' ' + '<span class="the-count">{{views_' + arr_menuitems[i].player_id + '}}' + '</span></div>';
          }

        }


        aux += '</div>';

        _navClipper.append(aux);


        if (cgallery.hasClass('skin-aura')) {

          if (arr_menuitems[i] && arr_menuitems[i].menu_description && arr_menuitems[i].menu_description.indexOf('float-right') > -1) {
            _navClipper.children().last().addClass('has-extra-info')
          }
        }

      }
    }

    function init_loaded() {
      // -- gallery

      cgallery.addClass('dzsag-loaded');
    }

    function click_downloadAfterRate() {
      var _t = $(this);


      if (!_t.hasClass('active')) {
        alert(str_alertBeforeRate)
        return false;
      }
    }


    function play_curr_media() {

      if (typeof (_sliderClipper.children().eq(currNr).get(0)) != 'undefined') {
        if (typeof (_sliderClipper.children().eq(currNr).get(0).api_play_media) != 'undefined') {
          _sliderClipper.children().eq(currNr).get(0).api_play_media({
            'call_from': 'play_curr_media_gallery'
          });
        }

      }
    }

    function mode_showall_listen_for_play(arg) {


      if (o.settings_mode == 'mode-showall') {

        var ind = _sliderClipper.children('.audioplayer,.audioplayer-tobe').index(arg);

        currNr = ind;
        cgallery.get(0).currNr_2 = ind;

      }

    }

    function handle_frame() {

      // -- cgallery

      if (isNaN(target_viy)) {
        target_viy = 0;
      }

      if (duration_viy === 0) {
        requestAnimationFrame(handle_frame);
        return false;
      }

      begin_viy = target_viy;
      change_viy = selfGallery.navClass.finish_viy - begin_viy;


      target_viy = Number(Math.easeIn(1, begin_viy, change_viy, duration_viy).toFixed(4));
      ;


      if (dzsapHelpers.is_ios() == false && dzsapHelpers.is_android() == false) {
        _navClipper.css({
          'transform': 'translateY(' + target_viy + 'px)'
        });
      }


      requestAnimationFrame(handle_frame);
    }


    function toggle_menu_state() {
      selfGallery.navClass.toggle_menu_state();
    }

    function gallery_handle_end() {

      if (o.autoplayNext == 'on') {

        goto_next();
      }
    }

    function player_commentSubmitted() {
      _navClipper.children('.menu-item').eq(currNr).find('.download-after-rate').addClass('active');

    }

    function player_rateSubmitted() {
      _navClipper.children('.menu-item').eq(currNr).find('.download-after-rate').addClass('active');
    }

    function calculateDims() {


      if (o.settings_mode != 'mode-showall' && !_sliderClipper.hasClass('isotoped') && o.mode_normal_video_mode != 'one') {
        // -- mode normal, not isotope
        if (_sliderClipper.children().eq(currNr).hasClass('zoomsounds-wrapper-bg-bellow') == false) {
          _sliderClipper.css('height', _sliderClipper.children().eq(currNr).outerHeight());

        }
      }

      if (!_sliderClipper.hasClass('isotoped')) {
        // -- not isotope
        setTimeout(function () {
          _sliderClipper.css('height', 'auto');
        }, ConstantsDzsAp.PLAYLIST_TRANSITION_DURATION);
      }


      selfGallery.navClass.calculateDims();

      if (o.embedded == 'on') {

        if (window.frameElement) {
          window.frameElement.height = cgallery.height();

        }
      }
    }


    function calculate_on_interval() {
      // -- @called on setInterval

      selfGallery.navClass.calculateDims();

      // -- this is for player ? todo ...
      if (0 && o.gallery_gapless_play == 'on') {
        var args = {
          'call_from': 'gapless_play'
        }

        if (o.parentgallery && cthis.hasClass('active-from-gallery')) {
          var _c = o.parentgallery;


          var _cach = _sliderClipper.children().eq(Number(_c.data('currNr')) + 1);


          if (!(_cach.data('gapless-inited') == true)) {

            var args = {
              preload_method: "auto"
              , "autoplay": "off"
              , "call_from": "gapless_play"
            }


            _c.get(0).api_init_player_from_gallery(_cach, args);

            _cach.data('gapless-inited', true);

            setTimeout(function () {
              _cach.get(0).api_handleResize();
            }, 1000)
          }
        }
      }


    }


    function handleResize() {

      if (o.settings_mode !== 'mode-showall' && _sliderClipper.hasClass('isotoped') === false) {
        setTimeout(function () {

          _sliderClipper.css('height', _sliderClipper.children().eq(currNr).outerHeight());
        }, 500);
      }

      calculateDims();

    }

    function transition_end(newCurrNr) {
      _sliderClipper.children().eq(lastCurrNr).removeClass('transitioning-out');

      _sliderClipper.children().eq(newCurrNr).removeClass('transitioning-in');
      lastCurrNr = currNr;
      busy = false;
    }

    function transition_bg_end() {
      cgallery.parent().children('.the-bg').eq(0).remove();
      busy = false;
    }

    function goto_prev() {
      tempNr = currNr;
      tempNr--;

      var isGoingToItem = true;


      if (tempNr < 0) {
        tempNr = _sliderClipper.children().length - 1;

        if (o.loop_playlist == 'off') {
          isGoingToItem = false;
        }
      }

      if (isGoingToItem) {

        goto_item(tempNr);
      }
    }

    function goto_next() {

      tempNr = currNr;


      var isGoingToItem = true;

      if (o.settings_mode == 'mode-showall') {
        tempNr = cgallery.get(0).currNr_2;
      }
      tempNr++;
      if (tempNr >= _sliderClipper.children().length) {
        tempNr = 0;

        if (o.loop_playlist == 'off') {
          isGoingToItem = false;
        }
      }


      if (isGoingToItem) {

        goto_item(tempNr);
      }
    }

    function goto_item(newCurrNr, pargs) {


      var margs = {

        'ignore_arg_currNr_check': false
        , 'ignore_linking': false // -- does not change the link if set to true
        , donotopenlink: "off"
        , called_from: "default"
      }

      if (pargs) {
        margs = $.extend(margs, pargs);
      }


      if (busy == true) {
        return;
      }

      if (newCurrNr == "last") {
        newCurrNr = _sliderClipper.children().length - 1;
      }


      if (Boolean(currNr == newCurrNr)) {
        if (_sliderClipper && _sliderClipper.children().eq(currNr).get(0) && _sliderClipper.children().eq(currNr).get(0).api_play_media) {
          _sliderClipper.children().eq(currNr).get(0).api_play_media({
            'call_from': 'gallery'
          });
        }
        return;
      }

      var _audioplayerToBeActive = _sliderClipper.children('.audioplayer,.audioplayer-tobe').eq(newCurrNr);


      var currNr_last_vol = '';

      if (currNr > -1) {
        if (typeof (_sliderClipper.children().eq(currNr).get(0)) != 'undefined') {
          if (typeof (_sliderClipper.children().eq(currNr).get(0).api_pause_media) != 'undefined') {
            _sliderClipper.children().eq(currNr).get(0).api_pause_media();
          }
          if (typeof (_sliderClipper.children().eq(currNr).get(0).api_get_last_vol) != 'undefined') {
            currNr_last_vol = _sliderClipper.children().eq(currNr).get(0).api_get_last_vol();
          }

        }


        _navClipper.children().removeClass('active active-from-gallery');


        if (o.mode_normal_video_mode == 'one') {

        } else {

          if (o.settings_mode != 'mode-showall') {


            _sliderClipper.children().eq(currNr).removeClass('active active-from-gallery');
            _navClipper.children().eq(currNr).removeClass('active active-from-gallery');


          }
        }

      }


      // --  setting settings
      if (o.settings_ap.design_skin === 'sameasgallery') {
        o.settings_ap.design_skin = o.design_skin;
      }


      // -- if this is  the first audio
      if (currNr == -1 && o.autoplay == 'on') {
        o.settings_ap.autoplay = 'on';
      }


      // -- if this is not the first audio
      if (currNr > -1 && o.autoplayNext == 'on') {
        o.settings_ap.autoplay = 'on';
      }
      o.settings_ap.parentgallery = cgallery;

      o.settings_ap.design_menu_show_player_state_button = o.design_menu_show_player_state_button;
      o.settings_ap.cue = 'on';
      if (first == true) {
        if (o.cueFirstMedia == 'off') {
          o.settings_ap.cue = 'off';
        }

        first = false;
      }

      // -- setting settings END


      var args_player = $.extend({}, o.settings_ap);


      args_player.volume_from_gallery = currNr_last_vol;
      args_player.call_from = 'gotoItem';
      args_player.player_navigation = o.player_navigation;


      if (o.mode_normal_video_mode == 'one' && newCurrNr > -1 && margs.called_from != 'init') {
        // -- video mode -> one


        var _c = _sliderClipper.children().eq(0).get(0);
        _audioplayerToBeActive = _sliderClipper.children().eq(0);

        if (_c) {
          if (_c.api_play_media) {


            _c.api_change_media(_sliderClipper.children().eq(newCurrNr), {
              'called_from': 'goto_item -- mode_normal_video_mode()',
              'modeOneGalleryIndex': newCurrNr,
              'source_player_do_not_update': 'on',

            });

            if (o.autoplayNext == 'on') {
              setTimeout(function () {
                _c.api_play_media();
              }, 200);
            }
          }
        }
      } else {

        // -- init player from gallery
        init_player_from_gallery(_audioplayerToBeActive, args_player);

      }


      // -- actions after init
      if (o.autoplayNext === 'on') {
        if (o.settings_mode === 'mode-showall') {
          currNr = cgallery.get(0).currNr_2;
        }
        if (!!(currNr > -1 && _audioplayerToBeActive.get(0) && _audioplayerToBeActive.get(0).api_play)) {
          _audioplayerToBeActive.get(0).api_play();
        }
      }

      if (o.settings_ap.playfrom === undefined || o.settings_ap.playfrom === "0") {
        if (_audioplayerToBeActive.get(0) && _audioplayerToBeActive.get(0).api_seek_to) {
          _audioplayerToBeActive.get(0).api_seek_to(0, {call_from: 'playlist_seek_from_0'});
        } else {
          console.log('_audioplayerToBeActive not found - ', _audioplayerToBeActive);
        }
      }


      // -- end actions after init

      dzsap_currplayer_focused = _audioplayerToBeActive.get(0);


      if (o.settings_mode !== 'mode-showall') {
        _sliderClipper.children().eq(currNr).addClass('transitioning-out');
        _audioplayerToBeActive.removeClass('transitioning-out-complete');
        _audioplayerToBeActive.addClass('transitioning-in');
        setTimeout((_arg) => {
          _arg.addClass('transitioning-out-complete')
        }, ConstantsDzsAp.PLAYLIST_TRANSITION_DURATION, _sliderClipper.children().eq(currNr));

        if (_audioplayerToBeActive.attr('data-type') != 'link') {
          if (margs.ignore_linking == false && o.settings_enable_linking == 'on') {
            var stateObj = {foo: "bar"};
            history.pushState(stateObj, null, dzsapHelpers.add_query_arg(window.location.href, 'audiogallery_startitem_' + cid, (newCurrNr)));
          }
        }

        if (o.playlistTransition === 'fade') {
          setTimeout(transition_end, ConstantsDzsAp.PLAYLIST_TRANSITION_DURATION, newCurrNr);
          busy = true;
        }
        if (o.playlistTransition === 'direct') {
          transition_end(newCurrNr);
        }
      }

      _audioplayerToBeActive.addClass('active active-from-gallery');
      _navClipper.children().eq(newCurrNr).addClass('active active-from-gallery');

      // -- background parent


      var bgimage = '';

      if (_audioplayerToBeActive.attr("data-bgimage")) {
        bgimage = _audioplayerToBeActive.attr("data-bgimage");
      }

      if (_audioplayerToBeActive.attr("data-wrapper-image")) {
        bgimage = _audioplayerToBeActive.attr("data-wrapper-image");
      }


      if (bgimage && cgallery.parent().hasClass('ap-wrapper') && cgallery.parent().children('.the-bg').length > 0) {


        cgallery.parent().children('.the-bg').eq(0).after('<div class="the-bg" style="background-image: url(' + bgimage + ');"></div>')
        cgallery.parent().children('.the-bg').eq(0).css({
          'opacity': 1
        })


        cgallery.parent().children('.the-bg').eq(1).css({
          'opacity': 0
        })
        cgallery.parent().children('.the-bg').eq(1).animate({
          'opacity': 1
        }, {
          queue: false,
          duration: 1000,
          complete: transition_bg_end,
          step: function () {
            busy = true;
          }
        })
        busy = true;
      }


      if (o.settings_mode != 'mode-showall') {

        currNr = newCurrNr;

        cgallery.data('currNr', currNr);
      }


      if (_sliderClipper.children().eq(currNr).get(0) && _sliderClipper.children().eq(currNr).get(0).api_handleResize && _sliderClipper.children().eq(currNr).hasClass('media-setuped')) {


        _sliderClipper.children().eq(currNr).get(0).api_handleResize();
      }

      calculateDims();
    }

    function init_player_from_gallery(_cache, pargs) {

      var player_args = $.extend({}, o.settings_ap);


      if (pargs) {
        player_args = $.extend(player_args, pargs);
      }


      if (_cache.hasClass('audioplayer-tobe')) {
        o.settings_ap.call_from = 'init player from gallery';

        player_args.is_inited_from_playlist = true;
        _cache.audioplayer(player_args);
      }
    }
  }
}

export const registerToJquery = function ($) {
  $.fn.audiogallery = function (argOptions) {
    var finalOptions = {};
    const defaultOptions = {...default_opts};
    finalOptions = dzsapHelpers.convertPluginOptionsToFinalOptions(this, defaultOptions, argOptions);


    this.each(function () {


      this.linkedClassInstance = new DzsApPlaylist(this, finalOptions, $);
    });
  }


  window.dzsag_init = function (selector, settings) {
    $(selector).audiogallery(Object.assign({}, settings));
  }
}
