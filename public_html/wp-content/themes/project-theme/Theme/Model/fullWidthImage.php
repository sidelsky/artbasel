<?php

namespace Theme\Model;
use App\WordPress\WordPress;

class FullWidthImage {

   public function __construct($layout)
   {
	   $this->layout = $layout;
   }

   public function getFullWidthImage() {
       $full_width_image = $this->layout['full_width_image'];
       $full_width_image_title = $this->layout['full_width_image_title'];
       $full_width_image_link = $this->layout['full_width_image_link'];

	return [
		'layoutName' => 'full_width_image',
      'full_width_image' => $full_width_image,
      'full_width_image_title' => $full_width_image_title,
      'full_width_image_link' => $full_width_image_link
   ];
   
   }
}