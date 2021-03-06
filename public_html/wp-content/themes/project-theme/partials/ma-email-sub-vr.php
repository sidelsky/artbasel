<?php
   if( is_page('june-art-fair') ) {
      $emailSubCopy = 'Subscribe to receive updates from June Art Fair<br><small style="font-size: 11px; line-height: 1.6; display: inline-block;">*By submitting your email address, you are signing up to June Art Fair’s Newsletter and consent to their <a href="/june-art-fair-privacy-policy/" style="color: currentColor; text-decoration:underline;">Privacy Policy</a>.</small>';
      $id = 14;
   } else {
      $id = 6;
      $emailSubCopy = get_field('artist_inquiry_email_description_text');
   }
   
   $smallPrint = null;
?>
<section class="c-ma-email-sub u-l-horizontal-padding u-l-vertical-padding--tiny">
   <div class="c-ma-email-sub__wrapper">
      <div class="c-ma-email-sub__column">
            <p><?= $emailSubCopy ?></p>
      </div>
      <div class="c-ma-email-sub__column">
            <?php echo do_shortcode('[gravityform id="' . $id . '" title="false" description="false"]'); ?>
      </div>
   </div>
</section>