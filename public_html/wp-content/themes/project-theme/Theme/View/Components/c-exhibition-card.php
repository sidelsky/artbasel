<?php
   foreach($data['currentExhibitionCard'] as $index => $exhibitionCard) { ?>
   <?php 
   if( $args['operator']($index, $args['index']) ) { ?>
         <div class="<?= $args['smallClass'] ? 'c-online-exhibitions__block--small' : 'c-online-exhibitions__block' ?>" style="background-image: url('<?= $exhibitionCard['currentExhibitionCardImage'] ?>')">
            <div class="canvas">
               <div class="c-online-exhibitions__content <?= $args['altFontClass'] ? 'c-online-exhibitions--alt-font' : ''?>">
                  <h3 class="c-online-exhibitions__artist">
                     <?= $exhibitionCard['currentExhibitionCardArtist'] ?>
                     <?php if( $exhibitionCard['currentExhibitionCardArtist'] ) : ?>
                        <br>
                     <?php endif; ?>
                     <?php if( $exhibitionCard['currentExhibitionCardTitle'] ) : ?>
                        <span class="c-online-exhibitions__title"><?= $exhibitionCard['currentExhibitionCardTitle'] ?></span>
                     <?php endif; ?>
                  </h3>
                     <span class="c-works__href-wrap c-online-exhibitions__href">
                        <a href="<?= $exhibitionCard['currentExhibitionCardLink'] ?>" class="c-works__href">Explore now</a>
                        <svg class="u-icon c-works__icon"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#shape-link-arrow-white" viewBox="0 0 32 32"></use></svg>
                     </span>
               </div>
            </div>
         </div>
   <?php } ?>
<?php } ?>