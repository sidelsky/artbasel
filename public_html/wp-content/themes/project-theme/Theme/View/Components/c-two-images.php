<section class="u-section ">
		<div class="zu-l-vertical-padding--medium40">
			
            <?php

               $count = 0;
               $output = '';
               $images = $data['items'];
               $videos = $data['two_three_video'];

               if( $data['tallerImages'] ) {
                  $isTall = 'c-two-images__figure--tall-image';
               }

               foreach($images as $image) { 

               $count++;
               
               // If Video
               if( $image['video'] ) {
                   $output .= '<figure role="img" aria-label="' .  esc_attr( $data['image_content']['alt'] ) . '" class="c-two-images__figure c-two-images__figure--video">';
                     $output .= '<button class="c-video-player__button" data-id="playBtn"><svg class="c-video-player__play-icon"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#shape-play" viewBox="0 0 32 32"></use></svg></button>';
                     $output .= '<div class="c-two-images__figure-inner" style="background-image:url('. $image['image']['url'] .')" data-id="cover"></div>';
                     $output .= '<div data-id="vimeo-content" class="u-video-aspect-ratio u-video-aspect-ratio--two-videos">' . $image['video'] . '</div>';
                     if( $image['image']['caption'] ) {
                        $output .= '<figcaption class="caption">' . esc_attr( $image['image']['caption'] ) . '</figcaption>';
                     }
                  $output .= '</figure>';
               }
               
               // If image only
               if( !$image['video'] ) {
                  $output .= '<figure role="img" aria-label="' .  esc_attr( $data['image_content']['alt'] ) . '" class="c-two-images__figure c-two-images__figure[number_of_items] ' . $isTall . '">';
                     $output .= '<div class="c-two-images__figure-inner" style="background-image:url('. $image['image']['url'] .')"></div>';
                     if( $image['image']['caption'] ) {
                        $output .= '<figcaption class="caption">' . esc_attr( $image['image']['caption'] ) . '</figcaption>';
                     }
                  $output .= '</figure>';
               }

               }

               // Checks to see if devisable by the number of items
               if($count === 2) {
                  $str = '--two-column';
               } else if ($count === 3 ){
                  $str = '--three-column';
               }

               $string = str_replace('[number_of_items]', $str, $output);
               echo '<div class="c-two-images">' . $string  . '</div>';

            ?>

		</div>
   </section>
