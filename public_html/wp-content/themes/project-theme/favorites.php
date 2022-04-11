<?php
/**
 * Template Name: Favorites
 */

include("header-artbasel.php");
?>

<!-- hero -->
<div class="favorite-contain">
	 <div class="wrapper-favorites">
	 	<?php echo do_shortcode('[yith_wcwl_wishlist]'); ?>
	</div>
</div>

<?php
/**
 * Email submission
 */
include("partials/artbasel-email-sub.php"); ?>


<?php
/**
 * Footer
 */
include("footer-artbasel.php"); ?>
