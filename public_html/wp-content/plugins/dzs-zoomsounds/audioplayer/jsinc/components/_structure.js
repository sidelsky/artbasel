import {extraHtmlBottomFunctionality, feedEmbedFunctionality} from "../extra-functionality/_extraHtmlFunctions";
import * as dzsapHelpers from '../_dzsap_helpers';
import {DzsapInnerPlaylist} from '../extra-functionality/_innerPlaylist';
import {pausebtn_svg, playbtn_svg, svg_embed_btn, svg_menu_state, svg_next_btn, svg_prev_btn} from '../_dzsap_svgs';


export function setup_structure_extras(selfClass, o) {


  if (o.design_skin === 'skin-wave' && selfClass.skinwave_mode === 'bigwavo') {
    selfClass._audioplayerInner.after(selfClass._scrubbar);

    if (selfClass.cthis.find('.feed-description')) {
      selfClass.$conControls.after(selfClass.cthis.find('.feed-description').eq(0));
      selfClass.$conControls.next().removeClass('feed-description').addClass('song-desc');
    }
  }


  selfClass.radio_isGoingToUpdateSongName = dzsapHelpers.player_radio_isNameUpdatable(selfClass, selfClass.radio_isGoingToUpdateSongName, '.the-songname');
  selfClass.radio_isGoingToUpdateArtistName = dzsapHelpers.player_radio_isNameUpdatable(selfClass, selfClass.radio_isGoingToUpdateArtistName, '.the-artist');




  if (o.disable_scrub === 'on') {
    selfClass.cthis.addClass('disable-scrubbar');
  }


  const struct_embedButtonWithTooltip = `<div class="btn-embed-code-con dzstooltip-con "><div class="btn-embed-code player-but "> <div class="the-icon-bg"></div>${svg_embed_btn}</div><span class="dzstooltip   transition-slidein arrow-bottom talign-end style-rounded color-dark-light " style="width: 350px; "><span class="dzstooltip--inner"><span class="embed-code--text"></span></span></span></div>`;

  if (selfClass.feedEmbedCode !== '') {
    feedEmbedFunctionality(selfClass, jQuery, dzsapHelpers, struct_embedButtonWithTooltip)
  }

  if (o.footer_btn_playlist === 'on') {
    if (selfClass._apControlsRight.find('.btn-footer-playlist').length === 0) {

      selfClass.classFunctionalityInnerPlaylist = new DzsapInnerPlaylist(selfClass);
      selfClass.classFunctionalityInnerPlaylist.init();
    }

  }


  setTimeout(function () {

    if (selfClass.cthis.find('.extra-html').length) {
      extraHtmlBottomFunctionality(selfClass);
    }
  }, 100);

  setTimeout(function () {
    if (selfClass.cthis.html().indexOf('dzsap-multisharer-but') > -1) {
      selfClass.isMultiSharer = true;
      selfClass.check_multisharer();
    }
  }, 1002);

  if (selfClass.cthis.find('.con-after-playpause').length) {
    selfClass.$conPlayPause.after(selfClass.cthis.find('.con-after-playpause').eq(0));
  }

  if (selfClass.cthis.find('.afterplayer').length > 0) {

    selfClass.cthis.append(selfClass.cthis.find('.afterplayer'));
  }

}

