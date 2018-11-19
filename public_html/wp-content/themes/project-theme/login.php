<?php
/**
 * Template Name: Login - page template
 */
 
	$url = site_url();
	
    if ( is_user_logged_in() ) {
        wp_redirect( $url . '../../works' );
        exit;
    } 
 
    include("header.php");
 
?>

<section class="u-section c-login">
	<div class="u-l-container--center" data-in-viewport>
		<div class="u-l-container u-l-container--row u-l-vertical-padding">
			<h3 class="c-site-headings c-site-headings--h3 c-text-align-centre">Enter Password to View</h3>
		<?php
				//$blog_name = get_bloginfo();
				//Lets check to see if the 'Theme my login plugin is active...
				if( function_exists( 'theme_my_login' ) ) {
					echo do_shortcode( '[theme-my-login show_title=0]' );
				}
			?>
		</div>
	</div>
</section>



<?php include("footer.php"); ?>