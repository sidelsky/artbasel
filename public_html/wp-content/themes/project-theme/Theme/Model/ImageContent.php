<?php

namespace Theme\Model;
use App\WordPress\WordPress;

class ImageContent {

   public function __construct($layout)
   {
	   $this->layout = $layout;
   }

   public function getImageContent() {
       $imageContent = $this->layout['image_content'];
       $imageContentYT = $this->layout['image_content_yt'];
       $videoContent = $this->layout['video_content'];
       $videoContentYT = $this->layout['video_content_youtube'];
       $portraitVideo = $this->layout['portrait_video'];
       $carousel = $this->layout['carousel'];
       $iframe = $this->layout['iframe'];
       $iframe_html = $this->layout['iframe_html'];

	return [
		'layoutName' => 'image_content',
      'image_content' => $imageContent,
      'image_content_yt' => $imageContentYT,
      'video_content' => $videoContent,
      'video_content_youtube' => $videoContentYT,
      'portrait_video' => $portraitVideo,
      'carousel' => $carousel,
      'iframe' => $iframe,
      'iframe_html' => $iframe_html
	];
   }
}
