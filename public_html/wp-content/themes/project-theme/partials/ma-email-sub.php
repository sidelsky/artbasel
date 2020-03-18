<section class="c-ma-email-sub u-l-horizontal-padding--large u-l-vertical-padding--tiny">
    <div class="c-ma-email-sub__wrapper">
        <div class="c-ma-email-sub__column">
            <span><?= $themeData['emailSubCopy']['details'];?></span>
        </div>
        <div class="c-ma-email-sub__column">
            <?php echo do_shortcode('[gravityform id="4" title="false" description="false"]'); ?>
        </div>
    </div>
    <p class="c-ma-email-sub--small"><?= $themeData['emailSubCopy']['smallPrint'];?></p>
</section>