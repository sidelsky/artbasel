<?php




$arr_off_on = array(
  array(
    'label' => esc_html__("Off"),
    'value' => 'off',
  ),
  array(
    'label' => esc_html__("On"),
    'value' => 'on',
  ),
);

$arr_on_off = array(
  array(
    'label' => esc_html__("On"),
    'value' => 'on',
  ),
  array(
    'label' => esc_html__("Off"),
    'value' => 'off',
  ),
);
$arr_default_detect = array(
  array(
    'label' => esc_html__("Default"),
    'value' => 'default',
  ),
  array(
    'label' => esc_html__("Detect"),
    'value' => 'detect',
  ),
);

$types = array(
  array(
    'label' => esc_html__("Auto Detect"),
    'value' => 'detect',
  ),
  array(
    'label' => esc_html__("Audio"),
    'value' => 'audio',
  ),
  array(
    'value' => 'soundcloud',
    'label' => ("Soundcloud"),
  ),
  array(
    'value' => 'shoutcast',
    'label' => esc_html__("Radio Station", 'dzsap'),
  ),
);


$arr_ap_configs = array(
  array(
    'label' => esc_html__("Default", 'dzsap'),
    'value' => 'default',
  ),
);

if (isset($this->mainitems_configs)) {

  foreach ($this->mainitems_configs as $mainItemConfig) {

    $aux = array();
    if (isset($mainItemConfig['settings']['id'])) {
      $aux = array(
        'label' => $mainItemConfig['settings']['id'],
        'value' => $mainItemConfig['settings']['id'],
      );
      array_push($arr_ap_configs, $aux);
    } else {

      error_log(DZSAP_PHP_LOG_LABEL . ' something wrong with $mainItemConfig in options_array_player - ' . print_r($mainItemConfig, true));
    }

  }
}


$arr_wrapper_type = array(
  array(
    'label' => esc_html__("Wide Image Wrapper", 'dzsap'),
    'value' => 'zoomsounds-wrapper-bg-center',
  ),
  array(
    'label' => esc_html__("Rectangle Image Wrapper"),
    'value' => 'zoomsounds-wrapper-bg-bellow',
  ),
);
$dependency_wrapper_type = array(

  array(
    'element' => 'dzsap_meta_wrapper_image',
    'value' => array('anything_but_blank'),
  ),
);


$dependency_content = array(

  array(
    'element' => 'open_in_ultibox',
    'value' => array('on'),
  ),
);
$dependency_download = array(

  array(
    'element' => 'enable_download_button',
    'value' => array('on'),
  ),
);

