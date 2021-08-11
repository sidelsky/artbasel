<?php

/**
 * Defaults Values
 */









$aux =  array(
	'id'               => '',
	'class'            => '',
	'style'            => '',
    'width' => '100%', // -- the width , leave 100% for responsive
    'height' => '300', // -- force a height
    'config' => '',  // -- player configuration name
    'source' => '', // -- the mp4 source / youtube id / vimeo id
    'mediaid' => '', // -- link to a media element
    'sourceogg' => '', // the ogg source
    'autoplay' => 'off', // autoplay video
    'cuevideo' => 'on',  // autoload video
    'cover' => '',  // cover image
    'type' => 'video', // youtube / vimeo / video
    'cssid' => '', // force an id - leave blank preferably
    'single' => 'on', // leave on
    'loop' => 'off', // -- loop the video on ending
    'logo' => '', // -- optional logo for the video
    'link' => '', // -- a link where the
    'link_label' => esc_html__('Go to Link'),
    'logo_link' => '',
    'extra_classes' => '', // leave blank
    'extra_classes_player' => '', // -- enter a extra css class for the player for example, entering "with-bottom-shadow" will create a shadow underneath the player
    'call_from' => 'from_shortcode_video',
    'title' => 'default', // -- title to appear on the left top
    'description' => 'default', // -- description to appear if the info button is enabled in video player configurations
);




return $aux;