/*
 * Author: Audio Player with Playlist
 * Website: https://digitalzoomstudio.net/
 * Portfolio: https://bit.ly/nM4R6u
 * Version: 5.73
 * */

'use strict';
import * as dzsapHelpers from "./jsinc/_dzsap_helpers";
import $es from "./js_common/_esjquery";
import {ConstantsDzsAp} from "./configs/_constants";



class WaveGenerator {

  constructor($waveGenerator_) {

    this.initOptions = {};
    this.dataSource = '';
    this.$waveGenerator_ = ($waveGenerator_);
    this.$waveGenerator = $es($waveGenerator_);
    this.$wavedataField = null;
    this.$status = null;
    this.init();
  }

  init() {

    var finalOptions = {};
    var defaultOptions = Object.assign({
      source: '',
      selectorWaveData: '',
    }, {});
    finalOptions = dzsapHelpers.convertPluginOptionsToFinalOptions(this.$waveGenerator_, defaultOptions, null, 'data-options', $es);

    var selfClass = this;

    selfClass.initOptions = finalOptions;
    selfClass.$status = selfClass.$waveGenerator.find('.dzsap-wave-generator--status');


    if (selfClass.initOptions.source) {
      selfClass.dataSource = selfClass.initOptions.source;
    }

    if (selfClass.initOptions.selectorWaveData) {
      selfClass.$wavedataField = $es(selfClass.initOptions.selectorWaveData);
    }

    selfClass.$status.html('generating waveforms');

    if (selfClass.dataSource) {
      promise_waveGenerate(selfClass.$waveGenerator.find('.dzsap-wave-generator--wave').get(0), selfClass.dataSource).then((resolve) => {
        selfClass.$status.html('');


        try {
          if (resolve.pcm_data) {
            if (selfClass.$wavedataField) {
              selfClass.$wavedataField.val(resolve.pcm_data);

              if(dzsap_settings.dzsap_wave_generate_auto==='on' && get_query_arg(window.location.href, 'dzsap_wave_generate_auto')==='on'){

                window.dzsap_admin_waveforms_submitPcmData(document.querySelector('.track-waveform-meta'));
              }

            }
          }
        } catch (err) {
          console.log(err);

        }
      })
    }
  }
}


window.es_document_ready(() => {

  $es('.dzsap-wave-generator.auto-init').each($el => {
    var generator = new WaveGenerator($el.get(0));
  })
})

function get_query_arg(purl, key) {
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
window.dzsap_generating_pcm = false;

const promise_waveGenerate = async function ($container_, sourceMp3, pargs) {


  var margs = Object.assign({
    called_from: 'default',
    preferBackendPeaks: true,
    wavesurfer_pcm_length: 200
  }, pargs);
  var self = this;


  return new Promise((resolvePcm, rejectPcm) => {

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

        if (!$container_) {
          $container_ = document.body;
        }

        $es($container_).append(`<div id="${wavesurferConId}"></div>`);


        var ctx = document.createElement('canvas').getContext('2d');
        var linGrad = ctx.createLinearGradient(0, 64, 0, 200);
        linGrad.addColorStop(0.5, 'rgb(107,95,95)');
        linGrad.addColorStop(0.5, 'rgb(119,115,115)');


        var wavesurfer = WaveSurfer.create({
          container: '#' + wavesurferConId,
          normalize: true,
          pixelRatio: 1,
          waveColor: linGrad,
          progressColor: 'hsla(200, 100%, 30%, 0.5)',
          cursorColor: '#fff',
          // This parameter makes the waveform look like SoundCloud's player
          barWidth: 2
        });


        wavesurfer.on('loading', function (percents) {

          $container_.parentNode.querySelector('.dzsap-wave-generator--status').innerHTML = (percents + '%')
        });


        try {
          wavesurfer.load(sourceMp3);
        } catch (err) {
          console.log("[wavesurfer] WAVE SURFER NO LOAD");
        }


        wavesurfer.on('ready', function () {
          console.log('[dzsap] [wavesurfer] generating wave data for ', sourceMp3);

          var accuracy = 100;
          var pcmDataArr = [];
          var pcmDataString = '';
          if (wavesurfer && wavesurfer.exportPCM) {

            if (margs.preferBackendPeaks !== true) {

              pcmDataArr = wavesurfer.exportPCM(margs.wavesurfer_pcm_length, accuracy, true);
            }

            let isPcmDataArrValid = false;
            try {
              pcmDataArr = JSON.parse(pcmDataString);

              for (let i in pcmDataArr) {
                if (pcmDataArr[i] !== null && pcmDataArr[i] !== 0 && pcmDataArr[i] !== "0") {
                  isPcmDataArrValid = true;
                  break;
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
            pcmDataArr = dzsapHelpers.generateFakeArrayForPcm();
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

        }, ConstantsDzsAp.WAVESURFER_MAX_TIMEOUT);
      }

      // -- end promise


      return new Promise((resolve, reject) => {
        asyncRenderPcm(resolve, reject);
      });
    }

    wavesurfer_renderPcm().then(responsePcm => {
      console.log('%c [dzsap] [wave] success while generating pcm - ', ConstantsDzsAp.DEBUG_STYLE_PLAY_FUNCTIONS, responsePcm)

      resolvePcm(responsePcm);

    }).catch(err => {

      var default_pcm = [];

      for (var i3 = 0; i3 < 200; i3++) {
        default_pcm[i3] = Math.abs(Number(Math.random()).toFixed(2));
      }
      default_pcm = JSON.stringify(default_pcm);

      console.log('%c [dzsap] [wave] error while generating pcm - ', ConstantsDzsAp.DEBUG_STYLE_PLAY_FUNCTIONS, err, err.error_message)


      resolvePcm(default_pcm);
    });
  })


}
