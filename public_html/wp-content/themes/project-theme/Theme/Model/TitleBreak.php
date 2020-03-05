<?php

namespace Theme\Model;
use App\WordPress\WordPress;

class TitleBreak {

   public function __construct($layout)
   {
	   $this->layout = $layout;
   }

   public function getTitleBreak() {
	   
	   $titleBreakTitle = $this->layout['title_break_title'];

	return [
		'layoutName' => 'title_break',
		'titleBreakTitle' => $titleBreakTitle,
	];
   }
}