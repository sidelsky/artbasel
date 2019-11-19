<?php
/**
 * Template Name: Login email
 */
    include("header.php");
?>

<section class="u-section c-login c-login--background-dark">
	<div class="u-l-container--center" data-in-viewport>
		<div class="u-l-container u-l-container--row u-l-vertical-padding">
			<h3 class="c-site-headings c-site-headings--h3 c-text-align-centre grey-copy">Enter email to view<br></h3>
				<div class="tml tml-login" id="theme-my-login1">
					<?php echo do_shortcode('[gravityform id="4" title="false" description="false"]'); ?>
					<p class="c-footer"><small>By submiting your email, you agree to receive our marketing communications.</small></p>
				</div>
			</div>
		</div>
	</div>
</section>


<?php include("footer.php"); ?>