<?php
$arr_off_on = array(
  array(
    'label' => esc_html__("Off", DZSAP_ID),
    'value' => 'off',
  ),
  array(
    'label' => esc_html__("On", DZSAP_ID),
    'value' => 'on',
  ),
);

return array(


  'galleryskin' => array(
    'name' => 'galleryskin',
    'type' => 'select',
    'default' => 'skin-default',
    'jsName' => 'design_skin',
    'canBeEmptyString' => true,
    'category' => 'main',
    'select_type' => 'opener-listbuttons',
    'title' => esc_html__('Gallery Skin', DZSAP_ID),
    'extra_classes' => 'opener-listbuttons-flex-full',
    'sidenote' => esc_html__("select the type of media"),
    'choices' => array(
      array(
        'label' => esc_html__("Wave", DZSAP_ID),
        'value' => 'skin-wave',
      ),
      array(
        'label' => esc_html__("Default", DZSAP_ID),
        'value' => 'skin-default',
      ),
      array(
        'label' => esc_html__("Aura", DZSAP_ID),
        'value' => 'skin-aura',
      ),
    ),
    'choices_html' => array(
      '<span class="option-con"><img src="' . DZSAP_BASE_URL . 'assets/img/galleryskin-wave.jpg"/><span class="option-label">' . esc_html__("Wave", DZSAP_ID) . '</span></span>',
      '<span class="option-con"><img src="' . DZSAP_BASE_URL . 'assets/img/galleryskin-default.jpg"/><span class="option-label">' . esc_html__("Default", DZSAP_ID) . '</span></span>',
      '<span class="option-con"><img src="' . DZSAP_BASE_URL . 'assets/img/galleryskin-aura.jpg"/><span class="option-label">' . esc_html__("Aura", DZSAP_ID) . '</span></span>',
    ),


  ),


  'vpconfig' => array(
    'name' => 'vpconfig',
    'jsName' => 'settings_ap',
    'default' => '',
    'canBeEmpty' => false,
    'title' => esc_html__('Player Configuration', DZSAP_ID),
    'description' => esc_html__('choose the player configuration > edit via zoomsounds > Player Configurations', DZSAP_ID),
    'type' => 'select',
    'category' => 'main',
    'options' => array(),
  ),
  'mode' => array(
    'name' => 'mode',
    'jsName' => 'settings_mode',
    'title' => esc_html__('Mode', DZSAP_ID),
    'description' => esc_html__('choose the gallery mode', DZSAP_ID),
    'type' => 'select',
    'category' => 'main',
    'options' => array(
      array(
        'label' => esc_html__("Default", DZSAP_ID),
        'value' => 'mode-normal',
      ),
      array(
        'label' => esc_html__("Show all", DZSAP_ID),
        'value' => 'mode-showall',
      ),
    ),
  ),
  'settings_navigation_method' => array(
    'name' => 'settings_navigation_method',
    'jsName' => 'navigation_method',
    'default' => 'mouseover',
    'title' => esc_html__('Navigation method', DZSAP_ID),
    'description' => esc_html__('choose how the playlist scrolls', DZSAP_ID),
    'type' => 'select',
    'category' => 'main',
    'options' => array(
      array(
        'label' => esc_html__("On mouse move", DZSAP_ID),
        'value' => 'mouseover',
      ),
      array(
        'label' => esc_html__("No scroll necessary", DZSAP_ID),
        'value' => 'full',
      ),
      array(
        'label' => esc_html__("Normal scroll", DZSAP_ID),
        'value' => 'legacyscroll',
      ),
    ),
    'dependency' => array(
      array(
        'element' => 'term_meta[mode]',
        'value' => array('mode-normal'),
      ),
    ),
  ),
  'settings_mode_showall_show_number' => array(
    'name' => 'settings_mode_showall_show_number',
    'jsName' => 'settings_mode_showall_show_number',
    'default' => 'on',
    'title' => esc_html__('Mode Showall Number', DZSAP_ID),
    'description' => esc_html__('display the number', DZSAP_ID),
    'type' => 'select',
    'category' => 'main',
    'options' => array(
      array(
        'label' => esc_html__("Disable", DZSAP_ID),
        'value' => 'off',
      ),
      array(
        'label' => esc_html__("Enable", DZSAP_ID),
        'value' => 'on',
      ),
    ),
    'dependency' => array(
      array(
        'element' => 'term_meta[mode]',
        'value' => array('mode-showall'),
      ),
    ),
  ),
  'mode_showall_layout' => array(
    'name' => 'mode_showall_layout',
    'jsName' => 'mode_showall_layout',
    'default' => 'one-per-row',
    'canBeEmpty' => false,
    'title' => esc_html__('Mode Showall Layout', DZSAP_ID),
    'description' => esc_html__('Number of items per row', DZSAP_ID),
    'type' => 'select',
    'category' => 'main',
    'options' => array(
      array(
        'label' => esc_html__("Default", DZSAP_ID),
        'value' => '',
      ),
      array(
        'label' => esc_html__("Two per row", DZSAP_ID),
        'value' => 'two-per-row',
      ),
      array(
        'label' => esc_html__("Three per row", DZSAP_ID),
        'value' => 'three-per-row',
      ),
      array(
        'label' => esc_html__("Four per row", DZSAP_ID),
        'value' => 'four-per-row',
      ),
    ),
    'dependency' => array(
      array(
        'element' => 'term_meta[mode]',
        'value' => array('mode-showall'),
      ),
    ),
  ),
  'enable_linking' => array(
    'name' => 'enable_linking',
    'default' => 'off',
    'jsName' => 'settings_enable_linking',
    'title' => esc_html__('Linking', DZSAP_ID),
    'description' => esc_html__('choose the gallery skin', DZSAP_ID),
    'type' => 'select',
    'category' => 'main',
    'options' => $arr_off_on,
  ),
  array(
    'name' => 'orderby',
    'title' => esc_html__('Order by', DZSAP_ID),
    'description' => esc_html__('choose an order', DZSAP_ID),
    'type' => 'select',
    'category' => 'main',
    'default' => 'custom',
    'options' => array(
      array(
        'label' => esc_html__("Custom", DZSAP_ID),
        'value' => 'custom',
      ),
      array(
        'label' => esc_html__("Random", DZSAP_ID),
        'value' => 'rand',
      ),
      array(
        'label' => esc_html__("Ratings score", DZSAP_ID),
        'value' => 'ratings_score',
      ),
      array(
        'label' => esc_html__("Ratings number", DZSAP_ID),
        'value' => 'ratings_number',
      ),
      array(
        'label' => esc_html__("Alphabetical asceding", DZSAP_ID),
        'value' => 'alphabetical_ASC',
      ),
      array(
        'label' => esc_html__("Alphabetical descending", DZSAP_ID),
        'value' => 'alphabetical_desc',
      ),
    ),

  ),
  array(
    'name' => 'gallery_play_in_footer_player',
    'title' => esc_html__('Play target', DZSAP_ID),
    'default' => 'off',
    'description' => esc_html__('choose if players play inline or play in the footer player', DZSAP_ID),
    'type' => 'select',
    'category' => 'main',
    'options' => array(
      array(
        'label' => esc_html__("Inline", DZSAP_ID),
        'value' => 'off',
      ),
      array(
        'label' => esc_html__("Footer / sticky player", DZSAP_ID),
        'value' => 'on',
      ),
    ),
  ),
  array(
    'name' => 'gallery_embed_type',
    'default' => 'off',
    'title' => esc_html__('Show embed playlist', DZSAP_ID),
    'description' => esc_html__('choose if players play inline or play in the footer player', DZSAP_ID),
    'type' => 'select',
    'category' => 'main',
    'options' => array(
      array(
        'label' => esc_html__("Off", DZSAP_ID),
        'value' => 'off',
      ),
      array(
        'label' => esc_html__("Show social and share link", DZSAP_ID),
        'value' => 'on-no-embed',
      ),
      array(
        'label' => esc_html__("Embed playlist code", DZSAP_ID),
        'value' => 'on-with-embed',
      ),
    ),
  ),

  array(
    'name' => 'bgcolor',
    'title' => esc_html__('Background Color', DZSAP_ID),
    'category' => 'appearence',
    'description' => esc_html__('for tag color ', DZSAP_ID),
    'type' => 'color',
  ),
  array(
    'name' => 'disable_player_navigation',
    'title' => esc_html__('Disable Player Navigation', DZSAP_ID),
    'category' => 'appearence',
    'description' => esc_html__('Disable arrows for gallery navigation on the player', DZSAP_ID),
    'type' => 'select',
    'options' => $arr_off_on,
  ),
  array(
    'name' => 'enable_bg_wrapper',
    'title' => esc_html__('Enable background wrapper', DZSAP_ID),
    'category' => 'appearence',
    'description' => dzs_esc__(__('Enable a background wrapper for all the gallery, as seen %shere%s', DZSAP_ID), array('<a href="https://previews.envatousercontent.com/files/242206229/index-gallery.html" target="_blank">', '</a>')),
    'type' => 'select',
    'options' => $arr_off_on,
  ),
  'menuposition' => array(
    'name' => 'menuposition',
    'jsName' => 'design_menu_position',
    'default' => 'bottom',
    'canBeEmpty' => false,
    'title' => esc_html__('Menu Position', DZSAP_ID),
    'description' => esc_html__('Menu Position if the mode allows it', DZSAP_ID),

    'type' => 'select',
    'category' => 'menu',
    'options' => array(
      array(
        'label' => esc_html__("Bottom", DZSAP_ID),
        'value' => 'bottom',
      ),
      array(
        'label' => esc_html__("Top", DZSAP_ID),
        'value' => 'top',
      ),
      array(
        'label' => esc_html__("Hide", DZSAP_ID),
        'value' => 'none',
      ),
    ),
  ),
  'design_menu_state' => array(
    'name' => 'design_menu_state',
    'jsName' => 'design_menu_state',
    'default' => 'open',
    'title' => esc_html__('Menu State', DZSAP_ID),
    'description' => esc_html__('If you set this to closed, you should enable the <strong>Menu State Button</strong> below. ', DZSAP_ID),

    'type' => 'select',
    'category' => 'menu',
    'options' => array(
      array(
        'label' => esc_html__("Open", DZSAP_ID),
        'value' => 'open',
      ),
      array(
        'label' => esc_html__("Closed", DZSAP_ID),
        'value' => 'closed',
      ),

    ),
  ),

  'design_menu_show_player_state_button' => array(
    'name' => 'design_menu_show_player_state_button',
    'jsName' => 'design_menu_show_player_state_button',
    'title' => esc_html__('Menu State Button', DZSAP_ID),
    'description' => esc_html__('If you set this to closed, you should enable the <strong>Menu State Button</strong> below. ', DZSAP_ID),

    'canBeEmpty' => false,
    'default' => 'off',
    'type' => 'select',
    'category' => 'menu',
    'options' => $arr_off_on,
  ),
  array(
    'name' => 'menu_facebook_share',
    'title' => esc_html__('Facebook Share', DZSAP_ID),
    'description' => esc_html__('enable a facebook share button in the menu ', DZSAP_ID),

    'type' => 'select',
    'category' => 'menu',
    'options' => array(
      array(
        'label' => esc_html__("Auto", DZSAP_ID),
        'value' => 'auto',
      ),
      array(
        'label' => esc_html__("Disable", DZSAP_ID),
        'value' => 'off',
      ),
      array(
        'label' => esc_html__("Enable", DZSAP_ID),
        'value' => 'on',
      ),
    ),
  ),
  array(
    'name' => 'menu_like_button',
    'title' => esc_html__('Like button', DZSAP_ID),
    'description' => esc_html__('enable a like button in the menu ', DZSAP_ID),

    'type' => 'select',
    'category' => 'menu',
    'options' => array(
      array(
        'label' => esc_html__("Auto", DZSAP_ID),
        'value' => 'auto',
      ),
      array(
        'label' => esc_html__("Disable", DZSAP_ID),
        'value' => 'off',
      ),
      array(
        'label' => esc_html__("Enable", DZSAP_ID),
        'value' => 'on',
      ),
    ),
  ),
  'design_menu_height' => array(
    'name' => 'design_menu_height',
    'jsName' => 'design_menu_height',
    'default' => '',
    'canBeEmpty' => false,
    'title' => esc_html__('Menu Maximum Height', DZSAP_ID),
    'description' => sprintf(esc_html__('input a height in pixels / or input %s to show all menu items', DZSAP_ID), '<strong>auto</strong>'),


    'type' => 'text',
    'category' => 'menu',

  ),
  'cuefirstmedia' => array(
    'name' => 'cuefirstmedia',
    'jsName' => 'cueFirstMedia',
    'default' => 'on',
    'title' => esc_html__('Cue First media', DZSAP_ID),


    'type' => 'select',
    'category' => 'autoplay',
    'options' => array(
      array(
        'label' => esc_html__("Enable", DZSAP_ID),
        'value' => 'on',
      ),
      array(
        'label' => esc_html__("Disable", DZSAP_ID),
        'value' => 'off',
      ),
    ),
  ),
  'autoplay' => array(
    'name' => 'autoplay',
    'title' => esc_html__('Autoplay', DZSAP_ID),


    'type' => 'select',
    'category' => 'autoplay',
    'default' => 'off',
    'options' => array(
      array(
        'label' => esc_html__("Disable", DZSAP_ID),
        'value' => 'off',
      ),
      array(
        'label' => esc_html__("Enable", DZSAP_ID),
        'value' => 'on',
      ),
    ),
  ),
  'autoplay_next' => array(
    'name' => 'autoplay_next',
    'jsName' => 'autoplayNext',
    'title' => esc_html__('Autoplay next', DZSAP_ID),
    'default' => 'on',


    'canBeEmptyString' => false,
    'type' => 'select',
    'category' => 'autoplay',
    'options' => array(
      array(
        'label' => esc_html__("Enable", DZSAP_ID),
        'value' => 'on',
      ),
      array(
        'label' => esc_html__("Disable", DZSAP_ID),
        'value' => 'off',
      ),
    ),
  ),


  'mode_normal_video_mode' => array(
    'name' => 'mode_normal_video_mode',
    'jsName' => 'mode_normal_video_mode',
    'default' => 'auto',
    'title' => esc_html__('Playlist mode', DZSAP_ID),
    'description' => esc_html__('(beta)', DZSAP_ID) . ' ' . esc_html__('setting this to on will enable autoplay next video on mobile - plus it will only use one player for the playlist', DZSAP_ID),
    'canBeEmptyString' => false,

    'type' => 'select',
    'category' => 'autoplay',
    'options' => array(
      array(
        'label' => esc_html__("Auto", DZSAP_ID),
        'value' => '',
      ),
      array(
        'label' => esc_html__("One player", DZSAP_ID),
        'value' => 'one',
      ),
    ),
  ),

  array(
    'name' => 'enable_views',
    'title' => esc_html__('Enable play count', DZSAP_ID),


    'type' => 'select',
    'category' => 'counters',
    'options' => array(
      array(
        'label' => esc_html__("Disable", DZSAP_ID),
        'value' => 'off',
      ),
      array(
        'label' => esc_html__("Enable", DZSAP_ID),
        'value' => 'on',
      ),
    ),
  ),
  array(
    'name' => 'enable_downloads_counter',
    'title' => esc_html__('Enable downloads counter', DZSAP_ID),


    'type' => 'select',
    'category' => 'counters',
    'options' => array(
      array(
        'label' => esc_html__("Disable", DZSAP_ID),
        'value' => 'off',
      ),
      array(
        'label' => esc_html__("Enable", DZSAP_ID),
        'value' => 'on',
      ),
    ),
  ),
  array(
    'name' => 'enable_likes',
    'title' => esc_html__('Enable like count', DZSAP_ID),


    'type' => 'select',
    'category' => 'counters',
    'options' => array(
      array(
        'label' => esc_html__("Disable", DZSAP_ID),
        'value' => 'off',
      ),
      array(
        'label' => esc_html__("Enable", DZSAP_ID),
        'value' => 'on',
      ),
    ),
  ),
  array(
    'name' => 'enable_rates',
    'title' => esc_html__('Enable rating', DZSAP_ID),


    'type' => 'select',
    'category' => 'counters',
    'options' => array(
      array(
        'label' => esc_html__("Disable", DZSAP_ID),
        'value' => 'off',
      ),
      array(
        'label' => esc_html__("Enable", DZSAP_ID),
        'value' => 'on',
      ),
    ),
  ),


  'embedded' => array(
    'name' => 'embedded',
    'jsName' => 'embedded',
    'default' => 'off',
    'type' => 'non-config',
  ),
  'loop_playlist' => array(
    'name' => 'loop_playlist',
    'jsName' => 'loop_playlist',
    'default' => 'on',
    'type' => 'non-config',
  )
);