<?php if($href) : ?>
   <a href="<?= $href ?>" class="<?= $hrefClass ?>" <?= $hrefDataAtts ?> >
<?php endif; ?>

   <svg class="b_icon <?= $className ?>">
      <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-<?= $iconName ?>" viewBox="0 0 32 32"></use>
   </svg>
   
<?php if($href) : ?>
   </a>
<?php endif; ?>