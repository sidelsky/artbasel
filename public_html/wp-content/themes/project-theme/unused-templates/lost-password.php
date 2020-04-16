<?php
/*
	Template Name: Lost password - page template
*/

include("header.php");

?>

	<section role="main">

		<div class="login">
			<div class="login-canvas">

				<?php include('partials/mediastage-logo.php'); ?>

				<?php echo do_shortcode('[theme-my-login show_title=0 default_action="lostpassword" lostpassword_template="tml-lostpassword-form.php"]'); ?>

			</div>
		</div>

	</section>

<?php include("footer.php"); ?>
