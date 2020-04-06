<?php

namespace Theme\Model;
use App\WordPress\WordPress;

class ImageContent {

   public function __construct($layout)
   {
	   $this->layout = $layout;
   }

   public function getImageContent() {
       $image_content = $this->layout['image_content']['url'];

	return [
		'layoutName' => 'image_content',
      'image_content' => $image_content,
      'wide_image' => $wide_image
	];
   }
}