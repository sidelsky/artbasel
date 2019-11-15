<?php

    use App\Helper\Render;

    $render = new Render;

    include("header.php");

?>


<script>

<?php

    $loop = new WP_Query(
        [
          'post_type' => 'works',
          'posts_per_page' => -1,
          'meta_key' => 'surname',
          'orderby' => 'meta_value',
          'order' => 'ASC'
        ]
     );

    $artwork = [];

    while ( $loop->have_posts() ) : $loop->the_post();

    // $title = get_the_title( $this->get_the_ID() );
    // $title = esc_html( strip_tags( $title ) ); //ADD - strip tags before sending to template

    // return $title;

      $artwork[] = [
        'id' => get_the_id(),
        'title' => get_the_title(),
        'subPostTitle' => get_field('sub_post_title'),
        'surname' => get_field('surname'),
        'image' => get_the_post_thumbnail_url(),
        'date' => get_field('date'),
        'description' => get_field('description'),
        'medium' => get_field('medium'),
        'mediumText' => get_field('medium_free_text'),
        'mediumChinese' => get_field('medium_chinese'),
        'decade' => get_field('decade'),
        'dimensions' => get_field('dimensions'),
        'price' => get_field('price'),
        'priceRange' => get_field('price_range'),
        'link' => get_the_permalink(),
        'sold' => get_field('sold')
      ];

    endwhile;

    echo 'var WORKS = ' . json_encode($artwork) . ';';

    function createFilters($array) {
      $newArray = [
        [
          'label' => 'All',
          'value' => 'all'
        ]
      ];

      foreach ($array as $key => $value) {
        $newArray[] = [
          'label' => $value,
          'value' => $key
        ];
      }

      return $newArray;
    };

    $priceRange = createFilters(get_field_object('price_range')['choices']);
    $medium = createFilters(get_field_object('medium')['choices']);
    $decade = createFilters(get_field_object('decade')['choices']);

    $filters = [
      'priceRange' => $priceRange,
      'medium' => $medium,
      'decade' => $decade
    ];

    echo 'var FILTERS = ' . json_encode($filters);
?>
		
</script>


<?php 

$gallery = get_field('image_gallery', 6);
$images = [];

if($gallery) : ?>
	<section class="u-section" id="carousel">
		<div class="zu-l-container--center" data-in-viewport>
      <div class="zu-l-container zu-l-container--row">

        <?php foreach ($gallery as $galleryImage) :  ?>
          <div class="parallax-window" data-parallax="scroll" data-image-src="<?php echo $galleryImage['sizes']['large']; ?>"></div>
        <?php endforeach; ?>
    
			</div>
		</div>
	</section>
<?php endif; ?>
		  
 
<section class="u-section ">
  <div id="app">
  </div>
</section>

<?php include("footer.php"); ?>