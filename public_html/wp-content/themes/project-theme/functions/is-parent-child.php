<?php
    // $pid = The ID of the page we're looking for pages underneath
    function is_tree($pid) {
        // load details about this page      
        global $post;         
        if(is_page()&&($post->post_parent==$pid||is_page($pid))) 
            // we're at the page or at a sub page
            return true;   
        else
            // we're elsewhere 
            return false; 
    };
?>