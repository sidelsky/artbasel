<?php
/**
 * player shortcode options
 */

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


return array(


  'playerid' => array(
    'category' => 'developer_options',
    'default' => '',
    'jsName' => 'skinwave_comments_playerid', // -- js option name, if not default
    'type' => 'select',
    'select_type' => '  ',
    'title' => esc_html__('Player id', DZSAP_ID) . ' <strong>Gutenberg</strong> ' . esc_html__('blocks', DZSAP_ID),
    'extra_classes' => '',
    'sidenote' => esc_html__("select the type of media"),
    'choices' => array(
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

  'skinwave_comments_account' => array(
    'category' => 'developer_options',
    'default' => 'none',
    'jsName' => 'skinwave_comments_account', // -- js option name, if not default
    'type' => 'select',
    'select_type' => '  ',
    'title' => esc_html__('Enable legacy', DZSAP_ID) . ' <strong>Gutenberg</strong> ' . esc_html__('blocks', DZSAP_ID),
    'extra_classes' => '',
    'sidenote' => esc_html__("select the type of media"),
    'choices' => array(
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
  'skinwave_comments_avatar' => array(
    'category' => 'developer_options',
    'default' => 'https://www.gravatar.com/avatar/00000000000000000000000000000000?s=20',
    'jsName' => 'skinwave_comments_account', // -- js option name, if not default
    'type' => 'select',
    'select_type' => '  ',
    'title' => esc_html__('Enable legacy', DZSAP_ID) . ' <strong>Gutenberg</strong> ' . esc_html__('blocks', DZSAP_ID),
    'extra_classes' => '',
    'sidenote' => esc_html__("select the type of media"),
    'choices' => array(
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
  'disable_volume' => array(
    'category' => 'developer_options',
    'default' => 'default',
    'jsName' => 'disable_volume', // -- js option name, if not default
    'type' => 'select',
    'select_type' => '  ',
    'title' => esc_html__('Enable legacy', DZSAP_ID) . ' <strong>Gutenberg</strong> ' . esc_html__('blocks', DZSAP_ID),
    'extra_classes' => '',
    'sidenote' => esc_html__("select the type of media"),
    'choices' => array(
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

  'skin_ap' => array(
    'category' => 'developer_options',
    'default' => 'skin-default',
    'jsName' => 'design_skin', // -- js option name, if not default
    'type' => 'select',
    'select_type' => '  ',
    'title' => esc_html__('Enable legacy', DZSAP_ID) . ' <strong>Gutenberg</strong> ' . esc_html__('blocks', DZSAP_ID),
    'extra_classes' => '',
    'sidenote' => esc_html__("select the type of media"),
    'choices' => array(
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
  'player_pause_method' => array(
    'category' => 'developer_options',
    'default' => 'pause',
    'jsName' => 'pause_method', // -- js option name, if not default
    'type' => 'select',
    'select_type' => '  ',
    'title' => esc_html__('Enable legacy', DZSAP_ID) . ' <strong>Gutenberg</strong> ' . esc_html__('blocks', DZSAP_ID),
    'extra_classes' => '',
    'sidenote' => esc_html__("select the type of media"),
    'choices' => array(
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
  'playfrom' => array(
    'category' => 'developer_options',
    'default' => 'off',
    'type' => 'select',
    'select_type' => '  ',
    'title' => esc_html__('Enable legacy', DZSAP_ID) . ' <strong>Gutenberg</strong> ' . esc_html__('blocks', DZSAP_ID),
    'extra_classes' => '',
    'sidenote' => esc_html__("select the type of media"),
    'choices' => array(
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
  'default_volume' => array(
    'category' => 'developer_options',
    'default' => 'default',
    'type' => 'select',
    'select_type' => '  ',
    'title' => esc_html__('Enable legacy', DZSAP_ID) . ' <strong>Gutenberg</strong> ' . esc_html__('blocks', DZSAP_ID),
    'extra_classes' => '',
    'sidenote' => esc_html__("select the type of media"),
    'choices' => array(
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
  'disable_scrubbar' => array(
    'category' => 'developer_options',
    'default' => 'default',
    'type' => 'select',
    'select_type' => '  ',
    'title' => esc_html__('Enable legacy', DZSAP_ID) . ' <strong>Gutenberg</strong> ' . esc_html__('blocks', DZSAP_ID),
    'extra_classes' => '',
    'sidenote' => esc_html__("select the type of media"),
    'choices' => array(
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
  'design_animateplaypause' => array(
    'category' => 'developer_options',
    'default' => 'default',
    'jsName' => 'design_animateplaypause', // -- js option name, if not default
    'type' => 'select',
    'select_type' => '  ',
    'title' => esc_html__('Enable legacy', DZSAP_ID) . ' <strong>Gutenberg</strong> ' . esc_html__('blocks', DZSAP_ID),
    'extra_classes' => '',
    'sidenote' => esc_html__("select the type of media"),
    'choices' => array(
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
  'skinwave_dynamicwaves' => array(
    'category' => 'developer_options',
    'default' => 'off',
    'type' => 'select',
    'select_type' => '  ',
    'title' => esc_html__('Enable legacy', DZSAP_ID) . ' <strong>Gutenberg</strong> ' . esc_html__('blocks', DZSAP_ID),
    'extra_classes' => '',
    'sidenote' => esc_html__("select the type of media"),
    'choices' => array(
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
  'settings_exclude_from_list' => array(
    'category' => 'developer_options',
    'default' => 'off',
    'type' => 'select',
    'select_type' => '  ',
    'title' => esc_html__('Enable legacy', DZSAP_ID) . ' <strong>Gutenberg</strong> ' . esc_html__('blocks', DZSAP_ID),
    'extra_classes' => '',
    'sidenote' => esc_html__("select the type of media"),
    'choices' => array(
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
  'enable_embed_button' => array(
    'category' => 'developer_options',
    'default' => 'off',
    'type' => 'select',
    'select_type' => '  ',
    'title' => esc_html__('Enable legacy', DZSAP_ID) . ' <strong>Gutenberg</strong> ' . esc_html__('blocks', DZSAP_ID),
    'extra_classes' => '',
    'sidenote' => esc_html__("select the type of media"),
    'choices' => array(
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
  'skinwave_enablespectrum' => array(
    'category' => 'developer_options',
    'default' => 'off',
    'jsName' => 'skinwave_enableSpectrum',
    'type' => 'select',
    'select_type' => '  ',
    'title' => esc_html__('Enable legacy', DZSAP_ID) . ' <strong>Gutenberg</strong> ' . esc_html__('blocks', DZSAP_ID),
    'extra_classes' => '',
    'sidenote' => esc_html__("select the type of media"),
    'choices' => array(
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
  'skinwave_enablereflect' => array(
    'category' => 'developer_options',
    'default' => 'off',
    'type' => 'select',
    'select_type' => '  ',
    'title' => esc_html__('Enable legacy', DZSAP_ID) . ' <strong>Gutenberg</strong> ' . esc_html__('blocks', DZSAP_ID),
    'extra_classes' => '',
    'sidenote' => esc_html__("select the type of media"),
    'choices' => array(
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
  'skinwave_mode' => array(
    'category' => 'developer_options',
    'default' => 'normal',
    'type' => 'select',
    'select_type' => '  ',
    'title' => esc_html__('Enable legacy', DZSAP_ID) . ' <strong>Gutenberg</strong> ' . esc_html__('blocks', DZSAP_ID),
    'extra_classes' => '',
    'sidenote' => esc_html__("select the type of media"),
    'choices' => array(
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
  'skinwave_wave_mode' => array(
    'category' => 'developer_options',
    'default' => 'canvas',
    'type' => 'select',
    'select_type' => '  ',
    'title' => esc_html__('Enable legacy', DZSAP_ID) . ' <strong>Gutenberg</strong> ' . esc_html__('blocks', DZSAP_ID),
    'extra_classes' => '',
    'sidenote' => esc_html__("select the type of media"),
    'choices' => array(
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
  'preload_method' => array(
    'category' => 'developer_options',
    'default' => 'metadata',
    'type' => 'select',
    'select_type' => '  ',
    'title' => esc_html__('Enable legacy', DZSAP_ID) . ' <strong>Gutenberg</strong> ' . esc_html__('blocks', DZSAP_ID),
    'extra_classes' => '',
    'sidenote' => esc_html__("select the type of media"),
    'choices' => array(
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
  'skinwave_wave_mode_canvas_mode' => array(
    'category' => 'developer_options',
    'default' => 'normal',
    'type' => 'select',
    'select_type' => '  ',
    'title' => esc_html__('Enable legacy', DZSAP_ID) . ' <strong>Gutenberg</strong> ' . esc_html__('blocks', DZSAP_ID),
    'extra_classes' => '',
    'sidenote' => esc_html__("select the type of media"),
    'choices' => array(
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



















  'player_navigation' => array(
    'category' => 'developer_options',
    'default' => 'default',
    'type' => 'select',
    'select_type' => '  ',
    'title' => esc_html__('Enable legacy', DZSAP_ID) . ' <strong>Gutenberg</strong> ' . esc_html__('blocks', DZSAP_ID),
    'extra_classes' => '',
    'sidenote' => esc_html__("select the type of media"),
    'choices' => array(
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


  'skinwave_timer_static' => array(
    'category' => 'developer_options',
    'default' => 'off',
    'type' => 'select',
    'select_type' => '  ',
    'title' => esc_html__('Enable legacy', DZSAP_ID) . ' <strong>Gutenberg</strong> ' . esc_html__('blocks', DZSAP_ID),
    'extra_classes' => '',
    'sidenote' => esc_html__("select the type of media"),
    'choices' => array(
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
  'design_wave_color_bg' => array(
    'category' => 'developer_options',
    'default' => '',
    'type' => 'select',
    'select_type' => '  ',
    'title' => esc_html__('Enable legacy', DZSAP_ID) . ' <strong>Gutenberg</strong> ' . esc_html__('blocks', DZSAP_ID),
    'extra_classes' => '',
    'sidenote' => esc_html__("select the type of media"),
    'choices' => array(
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
  'design_wave_color_progress' => array(
    'category' => 'developer_options',
    'default' => '',
    'type' => 'select',
    'select_type' => '  ',
    'title' => esc_html__('Enable legacy', DZSAP_ID) . ' <strong>Gutenberg</strong> ' . esc_html__('blocks', DZSAP_ID),
    'extra_classes' => '',
    'sidenote' => esc_html__("select the type of media"),
    'choices' => array(
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
  'skinwave_wave_mode_canvas_waves_number' => array(
    'category' => 'developer_options',
    'default' => '',
    'type' => 'select',
    'select_type' => '  ',
    'title' => esc_html__('Enable legacy', DZSAP_ID) . ' <strong>Gutenberg</strong> ' . esc_html__('blocks', DZSAP_ID),
    'extra_classes' => '',
    'sidenote' => esc_html__("select the type of media"),
    'choices' => array(
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
  'skinwave_wave_mode_canvas_waves_padding' => array(
    'category' => 'developer_options',
    'default' => '',
    'type' => 'select',
    'select_type' => '  ',
    'title' => esc_html__('Enable legacy', DZSAP_ID) . ' <strong>Gutenberg</strong> ' . esc_html__('blocks', DZSAP_ID),
    'extra_classes' => '',
    'sidenote' => esc_html__("select the type of media"),
    'choices' => array(
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
  'skinwave_wave_mode_canvas_reflection_size' => array(
    'category' => 'developer_options',
    'default' => '',
    'type' => 'select',
    'select_type' => '  ',
    'title' => esc_html__('Enable legacy', DZSAP_ID) . ' <strong>Gutenberg</strong> ' . esc_html__('blocks', DZSAP_ID),
    'extra_classes' => '',
    'sidenote' => esc_html__("select the type of media"),
    'choices' => array(
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
  'preview_on_hover' => array(
    'category' => 'developer_options',
    'default' => 'off',
    'type' => 'select',
    'select_type' => '  ',
    'title' => esc_html__('Enable legacy', DZSAP_ID) . ' <strong>Gutenberg</strong> ' . esc_html__('blocks', DZSAP_ID),
    'extra_classes' => '',
    'sidenote' => esc_html__("select the type of media"),
    'choices' => array(
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
  'skinwave_comments_enable' => array(
    'category' => 'developer_options',
    'default' => 'off',
    'type' => 'select',
    'select_type' => '  ',
    'title' => esc_html__('Enable legacy', DZSAP_ID) . ' <strong>Gutenberg</strong> ' . esc_html__('blocks', DZSAP_ID),
    'extra_classes' => '',
    'sidenote' => esc_html__("select the type of media"),
    'choices' => array(
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

  'autoplay' => array(
    'category' => 'developer_options',
    'default' => 'off',
    'type' => 'select',
    'select_type' => '  ',
    'title' => esc_html__('Enable legacy', DZSAP_ID) . ' <strong>Gutenberg</strong> ' . esc_html__('blocks', DZSAP_ID),
    'extra_classes' => '',
    'sidenote' => esc_html__("select the type of media", DZSAP_ID),
    'choices' => array(
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


  'dzsap_meta_loop' => array(
    'name' => 'dzsap_meta_loop',
    'type' => 'select',
    'title' => esc_html__("Loop", DZSAP_ID),
    'sidenote' => esc_html__("loop the song on end", DZSAP_ID),

    'context' => 'content',
    'options' => $arr_off_on,
    'default' => 'off',
    'category' => '',
    'it_is_for' => 'shortcode_generator',
  ),

  'cue' => array(
    'name' => 'cue',
    'type' => 'select',
    'title' => esc_html__("Cue", DZSAP_ID),
    'sidenote' => esc_html__("Cue", DZSAP_ID),

    'context' => 'content',
    'options' => $arr_on_off,
    'default' => 'on',
    'category' => '',
    'it_is_for' => 'shortcode_generator',
  ),
  'embedded' => array(
    'name' => 'embedded',
    'type' => 'select',
    'title' => esc_html__("not for user setting", DZSAP_ID),
    'sidenote' => esc_html__("not for user setting", DZSAP_ID),

    'context' => 'content',
    'options' => $arr_on_off,
    'default' => 'on',
    'category' => '',
    'it_is_for' => 'shortcode_generator',
  ),


);