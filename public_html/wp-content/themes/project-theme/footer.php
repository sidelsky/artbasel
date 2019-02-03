<?php
use App\Helper\WordpressHelper;
$wordpress = new WordpressHelper;
?>

</main>

	<footer class="u-section c-footer">

		<div class="u-l-container">		
			<p class="c-footer__copyright"><?php echo $themeData['copyright']['details'];?> &nbsp; | &nbsp; <a href="https://www.hauserwirth.com/terms-and-conditions" target="_blank">Terms &amp; Conditions</a><br><small>The artworks described above are subject to changes in availability and price without prior notice. Prices excl. VAT</small></p>
		</div>

	</footer>

</div>
		<script src="https://pawelgrzybek.com/siema/assets/siema.min.js"></script>
		<script>
			new Siema();
		</script>
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
