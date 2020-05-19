<div class="l-content__block l-content__block--image-content l-content__block--dark-background <?= is_page_template('page-viewing-room.php' || 'page-private-sales-sub.php') ? 'l-content__block--wide-image' : '' ?>">
   <div class="canvas l-content__block--center">

      <?php 
      /**
       * If there is a Carousel
         */
      if( $data['carousel'] ) { ?>
            <button class="fullscreenBtn carouselViewButton" data-id="fullScreenBtn"></button>
            <button class="closefullscreenBtn carouselViewButton" data-id="closefullscreenBtn"></button>
            <div class="owl-carousel owl-image-content-carousel" >
               <?php foreach( $data['carousel'] as $image) { ?> 
                  <div data-id='carousel-content' class="zis-active zl-content__block__carousel l-content__block__carousel--background" style="background-image: url('<?= $image['sizes']['large'] ?>')" ></div>
               <?php } ?>
            </div>         
      <?php } ?>
      
      <?php 
      /**
       * If no video content OR Carousel show a static image
       */
      if( $data['image_content'] && !$data['video_content'] && !$data['carousel'] ) : ?>
         <div class="c-video-player__cover-image" style="background-image: url('<?= $data['image_content'] ?>')"></div>	
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
         <div class="c-video-player__cover-image" style="background-image: url('<?= $data['image_content'] ?>')" data-id='cover'></div>	
         <div class="u-video-aspect-ratio <?= $data['portrait_video'] ? 'u-video-aspect-ratio--portrait' : 'u-video-aspect-ratio--full-width'?>">
            <?= $data['video_content'] ?>
         </div>
      <?php endif; ?>

   </div>
</div>