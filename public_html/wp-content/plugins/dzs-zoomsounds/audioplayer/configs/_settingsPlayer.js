export const defaultPlayerOptions = {
  design_skin: 'skin-default',  // -- the skin of the player - can be set from the html as well,autoplay: 'off' // -- autoplay the track ( only works is cue is set to "on"
  call_from: 'default', // -- call from a specific api
  autoplay_on_scrub_click: 'off',  // -- autoplay the track ( only works is cue is set to "on"
  cueMedia: 'on', // -- this chooses wheter "on" or not "off" a part .. what part is decided by the preload_method below
  preload_method: 'metadata',  // -- "none" or "metadata" or "auto" ( whole track )
  loop: 'off',  // -- loop the track
  pause_method: 'pause',  // -- loop the track
  // -- extra HTML
  settings_extrahtml: '',  // -- some extra html - can be rates, plays, likes
  settings_extrahtml_before_play_pause: '',  // -- some extra html that you may want before play button
  settings_extrahtml_after_play_pause: '',  // -- some extra html that you may want after play button

  settings_trigger_resize: '0',  // -- check the player dimensions every x milli seconds
  design_thumbh: "default",  // -- thumbnail size
  extra_classes_player: '',
  disable_volume: 'default',
  disable_scrub: 'default'
  , disable_timer: 'default' // -- disable timer display
  , disable_player_navigation: 'off'
  , scrub_show_scrub_time: 'on'
  , player_navigation: 'default' // -- auto decide if we need player navigation
  , type: 'audio'
  , enable_embed_button: 'off' // -- enable the embed button
  , embed_code: '' // -- embed code
  , skinwave_dynamicwaves: 'off' // -- dynamic scale based on volume for no spectrum wave
  , soundcloud_apikey: '',  // -- set the sound cloud api key
  skinwave_enableSpectrum: 'off' // -- off or on
  , skinwave_place_thumb_after_volume: 'off' // -- place the thumbnail after volume button
  , skinwave_place_metaartist_after_volume: 'off' // -- place metaartist after volume button
  , php_retriever: 'soundcloudretriever.php', // -- the soundcloud php file used to render soundcloud files,
  skinwave_mode: 'normal',  // -- "normal" or "small" or "alternate"
  skinwave_wave_mode: 'canvas',  // -- "normal" or "canvas" or "line"
  skinwave_wave_mode_canvas_mode: 'normal',  // -- "normal" or "reflecto"
  skinwave_wave_mode_canvas_normalize: 'on' // -- normalize wave to look more natural

  // -- wave settings
  , skinwave_wave_mode_canvas_waves_number: '3' // -- the number of waves in the canvas ( "1" pixel waves, "2" 2 pixel width waves, "3" 3 pixel width waves, "anything more then 3" means number of waves in the container, for example 100 means 100 waves in 1000px container if the container is 1000px width )
  , skinwave_wave_mode_canvas_waves_padding: '1' // -- padding between waves ( "1" 1 pixel between < - > "0" no reflection )
  , skinwave_wave_mode_canvas_reflection_size: '0.25',  // -- the reflection size ( "1" full size < - > "0" no reflection )
  wavesurfer_pcm_length: '200',  // -- define the precision of the wave generation; higher is more precise, but occupies more space

  pcm_data_try_to_generate: 'on',  // -- try to find out the pcm data and sent it via ajax ( maybe send it via php_handler
  pcm_data_try_to_generate_wait_for_real_pcm: 'on',  // -- if set to on, the fake pcm data will not be generated
  pcm_notice: 'off',  // -- show a notice for pcm
  notice_no_media: 'on',  // -- show a notice for when the media errors out

  sampleTimesMode: 'realPrevivew', // -- "realPreview" or "pseudoPreview"
  mobile_delete: "off",  // -- delete the whole player on mobile, useful for unwanted footer players in mobile
  footer_btn_playlist: "off",  // -- construct inner player playlist
  mobile_disable_fakeplayer: "off",  // -- disable feeding to other players on mobile
  skinwave_comments_displayontime: 'on', // -- display the comment when the scrub header is over it
  skinwave_comments_allow_post_if_not_logged_in: 'on', // -- allow posting in comments section if not looged in, to be logged in, skinwave_comments_account must be an account id

  skinwave_comments_links_to: '',  // -- clicking the comments bar will lead to this link ( optional )
  skinwave_comments_enable: 'off',  // -- enable the comments, publisher.php must be in the same folder as this html
  skinwave_comments_mode_outer_selector: '',  // -- the outer selector if it has one
  skinwave_comments_playerid: '',

  skinwave_timer_static: 'off',  // -- Timer indicators are static
  default_volume: 'default',  // -- number / set the default volume 0-1 or "last" for the last known volume
  volume_from_gallery: '' ,// -- the volume set from the gallery item select, leave blank if the player is not called from the gallery
   design_menu_show_player_state_button: 'off', // -- show a button that allows to hide or show the menu
  playfrom: 'off',  // -- off or specific number of settings or set to "last"
  design_animateplaypause: 'default'
  , embedded: 'off',  // // -- if embedded in a iframe
  embedded_iframe_id: '', // // -- if embedded in a iframe, specify the iframe id here
  google_analytics_send_play_event: 'off' // -- send the play event to google analytics, you need to have google analytics script already on your page
  , fakeplayer: null // -- if this is a fake player, it will feed
  , failsafe_repair_media_element: '' // -- some scripts might effect the media element used by zoomsounds, this is how we replace the media element in a certain time
  , action_audio_play: null // -- set a outer play function ( for example for tracking your analytics )
  , action_audio_play2: null // -- set a outer play function ( for example for tracking your analytics )
  , action_audio_pause: null, // -- set a outer play function ( for example for tracking your analytics )
   action_audio_end: null,  // -- set a outer ended function ( for example for tracking your analytics )
  action_audio_comment: null,  // -- set a outer commented function ( for example for tracking your analytics )
  action_received_time_total: null // -- event triggers at receiving time total
  , action_audio_change_media: null,  // -- set a outer on change track function
  action_audio_loaded_metadata: null,  // -- set a outer commented function ( for example for tracking your analytics )
  action_video_contor_60secs: null,  // -- fire every 30s
  type_audio_stop_buffer_on_unfocus: 'off',  // -- if set to on, when the audio player goes out of focus, it will unbuffer the file so that it will not load anymore, useful if you want to stop buffer on large files


  settings_exclude_from_list: 'off',  // -- a audioplayer list is formed at runtime so that when
  design_wave_color_bg: '222222',  // -- waveform background color..  000000,ffffff gradient is allowed
  design_wave_color_progress: 'ea8c52', // -- waveform progress color


  skin_minimal_button_size: '100',
  gallery_gapless_play: 'off', // -- play without pause
  preview_on_hover: 'off',  // -- on mouseenter autoplay the track
  controls_external_scrubbar: '',  // -- on mouseenter autoplay the track
  parentgallery: null, // -- the parent gallery of the player
  scrubbar_type: 'auto', // -- "wave" or "spectrum" or "bar"
  scrubbar_type_wave__has_reveal_animation: 'off',

  settings_php_handler: '', // -- the path of the publisher.php file, this is used to handle comments, likes etc. - can be "wpdefault"
};

// settings_extrahtml -- for plays use <div class="dzsap-counter counter-hits"><i class="fa fa-play"></i><span class="the-number">{{get_plays}}</span></div>
