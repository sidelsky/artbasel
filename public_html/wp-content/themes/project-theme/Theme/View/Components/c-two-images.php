<section class="u-section ">
		<div class="u-l-vertical-padding--medium40">
			
            <?php

               $count = 0;
               $output = '';
               $images = $data['items'];

               if( $data['tallerImages'] ) {
                  $isTall = 'c-two-images__figure--tall-image';
               }

               foreach($images as $image) { 
               $count++;
               
               $output .= '<figure class="c-two-images__figure c-two-images__figure[number_of_items] ' . $isTall . '" style="background-image:url('. $image['image'] .')">';
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
 