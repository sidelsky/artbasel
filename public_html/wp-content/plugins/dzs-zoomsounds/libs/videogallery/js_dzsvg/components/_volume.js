
var helpersSvg = require('../_dzsvg_svgs');
var helpersDZSVG = require('../_dzsvg_helpers');
class VolumeControls {
  constructor(selfClass) {
    this.selfClass = selfClass;
  }

  constructVolumeInPlayer(){

    var selfClass = this.selfClass;
    var o = selfClass.initOptions;
    var struct_volume = '<div class="volumecontrols"></div>';


    if (selfClass._controlsRight) {
      selfClass._controlsRight.append(struct_volume);
    } else {
      selfClass._controlsDiv.append(struct_volume);
    }


    selfClass._volumeControls = selfClass.cthis.find('.volumecontrols');
    selfClass._volumeControls_real = selfClass.cthis.find('.volumecontrols');



    var str_volumeControls_struct = '<div class="volumeicon">';
    if (o.design_skin === 'skin_aurora' || o.design_skin === 'skin_default' || o.design_skin === 'skin_white') {
      str_volumeControls_struct += helpersSvg.svg_volume_icon;
    }
    str_volumeControls_struct += '</div><div class="volume_static">';

    if (o.design_skin == 'skin_default') {
      str_volumeControls_struct += helpersSvg.svg_default_volume_static;
    }
    if (o.design_skin == 'skin_reborn' || o.design_skin == 'skin_white') {
      for (var i2 = 0; i2 < 10; i2++) {
        str_volumeControls_struct += '<div class="volbar"></div>';
      }
    }

    str_volumeControls_struct += '</div><div class="volume_active">';

    if (o.design_skin == 'skin_default') {
      str_volumeControls_struct += helpersSvg.svg_volume_active_skin_default;
    }
    if (o.design_skin == 'skin_aurora') {
      ;
    }


    str_volumeControls_struct += '</div><div class="volume_cut"></div>';


    if (o.design_skin == 'skin_reborn') {
      str_volumeControls_struct += '<div class="volume-tooltip">VOLUME: 100</div>';
    }

    selfClass._volumeControls.append(str_volumeControls_struct);
  }


  set_volume_adjustVolumeBar(volumeAmount){

    var selfClass = this.selfClass;
    var o = selfClass.initOptions;

    var volumeX = volumeAmount;
    if (o.design_skin == 'skin_reborn') {
      volumeX *= 10;
      volumeX = Math.round(volumeX);
      volumeX /= 10;
    }

    if (volumeX > 1) {
      volumeX = 1;
    }


    var volumeControl = selfClass.cthis.find('.volumecontrols').children();

    var aux = volumeX * (volumeControl.eq(1).width() + selfClass.volumeWidthOffset);

    if (o.design_skin == 'skin_reborn' || o.design_skin == 'skin_white') {



      if(selfClass._volumeControls_real){

        var aux2 = volumeX * 10;
        selfClass._volumeControls_real.children('.volume_static').children().removeClass('active');

        for (var i = 0; i < aux2; i++) {

          selfClass._volumeControls_real.children('.volume_static').children().eq(i).addClass('active');
        }

        selfClass._volumeControls_real.children('.volume-tooltip').css({
          'right': (100 - (aux2 * 10)) + '%'
        })
        selfClass._volumeControls_real.children('.volume-tooltip').html('VOLUME: ' + (aux2 * 10));
      }



    } else {

      volumeControl.eq(2).width(aux);
    }
  }
  set_volume(volumeAmount){
    var selfClass = this.selfClass;



    if (volumeAmount >= 0) {

      if(selfClass._videoElement){

        if (selfClass.dataType == 'selfHosted' || selfClass.dataType == 'audio') {
          selfClass._videoElement.volume = volumeAmount;

        }
        if (selfClass.dataType == 'youtube') {
          selfClass._videoElement.setVolume(volumeAmount * 100);
        }
      }




      if (selfClass.dataType == 'vimeo') {
        var vimeo_data = {
          "method": "setVolume"
          , "value": volumeAmount
        };

        if (selfClass.vimeo_url) {
          helpersDZSVG.vimeo_do_command(selfClass, vimeo_data, selfClass.vimeo_url);
        }
      }

    }
    this.set_volume_adjustVolumeBar(volumeAmount);


    if (localStorage != null) {
      localStorage.setItem('volumeIndex', volumeAmount);
    }
  }

  /**
   * apply only once per video
   * @returns {boolean}
   */
  volume_setInitial() {
    var selfClass = this.selfClass;
    var o = selfClass.initOptions;


    if(selfClass.cthis.data('isVolumeAlreadySetInitial') || selfClass.hasCustomSkin===false){
      return false;
    }
    selfClass.cthis.data('isVolumeAlreadySetInitial', true);

    if (o.defaultvolume == '') {
      o.defaultvolume = 'last';
    }

    if (isNaN(Number(o.defaultvolume))) {
      if (o.defaultvolume == 'last') {
        if (localStorage != null) {
          if (localStorage.getItem('volumeIndex') === null) {
            selfClass.volumeDefault = 1;
          } else {
            selfClass.volumeDefault = localStorage.getItem('volumeIndex');
          }
        } else {
          selfClass.volumeDefault = 1;
        }
      }
    } else {
      o.defaultvolume = Number(o.defaultvolume);
      selfClass.volumeDefault = o.defaultvolume;
    }
    selfClass.volumeDefault = Number(selfClass.volumeDefault);
    if (selfClass.volumeDefault > -0.1 || isNaN(Number(selfClass.volumeDefault)) == false) {
      // -- all well
    } else {
      selfClass.volumeDefault = 1;
    }




    if (!selfClass.shouldStartMuted) {
      selfClass.setupVolume(selfClass.volumeDefault, {'called_from': 'init, selfClass.volumeDefault'});
    } else {
      selfClass.volume_mute();
    }


  }


  volume_getVolume() {

    var selfClass = this.selfClass;

    if(selfClass._videoElement) {
      if (selfClass.dataType == 'selfHosted' || selfClass.dataType == 'audio' || selfClass.dataType == 'dash') {
        return selfClass._videoElement.volume;
      }
      if (selfClass.dataType == 'youtube') {
        return (Number(selfClass._videoElement.getVolume()) / 100);
      }
    }

    return 0;
  }

  player_volumeUnmute() {

    var selfClass = this.selfClass;
    var o = selfClass.initOptions;


    window.dzsvg_had_user_action = true;

    o.user_action = 'yet';

    if (selfClass._videoElement && selfClass._videoElement.removeAttribute) {

      selfClass._videoElement.muted = false;
      selfClass._videoElement.removeAttribute('muted');

    }

    if (selfClass._videoElement) {

      if (selfClass._videoElement.unMute) {
        selfClass._videoElement.unMute(); // -- youtube
      }
    }




    if (selfClass.is_muted_for_autoplay) {


      selfClass._videoElement.muted = false;
    }

    if (this.volume_getVolume() === 0) {
      if (!selfClass.volumeLast) {
        selfClass.volumeLast = 1;
      }
      selfClass.setupVolume(selfClass.volumeLast, {'called_from': 'volume_unmute()'});
    }
  }
}
exports.VolumeControls = VolumeControls;