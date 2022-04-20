<?php

namespace Theme\Model;
use App\WordPress\WordPress;

class ScrollToNavigationIds {

   public function __construct($layout)
   {
	   $this->layout = $layout;
   }

   public function getScrollToNavigationIds() {
	   
      $scroll_to_navigation_item = $this->layout['scroll_to_navigation_item'];

	return [
		'layoutName' => 'scroll_to_navigation',
      'scroll_to_navigation_item' => $scroll_to_navigation_item
	   ];
   }
}