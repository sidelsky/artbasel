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
			<h3 class="c-site-headings c-site-headings--h3 c-text-align-centre grey-copy">Enter Password to View</h3>
			<?php
				//$blog_name = get_bloginfo();
				//Lets check to see if the 'Theme my login plugin is active...
				if( function_exists( 'theme_my_login' ) ) {
					echo do_shortcode( '[theme-my-login show_title=0]' );
				}

			?>
				<div class="c-login__footer">
					<a href="mailto:passwordrequest@hauserwirth.com?subject=Password request — Art Basel Miami Beach 2018&body=Hello, I would like a password to enter the online viewing room for Art Basel Miami Beach 2018" class="c-login__href">Request a Password</a>
					<p class="c-login__detail">Please note: By submitting a password request you are consenting to join the Hauser &amp; Wirth mailing list.</p>
				</div>
		</div>
	</div>
</section>

<?php include("footer.php"); ?>
