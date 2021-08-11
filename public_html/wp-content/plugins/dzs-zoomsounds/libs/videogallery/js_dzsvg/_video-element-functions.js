// -- actions on the video element

const helpersDZSVG = require('./_dzsvg_helpers');
exports.video_play = function (selfClass, pargs) {


  var margs = {
    'called_from': 'default'
  };

  if (pargs) {
    margs = Object.assign(margs, pargs);
  }

  const self = this;

  if (selfClass.dataType == 'selfHosted' || selfClass.dataType == 'audio' || selfClass.dataType == 'dash') {


    var playPromise = null;
    if (selfClass._videoElement) {
      playPromise = selfClass._videoElement.play();
    }

    if (playPromise !== undefined && playPromise !== null) {
      playPromise.then(function () {

      }).catch(function (error) {
        // -- try muted

        console.log('[dzsvg] [player] fallback . autoplay muted');
        selfClass.cthis.addClass('autoplay-fallback--started-muted')
        if (margs.called_from != 'retry_muted') {
          if(selfClass.initOptions.autoplayWithVideoMuted=='auto'){

            self.video_mute(selfClass, {called_from: 'play_video__retry_muted'} )
            self.video_play(selfClass, Object.assign(margs, {
              called_from: 'retry_muted'
            }));
          }else{
            selfClass.pauseMovie();
          }
        } else {
          console.log('error when autoplaying - ', error, selfClass._videoElement, selfClass._videoElement.muted);
          throw new Error('retry not working even muted...');
        }

      });
    }


    if (selfClass.cthis.hasClass('pattern-video')) {
      selfClass.cthis.find('.the-video').each(function () {
        var _t = jQuery(this);

        if (margs.called_from == 'play_from_loop') {

          _t.get(0).currentTime = 0;
        }
        _t.get(0).play();
      })
    }
  }


  if (selfClass.dataType == 'vimeo') {
    var vimeo_data = {
      "method": "play"
    };
    helpersDZSVG.vimeo_do_command(selfClass, vimeo_data, selfClass.vimeo_url);
  }


  if (selfClass.dataType == 'youtube'){

    if (selfClass._videoElement.playVideo != undefined && selfClass._videoElement.getPlayerState && selfClass._videoElement.getPlayerState != 1) {
      selfClass._videoElement.playVideo();
    }
  }
}
exports.video_mute = function (selfClass, pargs) {

  if (selfClass.dataType == 'selfHosted' || selfClass.dataType == 'audio' || selfClass.dataType == 'dash') {


    if (selfClass._videoElement && selfClass._videoElement.setAttribute) {

      selfClass._videoElement.muted = true;
      selfClass._videoElement.setAttribute('muted', true);
      selfClass.cthis.addClass('is-muted');
    }
  }


  if (selfClass.dataType == 'youtube' && selfClass._videoElement) {

    if (selfClass._videoElement.mute) {
      selfClass._videoElement.mute(); // -- youtube
      selfClass.cthis.addClass('is-muted');
    }else{
      console.log('[dzsvg] [warning] [youtube] video mute failed');
    }
  }

}