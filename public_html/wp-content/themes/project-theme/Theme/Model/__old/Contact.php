<?php
namespace Theme\Model;

use App\WordPress\WordPress;

class Contact {
	
    public function __construct()
    {
		$this->contactDetails = get_field('contact_details');
	}
	
	private function optimisedContactList($contactDetails) {
		
		$optimisedContactList = [];

		foreach($contactDetails as $details) {
            
            $optimisedContactList[] = [
                'detailTitle' => $details['detail_title'],
                'detail' => $details['detail']
            ];

        }

		return $optimisedContactList;

	}
	
    public function getData() {

		return [
			'contactList' => $this->optimisedContactList($this->contactDetails)
	   ];
	   
    }
 }