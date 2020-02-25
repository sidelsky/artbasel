<?php

namespace Theme\Model;
use App\WordPress\WordPress;

class TextContent {

   public function __construct($layout)
   {
	   $this->layout = $layout;
   }

   public function getTextContent() {
       $darkBackground = $this->layout['dark_background'];
       $justifyTextRight = $this->layout['justify_text_right'];
       $textContentTitle = $this->layout['text_content_title'];
       $textContentCopy = $this->layout['text_content_copy'];
       $textContentDate = $this->layout['text_content_date'];
       $textContentLink = $this->layout['text_content_link'];

	return [
		'layoutName' => 'text_content',
        'darkBackground' => $darkBackground,
        'justifyTextRight' => $justifyTextRight,
        'textContentTitle' => $textContentTitle,
        'textContentCopy' => $textContentCopy,
        'textContentDate' => $textContentDate,
        'textContentLink' => $textContentLink
	];
   }
}