<?php
namespace Theme\Model;

use App\WordPress\WordPress;

class Enquiries {
	
    public function __construct()
    {
		$this->enquiries = get_field('enquiries');
	}
	
	private function optimisedEnquiriesList($enquiries) {
		
		$optimisedEnquiriesList = [];

		if( $enquiries ) {

			foreach($enquiries as $details) {
            
				$optimisedEnquiriesList[] = [
					'enquiry' => $details['enquiry'],
				];
	
			}

		}

		return $optimisedEnquiriesList;

	}
	
    public function getData() {

		return [
			'enquiriesList' => $this->optimisedEnquiriesList($this->enquiries)
	   ];
	   
    }
 }