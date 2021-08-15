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

<?php include("partials/artbasel-footer-details.php"); ?>

</div>

<?php wp_footer(); ?>
<!-- sticky header scroll -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
 




<!-- page animations -->
<link rel="stylesheet" href="/wp-content/themes/project-theme/assets/build/animate.css" media="screen" />

<script type="text/javascript" src="/wp-content/themes/project-theme/assets/build/wow.min.js"></script>
<script type="text/javascript">// <![CDATA[
 new WOW().init();
// ]]></script>
	</body>
</html>
