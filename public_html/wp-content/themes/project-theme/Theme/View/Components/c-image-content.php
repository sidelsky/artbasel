<div class="l-content__block l-content__block--image-content l-content__block--wide-image">
   <div class="canvas l-content__block--center">

      <?php 
      /**
       * If iFrame
         */
      if( $data['iframe'] ) { ?>
         <div data-id="vimeo-content" class="u-video-aspect-ratio u-video-aspect-ratio--full-width">
          <?= $data['iframe'] ?>
         </div>
      <?php } ?>

      <?php 
      /**
       * If there is a Carousel
         */
      if( $data['carousel'] ) { ?>
            <button class="fullscreenBtn carouselViewButton target" data-id="fullScreenBtn" title="Fullscreen"></button>
            <button class="closefullscreenBtn carouselViewButton target" data-id="closefullscreenBtn" title="Exit fullscreen"></button>
            <div class="owl-carousel owl-image-content-carousel" >
               <?php foreach( $data['carousel'] as $image) { ?> 
                  <figure class="jq-zoom zoom-image" data-id='carousel-content' role="img" aria-label="<?= esc_attr( $image['alt'] ); ?>" style="background-image: url('<?= $image['sizes']['large'] ?>')">
                     <img style="display:none;" src='<?= $image['sizes']['large'] ?>' alt='<?= esc_attr( $image['alt'] ); ?>'/>
                  </figure>
               <?php } ?>
            </div>         
      <?php } ?>
      <?php 
      /**
       * If no video content OR Carousel show a static image
       */
      if( $data['image_content'] && !$data['video_content'] && !$data['carousel'] ) : ?>
         <figure role="img" aria-label="<?= esc_attr( $data['image_content']['alt'] ); ?>" class="c-video-player__cover-image" style="background-image: url('<?= $data['image_content']['url'] ?>')"></figure>	
      <?php endif; ?>

      <?php 
      /**
       * If video content show cover image, play button and video
       */
      if( $data['video_content']) : ?>
         <button class="c-video-player__button" data-id='playBtn'>
            <svg class="c-video-player__play-icon">
               <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#shape-play" viewBox="0 0 32 32"></use>
            </svg>
         </button>
         <figure class="c-video-player__cover-image" role="img" aria-label="<?= esc_attr( $data['image_content']['alt'] ); ?>" style="background-image: url('<?= $data['image_content']['url'] ?>')" data-id='cover'></figure>	
         <div data-id="vimeo-content" class="u-video-aspect-ratio <?= $data['portrait_video'] ? 'u-video-aspect-ratio--portrait' : 'u-video-aspect-ratio--full-width'?>">
            <?= $data['video_content'] ?>
         </div>
      <?php endif; ?>
   </div>
   
</div>

<?php
/**
 * If caption
*/
if($data['image_content']['caption']) { ?>
   <figcaption class="caption caption--no-margin-top"><?= esc_attr( $data['image_content']['caption'] ); ?></figcaption>
<?php } ?>