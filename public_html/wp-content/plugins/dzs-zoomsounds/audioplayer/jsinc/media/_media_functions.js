import * as dzsapHelpers from '../_dzsap_helpers';
import * as dzsHelpers from '../../js_common/_dzs_helpers';
import {ConstantsDzsAp} from "../../configs/_constants";

/**
 *
 * @param {DzsAudioPlayer} selfClass
 * @param callbackFn
 * @param errorFn
 */
export const media_tryToPlay = function (selfClass, callbackFn, errorFn) {
  async function async_media_tryToPlay() {
    function tryToPlay(resolve, reject) {
      if (selfClass.cthis.attr('data-original-type')) {
        // -- then this player is feeding
      } else {

        // -- no audioCtx_buffer
        if (selfClass.$mediaNode_) {
          if (selfClass.$mediaNode_.play) {


            if (dzsapHelpers.is_ios() && selfClass.spectrum_audioContext !== null && typeof selfClass.spectrum_audioContext == 'object') {
              // todo: ios not playing nice.. with audio context

              selfClass.spectrum_audioContextBufferSource = selfClass.spectrum_audioContext.createBufferSource();
              selfClass.spectrum_audioContextBufferSource.buffer = selfClass.spectrum_audioContext_buffer;
              selfClass.spectrum_audioContextBufferSource.connect(selfClass.spectrum_audioContext.destination);

              selfClass.spectrum_audioContextBufferSource.start(0, selfClass.lastTimeInSeconds);
              resolve({
                'resolve_type': 'playing_context'
              })
            } else {

              if (dzsapHelpers.is_ie()) {
                selfClass.$mediaNode_.play();
                resolve({
                  'resolve_type': 'started_playing'
                })
              } else {

                selfClass.$mediaNode_.play().then(r => {
                  resolve({
                    'resolve_type': 'started_playing'
                  })
                }).catch(err => {
                  reject({
                    'error_type': 'did not want to play',
                    'error_message': err
                  });
                });
              }
            }
          } else {
            if (selfClass._actualPlayer == null) {
              selfClass.isPlayPromised = true;
            }

          }
        } else {
          if (selfClass._actualPlayer == null) {
            selfClass.isPlayPromised = true;
          }
        }


      }

    }

    return new Promise((resolve, reject) => {

      tryToPlay(resolve, reject);

    })
  }

  async_media_tryToPlay().then((r) => {
    callbackFn(r);
  }).catch((err) => {
    errorFn(err);
  })

}

export const media_removeMediaInside = (selfClass) => {

  selfClass.$theMedia.children().remove();
  selfClass.$mediaNode_ = null;
}

export const setupMediaElement = (selfClass, stringAudioElementHtml = '', stringAudioTagSource = '') => {


  media_removeMediaInside(selfClass);

  if (stringAudioTagSource) {
    if (selfClass.$mediaNode_) {

      jQuery(selfClass.$mediaNode_).append(stringAudioTagSource);
      if (selfClass.$mediaNode_.load) {
        selfClass.$mediaNode_.load();
      }

    } else {
      setupMediaElement(selfClass, stringAudioElementHtml);
      return false;
    }
  } else {
    selfClass.$theMedia.append(stringAudioElementHtml);
  }
  selfClass.$mediaNode_ = (selfClass.$theMedia.children('audio').get(0));


}

export const setupMediaListeners = function (selfClass, setupMediaAttrs, action_initLoaded, volume_lastVolume, volume_setVolume) {

  var attempt_reload = 0;


  selfClass.$mediaNode_.addEventListener('error', handleAudioError, true);
  selfClass.$mediaNode_.addEventListener('loadedmetadata', handleMediaMetaLoaded, true);


  function handleAudioError(e) {

    const $audioElement_ = this;

    var noSourcesLoaded = ($audioElement_.networkState === HTMLMediaElement.NETWORK_NO_SOURCE);
    if (noSourcesLoaded && dzsapHelpers.dzsap_is_mobile() === false) {
      if (selfClass.cthis.hasClass(ConstantsDzsAp.ERRORED_OUT_CLASS) === false) {

        if (attempt_reload < ConstantsDzsAp.ERRORED_OUT_MAX_ATTEMPTS) {
          setTimeout(function (earg) {
            if (selfClass.$mediaNode_) {
              selfClass.$mediaNode_.src = '';
            }


            setTimeout(function () {
              if (selfClass.$mediaNode_) {
                selfClass.$mediaNode_.src = selfClass.data_source;
                selfClass.$mediaNode_.load();
              }
            }, 1000)
          }, 1000, e)
          attempt_reload++;
        } else {

          // -- IT FAILED LOADING

          if (selfClass.initOptions.notice_no_media === 'on') {
            selfClass.cthis.addClass(ConstantsDzsAp.ERRORED_OUT_CLASS);
            var txt = 'error - file does not exist...';
            if (e.target.error) {
              txt = e.target.error.message;
            }
            selfClass.cthis.append(dzsHelpers.setupTooltip({
              tooltipConClass: ' feedback-tooltip-con',
              tooltipIndicatorText: '<span class="player-but"><span class="the-icon-bg" style="background-color: #912c2c"></span><span class="svg-icon dzsap-color-ui-inverse" >&#x2139;</span></span>',
              tooltipInnerHTML: 'cannot load - ( ' + selfClass.data_source + ' ) - error: ' + txt,
            }));
          }
        }
      }
    }
  }


  function handleMediaMetaLoaded(e) {


    dzsapHelpers.player_view_addMetaLoaded(selfClass);

    /** @type {HTMLAudioElement} */
    const $audio_ = e.currentTarget;

    if (isValidTotalTime($audio_.duration)) {
      selfClass.timeModel.actualTotalTime = Math.ceil($audio_.duration);
    }
    selfClass.service_checkIfWeShouldUpdateTotalTime();


    if (setupMediaAttrs.called_from === 'change_media') {

      action_initLoaded({
        'call_from': 'force_reload_change_media'
      })
    }

    if (setupMediaAttrs.called_from === 'change_media' || selfClass._sourcePlayer) {
      if (volume_lastVolume) {
        setTimeout(() => {
          volume_setVolume(volume_lastVolume, {
            call_from: "change_media"
          });
        }, 50);
      }
    }

    if (selfClass._sourcePlayer) {
      if (isValidTotalTime($audio_.duration)) {
        selfClass._sourcePlayer.get(0).api_set_timeVisualTotal($audio_.duration)

      }
    }


    selfClass.view_drawCurrentTime();
  }

}


