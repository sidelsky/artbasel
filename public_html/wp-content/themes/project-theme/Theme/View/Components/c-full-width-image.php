<?php if( $data['full_width_image'] ) { ?>
   <section class="u-section <?= !is_page('art-fairs') ? 'u-l-vertical-padding--medium40' : '' ?>" <?= is_page('art-fairs') ? 'style="margin:30px 0"' : '' ?>>
      <div class="<?= $args['fullWidth'] ? 'u-l-container--full-width' : 'u-l-container u-l-container--padding-full-width'?>">
         <!-- <figure role="img" aria-label="<?= esc_attr( $data['full_width_image']['alt'] ); ?>" class="l-content__block c-full-width-image" style="background-image: url('<?= $data['full_width_image']['url'] ?>'); "> -->
          <figure role="img" aria-label="<?= esc_attr( $data['full_width_image']['alt'] ); ?>" class="l-content__block c-full-width-image" style="background-image: url('<?= $data['full_width_image']['url'] ?>');">
         
         <?php if( $data['full_width_image_title'] || $data['full_width_image_link'] ) { ?>
            <div class="c-full-width-image-container">
                  
                  <?php if( $data['full_width_image_title'] ) { ?>
                     <h2 class="c-full-width-image-title <?= $data['full_width_image_link'] ? 'c-full-width-image-title--margin-bottom' : '' ?>"><?= $data['full_width_image_title'] ?></h2>
                  <?php } ?>
                  
                  <?php if( $data['full_width_image_link'] ) { ?>
                     <div class="c-works__href-wrap c-works__href-wrap--center">
                        <span class="c-works__href-wrap c-works__href-wrap--center l-content__block--link">
                           <a href="<?= $data['full_width_image_link'] ?>" class="c-works__href c-works__href--no-arrow">Explore now</a> 
                        </span>
                     </div>
                  <?php } ?>

            </div>
            <div class="c-full-width-image-shading"></div>
         <?php } ?>

      </figure>

      <?php
      /**
       * If caption
       */
      if($data['full_width_image']['caption']) {?>
         <figcaption class="caption"><?= esc_attr( $data['full_width_image']['caption'] ); ?></figcaption>
      <?php } ?>
      
   </div>
   </section>
<?php } ?> 