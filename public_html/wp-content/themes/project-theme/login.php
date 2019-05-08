<?php
/**
 * Template Name: Login - page template
 */

	// $url = site_url();

    // if ( is_user_logged_in() ) {
    //     wp_redirect( $url . '/works' );
    //     exit;
    // }

    include("header.php");

?>

<section class="u-section c-login">
	<div class="u-l-container--center" data-in-viewport>
		<div class="u-l-container u-l-container--row u-l-vertical-padding">
			<h3 class="c-site-headings c-site-headings--h3 c-text-align-centre grey-copy">Enter email to view<br></h3>
			<?php
				//$blog_name = get_bloginfo();
				//Lets check to see if the 'Theme my login plugin is active...
				if( function_exists( 'theme_my_login' ) ) {
					echo do_shortcode( '[theme-my-login show_title=0]' );
				}

			?>
			<div class="tml tml-login" id="theme-my-login1">
				<!-- Begin Mailchimp Signup Form -->
				<?php /*
				
				<div id="mc_embed_signup">
					<form class="sign-up">

						<!-- <label for="mce-EMAIL">Email Address </label>
						<input type="email" value="" name="EMAIL" class="required email input" id="mce-EMAIL" placeholder="your@email-address.com"> -->
<!-- 
						<label for="mce-FNAME">First Name </label>
						<input type="text" value="" name="FNAME" class="required" id="mce-FNAME"> -->

						<input type="email" value="" name="EMAIL" class="required email input" id="mce-EMAIL" placeholder="example@email.com">

						<input type="hidden" value="Yes" name="WPRESS">
						<input class="button btn c-button" type="submit" value="Submit">

					</form>
				</div>
				

				<div id="mc_embed_signup">
					<form action="https://hauserwirth.us14.list-manage.com/subscribe/post?u=4f151f95a189c32d4d5205374&amp;id=84804d8fbd" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate loginform1" novalidate>
						<div id="mc_embed_signup_scroll">
							<div class="mc-field-group">
							<p class="tml-user-pass-wrap">
								<input type="email" value="" name="EMAIL" class="required email input" id="mce-EMAIL" placeholder="Email address">
							</p>
							</div>
							<div id="mce-responses" class="clear">
								<div class="response" id="mce-error-response" style="display:none"></div>
								<div class="response" id="mce-success-response" style="display:none"></div>
							</div>    <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
							<div style="position: absolute; left: -5000px;" aria-hidden="true"><input type="text" name="b_4f151f95a189c32d4d5205374_84804d8fbd" tabindex="-1" value=""></div>
							<div class="clear"><input type="submit" value="Submit" name="subscribe" id="mc-embedded-subscribe" class="button c-button"></div>
						</div>
					</form>
					*/ ?>

					<!--G form -->
					<?php echo do_shortcode('[gravityform id="4" title="false" description="false"]'); ?>
					<p class="c-footer"><small>By submitting your email address, you consent to receive our Newsletter. Your consent is revocable at any time by clicking the unsubscribe link in our Newsletter. The Newsletter is sent in accordance with our <a href="https://www.hauserwirth.com/terms-and-conditions" target="_blank">Privacy Policy</a> and to advertise products and services of Hauser & Wirth Ltd. and its affiliated companies.<small></p>
				</div>
				
				<!--End mc_embed_signup-->
			</div>
			

			<?php/*
				<div class="c-login__footer">
					<a href="mailto:passwordrequest@hauserwirth.com?subject=Password request - Online Viewing Room&body=Hello, I would like a password to enter the Online Viewing Room" class="c-login__href">Request a Password</a>
					<p class="c-login__detail">Please note: By submitting a password request you are consenting to join the Hauser &amp; Wirth mailing list.</p>
				</div>
				*/?>
		</div>
	</div>
</section>


<?php include("footer.php"); ?>