export const buildAudioElementHtml = function (selfClass, type_normal_stream_type, calledFrom) {

  var stringAudioTagOpen = '';
  var stringAudioTagSource = '';
  var stringAudioTagClose = '';
  var o = selfClass.initOptions;


  if (selfClass.data_source) {
    if (selfClass.data_source.indexOf('get_track_source') > -1) {
      o.preload_method = 'none';
    }
  }

  stringAudioTagOpen += '<audio';
  stringAudioTagOpen += ' id="' + selfClass.uniqueId + '-audio"';
  stringAudioTagOpen += ' preload="' + o.preload_method + '"';
  if (o.skinwave_enableSpectrum === 'on') {
    stringAudioTagOpen += ' crossOrigin="anonymous"';

  }

  if (dzsapHelpers.is_ios()) {
    if (calledFrom === 'change_media') {
      stringAudioTagOpen += ' autoplay';
    }
  }

  stringAudioTagOpen += '>';
  stringAudioTagSource = '';


  if (selfClass.data_source) {

    if (!selfClass.data_source && type_normal_stream_type !== 'icecast') {
      selfClass.data_source = selfClass.cthis.attr('data-source');
    }


    if (selfClass.data_source !== 'fake') {
      stringAudioTagSource += '<source src="' + selfClass.data_source + '" type="audio/mpeg">';
      if (selfClass.cthis.attr('data-sourceogg') !== undefined) {
        stringAudioTagSource += '<source src="' + selfClass.cthis.attr('data-sourceogg') + '" type="audio/ogg">';
      }
    } else {
      selfClass.cthis.addClass('meta-loaded meta-fake');
    }
  }
  stringAudioTagClose += '</audio>';


  return {
    stringAudioElementHtml: stringAudioTagOpen + stringAudioTagSource + stringAudioTagClose,
    stringAudioTagSource
  };

}

export const makeMediaPreloadInTheFuture = function (selfClass, stringAudioElementHtml) {

  setTimeout(function () {
    if (selfClass.$mediaNode_) {
      jQuery(selfClass.$mediaNode_).attr('preload', 'metadata');
    }
  }, (Number(window.dzsap_player_index) * 10000));
}
export const repairMediaElement = function (selfClass, stringAudioElementHtml) {

  var o = selfClass.initOptions;
  setTimeout(function () {

    if (selfClass.$theMedia.children().eq(0).get(0) && selfClass.$theMedia.children().eq(0).get(0).nodeName === "AUDIO") {

      return false;
    }
    selfClass.$theMedia.html('');
    selfClass.$mediaNode_ = null;
    selfClass.$theMedia.append(stringAudioElementHtml);

    var isWasPlaying = selfClass.player_playing;

    selfClass.pause_media();
    selfClass.$mediaNode_ = (selfClass.$theMedia.children('audio').get(0));


    if (isWasPlaying) {
      setTimeout(function () {

        selfClass.play_media({
          'called_from': 'aux_was_playing'
        });
      }, 20);
    }
  }, o.failsafe_repair_media_element);

  o.failsafe_repair_media_element = '';
}
export const media_pause = function (selfClass, callbackFn) {

  var $ = jQuery;


  if (selfClass.audioType === 'youtube') {


    if (selfClass.$mediaNode_ && selfClass.$mediaNode_.pauseVideo) {
      selfClass.$mediaNode_.pauseVideo();
    }
  }
  if (selfClass.audioType === 'selfHosted') {

    if (0) {
    } else {
      if (selfClass.$mediaNode_) {

        if (selfClass.initOptions.pause_method == 'stop') {

          selfClass.$mediaNode_.pause();
          selfClass.$mediaNode_.src = '';


          selfClass.destroy_cmedia();
          $(selfClass.$mediaNode_).remove();
          selfClass.$mediaNode_ = null;
        } else {

          if (selfClass.$mediaNode_.pause) {
            selfClass.$mediaNode_.pause();
          }
        }
      }

    }


  }

  callbackFn();

}

export const isValidTotalTime = (duration)=>{
  return Boolean(duration && duration !== Infinity);
}

