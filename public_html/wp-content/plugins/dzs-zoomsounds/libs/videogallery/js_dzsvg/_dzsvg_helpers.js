import {ads_decode_ads_array} from "../js_player/_player_setupAd";


import {constants as ConstantsDzsvg} from '../configs/Constants';

function version_firefox() {
  if (/Firefox[\/\s](\d+\.\d+)/.test(navigator.userAgent)) {
    var aversion = Number(RegExp.$1);
    return (aversion);
  }
  ;
}
;

if(!window.multisharer_markup){

  window.multisharer_markup = 'include multisharer content';
}

export function get_query_arg(purl, key) {
  if (purl.indexOf(key + '=') > -1) {

    var regexS = "[?&]" + key + "=.+";
    var regex = new RegExp(regexS);
    var regtest = regex.exec(purl);


    if (regtest != null) {
      var splitterS = regtest[0];
      if (splitterS.indexOf('&') > -1) {
        var aux = splitterS.split('&');
        splitterS = aux[1];
      }

      var splitter = splitterS.split('=');



      return splitter[1];

    }

  }
}

export function can_translate() {
  if (is_chrome() || is_safari()) {
    return true;
  }
  if (is_firefox() && version_firefox() > 10) {
    return true;
  }
  return false;
}


export function is_safari() {
  return navigator.userAgent.toLowerCase().indexOf('safari') > -1;
}
;

export function player_setQualityLevels  (selfClass) {

  var $temp_qualitiesFromFeed = selfClass.cthis.find('.dzsvg-feed-quality');
  if ($temp_qualitiesFromFeed.length) {
    selfClass.cthis.addClass('has-multiple-quality-levels');


    var $qualitySelector = selfClass.cthis.find('.quality-selector');
    var str_qualitiesTooltip = $qualitySelector.find('.dzsvg-tooltip').html();

    var aux_opts = '';

    var added = false;


    var curr_qual_added = false;
    $temp_qualitiesFromFeed.each(function () {
      var _t2 = $(this);


      aux_opts += '<div class="quality-option';



      if (_t2.attr('data-source') === selfClass.dataSrc) {
        aux_opts += ' active';
        added = true;
      }


      aux_opts += '" data-val="' + _t2.attr('data-label') + '" data-source="' + selfClass.dataSrc + '">' + _t2.attr('data-label') + '</div>';
    })

    if (added === false) {

      aux_opts += '<div class="quality-option active ';


      aux_opts += '" data-val="' + selfClass.initOptions.settings_currQuality + '" data-source="' + selfClass.dataSrc + '">' + selfClass.initOptions.settings_currQuality + '</div>';

    }



    if (str_qualitiesTooltip) {


      str_qualitiesTooltip = str_qualitiesTooltip.replace('{{quality-options}}', aux_opts);
      $qualitySelector.find('.dzsvg-tooltip').html(str_qualitiesTooltip);
    } else {
      console.warn('no aux ? ', str_qualitiesTooltip, $qualitySelector);
    }


  }
}


export function is_android() {


  var ua = navigator.userAgent.toLowerCase();
  return (ua.indexOf("android") > -1);
}

export function is_touch_device() {

  return !!('ontouchstart' in window);
}

export function can_history_api() {
  return !!(window.history && history.pushState);
}

export function add_query_arg(purl, key, value) {
  key = encodeURIComponent(key);
  value = encodeURIComponent(value);

  var s = purl;
  var pair = key + "=" + value;

  var r = new RegExp("(&|\\?)" + key + "=[^\&]*");

  s = s.replace(r, "$1" + pair);

  if (s.indexOf(key + '=') > -1) {


  } else {
    if (s.indexOf('?') > -1) {
      s += '&' + pair;
    } else {
      s += '?' + pair;
    }
  }




  if (value === 'NaN') {
    var regex_attr = new RegExp('[\?|\&]' + key + '=' + value);
    s = s.replace(regex_attr, '');
  }

  return s;
}


export function is_ios() {

  return ((navigator.platform.indexOf("iPhone") !== -1) || (navigator.platform.indexOf("iPod") !== -1) || (navigator.platform.indexOf("iPad") !== -1 || (navigator.platform.indexOf("MacIntel") !== -1 && is_touch_device()))
  );
}

export function fullscreen_status() {
  if (document.fullscreenElement !== null && typeof document.fullscreenElement !== "undefined") {
    return 1;
  } else if (document.webkitFullscreenElement && typeof document.webkitFullscreenElement !== "undefined") {
    return 1;
  } else if (document.mozFullScreenElement && typeof document.mozFullScreenElement !== "undefined") {
    return 1;
  } ;
  return 0
}


function is_chrome() {
  return navigator.userAgent.toLowerCase().indexOf('chrome') > -1;
}
;
const helpersSvg = require('./_dzsvg_svgs');
export function player_controls_generatePlayCon(o) {

  var structPlayControls = '';
  structPlayControls = '<div class="playSimple dzsvgColorForFills">';
  if (o.design_skin == 'skin_bigplay_pro') {

    structPlayControls += helpersSvg.svg_play_simple_skin_bigplay_pro;
  }
  if (o.design_skin == 'skin_aurora' || o.design_skin == 'skin_bigplay' || o.design_skin == 'skin_avanti' || o.design_skin == 'skin_default' || o.design_skin == 'skin_pro' || o.design_skin == 'skin_white') {
    structPlayControls += helpersSvg.svg_aurora_play_btn;
  }


  structPlayControls += '</div><div class="pauseSimple dzsvgColorForFills">';
  if (o.design_skin == 'skin_aurora' || o.design_skin == 'skin_pro' || o.design_skin == 'skin_bigplay' || o.design_skin == 'skin_avanti' || o.design_skin == 'skin_default' || o.design_skin == 'skin_white') {

    structPlayControls += helpersSvg.svg_pause_simple_skin_aurora;
  }
  structPlayControls += '</div>';


  structPlayControls += '<div class="dzsvg-player--replay-btn dzsvgColorForFills">';
  structPlayControls += helpersSvg.svgReplayIcon;

  structPlayControls += '</div>';


  return structPlayControls;
}
export function dzsvg_call_video_when_ready (o, _videoElement, init_readyVideo, vimeo_is_ready, inter_videoReadyState) {





  if (o.type === 'youtube' && _videoElement.getPlayerState) {

    init_readyVideo();
  }




  if (is_firefox() && o.cueVideo != 'on' && (o.type == 'selfHosted' || o.type == 'audio') && Number(_videoElement.readyState) >= 2) {
    init_readyVideo({
      'called_from': 'check_videoReadyState'
    });
    return false;
  }


  if (o.type == 'vimeo' && o.vimeo_is_chromeless == 'on') {

    if (vimeo_is_ready) {
      init_readyVideo();
      return false;
    }
  }
  if (o.type == 'audio') {
    if (is_mobile()) {
      if (Number(_videoElement.readyState) >= 1) {

        init_readyVideo();
        return false;
      }
    }


    if (Number(_videoElement.readyState) >= 3) {
      clearInterval(inter_videoReadyState);
      init_readyVideo({
        'called_from': 'check_videoReadyState'
      });
      return false;
    }
  }
  if (o.type === 'selfHosted') {


    if (is_ios()) {

      if (Number(_videoElement.readyState) >= 1) {

        init_readyVideo();
        return false;
      }
    }

    if (is_android()) {
      if (Number(_videoElement.readyState) >= 2) {

        init_readyVideo();
        return false;
      }
    }


    if (Number(_videoElement.readyState) >= 3 || o.preload_method == 'none') {
      clearInterval(inter_videoReadyState);
      init_readyVideo({
        'called_from': 'check_videoReadyState'
      });
      return false;
    }
  }


  // --- WORKAROUND __ for some reason ios default browser would not go over video ready state 1

  if (o.type == 'dash') {

    clearInterval(inter_videoReadyState)
    init_readyVideo({
      'called_from': 'check_videoReadyState'
    });
  }


}

