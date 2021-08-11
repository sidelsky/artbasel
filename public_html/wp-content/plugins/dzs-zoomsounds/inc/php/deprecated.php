<?php

function dzsap_deprecated_init_slider_structure(){

  global $dzsap;
  include(DZSAP_BASE_PATH . 'inc/php/part-generate-legacy-sliderstructure.php');
  // -- legacy playlist
  $dzsap->itemstructure = dzsap_deprecated_generate_item_structure(); // -- *deprecated

  $dzsap->sliderstructure = preg_replace("/\r|\n/", "", $dzsap->sliderstructure);
}
function dzsap_deprecated_generate_item_structure($pargs = null) {

  global $dzsap;
  // -- generate the item structure for legacy playlist
  $margs = array(
    'generator_type' => 'normal',
    'type' => '',
    'source' => '',
    'sourceogg' => '',
    'waveformbg' => '',
    'waveformprog' => '',
    'thumb' => '',
    'linktomediafile' => '',
    'playfrom' => '',
    'bgimage' => '',
    'extra_html' => '',
    'extra_html_left' => '',
    'extra_html_in_controls_left' => '',
    'extra_html_in_controls_right' => '',
    'menu_artistname' => '',
    'menu_songname' => '',
    'menu_extrahtml' => '',
  );

  if (is_array($pargs) == false) {
    $pargs = array();
  }

  $margs = array_merge($margs, $pargs);


  $lab = 'type';
  $val = $margs[$lab];


  $uploadbtnstring = '<button class="button-secondary action upload_file ">Upload</button>';





  $aux = '';
  if ($margs['generator_type'] != 'onlyitems') {
    $aux = '<div class="item-con">
            <div class="item-delete">x</div>
            <div class="item-duplicate"></div>
        <div class="item-preview" style="">
        </div>
        <div class="item-settings-con">';
  }

  $aux .= '<div class="setting type_all">
            <h4 class="non-underline"><span class="underline">' . esc_html__('Type', DZSAP_ID) . '*</span>&nbsp;&nbsp;&nbsp;<span class="sidenote">select one from below</span></h4>

            <div class="main-feed-chooser select-hidden-metastyle select-hidden-foritemtype">
' . DZSHelpers::generate_select('0-0-' . $lab, array('options' => array('mediafile', 'soundcloud', 'shoutcast', 'youtube', 'audio', 'inline'), 'seekval' => $val, 'class' => 'textinput item-type', 'extraattr' => ' data-label="' . $lab . '"')) . '
                <div class="option-con clearfix">

                    <div class="an-option">
                    <div class="an-title">
                    ' . esc_html__('Media File', DZSAP_ID) . '
                    </div>
                    <div class="an-desc">
                    ' . esc_html__('Link to a media file from your WordPress Media Library.', DZSAP_ID) . '
                    </div>
                    </div>

                    <div class="an-option">
                    <div class="an-title">
                    ' . esc_html__('SoundCloud Sound', DZSAP_ID) . '
                    </div>
                    <div class="an-desc">
                    ' . esc_html__('Stream SoundCloud sounds. Input the full link to the sound in the Source field. '
      . 'You will have to input your SoundCloud API Key into ZoomSounds > Settings.', DZSAP_ID) . '  <a rel="nofollow" href="' . DZSAP_BASE_URL . 'readme/index.html#handbrake" target="_blank" class="">Documentation here</a>.
                    </div>
                    </div>

                    <div class="an-option">
                    <div class="an-title">
                    ' . esc_html__('ShoutCast Radio', DZSAP_ID) . '
                    </div>
                    <div class="an-desc">
                    ' . esc_html__('Insert a shoutcast radio address. It will have to stream in mpeg format. Input the address, example:  ', DZSAP_ID) . ' - https://vimeo.com/<strong>55698309</strong>
                    </div>
                    </div>

                    <div class="an-option">
                    <div class="an-title">
                    ' . esc_html__('YouTube', DZSAP_ID) . '
                    </div>
                    <div class="an-desc">
                    ' . esc_html__('Input the YouTube video id. Warning - will not work on iOS.', DZSAP_ID) . '
                    </div>
                    </div>
                    
                    
                    
                    <div class="an-option">
                    <div class="an-title">
                    
                    ' . esc_html__('Self-Hosted Audio', DZSAP_ID) . '
                    </div>
                    <div class="an-desc">
                    ' . esc_html__('Only mp3 is mandatory. Browsers that cannot decode mp3 will use the included Flash Player backup '
      . '. If you want full html5 player, you must set a ogg sound too.', DZSAP_ID) . '
                    </div>
                    </div>
                    
                    

                    <div class="an-option">
                    <div class="an-title">
                    ' . esc_html__('Inline Content', DZSAP_ID) . '
                    </div>
                    <div class="an-desc">
                    ' . esc_html__('Insert in the <strong>Source</strong> field custom content ( ie. embed from a custom site ).', DZSAP_ID) . '
                    </div>
                    </div>
                </div>
            </div>
        </div>';


  $lab = 'source';
  $val = $margs[$lab];


  $aux .= '<div class="setting type_all type_mediafile_hide">
            <div class="setting-label">' . esc_html__('Source', DZSAP_ID) . '*
                <div class="info-con">
                <div class="info-icon"></div>
                <div class="sidenote">' . esc_html__('Below you will enter your audio file address. If it is a video from YouTube or Vimeo you just need to enter
                the id of the video in the . The ID is the bolded part https://www.youtube.com/watch?v=<strong>j_w4Bi0sq_w</strong>.
                If it is a local video you just need to write its location there or upload it through the Upload button ( .mp3 format ).', DZSAP_ID) . '
                    </div>
                </div>
            </div>
