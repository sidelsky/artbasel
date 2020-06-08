<section class="u-section">
	<div class="u-l-container<?= $data['video_centered_full_width'] ? '--full-width' : '' ?> u-l-vertical-padding--small">

      <div class="c-video-player--centered">


         <button class="c-video-player__button" data-id='playBtn'>
            <svg class="c-video-player__play-icon">
               <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#shape-play" viewBox="0 0 32 32"></use>
            </svg>
         </button>

         <?php 

         if( $data['portrait_video_centered'] ) { ?>

            <figure class="u-video-aspect-ratio--portrait-centered u-video-aspect-ratio--portrait-centered--absolute c-video-player__cover-image " role="img" aria-label="<?= esc_attr( $data['video_centered_image_cover']['alt'] ); ?>" style="background-image: url('<?= $data['video_centered_image_cover']['url'] ?>')" data-id='cover'></figure>	
            <div data-id="vimeo-content" class="u-video-aspect-ratio--portrait-centered">
               <?= $data['video_centered'] ?>
            </div>
        
         <?php } else { ?>

         <figure class="c-video-player__cover-image" role="img" aria-label="<?= esc_attr( $data['video_centered_image_cover']['alt'] ); ?>" style="background-image: url('<?= $data['video_centered_image_cover']['url'] ?>')" data-id='cover'></figure>	
         <div data-id="vimeo-content" class="u-video-aspect-ratio">
            <?= $data['video_centered'] ?>
         </div>

         <?php } ?>
         
      </div>

   </div>
</section>