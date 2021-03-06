<?php
	 /**
		* This themeData file is included into the header.php file
		* @author Errol Sidelsky
		*/
	 $themeData = [
			
			//Copyright Info
			'copyright' => [
				 'details' => '&copy; ' . date('Y') . ' ' . get_bloginfo('title') . ''
			],

			//Title break - Carousel Prev Next
			'titleBreak' => [
				'newWorks' => [
					 'title' => 'New works',
					 'controls' => false
				 ],
				 'explore' => [
					 'title' => 'Explore',
					 'controls' => true
				 ],
				 'privateSales' => [
					 'title' => 'About Private Sales',
					 'controls' => false
				 ]
			],
			
			//Footer Info
			'emailSubCopy'    => [
				'details' => 'Be the first to receive updates',
				'smallPrint' => '*By submitting your email address, you consent to receive our Newsletter. Your consent is revocable at any time by clicking the unsubscribe link in our Newsletter. The Newsletter is sent in accordance with our Privacy Policy and to advertise products and services of Hauser & Wirth Ltd. and its affiliated companies.'
		   ]

	];