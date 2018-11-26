<?php
use App\Helper\WordpressHelper;
$wordpress = new WordpressHelper;
?>

</main>

	<footer class="u-section c-footer">

		<div class="u-l-container">		
			<p class="c-footer__copyright"><?php echo $themeData['copyright']['details'];?> &nbsp; | &nbsp; <a href="https://www.hauserwirth.com/terms-and-conditions" target="_blank">Terms &amp; Conditions</a></p>
		</div>

	</footer>

</div>

		<?php 
		wp_footer(); 

		// $googleAnalyticID = get_field('google_analytics', 'option');
		
		// //Google Analytics
		// $template = 'c-google-analytics';
		// $data = [
		// 	'UA' => $googleAnalyticID
		// ];

		// echo $render->view('Components/' . $template, $data);

		?>

		<!-- 
			Chat 
			<div class="chat">
				<div class="chat__button"></div>
				<div class="chat__modal"></div>
			</div>
		-->
		
	</body>
</html>
