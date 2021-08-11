// -- it is loaded from parts/ in audioplayer.js


const generateFakeArrayForPcm = function () {


  var maxlen = 256;

  var arr = [];

  for (var it1 = 0; it1 < maxlen; it1++) {
    arr[it1] = Math.random() * 100;

  }

  return arr;
}


function isWeCanGeneratePcm(selfClass) {

  if (selfClass.isAlreadyHasRealPcm) {
    return false;
  }
  return selfClass.data_source != 'fake';

}

/**
 * start to load wavesurfer
 * @param selfClass
 * @returns {boolean}
 */
window.scrubModeWave__initGenerateWaveData = function (selfClass) {


  var $ = jQuery;
  var o = selfClass.initOptions;
  var self = this;

  if (!isWeCanGeneratePcm(selfClass)) {
    return false;
  }


  if (selfClass.audioType !== 'youtube') {

    if (window.WaveSurfer) {

      waves_generatePcmArray(selfClass, {
        'call_from': 'wavesurfer already loaded'
      });
    } else {


      if (o.pcm_notice == 'on') {
        selfClass.cthis.addClass('errored-out');
        selfClass.cthis.append('<div class="feedback-text pcm-notice">please wait while pcm data is generated.. </div>');
      }

      service_loadWaveSurferScript(selfClass, self);
    }
  }
}

/**
 * actually load wavesurfer
 * @param selfClass
 * @param self
 * @returns {void}
 */
function service_loadWaveSurferScript(selfClass, self) {


  /**
   *
   * @param scriptSrc
   * @param checkForVar
   * @returns {Promise<unknown>}
   */
  const loadScriptIfItDoesNotExist = (scriptSrc, checkForVar) => {
    return new Promise((resolve, reject) => {
      if (checkForVar) {
        resolve('loadfromvar');
      }
      var script = document.createElement('script');
      script.onload = function () {
        resolve('loadfromload');
      };
      script.onerror = function () {
        reject();
      };
      script.src = scriptSrc;

      document.head.appendChild(script);
    })
  }


  window.dzsap_get_base_url();

  let wavesurferUrl = window.dzsap_base_url ? window.dzsap_base_url + '/wavesurfer.js' : 'https://unpkg.com/wavesurfer.js@3.3.3/dist/wavesurfer.min.js';


  loadScriptIfItDoesNotExist(wavesurferUrl, window.WaveSurfer).then((resolveStr) => {
    waves_generatePcmArray(selfClass, {
      'call_from': 'load_script'
    });
  });

}


/**
 * wavesurfer is loaded - acutally generate wave data
 * @param selfClass
 * @param pargs
 * @returns {boolean}
 */
