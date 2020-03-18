<?php
namespace Theme\Model;

use App\WordPress\WordPress;

class ViewingRoom {
	
    public function __construct()
    {   
        $post_id_devs = 2794;
        $post_id_prods = 3058;
    
        $localhost = 'artbasilvip:8888';
    
        if ($_SERVER['HTTP_HOST'] == $localhost) {
            $post_ids = $post_id_devs;
        } else {
            $post_ids = $post_id_prods;
        }  

		$this->viewingRoomDetails = get_field('current_viewing_rooms', $post_ids);
	}
	
	private function optimisedViewingRoom($viewingRoomDetails) {
		
		$optimisedViewingRoom = [];

		foreach($viewingRoomDetails as $viewingRoomDetail) {
            
            $optimisedViewingRoom[] = [
                'currentViewingRoomImage' => $viewingRoomDetail['current_viewing_room_image']['url'],
                'currentViewingRoomPretitle' => $viewingRoomDetail['current_viewing_room_pretitle'],
                'currentViewingRoomTitle' => $viewingRoomDetail['current_viewing_room_title'],
                'currentViewingRoomLinkDescription' => $viewingRoomDetail['current_viewing_room_link_description'],
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