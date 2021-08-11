
import * as helpersDZSVG from '../js_dzsvg/_dzsvg_helpers';

/**
 *
 * @param {string} initialVal
 */
const ads_sanitizeForSource = (initialVal) => {
  var fout = initialVal;
  fout = fout.replace(/{{doublequot_fordzsvgad}}/g, '"');
  fout = fout.replace(/&lt;/g, '<');
  fout = fout.replace(/&gt;/g, '>');

  return fout;
}

export const ads_view_setupMarkersOnScrub = (selfClass) => {

  for (var i2 = 0; i2 < selfClass.ad_array.length; i2++) {
    selfClass.cthis.find('.scrubbar .scrub').eq(0).before('<span class="reclam-marker" style="left: ' + (selfClass.ad_array[i2].time * 100) + '%"></span>');
  }
}
export const ads_decode_ads_array = (selfClass) => {


  // -- decode after parsing json
  for (var lab in selfClass.ad_array) {
    selfClass.ad_array[lab].source = ads_sanitizeForSource(selfClass.ad_array[lab].source)
  }

}

export const player_setupAd = (selfClass, arg, pargs) => {


  var margs = {
    'called_from': 'default'

  }

  if (pargs) {
    margs = Object.assign(margs, pargs);
  }

  var o = selfClass.initOptions;

  const {source,type,skip_delay,ad_link} = selfClass.ad_array[arg];

  var ad_time = selfClass.ad_array[arg].time;


  selfClass.ad_array.splice(arg, 1);

  selfClass.cthis.appendOnce('<div class="ad-container"></div>');

  selfClass.$adContainer = selfClass.cthis.find('.ad-container').eq(0);


  var stringVplayerAdStructure = '<div class="vplayer-tobe"';

  if (type !== 'inline') {
    stringVplayerAdStructure += ' data-sourcevp="' + source + '"';
  }
  if (type) {
    stringVplayerAdStructure += ' data-type="' + type + '"';
  }
  if (ad_link) {
    stringVplayerAdStructure += ' data-adlink="' + ad_link + '"';
  }
  if (skip_delay) {
    stringVplayerAdStructure += ' data-adskip_delay="' + skip_delay + '"';
  }


  stringVplayerAdStructure += '>';


  if (type === 'inline') {
    stringVplayerAdStructure += '<div class="feed-dzsvg--inline-content">'+source+'</div>';
  }


  stringVplayerAdStructure += '</div>';
  selfClass.$adContainer.show();
  selfClass.$adContainer.append(stringVplayerAdStructure);


  var argsForVideoPlayerAd = {};


  argsForVideoPlayerAd.design_skin = o.design_skin;
  argsForVideoPlayerAd.cueVideo = 'on';
  argsForVideoPlayerAd.is_ad = 'on';
  argsForVideoPlayerAd.parent_player = selfClass.cthis;
  argsForVideoPlayerAd.user_action = o.user_action;
  selfClass.isAdPlaying = true;

  argsForVideoPlayerAd.autoplay = 'on';



  if (ad_time < 0.1 && helpersDZSVG.is_mobile()) {
    // this is invisible

    selfClass.$adContainer.children('.vplayer-tobe').addClass('mobile-pretime-ad');
    selfClass.cthis.addClass('pretime-ad-setuped');
    if (o.gallery_object) {
      o.gallery_object.addClass('pretime-ad-setuped');
    }
  }



  selfClass.$adContainer.children('.vplayer-tobe').addClass('is-ad');
  selfClass.$adContainer.children('.vplayer-tobe').vPlayer(argsForVideoPlayerAd);


  if (o.gallery_object) {
    if (o.gallery_object.get(0) && o.gallery_object.get(0).api_ad_block_navigation) {
      o.gallery_object.get(0).api_ad_block_navigation();
    }
  }


  setTimeout(function () {

    selfClass.cthis.addClass('ad-playing');
  }, 100);
  selfClass.isAdPlaying = true;
  selfClass.pauseMovie({
    'called_from': 'ad_setup'
  });
}

export const player_setup_skipad = (selfClass) => () => {


  var translate_skipad = 'Skip Ad';
  var dzsvg_translate_youcanskipto = 'you can skip to video in ';

  if (window.dzsvg_translate_youcanskipto) {
    dzsvg_translate_youcanskipto = window.dzsvg_translate_youcanskipto;
  }
  if (window.dzsvg_translate_skipad) {
    translate_skipad = window.dzsvg_translate_skipad;
  }

  let inter_time_counter_skipad = null;
  let time_counter_skipad = null;

  let $ = jQuery;

  if (selfClass.ad_status === 'first_played') {
    return false;
  }

  if (selfClass.isAd) {
    let skipad_timer = 0;

    if (selfClass.dataType === 'image' || selfClass.dataType === 'inline') {
      skipad_timer = 0;
    }
    if (selfClass.dataType === 'selfHosted' || selfClass.dataType === 'youtube') {
      skipad_timer = 1001;
    }

    selfClass.cthis.appendOnce('<div class="skipad-con"></div>', '.skipad-con');
    selfClass.$adSkipCon = selfClass.cthis.find('.skipad-con').eq(0);



    if(helpersDZSVG.is_mobile() && selfClass.cthis.attr('data-adskip_delay')){

      // -- TBC - skip ad notice on mobile
      selfClass.$adSkipCon.html("Play the ad for the skip ad counter to appear");
      selfClass.$adSkipCon.attr('data-ad-status', 'waiting_for_play');
    }

    if (typeof selfClass.cthis.attr('data-adskip_delay') != 'undefined') {
      skipad_timer = Number(selfClass.cthis.attr('data-adskip_delay'));
    }

    time_counter_skipad = skipad_timer;
    if (skipad_timer !== 1001) {
      setTimeout(function () {

        time_counter_skipad = 0;
        selfClass.$adSkipCon.html(translate_skipad);
        selfClass.$adSkipCon.bind('click', function () {
          if ($(this).attr('data-ad-status') === 'can_be_clicked_for_end_ad') {
            selfClass.handleVideoEnd();
          }
        })
        selfClass.$adSkipCon.attr('data-ad-status', 'can_be_clicked_for_end_ad');
      }, skipad_timer * 1000);

      if (skipad_timer > 0) {
        inter_time_counter_skipad = setInterval(tick_counter_skipad, 1000);
      }
    }
  }


  selfClass.ad_status = 'first_played';


  function tick_counter_skipad() {

    if (time_counter_skipad > 0) {
      time_counter_skipad = time_counter_skipad - 1;
      if (selfClass.$adSkipCon) {
        selfClass.$adSkipCon.html(dzsvg_translate_youcanskipto + time_counter_skipad);
        selfClass.$adSkipCon.attr('data-ad-status', 'ad_status_is_ticking');
      }
    } else {
      clearInterval(inter_time_counter_skipad);
    }
  }
}


