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

<footer class="u-section c-footer">

<div class="c-footer__inner">
	
	<div class="c-footer__container c-footer__column">
		<p class="c-footer__copyright"><?php echo $themeData['copyright']['details'];?> &nbsp; | &nbsp; <a href="https://www.vip-hauserwirth.com/site-terms-of-use/" target="_blank">Terms &amp; Conditions</a><br><small>The artworks described above are subject to changes in availability and price without prior notice. Prices excl. VAT</small></p>
	</div>

	<div class="c-footer__column">
		<div class="social ">
		<a href="https://www.facebook.com/hauserwirth/" class="social__link" target="_blank">
			<svg class="social__icon">
				<use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#shape-facebook" viewBox="0 0 32 32"></use>
			</svg>
			<span class="sr-only">Facebook</span>
		</a>
		<a href="https://www.instagram.com/hauserwirth/?hl=en" class="social__link" target="_blank">
			<svg class="social__icon">
				<use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#shape-instagram" viewBox="0 0 32 32"></use>
			</svg>
			<span class="sr-only">Instagram</span>
		</a>
		<a href="https://twitter.com/hauserwirth" class="social__link" target="_blank">
			<svg class="social__icon">
				<use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#shape-twitter" viewBox="0 0 32 32"></use>
			</svg>
			<span class="sr-only">Twitter</span>
		</a>
		<a href="https://weibo.com/hauserwirth?is_hot=1" class="social__link" target="_blank">
			<svg class="social__icon">
				<use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#shape-weibo" viewBox="0 0 32 32"></use>
			</svg>
			<span class="sr-only">Weibo</span>
		</a>
		<a href="https://www.youtube.com/channel/UCmwkuoCJOD681XWL_3joE5w" class="social__link" target="_blank">
			<svg class="social__icon">
				<use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#shape-youtube" viewBox="0 0 32 32"></use>
			</svg>
			<span class="sr-only">Youtube</span>
		</a>
		<a href="#" class="social__link" target="_blank" id="weChatBtn">
			<svg class="social__icon">
				<use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#shape-wechat" viewBox="0 0 32 32"></use>
			</svg>
			<span class="sr-only">WeChat</span>
		</a>
	</div>
	</div>
	</div>
</footer>

</div>

		<?php 
			wp_footer(); 
			$googleAnalyticID = get_field('google_analytics', 'option');
			
			//Google Analytics
			$template = 'c-google-analytics';
			$data = [
				'UA' => $googleAnalyticID
			];
			echo $render->view('Components/' . $template, $data);
		
		?>
		


	</body>
</html>