$optionsForPlayer = array(


  'source' => array(
    'type' => 'upload',
    'library_type' => 'audio',
    'upload_type' => 'upload',
    'class' => '',
    'title' => esc_html__("Source"),
    'sidenote' => esc_html__("The source, input a mp3 or a youtube link"),

    'context' => 'content',
    'default' => '',
    'prefer_id' => 'on',
    'react_type' => 'string',
  ),


  'type' => array(
    'type' => 'select',
    'title' => esc_html__("Type"),
    'sidenote' => sprintf(esc_html__("leave the type to default for the player to decide wheter it is a souncloud link of a self hosted mp3")),
    'react_type' => 'string',


    'context' => 'content',
    'options' => $types,
    'default' => 'normal',
  ),
  'config' => array(
    'type' => 'select',
    'title' => esc_html__("Audio Player Configuration"),
    'holder' => 'div',
    'sidenote' => sprintf(esc_html__("the audio player configuration , can be edited in %s > Player Configurations"), 'ZoomSounds'),

    'context' => 'content',
    'options' => $arr_ap_configs,
    'default' => 'default',
    'react_type' => 'string',
  ),
  'thumb' => array(
    'type' => 'image',
    'title' => esc_html__("Thumbnail"),
    'sidenote' => esc_html__("a thumbnail for the song"),
    'sidenote-2' => sprintf(esc_html__("input %snone%s to force no thumbnail", 'dzsap'), '<strong>', '</strong>'),

    'context' => 'content',
    'default' => '',
    'react_type' => 'string',
  ),
  'cover' => array(
    'type' => 'image',
    'title' => esc_html__("Cover"),
    'sidenote' => esc_html__("cover image to show before video play"),

    'context' => 'content',
    'default' => '',
    'react_type' => 'string',
  ),
  'autoplay' => array(
    'type' => 'select',
    'title' => esc_html__("Autoplay"),
    'sidenote' => esc_html__("autoplay the videos"),

    'context' => 'content',
    'options' => $arr_off_on,
    'default' => 'off',
    'react_type' => 'string',
  ),
  'loop' => array(
    'type' => 'select',
    'title' => esc_html__("Loop"),
    'sidenote' => esc_html__("loop the video on end"),

    'context' => 'content',
    'options' => $arr_off_on,
    'default' => 'off',
    'react_type' => 'string',
  ),
  'extra_classes_player' => array(
    'type' => 'text',
    'title' => esc_html__("Extra Classes to the Player"),
    'sidenote' => esc_html__("enter a extra css class for the player for example, entering \"with-bottom-shadow\" will create a shadow underneath the player"),

    'context' => 'content',
    'default' => '',
  ),
  'artistname' => array(
    'type' => 'text',
    'title' => esc_html__("Artist Title", 'dzsap') . ' ' . sprintf(esc_html__("( line %s )", 'dzsap'), '1'),
    'sidenote' => esc_html__("title to appear on the left top"),

    'context' => 'content',
    'default' => 'default',
  ),
  'songname' => array(
    'type' => 'text',
    'title' => esc_html__("Song Title"),
    'sidenote' => esc_html__("title to appear on the left top"),

    'context' => 'content',
    'default' => 'default',
  ),
  'open_in_ultibox' => array(
    'type' => 'select',
    'title' => esc_html__("Open in Ultibox?"),
    'sidenote' => esc_html__("open the current player in a lightbox"),

    'context' => 'content',
    'options' => $arr_off_on,
    'default' => 'off',
  ),
  'content' => array(
    'type' => 'textarea_html',
    'title' => esc_html__("Content"),
    'sidenote' => esc_html__("description to appear if the info button is enabled in video player configurations"),

    'context' => 'content',
    'default' => '',
    'dependency' => $dependency_content,
  ),
  'playerid' => array(
    'type' => 'text',
    'title' => esc_html__("Link to ID"),
    'sidenote' => esc_html__("you need to link to a player id"),

    'context' => 'content',
    'default' => '',
  ),


  'enable_likes' => array(
    'type' => 'select',
    'title' => esc_html__("Enable Likes ? "),
    'sidenote' => esc_html__("enable like count and button"),
    'sidenote-2' => esc_html__("you need to have a id to link the player to in the database for the views, likes, etc to be recorded"),
    'sidenote-2-class' => 'notice-for-playerid warning',

    'context' => 'content',
    'options' => $arr_off_on,
    'default' => 'off',
  ),


  'enable_views' => array(
    'type' => 'select',
    'title' => esc_html__("Enable Play Count ? ", 'dzsap'),
    'sidenote' => esc_html__("enable play count "),
    'sidenote-2' => esc_html__("you need to have a id to link the player to in the database for the views, likes, etc to be recorded"),
    'sidenote-2-class' => 'notice-for-playerid warning',

    'context' => 'content',
    'options' => $arr_off_on,
    'default' => 'off',
  ),


  // -- download
  'enable_download_button' => array(
    'type' => 'select',
    'title' => esc_html__("Enable Download Button ? "),
    'sidenote' => esc_html__("enable a download button for this item"),
    'sidenote-2' => esc_html__("you need to have a id to link the player to in the database for the views, likes, etc to be recorded"),
    'sidenote-2-class' => 'notice-for-playerid warning',

    'class' => ' dzs-dependency-field',
    'context' => 'content',
    'options' => $arr_off_on,
    'default' => 'off',
  ),
  'enable_downloads_counter' => array(
    'type' => 'select',
    'title' => esc_html__("Enable Downloads Counter ? "),
    'sidenote' => esc_html__("enable a download counter for this item"),

    'class' => ' ',
    'context' => 'content',
    'options' => $arr_off_on,
    'default' => 'off',
  ),
  'download_custom_link' => array(
    'type' => 'text',
    'title' => esc_html__("Download Link"),
    'sidenote' => esc_html__("if no link is set then the button will just download the track"),

    'context' => 'content',
    'default' => '',
    'dependency' => $dependency_download,
  ),
  'download_link_label' => array(
    'type' => 'text',
    'title' => esc_html__("Link Label"),
    'sidenote' => esc_html__("If link button is enabled in the player configurations, then you can set a link here"),

    'context' => 'content',
    'default' => '',
    'dependency' => $dependency_download,
  ),
  // -- download END


  'itunes_link' => array(
    'type' => 'text',
    'title' => esc_html__("iTunes Link"),
    'sidenote' => esc_html__("input an optional link to the itunes track page"),

    'context' => 'content',
    'default' => '',
  ),


  'wrapper_image' => array(
    'type' => 'upload',
    'library_type' => 'image',
    'upload_type' => 'upload',
    'class' => '',
    'title' => esc_html__("Wrapper Image"),
    'sidenote' => esc_html__("The source, input a mp4 or a youtube link or a youtube id or a vimeo link or a vimeo id"),

    'context' => 'content',
    'default' => '',
    'prefer_id' => 'off',
  ),


  'wrapper_image_type' => array(
    'type' => 'select',
    'title' => esc_html__("Wrapper Image Type"),

    'context' => 'content',
    'options' => $arr_wrapper_type,
    'default' => 'off',
    'dependency' => $dependency_wrapper_type,
  ),

  'play_target' => array(
    'type' => 'select',
    'title' => esc_html__("Play Externally ?"),

    'context' => 'content',
    'options' => array(
      array(
        'label' => esc_html__("No"),
        'value' => 'default',
      ),
      array(
        'label' => esc_html__("Play in Footer"),
        'value' => 'footer',
      ),
    ),
    'default' => 'default',
  ),


  'extra_classes' => array(
    'type' => 'text',
    'title' => esc_html__("Extra Classes"),
    'sidenote' => esc_html__("some extra classes"),

    'context' => 'content',
    'default' => '',
  ),


);


if(!isset($dzsap)){
  $dzsap = $this;
}

if (isset($dzsap->mainoptions['aws_key']) && $dzsap->mainoptions['aws_key']) {
  $optionsForPlayer['is_amazon_s3'] =     array(
    'name' => 'is_amazon_s3',
    'type' => 'select',
    'title' => esc_html__("Is amazon s3 file", DZSAP_ID),
    'sidenote' => esc_html__("it's a amazon s3 private file"),

    'category' => 'misc',
    'context' => 'content',
    'options' => array(
      array(
        'label' => esc_html__("no", DZSAP_ID),
        'value' => "off",
      ),
      array(
        'label' => esc_html__("yes", DZSAP_ID),
        'value' => "on",
      ),
    ),
    'default' => 'off',
    'react_type' => 'string',
  );
}



$this->options_array_player = $optionsForPlayer;