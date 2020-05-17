<?php

namespace Theme\Model;
use App\WordPress\WordPress;

class ImageContent {

   public function __construct($layout)
   {
	   $this->layout = $layout;
   }

   public function getImageContent() {
       $imageContent = $this->layout['image_content']['url'];
       $videoContent = $this->layout['video_content'];
       $portraitVideo = $this->layout['portrait_video'];
       $carousel = $this->layout['carousel'];

	return [
		'layoutName' => 'image_content',
      'image_content' => $imageContent,
      'video_content' => $videoContent,
      'portrait_video' => $portraitVideo,
      'carousel' => $carousel
	];
   }
}