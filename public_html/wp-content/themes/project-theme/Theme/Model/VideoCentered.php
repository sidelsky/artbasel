<?php

namespace Theme\Model;
use App\WordPress\WordPress;

class VideoCentered {

   public function __construct($layout)
   {
	   $this->layout = $layout;
   }

   public function getVideoCentered() {
	   
      $video_centered = $this->layout['video_centered'];
      $video = $this->layout['video_centered'];
      $portrait_video_centered = $this->layout['portrait_video_centered'];
      $video_centered_full_width = $this->layout['video_centered_full_width'];
      $video_centered_image_cover = $this->layout['video_centered_image_cover'];

	return [
		'layoutName' => 'video_centered',
      'video_centered' => $video,
      'portrait_video_centered' => $portrait_video_centered,
      'video_centered_full_width' => $video_centered_full_width,
      'video_centered_image_cover' => $video_centered_image_cover
	];
   }
}