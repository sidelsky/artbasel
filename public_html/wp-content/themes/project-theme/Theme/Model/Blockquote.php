<?php

namespace Theme\Model;
use App\WordPress\WordPress;

class Blockquote {

   public function __construct($layout)
   {
	   $this->layout = $layout;
   }

   public function getBlockquote() {
	   
      $blockquote = $this->layout['blockquote'];
      $cite = $this->layout['cite'];
      $blockquote_thumbnail = $this->layout['blockquote_thumbnail'];

	return [
		'layoutName' => 'blockquote',
      'blockquote' => $blockquote,
      'cite' => $cite,
      'blockquote_thumbnail' => $blockquote_thumbnail
	];
   }
}