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
      'ids'  => get_field('code_id')
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

    <section class="u-section">
      <div id="app"></div>
    </section>

<?php endwhile; endif; ?>


<?php include("footer.php"); ?>