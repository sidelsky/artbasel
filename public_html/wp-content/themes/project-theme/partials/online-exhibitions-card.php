<?php
   if( isset($onlineExhibitionsCardData) && is_array($onlineExhibitionsCardData) ){

   $args = $onlineExhibitionsCardData;

   } else {
      $args = [];
   }

   $defaults = [
      'isCarousel' => null
   ];

   $args = wp_parse_args( $args, $defaults );

   // this variable to item
   $isCarousel = $args['isCarousel'];

?>
<article class="<?= $isCarousel ? 'c-online-exhibitions__block--small' : 'c-online-exhibitions__block' ?>" style="background-image: url('<?= $thumbnail ?>')">
   <div class="canvas">
      <div class="c-online-exhibitions__content c-online-exhibitions--alt-font">
         <h3 class="c-online-exhibitions__artist">
            <?= $fieldSubTitle ?>
            <?php if( $fieldSubTitle ) : ?>
               <br>
            <?php endif; ?>
            <?php if( $fieldSubTitle ) : ?>
               <span class="c-online-exhibitions__title"><?= $fieldSubTitle ?></span>
            <?php endif; ?>
         </h3>
            <span class="c-works__href-wrap c-online-exhibitions__href">
               <a href="<?= $url ?>" class="c-works__href">Explore now</a>
               <svg class="u-icon c-works__icon"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#shape-link-arrow-white" viewBox="0 0 32 32"></use></svg>
            </span>
      </div>
   </div>
</article>