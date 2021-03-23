<?php
	/**
	 * Purchase modal
	 */
	foreach($artwork as $index => $artworks): ?>
	<div id="ListPurchaseModal_<?= $index ?>" class="modal">
		<div class="modal-content">
			<svg class="c-header__icon"><use xlink:href="#shape-hauserwirth-logo"></use></svg>
			<span class="close">&times;</span>
			<?= do_shortcode('[gravityform id="5" title="false" description="false" ajax="true" field_values="form_msg=I would like to buy ' . $artworks['fullName'] .', ' . $artworks['title'] . '. \nPlease contact me to finalize the purchase details.&id_code=' . $artworks['ids'] . '"]'); ?>
			<small>*By submitting your email address, you consent to receive our Newsletter. Your consent is revocable at any time by clicking the unsubscribe link in our Newsletter. The Newsletter is sent in accordance with our Privacy Policy and to advertise products and services of Hauser &amp; Wirth Ltd. and it's affiliated companies.</small>
		</div>
	</div>
<?php endforeach; ?>

<?php
	/**
	 * Purchase modal
	 */
	foreach($artwork as $index => $art): ?>
	<div id="purchaseModal_<?= $index ?>" class="modal">
		<div class="modal-content">
			<svg class="c-header__icon"><use xlink:href="#shape-hauserwirth-logo"></use></svg>
			<span class="close">&times;</span>
			<?= do_shortcode('[gravityform id="5" title="false" description="false" ajax="true" field_values="form_msg=I would like to buy ' . $art['fullName'] .', ' . $art['title'] . '. \nPlease contact me to finalize the purchase details.&id_code=' . $art['ids'] . '"]'); ?>
			<small>*By submitting your email address, you consent to receive our Newsletter. Your consent is revocable at any time by clicking the unsubscribe link in our Newsletter. The Newsletter is sent in accordance with our Privacy Policy and to advertise products and services of Hauser &amp; Wirth Ltd. and it's affiliated companies.</small>
		</div>
	</div>
<?php endforeach; ?>