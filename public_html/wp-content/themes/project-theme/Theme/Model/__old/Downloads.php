<?php
namespace Theme\Model;

use App\WordPress\WordPress;

class Downloads {
	
    public function __construct()
    {
		$this->devices = get_field('the_device');
	}
	
	private function optimisedDeviceList($devices) {
		
		$optimisedDeviceList = [];

		foreach($devices as $device) {
			
			$downloadDetails = [];

			foreach($device['requirements'] as $requirement) {
				$downloadDetails[] = [
					'requirement' => $requirement['requirement_item'],
				];
			}

			$optimisedDeviceList[] = [
				'deviceName' => $device['device_name'],
				'typeOfDevice' => $device['image_of_device']['url'],
				'deviceFeatureImage' => $device['feature_image']['url'],
				'appStoreLogo' => $device['app_store_logo']['url'],
				'storeUrl' => $device['app_url'],
				'downloadDetails' => $downloadDetails
			];

		}

		return $optimisedDeviceList;

	}
	
    public function getData() {
		
		return [
			'deviceList' => $this->optimisedDeviceList($this->devices)
	   ];
	   
    }
 }