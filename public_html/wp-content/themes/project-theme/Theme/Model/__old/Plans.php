<?php
namespace Theme\Model;

use App\WordPress\WordPress;


class Plans {

   public function __construct($layout)
   {
      $this->layout = $layout;
   }

   public function getPlans() {
	   $pricePlanTitle = $this->layout['price_plan_title'];
	   $pricePlanDescription = $this->layout['price_plan_description'];
	   $acfPlans = $this->layout['plans'];
	   $show = $this->layout['show'];
	   $plans = [];
	   
	   foreach($acfPlans as $plan) {
		   
		   $planDetails = [];

		   foreach($plan['plan_items'] as $item) {
			$planDetails[] = [
				'planDetailTitle' => $item['plan_detail_title'],
				'planDetail' => $item['plan_detail']
			];
		   }

		   $plans[] = [
			'planIcon' 	=> $plan['plan_icon']['url'],
			'planTitle' => $plan['plan_title'],
			'planDescription' => $plan['plan_description'],
			'planDetails' => $planDetails,
			'planPrice' => $plan['plan_price'],
			'planButtonTitle' => $plan['plan_button_title'],
			];
		}

      return [
        'layoutName' => 'price_plans',
		'pricePlanTitle' => $pricePlanTitle,
		'pricePlanDescription' => $pricePlanDescription,
		'showPlans' => $show,
		'plans' => $plans,
      ];
   }
}