<?php

namespace Theme\Model;
use App\WordPress\WordPress;

class Magnify {

   public function __construct($layout)
   {
	   $this->layout = $layout;
   }

   public function getMagnify() {
    $rows = $this->layout['magnify_carousel_item'];
    $show_inquire_button = $this->layout['show_inquire_button'];


	return [
		'layoutName' => 'magnify_carousel',
		'magnify_carousel_item' => $magnify_carousel_item,
    'show_inquire_button' => $show_inquire_button,
    'show_magnifying_glass' => $show_magnifying_glass,


    'magnify_carousel_item' => $magnify_carousel_item,
    'magnify_carousel_item' => $magnify_carousel_item,
    'magnify_carousel_item' => $magnify_carousel_item






	];
   }
}