export function dzsvg_click_open_embed_ultibox() {
  // -- this is used for video gallery
  // -- @triggered on clicking multisharer button in dzsvg




  var _c = jQuery('.dzsvg-main-con').eq(0);




  var _t = jQuery(this);
  var _vg = null
    , _vp = null
  ;

  console.log('_t -dzsvg_click_open_embed_ultibox()- ', _t, _t.parent().parent().parent());


  var _par_par_par = _t.parent().parent().parent();
  // -- if this is a button in video player ?
  if (_par_par_par.hasClass('vplayer')) {
    _vp = _par_par_par;
  }
  // -- if this is a button in video gallery ?
  if (_par_par_par.hasClass('videogallery')) {
    _vg = _par_par_par;
  }

  var aux = '';

  if (window.dzsvg_social_feed_for_social_networks) {
    aux = window.dzsvg_social_feed_for_social_networks;
  }


  aux = aux.replace(/&quot;/g, '\'');
  aux = aux.replace('onclick=""', '');

  _c.find('.social-networks-con').html(aux);


  aux = '';


  if (window.dzsvg_social_feed_for_share_link) {
    aux = window.dzsvg_social_feed_for_share_link;
  }


  if (aux) {

    aux = aux.replace('{{replacewithcurrurl}}', window.location.href);
    _c.find('.share-link-con').html(aux);
  }

  aux = '';
  if (window.dzsvg_social_feed_for_embed_link) {
    aux = window.dzsvg_social_feed_for_embed_link;
  }





  // -- for single video player
  if ((_vp || _vg) && aux) {

    if (_vp && _vp.data('embed_code')) {
      jQuery('.embed-link-con').show();
      aux = aux.replace('{{replacewithembedcode}}', htmlEntities(_vp.data('embed_code')));


      _c.find('.embed-link-con').html(aux);
    } else {


      if (_vg && aux) {

        var iframe_code = '<div style="width: 100%; padding-top: 67.5%; position: relative;"><iframe src=\'' + dzsvg_settings.dzsvg_site_url + '?action=embed_dzsvg&type=' + 'gallery' + '&id=' + _vg.attr('data-dzsvg-gallery-id') + '\'  width="100%" style="position:absolute; top:0; left:0; width: 100%; height: 100%;" scrolling="no" frameborder="0" allowfullscreen allow></iframe>';

        var encodedStr = String(iframe_code).replace(/[\u00A0-\u9999<>\&]/gim, function (i) {
          return '&#' + i.charCodeAt(0) + ';';
        });
        aux = aux.replace('{{replacewithembedcode}}', encodedStr);

        // -- inspired from php @generate_embed_code
        _c.find('.embed-link-con').html(aux);
      } else {

        // -- hide embed link if we do not have embed_code
        jQuery('.embed-link-con').hide();
      }
    }
  }


  jQuery(document).on('click.dzsvg', '.field-for-view', function () {




    jQuery(this).select();
  });
  setTimeout(function () {

    _c.addClass('loading-item');
  }, 100);

  setTimeout(function () {
    _c.addClass('loaded-item');
  }, 200);
}


export function get_base_url_arr() {
  var scripts = document.getElementsByTagName("script");
  var scriptKey = '';
  for (scriptKey in scripts) {
    if (scripts[scriptKey] && scripts[scriptKey].src && String(scripts[scriptKey].src).indexOf('vplayer.js') > -1) {
      break;
    }
  }
  var baseUrl_arr = String(scripts[scriptKey].src).split('/');
  return baseUrl_arr;
}


export function dzsvg_check_multisharer() {



  setTimeout(function () {


    jQuery('body').append(multisharer_markup);


  }, 1000);


  jQuery(document).on('click.dzsvg', '.dzsvg-main-con .close-btn-con,.dzsvg-main-con .overlay-background', function () {

    var _c = jQuery('.dzsvg-main-con').eq(0);

    _c.removeClass('loading-item loaded-item');
  })

  jQuery(document).on('click.dzsvg', '.dzsvg-multisharer-but', dzsvg_click_open_embed_ultibox);
  jQuery(document).on('click.dzsvg', '.dzsvg-main-con .close-btn-con,.dzsvg-main-con .overlay-background', function () {

    var _c = jQuery('.dzsvg-main-con').eq(0);

    _c.removeClass('loading-item loaded-item');
  })


}


export function is_mobile() {

  return is_ios() || is_android();
}

function is_firefox() {
  if (navigator.userAgent.indexOf("Firefox") !== -1) {
    return true;
  }
  ;
  return false;
}
;

export function detect_startItemBasedOnQueryAddress (deeplinkGotoItemQueryParam = '', cid = '') {

  if (this.get_query_arg(window.location.href, deeplinkGotoItemQueryParam) && jQuery('.videogallery').length == 1) {
    return Number(this.get_query_arg(window.location.href, deeplinkGotoItemQueryParam)) - 1;
  }
  if (this.get_query_arg(window.location.href, deeplinkGotoItemQueryParam + '-' + cid)) {
    return Number(this.get_query_arg(window.location.href, deeplinkGotoItemQueryParam + '-' + cid)) - 1;

  }

  return null;
}
export function sanitize_to_youtube_id  (arg = '') {
  var fourArr = null;

  if (arg) {
    arg = this.detect_video_type_and_source(arg).source;
  }
  return arg;
}

/**
 *
 * @param _c the video player element
 * @param attr attribute
 * @returns {null|jQuery|undefined|*}
 */
export function getDataOrAttr(_c, attr) {
  if (_c.data && typeof _c.data(attr) != 'undefined') {
    return _c.data(attr);
  }
  if (_c.attr && typeof _c.attr(attr) != 'undefined') {
    return _c.attr(attr);
  }

  return null;
}

export function detect_videoTypeAndSourceForElement (_el) {

  if (_el.data('originalPlayerAttributes')) {
    return _el.data('originalPlayerAttributes');
  }

  var dataSrc = this.getDataOrAttr(_el, 'data-sourcevp');

  var forceType = this.getDataOrAttr(_el, 'data-type') ? this.getDataOrAttr(_el, 'data-type') : '';

  return this.detect_video_type_and_source(dataSrc, forceType)
}
/**
 * detect video type and source
 * @param {string} dataSrc
 * @param forceType we might want to force the type if we know it
 * @returns {{source: *, playFrom: null, type: string}}
 */
