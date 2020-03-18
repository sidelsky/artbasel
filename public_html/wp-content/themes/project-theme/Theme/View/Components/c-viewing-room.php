<?php

$count = 0;
$output = '';

$post_id_dev2 = 2794;
$post_id_prod2 = 3058;

$localhost = 'artbasilvip:8888';

if ($_SERVER['HTTP_HOST'] == $localhost) {
    $post_id2 = $post_id_dev2;
} else {
    $post_id2 = $post_id_prod2;
}  

    foreach($data['currentViewing'] as $viewingRoom) {
        
        $count++;
        ?>

        <?php
        $output .= '<div class="c-viewing-rooms-header[number_of_items] c-header-background-image" style="background-image: url(' . $viewingRoom['currentViewingRoomImage'] . ')">';
        $output .= '<span class="c-header-background-image__shading" style="background-color: rgba(0,0,0, ' . get_field('image_shading_cover', $post_id2) . ');"></span>';
            $output .= '<div class="canvas parallax-window__content parallax-window__content[number_of_items]" data-id="title">';
                $output .= '<div>';
                    $output .= '<h1 class="c-site-headings c-site-headings--h1--hero c-text-align-centre ">' . $viewingRoom['currentViewingRoomPretitle'] . '</h1>';
                    $output .= '<h2 class="c-site-headings c-site-headings--h1--sub c-site-headings--text-align-center">' . $viewingRoom['currentViewingRoomTitle'] . '</h2>';
                    $output .= '<span class="c-works__href-wrap c-works__href-wrap--center">';

                    if($viewingRoom['currentViewingRoomLinkDescription']) {

                        $output .= '<span class="c-works__href-wrap c-works__href-wrap--center l-content__block--link">';
        
                        if( $viewingRoom['currentViewingRoomLinkDescription'] && !$viewingRoom['currentViewingRoomLink'] ) {
                            $output .= '' . $viewingRoom['currentViewingRoomLinkDescription'] . '';
                        };
        
                        if( $viewingRoom['currentViewingRoomLink'] && $viewingRoom['currentViewingRoomLinkDescription'] ) {
                            $output .= '<a href="' . $viewingRoom['currentViewingRoomLink'] . '" class="c-works__href">' . $viewingRoom['currentViewingRoomLinkDescription'] . '</a> ';
                            $output .= '<svg class="u-icon c-works__icon">';
                                $output .= '<use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#shape-link-arrow-white" viewBox="0 0 32 32"></use>';
                            $output .= '</svg>';
                        };

                        $output .= '</span>';


                    }
                        
                $output .= '</div>';
            $output .= '</div>';
        $output .= '</div>';
        ?>

    <?php } 

    // Checks to see if devisable by the number of items
    if($count === 1) {
        $str = '--one-column';
    } else if ($count === 2 ){
        $str = '--two-column';
    }

    $string = str_replace('[number_of_items]', $str, $output);
    echo '<div class="c-viewing-rooms-header">' . $string  . '</div>';

?>