' . DZSHelpers::generate_input_textarea('0-0-' . $lab, array('class' => 'textinput main-source type_all upload-type-audio', 'seekval' => $val, 'extraattr' => ' data-label="' . $lab . '" style="width:160px; height:23px;"')) . $uploadbtnstring . '
        </div>';


  $lab = 'soundcloud_track_id';
  $val = '';

  if (isset($margs[$lab])) {
    $val = $margs[$lab];
  }


  $aux .= '<div class="setting type_soundcloud">
            <div class="setting-label">' . esc_html__('Track ID', DZSAP_ID) . '
            </div>
' . DZSHelpers::generate_input_text('0-0-' . $lab, array('class' => 'textinput ', 'seekval' => $val, 'extraattr' => '')) . '
                <div class="sidenote">' . esc_html__('Only for Private Soundcloud files. Guide on how to get the track_id - ', DZSAP_ID) . ' <a rel="nofollow" href="https://digitalzoomstudio.net/docs/wpzoomsounds/#faq_secret_token">' . esc_html__("here") . '</a>' . '
        </div>
        </div>';


  $lab = 'soundcloud_secret_token';
  $val = '';

  if (isset($margs[$lab])) {
    $val = $margs[$lab];
  }


  $aux .= '<div class="setting type_soundcloud">
            <div class="setting-label">' . esc_html__('Secret Token', DZSAP_ID) . '
            </div>
' . DZSHelpers::generate_input_text('0-0-' . $lab, array('class' => 'textinput ', 'seekval' => $val, 'extraattr' => '')) . '
                <div class="sidenote">' . esc_html__('Only for Private Soundcloud files. Guide on how to get the track_id - ', DZSAP_ID) . ' <a rel="nofollow" href="https://digitalzoomstudio.net/docs/wpzoomsounds/#faq_secret_token">' . esc_html__("here") . '</a>' . '
                    </div>
        </div>';


  $lab = 'sourceogg';
  $val = $margs[$lab];

  $aux .= '<div class="setting type_all type_mediafile_hide">
            <div class="setting-label">HTML5 OGG ' . esc_html__('Format', DZSAP_ID) . '</div>
            <div class="sidenote">' . esc_html__('Optional ogg / ogv file', DZSAP_ID) . ' / ' . esc_html__('Only for the Video or Audio type', DZSAP_ID) . '</div>
