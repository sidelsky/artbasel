<?php

namespace Theme\Model;
use App\WordPress\WordPress;

class ArtFairs {

   public function __construct($layout)
   {
	   $this->layout = $layout;
   }

   public function getArtFairs() {
       $art_fairs = $this->layout['art_fairs'];

	return [
		'layoutName' => 'art_fairs',
      'art_fairs' => $art_fairs
	];
   }
}