<?php
/**
 * Header
 */
include("header.php");
global $post;
require('classes/Users.php');
?>

<?php

   $users = new Users();
   //print_r($users->getUsers());
   echo $user->getUsers();
                           

   // $rows = get_field('current_viewing_rooms');

   // if($rows) {

   //    foreach($rows as $row) {
   //       $thumbnail = $row['current_viewing_room_image']['sizes']['medium'];
   //       $title = get_the_title($post->ID);

   //       // echo $thumbnail;
   //       // echo $title;
   //    }

   // }

?>




<?php
/**
 * Footer
 */
include("footer.php"); 
?>