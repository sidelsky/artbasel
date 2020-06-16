<section class="u-section ">
		<div class="zu-l-vertical-padding--medium40">
			
            <?php

            print_r($image['image']);

               $count = 0;
               $output = '';
               $images = $data['items'];

               if( $data['tallerImages'] ) {
                  $isTall = 'c-two-images__figure--tall-image';
               }

               foreach($images as $image) { 
               $count++;

               if($image['image']['caption']) {
                  $caption = '<figcaption class="caption">' . esc_attr( $image['image']['caption'] ) . '</figcaption>';
               }

               $output .= '<figure class="c-two-images__figure c-two-images__figure[number_of_items] ' . $isTall . '">';
                  $output .= '<div class="c-two-images__figure-inner" style="background-image:url('. $image['image']['url'] .')">';
                  $output .= '</div>';
                  $output .= $caption;
               $output .= '</figure>';

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
 