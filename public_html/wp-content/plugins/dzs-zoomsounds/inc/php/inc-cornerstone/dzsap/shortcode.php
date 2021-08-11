<?php

/**
 * Shortcode definition
 */

$class = "element-dzsap  " . $class;




global $dzsap;
$str_items = '';

$margs = array(

    'id'               => '',
    'class'            => '',
    'style'            => '',
    'cat'            => '',
    'extra_content'            => '',
    'mode'    => 'mode-default',
    'skin'    => 'skin-light',
    'post_type'    => 'timeline_items',
    'date_format'    => 'default',
    'desc_length'    => '100',
    'strip_html'    => 'on',
    'strip_shortcodes'    => 'on',
    'order' => 'asc',
    'order_by' => 'date',
);


$margs = array_merge($margs, $atts);






echo $dzsap->classView->shortcode_player($margs);