<?php

/*
Plugin Name: Theme my login page titles
Author: ERS
*/
//
function tml_title( $title, $action ) {
	if ( is_user_logged_in() ) {
		$user = wp_get_current_user();
		if ( 'profile' == $action )
			$title = 'Your Profile';
		else
			$title = sprintf( 'Welcome, %s', $user->display_name );
	} else {
		switch ( $action ) {
			case 'register' :
				$title = 'Sign Up';
				break;
			case 'lostpassword':
			case 'retrievepassword':
			case 'resetpass':
			case 'rp':
				$title = 'Password Recovery';
				break;
			case 'login':
			default:
				$title = 'Sign In';
		}
	}
	return $title;
}
add_filter( 'tml_title', 'tml_title', 11, 2 );
 ?>
