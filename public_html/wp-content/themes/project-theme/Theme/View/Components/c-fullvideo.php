<?php if( $data['fullvideo'] ) : ?>
asd
  <div class="u-l-container--shallow u-l-horizontal-padding--medium l-content__block__text-content--wide ">
    <div class="l-content__block__text-content l-content__block__body-text" id="anchor-top">

         <button class="c-video-player__button" data-id='playBtn'>
            <svg class="c-video-player__play-icon">
               <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#shape-play" viewBox="0 0 32 32"></use>
            </svg>
         </button>
         <figure class="c-video-player__cover-image" role="img" aria-label="<?= esc_attr( $data['coverimage']['alt'] ); ?>" style="background-image: url('<?= $data['coverimage']['url'] ?>')" data-id='cover'></figure>
         <div data-id="vimeo-content" class="u-video-aspect-ratio">
            <?= $data['video'] ?>
         </div>
      <?php endif; ?>
         <h2 class="l-content__block__title" >
           <?= $data['videotitle'] ?>
         </h2>
         <p>
           <?= $data['videotitle'] ?>
         </p>
       </div>
       </div>

<?php endif; ?>
