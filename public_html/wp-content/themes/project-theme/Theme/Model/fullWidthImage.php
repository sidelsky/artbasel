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
       $fullWidthImageTitle = $this->layout['full_width_image_title'];
       $fullWidthImageLink = $this->layout['full_width_image_link']['url'];

	return [
		'layoutName' => 'full_width_image',
      'full_width_image' => $fullWidthImage,
      'full_width_image_title' => $fullWidthImageTitle,
      'full_width_image_link' => $fullWidthImageLink
	];
   }
}