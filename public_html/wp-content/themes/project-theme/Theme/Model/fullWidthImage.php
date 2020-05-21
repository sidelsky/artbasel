<?php

namespace Theme\Model;
use App\WordPress\WordPress;

class FullWidthImage {

   public function __construct($layout)
   {
	   $this->layout = $layout;
   }

   public function getFullWidthImage() {
       $fullWidthImage = $this->layout['full_width_image'];

	return [
		'layoutName' => 'full_width_image',
      'full_width_image' => $fullWidthImage
	];
   }
}