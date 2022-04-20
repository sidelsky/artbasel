<?php
namespace Theme\Model;

use App\WordPress\WordPress;

class SubpageHero {

   public function getHero() {

    $subtitle = get_field('subtitle');

      return [
         'title' => get_the_title(),
         //'content' => apply_filters('the_content', get_the_content()),
         'content' => $subtitle,
         'backgroundImage' => get_the_post_thumbnail_url()
      ];
   }
}