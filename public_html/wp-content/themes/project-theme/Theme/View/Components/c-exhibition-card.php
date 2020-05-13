<?php
   foreach($data['currentExhibitionCard'] as $index => $exhibitionCard) { ?>
   <?php 
   if( $args['operator']($index, $args['index']) ) { ?>
         <div class="c-online_exhibitions__block<?= $args['smallClass'] ?>" style="background-image: url('<?= $exhibitionCard['currentExhibitionCardImage'] ?>')">
            <div class="canvas">
               <div class="c-online_exhibitions__content">
                  <h3 class="c-online_exhibitions__artist"><?= $exhibitionCard['currentExhibitionCardArtist'] ?></h3>
                  <h4 class="c-online_exhibitions__title"><?= $exhibitionCard['currentExhibitionCardTitle'] ?></h4>
                     <span class="c-works__href-wrap c-online_exhibitions__href">
                        <a href="<?= $exhibitionCard['currentExhibitionCardLink'] ?>" class="c-works__href">Explore now</a>
                        <svg class="u-icon c-works__icon"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#shape-link-arrow-white" viewBox="0 0 32 32"></use></svg>
                     </span>
               </div>
            </div>
         </div>
   <?php } ?>
<?php } ?>