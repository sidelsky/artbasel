<?php

namespace Theme\Model;
use App\WordPress\WordPress;

class Kuula {

   public function __construct($layout)
   {
	   $this->layout = $layout;
   }

   public function getKuula() {
      $kuula = $this->layout['kuula'];
      $kuula_image = $this->layout['kuula_image'];
      $kuula_vr = $this->layout['kuula_vr'];

	return [
		'layoutName' => 'kuula',
      'kuula_image' => $kuula_image,
      'kuula_vr' => $kuula_vr,
	];
   }
}