function waves_generatePcmArray(selfClass, pargs) {

  var $ = jQuery;
  var o = selfClass.initOptions;

  var margs = {
    call_from: 'default'
  }
  var self = this;

  if (pargs) {
    $.extend(margs, pargs);
  }


  if (selfClass.data_source === 'fake') {
    return false;
  }


  async function wavesurfer_renderPcm() {

    function asyncRenderPcm(resolve, reject) {


      // -- make sure we are generating only once
      if (window.dzsap_generating_pcm) {
        setTimeout(function () {
          asyncRenderPcm(resolve, reject);
        }, 1000);
        return false;
      }
      window.dzsap_generating_pcm = true;


      var wavesurferConId = 'wavesurfer_' + Math.ceil(Math.random() * 10000);
      if (selfClass.identifier_pcm) {
        wavesurferConId = 'wavesurfer_' + selfClass.identifier_pcm;
      }
      selfClass.cthis.append('<div id="' + wavesurferConId + '" class="hidden"></div>');

      var wavesurfer = WaveSurfer.create({
        container: '#' + wavesurferConId
        , normalize: true
        , pixelRatio: 1
      });


      // -- we will not generate for this
      if (String(selfClass.cthis.attr('data-source')).indexOf('https://soundcloud.com') === 0 || selfClass.cthis.attr('data-source') === 'fake') {
        return;
      }
      if (String(selfClass.cthis.attr('data-source')).indexOf('https://api.soundcloud.com') === 0) {
      }


      try {
        wavesurfer.load(selfClass.data_source);
      } catch (err) {
        console.log("[wavesurfer] WAVE SURFER NO LOAD");
      }


      wavesurfer.on('ready', function () {

        console.log('[dzsap] [WaveSurfer] generating wave data for ', selfClass.identifier_pcm);

        var accuracy = 100;
        if (selfClass.$mediaNode_ && selfClass.$mediaNode_.duration && selfClass.$mediaNode_.duration > 1000) {
          accuracy = 100;
        }


        var pcmDataArr = [];
        var pcmDataString = '';
        if (wavesurfer && wavesurfer.exportPCM) {

          pcmDataString = wavesurfer.exportPCM(o.wavesurfer_pcm_length, accuracy, true);

          let isPcmDataArrValid = false;
          try {
            pcmDataArr = JSON.parse(pcmDataString);

            for (let i in pcmDataArr) {
              if (pcmDataArr[i] !== null && pcmDataArr[i] !== 0 && pcmDataArr[i] !== "0") {
                isPcmDataArrValid = true;
              }
            }
          } catch (err) {
            isPcmDataArrValid = false;
          }

          if (!isPcmDataArrValid) {
            pcmDataArr = wavesurfer.backend.getPeaks(margs.wavesurfer_pcm_length, 0, margs.wavesurfer_pcm_length);
          }

          for (let i in pcmDataArr) {
            if (pcmDataArr[i] !== null && pcmDataArr[i] !== 0 && pcmDataArr[i] !== "0") {
              pcmDataArr[i] = Math.abs(Number(Number(pcmDataArr[i]).toFixed(2)));
            }
          }
        } else {
          pcmDataArr = generateFakeArrayForPcm();
        }

        pcmDataString = JSON.stringify(pcmDataArr);
        resolve({
          resolve_type: 'success',
          pcm_data: pcmDataString
        })
      });

      wavesurfer.on('error', function (err, err2) {
        reject({
          error_type: 'wavesurfer_error',
          error_message: err,
        })
      });


      setTimeout(() => {

        reject({
          error_type: 'wavesurfer_timeout',
          error_message: 'timeout',
        })

      }, 20003);
    }


    return new Promise((resolve, reject) => {
      asyncRenderPcm(resolve, reject);
    });
  }

  wavesurfer_renderPcm().then(responsePcm => {


    service_sendPcmData(selfClass, responsePcm.pcm_data);
    window.scrubModeWave__view_transitionIn(selfClass, responsePcm.pcm_data);
    window.dzsap_wavesurfer_is_trying_to_generate = null;
    selfClass.isAlreadyHasRealPcm = true;
  }).catch(err => {

    let randomPcm = [];

    for (var i3 = 0; i3 < 200; i3++) {
      randomPcm[i3] = Math.abs(Number(Math.random()).toFixed(3));
    }
    randomPcm = JSON.stringify(randomPcm);

    console.log('%c [dzsap] [wave] error while generating pcm - ', 'background-color: #aa3333;', err, err.error_message)

    service_sendPcmData(selfClass, randomPcm);
    window.scrubModeWave__view_transitionIn(selfClass, randomPcm);
    window.dzsap_wavesurfer_is_trying_to_generate = null;
    selfClass.isAlreadyHasRealPcm = false;
  });

}


/**
 * service send pcm data
 * @param {DzsAudioPlayer} selfClass
 * @param pcmArray
 */
function service_sendPcmData(selfClass, pcmArray) {
  var $ = jQuery;


  try {
    if (pcmArray.constructor === Array) {
      pcmArray = String('[' + String(pcmArray) + ']');
    }
    // -- convert to absolute
    pcmArray = JSON.stringify(JSON.parse(String(pcmArray)).map(Math.abs));
  } catch (err) {
    console.log('%c [dzsap] [wave] error while generating pcm - ', 'background-color: #aa3333;', err, 'ar_str - ', pcmArray, typeof pcmArray);
  }

  selfClass.cthis.attr('data-pcm', pcmArray);
  if (selfClass.$feed_fakeButton && selfClass.$feed_fakeButton.attr) {
    selfClass.$feed_fakeButton.attr('data-pcm', pcmArray);
  }
  if (selfClass._sourcePlayer && selfClass._sourcePlayer.attr) {
    selfClass._sourcePlayer.attr('data-pcm', pcmArray);
  }


  selfClass.cthis.find('.pcm-notice').fadeOut("fast");
  selfClass.cthis.removeClass('errored-out');


  if (selfClass.identifier_pcm === 'dzsap_footer') {
    selfClass.identifier_pcm = '';
  }
  if (!selfClass.identifier_pcm) {
    selfClass.identifier_pcm = selfClass.cthis.attr('data-source');

    if (selfClass.original_real_mp3) {
      selfClass.identifier_pcm = selfClass.original_real_mp3;
    }
  }
  if(typeof selfClass.identifier_pcm==='string'){
    selfClass.identifier_pcm = selfClass.identifier_pcm.replace('ap','');
  }


  const data = {
    action: 'dzsap_submit_pcm',
    postdata: pcmArray,
    playerid: selfClass.identifier_pcm,
    source: selfClass.cthis.attr('data-source')
  };


  window.dzsap_generating_pcm = false;


  if (selfClass.urlToAjaxHandler) {


    $.ajax({
      type: "POST",
      url: selfClass.urlToAjaxHandler,
      data: data,
      success: function (response) {

      }
    });
  }
}


