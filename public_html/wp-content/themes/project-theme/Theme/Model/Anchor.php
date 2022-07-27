<?php

namespace Theme\Model;
use App\WordPress\WordPress;

class Anchor {

   public function __construct($layout)
   {
	   $this->layout = $layout;
   }

   public function getAnchor() {
		$anchor = $this->layout['anchor'];
    $anchor_title = $this->layout['anchor_title'];
        $coverimage = $this->layout['coverimage'];
        $video = $this->layout['video'];
        $videotitle = $this->layout['videotitle'];
        $videodesc = $this->layout['videodesc'];

	return [
		'layoutName' => 'anchor',
		'anchor_title' => $anchor_title,
    'video' => $video,
    'videotitle' => $videotitle,
    'videodesc' => $videodesc,
    'coverimage' => $coverimage


	];
   }
}
