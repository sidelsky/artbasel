<?php
namespace Theme\Model;

use App\WordPress\WordPress;

class Awards {
	
    public function __construct()
    {
		$this->awards = get_field('awards');
	}
	
	private function optimisedAwardsList($awards) {
		
		$optimisedAwardsList = [];

		foreach($awards as $details) {
            
            $optimisedAwardsList[] = [
                'awardsTitle' => $details['award_title'],
                'awardDescription' => $details['award_description']
            ];

        }

		return $optimisedAwardsList;

	}
	
    public function getData() {

		return [
			'awardsList' => $this->optimisedAwardsList($this->awards)
	   ];
	   
    }
 }