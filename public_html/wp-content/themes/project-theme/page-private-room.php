<?php
/**
 * Template Name: Private Room
 */
include("header.php"); 
?>

<?php

$current_user = wp_get_current_user();
$current_user_id = $current_user->ID;
echo $current_user_id;

$rows = get_field('private_room');
if($rows) {
	echo '<ul>';
	foreach($rows as $row) {
      //print_r( $row );
      $users = $row['private_room_item']['private_room_users'];
      $title = $row['private_room_item']['private_room_item_title'];

      foreach($users as $user) {
         //print_r( $users );
         if( $user['ID'] == $current_user_id ){
            echo $title . '<br>';
         }
      }
      

      
	}
	echo '</ul>';
}

?>


<?php 
	//Fetch users and set our boolean to true per default
	$users = get_field('associated_users');
	$show_content = true;
	if ( !is_user_logged_in() && $users) {
		//If the visitor isn't logged in and we have users they should not be able to see the content
		$show_content = false;
	}elseif( is_user_logged_in() && $users ){
		//Okay so they're logged in and we have users we need to check against
		$current_user = wp_get_current_user();
		//assume they should not see it (just in case)
		$show_content = false;
		foreach( $users as $user ){
			//Loop through each user array from the ACF field
			if( $user['ID'] == $current_user->ID ){
				//Alright they are in the clear! Lets set our boolean to true and break early!
				$show_content = true;
				break;
			}
			
		}
	}
 ?>

<?php if( $show_content ){ ?>

<!-- All content goes in here -->

<?php } ?>

	 
<?php
/**
 * Footer
 */
include("footer.php"); ?>