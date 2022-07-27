<?php

namespace Theme\Model;
use App\WordPress\WordPress;

class FullVideo {

   public function __construct($layout)
   {
	   $this->layout = $layout;
   }

   public function getFullVideo() {
      $fullvideo = $this->layout['fullvideo'];
      $video = $this->layout['video'];
      $videotitle = $this->layout['videotitle'];
      $videodesc = $this->layout['videodesc'];
      $coverimage = $this->layout['coverimage'];

	return [
		'layoutName' => 'fullvideo',
      'video' => $video,
      'videotitle' => $videotitle,
      'videodesc' => $videodesc,
      'coverimage' => $coverimage
    	];
   }
}
