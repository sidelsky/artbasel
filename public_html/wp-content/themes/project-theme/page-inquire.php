<?php
/**
 * Template Name: Inquire
 */
	use App\Helper\Render;
	$render = new Render;
	include("header.php");
?>


<?php
/**
 * Inquire modal
 */
   $inquire_val = 'inquire_val';
   echo '<div id="inquireModal_0" class="modal" style="display:block">';
      echo '<div class="modal-content">';
         echo '<svg class="c-header__icon"><use xlink:href="#shape-hauserwirth-logo"></use></svg>';
         echo '<a href="javascript:history.go(-1);" class="close closei">&times;</a>';
         echo do_shortcode('[gravityform id="7" title="false" description="false" ajax="true" field_values="form_msg"]');
         echo '<small> *By submiting your email address, you consent to receive our Newsleter. Your consent is revocable at any time by clicking the unsubscribe link in our Newsleter. The Newsletter is sent in accordance with our Privacy Policy and to advertise products and services of Hauser &amp; Wirth Ltd. and it\'s afiliated companies.</small>';
         echo '</div>';
   echo '</div>';
?>


<?php include("footer.php"); ?>

