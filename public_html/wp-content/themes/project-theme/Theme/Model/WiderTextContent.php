<?php

namespace Theme\Model;
use App\WordPress\WordPress;

class WiderTextContent {

   public function __construct($layout)
   {
	   $this->layout = $layout;
   }

   public function getWiderTextContent() {

      $widerTextTitle = $this->layout['wider_text_title'];
      $widerTextContent = $this->layout['wider_text_content'];
      $widerTextAnchor = $this->layout['wider_text_anchor'];

	return [
		'layoutName' => 'wider_text_content',
      'widerTextTitle' => $widerTextTitle,
      'widerTextContent' => $widerTextContent,
      'widerTextAnchor' => $widerTextAnchor,



	];
   }
}
