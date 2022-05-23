<?php
use App\Helper\WordpressHelper;
$wordpress = new WordpressHelper;
?>

</main>

<script type='text/javascript' src='https://www.vip-hauserwirth.com/wp-content/themes/project-theme/assets/build/app.js' id='app-js'></script>

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

<?php wp_footer(); ?>

	</body>
</html>
