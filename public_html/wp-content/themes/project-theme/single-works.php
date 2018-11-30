<?php

use App\Helper\Render;

$render = new Render;

include("header.php"); ?>

<?php

    if ( have_posts() ) : while ( have_posts() ) : the_post();

    $work = [
      'title' => get_the_title(),
      'image' => get_the_post_thumbnail_url(),
      'date' => get_field('date'),
      'medium' => get_field('medium'),
      'dimensions' => get_field('dimensions'),
      'price' => get_field('price'),
      'learn_more' => get_the_permalink()
    ];

    $gallery = get_field('image_gallery');
    $images = [];
    foreach ($gallery as $galleryImage) {
      $images[] = $galleryImage['url'];
    }
    ?>

    <script>
      var GALLERY = <?php echo json_encode($images) ?>;
      var WORK = <?php echo json_encode($work) ?>
    </script>

    <section class="u-section light-grey ">
      <div id="app"></div>
    </section>

<?php endwhile; endif; ?>


<?php include("footer.php"); ?>