function structure_generatePlayPauseString(selfClass) {

  var o = selfClass.initOptions;

  let struct_con_playpause = '';
  if (o.settings_extrahtml_before_play_pause) {
    struct_con_playpause += o.settings_extrahtml_before_play_pause;


  }


  struct_con_playpause += '<div class="con-playpause-con">';

  struct_con_playpause = addExtraHtmlInPlace(selfClass, '.feed-dzsap-before-playpause', struct_con_playpause) + struct_con_playpause;

  struct_con_playpause += '<div class="con-playpause';

  if (selfClass.keyboard_controls.show_tooltips === 'on') {
    struct_con_playpause += ' dzstooltip-con';
  }

  struct_con_playpause += '">';
  if (selfClass.keyboard_controls.show_tooltips === 'on') {
    struct_con_playpause += dzsapHelpers.dzsap_generate_keyboard_tooltip(selfClass.keyboard_controls, 'pause_play');
  }


  struct_con_playpause += '<div class="playbtn player-but" aria-controls="' + selfClass.uniqueId + '-audio"><div class="the-icon-bg"></div><div class="dzsap-play-icon">';



  struct_con_playpause += playbtn_svg;


  struct_con_playpause += '</div>';
  struct_con_playpause += '</div>'; // -- end playbtn


  struct_con_playpause += '<div class="pausebtn player-but"';


  struct_con_playpause += '><div class="the-icon-bg"></div><div class="pause-icon">';


  struct_con_playpause += pausebtn_svg;


  struct_con_playpause += '</div>';// -- end pause-icon
  struct_con_playpause += '</div>'; // -- end pausebtn


  struct_con_playpause += '';


  struct_con_playpause += '</div>';

  struct_con_playpause += addExtraHtmlInPlace(selfClass, '.feed-dzsap-after-playpause', struct_con_playpause);


  struct_con_playpause += '</div>';

  return struct_con_playpause;
}

function setup_structure__setup_wrapper_image(selfClass) {

  var img = new Image();


  if (selfClass.cthis.hasClass('zoomsounds-no-wrapper') === false) {

    img.onload = function () {



      selfClass.cthis.css('background-image', 'url(' + this.src + ')');

      setTimeout(function () {

        selfClass.cthis.find('.zoomsounds-bg').addClass('loaded');


        if (selfClass.cthis.hasClass('zoomsounds-wrapper-bg-bellow')) {

          selfClass.cthis.css('padding-top', 200);
        }
      }, 100);
    }

    img.src = selfClass.cthis.attr('data-wrapper-image');
  }

}

/**
 * setup player structure , called from init
 * @param selfClass
 * @param pargs
 * @returns {boolean}
 */
