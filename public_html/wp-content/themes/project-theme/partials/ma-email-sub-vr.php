<?php
   $id = 6;
   $emailSubCopy = get_field('artist_inquiry_email_description_text');
   $smallPrint = null;
?>
<section class="c-ma-email-sub u-l-horizontal-padding u-l-vertical-padding--tiny">
   <div class="c-ma-email-sub__wrapper">
      <div class="c-ma-email-sub__column">
            <span><?= $emailSubCopy ?></span>
      </div>
      <div class="c-ma-email-sub__column">
            <?php echo do_shortcode('[gravityform id="' . $id . '" title="false" description="false"]'); ?>
      </div>
   </div>
</section>