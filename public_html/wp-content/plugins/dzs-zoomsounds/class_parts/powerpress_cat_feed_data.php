<?php

preg_match_all("/\<rawvoice:subscribe.*?stitcher=\"(.*?)\".*?\<\/raw/", $margs['cat_feed_data'], $output_array);


if (count($output_array[1])) {
  $margs['js_settings_extrahtml_in_float_right'] = str_replace('{{meta1val}}', $output_array[1][0], $margs['js_settings_extrahtml_in_float_right']);

  if (isset($its[0]['settings_extrahtml_after_con_controls'])) {

    $its[0]['settings_extrahtml_after_con_controls'] = str_replace('{{meta1val}}', $output_array[1][0], $its[0]['settings_extrahtml_after_con_controls']);

  }
}


preg_match_all("/\<rawvoice:subscribe.*?itunes=\"(.*?)\".*?\<\/raw/", $margs['cat_feed_data'], $output_array);


if (count($output_array[1])) {
  $margs['js_settings_extrahtml_in_float_right'] = str_replace('{{meta2val}}', $output_array[1][0], $margs['js_settings_extrahtml_in_float_right']);
  $margs['settings_extrahtml_after_con_controls'] = str_replace('{{meta2val}}', $output_array[1][0], $margs['settings_extrahtml_after_con_controls']);

  if (isset($its[0]['settings_extrahtml_after_con_controls'])) {

    $its[0]['settings_extrahtml_after_con_controls'] = str_replace('{{meta2val}}', $output_array[1][0], $its[0]['settings_extrahtml_after_con_controls']);

  }

}


preg_match_all("/\<rawvoice:subscribe.*?feed=\"(.*?)\".*?\<\/raw/", $margs['cat_feed_data'], $output_array);


if (count($output_array[1])) {
  $margs['js_settings_extrahtml_in_float_right'] = str_replace('{{meta3val}}', $output_array[1][0], $margs['js_settings_extrahtml_in_float_right']);

  if (isset($its[0]['settings_extrahtml_after_con_controls'])) {

    $its[0]['settings_extrahtml_after_con_controls'] = str_replace('{{meta3val}}', $output_array[1][0], $its[0]['settings_extrahtml_after_con_controls']);

  }
}


preg_match_all("/\<rawvoice:subscribe.*?googleplay=\"(.*?)\".*?\<\/raw/", $margs['cat_feed_data'], $output_array);


if (count($output_array[1])) {
  $margs['js_settings_extrahtml_in_float_right'] = str_replace('{{meta_googleplay_val}}', $output_array[1][0], $margs['js_settings_extrahtml_in_float_right']);

  if (isset($its[0]['settings_extrahtml_after_con_controls'])) {

    $its[0]['settings_extrahtml_after_con_controls'] = str_replace('{{meta_googleplay_val}}', $output_array[1][0], $its[0]['settings_extrahtml_after_con_controls']);

  }
} else {

  $margs['js_settings_extrahtml_in_float_right'] = str_replace('{{meta_googleplay_val}}', '', $margs['js_settings_extrahtml_in_float_right']);
}


preg_match_all("/\<rawvoice:subscribe.*?tunein=\"(.*?)\".*?\<\/raw/", $margs['cat_feed_data'], $output_array);


if (count($output_array[1])) {
  $margs['js_settings_extrahtml_in_float_right'] = str_replace('{{meta_tunein_val}}', $output_array[1][0], $margs['js_settings_extrahtml_in_float_right']);
  if (isset($its[0]['settings_extrahtml_after_con_controls'])) {

    $its[0]['settings_extrahtml_after_con_controls'] = str_replace('{{meta_tunein_val}}', $output_array[1][0], $its[0]['settings_extrahtml_after_con_controls']);

  }

} else {

  $margs['js_settings_extrahtml_in_float_right'] = str_replace('{{meta_tunein_val}}', '', $margs['js_settings_extrahtml_in_float_right']);
}
