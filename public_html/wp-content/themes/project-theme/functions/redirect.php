<?php
/**
 * @package Redirect to login if not logged in
 * @version 1.0
 *
 * This will check to see if the 'Theme my login' plugin is active and if not it will lock you out from the site by creating a redirect to the home page.
 *
*/
 
/*
function redirect_homepage() {
 
  //Get the home url - Output example: http://www.example.com.
  $url = home_url();
 
  //Lets check to see if the 'Theme my login' plugin is active.
  if ( !function_exists( 'theme_my_login' )  ) {
 
    //If the 'Theme my login' is inactive and if this is not the home or front page let's reditect the user.
    if( ! is_home() && ! is_front_page() ) {
      wp_redirect( $url, 301 );
      exit;
    }
 
  }
 
}
 
add_action( 'template_redirect', 'redirect_homepage' );
 
 ?>