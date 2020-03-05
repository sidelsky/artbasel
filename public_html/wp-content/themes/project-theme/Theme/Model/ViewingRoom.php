<?php
namespace Theme\Model;

use App\WordPress\WordPress;

class ViewingRoom {
	
    public function __construct()
    {   
        $post_id_dev = 2794;
        $post_id_prod = 3058;
    
        $localhost = 'artbasilvip:8888';
    
        if ($_SERVER['HTTP_HOST'] == $localhost) {
            $post_id = $post_id_dev;
        } else {
            $post_id = $post_id_prod;
        }  

		$this->viewingRoomDetails = get_field('current_viewing_rooms', $post_id);
	}
	
	private function optimisedViewingRoom($viewingRoomDetails) {
		
		$optimisedViewingRoom = [];

		foreach($viewingRoomDetails as $viewingRoomDetail) {
            
            $optimisedViewingRoom[] = [
                'currentViewingRoomImage' => $viewingRoomDetail['current_viewing_room_image']['url'],
                'currentViewingRoomPretitle' => $viewingRoomDetail['current_viewing_room_pretitle'],
                'currentViewingRoomTitle' => $viewingRoomDetail['current_viewing_room_title'],
                'currentViewingRoomLink' => $viewingRoomDetail['current_viewing_room_link']
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