<?php

namespace Theme\Model;
use App\WordPress\WordPress;

class Magnify {

   public function __construct($layout)
   {
	   $this->layout = $layout;
   }

   public function getMagnify() {
		$magnify_carousel = $this->layout['magnify_carousel'];
    $magnify_carousel_item = $this->layout['magnify_carousel_item'];

	return [
		'layoutName' => 'magnify_carousel',
		'magnify_carousel_item' => $magnify_carousel_item
	];
   }
}
