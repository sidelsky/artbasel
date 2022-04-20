<?php

namespace Theme\Model;

use App\WordPress\WordPress;

class CampaignBanner {

   public function __construct($layout)
   {
      $this->layout = $layout;
   }

   public function getCampaignBanner() {
    $campaignTitle = $this->layout['campaign_title'];
    $campaignLink = $this->layout['campaign_link'];
    $show = $this->layout['show'];

      return [
        'layoutName' => 'campaign_banner',
        'title' => $campaignTitle,
        'link' => $campaignLink,
        'showCampaignBanner' => $show
      ];
   }
}