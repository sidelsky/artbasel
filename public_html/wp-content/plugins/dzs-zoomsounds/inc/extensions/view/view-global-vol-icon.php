<?php


/**
 * [zoomsounds_global_vol_icon]
 * @param array $atts
 * @return string
 */
function shortcode_zoomsounds_global_vol_icon($atts = array()) {

  if (!is_array($atts)) {
    $atts = array();
  }

  $shortcodeAtts = array_merge(array(
    'playerTarget' => 'global'
  ), $atts);


  $fout = '';

  $fout .= '<div class="volume-container" data-player-target="' . $shortcodeAtts['playerTarget'] . '">
            <div class="volume-button">
              <div class="volume icono-volumeMedium">
                <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px"
                     width="347.182px" height="347.182px" viewBox="0 0 347.182 347.182"
                     style="enable-background:new 0 0 347.182 347.182;"
                     xml:space="preserve">
<g>
		<path d="M210.133,0c-4.948,0-9.233,1.809-12.847,5.426L102.213,100.5H27.412c-4.952,0-9.235,1.809-12.85,5.424
			c-3.618,3.621-5.426,7.901-5.426,12.85v109.634c0,4.948,1.809,9.232,5.426,12.847c3.619,3.617,7.901,5.428,12.85,5.428h74.801
			l95.073,95.077c3.613,3.61,7.898,5.421,12.847,5.421s9.232-1.811,12.854-5.421c3.613-3.617,5.421-7.901,5.421-12.847V18.276
			c0-4.948-1.808-9.235-5.421-12.851C219.362,1.809,215.081,0,210.133,0z"/>
  <path d="M325.904,133.037c-8.09-12.562-18.788-21.414-32.12-26.551c-1.903-0.95-4.278-1.427-7.132-1.427
			c-4.949,0-9.233,1.765-12.847,5.282c-3.614,3.521-5.428,7.853-5.428,12.991c0,3.997,1.143,7.376,3.429,10.136
			c2.286,2.762,5.037,5.142,8.281,7.139c3.231,1.999,6.469,4.189,9.706,6.567c3.237,2.38,5.995,5.758,8.281,10.135
			c2.279,4.377,3.429,9.801,3.429,16.274c0,6.478-1.149,11.899-3.429,16.279c-2.286,4.381-5.044,7.755-8.281,10.141
			c-3.237,2.374-6.475,4.564-9.706,6.563c-3.244,1.995-5.995,4.38-8.281,7.139c-2.286,2.762-3.429,6.139-3.429,10.137
			c0,5.143,1.813,9.465,5.428,12.99c3.613,3.518,7.897,5.28,12.847,5.28c2.854,0,5.229-0.476,7.132-1.424
			c13.332-5.328,24.03-14.229,32.12-26.689c8.097-12.474,12.142-25.933,12.142-40.402
			C338.046,159.124,333.991,145.611,325.904,133.037z"/>
	</g>
</svg>

              </div>
            </div>

            <div class="volume-slider">
              <div class="volume-bg"></div>
              <div class="volume-percentage"></div>
            </div>
          </div>';


  wp_enqueue_style('dzsap-part-global-volume-slider', DZSAP_URL_AUDIOPLAYER . 'parts/player/player-volume-button.css');
  wp_enqueue_script('dzsap-part-global-volume-slider', DZSAP_URL_AUDIOPLAYER . 'parts/player/player-volume-button.js', array(), DZSAP_VERSION);


  return $fout;
}