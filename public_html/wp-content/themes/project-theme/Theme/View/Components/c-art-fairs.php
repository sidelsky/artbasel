<div class="c-online-exhibitions">

     <?php $postContent = $data['art_fairs']; ?>

      <?php foreach( $postContent as $content ) :
         $rows = get_field('current_viewing_rooms', $content->ID);
			//$thumbnail = $rows[0]['current_viewing_room_image']['sizes']['medium'];
         $title = get_the_title($content->ID);
         $url = get_the_permalink($content->ID);
         $thumbnail = get_the_post_thumbnail_url($content->ID);
         $FeaturedThumbnail = wp_get_attachment_image_src( get_post_thumbnail_id( $content->ID ), 'medium');
      ?>
         <a href="<?= $url ?>" class="c-online-exhibitions__block--navigation c-online-exhibitions__block c-online-exhibitions__card" style="background-image: url('<?= $FeaturedThumbnail[0] ? $FeaturedThumbnail[0] : $thumbnail ?>'); display: block">
            <div class="canvas">
               <div class="c-online-exhibitions__content">
                  <h3 class="c-online-exhibitions__artist">
                     <span class="c-online-exhibitions__title"><?= $title ?></span>
                  </h3>
                     <span class="c-online-exhibitions__href">
                        Explore now 
                        <svg class="u-icon c-works__icon"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#shape-link-arrow-white" viewBox="0 0 32 32"></use></svg>
                     </span>
               </div>
            </div>
         </a>

      <?php endforeach; ?>

   </div>