export function detect_video_type_and_source (dataSrc, forceType = null, cthis = null) {




  dataSrc = String(dataSrc);

  var playFrom = null;
  var type = 'selfHosted';
  var source = dataSrc;

  if (dataSrc.indexOf('youtube.com/watch?') > -1 || dataSrc.indexOf('youtube.com/embed') > -1 || dataSrc.indexOf('youtu.be/') > -1) {
    type = 'youtube';

    var aux = /http(?:s?):\/\/(?:www\.)?youtu(?:be\.com\/watch\?v=|\.be\/)([\w\-\_]*)(&(amp;)?‌​[\w\?‌​=]*)?/g.exec(dataSrc);


    if (get_query_arg(dataSrc, 't')) {
      playFrom = get_query_arg(dataSrc, 't');
    }
    if (aux && aux[1]) {
      source = aux[1];
    } else {
      // -- let us try youtube embed
      source = dataSrc.replace(/http(?:s?):\/\/(?:www\.)?youtu(?:be\.com\/watch\?v=|\.be\/|be\.com)\/embed\//g, '');
    }
  }


  if (dataSrc.indexOf('<iframe') > -1 ) {
    type = 'inline';
  }
  // todo: php turn into content
  if(cthis && cthis.find('.feed-dzsvg--inline-content').length && cthis.find('.feed-dzsvg--inline-content').eq(0).html().indexOf('<iframe') > -1) {
    type = 'inline';
  }
  if (dataSrc.indexOf('vimeo.com/') > -1) {
    type = 'vimeo';

    var aux = /(?:www\.|player\.)?vimeo.com\/(?:channels\/(?:\w+\/)?|groups\/(?:[^\/]*)\/videos\/|album\/(?:\d+)\/video\/|video\/|)(\d+)(?:[a-zA-Z0-9_\-]+)?/g.exec(dataSrc);


    if (aux && aux[1]) {
      source = aux[1];
    }
  }

  if (dataSrc.indexOf('.mp4') > -1) {
    type = 'selfHosted';

  }
  if (dataSrc && dataSrc.indexOf('.mpd') > dataSrc.length - 5) {
    type = 'dash';
  }
  if (forceType && forceType !== 'detect') {
    type = forceType;
  }


  if (!playFrom) {

    if (cthis && cthis.attr('data-play_from')) {
      playFrom = cthis.attr('data-play_from');
    }
  }
  return {
    type,
    source,
    playFrom
  };
}
export function sanitizeDataAdArrayStringToArray  (aux) {

  var ad_array = null;
  try {
    // temp - try to remove slashes manually
    aux = aux.replace(/{\\"/g, '{"');
    aux = aux.replace(/\\":/g, '":');
    aux = aux.replace(/:\\"/g, ':"');
    aux = aux.replace(/\\",/g, '",');
    aux = aux.replace(/\\"}/g, '"}');
    aux = aux.replace(/,\\"/g, ',"');
    ad_array = JSON.parse(aux);
  } catch (err) {
    console.log('ad array parse error', aux);
  }

  return ad_array;


}

export function is_autoplay_and_muted  (autoplay, o) {

  return ((1) && autoplay == 'on' && o.autoplayWithVideoMuted == 'on' && o.user_action == 'noUserActionYet') || (o.defaultvolume == 0 && o.defaultvolume !== '');

}


export function setup_videogalleryCategories (arg) {
  var ccat = jQuery(arg);
  var currCatNr = -1;

  ccat.find('.gallery-precon').each(function () {
    var _t = jQuery(this);

    _t.css({'display': 'none'});
    ccat.find('.the-categories-con').append('<span class="a-category">' + _t.attr('data-category') + '</span>')
  });

  ccat.find('.the-categories-con').find('.a-category').eq(0).addClass('active');
  ccat.find('.the-categories-con').find('.a-category').bind('click', click_category);

  function click_category() {
    var _t = jQuery(this);
    var ind = _t.parent().children('.a-category').index(_t);


    gotoCategory(ind);
    setTimeout(function () {
      jQuery(window).trigger('resize');
    }, 100);
  }



  var i2 = 0;

  ccat.find('.gallery-precon').each(function () {
    var _t = jQuery(this);



    _t.find('.pagination-number').each(function () {
      var _t2 = jQuery(this);


      var auxurl = _t2.attr('href');



      auxurl = add_query_arg(auxurl, ccat.attr('id') + '_cat', NaN);



      auxurl = add_query_arg(auxurl, ccat.attr('id') + '_cat', i2);

      _t2.attr('href', auxurl);
    })

    i2++;
  })

  var tempCat = 0;



  if (get_query_arg(window.location.href, ccat.attr('id') + '_cat')) {
    tempCat = Number(get_query_arg(window.location.href, ccat.attr('id') + '_cat'));
  }


  ccat.get(0).api_goto_category = gotoCategory;

  gotoCategory(tempCat, {
    'called_from': 'init'
  });

  function gotoCategory(arg, pargs) {


    var margs = {
      'called_from': 'default'
    };


    if (pargs) {
      margs = jQuery.extend(margs, pargs);
    }



    if (currCatNr > -1 && ccat.find('.gallery-precon').eq(currCatNr).find('.videogallery').eq(0).get(0) != undefined && ccat.find('.gallery-precon').eq(currCatNr).find('.videogallery').eq(0).get(0).external_handle_stopCurrVideo != undefined) {


      var ind = 0;
      ccat.find('.gallery-precon').each(function () {



        if (ind != arg) {

          jQuery(this).find('.videogallery').eq(0).get(0).external_handle_stopCurrVideo();
        }
        ind++;
      })


    }
    ccat.find('.gallery-precon').removeClass('curr-gallery');
    ccat.find('.the-categories-con').find('.a-category').removeClass('active');
    ccat.find('.the-categories-con').find('.a-category').eq(arg).addClass('active');
    ccat.find('.gallery-precon').addClass('disabled');
    ccat.find('.gallery-precon').eq(arg).css('display', '').removeClass('disabled');

    var _cach = ccat.find('.gallery-precon').eq(arg);
    var _cachg = _cach.find('.videogallery').eq(0);




    if (_cachg.get(0) && _cachg.get(0).init_settings) {



      if (_cachg.get(0).init_settings.autoplay == 'on') {
        setTimeout(function () {

          _cachg.get(0).api_play_currVideo();
        }, 10);

        if (margs.called_from == 'deeplink' || margs.called_from == 'init') {

          setTimeout(function () {


          }, 1000);
          setTimeout(function () {

            _cachg.get(0).api_play_currVideo();
          }, 1500);
        }
      }
    }

    setTimeout(function () {
      ccat.children('.dzsas-second-con').hide();
      ccat.children('.dzsas-second-con').eq(arg).show();




      ccat.find('.gallery-precon').eq(arg).addClass('curr-gallery');


      currCatNr = arg;


      if (typeof ccat.find('.gallery-precon').eq(arg).find('.videogallery').eq(0).get(0) != 'undefined' && typeof ccat.find('.gallery-precon').eq(arg).find('.videogallery').eq(0).get(0).api_handleResize != 'undefined') {
        ccat.find('.gallery-precon').eq(arg).find('.videogallery').eq(0).get(0).api_handleResize();
        ccat.find('.gallery-precon').eq(arg).find('.videogallery').eq(0).get(0).api_handleResize_currVideo();
      }
      setTimeout(function () {

        jQuery(window).trigger('resize');
      }, 1500);


    }, 50);


  }

}
export function youtube_sanitize_url_to_id  (arg) {


  if (arg) {

    if (String(arg).indexOf('youtube.com/embed') > -1) {
      var auxa = String(dataSrc).split('youtube.com/embed/');


      if (auxa[1]) {

        return auxa[1];
      }
    }

    if (arg.indexOf('youtube.com') > -1 || arg.indexOf('youtu.be') > -1) {


      if (get_query_arg(arg, 'v')) {
        return get_query_arg(arg, 'v');
      }

      if (arg.indexOf('youtu.be') > -1) {
        var arr = arg.split('/');

        arg = arr[arr.length - 1];
      }
    }
  }


  return arg;
}

export function htmlEntities  (str) {
  return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
}

export function registerAuxjQueryExtends ($) {


  Math.easeIn = function (t, b, c, d) {

    return -c * (t /= d) * (t - 2) + b;

  };
  Math.easeOut = function (t, b, c, d) {
    t /= d;
    return -c * t * (t - 2) + b;
  };


  $.fn.prependOnce = function (arg, argfind) {
    var _t = $(this) // It's your element



    if (typeof (argfind) == 'undefined') {
      var regex = new RegExp('class="(.*?)"');
      var auxarr = regex.exec(arg);


      if (typeof auxarr[1] != 'undefined') {
        argfind = '.' + auxarr[1];
      }
    }


    // we compromise chaining for returning the success
    if (_t.children(argfind).length < 1) {
      _t.prepend(arg);
      return true;
    } else {
      return false;
    }
  };
  $.fn.appendOnce = function (arg, argfind) {
    var _t = $(this) // It's your element


    if (typeof (argfind) == 'undefined') {
      var regex = new RegExp('class="(.*?)"');
      var auxarr = regex.exec(arg);


      if (typeof auxarr[1] != 'undefined') {
        argfind = '.' + auxarr[1];
      }
    }

    if (_t.children(argfind).length < 1) {
      _t.append(arg);
      return true;
    } else {
      return false;
    }
  };




  var d = new Date();
  window.dzsvg_time_started = d.getTime();


  var inter_check_treat = 0;

  clearTimeout(inter_check_treat);
  inter_check_treat = setTimeout(workaround_treatuntretreadItems, 2000);

  function workaround_treatuntretreadItems() {
    jQuery('.js-api-player:not(.treated)').each(function () {
      var _t = jQuery(this);
      var $ytApiPlayer_ = _t.get(0);


      var playerId = _t.attr('id');

      var aux = playerId.substr(8);
      var aux2 = _t.attr('data-suggestedquality');


      if (typeof $ytApiPlayer_.loadVideoById != 'undefined') {
        $ytApiPlayer_.loadVideoById(aux, 0, aux2);
        $ytApiPlayer_.pauseVideo();
      } else {

        inter_check_treat = setTimeout(workaround_treatuntretreadItems, 2000);
      }


    })

  }

  // -- we save the other youtube player ready functions ( maybe conflict with other players )
  if (window.onYouTubePlayerReady && typeof window.onYouTubePlayerReady == 'function' && typeof backup_onYouTubePlayerReady == 'undefined') {
    window.dzsvg_backup_onYouTubePlayerReady = window.onYouTubePlayerReady;
  }
}

export function dzsvgExtraWindowFunctions  () {


  window.dzsvg_wp_send_view = function (argcthis, argtitle) {



    var data = {
      video_title: argtitle
      , video_analytics_id: argcthis.attr('data-player-id')
    };

    if (window.dzsvg_curr_user) {
      data.dzsvg_curr_user = window.dzsvg_curr_user;
    }

    var theajaxurl = 'index.php?action=ajax_dzsvg_submit_view';

    if (window.dzsvg_site_url) {
      theajaxurl = dzsvg_settings.dzsvg_site_url + theajaxurl;
    }


    jQuery.ajax({
      type: "POST",
      url: theajaxurl,
      data: data,
      success: function (response) {
        if (typeof window.console != "undefined") {
          console.log('Ajax - submit view - ' + response);
        }


      },
      error: function (arg) {
        if (typeof window.console != "undefined") {
          console.warn('Got this from the server: ' + arg);
        }
        ;
      }
    });


  }


  window.dzsvg_wp_send_contor_60_secs = function (argcthis, argtitle) {

    var data = {
      video_title: argtitle

      , video_analytics_id: argcthis.attr('data-player-id')
      , dzsvg_curr_user: window.dzsvg_curr_user
    };
    var theajaxurl = 'index.php?action=ajax_dzsvg_submit_contor_60_secs';

    if (window.dzsvg_site_url) {

      theajaxurl = dzsvg_settings.dzsvg_site_url + theajaxurl;
    }


    jQuery.ajax({
      type: "POST",
      url: theajaxurl,
      data: data,
      success: function (response) {
        if (typeof window.console != "undefined") {
          console.log('Ajax - submit view - ' + response);
        }


      },
      error: function (arg) {
        if (typeof window.console != "undefined") {
          console.warn('Got this from the server: ' + arg);
        }
        ;
      }
    });
  }


  window.dzsvg_open_social_link = function (arg) {
    var leftPosition, topPosition;
    var w = 500, h = 500;



    arg = arg.replace(/{{replacewithcurrurl}}/g, encodeURIComponent(window.location.href));
    console.warn('arg - ', arg);
    //Allow for borders.
    leftPosition = (window.screen.width / 2) - ((w / 2) + 10);
    //Allow for title and status bars.
    topPosition = (window.screen.height / 2) - ((h / 2) + 50);
    var windowFeatures = "status=no,height=" + h + ",width=" + w + ",resizable=yes,left=" + leftPosition + ",top=" + topPosition + ",screenX=" + leftPosition + ",screenY=" + topPosition + ",toolbar=no,menubar=no,scrollbars=no,location=no,directories=no";
    window.open(arg, "sharer", windowFeatures);
  }


  window.dzsvp_yt_iframe_ready = function () {

    _global_youtubeIframeAPIReady = true;
  }

  window.onYouTubeIframeAPIReady = function () {
    window.dzsvp_yt_iframe_ready();
  }


  window.requestAnimFrame = (function () {
    return window.requestAnimationFrame ||
      window.webkitRequestAnimationFrame ||
      window.mozRequestAnimationFrame ||
      function (callback) {
        window.setTimeout(callback, 1000 / 60);
      };
  })();

}


export function extractOptionsFromPlayer  ($c) {


  if ($c.data('originalPlayerAttributes')) {
    return $c.data('originalPlayerAttributes');
  }
  var finalOptions = {};


  if (getDataOrAttr($c, 'data-sourcevp')) {
    finalOptions.source = getDataOrAttr($c, 'data-sourcevp');
  }
  if ($c.attr('data-type')) {
    finalOptions.type = $c.attr('data-type');
  }

  return finalOptions;
}


export function convertPluginOptionsToFinalOptions  (elThis, defaultOptions, argOptions = null, searchedAttr = 'data-options', searchedDivClass = 'feed-options') {

  var finalOptions = null;
  var tempOptions = {};
  var sw_setFromJson = false;
  var _elThis = jQuery(elThis);

  if (argOptions && typeof argOptions == 'object') {
    tempOptions = argOptions;
  } else {

    if (_elThis.attr(searchedAttr)) {
      try {
        tempOptions = JSON.parse(_elThis.attr(searchedAttr));
        sw_setFromJson = true;
      } catch (err) {
        console.log('json parse error searched attr err - ', err, _elThis.attr(searchedAttr));
      }
    } else {

      if (_elThis.find('.feed-options').length) {
        try {
          tempOptions = JSON.parse(_elThis.find('.feed-options').html());
          sw_setFromJson = true;
        } catch (err) {
          console.log('json parse error feed-options err - ', err, _elThis.find('.feed-options').html());
        }
      }
    }
    if (!sw_setFromJson) {

      // -- *deprecated
      if (typeof argOptions == 'undefined' || !argOptions) {
        if (typeof _elThis.attr(searchedAttr) != 'undefined' && _elThis.attr(searchedAttr) !== '') {
          var aux = _elThis.attr(searchedAttr);
          aux = 'var aux_opts = ' + aux;
          eval(aux);
          tempOptions = Object.assign({}, aux_opts);
        }
      }
    }
  }

  finalOptions = Object.assign(defaultOptions, tempOptions);

  return finalOptions;
}

export function player_setupQualitySelector  (selfClass, yt_qualCurr, yt_qualArray) {
  var _qualitySelector = selfClass.cthis.find('.quality-selector');
  if (_qualitySelector.find('.dzsvg-tooltip').length) {

    var aux = _qualitySelector.find('.dzsvg-tooltip').html();
    var aux_opts = '';

    for (var i2 in yt_qualArray) {
      aux_opts += '<div class="quality-option';
      if (yt_qualCurr === yt_qualArray[i2]) {
        aux_opts += ' active';
      }
      aux_opts += '" data-val="' + yt_qualArray[i2] + '">' + yt_qualArray[i2] + '</div>';
    }
    aux = aux.replace('{{quality-options}}', aux_opts);
    _qualitySelector.find('.dzsvg-tooltip').html(aux);
  }

}
export function playerHandleDeprecatedAttrSrc  (cthis) {


  if (!cthis.attr('data-sourcevp')) {
    if (cthis.attr('data-source')) {
      cthis.attr('data-sourcevp', cthis.attr('data-source'));
    } else {

      if (cthis.attr('data-src')) {
        cthis.attr('data-sourcevp', cthis.attr('data-src'));

      }
    }
  }
}

export function player_assert_autoplay  (selfClass) {


  // -- autoplay assert

  var o = selfClass.initOptions;



  if (is_mobile()) {

  }




}
export function configureAudioPlayerOptionsInitial (cthis, o, selfClass) {



  if (o.gallery_object != null) {
    if (typeof (o.gallery_object.get(0)) != 'undefined') {
      selfClass.$parentGallery = o.gallery_object;


      setTimeout(function () {
        if (selfClass.$parentGallery.get(0).api_video_ready) {
          selfClass.$parentGallery.get(0).api_video_ready();
        }
      }, ConstantsDzsvg.DELAY_MINUSCULE);
    }
  }


  if (is_mobile() || (o.first_video_from_gallery === 'on' && (is_safari()))) {
    if (is_mobile()) {
      cthis.addClass('is-mobile');
    }
    if (cthis.attr('data-img')) {
    } else {
      cthis.removeClass('hide-on-paused');
    }
  }









  if (o.playfrom === 'default') {
    if (typeof selfClass.cthis.attr('data-playfrom') != 'undefined' && selfClass.cthis.attr('data-playfrom') != '') {
      o.playfrom = selfClass.cthis.attr('data-playfrom');
    }
  }
  if (isNaN(Number(o.playfrom)) == false) {
    o.playfrom = Number(o.playfrom);
  }
  if (isNaN(Number(o.sliderAreaHeight)) == false) {
    o.sliderAreaHeight = Number(o.sliderAreaHeight);
  }


  cthis.data('embed_code', o.embed_code);


  selfClass.videoWidth = cthis.width();
  selfClass.videoHeight = cthis.height();



  if (o.autoplay === 'on') {
    selfClass.autoplayVideo = 'on';
  }

  if (!selfClass.dataSrc) {
    console.log('[dzsvg] missing source', selfClass.cthis);
  }

  var mainClass = '';
  if (typeof (cthis.attr('class')) == 'string') {
    mainClass = cthis.attr('class');
  } else {
    mainClass = cthis.get(0).className;
  }

  if (mainClass.indexOf('skin_') == -1) {
    cthis.addClass(o.design_skin);
    mainClass += ' ' + o.design_skin;
  }


  cthis.addClass(o.extra_classes);

  //-setting skin specific vars
  if (mainClass.indexOf('skin_aurora') > -1) {
    o.design_skin = 'skin_aurora';
    selfClass.bufferedWidthOffset = -2;
    selfClass.volumeWidthOffset = -2;
    if (o.design_enableProgScrubBox == 'default') {
      o.design_enableProgScrubBox = 'on';
    }
    if (o.design_scrubbarWidth == 'default') {
      o.design_scrubbarWidth = -113;
    }
  }
  if (mainClass.indexOf('skin_pro') > -1) {
    o.design_skin = 'skin_pro';

    selfClass.volumeWidthOffset = -2;
    if (o.design_enableProgScrubBox == 'default') {
      o.design_enableProgScrubBox = 'off';
    }
    if (o.design_scrubbarWidth == 'default') {
      o.design_scrubbarWidth = 0;
    }
  }
  if (mainClass.indexOf('skin_bigplay') > -1) {
    o.design_skin = 'skin_bigplay';
  }
  if (mainClass.indexOf('skin_nocontrols') > -1) {
    o.design_skin = 'skin_nocontrols';
  }
  if (mainClass.indexOf('skin_bigplay_pro') > -1) {
    o.design_skin = 'skin_bigplay_pro';
  }
  if (mainClass.indexOf('skin_bluescrubtop') > -1) {
    o.design_skin = 'skin_bluescrubtop';

    if (o.design_scrubbarWidth == 'default') {
      o.design_scrubbarWidth = 0;
    }
  }
  if (mainClass.indexOf('skin_avanti') > -1) {
    o.design_skin = 'skin_avanti';

    if (o.design_scrubbarWidth == 'default') {
      o.design_scrubbarWidth = -125;
    }
  }
  if (mainClass.indexOf('skin_noskin') > -1) {
    o.design_skin = 'skin_noskin';
  }


  if (cthis.hasClass('skin_white')) {
    o.design_skin = 'skin_white';
  }
  if (cthis.hasClass('skin_reborn')) {
    o.design_skin = 'skin_reborn';
    if (o.design_scrubbarWidth === 'default') {
      o.design_scrubbarWidth = -312;

    }
  }



  if (o.design_scrubbarWidth === 'default') {
    o.design_scrubbarWidth = -201;
  }


  if (is_mobile() || is_ios()) {
    cthis.addClass('disable-volume');

    if (o.design_skin == 'skin_reborn') {
      o.design_scrubbarWidth += 65;
    }
  }


  if (o.gallery_object) {
    if (o.gallery_object.get(0)) {
      cthis.get(0).gallery_object = o.gallery_object.get(0);
    }
  }


  if (o.extra_controls) {
    cthis.append(o.extra_controls);
  }


  if (o.responsive_ratio === 'default' || (selfClass.dataType === 'youtube' && o.responsive_ratio === 'detect')) {

    if (cthis.attr('data-responsive_ratio')) {
      o.responsive_ratio = cthis.attr('data-responsive_ratio');
    }
  }

  if (o.gallery_object !== null) {
    selfClass.isPartOfAnGallery = true;
  }
  if (selfClass.isPartOfAnGallery) {
    selfClass.isGalleryHasOneVideoPlayerMode = o.gallery_object.data('vg_settings') && o.gallery_object.data('vg_settings').mode_normal_video_mode === 'one';
  }


  // -- we cache this for the one
  if (selfClass.isGalleryHasOneVideoPlayerMode) {
    if (o.gallery_target_index === 0 && !(selfClass.cthis.data('originalPlayerAttributes'))) {
      selfClass.cthis.data('originalPlayerAttributes', this.detect_videoTypeAndSourceForElement(selfClass.cthis));
    }
  }

  if (o.action_video_view === 'wpdefault') {
    o.action_video_view = window.dzsvg_wp_send_view;
  }
  if (o.action_video_contor_60secs === 'wpdefault') {
    o.action_video_contor_60secs = window.dzsvg_wp_send_contor_60_secs;
  }


  this.reinitPlayerOptions(selfClass, o);
}


export function reinitPlayerOptions (selfClass, o) {
  // -- we need  selfClass.dataType and selfClass.dataSrc beforeHand


  selfClass.hasCustomSkin = true;
  // -- assess custom skin
  if (selfClass.dataType === 'vimeo' && o.vimeo_is_chromeless !== 'on') {
    selfClass.hasCustomSkin = false;
  }
  if (selfClass.dataType === 'youtube' && o.settings_youtube_usecustomskin !== 'on') {
    selfClass.hasCustomSkin = false;
  }
  if (this.is_ios() && o.settings_ios_usecustomskin !== 'on') {
    selfClass.hasCustomSkin = false;
  }
  if(selfClass.dataType==='inline'){

    selfClass.hasCustomSkin = false;
  }




  if (selfClass.cthis.attr('data-ad-array')) {
    selfClass.ad_array = sanitizeDataAdArrayStringToArray(selfClass.cthis.attr('data-ad-array'));
  }
  ads_decode_ads_array(selfClass);


  this.player_assert_autoplay(selfClass);

  if (o.is_ad === 'on') {
    selfClass.isAd = true;
  }

  player_checkIfItShouldStartMuted(selfClass, o);

  if (selfClass.isAd && o.ad_link) {
    selfClass.ad_link = o.ad_link;
  }


  // -- assess custom skin END
}

function player_checkIfItShouldStartMuted(selfClass, o) {
  // console.log('player_checkIfItShouldStartMuted() ', selfClass.initOptions.autoplay, selfClass.initOptions.autoplayWithVideoMuted)

  const isPlayerOrGalleryHadFirstInteraction = () => {
    if (selfClass.isHadFirstInteraction) return true;
    if (!is_mobile() && selfClass.$parentGallery && selfClass.$parentGallery.hasClass('user-had-first-interaction')) {
      return true;
    }
    return false;
  };

  // -- should start muted
  if (selfClass.cthis.hasClass('start-muted')) {
    selfClass.initOptions.autoplayWithVideoMuted = 'always'; // -- warning: override
  }

  // -- detect
  if (selfClass.initOptions.autoplay === 'off') {
    selfClass.shouldStartMuted = false;

  }
  if (selfClass.initOptions.autoplay === 'on') {


    if (isPlayerOrGalleryHadFirstInteraction()) {
      selfClass.shouldStartMuted = false;
    } else {
      if (is_mobile()) {
        // -- mobile
        selfClass.shouldStartMuted = selfClass.initOptions.autoplay === 'on' && selfClass.initOptions.autoplayWithVideoMuted === 'auto';
      } else {
        // -- desktop
        if (o.autoplayWithVideoMuted === 'auto') {
          selfClass.shouldStartMuted = true;
        }
      }

    }
  }
  // -- should start muted

  if (o.autoplayWithVideoMuted === 'always') {
    selfClass.shouldStartMuted = true;
  }

}

export function tagsSetupDom  (_tagElement) {
  var auxhtml = _tagElement.html();
  var w = 100;
  var h = 100;
  var acomlink = '';
  if (_tagElement.attr('data-width') != undefined) {
    w = _tagElement.attr('data-width');
  }
  if (_tagElement.attr('data-height') != undefined) {
    h = _tagElement.attr('data-height');
  }
  if (_tagElement.attr('data-link') != undefined) {
    acomlink = '<a href="' + _tagElement.attr('data-link') + '"></a>';
  }

  _tagElement.html('');
  _tagElement.css({'left': (_tagElement.attr('data-left') + 'px'), 'top': (_tagElement.attr('data-top') + 'px')});

  _tagElement.append('<div class="tag-box" style="width:' + w + 'px; height:' + h + 'px;">' + acomlink + '</div>');
  _tagElement.append('<span class="tag-content">' + auxhtml + '</span>');
  _tagElement.removeClass('dzstag-tobe').addClass('dzstag');

}
export function pauseDzsapPlayers () {
  if (window.dzsap_list) {
    for (var i = 0; i < dzsap_list.length; i++) {


      if (typeof dzsap_list[i].get(0) != "undefined" && typeof dzsap_list[i].get(0).api_pause_media != "undefined" && dzsap_list[i].get(0) != cthis.get(0)) {


        if (dzsap_list[i].data('type_audio_stop_buffer_on_unfocus') && dzsap_list[i].data('type_audio_stop_buffer_on_unfocus') == 'on') {
          dzsap_list[i].get(0).api_destroy_for_rebuffer();
        } else {

          dzsap_list[i].get(0).api_pause_media({
            'audioapi_setlasttime': false
          });
        }
        window.dzsap_player_interrupted_by_dzsvg = dzsap_list[i].get(0);
      }
    }

  }

}
export function init_navigationOuter  () {
  jQuery('.videogallery--navigation-outer').each(function () {
    var _t = jQuery(this);


    var xpos = 0;
    _t.find('.videogallery--navigation-outer--bigblock').each(function () {
      var _t = jQuery(this);
      _t.css('left', xpos + '%');
      xpos += 100;
    })

    // console.warn('_t.attr(\'data-vgtarget\') -',_t.attr('data-vgtarget'));


    // -- we will use first gallery if id is auto
    if (_t.attr('data-vgtarget') == '.id_auto') {


      var _cach = jQuery('.videogallery,.videogallery-tobe').eq(0);
      // console.info('jQuery(\'.videogallery\').eq(0) - ',_cach.eq(0));

      var cclass = /id_(.*?) /.exec(_cach.attr('class'));

      if (cclass && cclass[1]) {
        _t.attr('data-vgtarget', '.id_' + cclass[1]);
      }

      if (_cach.get(0) && _cach.get(0).api_set_outerNav) {
        _cach.get(0).api_set_outerNav(_t);
      }
      setTimeout(function () {
        if (_cach.get(0) && _cach.get(0).api_set_outerNav) {
          _cach.get(0).api_set_outerNav(_t);
        }
      }, 1000)
    }
    var _tar = jQuery(_t.attr('data-vgtarget')).eq(0);


    var _clip = _t.find('.videogallery--navigation-outer--clip').eq(0);
    var _clipmover = _t.find('.videogallery--navigation-outer--clipmover').eq(0);

    var currPage = 0;
    var _block_active = _t.find('.videogallery--navigation-outer--bigblock.active').eq(0);
//        console.info(_tar);

    var _navOuterBullets = _t.find('.navigation-outer--bullet');
    var _navOuterBlocks = _t.find('.videogallery--navigation-outer--block');

    setTimeout(function () {
      _t.addClass('active');
      _block_active = _t.find('.videogallery--navigation-outer--bigblock.active').eq(0);
      _clip.height(_block_active.height());
    }, 500)

    _navOuterBlocks.bind('click', function () {
      var _t2 = jQuery(this);
      var ind = _navOuterBlocks.index(_t2);


//            console.info(ind);

      if (_tar.get(0) && _tar.get(0).api_gotoItem) {
        if (_tar.get(0).api_gotoItem(ind)) {
        }


        jQuery('html, body').animate({
          scrollTop: _tar.offset().top
        }, ConstantsDzsvg.ANIMATIONS_DURATION);
      }
    });

    _navOuterBullets.bind('click', function () {
      var _t2 = jQuery(this);
      var ind = _navOuterBullets.index(_t2);

      gotoPage(ind);

    })

    function gotoPage(arg) {
      var auxl = -(Number(arg) * 100) + '%';

      _navOuterBullets.removeClass('active');
      _navOuterBullets.eq(arg).addClass('active');

      _t.find('.videogallery--navigation-outer--bigblock.active').removeClass('active');
      _t.find('.videogallery--navigation-outer--bigblock').eq(arg).addClass('active');


      _clip.height(_t.find('.videogallery--navigation-outer--bigblock').eq(arg).height());

      _clipmover.css('left', auxl);

    }


  })
}
export function vimeo_do_command  (selfClass, vimeo_data, vimeo_url) {

  if (vimeo_url) {

    //console.info(vimeo_url);
    if (selfClass._videoElement && selfClass._videoElement.contentWindow && vimeo_url) {

      selfClass._videoElement.contentWindow.postMessage(JSON.stringify(vimeo_data), vimeo_url);
    }
  }
}
export function dash_setupPlayer  (selfClass) {

  var dash_player = null
    , dash_context = null
  ;

  // console.warn("Reading from source ", selfClass.dataSrc);


  function setup_dash() {

    dash_context = new Webm.di.WebmContext();
    dash_player = new MediaPlayer(dash_context);
    dash_player.startup();
    dash_player.attachView(video);

    if (selfClass.autoplayVideo == 'on') {
      dash_player.setAutoPlay(true);
    } else {
      dash_player.setAutoPlay(false);
    }
    dash_player.attachSource(selfClass.dataSrc);
  }

  if (!(selfClass && selfClass.dataSrc)) {
    console.log('[dzsvg][error] no selfclass .. no src ?? ');
    return false;
  }

  if (window.Webm) {

    setup_dash();


    //console.info(selfClass._videoElement.naturalWidth);
  } else {


    var baseUrl = '';
    var baseUrl_arr = get_base_url_arr();
    for (var i24 = 0; i24 < baseUrl_arr.length - 1; i24++) {
      baseUrl += baseUrl_arr[i24] + '/';
    }
    //var src = scripts[scripts.length-1].src;


    var url = baseUrl + 'dash.js';
    //console.warn(scripts[i23], baseUrl, url);
    jQuery.ajax({
      url: url,
      dataType: "script",
      success: function (arg) {
        //console.info(arg);

        setup_dash();


      }
    });
  }
}

export function playlistGotoItemHistoryChangeForLinks   (ind_ajaxPage, o, cgallery, _currentTargetPlayer) {


  var $ = jQuery;
  // --- history API ajax cool stuff
  if (o.settings_enableHistory == 'on' && can_history_api()) {
    var stateObj = {foo: "bar"};
    history.pushState(stateObj, "Gallery Video", this.getDataOrAttr(_currentTargetPlayer, 'data-sourcevp'));

    $.ajax({
      url: this.getDataOrAttr(_currentTargetPlayer, 'data-sourcevp'),
      success: function (response) {
        if (window.console != undefined) {
          console.info('Got this from the server: ' + response);
        }
        setTimeout(function () {
          //console.log(jQuery(response).find('.history-video-element').eq(0).get(0).innerHTML);

          $('.history-video-element').eq(0).html($(response).find('.history-video-element').eq(0).html());

          $('.toexecute').each(function () {
            var _t = $(this);
            if (_t.hasClass('executed') == false) {
              eval(_t.text());
              _t.addClass('executed');
            }
          });


          if (o.settings_ajax_extraDivs != '') {
            var extradivs = String(o.settings_ajax_extraDivs).split(',');
            for (let i = 0; i < extradivs.length; i++) {
              //console.log(extradivs[i], jQuery(response).find(extradivs[i]));
              $(extradivs[i]).eq(0).html(jQuery(response).find(extradivs[i]).eq(0).html());
            }
          }

          //console.log(jQuery(response)); console.log(jQuery('.toexecute'));
          //jQuery('.history-video-element').eq(0).get(0).innerHTML = jQuery(response).find('.history-video-element').eq(0).get(0).innerHTML;
          //eval(jQuery('.toexecute').text());
        }, 100);
      },
      error: function (xhr, ajaxOptions, thrownError) {
        if (window.console != undefined) {
          console.error('not found ' + ajaxOptions);
        }
        ind_ajaxPage++;
        cgallery.children('.preloader').fadeOut('slow');

      }
    });

  }
}

export function playlist_initSetupInitial(selfClass, o) {

  // console.log('initial playlist options -5 ', o);

  if (!o.autoplayFirstVideo) {

    o.autoplayFirstVideo = o.autoplay;
  }

  if (o.nav_type === 'outer') {
    if (o.forceVideoHeight === '') {
      o.forceVideoHeight = '300';
    }
  }

  if (o.settings_mode === 'slider') {
    o.menu_position = 'none';
    o.nav_type = 'none';
  }

  if (is_mobile() && o.autoplayNext === 'on') {
    if (o.mode_normal_video_mode !== 'one') {
      o.autoplayNext = 'off';
    }
  }

  selfClass.cgallery.data('vg_autoplayNext', o.autoplayNext);
  selfClass.cgallery.data('vg_settings', o);

  if (isNaN(parseInt(o.menuitem_width, 10)) === false && String(o.menuitem_width).indexOf('%') === -1) {
    o.menuitem_width = parseInt(o.menuitem_width, 10);
  } else {
    o.menuitem_width = '';
  }

  if (isNaN(Number(o.menuitem_height)) === false && o.menuitem_height > 0) {

    o.menuitem_height = Number(o.menuitem_height);
  } else {
    o.menuitem_height = '';
  }
  o.settings_go_to_next_after_inactivity = parseInt(o.settings_go_to_next_after_inactivity, 10);
  //o.menuitem_space = ;


  if (o.startItem !== 'default') {
    o.startItem = parseInt(o.startItem, 10);
  }

  // console.info('o.startItem - ',o.startItem);

  o.settings_separation_pages_number = parseInt(o.settings_separation_pages_number, 10);
  o.settings_trigger_resize = parseInt(o.settings_trigger_resize, 10);


  selfClass.$feedItemsContainer = selfClass.cgallery;

  if (selfClass.cgallery.children('.items').length) {
    selfClass.$feedItemsContainer = selfClass.cgallery.children('.items');
  }


  var masonry_options_default = {
    columnWidth: 1
    , containerStyle: {position: 'relative'}
    , isFitWidth: false
    , isAnimated: true
  };

  //console.info(o.masonry_options);

  o.masonry_options = Object.assign(masonry_options_default, o.masonry_options);


  if (can_history_api() === false) {
    o.settings_enable_linking = 'off';
  }

  if (selfClass.cgallery.children('.feed-layout-builder--menu-items')) {
    selfClass.navigation_customStructure = selfClass.cgallery.children('.feed-layout-builder--menu-items').eq(0).html();
  }

  if (!selfClass.navigation_customStructure) {
    if (!o.design_skin) {
      o.design_skin = 'skin-default';
    }
  }


  // console.log('o.settings_mode  - ', o.settings_mode);
  if (o.settings_mode === 'rotator3d') {

    o.menu_position = 'none';
    o.nav_type = 'none';
    o.transition_type = 'rotator3d';
  }


  if (typeof o.videoplayersettings == 'string' && window.dzsvg_vpconfigs) {
    if (typeof window.dzsvg_vpconfigs[o.videoplayersettings] === 'object') {
      o.videoplayersettings = {...window.dzsvg_vpconfigs[o.videoplayersettings]};
    }
  }


  if (o.videoplayersettings.design_skin === 'sameasgallery') {
    o.videoplayersettings.design_skin = o.design_skin;
  }

  if(selfClass.cgallery.find('.feed-dzsvg--embedcode').length){
    o.embedCode = selfClass.cgallery.find('.feed-dzsvg--embedcode').eq(0).html();
  }
  // console.log('o.embedCode - ', o.embedCode);


}

export function playlist_navigation_set_dimensions  (selfClass, menu_position, menuitem_width, menuitem_height, totalWidth, totalHeight) {

  var cssArgs = {};
  if (menu_position === 'right') {

    if (selfClass.cgallery.hasClass('ultra-responsive')) {

      selfClass._mainNavigation.css({
        'height': '' // height is auto in ultra-responsive
      });
    } else {
      selfClass._mainNavigation.css({
        'width': menuitem_width
        , 'height': totalHeight
      })
    }

  }
  if (menu_position === 'left') {

    //console.log(totalHeight);
    if (selfClass.cgallery.hasClass('ultra-responsive')) {

    } else {
      cssArgs = {
        'width': menuitem_width,
        'height': totalHeight,
        'left': 0
      };
      selfClass._mainNavigation.css(cssArgs);
    }
  }
  if (menu_position == 'bottom') {
    // console.log('menuitem_height -' , menuitem_height);

    cssArgs = {
      'width': totalWidth,
    };
    if (!isNaN(Number(selfClass.initOptions.menuitem_height))) {
      cssArgs.height = menuitem_height
    }
    selfClass._mainNavigation.css(cssArgs)
  }
  if (menu_position == 'top') {
    selfClass._mainNavigation.css({
      'width': totalWidth
      , 'height': ''
    });
  }
}

/**
 * we need selfClass.navigation_customStructure
 * @param selfClass
 */
export function playlist_navigationGenerateStructure(selfClass) {

  var desc = selfClass.navigation_customStructure;
  return desc;

}

export function playlist_navigationStructureAssignVars  (selfClass, $currentVideoPlayer, _cachmenuitem) {

  function replaceInNav(argInMenu, argInStructure) {

    _cachmenuitem.find(argInMenu).html($currentVideoPlayer.find(argInStructure).eq(0).html())
  }

  replaceInNav('.layout-builder--item--type-title', '.feed-menu-title');
  replaceInNav('.layout-builder--item--type-menu-description', '.feed-menu-desc');


  // console.log('_cachmenuitem.find(\'.layout-builder--item--type-thumbnail\') - ', _cachmenuitem.find('.layout-builder--item--type-thumbnail'), $currentVideoPlayer.find('.feed-menu-image'));
  _cachmenuitem.find('.layout-builder--item--type-thumbnail').css('background-image', 'url(' + $currentVideoPlayer.find('.feed-menu-image').html() + ')');
}


/**
 *
 * @param {DzsVideoPlayer} selfClass
 * @param pargs
 */
export function player_getResponsiveRatio  (selfClass, pargs) {

  var $ = jQuery;
  var o = selfClass.initOptions;

  var margs = {
    'reset_responsive_ratio': false
    , 'called_from': 'default'
  };

  if (pargs) {
    margs = $.extend(margs, pargs);
  }

  if (margs.reset_responsive_ratio) {
    o.responsive_ratio = 'default';
  }


  // console.log('player_getResponsiveRatio() ', margs);
  // console.log('[player_getResponsiveRatio()] o.responsive_ratio - ', o.responsive_ratio);
  if (o.responsive_ratio === 'detect') {

    // console.info('lets calculate responsive ratio', selfClass.dataType);
    if (selfClass.dataType === 'selfHosted' || selfClass.dataType === 'dash') {
      if (selfClass._videoElement && selfClass._videoElement.videoHeight) {

        o.responsive_ratio = selfClass._videoElement.videoHeight / selfClass._videoElement.videoWidth;
      } else {
        o.responsive_ratio = 0.5625;
      }

      //console.info(o.responsive_ratio);
      if (selfClass._videoElement && selfClass._videoElement.addEventListener) {
        selfClass._videoElement.addEventListener('loadedmetadata', function () {
          o.responsive_ratio = selfClass._videoElement.videoHeight / selfClass._videoElement.videoWidth;
//                                console.info(o.responsive_ratio);
          selfClass.handleResize();
        })
      }

      if (selfClass.dataType === 'dash') {
        selfClass.dash_inter_check_sizes = setInterval(function () {
          if (selfClass._videoElement && selfClass._videoElement.videoHeight) {
            if (selfClass._videoElement.videoWidth > 0) {
              o.responsive_ratio = selfClass._videoElement.videoHeight / selfClass._videoElement.videoWidth;
              selfClass.handleResize();
              clearInterval(selfClass.dash_inter_check_sizes);
            }
          }
        }, 1000);
      }

    }
    if (selfClass.dataType === 'audio') {
      if (selfClass.cthis.find('.div-full-image').length) {
        var _cach = selfClass.cthis.find('.div-full-image').eq(0);

        var aux = _cach.css('background-image');

        aux = aux.replace(/"/g, '');
        aux = aux.replace("url(", '');
        aux = aux.replace(")", '');

        var img = new Image();

        img.onload = function () {
          o.responsive_ratio = this.naturalHeight / this.naturalWidth;
          selfClass.handleResize();
        };
        img.src = aux;
      }


    }
    if (selfClass.dataType === 'youtube') {
      o.responsive_ratio = 0.5625;
    }
    if (selfClass.dataType === 'vimeo') {
      o.responsive_ratio = 0.5625;
    }
    if (selfClass.dataType === 'inline') {
      o.responsive_ratio = 0.5625;
    }

  }
  o.responsive_ratio = Number(o.responsive_ratio);

  if (selfClass.cthis.hasClass('vp-con-laptop')) {
    o.responsive_ratio = '';
  }
  // console.log('final o.responsive_ratio - ',o.responsive_ratio);
}

export function playlist_inDzsTabsHandle (selfClass, margs) {
  // -- tabs
  var _con = selfClass.cgallery.parent().parent().parent();
  if (selfClass.initOptions.autoplayFirstVideo === 'on') {
    if (margs.called_from !== 'init_restart_in_tabs') {

      setTimeout(function () {

        margs.called_from = 'init_restart_in_tabs';
        selfClass.init(margs);
      }, 50);
      return false;
    }
    // console.warn('_con -4 ',_con);
    if (_con.hasClass('active') || _con.hasClass('will-be-start-item')) {

    } else {
      selfClass.initOptions.autoplayFirstVideo = 'off';
    }
  }

}
/**
 * return .previewImg
 * @param _t
 * @returns {null|jQuery|undefined|*}
 */
export function playlist_navigation_getPreviewImg  (_t) {
  // console.log('playlist_navigation_getPreviewImg() _t - ', _t);

  let stringPreviewImg = '';
  if (_t.attr('data-previewimg')) {
    stringPreviewImg = _t.attr('data-previewimg');
  } else if (_t.attr('data-audioimg')) {


    stringPreviewImg = _t.attr('data-audioimg');
  } else if (_t.attr('data-thumb')) {


    stringPreviewImg = _t.attr('data-thumb');
  }
  return stringPreviewImg;

}
export function playlist_get_real_responsive_ratio  (i, selfClass) {
  var $ = jQuery;
  var o = selfClass.initOptions;
  setTimeout(function (targetIndex) {
    var _cach = selfClass._sliderCon.children().eq(targetIndex);
    //console.info(arg, _cach);
    var src = _cach.data('dzsvg-curatedid-from-gallery');

    $.get(o.php_media_data_retriever + "?action=dzsvg_action_get_responsive_ratio&type=" + _cach.data('dzsvg-curatedtype-from-gallery') + "&source=" + src, function (data) {

      //console.info(data);

      try {
        var json = JSON.parse(data);

        var rr = 0.562;

        if (json.height && json.width) {

          rr = json.height / json.width;
        }

        if (rr.toFixed(3) !== '0.563') {
          _cach.attr('data-responsive_ratio', rr.toFixed(3));
        }
        _cach.attr('data-responsive_ratio-not-known-for-sure', 'off');


        if (_cach.get(0) && _cach.get(0).api_get_responsive_ratio) {
          _cach.get(0).api_get_responsive_ratio({
            'reset_responsive_ratio': true
            , 'called_from': 'php_media_data_retriever'
          })

          setTimeout(function () {
            selfClass.handleResize_currVideo();
          }, 100);
        }
      } catch (err) {
        console.info('json parse error - ', data);
      }


      //_cach.attr('data-responsive_ratio')
    });
  }, 100, i)
}
/**
 * set player data
 * @param _cachmenuitem
 */
export function playlist_navigation_mode_one__set_players_data (_cachmenuitem) {

  var attr_arr = ['data-loop', 'data-sourcevp', 'data-source', 'data-videotitle', 'data-type'];

  var maxlen = attr_arr.length;
  var ci = 0;
  for (var i5 in attr_arr) {
    var lab4 = attr_arr[i5];

    var val = '';
    // console.info('_ci.attr(lab) -5 ',_ci.attr(lab),'lab ( ',lab, ' )');

    // -- put this data in here
    val = getDataOrAttr(_cachmenuitem, lab4)
    if (val) {

      var lab_sanitized_for_data = lab4.replace('data-', 'vp_');
      _cachmenuitem.data(lab_sanitized_for_data, val);
    }

    if (ci > maxlen || ci > 10) {
      break;
    }

    ci++;
  }
}


/** deprecated
 */
export function deprecated__playlist_replace_youtube_thumb_in_desc(desc, _currentVp) {
  var self = this;
  if (desc.indexOf('{ytthumb}') > -1 && _currentVp.data('dzsvg-curatedid-from-gallery')) {
    desc = desc.split("{ytthumb}").join('<div style="background-image:url(//img.youtube.com/vi/' + _currentVp.data('dzsvg-curatedid-from-gallery') + '/0.jpg)" class="imgblock divimage"></div>');
  }
  if (desc.indexOf('{ytthumbimg}') > -1 && _currentVp.data('dzsvg-curatedid-from-gallery')) {
    desc = desc.split("{ytthumbimg}").join('//img.youtube.com/vi/' + self.getDataOrAttr(_currentVp, 'data-sourcevp') + '/0.jpg');
  }

  return desc;
}


/** deprecated
 */
export function deprecated__playlist_navigationGenerateStructure(menu_description_format, _c) {
  var aux = menu_description_format;


  aux = aux.replace('{{number}}', '<div class="menu-number"><span class="the-number">' + _c.find('.feed-menu-number').html() + '</span></div>');
  aux = aux.replace('{{menuimage}}', '<div class="divimage imgblock" style="background-image: url(' + _c.find('.feed-menu-image').html() + ')"></div>');
  aux = aux.replace('{{menutitle}}', '<div class="menu-title" style="">' + _c.find('.feed-menu-title').html() + '</div>');
  aux = aux.replace('{{menudesc}}', '<div class="menu-desc" style="">' + _c.find('.feed-menu-desc').html() + '</div>');
  aux = aux.replace('{{menutime}}', '<div class="menu-time" style="">' + _c.find('.feed-menu-time').html() + '</div>');
  return aux;
}


export function playlist_setupModeWall(selfClass, o) {
  var $ = jQuery;
  var self = this;
  selfClass._sliderCon.children().each(function () {
    // -- each item
    var _t = $(this);


    if (_t.find('.videoDescription').length == 0) {
      if (_t.find('.menuDescription').length > 0) {
        _t.append('<div class="videoDescription">' + _t.find('.menuDescription').html() + '</div>')
      }
    }


    _t.addClass('vgwall-item').addClass('clearfix dzs-layout-item ultibox-item-delegated');
    _t.css({});
    //console.log(totalWidth, totalHeight);
    _t.attr('data-bigwidth', o.modewall_bigwidth);
    _t.attr('data-bigheight', o.modewall_bigheight);
    _t.attr('data-biggallery', 'vgwall');

    if (_t.attr('data-previewimg')) {

      _t.attr('data-thumb-for-gallery', _t.attr('data-previewimg'));
    } else {

    }

    if (_t.attr('data-videoTitle') != undefined && _t.attr('data-videoTitle') != '' && !_t.find('.videoTitle').length) {
      _t.prepend('<div class="videoTitle">' + _t.attr('data-videoTitle') + '</div>');
    }
    if (_t.attr('data-previewimg') != undefined) {
      var aux2 = _t.attr('data-previewimg');

      if (aux2 !== undefined && aux2.indexOf('{ytthumbimg}') > -1) {
        aux2 = aux2.split("{ytthumbimg}").join('//img.youtube.com/vi/' + self.getDataOrAttr(_t, 'data-sourcevp') + '/0.jpg');
      }

      if (!_t.find('.previewImg').length) {

        _t.prepend('<img class="previewImg" style="" src="' + aux2 + '"/>', '.previewImg');
      }

    }


    if ($.fn.zoomBox) {
      _t.zoomBox();
    }
  });

}

export function playlistGotoItemHistoryChangeForNonLinks  (margs, o, cid, arg, deeplinkGotoItemQueryParam = 'the-video') {

  var $ = jQuery;

  var deeplink_str = String(deeplinkGotoItemQueryParam).replace('{{galleryid}}', cid);
  if (margs.ignore_linking == false && margs.called_from != 'init') {
    var stateObj = {foo: "bar"};
    // debugger;
    if ($('.videogallery').length == 1) {
      history.pushState(stateObj, null, this.add_query_arg(window.location.href, deeplink_str, (Number(arg) + 1)));
    } else {
      history.pushState(stateObj, null, this.add_query_arg(window.location.href, deeplink_str + '-' + cid, (Number(arg) + 1)));
    }
  }
}
export function assertVideoFromGalleryAutoplayStatus  (currNr, o, cgallery) {
  var shouldVideoAutoplay = false;
  // console.log('gallery autoplay - ', o.autoplayFirstVideo, 'currNr - ', currNr);
  if (currNr === -1) {
    if (o.autoplayFirstVideo === 'on') {
      // console.log(!(cgallery.parent().parent().parent().hasClass('categories-videogallery')));
      if ((cgallery.parent().parent().parent().hasClass('categories-videogallery')) || !!(cgallery.parent().parent().parent().hasClass('categories-videogallery') && !cgallery.parent().parent().hasClass('gallery-precon')) || !!(cgallery.parent().parent().parent().hasClass('categories-videogallery') && cgallery.parent().parent().hasClass('gallery-precon') && cgallery.parent().parent().hasClass('curr-gallery'))) {
        shouldVideoAutoplay = false;
      } else {
        shouldVideoAutoplay = true;
      }
    }

  }
  // console.log('shouldVideoAutoplay-  ', shouldVideoAutoplay);

  //-- if it's not the first video
  if (currNr > -1) {
    if (o.autoplayNext == 'on') {
      shouldVideoAutoplay = true;
      // -- todo: sideeffect - maybe move
      o.videoplayersettings['cueVideo'] = 'on';
    }
  }


  return shouldVideoAutoplay;
}
export function navigation_detectClassesForPosition  (menu_position, _mainNavigation, cgallery) {


  const classMenuMovement = (menu_position == 'right' || menu_position == 'left') ? 'menu-moves-vertically' : 'menu-moves-horizontally';
  const classesClearMenuLocations = 'menu-top menu-bottom menu-right menu-left';
  const classesNewMenuLocation = 'menu-' + menu_position + ' ' + classMenuMovement;

  _mainNavigation.removeClass(classesClearMenuLocations);
  _mainNavigation.addClass(classesNewMenuLocation);

  cgallery.removeClass(classesClearMenuLocations);
  cgallery.addClass(classesNewMenuLocation);
}

export function navigation_initScroller  (_navMain) {

  var $ = jQuery;
  if ($ && $.fn && $.fn.scroller) {
    _navMain.scroller({
      'enable_easing': 'on'
    });
  }
}
export function playlist_convertMenuThumbs  (cgallery, o) {

  var $ = jQuery;
  var self = this;
  cgallery.children().each(function () {
    var _t = $(this);

    if (_t.attr('data-type') == 'youtube' && _t.attr('data-thumb') == undefined) {
      _t.attr('data-thumb', '//img.youtube.com/vi/' + self.sanitize_to_youtube_id(self.getDataOrAttr(_t, 'data-sourcevp')) + '/0.jpg');
    }

    if (_t.attr('data-previewimg') == undefined) {
      if (_t.attr('data-thumb') != undefined) {
        _t.attr('data-previewimg', _t.attr('data-thumb'));
      }

      if (_t.attr('data-img') != undefined) {
        _t.attr('data-previewimg', _t.attr('data-img'));
      }
    }
    if (o.settings_mode == 'wall') {

      if (_t.find('.videoDescription').length == 0) {
        if (_t.find('.menuDescription').length > 0) {
          _t.append('<div class="videoDescription">' + _t.find('.menuDescription').html() + '</div>')
        }
      }
    }
  })
}

/**
 * draw fullscreen bars
 * @param selfClass
 * @param _controls_fs_canvas
 * @param argColor
 */
export function player_controls_drawFullscreenBarsOnCanvas  (selfClass, _controls_fs_canvas, argColor) {


  if (selfClass.initOptions.design_skin != 'skin_pro') {
    return;
  }
  var ctx = _controls_fs_canvas.getContext("2d");
  var ctx_w = _controls_fs_canvas.width;
  var ctx_pw = ctx_w / 100;
  var ctx_ph = ctx_w / 100;

  ctx.fillStyle = argColor;
  var borderw = 30;
  ctx.fillRect(25 * ctx_pw, 25 * ctx_ph, 50 * ctx_pw, 50 * ctx_ph);
  ctx.beginPath();
  ctx.moveTo(0, 0);
  ctx.lineTo(0, borderw * ctx_ph);
  ctx.lineTo(borderw * ctx_pw, 0);
  ctx.fill();
  ctx.moveTo(0, 100 * ctx_ph);
  ctx.lineTo(0, (100 - borderw) * ctx_ph);
  ctx.lineTo(borderw * ctx_pw, 100 * ctx_ph);
  ctx.fill();
  ctx.moveTo((100) * ctx_pw, (100) * ctx_ph);
  ctx.lineTo((100 - borderw) * ctx_pw, (100) * ctx_ph);
  ctx.lineTo((100) * ctx_pw, (100 - borderw) * ctx_ph);
  ctx.fill();
  ctx.moveTo((100) * ctx_pw, (0) * ctx_ph);
  ctx.lineTo((100 - borderw) * ctx_pw, (0) * ctx_ph);
  ctx.lineTo((100) * ctx_pw, (borderw) * ctx_ph);
  ctx.fill();

}
export function exitFullscreen  () {
  if (document.exitFullscreen) {
    document.exitFullscreen();
  } else if (document.mozCancelFullScreen) {
    document.mozCancelFullScreen();
  } else if (document.webkitExitFullscreen) {
    document.webkitExitFullscreen();
  }

  return null;
}
export function requestFullscreen  (elem) {
  // console.log('request fullscreen -- ', elem);
  if (elem) {

    if (elem.requestFullScreen) {
      return elem.requestFullScreen();
    } else if (elem.webkitRequestFullScreen) {
      return elem.webkitRequestFullScreen()
    } else if (elem.mozRequestFullScreen) {
      return elem.mozRequestFullScreen()
    }
  }

  return null;
}

export function load_outside_script  (arg) {

  // -- vimeo api
  var head = document.getElementsByTagName('head')[0];
  var script = document.createElement('script');
  script.type = 'text/javascript';
  script.src = arg;
  head.appendChild(script);

  // console.log('head - ', head, script);
}