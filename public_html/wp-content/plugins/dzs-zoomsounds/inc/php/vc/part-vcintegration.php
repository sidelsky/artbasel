<?php






$order_by_arr_opts = array(
  array(
    'label' => esc_html__("Date")
  , 'value' => 'date'
  ),
);
$order_arr_opts = array(
  array(
    'label' => esc_html__("Ascending")
  , 'value' => 'asc'
  ),
  array(
    'label' => esc_html__("Descending")
  , 'value' => 'desc'
  ),
);


$feed_direction_opts = array(
  "normal" => "normal",
  "reverse" => "reverse",
);
$feed_scrollbar_opts = array(
  'off' => 'off',
  'on' => 'on',
);
$feed_breakout_opts = array(
  'off' => 'off',
  'trybreakout' => 'trybreakout',
);
if (function_exists('vc_map')) {


  $params_arr = array();
  $ilab = 0;



  foreach ($this->options_array_player as $lab => $opt) {

    $params_arr[$ilab] = array(
      'type' => $opt['type'],
      'param_name' => $lab,
      'heading' => $opt['title'],
    );

    if (isset($opt['type'])) {
      $params_arr[$ilab]['type'] = $opt['type'];
      if ($opt['type'] == 'select') {
        $params_arr[$ilab]['type'] = 'dropdown';
      }
      if ($opt['type'] == 'text') {
        $params_arr[$ilab]['type'] = 'textfield';
      }
      if ($opt['type'] == 'image') {
        $params_arr[$ilab]['type'] = 'attach_image';
      }
      if ($opt['type'] == 'upload') {
        $params_arr[$ilab]['type'] = 'dzs_add_media_att';
      }
    }
    if (isset($opt['sidenote'])) {
      $params_arr[$ilab]['description'] = $opt['sidenote'];
    }
    if (isset($opt['default'])) {
      $params_arr[$ilab]['std'] = $opt['default'];
      $params_arr[$ilab]['default'] = $opt['default'];
    }
    if (isset($opt['options'])) {
      $params_arr[$ilab]['value'] = $opt['options'];
    }

    if (isset($opt['library_type'])) {
      $params_arr[$ilab]['library_type'] = $opt['library_type'];
    }

    if (isset($opt['class'])) {
      $params_arr[$ilab]['class'] = $opt['class'];
    }

    if (isset($opt['dependency'])) {
      if (isset($opt['dependency'][0])) {

        $params_arr[$ilab]['dependency'] = $opt['dependency'][0];
      } else {

        $params_arr[$ilab]['dependency'] = $opt['dependency'];
      }
    }

    $ilab++;
  }



  vc_map(array(
    "name" => esc_html__("ZoomSounds Player"),
    "base" => "zoomsounds_player",
    "class" => "",

    "category" => esc_html__('Content'),
    "params" => $params_arr
  ));


}

