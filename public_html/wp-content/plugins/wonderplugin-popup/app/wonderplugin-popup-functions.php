<?php

if ( ! defined( 'ABSPATH' ) )
	exit;
	
function wonderplugin_popup_tags_allow( $allowedposttags ) {

	if ( empty($allowedposttags['style']) )
		$allowedposttags['style'] = array();
	
	$allowedposttags['style']['type'] = true;
	$allowedposttags['style']['id'] = true;
	
	if ( empty($allowedposttags['input']) )
		$allowedposttags['input'] = array();

	$allowedposttags['input']['type'] = true;
	$allowedposttags['input']['class'] = true;
	$allowedposttags['input']['id'] = true;
	$allowedposttags['input']['name'] = true;
	$allowedposttags['input']['value'] = true;
	$allowedposttags['input']['size'] = true;
	$allowedposttags['input']['checked'] = true;
	$allowedposttags['input']['placeholder'] = true;
	
	if ( empty($allowedposttags['textarea']) )
		$allowedposttags['textarea'] = array();
	
	$allowedposttags['textarea']['type'] = true;
	$allowedposttags['textarea']['class'] = true;
	$allowedposttags['textarea']['id'] = true;
	$allowedposttags['textarea']['name'] = true;
	$allowedposttags['textarea']['value'] = true;
	$allowedposttags['textarea']['rows'] = true;
	$allowedposttags['textarea']['cols'] = true;
	$allowedposttags['textarea']['placeholder'] = true;
	
	if ( empty($allowedposttags['select']) )
		$allowedposttags['select'] = array();
	
	$allowedposttags['select']['type'] = true;
	$allowedposttags['select']['class'] = true;
	$allowedposttags['select']['id'] = true;
	$allowedposttags['select']['name'] = true;
	$allowedposttags['select']['size'] = true;
	
	if ( empty($allowedposttags['option']) )
		$allowedposttags['option'] = array();
	
	$allowedposttags['option']['value'] = true;
	
	if ( empty($allowedposttags['a']) )
		$allowedposttags['a'] = array();
	
	$allowedposttags['a']['onclick'] = true;
	$allowedposttags['a']['download'] = true;
	$allowedposttags['a']['data'] = true;
	
	if ( empty($allowedposttags['source']) )
		$allowedposttags['source'] = array();
	
	$allowedposttags['source']['src'] = true;
	$allowedposttags['source']['type'] = true;
	
	if ( empty($allowedposttags['iframe']) )
		$allowedposttags['iframe'] = array();
	
	$allowedposttags['iframe']['width'] = true;
	$allowedposttags['iframe']['height'] = true;
	$allowedposttags['iframe']['scrolling'] = true;
	$allowedposttags['iframe']['frameborder'] = true;
	$allowedposttags['iframe']['allow'] = true;
	$allowedposttags['iframe']['src'] = true;
	
	$allowedposttags = apply_filters( 'wonderplugin_popup_custom_tags_allow', $allowedposttags );
	
	return $allowedposttags;
}

function wonderplugin_popup_css_allow($allowed_attr) {

	if ( !is_array($allowed_attr) ) {
		$allowed_attr = array();
	}

	array_push($allowed_attr, 'display', 'position', 'top', 'left', 'bottom', 'right');

	$allowed_attr = apply_filters( 'wonderplugin_popup_custom_css_allow', $allowed_attr );

	return $allowed_attr;
}

function wonderplugin_popup_wp_check_filetype_and_ext($data, $file, $filename, $mimes) {

	$filetype = wp_check_filetype( $filename, $mimes );

	return array(
			'ext'             => $filetype['ext'],
			'type'            => $filetype['type'],
			'proper_filename' => $data['proper_filename']
	);
}