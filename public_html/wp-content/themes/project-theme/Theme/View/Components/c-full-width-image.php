<?php if( $data['full_width_image']['url'] ) { ?>
   <section class="u-section">
      <div class="<?= $args['fullWidth'] ? 'u-l-container--full-width' : 'u-l-container u-l-container--padding-full-width'?>">
         <figure role="img" aria-label="<?= esc_attr( $data['full_width_image']['alt'] ); ?>" class="l-content__block l-content__block__full-width-image" style="background-image: url('<?= $data['full_width_image']['url'] ?>'); <?= $data['full_width_image']['caption'] ? 'margin-bottom: 70px' : '' ?>">
         
         <?php if( $data['full_width_image_title'] || $data['full_width_image_link'] ) { ?>
            <div class="l-content__block__full-width-image-container">
                  
                  <?php if( $data['full_width_image_title'] ) { ?>
                     <h2 class="l-content__block__full-width-image-title <?= $data['full_width_image_link'] ? 'l-content__block__full-width-image-title--margin-bottom' : '' ?>"><?= $data['full_width_image_title'] ?></h2>
                  <?php } ?>
                  
                  <?php if( $data['full_width_image_link'] ) { ?>
                     <div class="c-works__href-wrap c-works__href-wrap--center">
                        <span class="c-works__href-wrap c-works__href-wrap--center l-content__block--link">
                           <a href="<?= $data['full_width_image_link'] ?>" class="c-works__href c-works__href--no-arrow">Explore now</a> 
                        </span>
                     </div>
                  <?php } ?>

            </div>
            <div class="l-content__block__full-width-image-shading"></div>
         <?php } ?>

      <?php
      /**
       * If caption
       */
      if($data['full_width_image']['caption']) {?>
         <span class="caption"><?= esc_attr( $data['full_width_image']['caption'] ); ?></span>
      <?php } ?>

      </figure>
      
   </div>
   </section>
<?php } ?> 