import {ConstantsDzsAp} from "../configs/_constants";


export function retrieve_soundcloud_url(selfClass, pargs) {


  var o = selfClass.initOptions;

  if (o.soundcloud_apikey == '') {
    alert('soundcloud api key not defined, read docs!');
  }
  var aux = 'https://api.' + 'soundcloud.com' + '/resolve?url=' + selfClass.data_source + '&format=json&consumer_key=' + o.soundcloud_apikey;



  aux = encodeURIComponent(aux);


  var soundcloud_retriever = o.php_retriever + '?scurl=' + aux;


  jQuery.ajax({
    type: "GET",
    url: soundcloud_retriever
    , data: {}
    , async: true
    , dataType: 'text'
    , error: function (err, q, t) {

    }
    , success: function (response) {


      var data = [];


      let newSource = '';
      try {
        data = JSON.parse(response);

        selfClass.audioType = 'selfHosted';


        if (data == '') {
          selfClass.cthis.addClass(ConstantsDzsAp.ERRORED_OUT_CLASS);
          selfClass.cthis.append('<div class="feedback-text">soundcloud track does not seem to serve via api</div>');
        }



        selfClass.original_real_mp3 = selfClass.cthis.attr('data-source');
        if (data.stream_url) {

          newSource = data.stream_url + '?consumer_key=' + o.soundcloud_apikey + '&origin=localhost';
          selfClass.cthis.attr('data-source', newSource);


          if (selfClass.$feed_fakeButton) {
            selfClass.$feed_fakeButton.attr('data-source', newSource);
          }
          if (selfClass._sourcePlayer) {
            selfClass._sourcePlayer.attr('data-source', newSource);
          }
        } else {

          selfClass.cthis.addClass(ConstantsDzsAp.ERRORED_OUT_CLASS);
          selfClass.cthis.append('<div class="feedback-text ">this soundcloud track does not allow streaming  </div>');
        }
        selfClass.data_source = newSource;


        if (selfClass.cthis.attr('data-pcm')) {
          selfClass.isAlreadyHasRealPcm = true;
        }
        if (o.design_skin == 'skin-wave') {
          if (o.skinwave_wave_mode == 'canvas') {
            if (selfClass.isAlreadyHasRealPcm == false) {
              if ((o.pcm_data_try_to_generate == 'on' && o.pcm_data_try_to_generate_wait_for_real_pcm == 'on') == false) {
                window.scrubModeWave__initGenerateWaveData(selfClass, {
                  'call_from': 'soundcloud init(), pcm not real..'
                });
              }
            }
          }
        }

        selfClass.setup_media({
          'called_from': 'change_media'
        });


        setTimeout(function () {


          if (selfClass.isPlayPromised) {
            selfClass.play_media({
              'call_from': 'change_media'
            })
            selfClass.isPlayPromised = false;
          }
        }, 300);
      } catch (err) {
        console.log('soduncloud parse error -', response, ' - ', soundcloud_retriever);
      }
    }
  });

}
