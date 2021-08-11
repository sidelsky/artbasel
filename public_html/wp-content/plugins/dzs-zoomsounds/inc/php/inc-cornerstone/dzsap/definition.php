<?php

/**
 * Element Definition
 */


//          extends Cornerstone_Element_Base
class CS_DZSAP {


  public function ui() {
    return array(
      'title' => esc_html__('ZoomSounds Player', 'dzsap'),
      'autofocus' => array(
        'heading' => 'h4.my-first-element-heading',
        'content' => '.dzsap-element'
      ),
      'icon_group' => 'dzsap'
    );
  }

  public function update_build_shortcode_atts($atts) {

    // This allows us to manipulate attributes that will be assigned to the shortcode
    // Here we will inject a background-color into the style attribute which is
    // already present for inline user styles
    if (!isset($atts['style'])) {
      $atts['style'] = '';
    }


    if (isset($atts['background_color'])) {
      $atts['style'] .= ' background-color: ' . $atts['background_color'] . ';';
      unset($atts['background_color']);
    }

    return $atts;

  }

  public function controls() {

    global $dzsap;


    $options_array = array();
    foreach ($dzsap->options_array_player as $lab => $opt) {


      if ($opt['type'] == 'textarea_html') {

        $opt['type'] = 'editor';
      }

      if ($opt['type'] == 'upload') {


        $opt['type'] = 'text';

        $opt['context'] = '<span class="this-is-upload"></span>' . $opt['context'];
      }

      $options_array[$lab] = array(
        'type' => $opt['type'],
        'ui' => array(
          'title' => $opt['title'],
        ),
        'context' => $opt['context'],
      );

      if (isset($opt['sidenote'])) {
        $options_array[$lab]['ui']['tooltip'] = $opt['sidenote'];
      }
      if (isset($opt['default'])) {
        $options_array[$lab]['suggest'] = $opt['default'];
      }
      if (isset($opt['options'])) {
        $options_array[$lab]['options']['choices'] = $opt['options'];
      }
      $options_array[$lab]['capacity'] = 5;
      $options_array[$lab]['extra-ceva'] = 'alceva';
    }


    return $options_array;


  }

  public function render($atts) {

    // This allows us to manipulate attributes that will be assigned to the shortcode
    // Here we will inject a background-color into the style attribute which is
    // already present for inline user styles


  }


}