' . DZSHelpers::generate_input_text('0-0-' . $lab, array('class' => 'textinput upload-prev', 'seekval' => $val, 'extraattr' => ' data-label="' . $lab . '"')) . $uploadbtnstring . '
        </div>';


  if ($dzsap->mainoptions['skinwave_wave_mode'] != 'canvas') {
    $lab = 'waveformbg';
    $val = $margs[$lab];

    $aux .= '<div class="setting type_all type_mediafile_hide">
            <div class="setting-label">' . esc_html__('WaveForm Background Image', DZSAP_ID) . '</div>
            <div class="sidenote">' . esc_html__('Optional waveform image / ', DZSAP_ID) . ' / ' . esc_html__('Only for skin-wave', DZSAP_ID) . '</div>
' . DZSHelpers::generate_input_text('0-0-' . $lab, array('class' => 'textinput upload-prev', 'seekval' => $val, 'extraattr' => ' data-label="' . $lab . '"')) . $uploadbtnstring . ' <span class="aux-wave-generator"><button class="btn-autogenerate-waveform-bg button-secondary">' . esc_html__("Auto Generate") . '</button></span>
        </div>';


    //simple with upload and wave generator
    $lab = 'waveformprog';
    $val = $margs[$lab];

    $aux .= '<div class="setting type_all type_mediafile_hide">
            <div class="setting-label">' . esc_html__('WaveForm Progress Image', DZSAP_ID) . '</div>
            <div class="sidenote">' . esc_html__('Optional waveform image / ', DZSAP_ID) . ' / ' . esc_html__('Only for skin-wave', DZSAP_ID) . '</div>
' . DZSHelpers::generate_input_text('0-0-' . $lab, array('class' => 'textinput upload-prev', 'seekval' => $val, 'extraattr' => ' data-label="' . $lab . '"')) . $uploadbtnstring . ' <span class="aux-wave-generator"><button class="btn-autogenerate-waveform-prog button-secondary">Auto Generate</button></span>
        </div>';
  }


  $lab = 'linktomediafile';
  $val = $margs[$lab];

  $aux .= '<div class="setting type_all">
            <div class="setting-label">' . esc_html__('Link To Media File', DZSAP_ID) . '</div>
            <div class="sidenote">' . esc_html__('you can link to a media file in order to have comment / rates - just input the id of the media here or ', DZSAP_ID) . '</div>
' . DZSHelpers::generate_input_text('0-0-' . $lab, array('class' => 'textinput type_all upload-type-audio upload-prop-id main-media-file', 'seekval' => $val, 'extraattr' => ' data-label="' . $lab . '"')) . dzsap_misc_generate_upload_btn(array('label' => 'Link')) . '
</div>';


  //textarea special thumb
  $lab = 'thumb';
  $val = $margs[$lab];


  $aux .= '
        <div class="setting type_all ">
            <div class="setting-label">' . esc_html__('Thumbnail', DZSAP_ID) . '</div>
            <div class="sidenote">' . esc_html__('a thumbnail ', DZSAP_ID) . '</div>
' . DZSHelpers::generate_input_textarea('0-0-' . $lab, array('class' => 'textinput main-thumb type_all upload-type-image', 'seekval' => $val, 'extraattr' => ' data-label="' . $lab . '" style="width:160px; height:23px;"')) . $uploadbtnstring . '
</div>';


  //simple with upload and wave generator
  $lab = 'playfrom';
  $val = $margs[$lab];

  $aux .= '<div class="setting type_all">
            <div class="setting-label">' . esc_html__('Play From', DZSAP_ID) . '</div>
            <div class="sidenote">' . esc_html__('choose a number of seconds from which the track to play from ( for example if set "70" then the track will start to play from 1 minute and 10 seconds ) or input "last" for the track to play at the last position where it was.', DZSAP_ID) . '</div>
' . DZSHelpers::generate_input_text('0-0-' . $lab, array('class' => 'textinput upload-prev', 'seekval' => $val, 'extraattr' => ' data-label="' . $lab . '"')) . '
        </div>';


  //simple with upload and wave generator
  $lab = 'bgimage';
  $val = $margs[$lab];

  $aux .= '<div class="setting type_all">
            <div class="setting-label">' . esc_html__('Background Image', DZSAP_ID) . '</div>
            <div class="sidenote">' . esc_html__('optional - choose a background image to appear ( needs a wrapper / read docs )', DZSAP_ID) . '</div>
' . DZSHelpers::generate_input_text('0-0-' . $lab, array('class' => 'textinput upload-prev', 'seekval' => $val, 'extraattr' => ' data-label="' . $lab . '"')) . dzsap_misc_generate_upload_btn(array('label' => esc_html__('Upload', DZSAP_ID))) . '
        </div>';


  $lab = 'play_in_footer_player';
  $val = '';

  $aux .= '<div class="setting type_all">
            <div class="setting-label">' . esc_html__('Play in footer player', DZSAP_ID) . '</div>
            <div class="sidenote">' . esc_html__('optional - play this track in the footer player ( footer player must be setuped on the page ) ', DZSAP_ID) . '</div>
