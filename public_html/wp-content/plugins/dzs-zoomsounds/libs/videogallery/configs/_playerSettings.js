exports.default_opts = {
  type: 'detect',
  init_on: "init", // -- "init" or "scroll"

  // -- autoplay
  autoplay: "off", // -- autoplay
  autoplayWithVideoMuted: "auto", // -- will attempt to autoplay on mobile too, but with no sound --- "off" , "auto" , "always"

  user_action: "noUserActionYet" // -- says if user action has been made for muting purposes
  , first_video_from_gallery: "off" // -- first video from gallery for autoplay controls
  , old_curr_nr: -1
  , design_scrubbarWidth: 'default'
  , gallery_object: null // -- the gallery which invoked this
  , parent_player: null // -- the player which invoked this
  , design_skin: 'skin_default'
  , design_background_offsetw: 0
  , defaultvolume: 'last', // -- default volume . .set to "last" to remember last volume
  settings_youtube_usecustomskin: 'on',
  settings_ios_usecustomskin: 'on',
  settings_ios_playinline: 'on',
  cueVideo: 'on'
  , preload_method: 'metadata'
  , settings_disableControls: 'off'
  , settings_hideControls: 'off'
  , vimeo_color: 'ffffff'
  , vimeo_title: '1'
  , vimeo_avatar: '1'
  , vimeo_badge: '1'
  , vimeo_byline: '1'
  , mode_normal_video_mode: 'one',
  vimeo_is_chromeless: 'off',

  // -- ad options
  is_ad: 'off',
  ad_link: '',
  ad_show_markers: 'off',  // -- show markers on the scrubbar
  ads_player_mode: 'differentPlayer',  // -- "differentPlayer" or "inlinePlayer"

  // -- quality options
  settings_suggestedQuality: 'hd720', // -- suggested quality for youtube maybe
  settings_currQuality: 'HD',
  design_enableProgScrubBox: 'default'
  , settings_enableTags: 'on'
  , settings_disableVideoArray: 'off' // -- disable the video player list
  , settings_makeFunctional: false
  , settings_video_overlay: "on" // -- an overlay over the video that you can press for pause / unpause
  , settings_big_play_btn: "off" // -- show a big play button centered on video paused
  , video_description_style: "none" // -- choose how Video Description text shows
  , htmlContent: ''
  , extra_controls: '' // a div full of extra html
  , settings_swfPath: 'preview.swf'
  , settings_disable_mouse_out: 'off'  // -- disable the normal mouse-is-out behaviour when mouse leaves the player
  , settings_disable_mouse_out_for_fullscreen: 'off'  // -- disable the normal mouse-is-out behaviour when mouse is in the player / fullscreen
  , controls_fscanvas_bg: '#aaa'
  , controls_fscanvas_hover_bg: '#ddd'
  , touch_play_inline: 'on'
  , google_analytics_send_play_event: 'off'
  , settings_video_end_reset_time: 'on' // -- when the video has ended, reset the time to 0
  , settings_trigger_resize: '0' // -- a force trigger resize every x ms
  , settings_mouse_out_delay: 100
  , settings_mouse_out_delay_for_fullscreen: 1100

  , playfrom: 'default' // play from a index , default means that this will be decided by the data-playfrom
  , settings_subtitle_file: '' // -- set a subtitle file
  , responsive_ratio: 'default' // -- set a responsive ratio height/ratio 0.5 means that the player height will resize to 0.5 of the gallery width
  , action_video_play: null
  , action_video_view: null // -- an external function that you can set to record a view of the video - will be only cast once on play
  , action_video_end: null // -- an external function that you can set to record a view of the video - will be only cast once on play
  , action_video_contor_5secs: null // -- apply a function which fires once per second
  , action_video_contor_10secs: null
  , action_video_contor_60secs: null
  , try_to_pause_zoomsounds_players: 'off'
  , end_exit_fullscreen: 'on' // -- on or off
  , extra_classes: '',
  embed_code: '', // -- the embed code for multisharer
  default_playbackrate: '1' // -- only for self hosted
}
