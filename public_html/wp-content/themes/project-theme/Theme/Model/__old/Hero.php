<?php
namespace Theme\Model;
use App\WordPress\WordPress;

class Hero {

   public function __construct()
   {
      
   }

   //app_store_url

   public function getHero() {

	$appStoreUrl = get_field('app_store_url', 'option');
	$playStoreUrl = get_field('play_store_url', 'option');

      return [
         'title' => get_the_title(),
         'content' => apply_filters('the_content', get_the_content()),
         'backgroundImage' => get_the_post_thumbnail_url(),
         'store-icons' => [
            'app-store' => [
            	'title' => 'iOS App Store',
                //'icon' => 'iOS-App-Store-Badge.png',
                'icon' => 'apple_icon.svg',
            	'url' => $appStoreUrl,
            ],
            'play-store' => [
			'title' => 'Google Play Store',
                //'icon' => 'Play-App-Store-Badge.png',
                'icon' => 'googleplay_icon.svg',
            	'url' => $playStoreUrl
            ]
         ]
      ];
   }
}