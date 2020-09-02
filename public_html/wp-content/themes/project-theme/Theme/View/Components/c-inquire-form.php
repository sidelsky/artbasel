<div id="inquireModal" class="modal" data-id="mltp-inquire-modal">
	<div class="modal-content">
		<svg class="c-header__icon"><use xlink:href="#shape-hauserwirth-logo"></use></svg>
		<span class="close" id="closeBtn">&times;</span>
		hello
		<?= do_shortcode('[gravityform id="' . $args['id'] . '" title="' . $args['title'] . '" description="' . $args['description'] . '" ajax="true"]'); ?>
		<small>*By submiting your email address, you consent to receive our Newsleter. Your consent is revocable at any time by clicking the unsubscribe link in our Newsleter. The Newsletter is sent in accordance with our Privacy Policy and to advertise products and services of Hauser &amp; Wirth Ltd. and it\'s afiliated companies.</small>
		</div>
</div>