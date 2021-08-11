export const default_opts = {
  design_skin: 'skin-default', // -- "skin-default" or "skin-aura" or "skin-wave"
  cueFirstMedia: 'on', // -- preload the mp3 to the first item
  autoplay: 'off', // -- autoplay the first item
  autoplayNext: 'on', // -- autoplay items after the first item
  settings_enable_linking: 'off',  // -- use html5 history to remember last position in the gallery

  design_menu_position: 'bottom',
  navigation_method: 'mouseover', // -- 'mouseover', 'full', 'legacyscroll' or 'dzsscroll'
  design_menu_state: 'open',  // -- options are "open" or "closed", this sets the initial state of the menu, even if the initial state is "closed", it can still be opened by a button if you set design_menu_show_player_state_button to "on"
  design_menu_show_player_state_button: 'off',  // -- show a button that allows to hide or show the menu
  design_menu_width: 'default',
  design_menu_height: 'default',
  design_menu_space: 'default',
  settings_php_handler: '', // -- link to publisher.php ( auto assigned in wordpress to admin-ajax.php )
  design_menuitem_width: 'default',
  design_menuitem_height: 'default',
  design_menuitem_space: 'default',

  disable_menu_navigation: 'off',
  loop_playlist: 'on',
  menu_facebook_share: 'auto' // -- mousemove or scroller or all
  , enable_easing: 'off' // -- enable easing for menu animation
  , settings_ap: 'default' // -- string or array
  , playlistTransition: 'fade' // -- fade or direct
  , embedded: 'off'
  , mode_showall_layout: 'one-per-row' // or "two-per-row" or "three-per-row"
  , settings_mode: 'mode-normal' // -- mode-normal or mode-showall
  , settings_mode_showall_show_number: 'on' // mode-normal or mode-showall
  , mode_normal_video_mode: 'auto' // -- auto or "one" ( only one audio player )

};

