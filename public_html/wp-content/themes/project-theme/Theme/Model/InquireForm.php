<?php
namespace Theme\Model;

use App\WordPress\WordPress;

class InquireForm {

   public function getInquireForm() {
      $id = null;
      $title = null;
      $description = null;
      $ajax = null;
      return [
         'id' => $id,
		   'title' => $title,
		   'description' => $description,
		   'ajax' => $ajax
      ];
   }
   
}