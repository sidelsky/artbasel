<?php
use App\Helper\WordpressHelper;
$wordpress = new WordpressHelper;
?>

</main>


<!-- The Modal -->
<div id="weChatModal" class="modal wechat__modal">
	<!-- Modal content -->
	<div class="modal-content wechat__content">
		<span class="close" id="close">&times;</span>
		<p class="wechat__title">Follow us on WeChat</p>
		<img src="https://www.hauserwirth.com/images/wechat.jpg" alt="WeChat">
		<small>Scan the image to begin</small>
	</div>
</div>

<?php include("partials/footer-details.php"); ?>

</div>


<div class="modal" data-id="mltp-inquire-modal">
	<div class="modal-content">
		<svg class="c-header__icon"><use xlink:href="#shape-hauserwirth-logo"></use></svg>
		<span class="close closei">&times;</span>
		<?= do_shortcode('[gravityform id="7" title="false" description="false" ajax="true" field_values="form_msg=I am interested in learning more about ' . $work["fullName"] .', ' . $work["title"] . '. \nPlease send me further details about this artwork, pricing, and availability.&id_code=' . $work["ids"] . '"]'); ?>
		<small> *By submiting your email address, you consent to receive our Newsleter. Your consent is revocable at any time by clicking the unsubscribe link in our Newsleter. The Newsletter is sent in accordance with our Privacy Policy and to advertise products and services of Hauser &amp; Wirth Ltd. and it\'s afiliated companies.</small>
	</div>
</div>

<?php wp_footer(); ?>
		
	</body>
</html>