<?php
use App\Helper\WordpressHelper;
$wordpress = new WordpressHelper;
?>

</main>

<footer class="u-section c-footer">
	<div class="u-l-container">
		<p class="c-footer__copyright"><?php echo $themeData['copyright']['details'];?> &nbsp; | &nbsp; <a href="https://www.vip-hauserwirth.com/site-terms-of-use/" target="_blank">Terms &amp; Conditions</a><br><small>The artworks described above are subject to changes in availability and price without prior notice. Prices excl. VAT</small></p>
	</div>
</footer>

</div>

		<script src="https://cdn.jsdelivr.net/parallax.js/1.4.2/parallax.min.js"></script>

		<script>
		$(document).ready(function(){
		// Add smooth scrolling to all links
		$("a").on('click', function(event) {

			// Make sure this.hash has a value before overriding default behavior
			if (this.hash !== "") {
			// Prevent default anchor click behavior
			event.preventDefault();

			// Store hash
			var hash = this.hash;

			// Using jQuery's animate() method to add smooth page scroll
			// The optional number (800) specifies the number of milliseconds it takes to scroll to the specified area
			$('html, body').animate({
				scrollTop: $(hash).offset().top
			}, 800, function(){
		
				// Add hash (#) to URL when done scrolling (default click behavior)
				window.location.hash = hash;
			});
			} // End if
		});
		});
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