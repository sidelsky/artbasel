<?php
namespace Theme\Model;

use App\WordPress\WordPress;

class ExhibitionCard {
	
    public function __construct()
    {   
		$this->exhibitionCardDetails = get_field('online_exhibitions');
	}
	
	private function optimisedExhibitionCard($exhibitionCardDetails) {
		
		$optimisedExhibitionCard = [];

		foreach($exhibitionCardDetails as $exhibitionCardDetail) {
            
            $optimisedExhibitionCard[] = [
                'currentExhibitionCardImage' => $exhibitionCardDetail['online_exhibitions_image']['url'],
                'currentExhibitionCardArtist' => $exhibitionCardDetail['online_exhibitions_artist'],
                'currentExhibitionCardTitle' => $exhibitionCardDetail['online_exhibitions_title'],
                'currentExhibitionCardLink' => $exhibitionCardDetail['online_exhibitions_link']['url']
            ];

        }

		return $optimisedExhibitionCard;

	}
	
    public function getData() {
		return [
			'currentExhibitionCard' => $this->optimisedExhibitionCard($this->exhibitionCardDetails)
	   ];
    }
 }