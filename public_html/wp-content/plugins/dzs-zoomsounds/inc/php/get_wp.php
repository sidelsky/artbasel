<?php

$absolute_path = __FILE__;


$path_to_wp = dirname(dirname(dirname(dirname(dirname(__FILE__)))));

if(file_exists($path_to_wp.'/wp-load.php')){

}else{
  $path_to_file = explode( 'wp-content', $absolute_path );
  $path_to_wp = $path_to_file[0];
}

// Access WordPress
require_once( $path_to_wp . '/wp-load.php' );