<?php
use App\Helper\Render;
$render = new Render;
include("header.php"); ?>

<?php
if ( have_posts() ) : while ( have_posts() ) : the_post();
    $work = [
      'key' => get_the_id(),
      'title' => get_the_title(),
      'content' => get_the_content(),
      'subPostTitle' => get_field('sub_post_title'),
      'image' => get_the_post_thumbnail_url(),
      'date' => get_field('date'),
      'fullName' => get_field('full_name'),
      'medium' => get_field('medium'),
      'mediumText' => get_field('medium_free_text'),
      'mediumChinese' => get_field('medium_chinese'),
      'dimensions' => get_field('dimensions'),
      'price' => get_field('price'),
      'learn_more' => get_the_permalink(),
      'sold' => get_field('sold'),
      'video' => get_field('video'),
      'ids'  => get_field('code_id'),
      'hidePurchaseButton' => get_field('hide_purchase_button')
    ];
    $gallery = get_field('image_gallery');
    $images = [];
    foreach ($gallery as $galleryImage) {
      $images[] = $galleryImage['sizes']['large'];
    }
    ?>

    <script>
      var GALLERY = <?php echo json_encode($images) ?>;
      var WORK = <?php echo json_encode($work) ?>;
	</script>
	
	<?php
	/**
	 * Purchase modal
	 */
		echo '<div id="purchaseModal_0" class="modal">';
			echo '<div class="modal-content">';
				echo '<svg class="c-header__icon"><use xlink:href="#shape-hauserwirth-logo"></use></svg>';
				echo '<span class="close closep">&times;</span>';
				echo do_shortcode('[gravityform id="5" title="false" description="false" ajax="true" field_values="form_msg=I would like to buy ' . $work["fullName"] .', ' . $work["title"] . '. \nPlease contact me to finalize the purchase details.&id_code=' . $work["ids"] . '"]');
				echo '<small> *By submiting your email address, you consent to receive our Newsleter. Your consent is revocable at any time by clicking the unsubscribe link in our Newsleter. The Newsletter is sent in accordance with our Privacy Policy and to advertise products and services of Hauser &amp; Wirth Ltd. and it\'s afiliated companies.</small>';
				echo '</div>';
		echo '</div>';
	?>
 
	<?php
	/**
	 * Inquire modal
	 */
		echo '<div id="inquireModal_0" class="modal">';
			echo '<div class="modal-content">';
				echo '<svg class="c-header__icon"><use xlink:href="#shape-hauserwirth-logo"></use></svg>';
				echo '<span class="close closei">&times;</span>';
				echo do_shortcode('[gravityform id="5" title="false" description="false" ajax="true" field_values="form_msg=I am interested in learning more about ' . $work["fullName"] .', ' . $work["title"] . '. \nPlease send me further details about this artwork, pricing, and availability.&id_code=' . $work["ids"] . '"]');
				echo '<small> *By submiting your email address, you consent to receive our Newsleter. Your consent is revocable at any time by clicking the unsubscribe link in our Newsleter. The Newsletter is sent in accordance with our Privacy Policy and to advertise products and services of Hauser &amp; Wirth Ltd. and it\'s afiliated companies.</small>';
				echo '</div>';
		echo '</div>';
	?>

    <section class="u-section">
      <div id="app"></div>
    </section>

<?php endwhile; endif; ?>


<?php include("footer.php"); ?>