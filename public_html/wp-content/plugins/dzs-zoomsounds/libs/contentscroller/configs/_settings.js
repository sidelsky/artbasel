var default_opts = {
  settings_slideshowTime: '0',  //in seconds
  settings_autoHeight: 'on',  // -- auto height for the page ( default on )
  settings_skin: 'skin-default'
  , settings_autoHeight_proportional: 'off' // -- set proportional height of the image depending on the width
  , settings_autoHeight_proportional_max_height: 500 // -- the height on which shall not be passed
  , design_itemwidth: 'auto'
  , design_itemheight: 'auto'
  , design_arrowsize: 'default' // -- set the left and right arrow size, this is the size of an arrow
  , design_bulletspos: 'default' // --- set the bullets position top, bottom or default ( set by the skin ) or none
  , design_disableArrows: 'default'
  , design_forceitemwidth: ''
  , settings_transition: 'slide' // "slide" or "fade" or "wipeoutandfade" or "testimonials_transition_1"
  , settings_direction: 'horizontal'
  , settings_responsive: 'on'
  , settings_mode: 'normal'// -- normal or onlyoneitem
  , settings_swipe: "on"
  , settings_swipeOnDesktopsToo: "off" // -- if this is on then on desktops the transition will be set to SLIDE
  , settings_centeritems: false
  , settings_slideshow: 'off'
  , settings_lazyLoading: 'off'
  , settings_force_immediate_load: 'off' // -- force immediate load even if there are items to be loaded
  , settings_slideshowDontChangeOnHover: 'on'
  , settings_transition_only_when_loaded: 'off' // -- transition only when the image has fully loaded
  , settings_wait_for_do_transition_call: 'off' // -- [advanced] set this when the transition is actually called from an outer function / api
  , mode_onlyone_autoplay_videos: 'on' // -- autoplay videos when scrolled to the video
  , mode_onlyone_reset_videos_to_0: 'off' // -- on item select , if the item is a video, reset the scrub to 0
  , responsive_720_design_itemwidth: ''


  , nav_type: 'auto' // -- auto or slide
  , settings_onlyone: 'off'// -- show only one item per page
  , per_row: '4'// -- show only one item per page
  , outer_thumbs: '' // -- outer content scroller to control
  , outer_thumbs_keep_same_height: 'off' // -- in case you are going to set the outer thumbs to the right, you can keep same height
};

exports.default_opts = default_opts;
