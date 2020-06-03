<div class="l-content__block l-content__block--image-content l-content__block--wide-image">
   <div class="canvas l-content__block--center">

      <?php 
      /**
       * If there is a Carousel
         */
      if( $data['carousel'] ) { ?>
            <button class="fullscreenBtn carouselViewButton target" data-id="fullScreenBtn"></button>
            <button class="closefullscreenBtn carouselViewButton target" data-id="closefullscreenBtn"></button>
            <div class="owl-carousel owl-image-content-carousel" >
               <?php foreach( $data['carousel'] as $image) { ?> 

                  <!-- <div class="easyzoom easyzoom--overlay">
                     <a href="https://assets.ey.com/content/dam/ey-sites/ey-com/en_gl/topics/global-review/2019/ey-staff-at-event.jpg">
                        <img src="https://images.pexels.com/photos/161154/stained-glass-spiral-circle-pattern-161154.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=750&w=1260" alt="" class="normal" >
                     </a>
                  </div> -->

                  <div data-id='carousel-content' role="img" aria-label="<?= esc_attr( $image['alt'] ); ?>" class="l-content__block__carousel--background" style="background-image: url('<?= $image['sizes']['large'] ?>')" ></div>
                  <!-- <img src="https://images.pexels.com/photos/161154/stained-glass-spiral-circle-pattern-161154.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=750&w=1260" alt="<?= esc_attr( $image['alt'] ); ?>"> -->
                  <!-- <img src="<?= $image['sizes']['large'] ?>" alt="<?= esc_attr( $image['alt'] ); ?>"> -->
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