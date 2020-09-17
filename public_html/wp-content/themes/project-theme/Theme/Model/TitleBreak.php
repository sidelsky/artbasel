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
		$showGreyBackground = $this->layout['show_grey_background'];
		$showWorksFilters = $this->layout['show_works_filters'];
		
	return [
		'layoutName' => 'title_break',
		'titleBreakTitle' => $titleBreakTitle,
		'showGreyBackground' => $showGreyBackground,
		'showWorksFilters' => $showWorksFilters
	];
   }
}