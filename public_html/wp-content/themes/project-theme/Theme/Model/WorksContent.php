<?php

namespace Theme\Model;
use App\WordPress\WordPress;

class WorksContent {

   public function __construct($layout)
   {
	   $this->layout = $layout;
   }

   public function getWorksContent() {
       $works_content = $this->layout['works_content'];
       $show_works_filters = $this->layout['show_works_filters'];
       $works_filter_title = $this->layout['works_filter_title'];

	return [
		'layoutName' => 'works_content',
      'works_content' => $works_content,
      'show_works_filters' => $show_works_filters,
      'works_filter_title' => $works_filter_title
	];
   }
}