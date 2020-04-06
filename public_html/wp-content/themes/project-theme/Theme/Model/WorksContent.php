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

	return [
		'layoutName' => 'works_content',
      'works_content' => $works_content
	];
   }
}