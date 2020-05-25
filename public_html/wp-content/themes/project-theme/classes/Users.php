<?php

class Users {

   public $user;
   public $showContent;

	public function getUsers() {

		// //get users from ACF
		// $users = get_field('users');
		// $showContent = true;
		$showContent = "Paul";

		return $showContent;

	}

}