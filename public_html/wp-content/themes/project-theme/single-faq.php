<?php 
/**
 * Single FAQs
 */

include("header.php"); 

    $args = array(
        'post_type' => 'faq',
        'posts_per_page' => -1,
        'orderby' => 'post_date',
        'order' => 'DEC'
    );
    $loop = new WP_Query( $args );
    while ( $loop->have_posts() ) : $loop->the_post();
        echo 'Pop';
    endwhile; 

include("footer.php"); 