' . DZSHelpers::generate_select('0-0-' . $lab, array('class' => 'textinput  styleme', 'seekval' => $val, 'extraattr' => ' data-label="' . $lab . '"', 'options' => array('off', 'on'))) . '
        </div>';


  $lab = 'enable_download_button';
  $val = '';

  $aux .= '<div class="setting type_all">
            <div class="setting-label">' . esc_html__('Enable Download Button', DZSAP_ID) . '</div>
            <div class="sidenote">' . esc_html__('optional - Enable Download Button for this track', DZSAP_ID) . '</div>
' . DZSHelpers::generate_select('0-0-' . $lab, array('class' => 'textinput  styleme', 'seekval' => $val, 'extraattr' => ' data-label="' . $lab . '"', 'options' => array('off', 'on'))) . '
        </div>';


  $lab = 'download_custom_link';
  $val = '';

  if (isset($margs[$lab])) {

    $val = $margs[$lab];
  }

  $aux .= '<div class="setting type_all">
            <div class="setting-label">' . esc_html__('Custom Link Download', DZSAP_ID) . '</div>
            <div class="sidenote">' . esc_html__('a custom link for the download button - clicknig it will go to this link if set, if it is not set then it will just download the track', DZSAP_ID) . '</div>
' . DZSHelpers::generate_input_text('0-0-' . $lab, array('class' => 'textinput upload-prev', 'seekval' => $val, 'extraattr' => ' data-label="' . $lab . '"')) . '
        </div>';


  $lab = 'songname';
  $val = '';

  if (isset($margs[$lab])) {

    $val = $margs[$lab];
  }

  $aux .= '<div class="setting type_all">
            <div class="setting-label">' . esc_html__('Song name', DZSAP_ID) . '</div>
            <div class="sidenote">' . dzs_esc__(esc_html__('leave blank and zoomsounds will try to auto generate song name from mp3 id3 or from attached file meta. Or you can input %s to force no song name in the player', DZSAP_ID), array('<strong>none</strong>')) . '</div>
' . DZSHelpers::generate_input_text('0-0-' . $lab, array('class' => 'textinput upload-prev', 'seekval' => $val, 'extraattr' => ' data-label="' . $lab . '"')) . '
        </div>';


  $aux .= '<br>';
  $aux .= '<div class="dzstoggle toggle1" rel="">
        <div class="toggle-title" style="">' . esc_html__('Extra HTML Options', DZSAP_ID) . '</div>
        <div class="toggle-content" style="z-index:5;">';

  $aux .= '<img src="https://lh3.googleusercontent.com/JY9Q72y_Wkx4Au0Ijxjf2GCZUblfYbpyjooMaSt90XG9zOjd7vlddxLJTTX7C2UEV5TqBKBsSaFw3Pr8Psafl8XvzWMOzFaxJfndci9idgqFHSnEw9rd5K92tQyAiVqxPO30qznMwqIjIHQTm2hijSLM2S9OqVinEP_TGoKhtmgrCro7NmsNn0-T4N_Mmn3htOFy4o4mMZciif-zVcQ6T0HTB4n2xzI49Sn_s08ekF8DFwcE58n8Dp5LGfQpUeI8nfK8LSv4mKC1TKiewKkOm-YwGy3bhC8BFRsUXBDHd-YtX0y7HV7SfIg9hvA4QRJHBUQPod5YrDIODH7YLQi7HVIceBwyaYPvTAZEZh5oifrCCj61sSZztfjra-WbcxoRoUVrZSssvxLR1lJgH8WpnxdV-1qmDAr-0p7LKhdJM2_4P79SIOIKuYOWaDyx7GQ8CAjco--fhiwbYCxqgCXyGtRjpGYJV6IEKh7UhwEsNnkUAxWB-YoQrtFgoB3Rw4uFRdQCs--YHTeydLCEaAEL5CNwd6j0hh1UDunj1Xj7bmc=w736-h291-no"/>';

  //textarea simple
  $lab = 'extra_html';
  $val = $margs[$lab];


  $aux .= '
       <div class="setting type_all">
                <div class="setting-label">' . esc_html__('Extra HTML', DZSAP_ID) . '</div>