export const setup_structure = function (selfClass, pargs) {


// -- setup structure here
  var $ = jQuery;
  var o = selfClass.initOptions;


  var margs = {
    'setup_inner_player': true
    , 'setup_media': true
    , 'setup_otherstructure': true
    , 'call_from': "default"


  }



  if (pargs) {
    margs = $.extend(margs, pargs);
  }




  if (margs.call_from === 'reconstruct') {
    if (selfClass._metaArtistCon) {


    }


    selfClass._metaArtistCon = null;
    if (selfClass.cthis.hasClass('skin-wave')) {
      o.design_skin = 'skin-wave';
    }
    if (selfClass.cthis.hasClass('skin-silver')) {
      o.design_skin = 'skin-silver';
    }
  }


  var structure_str_apControls = '<div class="ap-controls';

  if (o.design_skin === 'skin-default') {
    structure_str_apControls += ' dzsap-color_inverse_ui_fill';
  }
  structure_str_apControls += '"></div>'

  if (margs.setup_inner_player) {
    selfClass.cthis.appendOnce('<div class="audioplayer-inner"></div>');
    selfClass._audioplayerInner = selfClass.cthis.children('.audioplayer-inner');
  }


  // -- end setup inner


  if (!margs.setup_otherstructure) {
    return false;
  }


  if (selfClass.cthis.attr('data-wrapper-image')) {
    setup_structure__setup_wrapper_image(selfClass);
  }


  var structure_str_scrubbar = '<div class="scrubbar">';
  var aux_str_con_controls = '';

  var aux_str_time = '';


  structure_str_scrubbar += '<div class="scrub-bg"></div><div class="scrub-buffer"></div>';

  structure_str_scrubbar += '<div class="scrub-prog';

  if (o.scrubbar_type !== 'wave') {
    structure_str_scrubbar += ' dzsap-color_brand_bg';
  }

  structure_str_scrubbar += '"></div><div class="scrubBox"></div><div class="scrubBox-prog"></div><div class="scrubBox-hover"></div>';
  aux_str_time = '<div class="total-time">00:00</div><div class="curr-time">00:00</div>';


  if (selfClass.sample_perc_start) {

    structure_str_scrubbar += '<div class="sample-block-start" style="width: ' + (selfClass.sample_perc_start * 100) + '%"></div>'
  }
  if (selfClass.sample_perc_end) {

    structure_str_scrubbar += '<div class="sample-block-end" style="left: ' + (selfClass.sample_perc_end * 100) + '%; width: ' + (100 - (selfClass.sample_perc_end * 100)) + '%"></div>'
  }

  structure_str_scrubbar += '</div>'; // -- end scrubbar


  if (o.controls_external_scrubbar) {
    structure_str_scrubbar = '';
  }


  var struct_con_playpause = '';

  struct_con_playpause += structure_generatePlayPauseString(selfClass);






  aux_str_con_controls += '<div class="con-controls"><div class="the-bg"></div>' + struct_con_playpause;


  if (selfClass.extraHtmlAreas.controlsLeft) {
    aux_str_con_controls += selfClass.extraHtmlAreas.controlsLeft;
  }





  if (o.design_skin === 'skin-pro') {
    aux_str_con_controls += '<div class="con-controls--right">';
    aux_str_con_controls += '</div>';
  }


  var aux_str_con_volume = '<div class="controls-volume"><div class="volumeicon"></div><div class="volume_static"></div><div class="volume_active"></div><div class="volume_cut"></div></div>';
  if (o.disable_volume === 'on') {
    aux_str_con_volume = '';
  }


  if (o.design_skin === 'skin-default' || o.design_skin === 'skin-wave') {

    aux_str_con_controls += '<div class="ap-controls-right">';
    if (o.disable_volume !== 'on') {
      aux_str_con_controls += '<div class="controls-volume"><div class="volumeicon"></div><div class="volume_static"></div><div class="volume_active"></div><div class="volume_cut"></div></div>';
    }




    aux_str_con_controls += '</div>';



  }

  aux_str_con_controls += '</div>'; // -- end con-controls


/// -- end STR




  if (o.design_skin === 'skin-wave' && selfClass.skinwave_mode === 'small') {
    aux_str_con_controls = '<div class="the-bg"></div><div class="ap-controls-left">' + struct_con_playpause + '</div>' + structure_str_scrubbar + '<div class="ap-controls-right">' + aux_str_con_volume + '<div class="extrahtml-in-float-right for-skin-wave-small">' + selfClass.extraHtmlAreas.controlsRight + '</div></div>';


  } else {


    // -- other skins

    if (o.design_skin === 'skin-aria' || o.design_skin === 'skin-silver' || o.design_skin === 'skin-redlights' || o.design_skin === 'skin-steel') {



      let playbtnSvg = playbtn_svg;
      let pausebtnSvg = pausebtn_svg;
      if (o.design_skin === 'skin-steel') {
        playbtnSvg = '';
        pausebtnSvg = '';
      }

      aux_str_con_controls = '<div class="the-bg"></div><div class="ap-controls-left">';


      if (o.design_skin === 'skin-silver') {

        aux_str_con_controls += struct_con_playpause;
      } else {




        aux_str_con_controls += '<div class="con-playpause';

        if (selfClass.keyboard_controls.show_tooltips === 'on') {
          aux_str_con_controls += ' dzstooltip-con';
        }

        aux_str_con_controls += '">';


        if (selfClass.keyboard_controls.show_tooltips === 'on') {
          aux_str_con_controls += dzsapHelpers.dzsap_generate_keyboard_tooltip(selfClass.keyboard_controls, 'pause_play');
        }


        aux_str_con_controls += '<div class="playbtn player-but playbtn-not-skin-silver"><div class="dzsap-play-icon">' + playbtnSvg + '</div></div><div class="pausebtn player-but" ';


        aux_str_con_controls += '><div class="pause-icon">' + pausebtnSvg + '</div></div></div>'; // -- enc con-playpause

      }




      addExtraHtmlInPlace(selfClass, '.feed-dzsap-after-playpause', struct_con_playpause);


      aux_str_con_controls += '</div>';


      if (selfClass.extraHtmlAreas.controlsRight) {
        aux_str_con_controls += '<div class="controls-right">' + selfClass.extraHtmlAreas.controlsRight + '</div>';




        if (o.design_skin === 'skin-redlights') {


          if (o.parentgallery && o.parentgallery.get(0).api_skin_redlights_give_controls_right_to_all) {
            o.parentgallery.get(0).api_skin_redlights_give_controls_right_to_all();
          }
        }
      }




      aux_str_con_controls += '<div class="ap-controls-right">';

      if (o.design_skin === 'skin-silver') {

        aux_str_con_controls += '<div class="controls-volume controls-volume-vertical"><div class="volumeicon"></div><div class="volume-holder"><div class="volume_static"></div><div class="volume_active"></div><div class="volume_cut"></div></div></div>';


        aux_str_con_controls += '</div>' + structure_str_scrubbar;
      } else {


        if (o.design_skin === 'skin-redlights') {

          if (o.disable_volume != 'on') {
            aux_str_con_controls += '<div class="controls-volume"><div class="volumeicon"></div><div class="volume_static"></div><div class="volume_active"></div><div class="volume_cut"></div></div>';
          }
        }

        aux_str_con_controls += structure_str_scrubbar;


        if (o.disable_timer != 'on') {
          aux_str_con_controls += '<div class="total-time">00:00</div>';
        }
      }


      if (o.design_skin === 'skin-silver') {

      } else {
        aux_str_con_controls += '</div>';
      }


    }


  }


// -- end strings
// --------------

  if (margs.setup_media) {
    selfClass._audioplayerInner.append('<div class="the-media"></div>');
    selfClass.$theMedia = selfClass._audioplayerInner.children('.the-media').eq(0);
  }
  if (o.design_skin !== 'skin-customcontrols') {
    selfClass._audioplayerInner.append(structure_str_apControls);
  }

  selfClass._apControls = selfClass._audioplayerInner.children('.ap-controls').eq(0);
  selfClass._apControls.append(aux_str_con_controls);


  if (selfClass.cthis.hasClass('skin-wave-mode-alternate')) {
    if (selfClass.cthis.find('.scrubbar').length === 0) {
      selfClass._apControls.append(structure_str_scrubbar);
    }
  } else {
    if (selfClass.cthis.find('.scrubbar').length === 0) {
      selfClass._apControls.prepend(structure_str_scrubbar);
    }
  }


  selfClass._apControlsRight = null;

  if (selfClass._apControls.find('.ap-controls-right').length > 0) {
    selfClass._apControlsRight = selfClass.cthis.find('.ap-controls-right');
  }
  if (selfClass._apControls.find('.ap-controls-left').length > 0) {
    selfClass._apControlsLeft = selfClass._apControls.find('.ap-controls-left').eq(0);
  }


  if (o.design_skin === 'skin-pro') {
    selfClass._apControlsRight = selfClass.cthis.find('.con-controls--right').eq(0)
  }





// -- Todo: if we have footer, playlist btn we can place it in ap-controls-right


  addExtraHtmlInPlace(selfClass, '.feed-dzsap--custom-controls', null, selfClass._audioplayerInner);
  addExtraHtmlInPlace(selfClass, '.feed-dzsap-after-con-controls', null, selfClass._apControls);


  if (o.controls_external_scrubbar) {
    selfClass._scrubbar = $(o.controls_external_scrubbar).children('.scrubbar').eq(0);
  } else {
    selfClass._scrubbar = selfClass._apControls.find('.scrubbar').eq(0);
  }



  selfClass.$$scrubbProg = selfClass._scrubbar.find('.scrub-prog').get(0);


  selfClass.$conControls = selfClass._apControls.children('.con-controls');
  selfClass.$conPlayPause = selfClass.cthis.find('.con-playpause').eq(0);
  selfClass._conPlayPauseCon = selfClass.cthis.find('.con-playpause-con').eq(0);
  selfClass.$controlsVolume = selfClass.cthis.find('.controls-volume').eq(0);




  (dzsapHelpers.player_constructArtistAndSongCon.bind(selfClass))(margs);



  selfClass._scrubbar.addClass('scrubbar-inited');
  if (o.scrubbar_type === 'wave' && o.disable_timer != 'on') {
    // -- no sense in adding time if external
    if (o.controls_external_scrubbar === '') {
      selfClass._scrubbar.append(aux_str_time);
    }
  }


  if (o.design_skin != 'skin-wave' && o.disable_timer != 'on') {
    // aux_str_con_controls += '<div class="curr-time">00:00</div><div class="total-time">00:00</div>';

    // -- all skins
    selfClass._apControls.append(aux_str_time);
  }


// -- end structure


// -- start assocations
  if (o.disable_timer != 'on') {
    selfClass.$currTime = selfClass.cthis.find('.curr-time').eq(0);
    selfClass.$totalTime = selfClass.cthis.find('.total-time').eq(0);

    if (o.design_skin === 'skin-steel') {
      if (selfClass.$currTime.length === 0) {
        selfClass.$totalTime.before('<div class="curr-time">00:00</div> <span class="separator-slash">/</span> ');


        selfClass.$currTime = selfClass.$totalTime.prev().prev();

      }
    }


  }


  if (Number(o.sample_time_total) > 0) {

    selfClass.timeTotal = Number(o.sample_time_total);


    if (selfClass.$totalTime) {


      selfClass.$totalTime.html(dzsapHelpers.formatTime(selfClass.time_total_for_visual));
    }




  }


  selfClass.setupStructure_thumbnailCon();


  if (o.design_skin === 'skin-wave' && o.parentgallery && typeof (o.parentgallery) != 'undefined' && o.design_menu_show_player_state_button === 'on') {
    if (o.design_skin === 'skin-wave') {
      if (selfClass._apControlsRight) {

        selfClass._apControlsRight.appendOnce('<div class="btn-menu-state player-but"> <div class="the-icon-bg"></div> ' + svg_menu_state + '    </div></div>');
      } else {
      }
    } else {
      selfClass._audioplayerInner.appendOnce('<div class="btn-menu-state"></div>');
    }
  }

  if (o.skinwave_place_metaartist_after_volume === 'on') {

    selfClass.$controlsVolume.before(selfClass._metaArtistCon);
  }


  if (o.skinwave_place_thumb_after_volume === 'on') {

    selfClass.$controlsVolume.before(selfClass.cthis.find('.the-thumb-con'));
  }



  if (o.design_skin === 'skin-wave') {


    // -- structure setup

    selfClass.setup_structure_scrub();


    if (o.skinwave_timer_static === 'on') {
      if (selfClass.$currTime) {
        selfClass.$currTime.addClass('static');
      }
      if (selfClass.$totalTime) {
        selfClass.$totalTime.addClass('static');
      }
    }


    selfClass._apControls.css({
      //'height': design_thumbh
    })





    if (o.skinwave_wave_mode === 'canvas') {

      setTimeout(function () {
        selfClass.cthis.addClass('scrubbar-loaded');
        selfClass._scrubbar.parent().addClass('scrubbar-loaded');



      }, 700); // -- tbc

    }

  }
// --- END skin-wave


  selfClass.check_multisharer();

  if (selfClass.cthis.hasClass('skin-minimal')) {
    // -- here is skin-minimal

    selfClass.cthis.find('.the-bg').before('<div class="skin-minimal-bg skin-minimal--outer-bg"></div><div class="skin-minimal-bg skin-minimal--inner-bg-under dzsap-color_brand_bg"></div><div class="skin-minimal-bg skin-minimal--inner-bg"></div><div class="skin-minimal-bg skin-minimal--inner-inner-bg dzsap-color_brand_bg"></div>')
    selfClass.cthis.find('.the-bg').append('<canvas width="100" height="100" class="playbtn-canvas"/>')
    selfClass.skin_minimal_canvasplay = selfClass.cthis.find('.playbtn-canvas').eq(0).get(0);

    if (selfClass.$conPlayPause) {

      selfClass.$conPlayPause.children('.playbtn').append(playbtn_svg);
      selfClass.$conPlayPause.children('.pausebtn').append(pausebtn_svg);
    }

    setTimeout(function () {
      selfClass.isCanvasFirstDrawn = false;
    }, 200);
  }


  if (selfClass.cthis.hasClass('skin-minion')) {
    if (selfClass.cthis.find('.menu-description').length > 0) {

      selfClass.$conPlayPause.addClass('with-tooltip');
      selfClass.$conPlayPause.prepend('<span class="dzstooltip" style="left:-7px;">' + selfClass.cthis.find('.menu-description').html() + '</span>');
      selfClass.$conPlayPause.children('span').eq(0).css('width', selfClass.$conPlayPause.children('span').eq(0).textWidth() + 10);
    }
  }





  if (o.player_navigation === 'default') {

    if (o.parentgallery) {

      o.player_navigation = 'on';
    }


    if (o.parentgallery && o.parentgallery.hasClass('mode-showall')) {
      o.player_navigation = 'off';
    }
  }

  if (o.disable_player_navigation === 'on') {

    o.player_navigation = 'off';
  }

  if (o.player_navigation === 'default') {

    o.player_navigation = 'off';
  }





  if (o.player_navigation === 'on') {

    var prev_btn_str = '<div class="prev-btn player-but"><div class="the-icon-bg"></div>' + svg_prev_btn + ' </div>';

    var next_btn_str = '<div class="next-btn player-but"><div class="the-icon-bg"></div>' + svg_next_btn + '  </div>';


    var auxs = prev_btn_str + next_btn_str;







    // -- create player navigation here
    if ((o.design_skin === 'skin-wave' && selfClass.skinwave_mode === 'small') || o.design_skin === 'skin-aria') {


      selfClass.$conPlayPause.before(prev_btn_str)
      selfClass.$conPlayPause.after(next_btn_str)


    } else {
      if (o.design_skin === 'skin-wave') {






        if (o.player_navigation === 'on') {

          selfClass._conPlayPauseCon.prependOnce(prev_btn_str, '.prev-btn');
          selfClass._conPlayPauseCon.appendOnce(next_btn_str, '.next-btn');
        }

      } else if (o.design_skin === 'skin-steel') {

        selfClass._apControlsLeft.prependOnce(prev_btn_str, '.prev-btn');

        if (selfClass._apControlsLeft.children('.the-thumb-con').length > 0) {


          if (selfClass._apControlsLeft.children('.the-thumb-con').eq(0).length > 0) {
            if (selfClass._apControlsLeft.children('.the-thumb-con').eq(0).prev().hasClass('next-btn') === false) {
              selfClass._apControlsLeft.children('.the-thumb-con').eq(0).before(next_btn_str);
            }
          }

        } else {

          selfClass._apControlsLeft.appendOnce(next_btn_str, '.next-btn');
        }
      } else {

        selfClass._audioplayerInner.appendOnce(auxs, '.prev-btn');
      }
    }

  }





  if (selfClass.cthis.hasClass('skinvariation-wave-bigtitles')) {
    if (selfClass.cthis.find('.controls-volume').length && selfClass._metaArtistCon.find('.controls-volume').length === 0) {
      selfClass._metaArtistCon.append('<br>');
      selfClass._metaArtistCon.append(selfClass.cthis.find('.controls-volume'));
    }

  }

  if (selfClass.cthis.hasClass('skinvariation-wave-righter')) {

    selfClass._apControls.appendOnce('<div class="playbuttons-con"></div>');
    var _c = selfClass.cthis.find('.playbuttons-con').eq(0);
    _c.append(selfClass.cthis.find('.con-playpause-con'));

  }




  if (o.design_skin === 'skin-pro') {

    selfClass._apControlsRight.append(selfClass.$currTime);
    selfClass._apControlsRight.append(selfClass.$totalTime);
  }


  if (o.design_skin === 'skin-silver') {
    selfClass._scrubbar.after(selfClass._apControlsRight);
    selfClass._apControlsLeft.prepend(selfClass._metaArtistCon);
    selfClass._apControlsLeft.append(selfClass.$currTime);
    selfClass._apControlsRight.append(selfClass.$totalTime);






  }


  if (o.design_skin === 'skin-redlights') {
    selfClass._apControlsRight.append('<div class="ap-controls-right--top"></div>');
    selfClass._apControlsRight.append('<div class="ap-controls-right--bottom"></div>');
    selfClass._apControlsRight.find('.ap-controls-right--top').append(selfClass._apControlsRight.find('.meta-artist-con'));
    selfClass._apControlsRight.find('.ap-controls-right--top').append(selfClass._apControlsRight.find('.controls-volume'));
    selfClass._apControlsRight.find('.ap-controls-right--bottom').append(selfClass._apControlsRight.find('.scrubbar'));
  }


  if (margs.call_from === 'reconstruct') {
    if (selfClass.cthis.hasClass('skin-silver')) {
      selfClass._apControlsLeft.append(selfClass.cthis.find('.con-playpause'));
    }
  }


  if (selfClass.isMultiSharer) {
    selfClass.check_multisharer();
  }

  selfClass.setup_structure_sanitizers();
  setup_structure_extras(selfClass, o);


  selfClass.cthis.addClass('structure-setuped');


  if (selfClass.extraHtmlAreas.afterArtist) {
    selfClass._metaArtistCon.find('.the-artist').append(selfClass.extraHtmlAreas.afterArtist);
  }



  if (selfClass.extraHtmlAreas.bottom !== '') {
    selfClass.cthis.append('<div class="extra-html">' + selfClass.extraHtmlAreas.bottom + '</div>');
  }



    var settings_extrahtml_in_float_right_str = '';



    if (String(selfClass.extraHtmlAreas.controlsRight).indexOf('dzsap-multisharer-but') > -1) {
      selfClass.isMultiSharer = true;
    }

    if (o.design_skin === 'skin-wave' && selfClass.skinwave_mode === 'small') {

    } else {

      settings_extrahtml_in_float_right_str += '<div class="extrahtml-in-float-right from-setup_structure from-js-setup_structure">' + selfClass.extraHtmlAreas.controlsRight + '</div>';
    }





    if (settings_extrahtml_in_float_right_str) {
      if (o.design_skin === 'skin-wave' || o.design_skin === 'skin-default') {

        selfClass.cthis.find('.ap-controls-right').eq(0).append(settings_extrahtml_in_float_right_str);

      }
      if (o.design_skin === 'skin-pro') {

        selfClass.cthis.find('.con-controls--right').eq(0).append(settings_extrahtml_in_float_right_str);

      }
    }


}

/**
 *
 * @param {DzsAudioPlayer} selfClass
 * @param {string} feedSelector
 * @param {string|null} addToString
 * @param $appendElement
 * @returns {null|string|any}
 */
function addExtraHtmlInPlace(selfClass, feedSelector, addToString = null, $appendElement = null) {

  if (selfClass.cthis.find(feedSelector).length) {
    if (addToString !== null) {

      addToString += selfClass.cthis.find(feedSelector).eq(0).html();
    }

    if ($appendElement !== null) {

      $appendElement.append(selfClass.cthis.find(feedSelector).eq(0).html())
      return $appendElement;
    }
    selfClass.cthis.find(feedSelector).remove();
    if (addToString !== null) {

      return addToString;
    }
  }


  if (typeof addToString == 'string') {
    return '';
  }

  return null;
}
