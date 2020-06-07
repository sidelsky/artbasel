<a href="<?= $url ?>" class="<?= $isFooterNavigation ? 'c-online-exhibitions__block--navigation' : 'c-online-exhibitions__href-wrap' ?> <?= $isCarousel ? 'c-online-exhibitions__block--small' : 'c-online-exhibitions__block' ?> c-online-exhibitions__card" style="background-image: url('<?= $FeaturedThumbnail[0] ? $FeaturedThumbnail[0] : $thumbnail ?>'); display: block">
   <div class="canvas">
      <div class="c-online-exhibitions__content <?= $altFont ? 'c-online-exhibitions--alt-font' : ''?>">
         <h3 class="c-online-exhibitions__artist">
            <?php if($artwork_title) { ?>
               <span class="c-online-exhibitions__artwork_title"><?= $artwork_title ?></span><br>
            <?php } ?>
            <span class="c-online-exhibitions__title"><?= $title ?></span>
         </h3>
            <span class="c-online-exhibitions__href">
               Explore now 
               <svg class="u-icon c-works__icon"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#shape-link-arrow-white" viewBox="0 0 32 32"></use></svg>
            </span>
      </div>
   </div>
</a>