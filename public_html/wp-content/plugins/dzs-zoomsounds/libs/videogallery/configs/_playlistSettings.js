exports.default_opts = {

  init_on: "init",


  randomise: "off",
  sliderAreaHeight: '300', // -- "300" is default, overwritten by responsive_ratio

  // -- video play options
  autoplay: "off", // -- autoplay ( deprecated )
  autoplayFirstVideo: undefined, // -- autoplay ( deprecated )
  autoplayNext: "on",  // -- play the next video when one finishes
  cueFirstVideo: 'on', // -- load first video

  // -- playlist playing options
  startItem: 'default',
  playorder: "normal", // -- normal or reverse
  loop_playlist: "on", // -- loop the playlist from the beginning when the end has been reached


  // -- navigation params
  menu_position: 'right',
  menuitem_width: "default", // -- *deprecated
  menuitem_height: "default", // -- *deprecated
  menuitem_space: "0", // -- *deprecated
  navigation_direction: "auto", // -- "auto" -> "vertical" / "horizontal"
  navigation_maxHeight: "auto", // -- only for navigation_direction:"vertical" AND menu_position:"top"|"bottom"
  nav_type_outer_max_height: '', // -- enable a scroller if menu height bigger then max_height *deprecated todo: replace with navigation_maxHeight
  nav_type: "thumbs",  // -- "thumbs" or "thumbsandarrows" or "scroller"
  // -- navigation params END
  nav_type_outer_grid: 'dzs-layout--4-cols', // -- four-per-row --- only for navPosition: "top"|"bottom" and navigation_direction: "vertical"


  // -- lightbox suggested params
  modewall_bigwidth: '800',  // -- the mode wall video ( when opened ) dimensions
  modewall_bigheight: '500',

  // -- misc
  logo: '',
  logoLink: '',

  easing_speed: ""
  , transition_type: "slideup"
  , design_skin: '' // -- *deprecated -> use class
  , videoplayersettings: {} // -- array or string from "window.dzsvg_vpconfigs"
  , embedCode: ''
  , php_media_data_retriever: '' // -- this can help get the video meta data for youtube and vimeo
  , design_navigationUseEasing: 'off'
  , settings_enable_linking: 'off' // -- enable deeplinking on video gallery items
  , settings_mode: 'normal' /// -- normal / wall / rotator / rotator3d / slider
  , mode_normal_video_mode: 'auto' // -- auto or "one" ( only one video player )
  , settings_disableVideo: 'off' //disable the video area
  , settings_enableHistory: 'off' // html5 history api for link type items
  , settings_enableHistory_fornormalitems: 'off' // html5 history api for normal items
  , settings_ajax_extraDivs: '' // extra divs to pull in the ajax request
  , settings_separation_mode: 'normal' // -- normal ( no pagination ) or pages or scroll or button
  , settings_separation_pages: []
  , settings_separation_pages_number: '5' //=== the number of items per 'page'
  , settings_menu_overlay: 'off' // -- an overlay to appear over the menu
  , search_field: 'off' // -- an overlay to appear over the menu
  , search_field_con: null // -- an overlay to appear over the menu
  , disable_videoTitle: "off" // -- do not auto set the video title
  , nav_type_auto_scroll: "off" // -- auto scroll nav
  , settings_trigger_resize: '0' // -- a force trigger resize every x ms
  , settings_go_to_next_after_inactivity: '0' // -- go to next track if no action
  , init_all_players_at_init: 'off'
  , settings_secondCon: null
  , settings_outerNav: null
  , extra_class_slider_con: ''
  , menu_description_format: '' // -- (*deprecated) use the new layout builder-- use something like "{{number}}{{menuimage}}{{title}}{{desc}}"
  , masonry_options: {}
}
