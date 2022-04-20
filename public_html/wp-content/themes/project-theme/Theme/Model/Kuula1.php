<?php

namespace Theme\Model;
use App\WordPress\WordPress;

class Kuula1 {

   public function __construct($layout)
   {
	   $this->layout = $layout;
   }

   public function getKuula1() {
      $kuula1 = $this->layout['kuula1'];
      $kuula_image1 = $this->layout['kuula_image1'];

	return [
		'layoutName' => 'kuula1',
      'kuula_image1' => $kuula_image1,
	];
   }
}
