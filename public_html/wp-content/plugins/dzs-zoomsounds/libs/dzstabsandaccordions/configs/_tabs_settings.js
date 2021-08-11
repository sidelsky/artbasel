exports.default = {
  settings_slideshowTime: '5' // -- in seconds
  , settings_enable_linking: 'off' // -- enable deeplinking on tabs
  , settings_contentHeight: '0'// -- set the fixed tab height
  , settings_scroll_to_start: 'off'// -- scroll to start when a tab menu is clicked
  , generator_is: 'off'//scroll to start when a tab menu is clicked
  , settings_startTab: 'default'// -- the start tab, default or a fixed number
  , design_skin: 'skin-default' // -- skin-default, skin-boxed, skin-melbourne or skin-blue
  , design_transition: 'default' // default, fade or slide
  , design_tabsposition: 'top' // -- set top, right, bottom or left
  , design_tabswidth: 'default' // -- set the tabs width for position left or right, if tabs position top or bottom and this is set to fullwidth, then the tabs will cover all the width
  , design_maxwidth: '4000'
  , settings_makeFunctional: false
  , settings_appendWholeContent: false // -- take the whole tab content and append it into the dzs tabs, this makes complex scripts like sliders still work inside of tabs
  , toggle_breakpoint: '320' //  -- a number at which bellow the tabs will trasform to toggles
  , toggle_type: 'accordion' // -- normally, the  toggles act like accordions, but they can act like traditional toggles if this is set to toggle
  , refresh_tab_height: '0' // -- normally, the  toggles act like accordions, but they can act like traditional toggles if this is set to toggle
  , outer_menu: null // -- normally, the  toggles act like accordions, but they can act like traditional toggles if this is set to toggle
  , action_gotoItem: null // -- set a external javascript action that happens when a item is selected
  , vc_editable: false // -- add some extra classes for the visual composer frontend edit

}