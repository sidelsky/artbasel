<?php
namespace Theme\Model;

use App\WordPress\WordPress;

class TwoImages {

   public function __construct($layout)
   {
      $this->layout = $layout;
   }

   public function getTwoImages() {
         $images = [];
         $imageItems = $this->layout['two_three_images'];
         $tallerImages = $this->layout['taller_images'];

         //print_r($imageItems);
   
      foreach($imageItems as $image) {
         $images[] = [
            'image' => $image['image']['url'],
         ];
      }

      return [
         'layoutName' => 'two_images_module',
         'items' => $images,
         'tallerImages' => $tallerImages
      ];
   }
}