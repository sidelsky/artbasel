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