<?php

function wpassist_remove_block_library_css(){
    wp_dequeue_style( 'wp-block-library' );
} 
add_action( 'wp_enqueue_scripts', 'wpassist_remove_block_library_css' );
