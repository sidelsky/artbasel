function dzscommon_documentReady(callback, reject) {

  return new Promise((resolve, reject) => {

    if (document.readyState === "complete" || document.readyState === "interactive") {
      resolve('document loaded');
    }
    document.addEventListener("DOMContentLoaded", function (event) {
      resolve('document loaded');
    });
  })
}

dzscommon_documentReady().then((r) => {

  const setSliderVolume = (newVolumePerc, $volumePercentage_) => {

    $volumePercentage_.style.height = newVolumePerc * 100 + '%';
  }
  const setPlayerVolume = ($playerTarget_, newVolumePerc) => {
    if ($playerTarget_ && $playerTarget_.api_set_volume) {
      $playerTarget_.api_set_volume(newVolumePerc);
    }
  }

  setTimeout(() => {

    document.querySelectorAll(" .volume-container").forEach($volumeContainer_ => {


      const $playerTarget_ = document.querySelector($volumeContainer_.getAttribute('data-player-target'));

      if ($playerTarget_ && $playerTarget_.api_get_last_vol) {
        setSliderVolume($playerTarget_.api_get_last_vol(), $volumeContainer_.querySelector(" .volume-percentage"))
      }
    })
  }, 1000);

  const volumeSlider = document.querySelector(" .volume-slider");
  volumeSlider.addEventListener('click', e => {
    const sliderHeight = parseInt(window.getComputedStyle(volumeSlider).height);
    const sliderHeightBounts = volumeSlider.getBoundingClientRect();
    const sliderTop = sliderHeightBounts.top;
    const mouseYRelative = e.clientY - sliderTop;


    /** @var {Element} $volumeContainer_ */
    const $volumeContainer_ = e.currentTarget.parentNode;


    const newVolumePerc = Math.abs(((sliderHeight - mouseYRelative) / sliderHeight));

    const attrPlayerTarget = $volumeContainer_.getAttribute('data-player-target');


    if (attrPlayerTarget === 'global') {
      const $playerTargets_ = document.querySelectorAll('.audioplayer');
      $playerTargets_.forEach($playerTarget_ => {

        setPlayerVolume($playerTarget_, newVolumePerc);
      })
    } else {

      const $playerTarget_ = document.querySelector(attrPlayerTarget);
      setPlayerVolume($playerTarget_, newVolumePerc);
    }
    document.querySelector(" .volume-percentage").style.height = newVolumePerc * 100 + '%';
    setSliderVolume(newVolumePerc, document.querySelector(" .volume-percentage"))
  }, false);


}).catch((err) => {
  console.log(err);
})
