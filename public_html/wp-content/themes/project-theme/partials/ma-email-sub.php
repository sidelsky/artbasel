<?php if( is_front_page() ) {
	$id = 4;
	$emailSubCopy = $themeData['emailSubCopy']['details'];
	//$smallPrint = '<p class="c-ma-email-sub--small">' . $themeData['emailSubCopy']['smallPrint'] . '</p>';
} elseif( is_page('Online Exhibitions') ) {
	$id = 6;
	$emailSubCopy = 'Be the first to receive updates';
	$smallPrint = null;
} else {
	$id = 6;
	$emailSubCopy = get_field('artist_inquiry_email_description_text');
	$smallPrint = null;
} ?>


<?php if( get_field('show_artist_inquiry_email') || is_front_page() || is_page('Online Exhibitions') ) : ?>
	<section class="c-ma-email-sub u-l-horizontal-padding u-l-vertical-padding--x-small">
		<div class="c-ma-email-sub__wrapper">
			<div class="c-ma-email-sub__column">
					<span><?= $emailSubCopy ?></span>
			</div>
			<div class="c-ma-email-sub__column">
					<?php echo do_shortcode('[gravityform id="' . $id . '" title="false" description="false"]'); ?>
			</div>
		</div>
		<?/*= $smallPrint */?>
	</section>
<?php endif; ?>