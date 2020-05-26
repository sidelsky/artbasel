<?php

class Users {

	public $users;
	public $currentUser;
	public $currentUserId;
   public $showContent;

	public function getUsers() {

		//Get the users from the ACP field 
		$users = get_field('users');
		
		//Get the current logged in user
		$currentUser = wp_get_current_user();
		$currentUserId = $currentUser->ID;

		//$showContent = 'No';

		foreach ( $users as $user ) {
			print_r($user);

			// if( in_array($currentUserId, $allAddedUsers) ) {
			// 	$showContent = 'yes';
			// }
		}
		//print_r($allAddedUsers);
	}

}