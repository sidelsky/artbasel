<?php if( have_rows('content_builder') ):
 while ( have_rows('content_builder') ) : the_row();
 $show_inquire_button = get_sub_field('show_inquire_button');
 $show_magnifying_glass = get_sub_field('show_magnifying_glass');
     if( get_row_layout() == 'magnify_carousel' ):
         $rows = get_sub_field('magnify_carousel_item');

     echo '<section class="u-section u-l-vertical-padding--margin-40">';
       echo '<div class="u-l-container ala">';

       echo '<div id="prev-slide" class="c-online-exhibitions__btn-prev"></div>';
       echo '<div id="next-slide" class="c-online-exhibitions__btn-next"></div>';

         if( $rows ) {

           echo '<div class="owl-carousel owl-carousel-magnify owl-theme">';
             foreach( $rows as $row ) {

               $image = $row['magnify_carousel_image']['sizes']['large'];
               $alt = $row['magnify_carousel_image']['alt'];
               $image_magnify = $row['magnify_carousel_image']['sizes']['large'];
               $caption = $row['magnify_carousel_image']['caption'];

               // Get iframe HTML
               $vimeo_iframe = $row['magnify_carousel_video'];

               // Use preg_match to find iframe src
               preg_match('/src="(.+?)"/', $vimeo_iframe, $matches);
               $src = $matches[1];

               // Add extra params to iframe src
               $params = array(
                 'controls'  => 0,
                 'hd'        => 1,
                 'autohide'  => 1,
                 'loop' 		=> 1,
                 'title'		=> 0,
                 'url' 		=> 1
               );

               $new_src = add_query_arg($params, $src);

               $vimeo_iframe = str_replace($src, $new_src, $vimeo_iframe);

               // Add extra attributes to iframe html
               $attributes = 'frameborder="0"';

               $vimeo_iframe = str_replace('></iframe>', ' ' . $attributes . '></iframe>', $vimeo_iframe);

               // Check if magnifying glass is active
               if ( $show_magnifying_glass ) {
                 $zoom = 'zoom';
               }

               if ( $vimeo_iframe ) {

                 echo '<div class="c-video-player--centered " id="video" data-id="video">';
                 echo '<button class="c-video-player__button" data-id="playBtn"><svg class="c-video-player__play-icon"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#shape-play" viewBox="0 0 32 32"></use></svg></button>';
                   echo '<figure class="c-paralax-carousel-cover" data-id="cover">';
                       echo '<img class="c-paralax-carousel-cover__image" src="' . $image . '" alt="' . $alt . '">';
                   echo '</figure>';
                     echo '<div data-id="vimeo-content" class="u-video-aspect-ratio">' . $vimeo_iframe  . '</div>';
                     echo '<figcaption class="caption">' . $caption . '</figcaption>';
                 echo '</div>';

               } else {
                 echo '<figure class="c-magnifying-zoom">';
                   echo '<img src="' . $image . '" class="' . $zoom . ' c-magnifying-zoom__image" data-magnify-src="' . $image_magnify . '">';
                   echo '<figcaption class="caption">' . $caption . '</figcaption>';
                 echo '</figure>';
               }

             }
           echo '</div>';

           if( $show_inquire_button ) {
             $message =  'I am interested in learning more about this piece. Please send me further details about this artwork, pricing, and availability.';
             $idCode = get_the_title();
             echo '<button class="cta-button" data-id="inquire-button" data-message-value="' . $message . '" data-id-code="' . $idCode . '">Inquire</button>';
           }

         }

       echo '</div>';
     echo '</section>';
   endif;
   // End loop.
   endwhile;
   endif;
   // end magnifying glass

   ?>
