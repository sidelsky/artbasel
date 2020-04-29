<figure class="l-content__block l-content__block--image-content l-content__block--dark-background <?= is_page_template('page-viewing-room.php') ? 'l-content__block--wide-image' : '' ?>">
   <div class="canvas l-content__block--center">
      
      <?php if( $data['video_content']) : ?>
         <button class="c-video-player__button" data-id='playBtn'>
            <svg class="c-video-player__play-icon">
               <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#shape-play" viewBox="0 0 32 32"></use>
            </svg>
         </button>
      <?php endif; ?>

      <div class="c-video-player__cover-image" style="background-image: url('<?= $data['image_content'] ?>')" data-id='cover'></div>	
      
      <?php if( $data['video_content']) : ?>
         <div class="u-video-aspect-ratio u-video-aspect-ratio--full-width">
            <?= $data['video_content'] ?>
         </div>
      <?php endif; ?>

   </div>
</figure>
