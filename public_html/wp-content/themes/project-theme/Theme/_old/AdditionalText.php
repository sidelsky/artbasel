<?php
// namespace Theme\Model;

// use App\WordPress\WordPress;

// class AdditionalText {
	
//     public function __construct()
//     {
// 		$this->additionalText = get_field('text_content');
// 	}
	
// 	private function optimisedAdditionalTextItem($additionalText) {
		
// 		$optimisedAdditionalTextItem = $this->additionalText;

// 		return $optimisedAdditionalTextItem;

// 	}
	
//     public function getData() {

// 		return [
// 			'additionalTextItem' => $this->optimisedAdditionalTextItem($this->additionalText)
// 	   ];
	   
//     }
//  }
namespace Theme\Model;

use App\WordPress\WordPress;
//use \WP_Query;


class AdditionalText {

   public function getHero() {
      return [
         'content' => apply_filters('the_content', get_the_content()),
      ];
   }
}