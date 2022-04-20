<?php
namespace Theme\Model;

use App\WordPress\WordPress;

class ViewingRoom {
	
    public function __construct()
    {   
		$this->viewingRoomDetails = get_field('current_viewing_rooms');
	}
	
	private function optimisedViewingRoom($viewingRoomDetails) {
		
		$optimisedViewingRoom = [];

		foreach($viewingRoomDetails as $viewingRoomDetail) {
            
			$optimisedViewingRoom[] = [
					'currentViewingRoomImage' => $viewingRoomDetail['current_viewing_room_image'],
					'currentViewingRoomPretitle' => $viewingRoomDetail['current_viewing_room_pretitle'],
					'currentViewingRoomTitle' => $viewingRoomDetail['current_viewing_room_title'],
					'currentViewingRoomLinkDescription' => $viewingRoomDetail['current_viewing_room_link_description'],
					'currentViewingRoomLink' => $viewingRoomDetail['current_viewing_room_link']['url']
			];

        }

		return $optimisedViewingRoom;

	}
	
    public function getData() {
		return [
			'currentViewing' => $this->optimisedViewingRoom($this->viewingRoomDetails)
	   ];
    }
 }