' . DZSHelpers::generate_input_textarea('0-0-' . $lab, array('class' => 'textinput', 'seekval' => $val, 'extraattr' => ' data-label="' . $lab . '" style="width:160px; height:23px;"')) . '
                <div class="sidenote">' . esc_html__('(1) extra html you may want underneath item', DZSAP_ID) . '</div>
</div>';


  $lab = 'extra_html_left';
  $val = $margs[$lab];


  $aux .= '
       <div class="setting type_all">
                <div class="setting-label">' . esc_html__('Extra HTML to the Left', DZSAP_ID) . '</div>
' . DZSHelpers::generate_input_textarea('0-0-' . $lab, array('class' => 'textinput', 'seekval' => $val, 'extraattr' => ' data-label="' . $lab . '" style="width:160px; height:23px;"')) . '
                <div class="sidenote">' . esc_html__('(2) extra html placed in the left of Like button', DZSAP_ID) . '</div>
</div>';


  $lab = 'extra_html_in_controls_left';
  $val = $margs[$lab];


  $aux .= '
       <div class="setting type_all">
                <div class="setting-label">' . esc_html__('Extra HTML in Left Controls', DZSAP_ID) . '</div>
' . DZSHelpers::generate_input_textarea('0-0-' . $lab, array('class' => 'textinput', 'seekval' => $val, 'extraattr' => ' data-label="' . $lab . '" style="width:160px; height:23px;"')) . '
                <div class="sidenote">' . esc_html__('(3) extra html placed in the player&quot;s ', DZSAP_ID) . '</div>
</div>';


  $lab = 'extra_html_in_controls_right';
  $val = $margs[$lab];


  $aux .= '
       <div class="setting type_all">
                <div class="setting-label">' . esc_html__('Extra HTML in Right Controls', DZSAP_ID) . '</div>
' . DZSHelpers::generate_input_textarea('0-0-' . $lab, array('class' => 'textinput', 'seekval' => $val, 'extraattr' => ' data-label="' . $lab . '" style="width:160px; height:23px;"')) . '
                <div class="sidenote">' . esc_html__('(3) extra html placed in the player&quot;s ', DZSAP_ID) . '</div>
</div>';


  $aux .= '</div>
        </div>';


  $aux .= '<div class="dzstoggle toggle1" rel="">
        <div class="toggle-title" style="">' . esc_html__('Menu Options', DZSAP_ID) . '</div>
        <div class="toggle-content">';


  //textarea simple
  $lab = 'menu_artistname';
  $val = $margs[$lab];


  $aux .= '
       <div class="setting type_all">
                <div class="setting-label">' . esc_html__('Artist Name', DZSAP_ID) . '</div>
                <div class="sidenote">' . esc_html__('an artist name if you include this item in a playlist', DZSAP_ID) . '</div>
' . DZSHelpers::generate_input_textarea('0-0-' . $lab, array('class' => 'textinput', 'seekval' => $val, 'extraattr' => ' data-label="' . $lab . '" style="width:160px; height:23px;"')) . '
</div>';


  //textarea simple
  $lab = 'menu_songname';
  $val = $margs[$lab];


  $aux .= '
       <div class="setting type_all">
                <div class="setting-label">' . esc_html__('Song Name', DZSAP_ID) . '</div>
                <div class="sidenote">' . esc_html__('a song name', DZSAP_ID) . '</div>
' . DZSHelpers::generate_input_textarea('0-0-' . $lab, array('class' => 'textinput', 'seekval' => $val, 'extraattr' => ' data-label="' . $lab . '" style="width:160px; height:23px;"')) . '
</div>';
  //textarea simple
  $lab = 'menu_extrahtml';
  $val = $margs[$lab];


  $aux .= '
       <div class="setting type_all">
                <div class="setting-label">' . esc_html__('Extra HTML', DZSAP_ID) . '</div>
                <div class="sidenote">' . esc_html__('extra html you may want in the menu item', DZSAP_ID) . '</div>
' . DZSHelpers::generate_input_textarea('0-0-' . $lab, array('class' => 'textinput', 'seekval' => $val, 'extraattr' => ' data-label="' . $lab . '" style="width:160px; height:23px;"')) . '
</div>';


  $aux .= '</div>
        </div>';


  if ($margs['generator_type'] != 'onlyitems') {
    $aux .= '</div><!--end item-settings-con-->
</div>';
  }


  return preg_replace("/\r|\n/", "", $aux);;
}


